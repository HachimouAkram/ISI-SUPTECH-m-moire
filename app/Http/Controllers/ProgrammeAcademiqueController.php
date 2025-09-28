<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgrammeAccademique;

class ProgrammeAcademiqueController extends Controller
{
    // Afficher tous les programmes
    public function index()
    {
        $programmes = ProgrammeAccademique::orderBy('annee_accademique', 'desc')->get();
        return view('pages.admin.programmeAccademique.index', compact('programmes'));
    }

    // Formulaire de création
    public function create()
    {
        return view('pages.admin.programmeAccademique.create');
    }

    // Stocker un nouveau programme
    public function store(Request $request)
    {
        $request->validate([
            'date_ouverture_inscription' => 'required|date',
            'date_fermeture_inscription' => 'required|date|after_or_equal:date_ouverture_inscription',
            'annee_accademique' => 'required|string|max:9',
            'etat' => 'nullable|boolean',
        ]);

        ProgrammeAccademique::create([
            'date_ouverture_inscription' => $request->date_ouverture_inscription,
            'date_fermeture_inscription' => $request->date_fermeture_inscription,
            'annee_accademique' => $request->annee_accademique,
            'etat' => $request->has('etat'), // actif si coché
        ]);

        return redirect()->route('programme.index')->with('success', 'Programme académique créé avec succès.');
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $programme = ProgrammeAccademique::findOrFail($id);
        return view('pages.admin.programmeAccademique.edit', compact('programme'));
    }

    // Mettre à jour le programme
    public function update(Request $request, $id)
    {
        $programme = ProgrammeAccademique::findOrFail($id);

        $request->validate([
            'date_ouverture_inscription' => 'required|date',
            'date_fermeture_inscription' => 'required|date|after_or_equal:date_ouverture_inscription',
            'annee_accademique' => 'required|string|max:9',
            'etat' => 'nullable|boolean',
        ]);

        $programme->update([
            'date_ouverture_inscription' => $request->date_ouverture_inscription,
            'date_fermeture_inscription' => $request->date_fermeture_inscription,
            'annee_accademique' => $request->annee_accademique,
            'etat' => $request->has('etat'),
        ]);

        return redirect()->route('programme.index')->with('success', 'Programme académique mis à jour avec succès.');
    }

    // Activer / désactiver le programme
    public function toggleEtat($id)
    {
        $programme = ProgrammeAccademique::findOrFail($id);
        $programme->etat = !$programme->etat;
        $programme->save();

        $status = $programme->etat ? 'activé' : 'désactivé';
        return redirect()->route('programme.index')->with('success', "Programme {$status} avec succès.");
    }
}
