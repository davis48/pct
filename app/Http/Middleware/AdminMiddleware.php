<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        \Log::debug('Admin middleware triggered for path: ' . $request->path());
        \Log::debug('User auth status: ' . (Auth::check() ? 'Authenticated' : 'Not authenticated'));

        if (Auth::check()) {
            \Log::debug('User role: ' . Auth::user()->role);
        }

        // Vérifier si l'utilisateur est connecté et a le rôle admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            \Log::debug('Admin access granted');
            return $next($request);
        }

        \Log::debug('Admin access denied, redirecting to connexion');

        // Rediriger vers la page de connexion avec un message d'erreur
        return redirect('/connexion')->with('error', 'Accès non autorisé. Vous devez être administrateur.');
    }
}
