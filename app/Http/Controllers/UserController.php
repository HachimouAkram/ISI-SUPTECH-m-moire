<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    use GenerateApiResponse;

    public function index()
    {
        // Récupération dynamique du nombre par page (valeur par défaut = 10)
        $perPage = request()->get('per_page', 10);

        // Séparer les utilisateurs par rôle
        $etudiants = User::where('role', 'etudiant')->paginate($perPage, ['*'], 'etudiants_page');
        $admins = User::where('role', 'admin')->paginate($perPage, ['*'], 'admins_page');
        return view('pages.admin.user.liste', compact('etudiants', 'admins'));
        //$perPage = request()->get('per_page', 10); // valeur par défaut : 10
        //$users = User::paginate($perPage);
        //return view('pages.admin.user.liste', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // ✅ champ corrigé
            $user->role = $request->role;
            $user->telephone = $request->telephone;
            $user->sexe = $request->sexe;
            $user->date_naissance = $request->date_naissance;
            $user->save();

            return $this->successResponse($user, 'Utilisateur créé avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Insertion échouée', 500, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password); // ✅ mise à jour sécurisée
            }

            $user->role = $request->role;
            $user->telephone = $request->telephone;
            $user->sexe = $request->sexe;
            $user->date_naissance = $request->date_naissance;
            $user->save();

            return $this->successResponse($user, 'Mise à jour réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Mise à jour échouée', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return $this->successResponse($user, 'Suppression réussie');
        } catch (Exception $e) {
            return $this->errorResponse('Suppression échouée', 500, $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->successResponse($user, 'Ressource trouvée');
        } catch (Exception $e) {
            return $this->errorResponse('Ressource non trouvée', 404, $e->getMessage());
        }
    }

    public function getformdetails()
    {
        try {
            return $this->successResponse([], 'Données du formulaire récupérées avec succès');
        } catch (Exception $e) {
            return $this->errorResponse('Erreur lors de la récupération des données du formulaire', 500, $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) { // ✅ ici aussi
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Email ou mot de passe incorrect.'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Connexion réussie',
                'data' => $user,
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Déconnexion réussie'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
