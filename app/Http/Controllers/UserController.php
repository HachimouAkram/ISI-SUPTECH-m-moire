<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GenerateApiResponse;
use App\Models\User;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\ProgrammeAccademique;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use GenerateApiResponse;

    public function index()
    {
        /** @var User $user */
        $perPage = request()->get('per_page', 10);
        $search = request()->get('search');

        $etudiantsQuery = User::where('role', 'etudiant');

        if ($search) {
            $etudiantsQuery->where(function($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        $etudiants = $etudiantsQuery->paginate($perPage, ['*'], 'etudiants_page');

        $admins = User::where('role', 'admin')->paginate($perPage, ['*'], 'admins_page');
        $formations = Formation::all();
        $programmeActif = ProgrammeAccademique::where('etat', '1')->first();
        $inscriptionsEnCours = Inscription::where('statut', 'Encours')
            ->whereIn('user_id', $etudiants->pluck('id'))
            ->get()
            ->groupBy('user_id');

        return view('pages.admin.user.liste', compact('etudiants', 'admins', 'formations', 'programmeActif', 'inscriptionsEnCours'));


    }


    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
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

    public function storeEtudiant(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'sexe' => 'required|in:Homme,Femme,Autre',
            'date_naissance' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']); // Hash mot de passe
        $validated['role'] = 'etudiant';
        $validated['fonction'] = null;

        $user = User::create($validated); // ⚠️ assigner à $user

        // 🔹 Log
        activity()
            ->causedBy(Auth::user())  // admin connecté ou étudiant lui-même
            ->performedOn($user)
            ->withProperties([
                'role' => $user->role,
                'email' => $user->email,
            ])
            ->log("Étudiant ajouté");

        return redirect()->route('users.index')->with('success', '✅ Étudiant ajouté avec succès.');

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

            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties([
                    'role' => $user->role,
                    'email' => $user->email,
                ])
                ->log("Utilisateur modifié");


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

            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties([
                    'role' => $user->role,
                    'email' => $user->email,
                ])
                ->log("Utilisateur supprimé");

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
