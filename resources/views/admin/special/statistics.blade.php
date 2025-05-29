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

        <!-- Performance Analytics Section -->
        <div class="space-y-6">
            <h4 class="text-xl font-bold text-gray-800 mb-6">📊 Analyse de Performance Complète</h4>
            
            <!-- Performance Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Efficacité Globale</p>
                            <p class="text-2xl font-bold">87.3%</p>
                        </div>
                        <div class="text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-blue-100 text-xs">+5.2% vs semaine précédente</div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Temps Moyen</p>
                            <p class="text-2xl font-bold">3.4min</p>
                        </div>
                        <div class="text-green-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15,1H9V3H15M11,14H13V8H11M19,3H21V5H19V19H21V21H19V20H5V21H3V19H5V5H3V3H5V4H19M19,5H5V19H19V5Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-green-100 text-xs">-12% amélioration</div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Satisfaction Client</p>
                            <p class="text-2xl font-bold">94.2%</p>
                        </div>
                        <div class="text-purple-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-purple-100 text-xs">Excellent niveau</div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm">Charge de Travail</p>
                            <p class="text-2xl font-bold">84.6%</p>
                        </div>
                        <div class="text-orange-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19,3H5C3.9,3 3,3.9 3,5V19C3,20.1 3.9,21 5,21H19C20.1,21 21,20.1 21,19V5C21,3.9 20.1,3 19,3M19,19H5V5H19V19Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-orange-100 text-xs">Capacité optimale</div>
                </div>
            </div>

            <!-- Performance Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Efficiency Trends with Real-time Updates -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">📈 Tendance d'Efficacité</h5>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded hover:bg-blue-200 transition-colors" onclick="toggleEfficiencyView('weekly')">Hebdomadaire</button>
                            <button class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded hover:bg-gray-200 transition-colors" onclick="toggleEfficiencyView('hourly')">Aujourd'hui</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="efficiencyTrendChart"></canvas>
                    </div>
                    <div class="mt-3 grid grid-cols-3 gap-2 text-xs">
                        <div class="text-center p-2 bg-blue-50 rounded">
                            <div class="font-semibold text-blue-800">Moyenne</div>
                            <div class="text-blue-600">87.3%</div>
                        </div>
                        <div class="text-center p-2 bg-green-50 rounded">
                            <div class="font-semibold text-green-800">Meilleur</div>
                            <div class="text-green-600">95%</div>
                        </div>
                        <div class="text-center p-2 bg-orange-50 rounded">
                            <div class="font-semibold text-orange-800">Écart Cible</div>
                            <div class="text-orange-600">-2.7%</div>
                        </div>
                    </div>
                </div>

                <!-- Response Time Analysis with SLA Compliance -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">⏱️ Temps de Réponse & SLA</h5>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-gray-500">Live</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="responseTimeChart"></canvas>
                    </div>
                    <div class="mt-3 flex justify-between text-xs">
                        <span class="text-gray-600">SLA Objectif: 3min</span>
                        <span class="text-green-600 font-medium">Conformité: 79%</span>
                    </div>
                </div>

                <!-- Satisfaction by Document Type with Complexity -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">🎯 Performance par Type de Document</h5>
                        <select class="px-2 py-1 border border-gray-300 rounded text-xs" onchange="updateSatisfactionView(this.value)">
                            <option value="satisfaction">Satisfaction</option>
                            <option value="complexity">Complexité</option>
                            <option value="combined">Vue Combinée</option>
                        </select>
                    </div>
                    <div class="h-64">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <div class="font-medium text-gray-700">Top Performer</div>
                            <div class="text-green-600">Acte Naissance (95%)</div>
                        </div>
                        <div>
                            <div class="font-medium text-gray-700">Besoin d'Attention</div>
                            <div class="text-orange-600">Cert. Résidence (85%)</div>
                        </div>
                    </div>
                </div>

                <!-- Workload Distribution with Specializations -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">⚖️ Répartition de la Charge</h5>
                        <button class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200 transition-colors" onclick="showAgentDetails()">
                            Détails Agents
                        </button>
                    </div>
                    <div class="h-64">
                        <canvas id="workloadChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="text-xs text-gray-600 mb-2">Alertes de Charge:</div>
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Bernard: Surcharge (95%)</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Dubois: Haute (92%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Predictive Analytics & Advanced Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- Forecast Widget -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold">🔮 Prévision Prochaine Heure</h5>
                        <div class="text-indigo-200 text-xs">85% confiance</div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-indigo-100">Demandes attendues:</span>
                            <span class="font-bold text-xl">45</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-indigo-100">Agents recommandés:</span>
                            <span class="font-bold">3</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-100">Risque de goulot:</span>
                            <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded">Moyen</span>
                        </div>
                    </div>
                </div>

                <!-- Anomaly Detection -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">🚨 Détection d'Anomalies</h5>
                    <div class="space-y-3">
                        <div class="flex items-center p-2 bg-yellow-50 rounded">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                            <div class="text-sm">
                                <div class="font-medium text-yellow-800">Pic de demandes</div>
                                <div class="text-yellow-600 text-xs">Mercredi +45%</div>
                            </div>
                        </div>
                        <div class="flex items-center p-2 bg-red-50 rounded">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                            <div class="text-sm">
                                <div class="font-medium text-red-800">Temps de réponse élevé</div>
                                <div class="text-red-600 text-xs">Jeudi 8.2min</div>
                            </div>
                        </div>
                        <div class="flex items-center p-2 bg-blue-50 rounded">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <div class="text-sm">
                                <div class="font-medium text-blue-800">Agent absent</div>
                                <div class="text-blue-600 text-xs">Vendredi 1 agent</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparative Analysis -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">📊 Analyse Comparative</h5>
                    <div class="h-48">
                        <canvas id="comparativeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Advanced Performance Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Trends -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">📅 Évolution Mensuelle</h5>
                    <div class="h-80">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Quality Radar -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">🎯 Radar de Qualité</h5>
                    <div class="h-80">
                        <canvas id="qualityRadarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Performance Insights -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">💡 Insights de Performance</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-green-800 font-medium text-sm">Points Forts</span>
                        </div>
                        <ul class="text-green-700 text-sm space-y-1">
                            <li>• Excellent taux de satisfaction (94.2%)</li>
                            <li>• Amélioration continue des temps</li>
                            <li>• Performance agents très stable</li>
                        </ul>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-yellow-800 font-medium text-sm">À Surveiller</span>
                        </div>
                        <ul class="text-yellow-700 text-sm space-y-1">
                            <li>• Charge élevée aux heures de pointe</li>
                            <li>• Variabilité dans les cert. résidence</li>
                            <li>• Formation continue nécessaire</li>
                        </ul>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-blue-800 font-medium text-sm">Recommandations</span>
                        </div>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li>• Optimiser les créneaux 14h-16h</li>
                            <li>• Standardiser les procédures</li>
                            <li>• Équilibrer la charge agents</li>
                        </ul>
                    </div>
                </div>
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
    // Configuration commune pour tous les graphiques
    Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
    Chart.defaults.color = '#6B7280';

    // 1. Document Stats Chart (amélioré)
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
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed * 100) / total).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Efficiency Trend Chart
    const efficiencyTrendCtx = document.getElementById('efficiencyTrendChart');
    if (efficiencyTrendCtx) {
        new Chart(efficiencyTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['performance_metrics']['efficiency']['labels'] ?? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']) !!},
                datasets: [{
                    label: 'Semaine Actuelle',
                    data: {!! json_encode($chartData['performance_metrics']['efficiency']['current_week'] ?? [85, 92, 78, 95, 88, 75, 82]) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }, {
                    label: 'Semaine Précédente',
                    data: {!! json_encode($chartData['performance_metrics']['efficiency']['previous_week'] ?? [78, 85, 82, 89, 91, 70, 85]) !!},
                    borderColor: 'rgb(156, 163, 175)',
                    backgroundColor: 'rgba(156, 163, 175, 0.1)',
                    tension: 0.4,
                    fill: false,
                    borderDash: [5, 5],
                    pointBackgroundColor: 'rgb(156, 163, 175)'
                }, {
                    label: 'Objectif',
                    data: {!! json_encode($chartData['performance_metrics']['efficiency']['target'] ?? [90, 90, 90, 90, 90, 90, 90]) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'transparent',
                    borderDash: [10, 5],
                    pointRadius: 0,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 70,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Response Time Chart
    const responseTimeCtx = document.getElementById('responseTimeChart');
    if (responseTimeCtx) {
        new Chart(responseTimeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['performance_metrics']['response_time']['labels'] ?? ['08h', '10h', '12h', '14h', '16h', '18h', '20h']) !!},
                datasets: [{
                    label: 'Temps Moyen',
                    data: {!! json_encode($chartData['performance_metrics']['response_time']['average_time'] ?? [2.5, 3.2, 4.1, 5.8, 4.3, 3.7, 2.1]) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }, {
                    label: 'Temps de Pointe',
                    data: {!! json_encode($chartData['performance_metrics']['response_time']['peak_time'] ?? [3.1, 4.5, 6.2, 8.3, 6.1, 5.2, 2.8]) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }, {
                    label: 'Objectif',
                    data: {!! json_encode($chartData['performance_metrics']['response_time']['target_time'] ?? [3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0]) !!},
                    type: 'line',
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    pointRadius: 0,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + 'min';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + 'min';
                            }
                        }
                    }
                }
            }
        });
    }

    // 4. Satisfaction Chart
    const satisfactionCtx = document.getElementById('satisfactionChart');
    if (satisfactionCtx) {
        new Chart(satisfactionCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['performance_metrics']['satisfaction']['labels'] ?? ['Acte Naissance', 'Acte Mariage', 'Cert. Nationalité', 'Décl. Naissance', 'Cert. Résidence']) !!},
                datasets: [{
                    label: 'Taux de Satisfaction (%)',
                    data: {!! json_encode($chartData['performance_metrics']['satisfaction']['satisfaction_rate'] ?? [95, 87, 92, 89, 85]) !!},
                    backgroundColor: 'rgba(168, 85, 247, 0.8)',
                    borderColor: 'rgb(168, 85, 247)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Taux de Réussite (%)',
                    data: {!! json_encode($chartData['performance_metrics']['satisfaction']['completion_rate'] ?? [98, 94, 96, 91, 88]) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Taux d\'Erreur (%)',
                    data: {!! json_encode($chartData['performance_metrics']['satisfaction']['error_rate'] ?? [2, 6, 4, 9, 12]) !!},
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
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        min: 80,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: 0,
                        max: 15,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 5. Workload Chart
    const workloadCtx = document.getElementById('workloadChart');
    if (workloadCtx) {
        new Chart(workloadCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['performance_metrics']['workload_distribution']['agents'] ?? ['Martin', 'Dubois', 'Leroy', 'Bernard', 'Moreau']) !!},
                datasets: [{
                    label: 'Charge Actuelle (%)',
                    data: {!! json_encode($chartData['performance_metrics']['workload_distribution']['current_load'] ?? [85, 92, 78, 95, 73]) !!},
                    backgroundColor: function(context) {
                        const value = context.parsed ? context.parsed.y : context.raw;
                        if (value > 90) return 'rgba(239, 68, 68, 0.8)';
                        if (value > 75) return 'rgba(245, 158, 11, 0.8)';
                        return 'rgba(34, 197, 94, 0.8)';
                    },
                    borderWidth: 1
                }, {
                    label: 'Score d\'Efficacité',
                    data: {!! json_encode($chartData['performance_metrics']['workload_distribution']['efficiency_score'] ?? [94, 88, 92, 87, 89]) !!},
                    type: 'line',
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        min: 80,
                        max: 100,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 6. Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart');
    if (monthlyTrendsCtx) {
        new Chart(monthlyTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['performance_trends']['monthly']['labels'] ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun']) !!},
                datasets: [{
                    label: 'Demandes Traitées',
                    data: {!! json_encode($chartData['performance_trends']['monthly']['requests_handled'] ?? [1250, 1380, 1156, 1420, 1650, 1789]) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Taux de Succès (%)',
                    data: {!! json_encode($chartData['performance_trends']['monthly']['success_rate'] ?? [92, 94, 89, 96, 93, 97]) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y1'
                }, {
                    label: 'Temps Moyen (min)',
                    data: {!! json_encode($chartData['performance_trends']['monthly']['avg_processing_time'] ?? [4.2, 3.8, 4.5, 3.9, 3.6, 3.4]) !!},
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y2'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Demandes'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Taux (%)'
                        }
                    },
                    y2: {
                        type: 'linear',
                        display: false,
                        min: 0,
                        max: 6
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 7. Quality Radar Chart
    const qualityRadarCtx = document.getElementById('qualityRadarChart');
    if (qualityRadarCtx) {
        new Chart(qualityRadarCtx, {
            type: 'radar',
            data: {
                labels: {!! json_encode($chartData['performance_trends']['quality_metrics']['labels'] ?? ['Précision', 'Rapidité', 'Satisfaction', 'Conformité', 'Innovation']) !!},
                datasets: [{
                    label: 'Scores Actuels',
                    data: {!! json_encode($chartData['performance_trends']['quality_metrics']['current_scores'] ?? [92, 87, 94, 96, 78]) !!},
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    pointBackgroundColor: 'rgb(99, 102, 241)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(99, 102, 241)',
                    borderWidth: 2
                }, {
                    label: 'Benchmarks',
                    data: {!! json_encode($chartData['performance_trends']['quality_metrics']['benchmarks'] ?? [90, 85, 90, 95, 80]) !!},
                    borderColor: 'rgb(156, 163, 175)',
                    backgroundColor: 'rgba(156, 163, 175, 0.1)',
                    pointBackgroundColor: 'rgb(156, 163, 175)',
                    pointBorderColor: '#fff',
                    borderDash: [5, 5],
                    borderWidth: 1
                }, {
                    label: 'Objectifs',
                    data: {!! json_encode($chartData['performance_trends']['quality_metrics']['targets'] ?? [95, 90, 95, 98, 85]) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    borderDash: [10, 5],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        pointLabels: {
                            font: {
                                size: 12
                            }
                        },
                        ticks: {
                            stepSize: 20,
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // 8. Comparative Analysis Chart
    const comparativeCtx = document.getElementById('comparativeChart');
    if (comparativeCtx) {
        new Chart(comparativeCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['performance_trends']['comparative_analysis']['labels'] ?? ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024', 'Q1 2025']) !!},
                datasets: [{
                    label: 'Index Performance',
                    data: {!! json_encode($chartData['performance_trends']['comparative_analysis']['performance_index'] ?? [78, 82, 85, 91, 94]) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Satisfaction Client',
                    data: {!! json_encode($chartData['performance_trends']['comparative_analysis']['customer_satisfaction'] ?? [87, 89, 91, 93, 94]) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 70,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 10,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }

    // Processing Time Chart (existant mais amélioré)
    const processingTimeCtx = document.getElementById('processingTimeChart');
    if (processingTimeCtx) {
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
    }

    // Agent Performance Chart (existant mais amélioré)
    const agentPerformanceCtx = document.getElementById('agentPerformanceChart');
    if (agentPerformanceCtx) {
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
    }

    // Variables globales pour les vues interactives
    let currentEfficiencyView = 'weekly';
    let currentSatisfactionView = 'satisfaction';
    let efficiencyChart, satisfactionChart;

    // Fonction pour basculer la vue d'efficacité
    window.toggleEfficiencyView = function(view) {
        currentEfficiencyView = view;
        updateEfficiencyChart();
        
        // Mise à jour des boutons
        document.querySelectorAll('button[onclick^="toggleEfficiencyView"]').forEach(btn => {
            btn.classList.remove('bg-blue-100', 'text-blue-800');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        event.target.classList.remove('bg-gray-100', 'text-gray-600');
        event.target.classList.add('bg-blue-100', 'text-blue-800');
    };

    // Fonction pour mettre à jour le graphique d'efficacité
    function updateEfficiencyChart() {
        const ctx = document.getElementById('efficiencyTrendChart');
        if (!ctx) return;

        if (efficiencyChart) {
            efficiencyChart.destroy();
        }

        if (currentEfficiencyView === 'weekly') {
            // Vue hebdomadaire (existante)
            const data = {!! json_encode($chartData['performance_metrics']['efficiency']) !!};
            efficiencyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Semaine Actuelle',
                        data: data.current_week,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Semaine Précédente',
                        data: data.previous_week,
                        borderColor: 'rgb(156, 163, 175)',
                        backgroundColor: 'rgba(156, 163, 175, 0.1)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 100,
                            ticks: {
                                callback: function(value) { return value + '%'; }
                            }
                        }
                    }
                }
            });
        } else {
            // Vue quotidienne (aujourd'hui par heure)
            const data = {!! json_encode($chartData['performance_metrics']['efficiency']['hourly_today']) !!};
            efficiencyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Efficacité (%)',
                        data: data.efficiency,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }, {
                        label: 'Volume',
                        data: data.volume,
                        type: 'line',
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            min: 70,
                            max: 100,
                            ticks: {
                                callback: function(value) { return value + '%'; }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }
    }

    // Fonction pour mettre à jour la vue de satisfaction
    window.updateSatisfactionView = function(view) {
        currentSatisfactionView = view;
        updateSatisfactionChart();
    };

    function updateSatisfactionChart() {
        const ctx = document.getElementById('satisfactionChart');
        if (!ctx) return;

        if (satisfactionChart) {
            satisfactionChart.destroy();
        }

        const data = {!! json_encode($chartData['performance_metrics']['satisfaction']) !!};

        if (currentSatisfactionView === 'satisfaction') {
            satisfactionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Taux de Satisfaction (%)',
                        data: data.satisfaction_rate,
                        backgroundColor: 'rgba(168, 85, 247, 0.8)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 80,
                            max: 100,
                            ticks: {
                                callback: function(value) { return value + '%'; }
                            }
                        }
                    }
                }
            });
        } else if (currentSatisfactionView === 'complexity') {
            satisfactionChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Complexité (1-5)',
                        data: data.complexity_score,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        pointBackgroundColor: 'rgb(239, 68, 68)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 5,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        } else {
            // Vue combinée
            satisfactionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Satisfaction (%)',
                        data: data.satisfaction_rate,
                        backgroundColor: 'rgba(168, 85, 247, 0.8)',
                        yAxisID: 'y'
                    }, {
                        label: 'Réussite (%)',
                        data: data.completion_rate,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        yAxisID: 'y'
                    }, {
                        label: 'Erreur (%)',
                        data: data.error_rate,
                        type: 'line',
                        borderColor: 'rgb(239, 68, 68)',
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { min: 80, max: 100, ticks: { callback: value => value + '%' } },
                        y1: { type: 'linear', position: 'right', min: 0, max: 15, grid: { drawOnChartArea: false } }
                    }
                }
            });
        }
    }

    // Fonction pour afficher les détails des agents
    window.showAgentDetails = function() {
        const agentData = {!! json_encode($chartData['performance_metrics']['workload_distribution']) !!};
        let details = 'Détails des Agents:\n\n';
        
        agentData.agents.forEach((agent, index) => {
            details += `${agent}:\n`;
            details += `  - Charge: ${agentData.current_load[index]}%\n`;
            details += `  - Efficacité: ${agentData.efficiency_score[index]}\n`;
            details += `  - Spécialisations: ${agentData.specializations[index].join(', ')}\n\n`;
        });

        alert(details);
    };

    // Initialisation des vues
    setTimeout(() => {
        updateEfficiencyChart();
        updateSatisfactionChart();
    }, 100);

    // Simulation de mise à jour en temps réel (toutes les 30 secondes)
    setInterval(() => {
        // Simulation de nouvelles données
        const elements = document.querySelectorAll('.animate-pulse');
        elements.forEach(el => {
            el.style.opacity = '0.5';
            setTimeout(() => { el.style.opacity = '1'; }, 200);
        });
    }, 30000);
});
</script>
@endpush
@endsection
