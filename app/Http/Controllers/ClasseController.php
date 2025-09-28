<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Classe;
use App\Models\Formation;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;


class ClasseController extends Controller
{
    use GenerateApiResponse;

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    
    public function index()
    {
        // Ordre personnalisé des types de formation
        $ordreTypes = ['LICENCE', 'MASTER', 'BTS'];

        // Récupération des paramètres de pagination
        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);

        // Récupérer toutes les classes avec leur formation
        $classesNonTriees = Classe::with('formation')->get();

        // Appliquer le tri personnalisé sur type_formation puis niveau
        $classesTriees = $classesNonTriees->sortBy(function ($classe) use ($ordreTypes) {
            $type = strtoupper(trim($classe->formation->type_formation));
            $niveau = (int) $classe->niveau;

            $indexType = array_search($type, $ordreTypes) ?? 999;

            // Combine les deux critères en une seule clé de tri
            return $indexType * 10 + $niveau;
        });

        // Paginer manuellement la collection triée
        $classes = new LengthAwarePaginator(
            $classesTriees->forPage($page, $perPage),
            $classesTriees->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Récupérer les formations pour les formulaires
        $formations = Formation::all();

        // Retourner la vue avec les données
        return view('pages.admin.classe.liste', compact('classes', 'formations'));
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
            'formation_id'      => 'required|exists:formations,id',
            'prix_inscription'  => 'required|numeric|min:0',
            'prix_mensuel'      => 'required|numeric|min:0',
            'duree'             => 'required|integer|min:1',
            'niveau'            => 'required|integer|min:1',
            'mois_rentree'      => 'required|string',
            'etat'              => 'nullable|boolean',
        ]);

        try {
            $classe = new Classe();
            $classe->formation_id     = $request->formation_id;
            $classe->prix_inscription = $request->prix_inscription;
            $classe->prix_mensuel     = $request->prix_mensuel;
            $classe->duree            = $request->duree;
            $classe->niveau           = $request->niveau;
            $classe->mois_rentree     = $request->mois_rentree;
            $classe->etat             = $request->etat ?? 1; // Valeur par défaut à 1

            $classe->save();

            return redirect()->route('classes.index')->with('success', 'Classe ajoutée avec succès');

        } catch (Exception $e) {
            return back()->with('error', 'Insertion échouée : ' . $e->getMessage());
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
            $classe = Classe::findOrFail($id);
            $classe->prix_inscription = $request->prix_inscription;
            $classe->prix_mensuel = $request->prix_mensuel;
            $classe->duree = $request->duree;
            $classe->niveau = $request->niveau;
            $classe->formation_id = $request->formation_id;
            $classe->save();

            return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error', 'Mise à jour échouée : ' . $e->getMessage());
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
            $classe = Classe::findOrFail($id);
            $classe->delete();

            return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès');
        } catch (Exception $e) {
            return back()->with('error', 'Suppression échouée : ' . $e->getMessage());
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
            $classe = Classe::findOrFail($id);
             return $this->successResponse($classe, 'Ressource trouvée');
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

    public function getClassesByFormation($id)
    {
        $classes = Classe::with('formation')
            ->where('formation_id', $id)
            ->where('etat', true) // Pour ne récupérer que les classes actives
            ->orderBy('niveau')
            ->get();

        return response()->json($classes);
    }

}
