@extends('layouts.agent')

@section('title', 'Détails du citoyen')

@section('header')
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('agent.citizens.index') }}" class="text-white hover:text-blue-200 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $citizen->nom }} {{ $citizen->prenoms }}</h1>
                <p class="text-blue-100 mt-1">Détails du citoyen et historique des demandes</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <button onclick="exportCitizenData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-download mr-2"></i>Exporter
            </button>
            <button onclick="contactCitizen()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i>Contacter
            </button>
        </div>
    </div>
@endsection

@section('content')
<div x-data="citizenDetailApp()" x-init="init()" class="space-y-8">
    <!-- Informations personnelles -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                    {{ substr($citizen->nom, 0, 1) }}{{ substr($citizen->prenoms, 0, 1) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold">{{ $citizen->nom }} {{ $citizen->prenoms }}</h2>
                    <p class="text-blue-100">Citoyen depuis le {{ $citizen->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de contact</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $citizen->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $citizen->telephone ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $citizen->adresse ?? 'Non renseignée' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_requests'] }}</div>
                            <div class="text-sm text-blue-600">Total demandes</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_requests'] }}</div>
                            <div class="text-sm text-yellow-600">En attente</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['completed_requests'] }}</div>
                            <div class="text-sm text-green-600">Terminées</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $stats['rejected_requests'] }}</div>
                            <div class="text-sm text-red-600">Rejetées</div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité récente</h3>
                    <div class="space-y-3">
                        @if($citizen->requests->count() > 0)
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 w-5"></i>
                                <span class="ml-3 text-gray-900">Dernière demande : {{ $citizen->requests->first()->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-gray-400 w-5"></i>
                                <span class="ml-3 text-gray-900">{{ $citizen->requests->first()->document ? $citizen->requests->first()->document->title : 'N/A' }}</span>
                            </div>
                        @else
                            <p class="text-gray-500">Aucune activité récente</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique d'activité -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Activité des demandes</h3>
            <select x-model="chartPeriod" @change="updateChart()" class="text-sm border rounded-lg px-3 py-1">
                <option value="6">6 derniers mois</option>
                <option value="12">12 derniers mois</option>
            </select>
        </div>
        <canvas id="activityChart" width="400" height="200"></canvas>
    </div>

    <!-- Historique des demandes -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Historique des demandes</h3>
                <div class="flex items-center space-x-4">
                    <select x-model="statusFilter" @change="filterRequests()" class="text-sm border rounded-lg px-3 py-1">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="processing">En cours</option>
                        <option value="completed">Terminé</option>
                        <option value="rejected">Rejeté</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($citizen->requests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->reference_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    <div class="text-sm text-gray-900">{{ $request->document ? $request->document->title : 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                    @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <span class="w-2 h-2 rounded-full mr-1
                                        @if($request->status === 'pending') bg-yellow-400
                                        @elseif($request->status === 'processing') bg-blue-400
                                        @elseif($request->status === 'completed') bg-green-400
                                        @elseif($request->status === 'rejected') bg-red-400
                                        @else bg-gray-400 @endif"></span>
                                    @if($request->status === 'pending') En attente
                                    @elseif($request->status === 'processing') En cours
                                    @elseif($request->status === 'completed') Terminé
                                    @elseif($request->status === 'rejected') Rejeté
                                    @else {{ $request->status }} @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($request->agent)
                                    {{ $request->agent->nom }} {{ $request->agent->prenoms }}
                                @else
                                    Non assigné
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $request->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('agent.requests.show', $request) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </a>
                                    @if(in_array($request->status, ['pending', 'processing']))
                                        <a href="{{ route('agent.requests.process', $request) }}"
                                           class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Traiter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Aucune demande trouvée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Timeline des actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Timeline des actions</h3>
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($citizen->requests->take(5) as $request)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full
                                        @if($request->status === 'completed') bg-green-500
                                        @elseif($request->status === 'processing') bg-blue-500
                                        @elseif($request->status === 'rejected') bg-red-500
                                        @else bg-yellow-500 @endif
                                        flex items-center justify-center ring-8 ring-white">
                                        <i class="fas
                                            @if($request->status === 'completed') fa-check
                                            @elseif($request->status === 'processing') fa-clock
                                            @elseif($request->status === 'rejected') fa-times
                                            @else fa-hourglass-half @endif
                                            text-white text-sm"></i>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Demande de <strong>{{ $request->document ? $request->document->title : 'N/A' }}</strong>
                                            <span class="font-medium text-gray-900">{{ $request->reference_number }}</span>
                                        </p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                        <time datetime="{{ $request->created_at->toISOString() }}">{{ $request->created_at->format('d/m/Y') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function citizenDetailApp() {
    return {
        chartPeriod: '6',
        statusFilter: '',

        init() {
            this.initChart();
        },

        updateChart() {
            // Mise à jour du graphique selon la période
            console.log('Mise à jour du graphique pour:', this.chartPeriod, 'mois');
        },

        filterRequests() {
            // Filtrage des demandes
            console.log('Filtrage par statut:', this.statusFilter);
        },

        initChart() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Demandes',
                        data: [{{ $citizen->requests->where('created_at', '>=', now()->subMonths(6))->groupBy(function($date) { return $date->created_at->format('m'); })->map->count()->values()->implode(',') ?: '0,0,0,0,0,0' }}],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
        }
    }
}

function exportCitizenData() {
    // Exporter les données du citoyen
    window.open(`/agent/citizens/{{ $citizen->id }}/export`, '_blank');
}

function contactCitizen() {
    // Contacter le citoyen par email
    window.location.href = `mailto:{{ $citizen->email }}?subject=PCT UVCI - Contact`;
}
</script>
@endpush
