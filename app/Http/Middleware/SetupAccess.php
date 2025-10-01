<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetupAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'accès setup est validé en session
        if (!session()->has('setup_validated') || session('setup_validated') !== true) {
            return redirect()->route('setup.form')->with('error', 'Accès refusé. Entrez le code secret.');
        }

        return $next($request);
    }
}

