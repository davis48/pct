<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'pendingRequests' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())
                                                 ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                                 ->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                                                  ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                                  ->count(),
            'inProgressRequests' => CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count(),
            
            // Clés pour les statistiques du tableau de bord
            'pending' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count(),
            'in_progress' => CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count(),
            'assigned' => CitizenRequest::where('assigned_to', Auth::id())
                                       ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                       ->count(),
            'completed_today' => CitizenRequest::where('processed_by', Auth::id())
                                              ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                              ->whereDate('updated_at', today())
                                              ->count(),
            'monthly_total' => CitizenRequest::where('processed_by', Auth::id())
                                           ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                           ->whereYear('updated_at', now()->year)
                                           ->whereMonth('updated_at', now()->month)
                                           ->count(),
            'reminders_needed' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
                                             ->where('created_at', '<=', now()->subDays(3))
                                             ->count(),
            
            // Statistiques supplémentaires
            'totalRequests' => CitizenRequest::count(),
            'pendingAssigned' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
                                              ->whereNotNull('assigned_to')
                                              ->count(),
            'allInProgress' => CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count(),
            'allApproved' => CitizenRequest::where('status', CitizenRequest::STATUS_APPROVED)->count(),
            'allRejected' => CitizenRequest::where('status', CitizenRequest::STATUS_REJECTED)->count(),
        ];

        // Demandes récentes à traiter
        $pendingRequests = CitizenRequest::with(['user', 'document'])
                         ->where('status', CitizenRequest::STATUS_PENDING)
                         ->where('payment_status', CitizenRequest::PAYMENT_STATUS_PAID)
                         ->whereNull('assigned_to')
                         ->latest()
                         ->take(10)
                         ->get();

        // Mes assignations
        $myAssignments = CitizenRequest::with(['user', 'document'])
                       ->where('assigned_to', Auth::id())
                       ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                       ->latest()
                       ->take(5)
                       ->get();
                       
        // Demandes en cours
        $inProgressRequests = CitizenRequest::with(['user', 'document', 'assignedAgent'])
                            ->where('status', CitizenRequest::STATUS_IN_PROGRESS)
                            ->latest()
                            ->take(5)
                            ->get();
        
        // Demandes nécessitant un rappel (plus de 3 jours sans traitement)
        $remindersNeeded = CitizenRequest::with(['user', 'document'])
                         ->where('status', CitizenRequest::STATUS_PENDING)
                         ->where('created_at', '<=', now()->subDays(3))
                         ->latest()
                         ->take(5)
                         ->get();
                       
        // Données pour les graphiques
        $chartData = $this->getChartData();

        return view('agent.dashboard', compact('stats', 'pendingRequests', 'myAssignments', 'chartData', 'inProgressRequests', 'remindersNeeded'));
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
        // Utiliser toutes les demandes traitées, pas seulement par l'agent connecté au début
        $processedRequests = CitizenRequest::whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                         ->where('updated_at', '>=', now()->subDays(7))
                                         ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                         ->groupBy('date')
                                         ->get();
                                         
        // Remplir les données réelles
        foreach ($processedRequests as $request) {
            $date = \Carbon\Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 6) {
                $index = 6 - $daysAgo;
                if (isset($performanceData[$index])) {
                    $performanceData[$index] = (int)$request->count;
                }
            }
        }
        
        // Données pour le graphique des types de documents
        $documentTypes = CitizenRequest::with('document')
                                     ->whereHas('document')
                                     ->get()
                                     ->groupBy(function($request) {
                                         return $request->document ? $request->document->category : 'Non spécifié';
                                     })
                                     ->map(function ($requests, $category) {
                                         return [
                                             'label' => $category ?: 'Non spécifié',
                                             'count' => $requests->count()
                                         ];
                                     })
                                     ->values();
                                     
        // Si aucune donnée, ajouter des données de démo
        if ($documentTypes->isEmpty()) {
            $documentTypes = collect([
                ['label' => 'Passeport', 'count' => 0],
                ['label' => 'Carte d\'identité', 'count' => 0],
                ['label' => 'Certificat de naissance', 'count' => 0],
                ['label' => 'Autres', 'count' => 0]
            ]);
        }
        
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
    {        $nextRequest = CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
                                     ->where('payment_status', CitizenRequest::PAYMENT_STATUS_PAID)
                                     ->whereNull('assigned_to')
                                     ->oldest()
                                     ->first();

        if ($nextRequest) {
            $nextRequest->update([
                'assigned_to' => Auth::id(),
                'status' => CitizenRequest::STATUS_IN_PROGRESS
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
            'pending_count' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count(),
            'my_assignments_count' => CitizenRequest::where('assigned_to', Auth::id())
                                                   ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                                   ->count(),
            'urgent_requests' => CitizenRequest::where('created_at', '<=', now()->subDays(3))
                                              ->where('status', CitizenRequest::STATUS_PENDING)
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
            'pending' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count(),
            'assigned' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'completed_today' => CitizenRequest::whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                              ->whereDate('updated_at', today())
                                              ->count(),
            'monthly_total' => CitizenRequest::whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                           ->whereYear('updated_at', now()->year)
                                           ->whereMonth('updated_at', now()->month)
                                           ->count(),
            'pendingRequests' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())
                                                 ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                                 ->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                                                  ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                                  ->count(),
            'inProgressRequests' => CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count(),
            'remindersCount' => CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
                                             ->where('created_at', '<=', now()->subDays(3))
                                             ->count(),
        ];
        
        return response()->json($stats);
    }
}
