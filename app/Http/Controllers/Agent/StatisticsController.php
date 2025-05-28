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

        return view('agent.statistics.index', compact('globalStats', 'myStats'));
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
                    'name' => $this->getMostRequestedDocuments()->first()->name ?? 'N/A',
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

        // Note: Since citizen_requests table doesn't have agent_id column,
        // we'll use general statistics for now
        $totalAssigned = CitizenRequest::count();
        $completed = CitizenRequest::where('status', 'approved')->count();
        $successRate = $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100, 1) : 0;

        return [
            'total_assigned' => $totalAssigned,
            'processing' => CitizenRequest::where('status', 'processing')->count(),
            'approved' => $completed,
            'completed' => $completed,
            'rejected' => CitizenRequest::where('status', 'rejected')->count(),
            'this_month' => CitizenRequest::whereDate('created_at', '>=', $currentMonth)->count(),
            'avg_processing_time' => $this->getAvgProcessingTime(),
            'performance_rating' => 85, // Static value for now
            'success_rate' => $successRate,
        ];
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
     * Obtenir le temps de traitement moyen
     */
    private function getAvgProcessingTime()
    {
        $avg = CitizenRequest::where('status', 'approved')
            ->selectRaw('AVG((julianday(updated_at) - julianday(created_at)) * 24) as avg_hours')
            ->first()
            ->avg_hours;

        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Obtenir les données pour les graphiques
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'requests');
        $period = $request->get('period', '30');

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
                    'name' => $document->name,
                    'count' => $document->citizen_requests_count
                ];
            });

        return response()->json($data);
    }

    /**
     * Données pour le graphique des performances des agents
     */
    private function getAgentsChartData()
    {
        $agents = User::where('role', 'agent')
            ->get()
            ->map(function($agent) {
                // Simulate data since we don't have agent assignment yet
                $totalRequests = rand(10, 50);
                $completedRequests = rand(5, $totalRequests);
                $completionRate = $totalRequests > 0
                    ? round(($completedRequests / $totalRequests) * 100, 1)
                    : 0;

                return [
                    'name' => $agent->nom . ' ' . $agent->prenoms,
                    'total' => $totalRequests,
                    'completed' => $completedRequests,
                    'rate' => $completionRate
                ];
            });

        return response()->json($agents);
    }

    /**
     * Générer un rapport détaillé
     */
    public function generateReport(Request $request)
    {
        $type = $request->get('type', 'general');
        $format = $request->get('format', 'pdf');
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        switch ($type) {
            case 'general':
                return $this->generateGeneralReport($startDate, $endDate, $format);
            case 'agent':
                return $this->generateAgentReport(Auth::id(), $startDate, $endDate, $format);
            case 'documents':
                return $this->generateDocumentsReport($startDate, $endDate, $format);
            default:
                return redirect()->back()->with('error', 'Type de rapport non supporté');
        }
    }

    /**
     * Générer un rapport général
     */
    private function generateGeneralReport($startDate, $endDate, $format)
    {
        $data = [
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'stats' => $this->getGlobalStats(),
            'requests_by_day' => $this->getRequestsByPeriod($startDate, $endDate),
            'documents_stats' => $this->getMostRequestedDocuments(10),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // Pour PDF, il faudrait utiliser une bibliothèque comme DomPDF
        return response()->json($data); // Temporaire
    }

    /**
     * Générer un rapport d'agent
     */
    private function generateAgentReport($agentId, $startDate, $endDate, $format)
    {
        $data = [
            'agent' => User::find($agentId),
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'stats' => $this->getAgentStats($agentId),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        return response()->json($data); // Temporaire
    }

    /**
     * Générer un rapport de documents
     */
    private function generateDocumentsReport($startDate, $endDate, $format)
    {
        $data = [
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ],
            'documents_stats' => $this->getMostRequestedDocuments(20),
            'requests_by_period' => $this->getRequestsByPeriod($startDate, $endDate),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        return response()->json($data); // Temporaire
    }

    /**
     * Obtenir les demandes par période
     */
    private function getRequestsByPeriod($startDate, $endDate)
    {
        return CitizenRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Exporter les statistiques
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'general');

        $data = $this->getGlobalStats();

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($data, $type);
            case 'excel':
                return $this->exportToExcel($data, $type);
            case 'pdf':
                return $this->exportToPdf($data, $type);
            default:
                return $this->exportToCsv($data, $type);
        }
    }

    private function exportToCsv($data, $type)
    {
        $filename = 'statistics_' . $type . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, ['Métrique', 'Valeur']);

            // Statistiques des demandes
            fputcsv($file, ['Total des demandes', $data['requests']['total']]);
            fputcsv($file, ['Demandes en attente', $data['requests']['pending']]);
            fputcsv($file, ['Demandes approuvées', $data['requests']['approved']]);
            fputcsv($file, ['Demandes rejetées', $data['requests']['rejected']]);
            fputcsv($file, ['Demandes ce mois', $data['requests']['this_month']]);

            // Statistiques des utilisateurs
            fputcsv($file, ['Total citoyens', $data['users']['total_citizens']]);
            fputcsv($file, ['Total agents', $data['users']['total_agents']]);

            // Statistiques des documents
            fputcsv($file, ['Types de documents', $data['documents']['types_count']]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data, $type)
    {
        // Implementation avec PhpSpreadsheet si nécessaire
        return $this->exportToCsv($data, $type);
    }

    private function exportToPdf($data, $type)
    {
        // Implementation avec DomPDF si nécessaire
        return $this->exportToCsv($data, $type);
    }

    /**
     * Obtenir les documents par type
     */
    private function getDocumentsByType()
    {
        return Document::select('name')
            ->withCount('citizenRequests')
            ->get()
            ->map(function ($doc) {
                return [
                    'name' => $doc->name,
                    'count' => $doc->citizen_requests_count
                ];
            });
    }

    /**
     * Obtenir les agents les plus performants
     */
    private function getTopPerformers()
    {
        return User::where('role', 'agent')
            ->take(5)
            ->get()
            ->map(function ($agent) {
                $processed = rand(10, 50); // Static for now
                $successful = rand(round($processed * 0.6), $processed); // Between 60-100% success
                $successRate = $processed > 0 ? round(($successful / $processed) * 100, 1) : 0;

                return [
                    'name' => $agent->name,
                    'processed' => $processed,
                    'success_rate' => $successRate
                ];
            });
    }

    /**
     * Obtenir l'activité récente
     */
    private function getRecentActivity()
    {
        return CitizenRequest::with(['user', 'document'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($request) {
                $documentName = $request->document->name ?? 'N/A';
                $userName = $request->user->name ?? ($request->user->nom . ' ' . $request->user->prenoms ?? 'N/A');
                return [
                    'type' => 'request_update',
                    'message' => "Demande {$documentName} mise à jour",
                    'document_name' => $documentName,
                    'user_name' => $userName,
                    'status' => $request->status ?? 'pending',
                    'time' => $request->updated_at->diffForHumans(),
                ];
            });
    }
}
