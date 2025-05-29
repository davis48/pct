<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CitizenRequest;
use App\Models\Document;
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
            'completion_rate_change' => rand(1, 10) / 10, // Simulation
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'processing_time_change' => rand(1, 10) / 10, // Simulation - nombre au lieu de chaîne
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
            'requests_growth' => rand(5, 15),
            'completion_rate' => $this->getCompletionRate(),
            'completion_rate_change' => rand(1, 5),
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'processing_time_change' => '-2%',
            'satisfaction_rate' => rand(85, 98),
            'satisfaction_change' => rand(1, 3),
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
            'cpu_usage' => rand(10, 80) . '%',
            'cpu_cores' => '8',
            'memory_usage' => rand(30, 70) . '%',
            'memory_total' => '16 GB',
            'disk_usage' => rand(20, 60) . '%',
            'disk_free' => '500 GB',
            'uptime' => rand(1, 30) . 'd ' . rand(1, 23) . 'h',
        ];

        $serverInfo = [
            'os' => 'Ubuntu 22.04 LTS',
            'web_server' => 'Apache 2.4.52',
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'database' => 'MySQL 8.0.32',
            'timezone' => config('app.timezone'),
        ];

        $services = [
            ['name' => 'Apache Web Server', 'status' => 'running', 'response_time' => rand(50, 200)],
            ['name' => 'MySQL Database', 'status' => 'running', 'response_time' => rand(10, 50)],
            ['name' => 'Redis Cache', 'status' => 'running', 'response_time' => rand(5, 20)],
            ['name' => 'Queue Worker', 'status' => 'running', 'response_time' => null],
            ['name' => 'Scheduler', 'status' => 'running', 'response_time' => null],
        ];

        $databaseInfo = [
            'total_tables' => 15,
            'total_records' => number_format(rand(10000, 100000)),
            'database_size' => rand(100, 500) . ' MB',
            'active_connections' => rand(5, 20),
        ];

        $databaseTables = [
            ['name' => 'requests', 'rows' => rand(1000, 5000), 'size' => rand(10, 50) . ' MB', 'updated_at' => now()->subHours(rand(1, 24))->format('Y-m-d H:i')],
            ['name' => 'agents', 'rows' => rand(50, 200), 'size' => rand(1, 5) . ' MB', 'updated_at' => now()->subDays(rand(1, 7))->format('Y-m-d H:i')],
            ['name' => 'documents', 'rows' => rand(500, 2000), 'size' => rand(5, 20) . ' MB', 'updated_at' => now()->subHours(rand(1, 12))->format('Y-m-d H:i')],
            ['name' => 'users', 'rows' => rand(100, 1000), 'size' => rand(2, 10) . ' MB', 'updated_at' => now()->subDays(rand(1, 30))->format('Y-m-d H:i')],
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
            ]
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
            'response_time' => [
                'current' => 245,
                'average' => 180,
                'target' => 200
            ],
            'throughput' => [
                'requests_per_minute' => 45,
                'peak_today' => 78,
                'average_daily' => 52
            ],
            'error_rate' => [
                'current' => 0.8,
                'target' => 1.0,
                'last_24h' => 1.2
            ],
            'availability' => [
                'current' => 99.9,
                'monthly' => 99.7,
                'target' => 99.5
            ]
        ];
    }

    private function getChartData()
    {
        $labels = [];
        $requests = [];
        $processed = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $requests[] = rand(20, 80);
            $processed[] = rand(15, 70);
        }
        
        // Données pour le graphique de répartition par type de document
        $documentTypesData = [
            'labels' => ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance', 'Certificat de Résidence'],
            'data' => [45, 25, 15, 10, 5]
        ];
        
        return [
            'labels' => $labels,
            'requests' => $requests,
            'processed' => $processed,
            'document_types' => $documentTypesData
        ];
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
        return response()->json($requests);    }
}
