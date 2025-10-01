<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Inscription;
use App\Models\ProgrammeAccademique;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF; // dompdf
use Spatie\SimpleExcel\SimpleExcelWriter;

class ListeClasseController extends Controller
{
    public function index(Request $request)
    {
        $programme = ProgrammeAccademique::where('etat', true)->first();
        $classes = Classe::with('formation')->get();

        return view('pages.admin.listes.index', compact('programme', 'classes'));
    }

    public function show($classeId, $programmeId)
    {
        $classe = Classe::with('formation')->findOrFail($classeId);
        $programme = ProgrammeAccademique::findOrFail($programmeId);

        $etudiants = Inscription::with('user')
            ->where('classe_id', $classeId)
            ->where('programme_accademique_id', $programmeId)
            ->get();

        return view('pages.admin.listes.show', compact('classe', 'programme', 'etudiants'));
    }

    public function exportPdf($classeId, $programmeId)
    {
        $classe = Classe::with('formation')->findOrFail($classeId);
        $programme = ProgrammeAccademique::findOrFail($programmeId);

        $etudiants = Inscription::with('user')
            ->where('classe_id', $classeId)
            ->where('programme_accademique_id', $programmeId)
            ->get();

        $pdf = FacadePdf::loadView('pages.admin.listes.export_pdf', compact('classe', 'programme', 'etudiants'));
        return $pdf->download("liste_{$classe->id}_{$programme->annee_accademique}.pdf");
    }

   public function exportExcel($classeId, $programmeId)
    {
        $classe = Classe::with('formation')->findOrFail($classeId);
        $programme = ProgrammeAccademique::findOrFail($programmeId);

        $etudiants = Inscription::with(['user', 'classe.formation'])
            ->where('classe_id', $classeId)
            ->where('programme_accademique_id', $programmeId)
            ->get();

        // Nom du fichier
        $fileName = "liste_{$classe->id}_{$programme->annee_accademique}.xlsx";

        return SimpleExcelWriter::streamDownload($fileName)
            ->addHeader([
                'N°',
                'Nom',
                'Prénom',
                'Email',
                'Téléphone',
                'Filière',
                'Niveau',
            ])
            ->addRows(
                $etudiants->map(function ($etudiant, $index) {
                    return [
                        $index + 1,
                        $etudiant->user->nom ?? '',
                        $etudiant->user->prenom ?? '',
                        $etudiant->user->email ?? '',
                        $etudiant->user->telephone ?? '',
                        $etudiant->classe->formation->nom ?? '', // ✅ depuis classe
                        ($etudiant->classe->formation->type_formation ?? '') . ' ' . ($etudiant->classe->niveau ?? ''),
                    ];
                })->toArray()
            )
            ->toBrowser();
    }

    public function exportExcelProgramme($programmeId)
{
    $programme = ProgrammeAccademique::findOrFail($programmeId);

    // Charger toutes les inscriptions de toutes les classes de ce programme
    $etudiants = Inscription::with(['user', 'classe.formation'])
        ->where('programme_accademique_id', $programmeId)
        ->get();

    $fileName = "programme_{$programme->annee_accademique}.xlsx";

    return SimpleExcelWriter::streamDownload($fileName)
        ->addHeader([
            'N°',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Formation',
            'Niveau',
        ])
        ->addRows(
            $etudiants->map(function ($etudiant, $index) {
                return [
                    $index + 1,
                    $etudiant->user->nom ?? '',
                    $etudiant->user->prenom ?? '',
                    $etudiant->user->email ?? '',
                    $etudiant->user->telephone ?? '',
                    // Exemple : Licence Informatique
                    ($etudiant->classe->formation->type_formation ?? '') . ' ' . ($etudiant->classe->formation->nom ?? ''),
                    $etudiant->classe->niveau ?? '',
                ];
            })->toArray()
        )
        ->toBrowser();
}
}
