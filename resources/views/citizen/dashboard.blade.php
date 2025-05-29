@extends('layouts.front.app')

@section('title', 'Mon Espace Citoyen')

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    }
    
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    
    .status-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }
    
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid #0d6efd;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .request-row {
        transition: all 0.3s ease;
    }
    
    .request-row:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    
    .action-btn {
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .refresh-indicator {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .refresh-indicator.show {
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header with Gradient Background -->    <div class="gradient-bg text-white rounded-3 p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">                <h1 class="h2 mb-2">
                    <i class="fas fa-user-circle me-2"></i>
                    Bienvenue{{ Auth::check() && Auth::user()->name ? ', ' . Auth::user()->name : '' }}
                </h1>
                <p class="mb-0 opacity-75">Gérez vos demandes administratives</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex flex-column align-items-md-end">
                    <div class="refresh-indicator" id="refreshIndicator">
                        <i class="fas fa-sync-alt fa-spin text-white"></i>
                        <span class="ms-1">Actualisation...</span>
                    </div>
                    <small class="opacity-75">Dernière mise à jour: <span id="lastUpdate">{{ now()->format('H:i') }}</span></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                            <i class="fas fa-plus me-1"></i>
                            Nouvelle Demande
                        </a>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-primary action-btn">
                            <i class="fas fa-list me-1"></i>
                            Mes Demandes
                        </a>
                        <button class="btn btn-outline-secondary action-btn" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Actualiser
                        </button>
                        <button class="btn btn-outline-info action-btn" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle me-1"></i>
                            Aide
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="totalRequests">{{ $stats['total_requests'] }}</div>
                            <div class="small">Total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="pendingRequests">{{ $stats['pending_requests'] }}</div>
                            <div class="small">En Attente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-spinner fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="inProgressRequests">{{ $stats['in_progress_requests'] }}</div>
                            <div class="small">En Cours</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="approvedRequests">{{ $stats['approved_requests'] }}</div>
                            <div class="small">Approuvées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="rejectedRequests">{{ $stats['rejected_requests'] ?? 0 }}</div>
                            <div class="small">Rejetées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    @if($notifications->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell text-primary me-2"></i>                            Notifications récentes ({{ $notifications->count() }})
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-1"></i>
                            Tout marquer comme lu
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notifications-container">
                        @foreach($notifications as $notification)
                        <div class="notification-item border rounded-3 p-3 mb-3" data-id="{{ $notification->id }}">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="{{ $notification->icon ?? 'fas fa-info-circle' }} text-primary me-2"></i>
                                        <h6 class="mb-0">{{ $notification->title ?? 'Notification' }}</h6>
                                    </div>
                                    <p class="text-muted mb-1 small">{{ $notification->message ?? $notification->data['message'] ?? 'Nouvelle notification' }}</p>
                                    <span class="badge bg-light text-dark">{{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead({{ $notification->id }})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <div class="row">
        <!-- Recent Requests -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Mes Demandes Récentes
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-2" id="autoRefreshStatus">
                                <i class="fas fa-circle text-success me-1"></i>
                                Auto-actualisation
                            </span>
                            <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list me-1"></i>
                                Voir tout
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="requests-container">
                        @forelse($requests->take(5) as $request)
                        <div class="request-row border rounded-3 p-3 mb-3" data-id="{{ $request->id }}">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @switch($request->status)
                                                @case('pending')
                                                    <span class="status-badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> En attente
                                                    </span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="status-badge bg-info text-white">
                                                        <i class="fas fa-cogs me-1"></i> En cours
                                                    </span>
                                                    @break
                                                @case('approved')
                                                    <span class="status-badge bg-success text-white">
                                                        <i class="fas fa-check-circle me-1"></i> Approuvée
                                                    </span>
                                                    @break
                                                @case('rejected')
                                                    <span class="status-badge bg-danger text-white">
                                                        <i class="fas fa-times-circle me-1"></i> Rejetée
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="status-badge bg-secondary text-white">
                                                        <i class="fas fa-question me-1"></i> {{ ucfirst($request->status) }}
                                                    </span>
                                            @endswitch
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $request->document->name ?? 'Document non spécifié' }}</h6>
                                            <p class="text-muted small mb-0">
                                                Référence: #{{ $request->reference_number ?? $request->id }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $request->created_at->format('d/m/Y') }}
                                    </small>
                                    @if($request->assignedAgent)
                                        <br><small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $request->assignedAgent->name }}
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('citizen.request.show', $request->id) }}" 
                                           class="btn btn-sm btn-outline-primary action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status === 'approved' && $request->document_url)
                                            <a href="{{ $request->document_url }}" 
                                               class="btn btn-sm btn-outline-success action-btn" 
                                               target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Aucune demande pour le moment</h5>
                            <p class="text-muted">Commencez par créer votre première demande</p>
                            <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                                <i class="fas fa-plus me-1"></i>
                                Créer ma première demande
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Actions -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Aperçu de mes demandes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 text-warning mb-1" id="pendingCount">{{ $stats['pending_requests'] }}</div>
                            <div class="small text-muted">En attente</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-info mb-1" id="progressCount">{{ $stats['in_progress_requests'] }}</div>
                            <div class="small text-muted">En cours</div>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $stats['total_requests'] > 0 ? ($stats['approved_requests'] / $stats['total_requests']) * 100 : 0 }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>{{ $stats['approved_requests'] }} approuvées</span>
                        <span>{{ $stats['total_requests'] }} total</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                            <i class="fas fa-plus me-2"></i>
                            Nouvelle demande
                        </a>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary action-btn">
                            <i class="fas fa-list me-2"></i>
                            Historique complet
                        </a>
                        <button class="btn btn-outline-info action-btn" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-2"></i>
                            Actualiser les données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-question-circle text-primary me-2"></i>
                    Guide d'utilisation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-plus text-success me-2"></i>Faire une demande</h6>
                        <p class="small">Cliquez sur "Nouvelle Demande" pour commencer votre demande de document administratif.</p>
                        
                        <h6><i class="fas fa-eye text-info me-2"></i>Suivre mes demandes</h6>
                        <p class="small">Consultez l'état de vos demandes en temps réel dans votre tableau de bord.</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-bell text-warning me-2"></i>Notifications</h6>
                        <p class="small">Recevez des notifications pour chaque mise à jour de vos demandes.</p>
                        
                        <h6><i class="fas fa-download text-primary me-2"></i>Télécharger</h6>
                        <p class="small">Une fois approuvés, vos documents sont disponibles en téléchargement.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let refreshInterval;

// Fonctions de gestion du rafraîchissement automatique
function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        refreshData();
    }, 30000); // Rafraîchir toutes les 30 secondes
    
    updateRefreshStatus(true);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
    updateRefreshStatus(false);
}

function updateRefreshStatus(isActive) {
    const statusElement = document.getElementById('autoRefreshStatus');
    if (statusElement) {
        statusElement.innerHTML = isActive 
            ? '<i class="fas fa-circle text-success me-1"></i>Auto-actualisation'
            : '<i class="fas fa-circle text-secondary me-1"></i>Arrêtée';
    }
}

// Fonction principale de rafraîchissement des données
async function refreshData() {
    const indicator = document.getElementById('refreshIndicator');
    if (indicator) {
        indicator.classList.add('show');
    }
    
    try {
        await Promise.all([
            refreshRequestsData(),
            refreshNotifications(),
            refreshStats()
        ]);
        
        // Mettre à jour l'heure de dernière mise à jour
        const lastUpdateElement = document.getElementById('lastUpdate');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = new Date().toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    } catch (error) {
        console.error('Erreur lors du rafraîchissement:', error);
    } finally {
        if (indicator) {
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 1000);
        }
    }
}

// Rafraîchir les données des demandes
async function refreshRequestsData() {
    try {
        const response = await fetch('{{ route("citizen.requests.updates") }}');
        if (response.ok) {
            const data = await response.json();
            if (data.requests) {
                updateRequestsDisplay(data.requests);
            }
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des demandes:', error);
    }
}

// Rafraîchir les notifications
async function refreshNotifications() {
    try {
        const response = await fetch('{{ route("citizen.notifications") }}');
        if (response.ok) {
            const data = await response.json();
            if (data.notifications) {
                updateNotificationsDisplay(data.notifications);
            }
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des notifications:', error);
    }
}

// Rafraîchir les statistiques
async function refreshStats() {
    try {        // Mettre à jour les compteurs visuellement
        const elements = ['totalRequests', 'pendingRequests', 'inProgressRequests', 'approvedRequests', 'rejectedRequests'];
        elements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => {
                    element.classList.remove('animate__animated', 'animate__pulse');
                }, 1000);
            }
        });
    } catch (error) {
        console.error('Erreur lors de la mise à jour des statistiques:', error);
    }
}

// Mettre à jour l'affichage des demandes
function updateRequestsDisplay(requests) {
    // Animation de mise à jour
    const container = document.getElementById('requests-container');
    if (container) {
        container.style.opacity = '0.7';
        setTimeout(() => {
            container.style.opacity = '1';
        }, 500);
    }
}

// Mettre à jour l'affichage des notifications
function updateNotificationsDisplay(notifications) {
    const container = document.getElementById('notifications-container');
    if (container && notifications.length > 0) {
        // Ajouter une animation pour signaler les nouvelles notifications
        container.classList.add('border-primary');
        setTimeout(() => {
            container.classList.remove('border-primary');
        }, 2000);
    }
}

// Marquer une notification comme lue
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`{{ route("citizen.notifications.read", "") }}/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.style.transition = 'opacity 0.3s ease';
                notificationElement.style.opacity = '0.5';
                setTimeout(() => {
                    notificationElement.remove();
                }, 300);
            }
        }
    } catch (error) {
        console.error('Erreur lors du marquage de la notification:', error);
    }
}

// Marquer toutes les notifications comme lues
async function markAllAsRead() {
    try {
        const response = await fetch('{{ route("citizen.notifications.read-all") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            const notificationsContainer = document.getElementById('notifications-container');
            if (notificationsContainer) {
                notificationsContainer.style.transition = 'opacity 0.3s ease';
                notificationsContainer.style.opacity = '0.5';
                setTimeout(() => {
                    notificationsContainer.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-check-circle me-2"></i>Toutes les notifications ont été marquées comme lues</div>';
                    notificationsContainer.style.opacity = '1';
                }, 300);
            }
        }
    } catch (error) {
        console.error('Erreur lors du marquage des notifications:', error);
    }
}

// Gestion des événements de la page
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    
    // Gestion de la visibilité de l'onglet
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
});

// Arrêter le rafraîchissement avant de quitter la page
window.addEventListener('beforeunload', function() {
    stopAutoRefresh();
});

// Toast notifications (si nécessaire)
function showToast(message, type = 'info') {
    // Implémentation simple de toast notification
    const toastContainer = document.createElement('div');
    toastContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toastContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toastContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toastContainer);
    
    // Auto-dismiss après 5 secondes
    setTimeout(() => {
        if (toastContainer.parentNode) {
            toastContainer.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection
