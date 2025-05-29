@extends('layouts.admin')

@section('title', 'Statistiques et performances')

@section('styles')
<style>
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
    .performance-card {
        transition: all 0.3s;
    }
    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stat-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Statistiques et performances</h2>
        <div>
            <button class="btn btn-sm bg-gradient-primary" id="refreshStats">
                <i class="fas fa-sync-alt me-2"></i> Actualiser
            </button>
            <button class="btn btn-sm bg-gradient-success" onclick="exportStats()">
                <i class="fas fa-file-export me-2"></i> Exporter
            </button>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Demandes totales</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['totalRequests'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-file-alt text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Demandes en attente</p>
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
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Demandes en cours</p>
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
            <div class="card stat-card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Demandes complétées</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $stats['completedRequests'] }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
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

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Performance des agents</h6>
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-sm bg-gradient-dark">Voir tous les agents</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Agent</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Demandes traitées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Traitées aujourd'hui</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">En cours</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Temps moyen</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentsPerformance as $agent)
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
                                                <p class="text-xs text-secondary mb-0">{{ $agent['email'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['processedCount'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['processedToday'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['inProgress'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $agent['avgProcessingTime'] }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.statistics.agent', $agent['id']) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Voir détails">
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

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Statistiques par type de document</h6>
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-sm bg-gradient-dark">Voir tous les documents</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Document</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">En attente</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Approuvées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rejetées</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Temps moyen</th>
                                    <th class="text-secondary opacity-7"></th>
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['totalCount'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['pendingCount'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['approvedCount'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['rejectedCount'] }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $document['avgProcessingTime'] }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.statistics.document', $document['id']) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Voir détails">
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour les graphiques
    const labels = @json($chartData['labels']);
    const activityData = @json($chartData['activityData']);
    const documentDistribution = @json($chartData['documentDistribution']);
    const statusDistribution = @json($chartData['statusDistribution']);

    // Graphique d'activité des 30 derniers jours
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Demandes en attente',
                    data: activityData.pending,
                    borderColor: '#fb6340',
                    backgroundColor: 'rgba(251, 99, 64, 0.1)',
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

    // Fonction pour actualiser les statistiques
    document.getElementById('refreshStats').addEventListener('click', function() {
        fetch('{{ route("admin.statistics.dashboard-stats") }}')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les statistiques
                document.querySelector('.stat-card:nth-child(1) h5').textContent = data.totalRequests;
                document.querySelector('.stat-card:nth-child(2) h5').textContent = data.pendingRequests;
                document.querySelector('.stat-card:nth-child(3) h5').textContent = data.inProgressRequests;
                document.querySelector('.stat-card:nth-child(4) h5').textContent = data.completedRequests;
                
                // Animation de mise à jour
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.classList.add('border-success');
                    setTimeout(() => {
                        card.classList.remove('border-success');
                    }, 1000);
                });
            });
    });

    // Fonction pour exporter les statistiques
    function exportStats() {
        // Simuler un téléchargement
        alert('Fonctionnalité d\'export en cours de développement');
    }
</script>
@endsection
