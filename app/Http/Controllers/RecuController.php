<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Recu;
use Carbon\Carbon;

use Exception;


class RecuController extends Controller
{
    use GenerateApiResponse;

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Récupérer les reçus liés aux paiements de l'utilisateur
        $recus = Recu::whereHas('paiement', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['paiement.inscription.classe.formation', 'paiement.inscription'])->paginate(10);

        // Calculer le mois correspondant à chaque paiement
        Carbon::setLocale('fr'); // mettre en français

        foreach ($recus as $recu) {
            $inscription = $recu->paiement->inscription;
            $classe = $inscription->classe;
            $frais_inscription = $classe->prix_inscription;
            $mensualite = $classe->prix_mensuel;
            $duree = $classe->duree;
            $mois_debut_nom = $classe->mois_rentree;
            $mois_map = [
                'Janvier'=>1, 'Février'=>2, 'Mars'=>3, 'Avril'=>4,
                'Mai'=>5, 'Juin'=>6, 'Juillet'=>7, 'Août'=>8,
                'Septembre'=>9, 'Octobre'=>10, 'Novembre'=>11, 'Décembre'=>12,
            ];
            $mois_debut = $mois_map[$mois_debut_nom] ?? 1;
            $annee = $inscription->created_at->year;

            $total_paye = $inscription->paiements()->where('id', '<=', $recu->paiement->id)->sum('montant');

            if ($total_paye <= $frais_inscription) {
                // 🔹 Si c'est le paiement d'inscription
                $recu->mois_du_paiement = $annee . '-' . ($annee + 1); // année académique
            } else {
                // 🔹 Paiement mensuel
                $reste = $total_paye - $frais_inscription;
                $mois_payes = ceil($reste / $mensualite); // 1ère mensualité = mois de rentrée

                $recu->mois_du_paiement = Carbon::createFromDate($annee, $mois_debut, 1)
                                    ->addMonths($mois_payes - 1) // -1 pour que le premier mois payé corresponde au mois de rentrée
                                    ->locale('fr')
                                    ->translatedFormat('F Y');
            }

        }

        return view('pages.admin.recu.liste', compact('recus'));
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
            $recu = new Recu();
            $recu->fichier_pdf = $request->fichier_pdf;
            $recu->date = $request->date;
            $recu->save();
                return $this->successResponse($recu, 'Récupération réussie');

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
            $recu = Recu::findOrFail($id);
            $recu->fichier_pdf = $request->fichier_pdf;
            $recu->date = $request->date;
            $recu->save();
                return $this->successResponse($recu, 'Mise à jour réussie');
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
            $recu = Recu::findOrFail($id);
            $recu->delete();
                return $this->successResponse($recu, 'Suppression réussie');
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
            $recu = Recu::findOrFail($id);
             return $this->successResponse($recu, 'Ressource trouvée');
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
