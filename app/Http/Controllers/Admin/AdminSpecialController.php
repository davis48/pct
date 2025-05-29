<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CitizenRequest;
use App\Models\Document;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminSpecialController extends Controller
{
    /**
     * Constructeur avec middleware d'authentification administrateur
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Affiche l'interface spéciale pour l'administrateur
     */
    public function index()
    {
        // Données système
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu',
            'db_connection' => config('database.default'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Activé' : 'Désactivé',
            'last_updated' => Carbon::now()->format('d/m/Y H:i:s'),
        ];
        
        // Statistiques avancées
        $advancedStats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'total_requests' => CitizenRequest::count(),
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'requests_this_month' => CitizenRequest::whereYear('created_at', now()->year)
                                   ->whereMonth('created_at', now()->month)
                                   ->count(),
            'completion_rate' => $this->getCompletionRate(),
        ];
        
        // Données de performance du système
        $performanceData = [
            'pending_duration' => $this->getAveragePendingDuration(),
            'busiest_day' => $this->getBusiestDay(),
            'most_requested_document' => $this->getMostRequestedDocument(),
            'best_agent' => $this->getBestAgent(),
            'slowest_document' => $this->getSlowestDocument(),
            'daily_average' => $this->getDailyAverage(),
        ];
        
        // Journal d'activité des administrateurs
        $adminLogs = $this->getAdminActivityLogs();
        
        return view('admin.special.index', compact('systemInfo', 'advancedStats', 'performanceData', 'adminLogs'));
    }
    
    /**
     * Affiche le tableau de bord admin spécial
     */
    public function dashboard()
    {        $stats = [
            'total_requests' => $this->getTotalRequests(),
            'requests_today' => $this->getRequestsToday(),
            'completion_rate' => $this->getCompletionRate(),
            'completion_rate_change' => $this->getCompletionRateChange(),
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'processing_time_change' => $this->getProcessingTimeChange(),
            'active_agents' => $this->getActiveAgents(),
            'total_agents' => $this->getTotalAgents(),
        ];

        $documentStats = $this->getDocumentTypeStatistics();
        $systemInfo = $this->getSystemInfo();
        $recentActivities = $this->getRecentActivities();
        $performance = $this->getPerformanceMetrics();
        $chartData = $this->getChartData();

        return view('admin.special.dashboard', compact(
            'stats', 'documentStats', 'systemInfo', 'recentActivities', 'performance', 'chartData'
        ));
    }

    /**
     * Affiche les statistiques avancées
     */
    public function statistics()
    {
        $kpis = [
            'total_requests' => $this->getTotalRequests(),
            'requests_growth' => $this->getRequestsGrowth(),
            'completion_rate' => $this->getCompletionRate(),
            'completion_rate_change' => $this->getCompletionRateChange(),
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'processing_time_change' => $this->getProcessingTimeChange(),
            'satisfaction_rate' => $this->getSatisfactionRate(),
            'satisfaction_change' => $this->getSatisfactionChange(),
        ];

        $documentTypeStats = $this->getDetailedDocumentTypeStats();
        $agentStats = $this->getAgentStatistics();
        $agents = $this->getAgents();
        $hourlyActivity = $this->getHourlyActivity();
        $chartData = $this->getAdvancedChartData();

        return view('admin.special.statistics', compact(
            'kpis', 'documentTypeStats', 'agentStats', 'agents', 'hourlyActivity', 'chartData'
        ));
    }

    /**
     * Affiche les informations système
     */
    public function systemInfo()
    {
        $systemInfo = [
            'cpu_usage' => $this->getCpuUsage(),
            'cpu_cores' => $this->getCpuCores(),
            'memory_usage' => $this->getMemoryUsage(),
            'memory_total' => $this->getTotalMemory(),
            'disk_usage' => $this->getDiskUsage(),
            'disk_free' => $this->getFreeDiskSpace(),
            'uptime' => $this->getSystemUptime(),
        ];

        $serverInfo = [
            'os' => php_uname('s') . ' ' . php_uname('r'),
            'web_server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'database' => $this->getDatabaseVersion(),
            'timezone' => config('app.timezone'),
        ];

        $services = [
            ['name' => 'Web Server', 'status' => 'running', 'response_time' => $this->getWebServerResponseTime()],
            ['name' => 'Database', 'status' => $this->getDatabaseStatus(), 'response_time' => $this->getDatabaseResponseTime()],
            ['name' => 'Application', 'status' => 'running', 'response_time' => null],
            ['name' => 'File System', 'status' => is_writable(storage_path()) ? 'running' : 'warning', 'response_time' => null],
        ];

        $databaseInfo = [
            'total_tables' => $this->getDatabaseTableCount(),
            'total_records' => number_format($this->getTotalDatabaseRecords()),
            'database_size' => $this->getDatabaseSize(),
            'active_connections' => $this->getActiveConnections(),
        ];

        $databaseTables = [
            ['name' => 'citizen_requests', 'rows' => CitizenRequest::count(), 'size' => $this->getTableSize('citizen_requests'), 'updated_at' => $this->getTableLastUpdate('citizen_requests')],
            ['name' => 'users', 'rows' => User::count(), 'size' => $this->getTableSize('users'), 'updated_at' => $this->getTableLastUpdate('users')],
            ['name' => 'documents', 'rows' => Document::count(), 'size' => $this->getTableSize('documents'), 'updated_at' => $this->getTableLastUpdate('documents')],
            ['name' => 'attachments', 'rows' => Attachment::count(), 'size' => $this->getTableSize('attachments'), 'updated_at' => $this->getTableLastUpdate('attachments')],
        ];

        $phpConfig = [
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        $phpExtensions = ['PDO', 'OpenSSL', 'Mbstring', 'Tokenizer', 'XML', 'JSON', 'Curl', 'GD', 'Zip'];

        $diskUsage = [
            ['mount' => '/', 'used' => '50 GB', 'total' => '100 GB', 'percentage' => 50],
            ['mount' => '/var', 'used' => '20 GB', 'total' => '50 GB', 'percentage' => 40],
        ];

        $chartData = [
            'cpu' => [
                'labels' => collect(range(0, 23))->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'),
                'data' => collect(range(0, 23))->map(fn() => rand(10, 80))
            ],
            'memory' => [
                'labels' => collect(range(0, 23))->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'),
                'data' => collect(range(0, 23))->map(fn() => rand(30, 70))
            ]
        ];

        return view('admin.special.system-info', compact(
            'systemInfo', 'serverInfo', 'services', 'databaseInfo', 'databaseTables',
            'phpConfig', 'phpExtensions', 'diskUsage', 'chartData'
        ));
    }
    
    /**
     * Affiche la page de maintenance
     */
    public function maintenance()
    {
        $maintenanceInfo = [
            'cache_last_cleared' => now()->subHours(rand(1, 48))->format('Y-m-d H:i'),
            'db_last_optimized' => now()->subDays(rand(1, 7))->format('Y-m-d H:i'),
            'last_backup' => now()->subHours(rand(1, 24))->format('Y-m-d H:i'),
        ];

        $backups = [
            [
                'name' => 'backup_' . now()->format('Y_m_d_H_i') . '.sql',
                'date' => now()->subHours(2)->format('Y-m-d H:i'),
                'size' => rand(10, 100) . ' MB',
                'type' => 'manual'
            ],
            [
                'name' => 'backup_' . now()->subDay()->format('Y_m_d_H_i') . '.sql',
                'date' => now()->subDay()->format('Y-m-d H:i'),
                'size' => rand(10, 100) . ' MB',
                'type' => 'automatic'
            ],
            [
                'name' => 'backup_' . now()->subDays(2)->format('Y_m_d_H_i') . '.sql',
                'date' => now()->subDays(2)->format('Y-m-d H:i'),
                'size' => rand(10, 100) . ' MB',
                'type' => 'automatic'
            ],
        ];

        $cacheInfo = [
            'app_cache_size' => rand(10, 50) . ' MB',
            'config_cache_size' => rand(1, 5) . ' MB',
            'route_cache_size' => rand(1, 5) . ' MB',
            'view_cache_size' => rand(5, 20) . ' MB',
        ];

        $logFiles = [
            ['name' => 'laravel.log', 'size' => rand(1, 20) . ' MB', 'entries' => rand(100, 1000)],
            ['name' => 'error.log', 'size' => rand(1, 10) . ' MB', 'entries' => rand(50, 500)],
            ['name' => 'access.log', 'size' => rand(50, 200) . ' MB', 'entries' => rand(1000, 10000)],
        ];

        $dbStats = [
            'total_size' => rand(100, 500) . ' MB',
            'fragmentation' => rand(5, 25) . '%',
            'last_optimized' => now()->subDays(rand(1, 7))->format('Y-m-d H:i'),
        ];

        $scheduledTasks = [
            [
                'name' => 'Sauvegarde automatique',
                'frequency' => 'Quotidienne (02:00)',
                'last_run' => now()->subHours(rand(1, 24))->format('Y-m-d H:i'),
                'next_run' => now()->addHours(rand(1, 24))->format('Y-m-d H:i'),
                'status' => 'active'
            ],
            [
                'name' => 'Nettoyage des logs',
                'frequency' => 'Hebdomadaire (Dimanche)',
                'last_run' => now()->subDays(rand(1, 7))->format('Y-m-d H:i'),
                'next_run' => now()->addDays(rand(1, 7))->format('Y-m-d H:i'),
                'status' => 'active'
            ],
            [
                'name' => 'Optimisation DB',
                'frequency' => 'Mensuelle (1er du mois)',
                'last_run' => now()->subDays(rand(7, 30))->format('Y-m-d H:i'),
                'next_run' => now()->addDays(rand(1, 30))->format('Y-m-d H:i'),
                'status' => 'active'
            ],
        ];

        return view('admin.special.maintenance', compact(
            'maintenanceInfo', 'backups', 'cacheInfo', 'logFiles', 'dbStats', 'scheduledTasks'
        ));
    }
    
    /**
     * Affiche la page de performance
     */
    public function performance()
    {
        return view('admin.special.performance');
    }
    
    /**
     * Affiche les journaux
     */
    public function logs()
    {
        // Simulation de données de logs - à remplacer par la vraie logique
        $logs = [
            'error_count' => 23,
            'warning_count' => 87,
            'info_count' => 1245,
            'total_count' => 2156,
            'recent_logs' => $this->getRecentLogs(),
            'hourly_activity' => $this->getHourlyLogActivity(),
            'frequent_errors' => $this->getFrequentErrors()
        ];

        return view('admin.special.logs', compact('logs'));
    }

    private function getRecentLogs()
    {
        return [
            [
                'id' => 1,
                'level' => 'error',
                'category' => 'auth',
                'message' => 'Tentative de connexion échouée pour l\'utilisateur admin@example.com',
                'context' => ['ip' => '192.168.1.100', 'user_agent' => 'Mozilla/5.0...'],
                'created_at' => now()->subMinutes(5),
            ],
            [
                'id' => 2,
                'level' => 'info',
                'category' => 'database',
                'message' => 'Connexion à la base de données établie',
                'context' => ['connection' => 'mysql', 'database' => 'pct_uvci'],
                'created_at' => now()->subMinutes(8),
            ],
            [
                'id' => 3,
                'level' => 'warning',
                'category' => 'file',
                'message' => 'Espace disque faible : 85% utilisé',
                'context' => ['disk' => '/var/www', 'usage' => '85%'],
                'created_at' => now()->subMinutes(12),
            ],
            // Plus de logs...
        ];
    }

    private function getHourlyLogActivity()
    {
        $hours = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('H:i');
            $hours[] = [
                'hour' => $hour,
                'errors' => rand(0, 10),
                'warnings' => rand(5, 20),
                'info' => rand(20, 50),
            ];
        }
        return $hours;
    }

    private function getFrequentErrors()
    {
        return [
            [
                'message' => 'Connexion base de données échouée',
                'count' => 12,
                'last_occurrence' => now()->subHours(2),
                'category' => 'database'
            ],
            [
                'message' => 'Échec d\'authentification',
                'count' => 8,
                'last_occurrence' => now()->subMinutes(30),
                'category' => 'auth'
            ],
            [
                'message' => 'Fichier non trouvé',
                'count' => 5,
                'last_occurrence' => now()->subHour(),
                'category' => 'file'
            ],
            [
                'message' => 'Limite de mémoire dépassée',
                'count' => 3,
                'last_occurrence' => now()->subHours(3),
                'category' => 'system'
            ]
        ];
    }
    
    /**
     * Effectue une sauvegarde de la base de données
     */
    public function backup()
    {
        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Créer le répertoire s'il n'existe pas
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }
        
        // Exécuter la commande de sauvegarde (à adapter selon votre configuration)
        if (config('database.default') == 'mysql') {
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );
            
            exec($command);
            
            if (file_exists($path)) {
                return response()->download($path)->deleteFileAfterSend(true);
            }
        }
        
        return redirect()->back()->with('error', 'La sauvegarde a échoué. Vérifiez les permissions ou contactez l\'administrateur système.');
    }
      /**
     * Obtient le temps moyen de traitement des demandes
     */
    private function getAverageProcessingTime()
    {
        $requests = CitizenRequest::whereIn('status', ['approved', 'rejected'])
                               ->whereNotNull('processed_at')
                               ->get();
        
        if ($requests->isEmpty()) {
            return 0;
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
        
        return round($totalHours / $count, 1); // Retourne juste le nombre d'heures, pas le texte formaté
    }
      /**
     * Obtient le taux de complétion des demandes
     */
    private function getCompletionRate()
    {
        $totalRequests = CitizenRequest::count();
        
        if ($totalRequests == 0) {
            return 0;
        }
        
        $completedRequests = CitizenRequest::whereIn('status', ['approved', 'rejected'])->count();
        $rate = round(($completedRequests / $totalRequests) * 100, 2);
        
        return $rate; // Retourne juste le nombre, pas avec %
    }
    
    /**
     * Obtient la durée moyenne des demandes en attente
     */
    private function getAveragePendingDuration()
    {
        $pendingRequests = CitizenRequest::where('status', 'pending')->get();
        
        if ($pendingRequests->isEmpty()) {
            return '0 jours';
        }
        
        $totalDays = 0;
        
        foreach ($pendingRequests as $request) {
            $totalDays += $request->created_at->diffInDays(now());
        }
        
        $avgDays = round($totalDays / $pendingRequests->count(), 1);
        
        return $avgDays . ' jours';
    }
    
    /**
     * Obtient le jour le plus chargé
     */
    private function getBusiestDay()
    {
        $result = CitizenRequest::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                              ->groupBy('day')
                              ->orderBy('count', 'desc')
                              ->first();
        
        if (!$result) {
            return 'N/A';
        }
        
        // Traduire le jour en français
        $days = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        
        return $days[$result->day] ?? $result->day;
    }
    
    /**
     * Obtient le document le plus demandé
     */
    private function getMostRequestedDocument()
    {
        $result = CitizenRequest::selectRaw('document_id, COUNT(*) as count')
                              ->with('document:id,title')
                              ->groupBy('document_id')
                              ->orderBy('count', 'desc')
                              ->first();
        
        if (!$result || !$result->document) {
            return 'N/A';
        }
        
        return $result->document->title . ' (' . $result->count . ' demandes)';
    }
    
    /**
     * Obtient l'agent avec le meilleur taux de complétion
     */
    private function getBestAgent()
    {
        $agents = User::where('role', 'agent')->get();
        
        if ($agents->isEmpty()) {
            return 'N/A';
        }
        
        $bestAgent = null;
        $bestRate = 0;
        
        foreach ($agents as $agent) {
            $processed = CitizenRequest::where('processed_by', $agent->id)->count();
            
            if ($processed > 0) {
                $assigned = CitizenRequest::where('assigned_to', $agent->id)->count();
                $rate = $assigned > 0 ? round(($processed / $assigned) * 100, 2) : 0;
                
                if ($rate > $bestRate) {
                    $bestRate = $rate;
                    $bestAgent = $agent;
                }
            }
        }
        
        if (!$bestAgent) {
            return 'N/A';
        }
        
        return $bestAgent->nom . ' ' . $bestAgent->prenoms . ' (' . $bestRate . '%)';
    }
    
    /**
     * Obtient le document avec le traitement le plus lent
     */
    private function getSlowestDocument()
    {
        $documents = Document::all();
        
        if ($documents->isEmpty()) {
            return 'N/A';
        }
        
        $slowestDocument = null;
        $slowestTime = 0;
        
        foreach ($documents as $document) {
            $requests = CitizenRequest::where('document_id', $document->id)
                                    ->whereIn('status', ['approved', 'rejected'])
                                    ->whereNotNull('processed_at')
                                    ->get();
            
            if ($requests->isNotEmpty()) {
                $totalHours = 0;
                
                foreach ($requests as $request) {
                    $totalHours += $request->created_at->diffInHours($request->processed_at);
                }
                
                $avgHours = $totalHours / $requests->count();
                
                if ($avgHours > $slowestTime) {
                    $slowestTime = $avgHours;
                    $slowestDocument = $document;
                }
            }
        }
        
        if (!$slowestDocument) {
            return 'N/A';
        }
        
        if ($slowestTime < 24) {
            $time = round($slowestTime, 1) . ' heures';
        } else {
            $days = floor($slowestTime / 24);
            $remainingHours = round($slowestTime % 24, 1);
            $time = $days . ' jour(s) ' . $remainingHours . ' heure(s)';
        }
        
        return $slowestDocument->title . ' (' . $time . ')';
    }
    
    /**
     * Obtient la moyenne quotidienne des demandes
     */
    private function getDailyAverage()
    {
        $firstRequest = CitizenRequest::orderBy('created_at', 'asc')->first();
        
        if (!$firstRequest) {
            return '0 demandes/jour';
        }
        
        $daysSinceFirst = $firstRequest->created_at->diffInDays(now()) + 1; // +1 pour inclure le jour de la première demande
        $totalRequests = CitizenRequest::count();
        
        $average = round($totalRequests / $daysSinceFirst, 2);
        
        return $average . ' demandes/jour';
    }
    
    /**
     * Obtient les journaux d'activité des administrateurs
     */
    private function getAdminActivityLogs()
    {
        // Simuler les journaux d'activité pour cette démonstration
        // Dans un vrai système, ces données proviendraient d'une table de journaux
        return [
            [
                'admin' => 'Admin Système',
                'action' => 'Connexion au système',
                'date' => Carbon::now()->subHours(2)->format('d/m/Y H:i:s'),
                'ip' => '192.168.1.1',
            ],
            [
                'admin' => 'Admin Système',
                'action' => 'Modification des paramètres système',
                'date' => Carbon::now()->subHours(1)->format('d/m/Y H:i:s'),
                'ip' => '192.168.1.1',
            ],
            [
                'admin' => Auth::user()->nom . ' ' . Auth::user()->prenoms,
                'action' => 'Accès à l\'interface spéciale',
                'date' => Carbon::now()->format('d/m/Y H:i:s'),
                'ip' => request()->ip(),
            ],
        ];
    }
    
    /**
     * Formate les octets en unités lisibles
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Obtient le nombre total de demandes
     */
    private function getTotalRequests()
    {
        return CitizenRequest::count();
    }

    /**
     * Obtient le nombre de demandes d'aujourd'hui
     */
    private function getRequestsToday()
    {
        return CitizenRequest::whereDate('created_at', now())->count();
    }

    /**
     * Obtient le nombre d'agents actifs
     */
    private function getActiveAgents()
    {
        return User::where('role', 'agent')->where('is_active', 1)->count();
    }

    /**
     * Obtient le nombre total d'agents
     */
    private function getTotalAgents()
    {
        return User::where('role', 'agent')->count();
    }

    /**
     * Obtient les statistiques par type de document
     */
    private function getDocumentTypeStatistics()
    {
        return [
            'Acte de Naissance' => [
                'name' => 'Acte de Naissance',
                'total' => rand(500, 1500),
                'avg_time' => rand(15, 45) . ' min',
                'success_rate' => rand(85, 98),
                'color' => '#6366f1'
            ],
            'Acte de Mariage' => [
                'name' => 'Acte de Mariage',
                'total' => rand(200, 800),
                'avg_time' => rand(20, 50) . ' min',
                'success_rate' => rand(80, 95),
                'color' => '#22c55e'
            ],
            'Acte de Décès' => [
                'name' => 'Acte de Décès',
                'total' => rand(100, 600),
                'avg_time' => rand(25, 55) . ' min',
                'success_rate' => rand(75, 90),
                'color' => '#f59e0b'
            ],
            'Certificat de Nationalité' => [
                'name' => 'Certificat de Nationalité',
                'total' => rand(300, 900),
                'avg_time' => rand(30, 60) . ' min',
                'success_rate' => rand(70, 85),
                'color' => '#ef4444'
            ],
            'Déclaration de Naissance' => [
                'name' => 'Déclaration de Naissance',
                'total' => rand(150, 500),
                'avg_time' => rand(10, 30) . ' min',
                'success_rate' => rand(90, 99),
                'color' => '#a855f7'
            ],
        ];
    }

    /**
     * Obtient des statistiques détaillées par type de document pour la page des statistiques
     */
    private function getDetailedDocumentTypeStats()
    {
        $basicStats = $this->getDocumentTypeStatistics();
        $detailedStats = [];
        
        foreach ($basicStats as $key => $stats) {
            $detailedStats[$key] = $stats;
            $detailedStats[$key]['monthly_trend'] = $this->generateRandomTrend(6);
            $detailedStats[$key]['processing_time_trend'] = $this->generateRandomTrend(6, 10, 60);
            $detailedStats[$key]['rejection_rate'] = rand(1, 15);            $detailedStats[$key]['pending'] = rand(10, 200);
            $detailedStats[$key]['approved'] = rand(100, 1000);
            $detailedStats[$key]['rejected'] = rand(5, 50);
            $detailedStats[$key]['avg_satisfaction'] = rand(70, 95);
            $detailedStats[$key]['most_common_issue'] = $this->getRandomIssue();
        }
        
        return $detailedStats;
    }
    
    /**
     * Génère une tendance aléatoire pour les graphiques
     */
    private function generateRandomTrend($length, $min = 5, $max = 100)
    {
        $trend = [];
        for ($i = 0; $i < $length; $i++) {
            $trend[] = rand($min, $max);
        }
        return $trend;
    }
    
    /**
     * Obtient un problème aléatoire pour les documents
     */
    private function getRandomIssue()
    {
        $issues = [
            'Documents incomplets',
            'Informations incorrectes',
            'Photo non conforme',
            'Signature manquante',
            'Pièce jointe illisible',
            'Identité non vérifiable',
            'Formulaire mal rempli',
            'Délai expiré'
        ];
        
        return $issues[array_rand($issues)];
    }
    
    /**
     * Obtient les statistiques des agents
     */
    private function getAgentStatistics()
    {
        return [
            [
                'name' => 'Agent Martin',
                'email' => 'martin@example.com',
                'requests_handled' => rand(100, 500),
                'avg_time' => rand(20, 40) . ' min',
                'success_rate' => rand(85, 98),
                'last_activity' => now()->subHours(rand(1, 8))->format('Y-m-d H:i'),
                'performance_rating' => rand(3, 5)
            ],
            [
                'name' => 'Agent Dubois',
                'email' => 'dubois@example.com',
                'requests_handled' => rand(80, 400),
                'avg_time' => rand(15, 35) . ' min',
                'success_rate' => rand(80, 95),
                'last_activity' => now()->subHours(rand(1, 12))->format('Y-m-d H:i'),
                'performance_rating' => rand(3, 5)
            ],
            [
                'name' => 'Agent Leroy',
                'email' => 'leroy@example.com',
                'requests_handled' => rand(120, 600),
                'avg_time' => rand(18, 42) . ' min',
                'success_rate' => rand(75, 92),
                'last_activity' => now()->subHours(rand(2, 24))->format('Y-m-d H:i'),
                'performance_rating' => rand(2, 4)
            ],
        ];
    }

    /**
     * Obtient l'activité horaire
     */
    private function getHourlyActivity()
    {
        $activity = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $activity[$hour] = rand(10, 100);
        }
        return $activity;
    }

    /**
     * Obtient les données de graphiques avancées
     */
    private function getAdvancedChartData()
    {
        return [
            'document_stats' => [
                'labels' => ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance'],
                'data' => [45, 30, 15, 10]
            ],
            'document_types' => [
                'labels' => ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance', 'Certificat de Résidence'],
                'data' => [45, 25, 15, 10, 5]
            ],
            'processing_time' => [
                'labels' => collect(range(1, 30))->map(fn($d) => 'Jour ' . $d),
                'data' => collect(range(1, 30))->map(fn() => rand(15, 60))
            ],
            'agent_performance' => [
                'labels' => ['Martin', 'Dubois', 'Leroy', 'Bernard', 'Moreau'],
                'data' => [rand(50, 200), rand(40, 180), rand(60, 220), rand(30, 150), rand(45, 190)]
            ],
            // Nouvelles données pour graphiques de performance améliorés
            'performance_metrics' => [
                'efficiency' => [
                    'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                    'current_week' => [85, 92, 78, 95, 88, 75, 82],
                    'previous_week' => [78, 85, 82, 89, 91, 70, 85],
                    'target' => [90, 90, 90, 90, 90, 90, 90],
                    'hourly_today' => [
                        'labels' => ['08h', '09h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h'],
                        'efficiency' => [78, 82, 88, 91, 87, 83, 79, 85, 92, 89, 86],
                        'volume' => [12, 18, 25, 32, 28, 15, 35, 42, 38, 29, 18]
                    ]
                ],
                'response_time' => [
                    'labels' => ['08h', '10h', '12h', '14h', '16h', '18h', '20h'],
                    'average_time' => [2.5, 3.2, 4.1, 5.8, 4.3, 3.7, 2.1],
                    'peak_time' => [3.1, 4.5, 6.2, 8.3, 6.1, 5.2, 2.8],
                    'target_time' => [3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0],
                    'sla_compliance' => [95, 88, 72, 45, 68, 82, 96]
                ],
                'satisfaction' => [
                    'labels' => ['Acte Naissance', 'Acte Mariage', 'Cert. Nationalité', 'Décl. Naissance', 'Cert. Résidence'],
                    'satisfaction_rate' => [95, 87, 92, 89, 85],
                    'completion_rate' => [98, 94, 96, 91, 88],
                    'error_rate' => [2, 6, 4, 9, 12],
                    'complexity_score' => [2, 4, 3, 3, 5] // Sur 5
                ],
                'workload_distribution' => [
                    'agents' => ['Martin', 'Dubois', 'Leroy', 'Bernard', 'Moreau'],
                    'current_load' => [85, 92, 78, 95, 73],
                    'capacity' => [100, 100, 100, 100, 100],
                    'efficiency_score' => [94, 88, 92, 87, 89],
                    'specializations' => [
                        ['Actes civils', 'Nationalité'],
                        ['Mariages', 'Résidences'],
                        ['Naissances', 'Actes civils'],
                        ['Résidences', 'Nationalité'],
                        ['Polyvalent', 'Formation']
                    ]
                ],
                'predictive_analytics' => [
                    'next_hour_forecast' => [
                        'expected_requests' => 45,
                        'confidence' => 85,
                        'recommended_agents' => 3,
                        'bottleneck_risk' => 'moyen'
                    ],
                    'weekly_trend' => [
                        'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'],
                        'forecast' => [180, 220, 195, 240, 210],
                        'actual' => [175, 215, 198, 235, null] // null pour les jours futurs
                    ]
                ]
            ],
            'performance_trends' => [
                'monthly' => [
                    'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    'requests_handled' => [1250, 1380, 1156, 1420, 1650, 1789],
                    'success_rate' => [92, 94, 89, 96, 93, 97],
                    'avg_processing_time' => [4.2, 3.8, 4.5, 3.9, 3.6, 3.4]
                ],
                'quality_metrics' => [
                    'labels' => ['Précision', 'Rapidité', 'Satisfaction', 'Conformité', 'Innovation'],
                    'current_scores' => [92, 87, 94, 96, 78],
                    'benchmarks' => [90, 85, 90, 95, 80],
                    'targets' => [95, 90, 95, 98, 85]
                ],
                'comparative_analysis' => [
                    'labels' => ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024', 'Q1 2025'],
                    'performance_index' => [78, 82, 85, 91, 94],
                    'cost_efficiency' => [82, 79, 88, 92, 89],
                    'customer_satisfaction' => [87, 89, 91, 93, 94],
                    'innovation_score' => [65, 71, 78, 82, 85]
                ],
                'anomaly_detection' => [
                    'anomalies_this_week' => [
                        ['type' => 'pic_demandes', 'day' => 'Mercredi', 'severity' => 'moyenne', 'value' => '+45%'],
                        ['type' => 'temps_reponse', 'day' => 'Jeudi', 'severity' => 'haute', 'value' => '8.2min'],
                        ['type' => 'agent_absent', 'day' => 'Vendredi', 'severity' => 'faible', 'value' => '1 agent']
                    ],
                    'trend_analysis' => [
                        'positive_trends' => ['Satisfaction client en hausse', 'Temps de traitement optimisé'],
                        'negative_trends' => ['Surcharge aux heures de pointe'],
                        'recommendations' => ['Renforcer équipe 14h-16h', 'Formation efficacité']
                    ]
                ]
            ],
            // Statistiques détaillées par type de document disponible pour les citoyens
            'document_types_detailed' => $this->getDocumentTypesStatistics()
        ];
    }

    /**
     * Obtient la liste des agents
     */
    private function getAgents()
    {
        return [
            ['id' => 1, 'name' => 'Agent Martin'],
            ['id' => 2, 'name' => 'Agent Dubois'],
            ['id' => 3, 'name' => 'Agent Leroy'],
            ['id' => 4, 'name' => 'Agent Bernard'],
            ['id' => 5, 'name' => 'Agent Moreau'],
        ];
    }

    private function getSystemInfo()
    {
        return [
            'server_load' => '15%',
            'memory_usage' => '68%',
            'disk_usage' => '45%',
            'uptime' => '15 jours 3h',
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'database_size' => '2.3 GB',
            'cache_status' => 'Actif'
        ];
    }

    private function getRecentActivities()
    {
        return [
            [
                'id' => 1,
                'type' => 'request_created',
                'message' => 'Nouvelle demande d\'extrait de naissance',
                'user' => 'Jean Dupont',
                'time' => now()->subMinutes(5),
                'icon' => 'fas fa-file-plus',
                'color' => 'text-green-600'
            ],
            [
                'id' => 2,
                'type' => 'request_processed',
                'message' => 'Demande de certificat de mariage traitée',
                'user' => 'Agent Marie',
                'time' => now()->subMinutes(12),
                'icon' => 'fas fa-check-circle',
                'color' => 'text-blue-600'
            ],
            [
                'id' => 3,
                'type' => 'user_login',
                'message' => 'Connexion administrateur',
                'user' => 'Admin',
                'time' => now()->subMinutes(18),
                'icon' => 'fas fa-sign-in-alt',
                'color' => 'text-purple-600'
            ],
            [
                'id' => 4,
                'type' => 'system_backup',
                'message' => 'Sauvegarde automatique effectuée',
                'user' => 'Système',
                'time' => now()->subMinutes(25),
                'icon' => 'fas fa-database',
                'color' => 'text-gray-600'
            ]
        ];
    }

    private function getPerformanceMetrics()
    {
        return [
            // Données compatibles avec le template existant
            'best_agent' => 'Agent Smith',
            'fastest_document' => 'Acte de Naissance',
            'peak_hour' => '14h00',
            
            // Nouvelles métriques de performance détaillées
            'response_time' => [
                'current' => 245,
                'average' => 180,
                'target' => 200,
                'trend' => 'improving'
            ],
            'throughput' => [
                'requests_per_minute' => 45,
                'peak_today' => 78,
                'average_daily' => 52,
                'trend' => 'stable'
            ],
            'error_rate' => [
                'current' => 0.8,
                'target' => 1.0,
                'last_24h' => 1.2,
                'trend' => 'improving'
            ],
            'availability' => [
                'current' => 99.9,
                'monthly' => 99.7,
                'target' => 99.5,
                'trend' => 'excellent'
            ],
            'agent_performance' => [
                'labels' => ['Agent A', 'Agent B', 'Agent C', 'Agent D', 'Agent E'],
                'completed_requests' => [85, 72, 91, 68, 79],
                'avg_processing_time' => [120, 145, 98, 156, 134],
                'satisfaction_rate' => [95, 88, 97, 85, 92]
            ],
            'hourly_performance' => [
                'labels' => ['08h', '10h', '12h', '14h', '16h', '18h'],
                'response_times' => [180, 165, 210, 195, 175, 185],
                'request_volume' => [15, 25, 45, 78, 35, 20]
            ]
        ];
    }

    private function getChartData()
    {
        $labels = [];
        $requestsData = [];
        $processed = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $requestsData[] = rand(20, 80);
            $processed[] = rand(15, 70);
        }
        
        // Données pour le graphique de répartition par type de document
        $documentTypesData = [
            'labels' => ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance', 'Certificat de Résidence'],
            'data' => [45, 25, 15, 10, 5]
        ];
        
        return [
            'requests' => [
                'labels' => $labels,
                'data' => $requestsData
            ],
            'processed' => [
                'labels' => $labels,
                'data' => $processed
            ],
            'document_types' => $documentTypesData
        ];
    }

    /**
     * Obtient les statistiques détaillées par type de document
     */
    private function getDocumentTypesStatistics()
    {
        // Types de documents disponibles dans le formulaire citoyen
        $documentTypes = [
            'attestation' => 'Attestation de domicile',
            'legalisation' => 'Légalisation de document', 
            'mariage' => 'Certificat de mariage',
            'extrait-acte' => 'Extrait d\'acte de naissance',
            'declaration-naissance' => 'Déclaration de naissance',
            'certificat' => 'Certificat de célibat',
            'information' => 'Demande d\'information',
            'autre' => 'Autre'
        ];

        $statistics = [];

        foreach ($documentTypes as $type => $label) {
            // Récupérer les demandes pour ce type de document
            $requests = CitizenRequest::where('type', $type);
            $totalRequests = $requests->count();
            
            if ($totalRequests > 0) {
                // Statistiques de base
                $pending = $requests->clone()->where('status', 'pending')->count();
                $processing = $requests->clone()->where('status', 'in_progress')->count();
                $approved = $requests->clone()->where('status', 'approved')->count();
                $rejected = $requests->clone()->where('status', 'rejected')->count();
                
                // Taux de succès
                $successRate = $totalRequests > 0 ? round(($approved / $totalRequests) * 100, 1) : 0;
                
                // Temps moyen de traitement réel (basé sur les dates)
                $processedRequests = CitizenRequest::where('type', $type)
                    ->whereNotNull('processed_at')
                    ->get();
                
                $avgProcessingTime = 0;
                if ($processedRequests->count() > 0) {
                    $totalDays = 0;
                    foreach ($processedRequests as $request) {
                        $days = $request->created_at->diffInDays($request->processed_at);
                        $totalDays += $days;
                    }
                    $avgProcessingTime = round($totalDays / $processedRequests->count(), 1);
                } else {
                    // Valeur par défaut basée sur le type si aucune donnée historique
                    $avgProcessingTime = match($type) {
                        'attestation' => 3,
                        'legalisation' => 6,
                        'mariage' => 10,
                        'extrait-acte' => 4,
                        'declaration-naissance' => 2,
                        'certificat' => 5,
                        'information' => 1,
                        default => 4
                    };
                }
                
                // Score de satisfaction basé sur le taux de succès et temps de traitement
                $satisfactionScore = 0;
                if ($totalRequests > 0) {
                    $baseScore = min(90, 60 + ($successRate * 0.3)); // Base sur taux de succès
                    $timeBonus = max(0, 10 - ($avgProcessingTime * 0.5)); // Bonus si traitement rapide
                    $satisfactionScore = round($baseScore + $timeBonus);
                } else {
                    $satisfactionScore = 80; // Score par défaut
                }
                
                // Niveau de complexité basé sur temps moyen et taux de succès
                $complexityLevel = 'Moyen';
                if ($avgProcessingTime <= 2 && $successRate >= 90) {
                    $complexityLevel = 'Très faible';
                } elseif ($avgProcessingTime <= 4 && $successRate >= 80) {
                    $complexityLevel = 'Faible';
                } elseif ($avgProcessingTime >= 8 || $successRate <= 60) {
                    $complexityLevel = 'Élevé';
                }
                
                // Évolution mensuelle (6 derniers mois) - données réelles
                $monthlyEvolution = [];
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $monthlyCount = CitizenRequest::where('type', $type)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
                    $monthlyEvolution[] = $monthlyCount;
                }
                
                // Répartition par statut pour graphique en secteurs
                $statusDistribution = [
                    'pending' => $pending,
                    'processing' => $processing,
                    'approved' => $approved,
                    'rejected' => $rejected
                ];
                
                // Pièces jointes moyennes réelles
                $attachmentsData = Attachment::join('citizen_requests', 'attachments.citizen_request_id', '=', 'citizen_requests.id')
                    ->where('citizen_requests.type', $type)
                    ->selectRaw('citizen_request_id, COUNT(*) as attachment_count')
                    ->groupBy('citizen_request_id')
                    ->get();
                
                $avgAttachments = 0;
                if ($attachmentsData->count() > 0) {
                    $totalAttachments = $attachmentsData->sum('attachment_count');
                    $avgAttachments = round($totalAttachments / $attachmentsData->count(), 1);
                } else {
                    // Valeur par défaut si aucune donnée
                    $avgAttachments = match($type) {
                        'attestation' => 2,
                        'legalisation' => 3,
                        'mariage' => 4,
                        'extrait-acte' => 2,
                        'declaration-naissance' => 3,
                        'certificat' => 2,
                        'information' => 1,
                        default => 2
                    };
                }

                $statistics[$type] = [
                    'label' => $label,
                    'total_requests' => $totalRequests,
                    'status_breakdown' => $statusDistribution,
                    'success_rate' => $successRate,
                    'avg_processing_time' => $avgProcessingTime,
                    'satisfaction_score' => $satisfactionScore,
                    'complexity_level' => $complexityLevel,
                    'monthly_evolution' => $monthlyEvolution,
                    'avg_attachments_required' => $avgAttachments,
                    'peak_hours' => match($type) {
                        'attestation' => ['09:00-11:00', '14:00-16:00'],
                        'legalisation' => ['10:00-12:00', '15:00-17:00'], 
                        'mariage' => ['08:00-10:00', '13:00-15:00'],
                        'extrait-acte' => ['09:00-11:00', '14:00-16:00'],
                        'declaration-naissance' => ['08:00-10:00', '16:00-18:00'],
                        'certificat' => ['10:00-12:00', '15:00-17:00'],
                        'information' => ['Toute la journée'],
                        default => ['09:00-11:00', '14:00-16:00']
                    },
                    'common_issues' => match($type) {
                        'attestation' => ['Justificatifs de domicile manquants', 'Factures trop anciennes'],
                        'legalisation' => ['Documents illisibles', 'Traductions manquantes'],
                        'mariage' => ['Certificats de célibat expirés', 'Témoins indisponibles'],
                        'extrait-acte' => ['Informations d\'état civil incorrectes', 'Pièces d\'identité expirées'],
                        'declaration-naissance' => ['Certificat médical manquant', 'Déclaration tardive'],
                        'certificat' => ['Justificatifs de célibat insuffisants', 'Enquête de moralité requise'],
                        'information' => ['Demande trop vague', 'Orientation vers autre service'],
                        default => ['Documents manquants', 'Informations incomplètes']
                    },
                    'processing_agents' => CitizenRequest::where('type', $type)
                        ->whereNotNull('processed_by')
                        ->distinct('processed_by')
                        ->count('processed_by') ?: 1,
                    'digital_percentage' => $totalRequests > 0 ? 
                        round((CitizenRequest::where('type', $type)
                            ->whereHas('attachments')
                            ->count() / $totalRequests) * 100) : 0,
                    'cost_per_request' => match($type) {
                        'attestation' => 2500,
                        'legalisation' => 5000,
                        'mariage' => 15000,
                        'extrait-acte' => 3000,
                        'declaration-naissance' => 1000,
                        'certificat' => 7500,
                        'information' => 0,
                        default => 2000
                    }
                ];
            }
        }

        return $statistics;
    }

    /**
     * Obtient la liste des utilisateurs pour l'API admin
     */
    public function getUsers()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);
        return response()->json($users);
    }
    
    /**
     * Obtient la liste des documents pour l'API admin
     */
    public function getDocuments()
    {
        $documents = Document::paginate(10);
        return response()->json($documents);
    }
    
    /**
     * Obtient la liste des demandes pour l'API admin
     */
    public function getRequests()
    {
        $requests = CitizenRequest::with(['citizen', 'document', 'agent'])->paginate(10);
        return response()->json($requests);
    }

    /**
     * Calcule le changement du taux de completion par rapport au mois précédent
     */
    private function getCompletionRateChange()
    {
        $currentMonthRate = $this->getCompletionRateForMonth(now());
        $previousMonthRate = $this->getCompletionRateForMonth(now()->subMonth());
        
        if ($previousMonthRate == 0) return 0;
        
        $change = (($currentMonthRate - $previousMonthRate) / $previousMonthRate) * 100;
        return round($change, 1);
    }

    /**
     * Calcule le taux de completion pour un mois donné
     */
    private function getCompletionRateForMonth($date)
    {
        $total = CitizenRequest::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
        if ($total == 0) return 0;
        
        $completed = CitizenRequest::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->whereIn('status', ['approved', 'rejected'])
            ->count();
            
        return ($completed / $total) * 100;
    }

    /**
     * Calcule le changement du temps de traitement par rapport au mois précédent
     */
    private function getProcessingTimeChange()
    {
        $currentMonthTime = $this->getAverageProcessingTimeForMonth(now());
        $previousMonthTime = $this->getAverageProcessingTimeForMonth(now()->subMonth());
        
        if ($previousMonthTime == 0) return 0;
        
        $change = (($currentMonthTime - $previousMonthTime) / $previousMonthTime) * 100;
        return round($change, 1);
    }

    /**
     * Calcule le temps de traitement moyen pour un mois donné
     */
    private function getAverageProcessingTimeForMonth($date)
    {
        $processedRequests = CitizenRequest::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->whereNotNull('processed_at')
            ->get();
            
        if ($processedRequests->count() == 0) return 0;
        
        $totalDays = 0;
        foreach ($processedRequests as $request) {
            $totalDays += $request->created_at->diffInDays($request->processed_at);
        }
        
        return $totalDays / $processedRequests->count();
    }

    /**
     * Calcule la croissance des demandes par rapport au mois précédent
     */
    private function getRequestsGrowth()
    {
        $currentMonth = CitizenRequest::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        $previousMonth = CitizenRequest::whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();
            
        if ($previousMonth == 0) return $currentMonth > 0 ? 100 : 0;
        
        $growth = (($currentMonth - $previousMonth) / $previousMonth) * 100;
        return round($growth, 1);
    }

    /**
     * Calcule le taux de satisfaction basé sur le taux d'approbation
     */
    private function getSatisfactionRate()
    {
        $total = CitizenRequest::whereIn('status', ['approved', 'rejected'])->count();
        
        if ($total == 0) return 85; // Valeur par défaut
        
        $approved = CitizenRequest::where('status', 'approved')->count();
        $satisfactionRate = ($approved / $total) * 100;
        
        // Ajustement du score de satisfaction (généralement plus élevé que le simple taux d'approbation)
        return min(98, round($satisfactionRate * 1.1)); // Bonus de 10% avec maximum de 98%
    }

    /**
     * Calcule le changement du taux de satisfaction par rapport au mois précédent
     */
    private function getSatisfactionChange()
    {
        $currentMonthSatisfaction = $this->getSatisfactionRateForMonth(now());
        $previousMonthSatisfaction = $this->getSatisfactionRateForMonth(now()->subMonth());
        
        if ($previousMonthSatisfaction == 0) return 0;
        
        $change = $currentMonthSatisfaction - $previousMonthSatisfaction;
        return round($change, 1);
    }

    /**
     * Calcule le taux de satisfaction pour un mois donné
     */
    private function getSatisfactionRateForMonth($date)
    {
        $total = CitizenRequest::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->whereIn('status', ['approved', 'rejected'])
            ->count();
            
        if ($total == 0) return 0;
        
        $approved = CitizenRequest::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->where('status', 'approved')
            ->count();
            
        $satisfactionRate = ($approved / $total) * 100;
        return min(98, round($satisfactionRate * 1.1));
    }

    /**
     * Obtient l'usage CPU (approximatif)
     */
    private function getCpuUsage()
    {
        // Sur Windows/serveur de développement, on simule
        if (PHP_OS_FAMILY === 'Windows') {
            return '25%'; // Valeur simulée raisonnable
        }
        
        // Sur Linux, essayer de lire /proc/loadavg
        if (is_readable('/proc/loadavg')) {
            $load = sys_getloadavg();
            if ($load !== false) {
                $cpuUsage = min(100, round($load[0] * 100 / $this->getCpuCores()));
                return $cpuUsage . '%';
            }
        }
        
        return '30%'; // Valeur par défaut
    }

    /**
     * Obtient le nombre de cœurs CPU
     */
    private function getCpuCores()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return $_SERVER['NUMBER_OF_PROCESSORS'] ?? '4';
        }
        
        if (is_readable('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            $cores = substr_count($cpuinfo, 'processor');
            return $cores > 0 ? $cores : '4';
        }
        
        return '4'; // Valeur par défaut
    }

    /**
     * Obtient l'usage mémoire
     */
    private function getMemoryUsage()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        if ($memoryLimit === '-1') {
            return '45%'; // Valeur simulée si pas de limite
        }
        
        $limit = $this->parseBytes($memoryLimit);
        if ($limit > 0) {
            $percentage = round(($memoryUsage / $limit) * 100);
            return min(100, $percentage) . '%';
        }
        
        return '45%'; // Valeur par défaut
    }

    /**
     * Obtient la mémoire totale disponible
     */
    private function getTotalMemory()
    {
        $memoryLimit = ini_get('memory_limit');
        
        if ($memoryLimit === '-1') {
            return '8 GB'; // Valeur par défaut
        }
        
        $bytes = $this->parseBytes($memoryLimit);
        return $this->formatBytes($bytes);
    }

    /**
     * Obtient l'usage du disque
     */
    private function getDiskUsage()
    {
        $root = '/';
        if (PHP_OS_FAMILY === 'Windows') {
            $root = 'C:';
        }
        
        $total = disk_total_space($root);
        $free = disk_free_space($root);
        
        if ($total && $free) {
            $used = $total - $free;
            $percentage = round(($used / $total) * 100);
            return $percentage . '%';
        }
        
        return '55%'; // Valeur par défaut
    }

    /**
     * Obtient l'espace disque libre
     */
    private function getFreeDiskSpace()
    {
        $root = '/';
        if (PHP_OS_FAMILY === 'Windows') {
            $root = 'C:';
        }
        
        $free = disk_free_space($root);
        return $free ? $this->formatBytes($free) : '500 GB';
    }

    /**
     * Obtient l'uptime du système (approximatif via fichier ou process)
     */
    private function getSystemUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return '5d 12h'; // Valeur simulée
        }
        
        if (is_readable('/proc/uptime')) {
            $uptime = file_get_contents('/proc/uptime');
            $seconds = intval(explode(' ', $uptime)[0]);
            
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            
            return $days . 'd ' . $hours . 'h';
        }
        
        return '7d 8h'; // Valeur par défaut
    }

    /**
     * Obtient la version de la base de données
     */
    private function getDatabaseVersion()
    {
        try {
            $version = \DB::select('SELECT VERSION() as version')[0]->version ?? '';
            if (strpos($version, 'MySQL') !== false || strpos($version, 'MariaDB') !== false) {
                return 'MySQL ' . explode('-', $version)[0];
            }
            return $version;
        } catch (\Exception $e) {
            return 'MySQL 8.0';
        }
    }

    /**
     * Obtient le temps de réponse du serveur web
     */
    private function getWebServerResponseTime()
    {
        $start = microtime(true);
        // Petit test de performance
        for ($i = 0; $i < 1000; $i++) {
            $test = md5($i);
        }
        $end = microtime(true);
        
        return round(($end - $start) * 1000); // en millisecondes
    }

    /**
     * Obtient le statut de la base de données
     */
    private function getDatabaseStatus()
    {
        try {
            \DB::connection()->getPdo();
            return 'running';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Obtient le temps de réponse de la base de données
     */
    private function getDatabaseResponseTime()
    {
        try {
            $start = microtime(true);
            \DB::select('SELECT 1');
            $end = microtime(true);
            
            return round(($end - $start) * 1000); // en millisecondes
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtient le nombre de tables dans la base de données
     */
    private function getDatabaseTableCount()
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            return count($tables);
        } catch (\Exception $e) {
            return 10; // Valeur par défaut
        }
    }

    /**
     * Obtient le nombre total d'enregistrements
     */
    private function getTotalDatabaseRecords()
    {
        try {
            $total = 0;
            $total += CitizenRequest::count();
            $total += User::count();
            $total += Document::count();
            $total += Attachment::count();
            return $total;
        } catch (\Exception $e) {
            return 50000; // Valeur par défaut
        }
    }

    /**
     * Obtient la taille de la base de données
     */
    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = \DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS DB_Size_MB 
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [$database]);
            
            $sizeMB = $result[0]->DB_Size_MB ?? 100;
            return $sizeMB . ' MB';
        } catch (\Exception $e) {
            return '150 MB'; // Valeur par défaut
        }
    }

    /**
     * Obtient le nombre de connexions actives
     */
    private function getActiveConnections()
    {
        try {
            $result = \DB::select('SHOW STATUS WHERE Variable_name = "Threads_connected"');
            return $result[0]->Value ?? 5;
        } catch (\Exception $e) {
            return 5; // Valeur par défaut
        }
    }

    /**
     * Obtient la taille d'une table
     */
    private function getTableSize($tableName)
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = \DB::select("
                SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                FROM information_schema.TABLES 
                WHERE table_schema = ? AND table_name = ?
            ", [$database, $tableName]);
            
            $sizeMB = $result[0]->size_mb ?? 1;
            return $sizeMB . ' MB';
        } catch (\Exception $e) {
            return '2 MB'; // Valeur par défaut
        }
    }

    /**
     * Obtient la dernière mise à jour d'une table
     */
    private function getTableLastUpdate($tableName)
    {
        try {
            switch ($tableName) {
                case 'citizen_requests':
                    $latest = CitizenRequest::latest()->first();
                    break;
                case 'users':
                    $latest = User::latest()->first();
                    break;
                case 'documents':
                    $latest = Document::latest()->first();
                    break;
                case 'attachments':
                    $latest = Attachment::latest()->first();
                    break;
                default:
                    return now()->format('Y-m-d H:i');
            }
            
            return $latest ? $latest->updated_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i');
        } catch (\Exception $e) {
            return now()->format('Y-m-d H:i');
        }
    }

    /**
     * Parse les bytes depuis une chaîne (ex: "128M")
     */
    private function parseBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
}
