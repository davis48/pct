<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\CitizenRequest;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Nous appliquons déjà ce middleware au niveau des routes
        // donc nous n'avons pas besoin de l'appliquer ici
        // $this->middleware('admin');
    }

    /**
     * Affiche le tableau de bord admin
     */
    public function index()
    {
        // Récupérer les statistiques pour le dashboard
        $stats = [
            'users' => User::count(),
            'documents' => Document::count(),
            'requests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
        ];

        // Récupérer les dernières demandes
        $latestRequests = CitizenRequest::with(['user', 'document'])
                        ->latest()
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact('stats', 'latestRequests'));
    }
}
