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
    }    .status-draft { background-color: #ffeaa7; color: #d68910; }
    .status-pending { 
        background: linear-gradient(135deg, #28a745, #20c997); 
        color: #ffffff; 
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        animation: gentle-pulse 3s infinite;
    }
    .status-in_progress { background-color: #cce7ff; color: #0066cc; }
    .status-approved { background-color: #d1e7dd; color: #0a3622; }
    .status-rejected { background-color: #f8d7da; color: #721c24; }
    .status-unpaid { background-color: #ff6b6b; color: #ffffff; animation: pulse-payment 2s infinite; }
    
    @keyframes gentle-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes pulse-payment {
        0%, 100% { background-color: #ff6b6b; }
        50% { background-color: #ff8e8e; }
    }
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
    }    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    @keyframes successPulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.02); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
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
                        <div class="col-md-8">                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-city me-3"></i>
                                Bienvenue, {{ auth()->user()->prenoms }} {{ auth()->user()->nom }}
                            </h1>
                            <p class="mb-0 opacity-90">Gérez vos demandes administratives en toute simplicité</p>
                        </div>                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                                <a href="{{ route('interactive-forms.index') }}" class="btn btn-light btn-lg action-btn">
                                    <i class="fas fa-file-signature me-2"></i>Nouvelle Demande
                                </a>
                                <a href="{{ route('documents.index') }}" class="btn btn-outline-light action-btn">
                                    <i class="fas fa-archive me-2"></i>Documents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        </div>        <!-- Messages de succès ULTRA VISIBLES pour les paiements -->
        @if(session('payment_success') || session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-dismissible fade show shadow-lg" role="alert" style="
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                    border: 3px solid #ffffff;
                    border-radius: 20px;
                    color: white;
                    font-size: 1.2rem;
                    padding: 2rem;
                    animation: successPulse 4s ease-in-out;
                    box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4), 0 5px 15px rgba(40, 167, 69, 0.2);
                    position: relative;
                    overflow: hidden;
                ">
                    <!-- Effet de brillance animé -->
                    <div style="
                        position: absolute;
                        top: -50%;
                        left: -50%;
                        width: 200%;
                        height: 200%;
                        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                        animation: shine 3s infinite;
                        pointer-events: none;
                    "></div>
                    
                    <div class="d-flex align-items-center">
                        <div class="me-4" style="font-size: 4rem; animation: bounce 2s infinite;">
                            🎉
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="alert-heading mb-3" style="font-size: 1.8rem; font-weight: bold;">
                                <i class="fas fa-check-circle me-3" style="font-size: 1.5rem;"></i>
                                FÉLICITATIONS ! Paiement Réussi avec Succès !
                            </h3>
                            <p class="mb-2" style="font-size: 1.1rem; font-weight: 500;">
                                {{ session('payment_success') ?? session('success') }}
                            </p>
                            <hr style="border-color: rgba(255,255,255,0.4); margin: 1.5rem 0;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hourglass-half me-2" style="font-size: 1.2rem;"></i>
                                <p class="mb-0" style="font-size: 1rem; font-weight: 500;">
                                    Votre demande est maintenant <strong>EN ATTENTE DE TRAITEMENT</strong>. 
                                    Vous recevrez une notification dès qu'elle sera traitée par nos services.
                                </p>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 1.2rem;"></button>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes shine {
                0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            }
            
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                40% { transform: translateY(-10px); }
                60% { transform: translateY(-5px); }
            }
        </style>
        @endif

        <!-- Notifications Section -->
        @if($notifications->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Notifications récentes ({{ $notifications->count() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('citizen.notification-preferences') }}" class="btn btn-outline-light btn-sm" title="Préférences de notifications">
                                <i class="fas fa-cog me-1"></i>Préférences
                            </a>
                            <button onclick="markAllAsRead()" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                            </button>
                        </div>
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
            <div class="col-12">                <!-- Section Mes Demandes Moderne -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div class="mb-3 sm:mb-0">
                                <h5 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-list-alt mr-2 text-primary-600"></i>
                                    Mes Demandes
                                </h5>
                                <p class="text-sm text-gray-600">Suivez l'état de toutes vos demandes en temps réel</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('interactive-forms.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-plus mr-2"></i>Nouvelle Demande
                                </a>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-2">Mise à jour auto</span>
                                    <div id="auto-refresh-indicator" class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="requests-container">
                            @if($requests->count() > 0)
                            <!-- Version desktop moderne -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Document</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Statut</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Date de Demande</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Dernière Mise à Jour</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Agent Assigné</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($requests as $request)
                                        <tr class="request-item hover:bg-gray-50 transition-colors duration-200" data-id="{{ $request->id }}">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-file-alt text-primary-600 text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $request->type_label ?? ucfirst($request->type) }}</div>
                                                        <div class="text-sm text-gray-500">ID: #{{ $request->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($request->payment_status === 'unpaid' && $request->requiresPayment())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-400 rounded-full"></span>
                                                        <i class="fas fa-credit-card mr-1"></i>À Payer
                                                    </span>
                                                @else
                                                    @switch($request->status)
                                                        @case('draft')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                                                                <i class="fas fa-edit mr-1"></i>Brouillon
                                                            </span>
                                                            @break
                                                        @case('pending')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-400 rounded-full"></span>
                                                                <i class="fas fa-hourglass-half mr-1"></i>En Attente
                                                            </span>
                                                            @break
                                                        @case('in_progress')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-blue-400 rounded-full animate-spin"></span>
                                                                <i class="fas fa-cog mr-1"></i>En Cours
                                                            </span>
                                                            @break
                                                        @case('approved')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                                                <i class="fas fa-check-circle mr-1"></i>Approuvée
                                                            </span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                                                <i class="fas fa-times-circle mr-1"></i>Rejetée
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                                                                <i class="fas fa-question-circle mr-1"></i>{{ ucfirst($request->status) }}
                                                            </span>
                                                    @endswitch
                                                @endif
                                            </td>
                                                    <div class="mt-1">
                                                        <small class="text-success fw-bold">
                                                            <i class="fas fa-clock me-1"></i>Soumise et en cours de traitement
                                                        </small>
                                                    </div>
                                                @endif
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
                                <a href="{{ route('interactive-forms.index') }}" class="btn btn-primary btn-lg action-btn">
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
<!-- Inclusion du système de notification amélioré -->
<script src="{{ asset('js/citizen-notifications.js') }}"></script>
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
                    statusText = '<i class="fas fa-hourglass-half me-1"></i>En Attente';
                    break;
                case 'in_progress':
                    statusText = '<i class="fas fa-cog fa-spin me-1"></i>En Cours';
                    break;
                case 'approved':
                    statusText = '<i class="fas fa-check-circle me-1"></i>Approuvée';
                    break;
                case 'rejected':
                    statusText = '<i class="fas fa-times-circle me-1"></i>Rejetée';
                    break;
                case 'draft':
                    statusText = '<i class="fas fa-edit me-1"></i>Brouillon';
                    break;
                default:
                    statusText = `<i class="fas fa-question-circle me-1"></i>${request.status}`;
            }
            statusCell.html(statusText);
            
            // Mettre à jour la date de dernière modification
            let updateCell = requestRow.find('td:nth-child(4)');
            updateCell.html(`<i class="fas fa-sync me-1 text-muted"></i>${request.updated_at}`);
            
            // Animation pour indiquer la mise à jour - utiliser une couleur verte pour les demandes en attente
            if (request.status === 'pending') {
                requestRow.addClass('table-success');
                setTimeout(() => requestRow.removeClass('table-success'), 3000);
            } else {
                requestRow.addClass('table-warning');
                setTimeout(() => requestRow.removeClass('table-warning'), 2000);
            }
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

// Les fonctions de notification sont maintenant dans citizen-notifications.js

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
