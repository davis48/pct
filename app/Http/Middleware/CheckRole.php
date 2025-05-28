<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Gets an array of roles from the middleware parameters
        $userRoles = is_array($roles) ? $roles : [$roles];

        // Check if the user has any of the required roles
        if (!in_array(Auth::user()->role, $userRoles)) {
            // If user doesn't have the required role, redirect based on their actual role
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect('/admin/dashboard')->with('error', 'Accès non autorisé.');
                case 'agent':
                    return redirect('/agent/dashboard')->with('error', 'Accès non autorisé.');
                default:
                    return redirect('/dashboard')->with('error', 'Accès non autorisé.');
            }
        }

        return $next($request);
    }
}
