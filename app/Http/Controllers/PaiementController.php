<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Paiement;
use Carbon\Carbon;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
Carbon::setLocale('fr');
setlocale(LC_TIME, 'fr_FR.UTF-8');
use Stripe\Stripe;
use Barryvdh\DomPDF\Facade\Pdf;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Inscription;
use App\Models\Recu;
use Exception;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\RecuPaiementMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttestationInscriptionMail;
use Illuminate\Support\Facades\Storage;

class PaiementController extends Controller
{
    use GenerateApiResponse;

    // Page de choix
    public function choixType()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();
        $inscriptionPayee = $inscription ? $inscription->paiements()->where('type_paiement', 'Inscription')->exists() : false;

        return view('pages.admin.paiement.choix', compact('inscriptionPayee'));
    }

    // Affiche la page de paiement
    public function afficherPaiement($type)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

        if(!$inscription) {
            return redirect()->route('paiement.choix')->with('error', 'Aucune inscription validée trouvée.');
        }

        $montant = $type === 'Inscription' ? $inscription->classe->prix_inscription : $inscription->classe->prix_mensuel;

        return view('pages.admin.paiement.index', [
            'type' => $type,
            'montant' => $montant,
            'formation' => $inscription->classe->formation,
            'inscription' => $inscription
        ]);
    }

    // --- PAYPAL ---
    public function createPaypalPaiement($type)
    {
        try {
            Log::info('=== PAYPAL CREATE START ===');

            /** @var User $user */
            $user = Auth::user();
            $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

            if (!$inscription) {
                throw new Exception('Aucune inscription validée trouvée');
            }

            $classe = $inscription->classe;
            $montant = $type === 'Inscription' ? $classe->prix_inscription : $classe->prix_mensuel;

            // Stocker en session
            session([
                'paiement_type' => $type,
                'paiement_inscription_id' => $inscription->id,
                'paiement_montant' => $montant
            ]);

            Log::info('Session stored', [
                'type' => $type,
                'inscription_id' => $inscription->id,
                'montant' => $montant
            ]);

            // Initialiser PayPal avec debug
            $provider = new PayPalClient;
            $config = config('paypal');
            Log::info('PayPal config', $config);

            $provider->setApiCredentials($config);

            try {
                $token = $provider->getAccessToken();
                Log::info('PayPal token obtained');
            } catch (Exception $e) {
                Log::error('PayPal token error', ['error' => $e->getMessage()]);
                throw new Exception('Erreur connexion PayPal: ' . $e->getMessage());
            }

            $provider->setAccessToken($token);

            // Créer l'ordre
            $orderData = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($montant, 2, '.', '')
                        ],
                        "description" => "Paiement $type - " . $classe->formation->nom,
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.success'),
                    "brand_name" => config('app.name'),
                    "user_action" => "PAY_NOW",
                ]
            ];

            Log::info('Order data', $orderData);

            $response = $provider->createOrder($orderData);
            Log::info('PayPal order response', $response);

            // Vérifier la réponse
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        Log::info('Redirecting to PayPal', ['url' => $link['href']]);
                        return redirect()->away($link['href']);
                    }
                }
            }

            throw new Exception('Réponse PayPal invalide: ' . json_encode($response));

        } catch (Exception $e) {
            Log::error('PayPal creation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('paiement.page', ['type' => $type])
                ->with('error', 'Erreur création paiement: ' . $e->getMessage());
        }
    }

    public function paypalSuccess(Request $request)
    {
        try {
            // Récupérer les données JSON envoyées
            $data = $request->json()->all();
            $orderID = $data['orderID'] ?? null;
            $type = $data['type'] ?? null;
            $inscriptionId = $data['inscription_id'] ?? null;

            if (!$orderID || !$type || !$inscriptionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres manquants'
                ], 400);
            }

            /** @var User $user */
            $user = Auth::user();
            $inscription = Inscription::findOrFail($inscriptionId);

            if ($inscription->user_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Initialiser PayPal
            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Capturer le paiement côté serveur
            $response = $provider->capturePaymentOrder($orderID);

            // Log complet pour debug
            Log::info('PayPal Capture Response', $response);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $montantUSD = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0;
                $montantFCFA = $montantUSD * 550; // conversion USD -> FCFA

                try {
                    // Créer le paiement
                    $paiement = Paiement::create([
                        'montant' => $montantFCFA,
                        'date' => now(),
                        'mode_paiement' => 'PayPal',
                        'type_paiement' => $type,
                        'user_id' => $user->id,
                        'inscription_id' => $inscription->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur création Paiement PayPal', ['message' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l’insertion en base : ' . $e->getMessage()
                    ], 500);
                }

                // Générer le PDF du reçu
                $pdf = Pdf::loadView('pages.admin.recu.pdf.recu', [
                    'paiement' => $paiement,
                    'user' => $user,
                ]);

                $fileName = 'recu_'.$paiement->id.'.pdf';
                Storage::put('public/documents/'.$fileName, $pdf->output());

                Recu::create([
                    'fichier_pdf' => 'documents/'.$fileName,
                    'date_emission' => now(),
                    'paiement_id' => $paiement->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement PayPal validé et reçu généré.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Paiement non complété'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Erreur PayPal Success', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation du paiement : ' . $e->getMessage()
            ], 500);
        }
    }


    public function paypalCancel(Request $request)
    {
        Log::info('PayPal Cancel', ['request' => $request->all()]);

        session()->forget(['paiement_type', 'paiement_inscription_id', 'paiement_montant']);

        return redirect()->route('paiement.choix')
            ->with('error', 'Paiement annulé.');
    }

    // Méthode pour capture AJAX (si utilisée ailleurs)
    public function captureOrder(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        try {
            $response = $provider->capturePaymentOrder($request->orderID);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- STRIPE ---
    public function createStripePaiement($type)
    {
        /** @var User $user */
        $user = Auth::user();

        // Récupérer inscription validée
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();
        if (!$inscription) {
            return redirect()->back()->with('error', 'Aucune inscription validée trouvée.');
        }

        // Déterminer montant (en FCFA)
        $classe = $inscription->classe;
        $montant_fcfa = $type === 'Inscription'
            ? $classe->prix_inscription
            : $classe->prix_mensuel;

        // Conversion FCFA -> USD (par exemple 1 USD = 605 FCFA)
        $taux = 605;
        $montant_usd = round($montant_fcfa / $taux, 2); // Ex: 10000 FCFA ≈ 16.53 USD

        // Stripe attend le montant en CENTS
        $montant_stripe = $montant_usd * 100;

        // Initialiser Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "Paiement $type - " . $classe->formation->nom,
                    ],
                    'unit_amount' => $montant_stripe,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['type' => $type]),
            'cancel_url' => route('stripe.cancel'),
            'client_reference_id' => $user->id . '_' . $inscription->id . '_' . $type,
        ]);

        return redirect($session->url);
    }

    public function stripeSuccess(Request $request, $type)
    {
        /** @var User $user */
        $user = Auth::user();
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

        if (!$inscription) {
            return redirect()->route('paiement.choix')->with('error', 'Inscription non trouvée.');
        }

        // Enregistrer le paiement en FCFA (pas en USD)
        $paiement = Paiement::create([
            'montant' => $type === 'Inscription'
                ? $inscription->classe->prix_inscription
                : $inscription->classe->prix_mensuel,
            'date' => Carbon::now(),
            'mode_paiement' => 'Stripe',
            'type_paiement' => $type,
            'user_id' => $user->id,
            'inscription_id' => $inscription->id,
        ]);

        // S'assurer que le dossier existe
        Storage::makeDirectory('public/documents');

        // Générer le reçu PDF
        $recuPdf = Pdf::loadView('pages.admin.recu.pdf.recu', [
            'paiement' => $paiement,
            'user' => $user,
        ]);

        $recuFileName = 'recu_'.$paiement->id.'.pdf';
        Storage::put('public/documents/'.$recuFileName, $recuPdf->output());

        // Sauvegarder dans la table "recus"
        Recu::create([
            'fichier_pdf' => 'documents/'.$recuFileName,
            'date_emission' => Carbon::now(),
            'paiement_id' => $paiement->id,
        ]);

        // Envoi du mail avec le reçu
        Mail::to($user->email)->send(new RecuPaiementMail($paiement, $user, 'documents/'.$recuFileName));

        // Si c'est un paiement d'inscription, générer et envoyer l'attestation
        if ($type === 'Inscription') {
            // Générer l'attestation d'inscription PDF
            $attestationPdf = Pdf::loadView('pages.admin.attestation.pdf.attestation', [
                'user' => $user,
                'inscription' => $inscription,
            ]);

            $attestationFileName = 'attestation_inscription_'.$inscription->id.'.pdf';
            Storage::put('public/documents/'.$attestationFileName, $attestationPdf->output());

            // Envoyer l'attestation par email (séparément du reçu)
            try {
                Mail::to($user->email)->send(new AttestationInscriptionMail(
                    $user,
                    $inscription,
                    'documents/'.$attestationFileName
                ));

                Log::info('Attestation d\'inscription envoyée', [
                    'user_id' => $user->id,
                    'inscription_id' => $inscription->id,
                    'file' => $attestationFileName
                ]);

            } catch (Exception $e) {
                Log::error('Erreur envoi attestation inscription', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }
        }

        $message = $type === 'Inscription'
            ? 'Paiement Stripe validé ! Reçu et attestation d\'inscription envoyés par email.'
            : 'Paiement Stripe validé et reçu généré.';

        return redirect()->route('paiement.choix')->with('success', $message);
    }

    public function stripeCancel()
    {
        return redirect()->route('paiement.choix')
            ->with('error', 'Paiement Stripe annulé.');
    }

    public function payerEspeceUnique(Request $request, User $user)
    {
        // Vérifier si l'étudiant a une inscription validée
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

        if (!$inscription) {
            return back()->with('error', '⚠️ Cet étudiant n’a aucune inscription validée.');
        }

        $classe = $inscription->classe;
        $frais_inscription = $classe->prix_inscription;
        $mensualite = $classe->prix_mensuel;
        $duree = $classe->duree;
        $mois_debut_nom = $classe->mois_rentree;
        $annee = $inscription->created_at->year;

        // Total payé
        $total_paye = $inscription->paiements()->sum('montant');

        // Montant restant à payer
        $montant_restant = max(0, $frais_inscription + $mensualite * ($duree - 1) - $total_paye);

        if ($montant_restant <= 0) {
            return back()->with('error', '✅ Toutes les échéances ont déjà été réglées pour cet étudiant.');
        }

        // Déterminer le type de paiement
        $inscriptionPayee = $inscription->paiements()->where('type_paiement', 'Inscription')->exists();
        $typePaiement = $inscriptionPayee ? 'Mensualité' : 'Inscription';
        $montant = $typePaiement === 'Inscription' ? $frais_inscription : $mensualite;

        // Créer le paiement
        $paiement = Paiement::create([
            'montant' => $montant,
            'date' => now(),
            'mode_paiement' => 'Espèce',
            'type_paiement' => $typePaiement,
            'user_id' => $user->id,
            'inscription_id' => $inscription->id,
        ]);

        // 🔹 Enregistrer le log
        activity()
            ->causedBy(Auth::user())      // l’admin ou secrétaire qui enregistre
            ->performedOn($paiement)      // le paiement qui vient d’être créé
            ->withProperties([
                'montant' => $paiement->montant,
                'mode' => $paiement->mode_paiement,
                'type' => $paiement->type_paiement,
                'etudiant' => $user->nom . ' ' . $user->prenom,
            ])
            ->log("Paiement en espèces enregistré pour {$user->nom} {$user->prenom}");

        // Générer le PDF du reçu
        $pdf = Pdf::loadView('pages.admin.recu.pdf.recu', [
            'paiement' => $paiement,
            'user' => $user,
        ]);

        $fileName = 'recu_' . $paiement->id . '.pdf';
        Storage::put('public/documents/' . $fileName, $pdf->output());

        Recu::create([
            'fichier_pdf' => 'documents/' . $fileName,
            'date_emission' => now(),
            'paiement_id' => $paiement->id,
        ]);

        // Envoi du mail du reçu
        try {
            Mail::to($user->email)->send(new RecuPaiementMail($paiement, $user, 'documents/' . $fileName));
        } catch (Exception $e) {
            Log::error('Erreur envoi email paiement espèces', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        // --- Générer l'attestation si c'est un paiement d'inscription ---
        if ($typePaiement === 'Inscription') {
            $attestationPdf = Pdf::loadView('pages.admin.attestation.pdf.attestation', [
                'user' => $user,
                'inscription' => $inscription,
            ]);

            $attestationFileName = 'attestation_inscription_'.$inscription->id.'.pdf';
            Storage::put('public/documents/'.$attestationFileName, $attestationPdf->output());

            try {
                Mail::to($user->email)->send(new AttestationInscriptionMail(
                    $user,
                    $inscription,
                    'documents/'.$attestationFileName
                ));
            } catch (Exception $e) {
                Log::error('Erreur envoi attestation inscription espèces', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return back()->with('success', "Paiement $typePaiement effectué avec succès. Reçu envoyé par email.");
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        // Récupérer les filtres
        $classeId = $request->get('classe_id');
        $anneeAcademique = $request->get('annee_accademique');
        $nomPrenom = $request->get('nom_prenom');

        $paiements = Paiement::query()
            ->with(['inscription.classe.formation', 'inscription.programmeAccademique', 'inscription.user']);

        // Filtrer par classe
        if ($classeId) {
            $paiements->whereHas('inscription.classe', function($q) use ($classeId) {
                $q->where('id', $classeId);
            });
        }

        // Filtrer par année académique
        if ($anneeAcademique) {
            $paiements->whereHas('inscription.programmeAccademique', function($q) use ($anneeAcademique) {
                $q->where('annee_accademique', $anneeAcademique);
            });
        }

        // Filtrer par nom/prénom
        if ($nomPrenom) {
            $paiements->whereHas('inscription.user', function($q) use ($nomPrenom) {
                $q->where('nom', 'like', "%$nomPrenom%")
                ->orWhere('prenom', 'like', "%$nomPrenom%");
            });
        }

        $paiements = $paiements->orderBy('date', 'desc')->paginate($perPage);

        $classes = \App\Models\Classe::all();
        $annees = \App\Models\ProgrammeAccademique::pluck('annee_accademique');

        return view('pages.admin.paiement.liste', compact('paiements', 'classes', 'annees'));
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $paiement = new Paiement();
            $paiement->montant = $request->montant;
            $paiement->date = $request->date;
            $paiement->mode_paiement = $request->mode_paiement;
            $paiement->type_paiement = $request->type_paiement;
            $paiement->save();
                return $this->successResponse($paiement, 'Récupération réussie');

        } catch (Exception $e) {
            return $this->errorResponse('Insertion échouée', 500, $e->getMessage());
        }
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
            $paiement->montant = $request->montant;
            $paiement->date = $request->date;
            $paiement->mode_paiement = $request->mode_paiement;
            $paiement->type_paiement = $request->type_paiement;
            $paiement->save();
                return $this->successResponse($paiement, 'Mise à jour réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Mise à jour échouée', 500, $e->getMessage());
        }
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
            $paiement->delete();
                return $this->successResponse($paiement, 'Suppression réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Suppression échouée', 500, $e->getMessage());
        }
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
             return $this->successResponse($paiement, 'Ressource trouvée');
        } catch (Exception $e) {
            return $this->errorResponse('Ressource non trouvée', 404, $e->getMessage());
        }
    }

        /**
     * Get related form details for foreign keys.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getformdetails()
    {
        try {

            return $this->successResponse([

            ], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }

    public function afficherEcheances()
    {
        $user = Auth::user();

        // Dernière inscription de l'utilisateur
        /** @var \App\Models\User $user */
        $inscription = $user->inscriptions()->where('statut', 'Valider')->latest()->first();

        if (!$inscription) {
            return view('echeances.liste', ['mois_echeances' => [], 'message' => 'Aucune inscription trouvée']);
        }

        $classe = $inscription->classe;

        $frais_inscription = $classe->prix_inscription;
        $mensualite = $classe->prix_mensuel;
        $duree = $classe->duree; // durée totale de la classe
        $mois_debut_nom = $classe->mois_rentree;
        $annee = $inscription->created_at->year;

        // Calculer le montant total à payer (mensualités excluant le 1er mois inclus dans l'inscription)
        $total_a_payer = $frais_inscription + $mensualite * ($duree - 1);

        // Total déjà payé
        $total_paye = $inscription->paiements()->sum('montant');

        // Montant restant à payer
        $montant_restant = max(0, $total_a_payer - $total_paye);

        // Calcul des mensualités déjà payées (hors frais d'inscription)
        $reste = max(0, $total_paye - $frais_inscription);
        $mois_payes = floor($reste / $mensualite);

        $mois_map = [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];

        // Convertir mois rentrée
        $mois_debut_nom = $classe->mois_rentree;
        $mois_debut = $mois_map[$mois_debut_nom] ?? 1; // fallback janvier si erreur

        // Générer les mois restants
        $debut = Carbon::createFromDate($annee, $mois_debut, 1)->addMonth();
        $mois_echeances = [];

        for ($i = $mois_payes; $i < $duree - 1; $i++) {
            $mois_echeances[] = $debut->copy()->addMonths($i)->translatedFormat('F Y');
        }

       return view('pages.admin.echeance.liste', compact('mois_echeances', 'mensualite', 'montant_restant'));
    }
}
