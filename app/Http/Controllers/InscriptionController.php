<?php

namespace App\Http\Controllers;

use App\Mail\RappelDocumentMail;
use App\Models\Classe;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Inscription;
use App\Models\ProgrammeAccademique;
use App\Notifications\InscriptionRefusee;
use App\Notifications\InscriptionValidee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Exception;
use Illuminate\Support\Facades\Mail;

class InscriptionController extends Controller
{
    use GenerateApiResponse;

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     // à ajouter en haut si pas déjà présent

    public function mesInscriptions()
    {
        try {
            $userId = Auth::id();

            $inscriptions = Inscription::with([
                'documents',
                'classe.formation',
                'programmeAccademique',
                'user'
            ])
            ->where('user_id', $userId)
            ->paginate(10);

            return view('pages.admin.inscription.mes_inscriptions', compact('inscriptions'));
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des inscriptions', 500, $e->getMessage());
        }
    }


    public function index(Request $request)
    {
        $statut = $request->get('statut');
        $perPage = $request->get('per_page', 10);

        $query = Inscription::with([
            'documents',
            'user',
            'programmeAccademique',
            'classe.formation'
        ]);

        if ($statut) {
            $query->where('statut', $statut);
        }

        $inscriptions = $query->paginate($perPage)->appends([
            'statut' => $statut,
            'per_page' => $perPage,
        ]);

        // 🔹 Ajouter les notifications ici
        /** @var User $user */
        $user = Auth::user();
        $notifications = $user->unreadNotifications;

        return view('pages.admin.inscription.liste', compact('inscriptions', 'statut', 'notifications'));
    }


    public function valider($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->statut = 'Valider';
        $inscription->save();

        // 🔹 Récupérer l'utilisateur qui a fait l'inscription
        $user = $inscription->user; // relation dans ton modèle Inscription

        // 🔹 Lui envoyer la notification
        $user->notify(new InscriptionValidee());

        return redirect()->back()->with('success', 'Inscription validée avec succès et notification envoyée.');
    }

    public function refuser(Request $request, $id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->statut = 'Refuser';
        $inscription->save();

        $user = $inscription->user;

        // 🔹 Passer le motif au constructeur de la notification
        $user->notify(new InscriptionRefusee($request->motif));

        return redirect()->back()->with('success', 'Inscription refusée et notification envoyée avec le motif.');
    }

    public function envoyerRappel(Request $request, $id)
    {
        $inscription = Inscription::findOrFail($id);
        $user = $inscription->user;

        // Récupérer le message du formulaire
        $message = $request->input('message');

        // Envoyer l'email
        Mail::to($user->email)->send(new RappelDocumentMail($user, $message));

        return back()->with('success', 'Rappel envoyé avec succès à ' . $user->email);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'classe_id' => 'required|exists:classes,id',
            'programme_accademique_id' => 'required|exists:programme_accademiques,id',
        ]);

        try {
            $inscription = new Inscription();
            $inscription->date = $request->date;
            $inscription->statut = 'Encours';
            $inscription->classe_id = $request->classe_id;
            $inscription->programme_accademique_id = $request->programme_accademique_id;
            $inscription->user_id = Auth::id();
            $inscription->save();

            return redirect()->route('inscription.confirmation')
                ->with('success', 'Inscription enregistrée avec succès.');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de l\'enregistrement', 500, $e->getMessage());
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
            $inscription = Inscription::findOrFail($id);
            $inscription->date = $request->date;
            $inscription->statut = $request->statut;
            $inscription->save();
                return $this->successResponse($inscription, 'Mise à jour réussie');
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
            $inscription = Inscription::findOrFail($id);
            $inscription->delete();
                return $this->successResponse($inscription, 'Suppression réussie');
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
            $inscription = Inscription::findOrFail($id);
             return $this->successResponse($inscription, 'Ressource trouvée');
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

    public function create()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $inscriptionEnCours = $user->inscriptions()
            ->where('statut', 'Encours')
            ->latest()
            ->first();

        $formations = Formation::all();
        $programmeActif = ProgrammeAccademique::where('etat', true)->first();

        return view('pages.admin.inscription.welcome', compact('formations', 'programmeActif', 'inscriptionEnCours'));
    }


    public function edit($id)
    {
        $inscription = Inscription::findOrFail($id);
        $formations = Formation::all();
        $classes = Classe::all();

        return view('pages.admin.inscription.edit', compact('inscription', 'formations', 'classes'));
    }

}
