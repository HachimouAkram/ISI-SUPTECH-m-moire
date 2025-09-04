<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Classe;
use App\Models\ProgrammeAccademique;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $annees = ProgrammeAccademique::orderBy('annee_accademique', 'desc')
            ->take(6)
            ->pluck('annee_accademique')
            ->toArray();

        if(empty($annees)){
            $annees = [date('Y').'-'.(date('Y')+1)]; // Exemple : 2025-2026
        }


        $inscriptionsParAn = [];
        $etudiantsParAn = [];

        foreach ($annees as $annee) {
            $inscriptionsParAn[] = Inscription::whereHas('programmeAccademique', function($q) use ($annee) {
                $q->where('annee_accademique', $annee);
            })->count();

            $etudiantsParAn[] = User::where('role','etudiant')
                ->whereHas('inscriptions.programmeAccademique', function($q) use ($annee) {
                    $q->where('annee_accademique','<=',$annee);
                })->count();
        }

        // Si tous les tableaux sont vides, mettre 0 par défaut pour afficher quelque chose
        if(empty($inscriptionsParAn)) $inscriptionsParAn = [0];
        if(empty($etudiantsParAn)) $etudiantsParAn = [0];

        $nbEtudiants = User::where('role', 'etudiant')->count();
        $nbFormations = Formation::count();
        $nbInscriptions = Inscription::whereYear('created_at', Carbon::now()->year)->count();
        $nbClasses = Classe::count();
        $formations = Formation::latest()->paginate(5);
        return view('dashboard', compact(
            'nbEtudiants',
            'nbFormations',
            'nbInscriptions',
            'nbClasses',
            'formations',
            'annees',
            'inscriptionsParAn',
            'etudiantsParAn'
        ));
    }
}
