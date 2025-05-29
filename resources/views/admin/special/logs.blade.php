@extends('admin.special.layout')

@section('title', 'Journaux Système')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Journaux Système</h1>
                <p class="text-gray-600 mt-1">Surveillance et analyse des logs système</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportLogs()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Exporter
                </button>
                <button onclick="clearLogs()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Vider
                </button>
                <button onclick="refreshLogs()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau de log</label>
                <select id="logLevel" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les niveaux</option>
                    <option value="error">Erreur</option>
                    <option value="warning">Avertissement</option>
                    <option value="info">Information</option>
                    <option value="debug">Debug</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select id="logCategory" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    <option value="auth">Authentification</option>
                    <option value="database">Base de données</option>
                    <option value="mail">Email</option>
                    <option value="file">Fichiers</option>
                    <option value="api">API</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="logDate" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" id="logSearch" placeholder="Rechercher dans les logs..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Statistiques des logs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-red-100">Erreurs (24h)</p>
                    <p class="text-3xl font-bold">{{ $logs['error_count'] ?? 23 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-yellow-100">Avertissements</p>
                    <p class="text-3xl font-bold">{{ $logs['warning_count'] ?? 87 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-info-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-blue-100">Informations</p>
                    <p class="text-3xl font-bold">{{ $logs['info_count'] ?? 1245 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-green-100">Total Logs</p>
                    <p class="text-3xl font-bold">{{ $logs['total_count'] ?? 2156 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des logs par heure -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité des logs par heure</h3>
        <div class="h-64">
            <canvas id="logsChart"></canvas>
        </div>
    </div>

    <!-- Journal en temps réel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Logs récents -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Logs récents</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3 max-h-96 overflow-y-auto" id="logsContainer">
                    @php
                    $sampleLogs = [
                        ['level' => 'error', 'time' => '14:32:15', 'category' => 'auth', 'message' => 'Tentative de connexion échouée pour l\'utilisateur admin@example.com'],
                        ['level' => 'info', 'time' => '14:31:42', 'category' => 'database', 'message' => 'Connexion à la base de données établie'],
                        ['level' => 'warning', 'time' => '14:30:18', 'category' => 'file', 'message' => 'Espace disque faible : 85% utilisé'],
                        ['level' => 'info', 'time' => '14:29:33', 'category' => 'api', 'message' => 'Demande API traitée avec succès'],
                        ['level' => 'error', 'time' => '14:28:47', 'category' => 'mail', 'message' => 'Échec d\'envoi d\'email à citizen@example.com'],
                        ['level' => 'debug', 'time' => '14:27:12', 'category' => 'database', 'message' => 'Requête SQL exécutée en 0.24s'],
                        ['level' => 'info', 'time' => '14:26:08', 'category' => 'auth', 'message' => 'Connexion réussie pour agent001'],
                        ['level' => 'warning', 'time' => '14:25:35', 'category' => 'api', 'message' => 'Limite de taux API approchée pour 192.168.1.100']
                    ];
                    @endphp

                    @foreach($sampleLogs as $log)
                    <div class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            @if($log['level'] === 'error')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Erreur
                                </span>
                            @elseif($log['level'] === 'warning')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Alerte
                                </span>
                            @elseif($log['level'] === 'info')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Info
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-bug mr-1"></i>
                                    Debug
                                </span>
                            @endif
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $log['message'] }}</p>
                            <div class="flex items-center mt-1 text-xs text-gray-500">
                                <span>{{ $log['time'] }}</span>
                                <span class="mx-2">•</span>
                                <span class="capitalize">{{ $log['category'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Alertes système -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Alertes système</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-red-800">Erreurs critiques détectées</h4>
                                <p class="text-sm text-red-700 mt-1">23 erreurs dans les dernières 24h</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-clock text-yellow-500 mt-1"></i>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-800">Performance dégradée</h4>
                                <p class="text-sm text-yellow-700 mt-1">Temps de réponse élevé détecté</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">Maintenance programmée</h4>
                                <p class="text-sm text-blue-700 mt-1">Redémarrage prévu à 02:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyse des erreurs fréquentes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top des erreurs fréquentes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Erreur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fréquence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière occurrence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Connexion base de données échouée</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">12 fois</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Il y a 2 heures</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900">Analyser</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Échec d'authentification</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">8 fois</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Il y a 30 minutes</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900">Analyser</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Fichier non trouvé</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">5 fois</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Il y a 1 heure</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900">Analyser</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Graphique des logs par heure
const logsCtx = document.getElementById('logsChart').getContext('2d');
new Chart(logsCtx, {
    type: 'line',
    data: {
        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
        datasets: [{
            label: 'Erreurs',
            data: [2, 1, 3, 8, 5, 3],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }, {
            label: 'Avertissements',
            data: [5, 3, 8, 15, 12, 7],
            borderColor: 'rgb(245, 158, 11)',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            tension: 0.4
        }, {
            label: 'Informations',
            data: [20, 15, 35, 45, 38, 25],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Fonctions de gestion des logs
function exportLogs() {
    // Simulation d'export
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = 'Export des logs en cours...';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.textContent = 'Logs exportés avec succès!';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    }, 1500);
}

function clearLogs() {
    if (confirm('Êtes-vous sûr de vouloir vider tous les logs? Cette action est irréversible.')) {
        // Simulation de vidage
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = 'Logs vidés avec succès!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }
}

function refreshLogs() {
    // Simulation de rafraîchissement
    const container = document.getElementById('logsContainer');
    container.style.opacity = '0.5';
    
    setTimeout(() => {
        container.style.opacity = '1';
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = 'Logs actualisés!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    }, 1000);
}

// Filtres en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['logLevel', 'logCategory', 'logDate', 'logSearch'];
    
    filters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', function() {
                // Simulation de filtrage
                console.log(`Filtrage par ${filterId}:`, this.value);
            });
        }
    });
});

// Auto-refresh des logs toutes les 30 secondes
setInterval(() => {
    // Simulation d'actualisation automatique
    console.log('Auto-refresh des logs...');
}, 30000);
</script>
@endsection
