<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    // Afficher le formulaire
    public function notice(Request $request)
    {
        $email = $request->session()->get('email'); // récupère l'email depuis la session
        return view('auth.change-password', compact('email'));
    }

    // Mettre à jour le mot de passe
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // Plus besoin de changer
        $user->save();

        // Rediriger vers la vérification email
        $request->session()->put('email', $user->email);
        // Envoyer le mail
        Mail::to($user->email)->send(new VerificationCodeMail($user->email_verification_code));

        return redirect()->route('verify.code')->with('email', $user->email);
    }
}
