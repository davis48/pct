@extends('admin.special.layout')

@section('title', 'Performance Système')
@section('subtitle', 'Analyse des performances et optimisations')

@section('content')
<div class="space-y-6">
    <!-- Performance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="stats-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Score Performance</p>
                    <p class="text-2xl font-bold text-gray-900">92/100</p>
                    <p class="text-xs text-green-600">+5 cette semaine</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="success-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Temps Réponse</p>
                    <p class="text-2xl font-bold text-gray-900">150ms</p>
                    <p class="text-xs text-green-600">-20ms vs hier</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="warning-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Débit</p>
                    <p class="text-2xl font-bold text-gray-900">1,240</p>
                    <p class="text-xs text-blue-600">req/min</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center">
                <div class="card-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Uptime</p>
                    <p class="text-2xl font-bold text-gray-900">99.9%</p>
                    <p class="text-xs text-green-600">30 derniers jours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Response Time Trend -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution Temps de Réponse (24h)</h3>
            <canvas id="responseTimeChart" class="max-h-64"></canvas>
        </div>

        <!-- Throughput Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Débit du Système (24h)</h3>
            <canvas id="throughputChart" class="max-h-64"></canvas>
        </div>
    </div>

    <!-- Resource Usage -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Utilisation des Ressources</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- CPU Usage -->
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center w-32 h-32 mb-4">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200"></circle>
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" 
                                stroke-dasharray="351.86" stroke-dashoffset="105.558" class="text-blue-500"></circle>
                    </svg>
                    <span class="absolute text-2xl font-bold text-gray-700">70%</span>
                </div>
                <h4 class="text-lg font-medium text-gray-800">CPU</h4>
                <p class="text-sm text-gray-600">8 cœurs disponibles</p>
            </div>

            <!-- Memory Usage -->
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center w-32 h-32 mb-4">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200"></circle>
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" 
                                stroke-dasharray="351.86" stroke-dashoffset="176.0" class="text-green-500"></circle>
                    </svg>
                    <span class="absolute text-2xl font-bold text-gray-700">50%</span>
                </div>
                <h4 class="text-lg font-medium text-gray-800">Mémoire</h4>
                <p class="text-sm text-gray-600">8 GB / 16 GB</p>
            </div>

            <!-- Disk Usage -->
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center w-32 h-32 mb-4">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200"></circle>
                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" 
                                stroke-dasharray="351.86" stroke-dashoffset="246.3" class="text-yellow-500"></circle>
                    </svg>
                    <span class="absolute text-2xl font-bold text-gray-700">30%</span>
                </div>
                <h4 class="text-lg font-medium text-gray-800">Disque</h4>
                <p class="text-sm text-gray-600">300 GB / 1 TB</p>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Database Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Base de Données</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Requêtes par seconde</span>
                    <span class="text-sm font-bold text-gray-900">1,250 QPS</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Temps de réponse moyen</span>
                    <span class="text-sm font-bold text-gray-900">15ms</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Connexions actives</span>
                    <span class="text-sm font-bold text-gray-900">12 / 100</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Cache hit ratio</span>
                    <span class="text-sm font-bold text-green-600">98.5%</span>
                </div>
            </div>
        </div>

        <!-- Application Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Application</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Routes les plus lentes</span>
                    <span class="text-sm font-bold text-gray-900">/admin/statistics</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Erreurs 500 (24h)</span>
                    <span class="text-sm font-bold text-red-600">2</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Queue en attente</span>
                    <span class="text-sm font-bold text-gray-900">0 jobs</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Sessions actives</span>
                    <span class="text-sm font-bold text-blue-600">45</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Slow Queries Analysis -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Analyse des Requêtes Lentes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requête</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps Moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exécutions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Impact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">SELECT * FROM requests WHERE...</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">450ms</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1,234</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Élevé</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900">Optimiser</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">SELECT COUNT(*) FROM agents...</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">220ms</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">856</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Moyen</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900">Optimiser</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">UPDATE documents SET...</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">180ms</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">432</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Faible</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-gray-600 hover:text-gray-900">Surveiller</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Recommendations -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recommandations d'Optimisation</h3>
        <div class="space-y-4">
            <div class="flex items-start p-4 bg-blue-50 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Indexation Base de Données</h4>
                    <p class="text-sm text-blue-700 mt-1">Ajouter un index sur la colonne 'status' de la table 'requests' pour améliorer les performances des requêtes de filtrage.</p>
                </div>
            </div>

            <div class="flex items-start p-4 bg-yellow-50 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-yellow-800">Cache Redis</h4>
                    <p class="text-sm text-yellow-700 mt-1">Configurer Redis pour mettre en cache les statistiques fréquemment consultées et réduire la charge sur la base de données.</p>
                </div>
            </div>

            <div class="flex items-start p-4 bg-green-50 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-green-800">Optimisation Images</h4>
                    <p class="text-sm text-green-700 mt-1">Bon travail ! Les images sont correctement optimisées et compressées.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Response Time Chart
    const responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
    new Chart(responseTimeCtx, {
        type: 'line',
        data: {
            labels: Array.from({length: 24}, (_, i) => String(i).padStart(2, '0') + ':00'),
            datasets: [{
                label: 'Temps de réponse (ms)',
                data: Array.from({length: 24}, () => Math.floor(Math.random() * 100) + 100),
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Throughput Chart
    const throughputCtx = document.getElementById('throughputChart').getContext('2d');
    new Chart(throughputCtx, {
        type: 'bar',
        data: {
            labels: Array.from({length: 24}, (_, i) => String(i).padStart(2, '0') + ':00'),
            datasets: [{
                label: 'Requêtes/min',
                data: Array.from({length: 24}, () => Math.floor(Math.random() * 500) + 800),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
@endsection
