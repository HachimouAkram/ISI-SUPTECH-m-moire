<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\Inscription;

use Exception;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use GenerateApiResponse;

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10); // valeur par défaut : 10
        $documents = Document::paginate($perPage);
        return view('pages.admin.document.liste', compact('documents'));
    }
    public function create()
{
    $user = Auth::user();

    /** @var \App\Models\User $user */
    $inscription = $user->inscriptions()
                        ->where('statut', 'Encours')
                        ->latest('created_at')
                        ->first();

    if (! $inscription) {
        return redirect()->back()
                         ->with('error', 'Aucune inscription en cours trouvée.');
    }

    // Récupération du niveau et du type de formation
    $classe     = $inscription->classe;
    $formation  = $classe->formation;

    $niveau         = $classe->niveau; // ex: 1, 2, 3
    $typeFormation  = $formation->type_formation; // "Licence", "Master", "BTS"

    // Tous les types de documents possibles
    $types = [
        'Bulletin Semestre 2' => 'Bulletin de notes de la première année.',
        'Bulletin Semestre 4' => 'Bulletin de notes de la deuxième année.',
        'Bulletin Semestre 6' => 'Bulletin de notes de la troisième année.',
        'Bulletin Semestre 8' => 'Bulletin de notes de la quatrième année (Master 2).',
        'Pièce d\'identité' => 'Copie de votre carte nationale d\'identité.',
        'Photo d\'identité' => 'Photo d’identité récente au format officiel.',
        'Relevé Bac'        => 'Relevé de notes du baccalauréat.',
        'Attestation Bac'   => 'Attestation ou diplôme du baccalauréat.',
        'Diplôme de validation Licence ou equivalant'   => 'Il permet de confirmer votre parcours académique.',
        'Acte de naissance' => 'Copie intégrale de votre acte de naissance pour vérification d’état civil.',
    ];

    // Initialisation des bulletins à exclure
    $exclus = [];

    // Logique métier centralisée
    switch ($typeFormation) {
        case 'Licence':
        case 'BTS':
            if ($niveau === 1) {
                $exclus = ['Bulletin Semestre 2', 'Bulletin Semestre 4', 'Bulletin Semestre 6', 'Bulletin Semestre 8'];
            } elseif ($niveau === 2) {
                $exclus = ['Bulletin Semestre 4', 'Bulletin Semestre 6', 'Bulletin Semestre 8'];
            } elseif ($niveau === 3) {
                $exclus = ['Bulletin Semestre 6', 'Bulletin Semestre 8'];
            }
            break;
    }

    // Suppression des bulletins exclus
    foreach ($exclus as $key) {
        unset($types[$key]);
    }

    // Documents déjà déposés
    $deposes = $inscription->documents()->pluck('nom')->all();

    // On retire les documents déjà déposés
    $disponibles = array_diff_key($types, array_flip($deposes));

    return view('pages.admin.document.create', [
        'inscription' => $inscription,
        'types'       => $disponibles,
    ]);
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
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fichier' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            // Récupérer la dernière inscription avec statut "Encours"
            $inscription = $user->inscriptions()
                                ->where('statut', 'Encours')
                                ->latest('created_at')
                                ->first();

            if (!$inscription) {
                return back()->with('error', 'Aucune inscription en cours trouvée.');
            }

            $fichierPath = $request->file('fichier')->store('documents', 'public');

            $document = new Document();
            $document->nom = $request->nom;
            $document->description = $request->description;
            $document->chemin_fichier = $fichierPath;
            $document->inscription_id = $inscription->id;
            $document->save();

            return redirect()->route('documents.create')->with('success', 'Document ajouté avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout : ' . $e->getMessage());
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
        $request->validate([
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ]);

        try {
            $user = Auth::user();
            $document = Document::findOrFail($id);
            /** @var \App\Models\User $user */
            $inscription = $user->inscriptions()
                                ->where('statut', 'Encours')
                                ->latest('created_at')
                                ->first();

            if (!$inscription || $document->inscription_id !== $inscription->id) {
                return redirect()->back()->with('error', 'Document non modifiable.');
            }

            // Si un nouveau fichier a été uploadé
            if ($request->hasFile('fichier')) {
                $fichierPath = $request->file('fichier')->store('documents', 'public');
                $document->chemin_fichier = $fichierPath;
            }

            $document->description = $request->description;
            $document->save();

            return redirect()->route('documents.index')->with('success', 'Document modifié avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);

        // Vérification que le document appartient à la dernière inscription "Encours" du user connecté
        /** @var \App\Models\User $user */
        $inscription = $user->inscriptions()
                            ->where('statut', 'Encours')
                            ->latest('created_at')
                            ->first();

        if (!$inscription || $document->inscription_id !== $inscription->id) {
            return redirect()->back()->with('error', 'Document non modifiable.');
        }

        // Liste des types et descriptions (à des fins de rappel visuel)
        $types = [
            'Bulletin Année 1' => 'Bulletin de notes de la première année afin de vérifier votre progression académique.',
            'Bulletin Année 2' => 'Bulletin de notes de la deuxième année afin de vérifier votre progression académique.',
            'Bulletin Année 3' => 'Bulletin de notes de la troisième année afin de vérifier votre progression académique.',
            'Pièce d\'identité' => 'Copie de votre carte nationale d\'identité ou passeport pour vérification d\'identité.',
            'Relevé Bac'       => 'Relevé de notes du baccalauréat pour attester de vos résultats finaux.',
            'Attestation Bac'  => 'Attestation ou diplôme de baccalauréat pour prouver l’obtention du diplôme.',
            'Dernier Diplôme'  => 'Votre dernier diplôme obtenu (Licence, DUT, etc.) pour valider votre parcours antérieur.',
        ];

        return view('pages.admin.document.edit', compact('document', 'types'));
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
            $document = Document::findOrFail($id);
            $document->delete();
                return $this->successResponse($document, 'Suppression réussie');
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
            $document = Document::findOrFail($id);
             return $this->successResponse($document, 'Ressource trouvée');
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


    public function editMesDocuments()
    {
        $user = Auth::user();

        $inscription = Inscription::where('user_id', $user->id)
            ->where('statut', 'Encours')
            ->latest()
            ->with('documents')
            ->first();

        if (!$inscription) {
            return redirect()->back()->with('error', 'Aucune inscription en cours trouvée.');
        }

        return view('pages.admin.document.edit-mes-documents', [
            'documents' => $inscription->documents
        ]);
    }

    public function updateMesDocument(Request $request, Document $document)
{
    $request->validate([
        'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        $nom = $document->nom;
        $inscription_id = $document->inscription_id;

        $description = $request->input('description', $document->description);

        if ($document->chemin_fichier) {
            Storage::disk('public')->delete($document->chemin_fichier);
        }

        $document->delete();

        $chemin = $request->file('fichier')->store('documents', 'public');

        $nouveauDocument = new Document();
        $nouveauDocument->nom = $nom;
        $nouveauDocument->description = $description;
        $nouveauDocument->chemin_fichier = $chemin;
        $nouveauDocument->inscription_id = $inscription_id;
        $nouveauDocument->save();

        return redirect()->back()->with('success', 'Document remplacé avec succès.');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors du remplacement : ' . $e->getMessage());
    }
}


}
