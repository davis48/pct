@extends('admin.special.layout')

@section('title', 'Maintenance Système')
@section('subtitle', 'Outils de maintenance et d\'optimisation')

@section('content')
<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <button onclick="clearCache()" class="bg-white rounded-xl shadow-lg p-6 hover-lift text-left border-l-4 border-blue-500 group">
            <div class="flex items-center">
                <div class="stats-gradient rounded-lg p-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vider Cache</p>
                    <p class="text-lg font-bold text-gray-900">Laravel</p>
                </div>
            </div>
        </button>

        <button onclick="optimizeDatabase()" class="bg-white rounded-xl shadow-lg p-6 hover-lift text-left border-l-4 border-green-500 group">
            <div class="flex items-center">
                <div class="success-gradient rounded-lg p-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Optimiser</p>
                    <p class="text-lg font-bold text-gray-900">Base de Données</p>
                </div>
            </div>
        </button>

        <button onclick="createBackup()" class="bg-white rounded-xl shadow-lg p-6 hover-lift text-left border-l-4 border-yellow-500 group">
            <div class="flex items-center">
                <div class="warning-gradient rounded-lg p-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Créer</p>
                    <p class="text-lg font-bold text-gray-900">Sauvegarde</p>
                </div>
            </div>
        </button>

        <button onclick="cleanLogs()" class="bg-white rounded-xl shadow-lg p-6 hover-lift text-left border-l-4 border-purple-500 group">
            <div class="flex items-center">
                <div class="card-gradient rounded-lg p-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Nettoyer</p>
                    <p class="text-lg font-bold text-gray-900">Journaux</p>
                </div>
            </div>
        </button>
    </div>

    <!-- Maintenance Status -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">État de Maintenance</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Cache System</span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Dernière optimisation: {{ $maintenanceInfo['cache_last_cleared'] ?? 'Jamais' }}</p>
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Base de Données</span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Dernière optimisation: {{ $maintenanceInfo['db_last_optimized'] ?? 'Jamais' }}</p>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Sauvegarde</span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Dernière sauvegarde: {{ $maintenanceInfo['last_backup'] ?? 'Jamais' }}</p>
            </div>
        </div>
    </div>

    <!-- Backup Management -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Gestion des Sauvegardes</h3>
            <button onclick="createBackup()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle Sauvegarde
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taille</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($backups ?? [] as $backup)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ $backup['name'] ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $backup['date'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $backup['size'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if(($backup['type'] ?? '') === 'automatic') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $backup['type'] === 'automatic' ? 'Automatique' : 'Manuelle' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">Télécharger</button>
                            <button class="text-red-600 hover:text-red-900">Supprimer</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Maintenance Tools -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cache Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestion du Cache</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Cache Application</p>
                        <p class="text-xs text-gray-500">{{ $cacheInfo['app_cache_size'] ?? '0 MB' }}</p>
                    </div>
                    <button onclick="clearCache('app')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                        Vider
                    </button>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Cache Config</p>
                        <p class="text-xs text-gray-500">{{ $cacheInfo['config_cache_size'] ?? '0 MB' }}</p>
                    </div>
                    <button onclick="clearCache('config')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                        Vider
                    </button>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Cache Routes</p>
                        <p class="text-xs text-gray-500">{{ $cacheInfo['route_cache_size'] ?? '0 MB' }}</p>
                    </div>
                    <button onclick="clearCache('route')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                        Vider
                    </button>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Cache Views</p>
                        <p class="text-xs text-gray-500">{{ $cacheInfo['view_cache_size'] ?? '0 MB' }}</p>
                    </div>
                    <button onclick="clearCache('view')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                        Vider
                    </button>
                </div>
            </div>
        </div>

        <!-- Log Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestion des Journaux</h3>
            <div class="space-y-4">
                @foreach($logFiles ?? [] as $logFile)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $logFile['name'] ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $logFile['size'] ?? '0 MB' }} - {{ $logFile['entries'] ?? 0 }} entrées</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-900 text-xs">Voir</button>
                        <button class="text-red-600 hover:text-red-900 text-xs">Vider</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Database Maintenance -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Maintenance Base de Données</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Actions de Maintenance</h4>
                <div class="space-y-3">
                    <button onclick="optimizeDatabase()" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Optimiser Tables
                        </div>
                        <p class="text-xs text-green-100 mt-1 ml-8">Réorganise et optimise les tables</p>
                    </button>

                    <button onclick="analyzeTables()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Analyser Tables
                        </div>
                        <p class="text-xs text-blue-100 mt-1 ml-8">Analyse la distribution des clés</p>
                    </button>

                    <button onclick="checkTables()" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Vérifier Tables
                        </div>
                        <p class="text-xs text-yellow-100 mt-1 ml-8">Vérifie l'intégrité des tables</p>
                    </button>
                </div>
            </div>

            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Statistiques</h4>
                <div class="space-y-3">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <div class="text-lg font-bold text-blue-600">{{ $dbStats['total_size'] ?? '0 MB' }}</div>
                        <div class="text-sm text-gray-600">Taille totale</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-3">
                        <div class="text-lg font-bold text-green-600">{{ $dbStats['fragmentation'] ?? '0%' }}</div>
                        <div class="text-sm text-gray-600">Fragmentation</div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-3">
                        <div class="text-lg font-bold text-yellow-600">{{ $dbStats['last_optimized'] ?? 'Jamais' }}</div>
                        <div class="text-sm text-gray-600">Dernière optimisation</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduled Tasks -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tâches Programmées</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fréquence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière Exécution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prochaine Exécution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($scheduledTasks ?? [] as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $task['name'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task['frequency'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task['last_run'] ?? 'Jamais' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task['next_run'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if(($task['status'] ?? '') === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $task['status'] === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">Exécuter</button>
                            <button class="text-green-600 hover:text-green-900 mr-3">Activer</button>
                            <button class="text-red-600 hover:text-red-900">Désactiver</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span id="loadingText">Traitement en cours...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showLoading(text = 'Traitement en cours...') {
    document.getElementById('loadingText').textContent = text;
    document.getElementById('loadingModal').classList.remove('hidden');
    document.getElementById('loadingModal').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingModal').classList.add('hidden');
    document.getElementById('loadingModal').classList.remove('flex');
}

function clearCache(type = 'all') {
    showLoading('Vidage du cache en cours...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Cache vidé avec succès!');
    }, 2000);
}

function optimizeDatabase() {
    showLoading('Optimisation de la base de données...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Base de données optimisée avec succès!');
    }, 3000);
}

function createBackup() {
    showLoading('Création de la sauvegarde...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Sauvegarde créée avec succès!');
    }, 5000);
}

function cleanLogs() {
    showLoading('Nettoyage des journaux...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Journaux nettoyés avec succès!');
    }, 2000);
}

function analyzeTables() {
    showLoading('Analyse des tables...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Analyse des tables terminée!');
    }, 3000);
}

function checkTables() {
    showLoading('Vérification des tables...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        alert('Vérification des tables terminée!');
    }, 4000);
}
</script>
@endpush
@endsection
