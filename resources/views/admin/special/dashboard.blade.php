@extends('admin.special.layout')

@section('title', 'Tableau de Bord Admin Spécial')
@section('subtitle', 'Vue d\'ensemble avancée du système')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Requests -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="stats-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Demandes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_requests'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['requests_today'] ?? 0 }} aujourd'hui</p>
                </div>
            </div>
        </div>

        <!-- Processing Rate -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="success-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Taux de Traitement</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['completion_rate'] ?? 0, 1) }}%</p>
                    <p class="text-xs text-green-600">+{{ number_format($stats['completion_rate_change'] ?? 0, 1) }}% cette semaine</p>
                </div>
            </div>
        </div>

        <!-- Average Processing Time -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="warning-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Temps Moyen</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @php
                            $hours = $stats['avg_processing_time'] ?? 0;
                            if ($hours < 24) {
                                echo number_format($hours, 1) . ' heures';
                            } else {
                                $days = floor($hours / 24);
                                $remainingHours = $hours % 24;
                                echo $days . ' jour(s) ' . number_format($remainingHours, 1) . ' h';
                            }
                        @endphp
                    </p>
                    <p class="text-xs text-yellow-600">+{{ number_format($stats['processing_time_change'] ?? 0, 1) }}% vs. mois dernier</p>
                </div>
            </div>
        </div>

        <!-- Active Agents -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="card-gradient rounded-lg p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Agents Actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_agents'] ?? 0 }}</p>
                    <p class="text-xs text-purple-600">{{ $stats['total_agents'] ?? 0 }} total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Requests Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Demandes par Jour</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">7j</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">30j</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="requestsChart"></canvas>
            </div>
        </div>

        <!-- Document Types Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition par Type de Document</h3>
            <div class="h-64">
                <canvas id="documentTypesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Document Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($documentStats ?? [] as $docType => $stats)
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-800">{{ $docType }}</h4>
                <div class="stats-gradient rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total traité</span>
                    <span class="font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Temps moyen</span>
                    <span class="font-semibold text-gray-900">{{ $stats['avg_time'] ?? '0 min' }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Taux de succès</span>
                    <span class="font-semibold text-green-600">{{ number_format($stats['success_rate'] ?? 0, 1) }}%</span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="stats-gradient h-2 rounded-full" style="width: {{ $stats['success_rate'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- System Status and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">État du Système</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Base de données</span>
                    </div>
                    <span class="text-xs text-green-600 font-medium">En ligne</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Serveur Web</span>
                    </div>
                    <span class="text-xs text-green-600 font-medium">En ligne</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Stockage</span>
                    </div>
                    <span class="text-xs text-yellow-600 font-medium">{{ $systemInfo['disk_usage'] ?? '0%' }} utilisé</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Mémoire</span>
                    </div>
                    <span class="text-xs text-blue-600 font-medium">{{ $systemInfo['memory_usage'] ?? '0%' }} utilisée</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activité Récente</h3>            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach($recentActivities ?? [] as $activity)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">{{ $activity['message'] ?? 'Activité système' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] ?? now() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Métriques de Performance</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $performance['best_agent'] ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600 mt-1">Agent le Plus Performant</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $performance['fastest_document'] ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600 mt-1">Document le Plus Rapide</div>
            </div>
            
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $performance['peak_hour'] ?? 'N/A' }}</div>
                <div class="text-sm text-gray-600 mt-1">Heure de Pointe</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Requests Chart
    const requestsCtx = document.getElementById('requestsChart').getContext('2d');
    new Chart(requestsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['requests']['labels'] ?? []) !!},
            datasets: [{
                label: 'Demandes',
                data: {!! json_encode($chartData['requests']['data'] ?? []) !!},
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

    // Document Types Chart
    const documentTypesCtx = document.getElementById('documentTypesChart').getContext('2d');
    new Chart(documentTypesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartData['document_types']['labels'] ?? []) !!},
            datasets: [{
                data: {!! json_encode($chartData['document_types']['data'] ?? []) !!},
                backgroundColor: [
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection
