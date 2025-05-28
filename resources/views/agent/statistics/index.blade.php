@extends('layouts.agent')

@section('title', 'Statistiques et Rapports')

@section('content')
<div class="space-y-6" x-data="statisticsPage">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Statistiques et Rapports</h1>
                <p class="text-gray-600 mt-1">Analyse des performances et générations de rapports</p>
            </div>
            <div class="flex items-center space-x-3">
                <select x-model="selectedPeriod" @change="updateCharts()"
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="7">7 derniers jours</option>
                    <option value="30">30 derniers jours</option>
                    <option value="90">3 derniers mois</option>
                    <option value="365">12 derniers mois</option>
                </select>
                <button @click="generateReport()"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
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
                        <path d="M9,12H15V14H9V12M9,8H15V10H9V8M9,16H15V18H9V16M7,22H17A2,2 0 0,0 19,20V4A2,2 0 0,0 17,2H7A2,2 0 0,0 5,4V20A2,2 0 0,0 7,22Z"></path>
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

        <!-- Performance par agent -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance par Agent</h3>
            <div style="height: 300px;">
                <canvas id="agentPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Types de documents -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Types de Documents Demandés</h3>
            <div style="height: 300px;">
                <canvas id="documentTypesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableaux de détails -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top agents -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Agents</h3>
            <div class="overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Traitées</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taux</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($globalStats['agents']['top_performers'] ?? [] as $agent)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium text-blue-600">
                                            {{ substr($agent['name'], 0, 2) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $agent['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $agent['processed'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $agent['success_rate'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents récents -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
            <div class="space-y-3">
                @foreach($globalStats['recent_activity'] ?? [] as $activity)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        @php
                            $statusColors = [
                                'completed' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'processing' => 'bg-blue-100 text-blue-800'
                            ];
                        @endphp
                        <span class="w-2 h-2 rounded-full {{ $statusColors[$activity['status']] ?? 'bg-gray-300' }}"></span>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $activity['document_name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['user_name'] }} • {{ $activity['time'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $activity['status'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mes statistiques personnelles -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mes Statistiques</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">{{ $myStats['processed_today'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Traitées aujourd'hui</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">{{ $myStats['processed_this_week'] ?? 0 }}</p>
                <p class="text-sm text-gray-600">Cette semaine</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <p class="text-2xl font-bold text-orange-600">{{ $myStats['avg_processing_time'] ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">Temps moyen</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-2xl font-bold text-purple-600">{{ $myStats['success_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600">Taux de succès</p>
            </div>
        </div>
    </div>

    <!-- Modal de génération de rapport -->
    <div x-show="showReportModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
         @click="showReportModal = false">

        <div @click.stop class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Générer un Rapport</h3>

            <form @submit.prevent="submitReport()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de rapport</label>
                        <select x-model="reportType" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="global">Rapport global</option>
                            <option value="agent">Performance par agent</option>
                            <option value="document">Analyse par document</option>
                            <option value="monthly">Rapport mensuel</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input x-model="reportStartDate" type="date" required
                                   class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <input x-model="reportEndDate" type="date" required
                                   class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <select x-model="reportFormat" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            @click="showReportModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            :disabled="isGenerating">
                        <span x-show="!isGenerating">Générer</span>
                        <span x-show="isGenerating" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Génération...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('statisticsPage', () => ({
        selectedPeriod: '30',
        showReportModal: false,
        reportType: '',
        reportStartDate: '',
        reportEndDate: '',
        reportFormat: 'pdf',
        isGenerating: false,
        charts: {},

        init() {
            this.initCharts();
            this.loadChartData();
        },

        initCharts() {
            // Graphique d'évolution des demandes
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            this.charts.requests = new Chart(requestsCtx, {
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

            // Graphique de répartition par statut
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            this.charts.status = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'En traitement', 'Complétées', 'Rejetées'],
                    datasets: [{
                        data: [
                            {{ $globalStats['requests']['pending'] }},
                            {{ $globalStats['requests']['processing'] }},
                            {{ $globalStats['requests']['completed'] }},
                            {{ $globalStats['requests']['rejected'] }}
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

            // Autres graphiques...
            this.initAgentPerformanceChart();
            this.initDocumentTypesChart();
        },

        initAgentPerformanceChart() {
            const agentCtx = document.getElementById('agentPerformanceChart').getContext('2d');
            this.charts.agent = new Chart(agentCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(collect($globalStats['agents']['top_performers'] ?? [])->pluck('name')) !!},
                    datasets: [
                        {
                            label: 'Demandes traitées',
                            data: {!! json_encode(collect($globalStats['agents']['top_performers'] ?? [])->pluck('processed')) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            yAxisID: 'y',
                        },
                        {
                            label: 'Taux de succès (%)',
                            data: {!! json_encode(collect($globalStats['agents']['top_performers'] ?? [])->pluck('success_rate')) !!},
                            type: 'line',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            fill: false,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Demandes traitées' }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Taux de succès (%)' },
                            min: 0,
                            max: 100
                        }
                    }
                }
            });
        },

        initDocumentTypesChart() {
            const docCtx = document.getElementById('documentTypesChart').getContext('2d');
            this.charts.documents = new Chart(docCtx, {
                type: 'polarArea',
                data: {
                    labels: {!! json_encode(collect($globalStats['documents']['by_type'] ?? [])->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode(collect($globalStats['documents']['by_type'] ?? [])->pluck('count')) !!},
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(147, 51, 234, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        },

        async loadChartData() {
            try {
                const response = await fetch(`{{ route('agent.statistics.chart-data') }}?period=${this.selectedPeriod}`);
                const data = await response.json();
                this.updateChartsWithData(data);
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
            }
        },

        updateChartsWithData(data) {
            // Mettre à jour le graphique d'évolution
            if (this.charts.requests && data.evolution) {
                this.charts.requests.data.labels = data.evolution.labels;
                this.charts.requests.data.datasets[0].data = data.evolution.received;
                this.charts.requests.data.datasets[1].data = data.evolution.processed;
                this.charts.requests.update();
            }
        },

        updateCharts() {
            this.loadChartData();
        },

        generateReport() {
            this.showReportModal = true;
            // Définir les dates par défaut
            const today = new Date();
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            this.reportEndDate = today.toISOString().split('T')[0];
            this.reportStartDate = lastMonth.toISOString().split('T')[0];
        },

        async submitReport() {
            this.isGenerating = true;

            try {
                const formData = new FormData();
                formData.append('type', this.reportType);
                formData.append('start_date', this.reportStartDate);
                formData.append('end_date', this.reportEndDate);
                formData.append('format', this.reportFormat);

                const response = await fetch('{{ route("agent.statistics.generate-report") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `rapport-${this.reportType}-${new Date().toISOString().split('T')[0]}.${this.reportFormat}`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);

                    this.showReportModal = false;
                } else {
                    alert('Erreur lors de la génération du rapport');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la génération du rapport');
            } finally {
                this.isGenerating = false;
            }
        }
    }))
})
</script>
@endsection
