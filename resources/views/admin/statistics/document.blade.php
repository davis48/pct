@extends('layouts.admin')

@section('title', 'Statistiques du document ' . $document->title)

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
        <h2 class="mb-0">Statistiques du document: {{ $document->title }}</h2>
        <div>
            <a href="{{ route('admin.statistics.index') }}" class="btn btn-sm bg-gradient-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour aux statistiques
            </a>
            <button class="btn btn-sm bg-gradient-success" onclick="exportDocumentStats()">
                <i class="fas fa-file-export me-2"></i> Exporter
            </button>
        </div>
    </div>

    <!-- Carte d'information du document -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-file-alt text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $document->title }}</h6>
                                    <p class="text-sm mb-0">{{ $document->description ?? 'Aucune description' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-chart-bar text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ $stats['totalRequests'] }} demandes au total</h6>
                                    <p class="text-sm mb-0">{{ $stats['completedToday'] }} traitées aujourd'hui</p>
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
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">En attente</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['pendingRequests'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-hourglass-half text-lg opacity-10" aria-hidden="true"></i>
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
                                    {{ $stats['inProgressRequests'] }}
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
                                    {{ $stats['approvedRequests'] }}
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
                                    {{ $stats['rejectedRequests'] }}
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
                    <h6>Activité des 30 derniers jours</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Répartition par statut</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top agents -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Top agents pour ce document</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Agent</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Demandes traitées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Approuvées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rejetées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Taux d'approbation</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topAgents as $agent)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-primary border-radius-md p-2">
                                                    <i class="fas fa-user-tie text-white"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $agent['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['total'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['approved'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['rejected'] }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $approvalRate = $agent['total'] > 0 ? round(($agent['approved'] / $agent['total']) * 100) : 0;
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
                                    <td class="align-middle">
                                        <a href="{{ route('admin.statistics.agent', $agent['id']) }}" class="text-secondary font-weight-bold text-xs">
                                            Détails
                                        </a>
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

    <!-- Historique des demandes -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Historique des demandes</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Référence</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Demandeur</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Agent assigné</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Statut</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $request->reference_number }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->user ? $request->user->nom . ' ' . $request->user->prenoms : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->assignedAgent ? $request->assignedAgent->nom . ' ' . $request->assignedAgent->prenoms : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="stat-badge bg-gradient-warning text-white">En attente</span>
                                        @elseif($request->status == 'in_progress')
                                            <span class="stat-badge bg-gradient-info text-white">En cours</span>
                                        @elseif($request->status == 'approved')
                                            <span class="stat-badge bg-gradient-success text-white">Approuvée</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="stat-badge bg-gradient-danger text-white">Rejetée</span>
                                        @else
                                            <span class="stat-badge bg-gradient-secondary text-white">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->updated_at->format('d/m/Y H:i') }}</p>
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
                        {{ $requests->links() }}
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
    const activityData = @json($chartData['activityData']);
    const statusDistribution = @json($chartData['statusDistribution']);

    // Graphique d'activité des 30 derniers jours
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Demandes créées',
                    data: activityData.created,
                    borderColor: '#5e72e4',
                    backgroundColor: 'rgba(94, 114, 228, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Demandes approuvées',
                    data: activityData.approved,
                    borderColor: '#2dce89',
                    backgroundColor: 'rgba(45, 206, 137, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Demandes rejetées',
                    data: activityData.rejected,
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

    // Graphique de répartition par statut
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusDistributionChart = new Chart(statusDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: statusDistribution.map(item => item.label),
            datasets: [{
                data: statusDistribution.map(item => item.value),
                backgroundColor: [
                    '#fb6340', // En attente (warning)
                    '#11cdef', // En cours (info)
                    '#2dce89', // Approuvées (success)
                    '#f5365c'  // Rejetées (danger)
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
    function exportDocumentStats() {
        // Simuler un téléchargement
        alert('Fonctionnalité d\'export en cours de développement');
    }
</script>
@endsection
