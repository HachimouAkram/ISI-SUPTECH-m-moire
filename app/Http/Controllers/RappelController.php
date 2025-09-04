<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\Rappel;

use Exception;


class RappelController extends Controller
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
        $rappels = Rappel::paginate($perPage);
        return view('pages.admin.rappel.liste', compact('rappels'));
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
            $rappel = new Rappel();
            $rappel->date = $request->date;
            $rappel->texte = $request->texte;
            $rappel->save();
                return $this->successResponse($rappel, 'Récupération réussie');

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
            $rappel = Rappel::findOrFail($id);
            $rappel->date = $request->date;
            $rappel->texte = $request->texte;
            $rappel->save();
                return $this->successResponse($rappel, 'Mise à jour réussie');
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
            $rappel = Rappel::findOrFail($id);
            $rappel->delete();
                return $this->successResponse($rappel, 'Suppression réussie');
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
            $rappel = Rappel::findOrFail($id);
             return $this->successResponse($rappel, 'Ressource trouvée');
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


}
