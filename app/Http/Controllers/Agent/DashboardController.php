<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord agent
     */
    public function index()
    {
        // Statistiques pour l'agent avec toutes les clés attendues par la vue
        $stats = [
            // Clés pour le menu de navigation
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())
                                                 ->whereIn('status', ['pending', 'in_progress'])
                                                 ->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                                                  ->whereIn('status', ['approved', 'rejected'])
                                                  ->count(),
            
            // Clés pour les statistiques du tableau de bord
            'pending' => CitizenRequest::where('status', 'pending')->count(),
            'assigned' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'completed_today' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                              ->whereDate('updated_at', today())
                                              ->count(),
            'monthly_total' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                           ->whereYear('updated_at', now()->year)
                                           ->whereMonth('updated_at', now()->month)
                                           ->count(),
            
            // Statistiques supplémentaires
            'totalRequests' => CitizenRequest::count(),
            'pendingAssigned' => CitizenRequest::where('status', 'pending')
                                              ->whereNotNull('assigned_to')
                                              ->count(),
            'allInProgress' => CitizenRequest::where('status', 'in_progress')->count(),
            'allApproved' => CitizenRequest::where('status', 'approved')->count(),
            'allRejected' => CitizenRequest::where('status', 'rejected')->count(),
        ];

        // Demandes récentes à traiter
        $pendingRequests = CitizenRequest::with(['user', 'document'])
                         ->where('status', 'pending')
                         ->latest()
                         ->take(10)
                         ->get();

        // Mes assignations
        $myAssignments = CitizenRequest::with(['user', 'document'])
                       ->where('assigned_to', Auth::id())
                       ->whereIn('status', ['pending', 'in_progress'])
                       ->latest()
                       ->take(5)
                       ->get();
                       
        // Données pour les graphiques
        $chartData = $this->getChartData();

        return view('agent.dashboard', compact('stats', 'pendingRequests', 'myAssignments', 'chartData'));
    }
    
    /**
     * Récupérer les données pour les graphiques
     */
    private function getChartData()
    {
        // Données pour le graphique de performance (7 derniers jours)
        $performanceData = [];
        $labels = [];
        
        // Générer les labels et initialiser les données à 0
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D'); // Jour abrégé
            $performanceData[] = 0;
        }
        
        // Requête pour les demandes traitées dans les 7 derniers jours
        $processedRequests = CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                         ->where('updated_at', '>=', now()->subDays(7))
                                         ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                         ->groupBy('date')
                                         ->get();
                                         
        // Remplir les données
        foreach ($processedRequests as $request) {
            $date = \Carbon\Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 6) {
                $index = 6 - $daysAgo;
                $performanceData[$index] = $request->count;
            }
        }
        
        // Données pour le graphique des types de documents
        $documentTypes = CitizenRequest::selectRaw('type, COUNT(*) as count')
                                     ->groupBy('type')
                                     ->get()
                                     ->map(function ($item) {
                                         return [
                                             'label' => ucfirst($item->type),
                                             'count' => $item->count
                                         ];
                                     });
        
        return [
            'performance' => [
                'labels' => $labels,
                'data' => $performanceData
            ],
            'documentTypes' => $documentTypes
        ];
    }

    /**
     * Assigner la prochaine demande à l'agent connecté
     */
    public function assignNext(Request $request)
    {
        $nextRequest = CitizenRequest::where('status', 'pending')
                     ->whereNull('assigned_to')
                     ->oldest()
                     ->first();

        if ($nextRequest) {
            $nextRequest->update([
                'assigned_to' => Auth::id(),
                'status' => 'in_progress'
            ]);

            // Redirection vers la page de traitement de la demande
            return redirect()->route('agent.requests.process', $nextRequest->id)
                ->with('success', 'Demande assignée avec succès. Vous pouvez maintenant la traiter.');
        }

        // Aucune demande disponible, retour à la page précédente avec un message
        return back()->with('warning', 'Aucune demande en attente n\'est disponible actuellement.');
    }

    /**
     * Obtenir les notifications pour l'agent
     */
    public function getNotifications(Request $request)
    {
        $notifications = [
            'pending_count' => CitizenRequest::where('status', 'pending')->count(),
            'my_assignments_count' => CitizenRequest::where('assigned_to', Auth::id())
                                                   ->whereIn('status', ['pending', 'in_progress'])
                                                   ->count(),
            'urgent_requests' => CitizenRequest::where('created_at', '<=', now()->subDays(3))
                                              ->where('status', 'pending')
                                              ->count()
        ];

        return response()->json($notifications);
    }

    /**
     * API pour récupérer les données des graphiques
     */
    public function getChartDataApi()
    {
        return response()->json($this->getChartData());
    }
    
    /**
     * API pour récupérer les statistiques du tableau de bord
     */
    public function getDashboardStats()
    {
        $stats = [
            'pending' => CitizenRequest::where('status', 'pending')->count(),
            'assigned' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'completed_today' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                              ->whereDate('updated_at', today())
                                              ->count(),
            'monthly_total' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                           ->whereYear('updated_at', now()->year)
                                           ->whereMonth('updated_at', now()->month)
                                           ->count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())
                                                 ->whereIn('status', ['pending', 'in_progress'])
                                                 ->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                                                  ->whereIn('status', ['approved', 'rejected'])
                                                  ->count(),
        ];
        
        return response()->json($stats);
    }
}
