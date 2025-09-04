<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('email')) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        return view('auth.verify-code');
    }


    public function verify(Request $request)
{
    $request->validate([
        'code'  => 'required|digits:6',
        'email' => 'required|email',
    ]);

    $email = $request->input('email');

    $user = User::where('email', $email)
                ->where('email_verification_code', $request->code)
                ->first();

    if ($user) {
        $user->is_verified = true;
        $user->email_verification_code = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Compte vérifié, vous pouvez vous connecter.');
    }

    return back()->withErrors(['code' => 'Code incorrect']);
}



    public function resend(Request $request)
    {
        $request->validate([
            'current_email' => 'required|email',
            'new_email' => 'nullable|email',
        ]);

        $email = $request->new_email ?? $request->current_email;

        $user = User::where('email', $request->current_email)->first();

        if (!$user) {
            return back()->withErrors(['current_email' => 'Utilisateur introuvable.']);
        }

        // Si l'email a changé, on le met à jour
        if ($request->new_email && $request->new_email !== $request->current_email) {
            $user->email = $request->new_email;
        }

        // Générer un nouveau code
        $code = random_int(100000, 999999);
        $user->email_verification_code = $code;
        $user->save();

        // Envoi du mail
        Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($code));

        // Mettre à jour la session
        session(['email' => $user->email]);

        return back();//->with('status', 'Un nouveau code a été envoyé à ' . $user->email);
    }

}
