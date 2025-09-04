<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        // Authentification normale
        if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        // Vérifier si le mot de passe par défaut est utilisé
        if (Hash::check('passer123', $user->password)) {
            $request->session()->put('email', $user->email);
            return redirect()->route('password.change.notice');
        }

        // Vérifier si le compte est vérifié
        if (!$user->is_verified) {
            Auth::logout();
            $request->session()->put('email', $user->email);
            Mail::to($user->email)->send(new VerificationCodeMail($user->email_verification_code));

            return redirect()->route('verify.code')->with('email', $user->email);
        }

        // Authentification normale
        if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
