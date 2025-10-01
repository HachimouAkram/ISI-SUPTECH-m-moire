<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\GenerateApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    use GenerateApiResponse;
    public function indexAdmin()
    {
        // Récupération dynamique du nombre par page (valeur par défaut = 10)
        $perPage = request()->get('per_page', 10);

        // Récupérer uniquement les utilisateurs avec le rôle "admin"
        $admins = User::where('role', 'admin')->paginate($perPage);

        // Retourner une vue dédiée à la liste des admins
        return view('pages.admin.user.liste-admin', compact('admins'));
    }

    public function parametre()
    {
        $role = Role::find(1); // ou récupère dynamiquement selon le contexte

        return view('pages.admin.parametre.liste', compact('role'));
        // le fichier Blade que tu as créé
    }


    // Affiche le formulaire de code secret
    public function setup()
    {
        return view('setup.code'); // page avec champ pour le code
    }

    // Vérifie le code secret
    public function checkSetup(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if ($request->code === env('SETUP_CODE')) {
            session(['setup_validated' => true]);
            return redirect()->route('admin.create');
        }

        return back()->withErrors(['code' => 'Code secret invalide']);
    }

    // Page création admin
    public function create(Request $request)
    {
        if (!session('setup_validated')) {
            return redirect()->route('setup.form')->with('error', 'Accès refusé');
        }

        return view('admin.create-user');
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
            'fonction' => 'required|in:Directeur,Secretaire,Tresorier',
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
            'fonction' => $request->fonction,
            'email_verification_code' => rand(100000, 999999), // code de vérification
        ]);

        // 🔹 Enregistrer le log de création
        activity()
            ->causedBy(Auth::user()) // l'admin connecté
            ->performedOn($user)       // le nouvel admin créé
            ->withProperties([
                'role' => $user->role,
                'email' => $user->email,
            ])
            ->log('Administrateur ajouté');

        return redirect()->back()->with('success', 'Administrateur créé avec succès !');
    }
}
