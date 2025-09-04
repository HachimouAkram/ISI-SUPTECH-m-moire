<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Affiche le formulaire
    public function create()
    {
        return view('admin.create-user'); // le fichier Blade que tu as créé
    }

    // Enregistre le nouvel administrateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'sexe' => 'required|in:Homme,Femme,Autre',
            'date_naissance' => 'required|date',
        ]);

        // Création de l'utilisateur admin avec mot de passe par défaut
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role' => 'admin',
            'telephone' => $request->telephone,
            'sexe' => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'password' => Hash::make('passer123'), // mot de passe par défaut
            'must_change_password' => true,
            'is_verified' => false,
            'email_verification_code' => rand(100000, 999999), // code de vérification
        ]);

        return redirect()->back()->with('success', 'Administrateur créé avec succès !');
    }
}
