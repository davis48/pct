<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et a le rôle agent ou admin
        if (Auth::check() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        // Rediriger vers la page de connexion avec un message d'erreur
        return redirect('/connexion')->with('error', 'Accès non autorisé. Vous devez être agent.');
    }
}
