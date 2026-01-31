<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifier si l'utilisateur a le rôle admin
        $user = Auth::user();

        // Méthode 1: Vérifier un champ is_admin dans la table users
        // if (isset($user->is_admin) && $user->is_admin == 1) {
        //     return $next($request);
        // }

        // Méthode 2: Vérifier via un rôle (si vous utilisez un système de rôles)
        if ($user->hasAnyRole(['admin', 'dev'])) {
            return $next($request);
        }

        // Méthode 3: Vérifier l'email (solution temporaire)
        // $adminEmails = ['admin@example.com', 'superadmin@example.com'];
        // if (in_array($user->email, $adminEmails)) {
        //     return $next($request);
        // }

        // Si aucune condition n'est remplie, rediriger avec erreur
        return redirect()->route('dashboard')
            ->with('danger', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
