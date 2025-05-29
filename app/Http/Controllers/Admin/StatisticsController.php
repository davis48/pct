<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\CitizenRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Affiche la page des statistiques
     */
    public function index()
    {
        // Récupérer les statistiques générales
        $stats = [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalAgents' => User::where('role', 'agent')->count(),
            'totalRequests' => CitizenRequest::count(),
            'totalDocuments' => Document::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'inProgressRequests' => CitizenRequest::where('status', 'in_progress')->count(),
            'completedRequests' => CitizenRequest::whereIn('status', ['approved', 'rejected'])->count(),
            'completedToday' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                          ->whereDate('updated_at', today())
                                          ->count(),
            'monthlyTotal' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                       ->whereYear('updated_at', now()->year)
                                       ->whereMonth('updated_at', now()->month)
                                       ->count(),
        ];

        // Données pour les graphiques
        $chartData = $this->getChartData();
        
        // Performance des agents
        $agentsPerformance = $this->getAgentsPerformance();
        
        // Statistiques par document
        $documentStats = $this->getDocumentStats();

        return view('admin.statistics.index', compact('stats', 'chartData', 'agentsPerformance', 'documentStats'));
    }
    
    /**
     * Affiche les statistiques d'un agent spécifique
     */
    public function agentStats($id)
    {
        $agent = User::findOrFail($id);
        
        // Vérifier que c'est bien un agent
        if ($agent->role !== 'agent') {
            return redirect()->route('admin.statistics.index')->with('error', 'Cet utilisateur n\'est pas un agent.');
        }
        
        // Statistiques de l'agent
        $stats = [
            'totalAssigned' => CitizenRequest::where('assigned_to', $agent->id)->count(),
            'totalProcessed' => CitizenRequest::where('processed_by', $agent->id)->count(),
            'inProgress' => CitizenRequest::where('assigned_to', $agent->id)
                                        ->where('status', 'in_progress')
                                        ->count(),
            'approved' => CitizenRequest::where('processed_by', $agent->id)
                                      ->where('status', 'approved')
                                      ->count(),
            'rejected' => CitizenRequest::where('processed_by', $agent->id)
                                      ->where('status', 'rejected')
                                      ->count(),
            'completedToday' => CitizenRequest::where('processed_by', $agent->id)
                                          ->whereIn('status', ['approved', 'rejected'])
                                          ->whereDate('updated_at', today())
                                          ->count(),
            'monthlyTotal' => CitizenRequest::where('processed_by', $agent->id)
                                       ->whereIn('status', ['approved', 'rejected'])
                                       ->whereYear('updated_at', now()->year)
                                       ->whereMonth('updated_at', now()->month)
                                       ->count(),
            'averageProcessingTime' => $this->getAverageProcessingTime($agent->id),
        ];
        
        // Données pour les graphiques
        $chartData = $this->getAgentChartData($agent->id);
        
        // Statistiques par document pour cet agent
        $documentStats = $this->getAgentDocumentStats($agent->id);
        
        // Historique des traitements
        $processedRequests = CitizenRequest::with(['document', 'user'])
                                          ->where('processed_by', $agent->id)
                                          ->orderBy('updated_at', 'desc')
                                          ->paginate(10);
        
        return view('admin.statistics.agent', compact('agent', 'stats', 'chartData', 'documentStats', 'processedRequests'));
    }
    
    /**
     * Affiche les statistiques par type de document
     */
    public function documentStats($id)
    {
        $document = Document::findOrFail($id);
        
        // Statistiques du document
        $stats = [
            'totalRequests' => CitizenRequest::where('document_id', $document->id)->count(),
            'pendingRequests' => CitizenRequest::where('document_id', $document->id)
                                             ->where('status', 'pending')
                                             ->count(),
            'inProgressRequests' => CitizenRequest::where('document_id', $document->id)
                                                ->where('status', 'in_progress')
                                                ->count(),
            'approvedRequests' => CitizenRequest::where('document_id', $document->id)
                                              ->where('status', 'approved')
                                              ->count(),
            'rejectedRequests' => CitizenRequest::where('document_id', $document->id)
                                              ->where('status', 'rejected')
                                              ->count(),
            'completedToday' => CitizenRequest::where('document_id', $document->id)
                                          ->whereIn('status', ['approved', 'rejected'])
                                          ->whereDate('updated_at', today())
                                          ->count(),
            'monthlyTotal' => CitizenRequest::where('document_id', $document->id)
                                       ->whereIn('status', ['approved', 'rejected'])
                                       ->whereYear('updated_at', now()->year)
                                       ->whereMonth('updated_at', now()->month)
                                       ->count(),
            'averageProcessingTime' => $this->getDocumentAverageProcessingTime($document->id),
        ];
        
        // Données pour les graphiques
        $chartData = $this->getDocumentChartData($document->id);
        
        // Top agents pour ce document
        $topAgents = $this->getTopAgentsForDocument($document->id);
        
        // Historique des demandes
        $requests = CitizenRequest::with(['user', 'assignedAgent', 'processedBy'])
                                 ->where('document_id', $document->id)
                                 ->orderBy('updated_at', 'desc')
                                 ->paginate(10);
        
        return view('admin.statistics.document', compact('document', 'stats', 'chartData', 'topAgents', 'requests'));
    }
    
    /**
     * Récupère les données pour le tableau de bord (format API)
     */
    public function getDashboardStats()
    {
        $stats = [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalAgents' => User::where('role', 'agent')->count(),
            'totalRequests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'inProgressRequests' => CitizenRequest::where('status', 'in_progress')->count(),
            'completedRequests' => CitizenRequest::whereIn('status', ['approved', 'rejected'])->count(),
            'completedToday' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                          ->whereDate('updated_at', today())
                                          ->count(),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Récupère les données pour les graphiques
     */
    private function getChartData()
    {
        // Données pour le graphique d'activité (30 derniers jours)
        $activityData = [];
        $labels = [];
        
        // Générer les labels et initialiser les données
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $activityData['pending'][] = 0;
            $activityData['approved'][] = 0;
            $activityData['rejected'][] = 0;
        }
        
        // Demandes en attente créées par jour
        $pendingRequests = CitizenRequest::where('status', 'pending')
                                      ->where('created_at', '>=', now()->subDays(30))
                                      ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                      ->groupBy('date')
                                      ->get();
        
        // Demandes approuvées par jour
        $approvedRequests = CitizenRequest::where('status', 'approved')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Demandes rejetées par jour
        $rejectedRequests = CitizenRequest::where('status', 'rejected')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Remplir les données
        foreach ($pendingRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['pending'][$index] = $request->count;
            }
        }
        
        foreach ($approvedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['approved'][$index] = $request->count;
            }
        }
        
        foreach ($rejectedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['rejected'][$index] = $request->count;
            }
        }
        
        // Répartition par type de document
        $documentDistribution = CitizenRequest::select('document_id', DB::raw('count(*) as total'))
                                           ->with('document:id,title')
                                           ->groupBy('document_id')
                                           ->get()
                                           ->map(function ($item) {
                                               return [
                                                   'label' => $item->document ? $item->document->title : 'Document inconnu',
                                                   'value' => $item->total
                                               ];
                                           });
        
        // Répartition par statut
        $statusDistribution = [
            ['label' => 'En attente', 'value' => CitizenRequest::where('status', 'pending')->count()],
            ['label' => 'En cours', 'value' => CitizenRequest::where('status', 'in_progress')->count()],
            ['label' => 'Approuvées', 'value' => CitizenRequest::where('status', 'approved')->count()],
            ['label' => 'Rejetées', 'value' => CitizenRequest::where('status', 'rejected')->count()],
        ];
        
        return [
            'labels' => $labels,
            'activityData' => $activityData,
            'documentDistribution' => $documentDistribution,
            'statusDistribution' => $statusDistribution,
        ];
    }
    
    /**
     * Récupère les performances des agents
     */
    private function getAgentsPerformance()
    {
        // Récupérer tous les agents
        $agents = User::where('role', 'agent')->get();
        
        $performance = [];
        
        foreach ($agents as $agent) {
            // Nombre de demandes traitées
            $processedCount = CitizenRequest::where('processed_by', $agent->id)
                                          ->whereIn('status', ['approved', 'rejected'])
                                          ->count();
            
            // Nombre de demandes traitées aujourd'hui
            $processedToday = CitizenRequest::where('processed_by', $agent->id)
                                          ->whereIn('status', ['approved', 'rejected'])
                                          ->whereDate('updated_at', today())
                                          ->count();
            
            // Nombre de demandes en cours
            $inProgress = CitizenRequest::where('assigned_to', $agent->id)
                                      ->where('status', 'in_progress')
                                      ->count();
            
            // Temps moyen de traitement
            $avgTime = $this->getAverageProcessingTime($agent->id);
            
            $performance[] = [
                'id' => $agent->id,
                'name' => $agent->nom . ' ' . $agent->prenoms,
                'email' => $agent->email,
                'processedCount' => $processedCount,
                'processedToday' => $processedToday,
                'inProgress' => $inProgress,
                'avgProcessingTime' => $avgTime,
            ];
        }
        
        // Trier par nombre de demandes traitées (décroissant)
        usort($performance, function ($a, $b) {
            return $b['processedCount'] - $a['processedCount'];
        });
        
        return $performance;
    }
    
    /**
     * Récupère les statistiques par document
     */
    private function getDocumentStats()
    {
        // Récupérer tous les documents
        $documents = Document::all();
        
        $stats = [];
        
        foreach ($documents as $document) {
            // Nombre total de demandes
            $totalCount = CitizenRequest::where('document_id', $document->id)->count();
            
            // Nombre de demandes en attente
            $pendingCount = CitizenRequest::where('document_id', $document->id)
                                        ->where('status', 'pending')
                                        ->count();
            
            // Nombre de demandes approuvées
            $approvedCount = CitizenRequest::where('document_id', $document->id)
                                         ->where('status', 'approved')
                                         ->count();
            
            // Nombre de demandes rejetées
            $rejectedCount = CitizenRequest::where('document_id', $document->id)
                                         ->where('status', 'rejected')
                                         ->count();
            
            // Temps moyen de traitement
            $avgTime = $this->getDocumentAverageProcessingTime($document->id);
            
            $stats[] = [
                'id' => $document->id,
                'title' => $document->title,
                'totalCount' => $totalCount,
                'pendingCount' => $pendingCount,
                'approvedCount' => $approvedCount,
                'rejectedCount' => $rejectedCount,
                'avgProcessingTime' => $avgTime,
            ];
        }
        
        // Trier par nombre total de demandes (décroissant)
        usort($stats, function ($a, $b) {
            return $b['totalCount'] - $a['totalCount'];
        });
        
        return $stats;
    }
    
    /**
     * Récupère le temps moyen de traitement pour un agent
     */
    private function getAverageProcessingTime($agentId)
    {
        $requests = CitizenRequest::where('processed_by', $agentId)
                                 ->whereIn('status', ['approved', 'rejected'])
                                 ->whereNotNull('processed_at')
                                 ->get();
        
        if ($requests->isEmpty()) {
            return 'N/A';
        }
        
        $totalHours = 0;
        $count = 0;
        
        foreach ($requests as $request) {
            // Si la demande a été assignée à cet agent
            if ($request->assigned_to == $agentId) {
                $createdAt = $request->created_at;
                $processedAt = $request->processed_at;
                
                $diffInHours = $createdAt->diffInHours($processedAt);
                $totalHours += $diffInHours;
                $count++;
            }
        }
        
        if ($count == 0) {
            return 'N/A';
        }
        
        $avgHours = round($totalHours / $count, 1);
        
        if ($avgHours < 24) {
            return $avgHours . ' heures';
        } else {
            $days = floor($avgHours / 24);
            $remainingHours = $avgHours % 24;
            return $days . ' jour(s) ' . $remainingHours . ' heure(s)';
        }
    }
    
    /**
     * Récupère le temps moyen de traitement pour un document
     */
    private function getDocumentAverageProcessingTime($documentId)
    {
        $requests = CitizenRequest::where('document_id', $documentId)
                                 ->whereIn('status', ['approved', 'rejected'])
                                 ->whereNotNull('processed_at')
                                 ->get();
        
        if ($requests->isEmpty()) {
            return 'N/A';
        }
        
        $totalHours = 0;
        $count = 0;
        
        foreach ($requests as $request) {
            $createdAt = $request->created_at;
            $processedAt = $request->processed_at;
            
            $diffInHours = $createdAt->diffInHours($processedAt);
            $totalHours += $diffInHours;
            $count++;
        }
        
        if ($count == 0) {
            return 'N/A';
        }
        
        $avgHours = round($totalHours / $count, 1);
        
        if ($avgHours < 24) {
            return $avgHours . ' heures';
        } else {
            $days = floor($avgHours / 24);
            $remainingHours = $avgHours % 24;
            return $days . ' jour(s) ' . $remainingHours . ' heure(s)';
        }
    }
    
    /**
     * Récupère les données de graphique pour un agent spécifique
     */
    private function getAgentChartData($agentId)
    {
        // Données pour le graphique de performance (30 derniers jours)
        $performanceData = [];
        $labels = [];
        
        // Générer les labels et initialiser les données
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $performanceData['approved'][] = 0;
            $performanceData['rejected'][] = 0;
        }
        
        // Demandes approuvées par jour
        $approvedRequests = CitizenRequest::where('processed_by', $agentId)
                                       ->where('status', 'approved')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Demandes rejetées par jour
        $rejectedRequests = CitizenRequest::where('processed_by', $agentId)
                                       ->where('status', 'rejected')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Remplir les données
        foreach ($approvedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $performanceData['approved'][$index] = $request->count;
            }
        }
        
        foreach ($rejectedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $performanceData['rejected'][$index] = $request->count;
            }
        }
        
        // Répartition par type de document
        $documentDistribution = CitizenRequest::where('processed_by', $agentId)
                                           ->select('document_id', DB::raw('count(*) as total'))
                                           ->with('document:id,title')
                                           ->groupBy('document_id')
                                           ->get()
                                           ->map(function ($item) {
                                               return [
                                                   'label' => $item->document ? $item->document->title : 'Document inconnu',
                                                   'value' => $item->total
                                               ];
                                           });
        
        return [
            'labels' => $labels,
            'performanceData' => $performanceData,
            'documentDistribution' => $documentDistribution,
        ];
    }
    
    /**
     * Récupère les statistiques par document pour un agent spécifique
     */
    private function getAgentDocumentStats($agentId)
    {
        return CitizenRequest::where('processed_by', $agentId)
                          ->select('document_id', DB::raw('count(*) as total'))
                          ->with('document:id,title')
                          ->groupBy('document_id')
                          ->get()
                          ->map(function ($item) use ($agentId) {
                              // Nombre de demandes approuvées pour ce document
                              $approvedCount = CitizenRequest::where('processed_by', $agentId)
                                                          ->where('document_id', $item->document_id)
                                                          ->where('status', 'approved')
                                                          ->count();
                              
                              // Nombre de demandes rejetées pour ce document
                              $rejectedCount = CitizenRequest::where('processed_by', $agentId)
                                                          ->where('document_id', $item->document_id)
                                                          ->where('status', 'rejected')
                                                          ->count();
                              
                              return [
                                  'id' => $item->document_id,
                                  'title' => $item->document ? $item->document->title : 'Document inconnu',
                                  'total' => $item->total,
                                  'approved' => $approvedCount,
                                  'rejected' => $rejectedCount,
                              ];
                          });
    }
    
    /**
     * Récupère les données de graphique pour un document spécifique
     */
    private function getDocumentChartData($documentId)
    {
        // Données pour le graphique d'activité (30 derniers jours)
        $activityData = [];
        $labels = [];
        
        // Générer les labels et initialiser les données
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $activityData['created'][] = 0;
            $activityData['approved'][] = 0;
            $activityData['rejected'][] = 0;
        }
        
        // Demandes créées par jour
        $createdRequests = CitizenRequest::where('document_id', $documentId)
                                      ->where('created_at', '>=', now()->subDays(30))
                                      ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                      ->groupBy('date')
                                      ->get();
        
        // Demandes approuvées par jour
        $approvedRequests = CitizenRequest::where('document_id', $documentId)
                                       ->where('status', 'approved')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Demandes rejetées par jour
        $rejectedRequests = CitizenRequest::where('document_id', $documentId)
                                       ->where('status', 'rejected')
                                       ->where('updated_at', '>=', now()->subDays(30))
                                       ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                                       ->groupBy('date')
                                       ->get();
        
        // Remplir les données
        foreach ($createdRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['created'][$index] = $request->count;
            }
        }
        
        foreach ($approvedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['approved'][$index] = $request->count;
            }
        }
        
        foreach ($rejectedRequests as $request) {
            $date = Carbon::parse($request->date);
            $daysAgo = now()->diffInDays($date);
            if ($daysAgo <= 29) {
                $index = 29 - $daysAgo;
                $activityData['rejected'][$index] = $request->count;
            }
        }
        
        // Répartition par statut
        $statusDistribution = [
            ['label' => 'En attente', 'value' => CitizenRequest::where('document_id', $documentId)->where('status', 'pending')->count()],
            ['label' => 'En cours', 'value' => CitizenRequest::where('document_id', $documentId)->where('status', 'in_progress')->count()],
            ['label' => 'Approuvées', 'value' => CitizenRequest::where('document_id', $documentId)->where('status', 'approved')->count()],
            ['label' => 'Rejetées', 'value' => CitizenRequest::where('document_id', $documentId)->where('status', 'rejected')->count()],
        ];
        
        return [
            'labels' => $labels,
            'activityData' => $activityData,
            'statusDistribution' => $statusDistribution,
        ];
    }
    
    /**
     * Récupère les top agents pour un document spécifique
     */
    private function getTopAgentsForDocument($documentId)
    {
        return CitizenRequest::where('document_id', $documentId)
                          ->whereIn('status', ['approved', 'rejected'])
                          ->select('processed_by', DB::raw('count(*) as total'))
                          ->with('processedBy:id,nom,prenoms')
                          ->groupBy('processed_by')
                          ->orderBy('total', 'desc')
                          ->take(5)
                          ->get()
                          ->map(function ($item) use ($documentId) {
                              // Nombre de demandes approuvées pour ce document et cet agent
                              $approvedCount = CitizenRequest::where('processed_by', $item->processed_by)
                                                          ->where('document_id', $documentId)
                                                          ->where('status', 'approved')
                                                          ->count();
                              
                              // Nombre de demandes rejetées pour ce document et cet agent
                              $rejectedCount = CitizenRequest::where('processed_by', $item->processed_by)
                                                          ->where('document_id', $documentId)
                                                          ->where('status', 'rejected')
                                                          ->count();
                              
                              return [
                                  'id' => $item->processed_by,
                                  'name' => $item->processedBy ? $item->processedBy->nom . ' ' . $item->processedBy->prenoms : 'Agent inconnu',
                                  'total' => $item->total,
                                  'approved' => $approvedCount,
                                  'rejected' => $rejectedCount,
                              ];
                          });
    }
}
