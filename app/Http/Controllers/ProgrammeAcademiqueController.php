<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgrammeAccademique;

class ProgrammeAcademiqueController extends Controller
{
    public function create()
    {
        return view('pages.admin.programmeAccademique.create');
    }

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
            'etat' => $request->has('etat'), // renvoie true si coché, false sinon
        ]);

        return redirect()->back()->with('success', 'Programme académique créé avec succès.');
    }
}
