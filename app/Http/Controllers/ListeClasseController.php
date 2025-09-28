<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Inscription;
use App\Models\ProgrammeAccademique;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF; // dompdf

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

        $etudiants = Inscription::with('user')
            ->where('classe_id', $classeId)
            ->where('programme_accademique_id', $programmeId)
            ->get();

        return Excel::download(new \App\Exports\ListeClasseExport($etudiants, $classe, $programme),
            "liste_{$classe->id}_{$programme->annee_accademique}.xlsx");
    }
}
