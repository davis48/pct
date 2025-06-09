@extends('layouts.agent')

@section('title', 'Statistiques')

@section('content')
<div class="space-y-6" x-data="statisticsPage">
    <!-- En-tête avec boutons d'action -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Statistiques et Analyses</h1>
                <p class="mt-1 text-sm text-gray-600">Vue d'ensemble des performances et tendances</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <select x-model="selectedPeriod" @change="updateCharts()" 
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="7">7 derniers jours</option>
                    <option value="30">30 derniers jours</option>
                    <option value="90">90 derniers jours</option>
                    <option value="365">1 an</option>
                </select>
                <button @click="generateReport()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-file-export mr-2"></i>
                    Générer Rapport
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total des demandes -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Demandes</p>
                    <p class="text-3xl font-bold">{{ number_format($globalStats['requests']['total']) }}</p>
                    <p class="text-blue-100 text-sm mt-1">
                        +{{ $globalStats['requests']['this_month'] }} ce mois
                    </p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux de traitement -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Taux de Traitement</p>
                    @php
                        $processingRate = $globalStats['requests']['total'] > 0 ?
                            round(($globalStats['requests']['completed'] / $globalStats['requests']['total']) * 100, 1) : 0;
                    @endphp
                    <p class="text-3xl font-bold">{{ $processingRate }}%</p>
                    <p class="text-green-100 text-sm mt-1">
                        {{ $globalStats['requests']['completed'] }} complétées
                    </p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Temps moyen -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Temps Moyen</p>
                    <p class="text-3xl font-bold">{{ $globalStats['processing']['average_time'] ?? 'N/A' }}</p>
                    <p class="text-orange-100 text-sm mt-1">de traitement</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Documents populaires -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Document Populaire</p>
                    <p class="text-lg font-bold">{{ $globalStats['documents']['most_requested']['name'] ?? 'N/A' }}</p>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $globalStats['documents']['most_requested']['count'] ?? 0 }} demandes
                    </p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes statistiques personnelles -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Mes Statistiques</h3>
            <div class="flex items-center space-x-2">
                <div x-show="isUpdating" class="flex items-center text-blue-600">
                    <svg class="animate-spin h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs">Mise à jour...</span>
                </div>
                <div x-show="!isUpdating" class="text-xs text-gray-500">
                    Dernière maj: <span x-text="lastUpdate"></span>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg transition-all duration-300" x-bind:class="{'ring-2 ring-blue-300': isUpdating}">
                <p class="text-2xl font-bold text-blue-600" x-text="myStats.processed_today">{{ $myStats['processed_today'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Traitées aujourd'hui</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg transition-all duration-300" x-bind:class="{'ring-2 ring-green-300': isUpdating}">
                <p class="text-2xl font-bold text-green-600" x-text="myStats.processed_week">{{ $myStats['processed_this_week'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Cette semaine</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg transition-all duration-300" x-bind:class="{'ring-2 ring-orange-300': isUpdating}">
                <p class="text-2xl font-bold text-orange-600" x-text="myStats.avg_time">'{{ $myStats['avg_processing_time'] ?? 'N/A' }}'</p>
                <p class="text-sm text-gray-600">Temps moyen</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg transition-all duration-300" x-bind:class="{'ring-2 ring-purple-300': isUpdating}">
                <p class="text-2xl font-bold text-purple-600" x-text="myStats.success_rate + '%'">{{ $myStats['success_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600">Taux de succès</p>
            </div>
        </div>
        
        <!-- Statistiques détaillées -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Assignées à moi</span>
                    <span class="text-lg font-bold text-indigo-600" x-text="myStats.assigned">{{ $myStats['total_assigned'] ?? 0 }}</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total traitées</span>
                    <span class="text-lg font-bold text-green-600" x-text="myStats.processed_total">{{ $myStats['total_processed'] ?? 0 }}</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Performance</span>
                    <span class="text-lg font-bold text-yellow-600" x-text="myStats.performance + '/100'">{{ $myStats['performance_rating'] ?? 0 }}/100</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes performances des 7 derniers jours -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Mes Performances des 7 Derniers Jours</h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ $myStats['processed_this_week'] ?? 0 }}</span> demandes traitées cette semaine
            </div>
        </div>
        
        @if(isset($weeklyPerformance) && count($weeklyPerformance) > 0)
            <div class="grid grid-cols-7 gap-2 mb-4">
                @foreach($weeklyPerformance as $day)
                <div class="text-center">
                    <div class="text-xs text-gray-500 mb-1">{{ $day['day'] }}</div>
                    <div class="bg-blue-100 rounded-lg p-3 relative">
                        <div class="text-lg font-bold text-blue-600">{{ $day['processed'] }}</div>
                        @if($day['processed'] > 0)
                            <div class="absolute inset-x-0 bottom-0 bg-blue-500 h-1 rounded-b-lg" 
                                 style="height: {{ min(($day['processed'] / max(array_column($weeklyPerformance, 'processed'), 1)) * 100, 100) }}%"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p>Aucune donnée de performance disponible pour cette semaine</p>
            </div>
        @endif
        
        <div style="height: 250px;">
            <canvas id="weeklyPerformanceChart"></canvas>
        </div>
    </div>

    <!-- Répartition par type de document -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Répartition par Type de Document</h3>
            <div class="text-sm text-gray-600">
                Documents les plus demandés
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Graphique en barres -->
            <div style="height: 300px;">
                <canvas id="documentsChart"></canvas>
            </div>
            <!-- Liste détaillée -->
            <div class="space-y-3">
                @if(isset($globalStats['documents']['by_type']) && count($globalStats['documents']['by_type']) > 0)
                    @foreach($globalStats['documents']['by_type'] as $index => $document)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $document['name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $document['count'] }} demandes</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @php
                                $percentage = $globalStats['requests']['total'] > 0 ? round(($document['count'] / $globalStats['requests']['total']) * 100, 1) : 0;
                            @endphp
                            <span class="text-lg font-bold text-blue-600">{{ $percentage }}%</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Aucune donnée de document disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution des demandes -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Demandes</h3>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span>Demandes reçues</span>
                    <span class="w-3 h-3 bg-green-500 rounded-full ml-4"></span>
                    <span>Traitées</span>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="requestsChart"></canvas>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par Statut</h3>
            <div style="height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('statisticsPage', () => ({
        selectedPeriod: '30',
        showReportModal: false,
        isUpdating: false,
        lastUpdate: '',
        myStats: {
            processed_today: {{ $myStats['processed_today'] ?? 0 }},
            processed_week: {{ $myStats['processed_this_week'] ?? 0 }},
            avg_time: '{{ $myStats['avg_processing_time'] ?? 'N/A' }}',
            success_rate: {{ $myStats['success_rate'] ?? 0 }},
            assigned: {{ $myStats['total_assigned'] ?? 0 }},
            processed_total: {{ $myStats['total_processed'] ?? 0 }},
            performance: {{ $myStats['performance_rating'] ?? 0 }}
        },
        charts: {},
        updateInterval: null,

        init() {
            this.initCharts();
            this.loadChartData();
            this.updateLastUpdateTime();
            this.startAutoUpdate();
        },

        startAutoUpdate() {
            // Mise à jour automatique toutes les 30 secondes
            this.updateInterval = setInterval(() => {
                this.updateRealTimeStats();
            }, 30000);
        },

        async updateRealTimeStats() {
            if (this.isUpdating) return;
            
            this.isUpdating = true;
            
            try {
                const response = await fetch('{{ route("agent.statistics.real-time") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Mettre à jour les statistiques personnelles
                    this.myStats.processed_today = data.processed_today || 0;
                    this.myStats.processed_week = data.processed_this_week || 0;
                    this.myStats.avg_time = data.avg_processing_time || 'N/A';
                    this.myStats.success_rate = data.success_rate || 0;
                    this.myStats.assigned = data.total_assigned || 0;
                    this.myStats.processed_total = data.total_processed || 0;
                    this.myStats.performance = data.performance_rating || 0;
                    
                    this.updateLastUpdateTime();
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour des statistiques:', error);
            } finally {
                this.isUpdating = false;
            }
        },

        updateLastUpdateTime() {
            const now = new Date();
            this.lastUpdate = now.toLocaleTimeString('fr-FR', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
        },

        destroy() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
            }
        },

        initCharts() {
            // Graphique des performances hebdomadaires
            const weeklyCtx = document.getElementById('weeklyPerformanceChart');
            if (weeklyCtx) {
                this.charts.weekly = new Chart(weeklyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($weeklyPerformance ? array_column($weeklyPerformance, 'day') : []),
                        datasets: [{
                            label: 'Demandes traitées par jour',
                            data: @json($weeklyPerformance ? array_column($weeklyPerformance, 'processed') : []),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
            }

            // Graphique d'évolution des demandes
            const requestsCtx = document.getElementById('requestsChart');
            if (requestsCtx) {
                this.charts.requests = new Chart(requestsCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Demandes reçues',
                            data: [],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }, {
                            label: 'Traitées',
                            data: [],
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4
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
            }

            // Graphique de répartition par statut
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                this.charts.status = new Chart(statusCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['En attente', 'En traitement', 'Complétées', 'Rejetées'],
                        datasets: [{
                            data: [
                                {{ $globalStats['requests']['pending'] ?? 0 }},
                                {{ $globalStats['requests']['processing'] ?? 0 }},
                                {{ $globalStats['requests']['completed'] ?? 0 }},
                                {{ $globalStats['requests']['rejected'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgb(251, 191, 36)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Graphique des types de documents
            const documentsCtx = document.getElementById('documentsChart');
            if (documentsCtx) {
                const documentsData = @json($globalStats['documents']['by_type'] ?? []);
                this.charts.documents = new Chart(documentsCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: documentsData.map(doc => doc.name),
                        datasets: [{
                            label: 'Nombre de demandes',
                            data: documentsData.map(doc => doc.count),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(147, 51, 234, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(251, 191, 36)',
                                'rgb(239, 68, 68)',
                                'rgb(147, 51, 234)'
                            ],
                            borderWidth: 2
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
            }
        },

        async loadChartData() {
            try {
                const response = await fetch(`{{ route('agent.statistics.chart-data') }}?type=requests&period=${this.selectedPeriod}`);
                if (response.ok) {
                    const data = await response.json();
                    this.updateChartsWithData(data);
                }
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
            }
        },

        updateChartsWithData(data) {
            // Mettre à jour le graphique d'évolution
            if (this.charts.requests && data) {
                const labels = data.map(item => new Date(item.date).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' }));
                const counts = data.map(item => item.count);
                
                this.charts.requests.data.labels = labels;
                this.charts.requests.data.datasets[0].data = counts;
                this.charts.requests.update();
            }
        },

        updateCharts() {
            this.loadChartData();
        },

        generateReport() {
            alert('Fonctionnalité de génération de rapport à implémenter');
        }
    }));
});
</script>
@endsection
