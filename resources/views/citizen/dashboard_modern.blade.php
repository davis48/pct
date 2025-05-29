@extends('layouts.front.app')

@section('title', 'Mon Espace Citoyen | Plateforme Administrative')

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    }
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .notification-item {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .request-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-in_progress { background-color: #cce7ff; color: #0066cc; }
    .status-approved { background-color: #d1e7dd; color: #0a3622; }
    .status-rejected { background-color: #f8d7da; color: #721c24; }
    .action-btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="gradient-bg text-white p-4 rounded-4 shadow">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-user-circle me-3"></i>
                                Bienvenue, {{ auth()->user()->prenoms }} {{ auth()->user()->nom }}
                            </h1>
                            <p class="mb-0 opacity-90">Gérez vos demandes administratives en toute simplicité</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                                <a href="{{ route('requests.create') }}" class="btn btn-light btn-lg action-btn">
                                    <i class="fas fa-plus me-2"></i>Nouvelle Demande
                                </a>
                                <a href="{{ route('documents.index') }}" class="btn btn-outline-light action-btn">
                                    <i class="fas fa-file-alt me-2"></i>Documents
                                </a>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            Notifications récentes ({{ $notifications->count() }})
                        </h5>
                        <button onclick="markAllAsRead()" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="notifications-container">
                            @foreach($notifications as $notification)
                            <div class="notification-item p-3 border-bottom" data-id="{{ $notification->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="{{ $notification->icon ?? 'fas fa-info-circle' }} me-2 text-primary"></i>
                                            <h6 class="mb-0 fw-semibold">{{ $notification->title }}</h6>
                                        </div>
                                        <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $notification->created_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <button onclick="markAsRead({{ $notification->id }})" class="btn btn-outline-secondary btn-sm">
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="display-6 text-primary mb-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5 class="card-title">Total des Demandes</h5>
                        <h2 class="text-primary fw-bold">{{ $stats['total_requests'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card h-100 border-warning">
                    <div class="card-body text-center">
                        <div class="display-6 text-warning mb-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="card-title">En Attente</h5>
                        <h2 class="text-warning fw-bold">{{ $stats['pending_requests'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card h-100 border-info">
                    <div class="card-body text-center">
                        <div class="display-6 text-info mb-3">
                            <i class="fas fa-cog fa-spin"></i>
                        </div>
                        <h5 class="card-title">En Cours</h5>
                        <h2 class="text-info fw-bold">{{ $stats['in_progress_requests'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card h-100 border-success">
                    <div class="card-body text-center">
                        <div class="display-6 text-success mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="card-title">Approuvées</h5>
                        <h2 class="text-success fw-bold">{{ $stats['approved_requests'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Requests Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-0">
                                    <i class="fas fa-list-alt me-2"></i>
                                    Mes Demandes
                                </h5>
                                <small class="text-muted">Suivez l'état de toutes vos demandes en temps réel</small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex align-items-center justify-content-md-end gap-3">
                                    <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                                        <i class="fas fa-plus me-2"></i>Nouvelle Demande
                                    </a>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2">Mise à jour auto</small>
                                        <div id="auto-refresh-indicator" class="bg-success rounded-circle pulse-animation" style="width: 10px; height: 10px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="requests-container">
                            @if($requests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document</th>
                                            <th>Statut</th>
                                            <th>Date de Demande</th>
                                            <th>Dernière Mise à Jour</th>
                                            <th>Agent Assigné</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $request)
                                        <tr class="request-item" data-id="{{ $request->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                                    <div>
                                                        <strong>{{ $request->document->name ?? 'Document non spécifié' }}</strong>
                                                        <br><small class="text-muted">ID: #{{ $request->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="request-status status-{{ $request->status }}">
                                                    @switch($request->status)
                                                        @case('pending')
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                            @break
                                                        @case('in_progress')
                                                            <i class="fas fa-cog me-1"></i>En cours
                                                            @break
                                                        @case('approved')
                                                            <i class="fas fa-check me-1"></i>Approuvée
                                                            @break
                                                        @case('rejected')
                                                            <i class="fas fa-times me-1"></i>Rejetée
                                                            @break
                                                        @default
                                                            {{ ucfirst($request->status) }}
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar me-1 text-muted"></i>
                                                {{ $request->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <i class="fas fa-sync me-1 text-muted"></i>
                                                {{ $request->updated_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if($request->assignedAgent)
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user text-info me-1"></i>
                                                        {{ $request->assignedAgent->name }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-user-slash me-1"></i>Non assigné
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('citizen.request.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($request->status === 'approved')
                                                        <button class="btn btn-outline-success btn-sm" title="Télécharger">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <div class="display-1 text-muted mb-3">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h4 class="text-muted mb-3">Aucune demande pour le moment</h4>
                                <p class="text-muted mb-4">Commencez par soumettre votre première demande administrative</p>
                                <a href="{{ route('requests.create') }}" class="btn btn-primary btn-lg action-btn">
                                    <i class="fas fa-plus me-2"></i>Faire ma première demande
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Configuration AJAX pour CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Auto-refresh des demandes toutes les 30 secondes
let refreshInterval = setInterval(function() {
    refreshRequests();
}, 30000);

// Fonction pour rafraîchir les demandes
function refreshRequests() {
    $.get('{{ route("citizen.requests.updates") }}', function(data) {
        if (data.requests) {
            updateRequestsDisplay(data.requests);
            updateAutoRefreshIndicator();
        }
    }).fail(function() {
        $('#auto-refresh-indicator').removeClass('bg-success pulse-animation').addClass('bg-danger');
    });
}

// Fonction pour mettre à jour l'affichage des demandes
function updateRequestsDisplay(requests) {
    requests.forEach(function(request) {
        let requestRow = $(`.request-item[data-id="${request.id}"]`);
        if (requestRow.length) {
            // Mettre à jour le statut
            let statusCell = requestRow.find('.request-status');
            statusCell.removeClass().addClass(`request-status status-${request.status}`);
            
            let statusText = '';
            switch(request.status) {
                case 'pending':
                    statusText = '<i class="fas fa-clock me-1"></i>En attente';
                    break;
                case 'in_progress':
                    statusText = '<i class="fas fa-cog me-1"></i>En cours';
                    break;
                case 'approved':
                    statusText = '<i class="fas fa-check me-1"></i>Approuvée';
                    break;
                case 'rejected':
                    statusText = '<i class="fas fa-times me-1"></i>Rejetée';
                    break;
            }
            statusCell.html(statusText);
            
            // Mettre à jour la date de dernière modification
            let updateCell = requestRow.find('td:nth-child(4)');
            updateCell.html(`<i class="fas fa-sync me-1 text-muted"></i>${request.updated_at}`);
            
            // Animation pour indiquer la mise à jour
            requestRow.addClass('table-warning');
            setTimeout(() => requestRow.removeClass('table-warning'), 2000);
        }
    });
}

// Fonction pour mettre à jour l'indicateur de rafraîchissement
function updateAutoRefreshIndicator() {
    $('#auto-refresh-indicator').removeClass('bg-danger').addClass('bg-success pulse-animation');
    setTimeout(() => {
        $('#auto-refresh-indicator').removeClass('pulse-animation');
    }, 1000);
}

// Fonction pour marquer une notification comme lue
function markAsRead(notificationId) {
    $.post(`{{ url('/citizen/notifications') }}/${notificationId}/read`, function() {
        $(`.notification-item[data-id="${notificationId}"]`).fadeOut(300, function() {
            $(this).remove();
            updateNotificationCount();
        });
    });
}

// Fonction pour marquer toutes les notifications comme lues
function markAllAsRead() {
    $.post('{{ route("citizen.notifications.read-all") }}', function() {
        $('#notifications-container').parent().parent().fadeOut(300);
    });
}

// Fonction pour mettre à jour le compteur de notifications
function updateNotificationCount() {
    let remainingNotifications = $('.notification-item').length;
    if (remainingNotifications === 0) {
        $('.card').has('#notifications-container').fadeOut(300);
    } else {
        $('.card-header h5').html(`<i class="fas fa-bell me-2"></i>Notifications récentes (${remainingNotifications})`);
    }
}

// Rafraîchir les notifications toutes les 60 secondes
setInterval(function() {
    $.get('{{ route("citizen.notifications") }}', function(data) {
        if (data.notifications && data.notifications.length > 0) {
            // Ajouter les nouvelles notifications
            data.notifications.forEach(function(notification) {
                if (!$(`.notification-item[data-id="${notification.id}"]`).length) {
                    let notificationHtml = `
                        <div class="notification-item p-3 border-bottom" data-id="${notification.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="${notification.icon || 'fas fa-info-circle'} me-2 text-primary"></i>
                                        <h6 class="mb-0 fw-semibold">${notification.title}</h6>
                                    </div>
                                    <p class="mb-1 text-muted">${notification.message}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        ${notification.created_at}
                                    </small>
                                </div>
                                <button onclick="markAsRead(${notification.id})" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    $('#notifications-container').prepend(notificationHtml);
                }
            });
        }
    });
}, 60000);

// Initialisation au chargement de la page
$(document).ready(function() {
    // Premier rafraîchissement après 5 secondes
    setTimeout(refreshRequests, 5000);
    
    // Animation des cartes statistiques au scroll
    $(window).scroll(function() {
        $('.stat-card').each(function() {
            let cardTop = $(this).offset().top;
            let cardBottom = cardTop + $(this).outerHeight();
            let windowTop = $(window).scrollTop();
            let windowBottom = windowTop + $(window).height();
            
            if (cardBottom > windowTop && cardTop < windowBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    });
});
</script>
@endpush
