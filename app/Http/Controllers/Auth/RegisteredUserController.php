<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['required', 'string', 'max:20'],
            'sexe' => ['required', 'in:Homme,Femme,Autre'],
            'date_naissance' => ['required', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'sexe' => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'password' => Hash::make($request->password),
            'role' => 'etudiant', // par défaut
            'email_verification_code' => rand(100000, 999999),
        ]);

        // Envoyer le mail
        Mail::to($user->email)->send(new VerificationCodeMail($user->email_verification_code));

        return redirect()->route('verify.code')->with('email', $user->email);
    }
}

