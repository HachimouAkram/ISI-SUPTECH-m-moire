<?php
namespace App\Http\Controllers;

use App\Services\OrangeMoneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paiement;      // Modèle pour enregistrer les paiements validés
use App\Models\Inscription;   // Modèle pour récupérer les inscriptions de l'utilisateur
use App\Models\Recu;          // Modèle pour générer les reçus PDF
use Barryvdh\DomPDF\Facade\Pdf; // Pour générer le PDF du reçu
use Illuminate\Support\Facades\Storage; // Pour stocker les PDF
use Illuminate\Support\Facades\Log;     // Pour journaliser les erreurs et événements
use Illuminate\Support\Facades\Mail;    // Pour envoyer le reçu par mail
use App\Mail\RecuPaiementMail;          // Mail personnalisé pour le reçu

class PaiementOrangeController extends Controller
{
    // Service OrangeMoney qui contient les méthodes pour créer un paiement et vérifier les callbacks
    protected OrangeMoneyService $orange;

    // Injection du service via le constructeur
    public function __construct(OrangeMoneyService $orange)
    {
        $this->orange = $orange;
    }

    /**
     * Affiche le formulaire de paiement Orange Money pour l'utilisateur.
     * - $type : 'Inscription' ou 'Mensualité'
     */
    public function showOrangeForm(string $type)
    {
        /** @var \App\Models\User $user */
        // Récupération de l'utilisateur connecté
        $user = Auth::user();

        // Récupérer la dernière inscription validée de l'utilisateur
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

        // Si aucune inscription n'est trouvée, on redirige avec un message d'erreur
        if (!$inscription) return redirect()->route('paiement.choix')->with('error','Aucune inscription');

        // Récupération des informations de la formation associée
        $formation = $inscription->classe;

        // Détermination du montant exact à payer selon le type de paiement
        $montant = $type === 'Inscription' ? $formation->prix_inscription : $formation->prix_mensuel;

        // Retourner la vue avec les informations nécessaires
        return view('pages.admin.paiement.orange_form', compact('type','formation','montant','inscription'));
    }

    /**
     * Crée un paiement côté Orange Money
     * - Valide que le téléphone est correct
     * - Génère une référence unique
     * - Appelle le service OrangeMoney pour initier le paiement
     */
    public function createPayment(Request $request, string $type)
    {
        // Validation du téléphone
        $request->validate(['phone'=>'required|string|min:8|max:15']);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $inscription = $user->inscriptions()->where('statut','Valider')->latest()->first();
        if (!$inscription) return redirect()->route('paiement.choix')->with('error','Aucune inscription');

        // Montant exact à payer (ne peut pas être choisi par l'utilisateur)
        $montant = $type === 'Inscription' ? $inscription->classe->prix_inscription : $inscription->classe->prix_mensuel;
        $phone = $request->input('phone');

        // Référence unique pour identifier ce paiement dans les callbacks
        $reference = $user->id . '_' . $inscription->id . '_' . $type . '_' . time();

        try {
            // Appel au service OrangeMoney pour créer le paiement
            $response = $this->orange->createPayment($montant, $phone, $reference);

            // Si l'API Orange retourne une URL de paiement, rediriger l'utilisateur
            if (!empty($response['paymentUrl'])) {
                return redirect()->away($response['paymentUrl']);
            }

            // Sinon, on attend le callback pour confirmer le paiement
            return redirect()->route('paiement.confirmation', ['reference'=>$reference])
                ->with('success','Paiement initié — en attente de confirmation.');
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans le log et rediriger avec un message
            Log::error('Erreur createPayment', ['msg'=>$e->getMessage()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Callback qu'Orange appelle après le paiement.
     * Cette méthode gère :
     * - La vérification de la signature (sécurité)
     * - La vérification du montant exact
     * - L'enregistrement du paiement valide
     * - La génération du reçu PDF et l'envoi par mail
     */
    public function callback(Request $request)
    {
        $raw = $request->getContent(); // Contenu brut du callback
        $signature = $request->header('X-Signature') ?? $request->header('x-om-signature') ?? null;

        // Vérification de la signature pour s'assurer que c'est bien Orange qui appelle
        if (!$this->orange->verifyCallbackSignature($raw, $signature)) {
            Log::warning('Orange callback signature invalid', ['headers'=>$request->headers->all()]);
            return response()->json(['error'=>'Signature invalide'], 403);
        }

        // Décoder le JSON reçu
        $data = $request->json()->all();
        $status = $data['status'] ?? ($data['transactionStatus'] ?? null);
        $reference = $data['externalId'] ?? $data['reference'] ?? null;
        $amount = $data['amount'] ?? null;

        if (!$reference) {
            Log::error('Callback missing reference', ['body'=>$data]);
            return response()->json(['error'=>'Référence manquante'], 400);
        }

        // Extraire les informations de la référence
        $parts = explode('_', $reference);
        $userId = $parts[0] ?? null;
        $inscriptionId = $parts[1] ?? null;
        $type = $parts[2] ?? null;

        $user = \App\Models\User::find($userId);
        $inscription = \App\Models\Inscription::find($inscriptionId);
        if (!$user || !$inscription) {
            Log::error('Utilisateur ou inscription introuvable', ['reference'=>$reference]);
            return response()->json(['error'=>'Référence invalide'], 404);
        }

        // Montant attendu selon le type de paiement
        $expected = $type === 'Inscription'
            ? $inscription->classe->prix_inscription
            : $inscription->classe->prix_mensuel;

        // Paiement réussi
        if (strtoupper($status) === 'SUCCESS' || strtoupper($status) === 'COMPLETED') {

            // Vérification que le montant reçu correspond au montant attendu
            if ($amount < $expected) {
                Log::warning('Montant insuffisant', [
                    'user'=>$userId,
                    'inscription'=>$inscriptionId,
                    'amount_received'=>$amount,
                    'amount_expected'=>$expected
                ]);
                return response()->json(['error'=>'Montant insuffisant'], 400);
            }

            // Vérifier si le paiement existe déjà (éviter doublons)
            $existing = \App\Models\Paiement::where('inscription_id', $inscriptionId)
                ->where('reference', $reference)
                ->first();

            if ($existing) {
                return response()->json(['ok'=>true], 200);
            }

            // Enregistrer le paiement dans la base
            $paiement = \App\Models\Paiement::create([
                'montant' => $amount,
                'date' => now(),
                'mode_paiement' => 'OrangeMoney',
                'type_paiement' => $type,
                'user_id' => $userId,
                'inscription_id' => $inscriptionId,
                'reference' => $reference,
                'statut' => 'Valide',
            ]);

            // Génération du reçu PDF
            try {
                Storage::makeDirectory('public/documents');
                $pdf = Pdf::loadView('pages.admin.recu.pdf.recu', [
                    'paiement'=>$paiement,
                    'user'=>$user
                ]);
                $fileName = 'recu_'.$paiement->id.'.pdf';
                Storage::put('public/documents/'.$fileName, $pdf->output());

                // Créer l'entrée Recu dans la base
                \App\Models\Recu::create([
                    'fichier_pdf' => 'documents/'.$fileName,
                    'date_emission' => now(),
                    'paiement_id' => $paiement->id,
                ]);

                // Envoi du mail avec le reçu
                if ($user->email) {
                    Mail::to($user->email)->send(new RecuPaiementMail($paiement, $user, 'documents/'.$fileName));
                }
            } catch (\Exception $e) {
                Log::error('Erreur génération reçu', ['err'=>$e->getMessage()]);
            }

            return response()->json(['ok'=>true, 'message'=>'Paiement validé'], 200);
        }

        // Paiement annulé ou échoué
        Log::info('Paiement annulé ou échoué', [
            'status'=>$status,
            'reference'=>$reference,
            'data'=>$data
        ]);

        // On ne crée pas d'entrée Paiement pour les échecs, on renvoie juste un message
        return response()->json(['ok'=>true, 'message'=>'Paiement annulé'], 200);
    }

    /**
     * Page de confirmation côté utilisateur
     * - Affiche la référence et le message selon le statut
     */
    public function confirmation(string $reference)
    {
        return view('pages.admin.paiement.confirmation', compact('reference'));
    }
}
