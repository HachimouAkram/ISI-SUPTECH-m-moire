<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Formation;

use Exception;


class FormationController extends Controller
{
    use GenerateApiResponse;

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $classes = Classe::paginate($perPage);
        $perPage = request()->get('per_page', 10);
        $formations = Formation::paginate($perPage);
        return view('pages.admin.formation.liste', compact('formations', 'classes'));
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
            $formation = new Formation();
            $formation->nom = $request->nom;
            $formation->description = $request->description;
            $formation->duree = $request->duree;
            $formation->type_formation = $request->type_formation;
            $formation->domaine = $request->domaine;
            $formation->save();
            return redirect()->route('formations.index')->with('success', 'Classe mise à jour avec succès');
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
            $formation = Formation::findOrFail($id);
            $formation->nom = $request->nom;
            $formation->description = $request->description;
            $formation->duree = $request->duree;
            $formation->type_formation = $request->type_formation;
            $formation->domaine = $request->domaine;
            $formation->save();
            return redirect()->route('formations.index')->with('success', 'Mise à jour réussie');

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
            $formation = Formation::findOrFail($id);
            $formation->delete();
                return $this->successResponse($formation, 'Suppression réussie');
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
            $formation = Formation::findOrFail($id);
             return $this->successResponse($formation, 'Ressource trouvée');
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

    public function getClasses($id)
    {
        try {
            $classes = \App\Models\Classe::with('formation')
                        ->where('formation_id', $id)
                        ->get();

            return response()->json($classes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showTestForm()
    {
        $formations = \App\Models\Formation::all();
        return view('pages.admin.inscription.test', compact('formations'));
    }

}
