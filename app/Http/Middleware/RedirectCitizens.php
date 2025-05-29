<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectCitizens
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'citizen') {
            // Si un citoyen accède à /dashboard, le rediriger vers son espace
            if ($request->is('dashboard')) {
                return redirect()->route('citizen.dashboard');
            }
        }

        return $next($request);
    }
}
