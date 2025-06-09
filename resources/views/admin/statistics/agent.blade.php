@extends('layouts.admin')

@section('title', 'Statistiques de l\'agent ' . $agent->nom . ' ' . $agent->prenoms)

@section('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .performance-card {
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    .progress-bar {
        height: 4px;
        border-radius: 4px;
    }
    .stat-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Performance de l'agent: {{ $agent->nom }} {{ $agent->prenoms }}</h2>
        <div>
            <a href="{{ route('admin.special.statistics') }}" class="btn btn-sm bg-gradient-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour aux statistiques
            </a>
            <button class="btn btn-sm bg-gradient-success" onclick="exportAgentStats()">
                <i class="fas fa-file-export me-2"></i> Exporter
            </button>
        </div>
    </div>

    <!-- Carte de profil de l'agent -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-user-tie text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $agent->nom }} {{ $agent->prenoms }}</h6>
                                    <p class="text-sm mb-0">{{ $agent->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $stats['totalProcessed'] }} demandes traitées</h6>
                                    <p class="text-sm mb-0">{{ $stats['completedToday'] }} aujourd'hui</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $stats['averageProcessingTime'] }}</h6>
                                    <p class="text-sm mb-0">Temps moyen de traitement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques clés -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card performance-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Demandes assignées</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['totalAssigned'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-tasks text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card performance-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">En cours</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['inProgress'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-spinner text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card performance-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Approuvées</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['approved'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card performance-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Rejetées</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['rejected'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fas fa-times text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Performance des 30 derniers jours</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Répartition par type de document</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container">
                        <canvas id="documentDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par document -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Statistiques par type de document</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Document</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total traitées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Approuvées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rejetées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Taux d'approbation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentStats as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-success border-radius-md p-2">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $document['title'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['total'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['approved'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['rejected'] }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $approvalRate = $document['total'] > 0 ? round(($document['approved'] / $document['total']) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 text-xs font-weight-bold">{{ $approvalRate }}%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="{{ $approvalRate }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $approvalRate }}%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des demandes traitées -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Historique des demandes traitées</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Référence</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Document</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Demandeur</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Statut</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date de traitement</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processedRequests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $request->reference_number }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->document ? $request->document->title : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->user ? $request->user->nom . ' ' . $request->user->prenoms : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        @if($request->status == 'approved')
                                            <span class="stat-badge bg-gradient-success text-white">Approuvée</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="stat-badge bg-gradient-danger text-white">Rejetée</span>
                                        @else
                                            <span class="stat-badge bg-gradient-secondary text-white">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->processed_at ? $request->processed_at->format('d/m/Y H:i') : $request->updated_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Voir détails">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $processedRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour les graphiques
    const labels = @json($chartData['labels']);
    const performanceData = @json($chartData['performanceData']);
    const documentDistribution = @json($chartData['documentDistribution']);

    // Graphique de performance des 30 derniers jours
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Demandes approuvées',
                    data: performanceData.approved,
                    borderColor: '#2dce89',
                    backgroundColor: 'rgba(45, 206, 137, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Demandes rejetées',
                    data: performanceData.rejected,
                    borderColor: '#f5365c',
                    backgroundColor: 'rgba(245, 54, 92, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Graphique de répartition par type de document
    const documentDistributionCtx = document.getElementById('documentDistributionChart').getContext('2d');
    const documentDistributionChart = new Chart(documentDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: documentDistribution.map(item => item.label),
            datasets: [{
                data: documentDistribution.map(item => item.value),
                backgroundColor: [
                    '#5e72e4', '#2dce89', '#fb6340', '#11cdef', '#f5365c', 
                    '#172b4d', '#5603ad', '#8965e0', '#f3a4b5', '#ffd600'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Fonction pour exporter les statistiques
    function exportAgentStats() {
        // Simuler un téléchargement
        alert('Fonctionnalité d\'export en cours de développement');
    }
</script>
@endsection
