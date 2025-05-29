@extends('admin.special.layout')

@section('title', 'Statistiques Avancées')
@section('subtitle', 'Analyses détaillées et métriques de performance')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtres</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="period" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="7">7 derniers jours</option>
                    <option value="30">30 derniers jours</option>
                    <option value="90">3 derniers mois</option>
                    <option value="365">Dernière année</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de Document</label>
                <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les types</option>
                    <option value="acte_naissance">Acte de Naissance</option>
                    <option value="declaration_naissance">Déclaration de Naissance</option>
                    <option value="acte_mariage">Acte de Mariage</option>
                    <option value="acte_deces">Acte de Décès</option>
                    <option value="certificat_nationalite">Certificat de Nationalité</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                <select name="agent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les agents</option>
                    @foreach($agents ?? [] as $agent)
                    <option value="{{ $agent['id'] }}">{{ $agent['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Appliquer Filtres
                </button>
            </div>
        </form>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Demandes Totales</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_requests'] ?? 0) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +{{ $kpis['requests_growth'] ?? 0 }}% vs période précédente
                        </span>
                    </p>
                </div>
                <div class="stats-gradient rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Taux de Complétion</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['completion_rate'] ?? 0, 1) }}%</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +{{ number_format($kpis['completion_rate_change'] ?? 0, 1) }}%
                        </span>
                    </p>
                </div>
                <div class="success-gradient rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l3.5 3.5L20 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Temps Moyen</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $kpis['avg_processing_time'] ?? '0 min' }}</p>
                    <p class="text-sm text-red-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                            {{ $kpis['processing_time_change'] ?? '0%' }}
                        </span>
                    </p>
                </div>
                <div class="warning-gradient rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Satisfaction Client</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['satisfaction_rate'] ?? 0, 1) }}%</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            +{{ number_format($kpis['satisfaction_change'] ?? 0, 1) }}%
                        </span>
                    </p>
                </div>
                <div class="card-gradient rounded-lg p-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 100 5H9m0-5v5m0-5.5V8a2 2 0 012-2h.5M15 8a2 2 0 012 2v1M9 15v-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Type Statistics -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Statistiques par Type de Document d'Acte Civil</h3>
        
        <!-- Document Types Table -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-gray-700 mb-4">Tableau des Statistiques</h4>
            <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de Document</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps Moyen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Succès</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($documentTypeStats ?? [] as $type => $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $stat['color'] ?? '#6366f1' }}"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $stat['name'] ?? $type }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stat['total'] ?? 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['avg_time'] ?? '0 min' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stat['success_rate'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-900">{{ number_format($stat['success_rate'] ?? 0, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Document Types Chart -->
        <div>
            <h4 class="text-md font-medium text-gray-700 mb-4">Graphique de Performance</h4>
            <div class="bg-gray-50 rounded-lg p-6" style="height: 400px;">
                <canvas id="documentStatsChart" style="max-height: 100%; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Processing Time Trend -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution du Temps de Traitement</h3>
            <canvas id="processingTimeChart" class="max-h-64"></canvas>
        </div>

        <!-- Agent Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance des Agents</h3>
            <canvas id="agentPerformanceChart" class="max-h-64"></canvas>
        </div>
    </div>

    <!-- Detailed Agent Statistics -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Statistiques Détaillées des Agents</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandes Traitées</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps Moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de Succès</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière Activité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($agentStats ?? [] as $agent)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-medium">{{ substr($agent['name'] ?? '', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $agent['name'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $agent['email'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($agent['requests_handled'] ?? 0) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $agent['avg_time'] ?? '0 min' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if(($agent['success_rate'] ?? 0) >= 90) bg-green-100 text-green-800
                                @elseif(($agent['success_rate'] ?? 0) >= 70) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ number_format($agent['success_rate'] ?? 0, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $agent['last_activity'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= ($agent['performance_rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hourly Activity Heatmap -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Heatmap d'Activité Horaire</h3>
        <div class="grid grid-cols-24 gap-1">
            @for($hour = 0; $hour < 24; $hour++)
                <div class="text-center">
                    <div class="text-xs text-gray-500 mb-1">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}h</div>
                    <div class="h-8 rounded" 
                         style="background-color: rgba(99, 102, 241, {{ ($hourlyActivity[$hour] ?? 0) / 100 }})"
                         title="{{ $hourlyActivity[$hour] ?? 0 }}% d'activité à {{ $hour }}h">
                    </div>
                </div>
            @endfor
        </div>
        <div class="flex justify-between items-center mt-4 text-xs text-gray-500">
            <span>Moins d'activité</span>
            <span>Plus d'activité</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Document Stats Chart
    const documentStatsCtx = document.getElementById('documentStatsChart');
    if (documentStatsCtx) {
        new Chart(documentStatsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['document_stats']['labels'] ?? ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance']) !!},
                datasets: [{
                    label: 'Nombre de demandes',
                    data: {!! json_encode($chartData['document_stats']['data'] ?? [45, 30, 15, 10]) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)', 
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(168, 85, 247, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)', 
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' demandes';
                            }
                        }
                    }
                }
            }
        });
    }

    // Processing Time Chart
    const processingTimeCtx = document.getElementById('processingTimeChart').getContext('2d');
    new Chart(processingTimeCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['processing_time']['labels'] ?? []) !!},
            datasets: [{
                label: 'Temps de traitement (minutes)',
                data: {!! json_encode($chartData['processing_time']['data'] ?? []) !!},
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
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
            }
        }
    });

    // Agent Performance Chart
    const agentPerformanceCtx = document.getElementById('agentPerformanceChart').getContext('2d');
    new Chart(agentPerformanceCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['agent_performance']['labels'] ?? []) !!},
            datasets: [{
                label: 'Demandes traitées',
                data: {!! json_encode($chartData['agent_performance']['data'] ?? []) !!},
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
            }
        }
    });
});
</script>
@endpush
@endsection
