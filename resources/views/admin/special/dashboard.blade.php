@extends('admin.special.layout')

@section('title', 'Tableau de Bord Admin Spécial')
@section('subtitle', 'Vue d\'ensemble avancée du système')

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .success-gradient {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .warning-gradient {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .error-gradient {
        background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
    }
    
    .purple-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .performance-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .performance-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.1);
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: #ef4444;
    }
    
    .trend-stable {
        color: #6b7280;
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .metric-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
    }
    
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        background-color: #f3f4f6;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease;
    }
</style>
@endpush

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
    <div class="space-y-6">
        <h3 class="text-2xl font-bold text-gray-800">Métriques de Performance</h3>
        
        <!-- Performance KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Response Time -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Temps de Réponse</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $performance['response_time']['current'] ?? 0 }}ms</p>
                    </div>
                    <div class="stats-gradient rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Cible: {{ $performance['response_time']['target'] ?? 0 }}ms</span>
                    <span class="text-xs text-green-600 font-medium">
                        @if(($performance['response_time']['trend'] ?? '') === 'improving')
                            ↗ Amélioration
                        @else
                            → Stable
                        @endif
                    </span>
                </div>
                <!-- Progress bar -->
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, (($performance['response_time']['target'] ?? 200) / ($performance['response_time']['current'] ?? 200)) * 100) }}%"></div>
                </div>
            </div>

            <!-- Throughput -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Débit</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $performance['throughput']['requests_per_minute'] ?? 0 }}/min</p>
                    </div>
                    <div class="success-gradient rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Pic: {{ $performance['throughput']['peak_today'] ?? 0 }}/min</span>
                    <span class="text-xs text-blue-600 font-medium">{{ $performance['throughput']['trend'] ?? 'stable' }}</span>
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, (($performance['throughput']['requests_per_minute'] ?? 0) / ($performance['throughput']['peak_today'] ?? 1)) * 100) }}%"></div>
                </div>
            </div>

            <!-- Error Rate -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Taux d'Erreur</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($performance['error_rate']['current'] ?? 0, 1) }}%</p>
                    </div>
                    <div class="error-gradient rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Cible: <{{ $performance['error_rate']['target'] ?? 1 }}%</span>
                    <span class="text-xs text-green-600 font-medium">
                        @if(($performance['error_rate']['current'] ?? 0) < ($performance['error_rate']['target'] ?? 1))
                            ✓ Objectif atteint
                        @else
                            ⚠ À améliorer
                        @endif
                    </span>
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(100, ($performance['error_rate']['current'] ?? 0) * 20) }}%"></div>
                </div>
            </div>

            <!-- Availability -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover-lift border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Disponibilité</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($performance['availability']['current'] ?? 0, 1) }}%</p>
                    </div>
                    <div class="purple-gradient rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Cible: {{ $performance['availability']['target'] ?? 99.5 }}%</span>
                    <span class="text-xs text-green-600 font-medium">{{ $performance['availability']['trend'] ?? 'excellent' }}</span>
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $performance['availability']['current'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Performance Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Agent Performance Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Performance des Agents</h4>
                <div class="h-64">
                    <canvas id="agentPerformanceChart"></canvas>
                </div>
            </div>

            <!-- Hourly Performance Trend -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Évolution des Temps de Réponse</h4>
                <div class="h-64">
                    <canvas id="hourlyPerformanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Performances Exceptionnelles</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-2">{{ $performance['best_agent'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Agent le Plus Performant</div>
                    <div class="text-xs text-blue-500 mt-1">97% de satisfaction</div>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-2">{{ $performance['fastest_document'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Document le Plus Rapide</div>
                    <div class="text-xs text-green-500 mt-1">Traité en 98min en moyenne</div>
                </div>
                
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 mb-2">{{ $performance['peak_hour'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Heure de Pointe</div>
                    <div class="text-xs text-purple-500 mt-1">78 demandes/min max</div>
                </div>
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

    // Agent Performance Chart
    const agentPerformanceCtx = document.getElementById('agentPerformanceChart').getContext('2d');
    new Chart(agentPerformanceCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($performance['agent_performance']['labels'] ?? []) !!},
            datasets: [{
                label: 'Demandes Traitées',
                data: {!! json_encode($performance['agent_performance']['completed_requests'] ?? []) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Temps Moyen (min)',
                data: {!! json_encode($performance['agent_performance']['avg_processing_time'] ?? []) !!},
                type: 'line',
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Agents'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Demandes Traitées'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Temps (minutes)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return context.dataset.label + ': ' + context.parsed.y + ' demandes';
                            } else {
                                return context.dataset.label + ': ' + context.parsed.y + ' min';
                            }
                        }
                    }
                }
            }
        }
    });

    // Hourly Performance Chart
    const hourlyPerformanceCtx = document.getElementById('hourlyPerformanceChart').getContext('2d');
    new Chart(hourlyPerformanceCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($performance['hourly_performance']['labels'] ?? []) !!},
            datasets: [{
                label: 'Temps de Réponse (ms)',
                data: {!! json_encode($performance['hourly_performance']['response_times'] ?? []) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }, {
                label: 'Volume de Demandes',
                data: {!! json_encode($performance['hourly_performance']['request_volume'] ?? []) !!},
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: false,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Heure'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Temps de Réponse (ms)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Volume de Demandes'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return context.dataset.label + ': ' + context.parsed.y + 'ms';
                            } else {
                                return context.dataset.label + ': ' + context.parsed.y + ' demandes';
                            }
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
