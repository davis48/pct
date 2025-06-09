<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:agent']);
    }

    /**
     * Afficher la page des statistiques
     */
    public function index()
    {
        $globalStats = $this->getGlobalStats();
        $myStats = $this->getAgentStats(Auth::id());
        $weeklyPerformance = $this->getAgentWeeklyPerformance(Auth::id());

        return view('agent.statistics.index', compact('globalStats', 'myStats', 'weeklyPerformance'));
    }

    /**
     * Obtenir les statistiques globales
     */
    public function getGlobalStats()
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        return [
            'requests' => [
                'total' => CitizenRequest::count(),
                'pending' => CitizenRequest::where('status', 'pending')->count(),
                'processing' => CitizenRequest::where('status', 'processing')->count(), // Added processing status
                'approved' => CitizenRequest::where('status', 'approved')->count(),
                'completed' => CitizenRequest::where('status', 'approved')->count(), // Same as approved for now
                'rejected' => CitizenRequest::where('status', 'rejected')->count(),
                'this_month' => CitizenRequest::whereDate('created_at', '>=', $currentMonth)->count(),
                'last_month' => CitizenRequest::whereBetween('created_at', [$lastMonth, $currentMonth])->count(),
            ],
            'users' => [
                'total_citizens' => User::where('role', 'citizen')->count(),
                'total_agents' => User::where('role', 'agent')->count(),
                'new_citizens_this_month' => User::where('role', 'citizen')
                    ->whereDate('created_at', '>=', $currentMonth)->count(),
            ],
            'documents' => [
                'types_count' => Document::count(),
                'most_requested' => [
                    'name' => $this->getMostRequestedDocuments()->first()->title ?? 'N/A',
                    'count' => $this->getMostRequestedDocuments()->first()->citizen_requests_count ?? 0,
                ],
                'by_type' => $this->getDocumentsByType(),
            ],
            'processing' => [
                'average_time' => $this->getAvgProcessingTime() . 'h',
            ],
            'agents' => [
                'top_performers' => $this->getTopPerformers(),
            ],
            'recent_activity' => $this->getRecentActivity(),
        ];
    }

    /**
     * Obtenir les statistiques d'un agent
     */
    public function getAgentStats($agentId)
    {
        $currentMonth = now()->startOfMonth();
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();

        // Statistiques basées sur l'agent avec 'processed_by' ou 'assigned_to'
        $processedByAgent = CitizenRequest::where('processed_by', $agentId)
                                        ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED]);
        
        $assignedToAgent = CitizenRequest::where('assigned_to', $agentId);

        // Comptages exacts pour cet agent
        $totalProcessed = $processedByAgent->count();
        $approvedByAgent = CitizenRequest::where('processed_by', $agentId)
                                       ->where('status', CitizenRequest::STATUS_APPROVED)
                                       ->count();
        $rejectedByAgent = CitizenRequest::where('processed_by', $agentId)
                                       ->where('status', CitizenRequest::STATUS_REJECTED)
                                       ->count();

        // Statistiques temporelles personnalisées
        $processedToday = CitizenRequest::where('processed_by', $agentId)
                                      ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                      ->whereDate('updated_at', today())
                                      ->count();

        $processedThisWeek = CitizenRequest::where('processed_by', $agentId)
                                         ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                         ->where('updated_at', '>=', $thisWeek)
                                         ->count();

        $processedThisMonth = CitizenRequest::where('processed_by', $agentId)
                                          ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                          ->where('updated_at', '>=', $currentMonth)
                                          ->count();

        $assignedCurrent = $assignedToAgent->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                         ->count();

        // Taux de succès basé sur les vraies données
        $successRate = $totalProcessed > 0 ? round(($approvedByAgent / $totalProcessed) * 100, 1) : 0;

        // Temps de traitement moyen personnalisé
        $avgProcessingTime = $this->getAgentAvgProcessingTime($agentId);

        return [
            'total_assigned' => $assignedToAgent->count(),
            'total_processed' => $totalProcessed,
            'processing' => $assignedCurrent,
            'approved' => $approvedByAgent,
            'completed' => $totalProcessed,
            'rejected' => $rejectedByAgent,
            'processed_today' => $processedToday,
            'processed_this_week' => $processedThisWeek,
            'this_month' => $processedThisMonth,
            'avg_processing_time' => $avgProcessingTime,
            'performance_rating' => $this->calculatePerformanceRating($agentId),
            'success_rate' => $successRate,
        ];
    }

    /**
     * Obtenir les performances hebdomadaires d'un agent (7 derniers jours)
     */
    public function getAgentWeeklyPerformance($agentId)
    {
        $sevenDaysAgo = now()->subDays(7);
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $processed = CitizenRequest::where('processed_by', $agentId)
                                    ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                    ->whereDate('updated_at', $date)
                                    ->count();
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d/m'),
                'processed' => $processed
            ];
        }

        return $data;
    }

    /**
     * Obtenir les documents les plus demandés
     */
    private function getMostRequestedDocuments($limit = 5)
    {
        return Document::withCount('citizenRequests')
            ->orderBy('citizen_requests_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Récupère les documents les plus demandés par type
     */
    private function getDocumentsByType()
    {
        return Document::withCount('citizenRequests')
            ->orderBy('citizen_requests_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($document) {
                return [
                    'name' => $document->title,
                    'count' => $document->citizen_requests_count
                ];
            });
    }

    /**
     * Récupère les top performers parmi les agents
     */
    private function getTopPerformers($limit = 5)
    {
        // Récupérer tous les agents
        $agents = User::where('role', 'agent')->get();
        
        $performers = [];
        
        foreach ($agents as $agent) {
            $totalProcessed = CitizenRequest::where('processed_by', $agent->id)
                                          ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                          ->count();
            
            $approved = CitizenRequest::where('processed_by', $agent->id)
                                    ->where('status', CitizenRequest::STATUS_APPROVED)
                                    ->count();
            
            $successRate = $totalProcessed > 0 ? round(($approved / $totalProcessed) * 100, 1) : 0;
            
            $performers[] = [
                'name' => $agent->nom . ' ' . $agent->prenoms,
                'processed' => $totalProcessed,
                'success_rate' => $successRate
            ];
        }
        
        // Trier par nombre de demandes traitées
        usort($performers, function($a, $b) {
            return $b['processed'] - $a['processed'];
        });
        
        return array_slice($performers, 0, $limit);
    }

    /**
     * Obtenir le temps de traitement moyen
     */
    private function getAvgProcessingTime()
    {
        $avg = CitizenRequest::where('status', 'approved')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->first()
            ->avg_hours;

        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Récupère l'activité récente (dernières demandes)
     */
    private function getRecentActivity($limit = 10)
    {
        return CitizenRequest::with(['user', 'document', 'assignedAgent', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'citizen_name' => $request->user->name ?? 'N/A',
                    'document_title' => $request->document->title ?? 'N/A',
                    'status' => $request->status,
                    'created_at' => $request->created_at->format('d/m/Y H:i'),
                    'agent_name' => $request->assignedAgent->name ?? $request->processedBy->name ?? null,
                ];
            });
    }

    /**
     * Données pour le graphique des demandes
     */
    private function getRequestsChartData($period)
    {
        $startDate = now()->subDays($period);

        $data = CitizenRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Remplir les jours manquants avec 0
        $result = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = $data->firstWhere('date', $date);
            $result[] = [
                'date' => $date,
                'count' => $dayData ? $dayData->count : 0
            ];
        }

        return response()->json($result);
    }

    /**
     * Données pour le graphique de completion
     */
    private function getCompletionChartData($period)
    {
        $startDate = now()->subDays($period);

        $approved = CitizenRequest::selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('status', 'approved')
            ->where('updated_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $rejected = CitizenRequest::selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('status', 'rejected')
            ->where('updated_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $result = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $approvedData = $approved->firstWhere('date', $date);
            $rejectedData = $rejected->firstWhere('date', $date);

            $result[] = [
                'date' => $date,
                'approved' => $approvedData ? $approvedData->count : 0,
                'rejected' => $rejectedData ? $rejectedData->count : 0
            ];
        }

        return response()->json($result);
    }

    /**
     * Données pour le graphique des types de documents
     */
    private function getDocumentsChartData()
    {
        $data = Document::withCount('citizenRequests')
            ->having('citizen_requests_count', '>', 0)
            ->orderBy('citizen_requests_count', 'desc')
            ->get()
            ->map(function($document) {
                return [
                    'name' => $document->title,
                    'count' => $document->citizen_requests_count
                ];
            });

        return response()->json($data);
    }

    /**
     * Calculer le temps de traitement moyen pour un agent spécifique
     */
    private function getAgentAvgProcessingTime($agentId)
    {
        $requests = CitizenRequest::where('processed_by', $agentId)
                                ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                ->whereNotNull('updated_at')
                                ->get();

        if ($requests->isEmpty()) {
            return 'N/A';
        }

        $totalHours = 0;
        $count = 0;

        foreach ($requests as $request) {
            $createdAt = $request->created_at;
            $processedAt = $request->updated_at;
            
            $diffInHours = $createdAt->diffInHours($processedAt);
            $totalHours += $diffInHours;
            $count++;
        }

        if ($count == 0) {
            return 'N/A';
        }

        $avgHours = round($totalHours / $count, 1);
        
        if ($avgHours < 24) {
            return $avgHours . 'h';
        } else {
            $days = floor($avgHours / 24);
            $remainingHours = round($avgHours % 24, 1);
            return $days . 'j ' . $remainingHours . 'h';
        }
    }

    /**
     * Calculer la note de performance d'un agent
     */
    private function calculatePerformanceRating($agentId)
    {
        $totalProcessed = CitizenRequest::where('processed_by', $agentId)
                                      ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                      ->count();

        $approved = CitizenRequest::where('processed_by', $agentId)
                                ->where('status', CitizenRequest::STATUS_APPROVED)
                                ->count();

        if ($totalProcessed == 0) {
            return 0;
        }

        // Calcul basé sur le taux de succès et le volume de travail
        $successRate = ($approved / $totalProcessed) * 100;
        $volumeScore = min(($totalProcessed / 50) * 20, 20); // Max 20 points pour le volume
        
        return round($successRate * 0.8 + $volumeScore, 0);
    }

    /**
     * API pour récupérer les statistiques en temps réel
     */
    public function getRealTimeStats()
    {
        $agentId = Auth::id();
        $stats = $this->getAgentStats($agentId);
        
        // Ajouter des informations supplémentaires
        $stats['last_updated'] = now()->format('H:i:s');
        $stats['pending_global'] = CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count();
        $stats['in_progress_global'] = CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count();
        
        return response()->json($stats);
    }

    /**
     * Générer un rapport pour l'agent
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:global,agent,document,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        $agentId = Auth::id();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $data = $this->generateReportData($request->type, $agentId, $startDate, $endDate);
        
        switch ($request->format) {
            case 'pdf':
                return $this->generatePdfReport($data, $request->type);
            case 'excel':
                return $this->generateExcelReport($data, $request->type);
            case 'csv':
                return $this->generateCsvReport($data, $request->type);
            default:
                return response()->json(['error' => 'Format non supporté'], 400);
        }
    }

    /**
     * Générer les données du rapport
     */
    private function generateReportData($type, $agentId, $startDate, $endDate)
    {
        $data = [
            'agent' => Auth::user(),
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y')
            ],
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        switch ($type) {
            case 'agent':
                $data['stats'] = $this->getAgentStatsForPeriod($agentId, $startDate, $endDate);
                $data['requests'] = $this->getAgentRequestsForPeriod($agentId, $startDate, $endDate);
                break;
            case 'global':
                $data['global_stats'] = $this->getGlobalStatsForPeriod($startDate, $endDate);
                break;
            // Ajouter d'autres types selon les besoins
        }

        return $data;
    }

    /**
     * Obtenir les statistiques d'un agent pour une période
     */
    private function getAgentStatsForPeriod($agentId, $startDate, $endDate)
    {
        return [
            'processed' => CitizenRequest::where('processed_by', $agentId)
                                       ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                       ->whereBetween('updated_at', [$startDate, $endDate])
                                       ->count(),
            'approved' => CitizenRequest::where('processed_by', $agentId)
                                      ->where('status', CitizenRequest::STATUS_APPROVED)
                                      ->whereBetween('updated_at', [$startDate, $endDate])
                                      ->count(),
            'rejected' => CitizenRequest::where('processed_by', $agentId)
                                      ->where('status', CitizenRequest::STATUS_REJECTED)
                                      ->whereBetween('updated_at', [$startDate, $endDate])
                                      ->count(),
        ];
    }

    /**
     * Obtenir les demandes d'un agent pour une période
     */
    private function getAgentRequestsForPeriod($agentId, $startDate, $endDate)
    {
        return CitizenRequest::with(['user', 'document'])
                           ->where('processed_by', $agentId)
                           ->whereBetween('updated_at', [$startDate, $endDate])
                           ->orderBy('updated_at', 'desc')
                           ->get();
    }

    /**
     * Obtenir les statistiques globales pour une période
     */
    private function getGlobalStatsForPeriod($startDate, $endDate)
    {
        return [
            'total_requests' => CitizenRequest::whereBetween('created_at', [$startDate, $endDate])->count(),
            'processed' => CitizenRequest::whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                       ->whereBetween('updated_at', [$startDate, $endDate])
                                       ->count(),
        ];
    }

    /**
     * Générer un rapport PDF
     */
    private function generatePdfReport($data, $type)
    {
        // Simulation de génération PDF
        $filename = "rapport_{$type}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return response()->json([
            'message' => 'Rapport PDF généré avec succès',
            'filename' => $filename,
            'download_url' => "#" // À implémenter selon le système de fichiers
        ]);
    }

    /**
     * Générer un rapport Excel
     */
    private function generateExcelReport($data, $type)
    {
        // Simulation de génération Excel
        $filename = "rapport_{$type}_" . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return response()->json([
            'message' => 'Rapport Excel généré avec succès',
            'filename' => $filename,
            'download_url' => "#"
        ]);
    }

    /**
     * Générer un rapport CSV
     */
    private function generateCsvReport($data, $type)
    {
        // Simulation de génération CSV
        $filename = "rapport_{$type}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->json([
            'message' => 'Rapport CSV généré avec succès',
            'filename' => $filename,
            'download_url' => "#"
        ]);
    }

    /**
     * Obtenir les données pour les graphiques des agents (performances)
     */
    private function getAgentsChartData()
    {
        $agents = User::where('role', 'agent')
            ->get()
            ->map(function($agent) {
                $totalProcessed = CitizenRequest::where('processed_by', $agent->id)
                                              ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                              ->count();
                
                $approved = CitizenRequest::where('processed_by', $agent->id)
                                        ->where('status', CitizenRequest::STATUS_APPROVED)
                                        ->count();
                
                $successRate = $totalProcessed > 0 ? round(($approved / $totalProcessed) * 100, 1) : 0;

                return [
                    'name' => $agent->nom . ' ' . $agent->prenoms,
                    'total' => $totalProcessed,
                    'completed' => $approved,
                    'success_rate' => $successRate,
                ];
            });

        return response()->json($agents);
    }

    /**
     * Récupère les données pour les graphiques (API)
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 30);
        $type = $request->get('type', 'requests');

        switch ($type) {
            case 'requests':
                return $this->getRequestsChartData($period);
            case 'completion':
                return $this->getCompletionChartData($period);
            case 'documents':
                return $this->getDocumentsChartData();
            case 'agents':
                return $this->getAgentsChartData();
            default:
                return response()->json(['error' => 'Type de graphique non supporté'], 400);
        }
    }
}
