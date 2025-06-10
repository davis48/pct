@extends('layouts.dashboard')

@section('title', 'Mon Espace Citoyen')
@section('dashboard-title', 'Espace Citoyen')

@section('sidebar-menu')
    <li>
        <a href="{{ route('citizen.dashboard') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Tableau de bord
        </a>
    </li>
    <li>
        <a href="{{ route('requests.create') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('requests.create') ? 'active' : '' }}">
            <i class="fas fa-plus mr-3"></i>
            Nouvelle Demande
        </a>
    </li>
    <li>
        <a href="{{ route('requests.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('requests.index') ? 'active' : '' }}">
            <i class="fas fa-file-alt mr-3"></i>
            Mes Demandes
        </a>
    </li>
    <li>
        <a href="{{ route('interactive-forms.index') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('interactive-forms.*') ? 'active' : '' }}">
            <i class="fas fa-edit mr-3"></i>
            Formulaires Interactifs
        </a>
    </li>
    <li>
        <a href="{{ route('profile.edit') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user mr-3"></i>
            Mon Profil
        </a>
    </li>
    <li>
        <a href="#" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-bell mr-3"></i>
            Notifications
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
        </a>
    </li>
    <li class="mt-8 pt-4 border-t border-blue-700">
        <a href="#" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-question-circle mr-3"></i>
            Aide & Support
        </a>
    </li>
@endsection

@section('dashboard-content')

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
        
        .card-body {
            padding: 1rem !important;
        }
        
        .h3 {
            font-size: 1.5rem !important;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .request-row {
            padding: 1rem !important;
        }
        
        .request-row .row {
            text-align: center;
        }
        
        .request-row .col-md-3:last-child {
            margin-top: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .d-flex.flex-wrap {
            flex-direction: column !important;
        }
        
        .action-btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
        }
        
        .gradient-bg .col-md-4 {
            margin-top: 1rem;
            text-align: center !important;
        }
    }

    /* Styles pour le système de notification compact */
    .notification-bell {
        animation: bell-shake 2s ease-in-out infinite;
    }
    
    @keyframes bell-shake {
        0%, 50%, 100% { transform: rotate(0deg); }
        5%, 15%, 25%, 35%, 45% { transform: rotate(10deg); }
        10%, 20%, 30%, 40% { transform: rotate(-10deg); }
    }
    
    .notification-compact {
        border-left: 4px solid #0d6efd;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        transition: all 0.3s ease;
        border-radius: 0 8px 8px 0;
    }
    
    .notification-compact:hover {
        transform: translateX(2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .notification-content h6 {
        font-size: 14px;
        font-weight: 600;
        color: #1d2b36;
        margin-bottom: 2px;
    }
    
    .notification-content .text-muted {
        font-size: 12px;
        line-height: 1.4;
    }
    
    .notification-actions .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
    }
    
    .notification-badge-pulse {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .notification-center-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        transition: all 0.3s ease;
    }
    
    .notification-center-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-1px);
    }
    
    .notification-item {
        position: relative;
        overflow: hidden;
    }
    
    .notification-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #0d6efd, #0a58ca);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .notification-item:hover::before {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header with Gradient Background -->
    <div class="gradient-bg text-white rounded-3 p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-2">
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

    <!-- Quick Actions -->    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                            <i class="fas fa-plus me-1"></i>
                            Nouvelle Demande
                        </a>
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-success action-btn">
                            <i class="fas fa-edit me-1"></i>
                            Formulaires Interactifs
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
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-credit-card fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="draftRequests">{{ $stats['draft_requests'] ?? 0 }}</div>
                            <div class="small">À Payer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card stat-card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-flag-checkered fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="h3 mb-0" id="completedRequests">{{ $stats['completed_requests'] ?? 0 }}</div>
                            <div class="small">Complétées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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

    @if(isset($additionalData['payment_summary']) && $additionalData['payment_summary']['total_paid'] > 0)
    <!-- Payment Summary Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Résumé des Paiements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ number_format($additionalData['payment_summary']['total_paid'], 0, ',', ' ') }}</h4>
                                <small class="text-muted">FCFA Payés</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $additionalData['payment_summary']['payment_count'] }}</h4>
                                <small class="text-muted">Paiements</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-info mb-1">{{ number_format($additionalData['payment_summary']['average_amount'], 0, ',', ' ') }}</h4>
                                <small class="text-muted">Moyenne</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning mb-1">{{ number_format($additionalData['payment_summary']['total_pending'], 0, ',', ' ') }}</h4>
                            <small class="text-muted">En Attente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    @endif
    
    <!-- Widget de Notifications Compact -->
    <div class="row mb-4">
        <div class="col-12">
            @include('citizen.partials.notifications-widget')
        </div>
    </div>

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
                                        </div>                                        <div>
                                            <h6 class="mb-1">{{ ucfirst($request->type) ?? 'Type non spécifié' }}</h6>
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
                                    <div class="btn-group" role="group">                                        <a href="{{ route('citizen.request.show', $request->id) }}" 
                                           class="btn btn-sm btn-outline-primary action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>                        @if($request->status === 'approved')
                                            <a href="{{ route('documents.preview', $request) }}" 
                                               class="btn btn-sm btn-outline-info action-btn" 
                                               target="_blank"
                                               title="Prévisualiser le document">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('documents.download', $request) }}" 
                                               class="btn btn-sm btn-outline-success action-btn"
                                               title="Télécharger le document">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        @if($request->status === 'draft')
                                            <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger action-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                </div>            </div>
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
                    </div>                    <div class="progress mb-2" style="height: 8px;">
                        @php
                            $progressPercentage = $stats['total_requests'] > 0 ? 
                                round(($stats['approved_requests'] / $stats['total_requests']) * 100, 1) : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $progressPercentage }}%"
                             aria-valuenow="{{ $progressPercentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
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
                <div class="card-body">                    <div class="d-grid gap-2">
                        <a href="{{ route('requests.create') }}" class="btn btn-primary action-btn">
                            <i class="fas fa-plus me-2"></i>
                            Nouvelle demande
                        </a>
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-success action-btn">
                            <i class="fas fa-edit me-2"></i>
                            Formulaires Interactifs
                        </a>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary action-btn">
                            <i class="fas fa-list me-2"></i>
                            Historique complet
                        </a>
                        <button class="btn btn-outline-info action-btn" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-2"></i>
                            Actualiser les données
                        </button>
                    </div>                </div>
            </div>
        </div>
    </div>

    <!-- Formulaires Interactifs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit text-success me-2"></i>
                            Formulaires Interactifs
                        </h5>
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye me-1"></i>
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-lightbulb me-1"></i>
                        Remplissez et générez vos documents directement en ligne !
                    </p>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100 border-success border-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-baby fa-2x text-pink-500 mb-2"></i>
                                    <h6 class="card-title">Extrait de Naissance</h6>
                                    <p class="card-text small text-muted">Formulaire en ligne pour extrait de naissance</p>
                                    <a href="{{ route('interactive-forms.show', 'extrait-naissance') }}" class="btn btn-sm btn-outline-success">
                                        Remplir
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100 border-success border-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-heart fa-2x text-red-500 mb-2"></i>
                                    <h6 class="card-title">Certificat de Mariage</h6>
                                    <p class="card-text small text-muted">Formulaire en ligne pour certificat de mariage</p>
                                    <a href="{{ route('interactive-forms.show', 'certificat-mariage') }}" class="btn btn-sm btn-outline-success">
                                        Remplir
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100 border-success border-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-2x text-blue-500 mb-2"></i>
                                    <h6 class="card-title">Certificat de Célibat</h6>
                                    <p class="card-text small text-muted">Formulaire en ligne pour certificat de célibat</p>
                                    <a href="{{ route('interactive-forms.show', 'certificat-celibat') }}" class="btn btn-sm btn-outline-success">
                                        Remplir
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100 border-success border-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-home fa-2x text-green-500 mb-2"></i>
                                    <h6 class="card-title">Attestation de Domicile</h6>
                                    <p class="card-text small text-muted">Formulaire en ligne pour attestation de domicile</p>
                                    <a href="{{ route('interactive-forms.show', 'attestation-domicile') }}" class="btn btn-sm btn-outline-success">
                                        Remplir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profil du Citoyen -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user text-primary me-2"></i>
                            Mon Profil
                        </h5>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                    alt="Photo de profil" class="rounded-circle img-thumbnail" 
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                    style="width: 120px; height: 120px;">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Nom complet</h6>
                                    <p class="mb-3"><strong>{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</strong></p>
                                    
                                    <h6 class="text-muted mb-1">Date de naissance</h6>
                                    <p class="mb-3">{{ Auth::user()->date_naissance ? \Carbon\Carbon::parse(Auth::user()->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                                    
                                    <h6 class="text-muted mb-1">Lieu de naissance</h6>
                                    <p class="mb-3">{{ Auth::user()->place_of_birth ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Genre</h6>
                                    <p class="mb-3">
                                        @if(Auth::user()->genre == 'M')
                                            Masculin
                                        @elseif(Auth::user()->genre == 'F')
                                            Féminin
                                        @else
                                            {{ Auth::user()->genre ?? 'Non renseigné' }}
                                        @endif
                                    </p>
                                    
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="mb-3">{{ Auth::user()->email }}</p>
                                    
                                    <h6 class="text-muted mb-1">Téléphone</h6>
                                    <p class="mb-3">{{ Auth::user()->phone ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                            
                            @if(Auth::user()->address)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-1">Adresse</h6>
                                    <p class="mb-0">{{ Auth::user()->address }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
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

// Variables pour le système de rafraîchissement intelligent
let refreshIntervalTime = 30000; // 30 secondes par défaut
let isPageVisible = true;
let consecutiveErrors = 0;
let maxRetries = 3;

// Fonctions de gestion du rafraîchissement automatique
function startAutoRefresh() {
    stopAutoRefresh(); // S'assurer qu'il n'y a pas de double interval
    
    refreshInterval = setInterval(async () => {
        if (isPageVisible && consecutiveErrors < maxRetries) {
            try {
                await refreshData();
                consecutiveErrors = 0; // Reset sur succès
                
                // Adapter l'intervalle selon l'activité
                adaptRefreshInterval();
            } catch (error) {
                consecutiveErrors++;
                console.error('Erreur de rafraîchissement automatique:', error);
                
                // Augmenter l'intervalle en cas d'erreurs répétées
                if (consecutiveErrors >= maxRetries) {
                    refreshIntervalTime = Math.min(refreshIntervalTime * 2, 300000); // Max 5 minutes
                    showToast('Problème de connexion détecté. Ralentissement des mises à jour.', 'warning');
                }
            }
        }
    }, refreshIntervalTime);
    
    updateRefreshStatus(true);
}

// Adapter l'intervalle de rafraîchissement selon l'activité
function adaptRefreshInterval() {
    const hour = new Date().getHours();
    
    // Heures de bureau (8h-18h) : plus fréquent
    if (hour >= 8 && hour <= 18) {
        refreshIntervalTime = 30000; // 30 secondes
    } else {
        refreshIntervalTime = 60000; // 1 minute hors heures de bureau
    }
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
    try {
        const response = await fetch('{{ route("citizen.stats") }}');
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.stats) {
                updateStatsDisplay(data.stats);
                
                // Mettre à jour les informations supplémentaires si disponibles
                if (data.data) {
                    updateAdditionalData(data.data);
                }
            }
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des statistiques:', error);
    }
}

// Mettre à jour l'affichage des statistiques avec animation
function updateStatsDisplay(stats) {    const statsMapping = {
        'totalRequests': stats.total_requests,
        'draftRequests': stats.draft_requests,
        'pendingRequests': stats.pending_requests,
        'inProgressRequests': stats.in_progress_requests,
        'approvedRequests': stats.approved_requests,
        'completedRequests': stats.completed_requests || 0,
        'rejectedRequests': stats.rejected_requests
    };
    
    Object.entries(statsMapping).forEach(([elementId, newValue]) => {
        const element = document.getElementById(elementId);
        if (element) {
            const currentValue = parseInt(element.textContent) || 0;
            
            // Animation si la valeur a changé
            if (currentValue !== newValue) {
                animateCounterUpdate(element, currentValue, newValue);
                
                // Ajouter une classe pour highlighter le changement
                const card = element.closest('.stat-card');
                if (card) {
                    card.classList.add('border-primary', 'border-2');
                    setTimeout(() => {
                        card.classList.remove('border-primary', 'border-2');
                    }, 2000);
                }
            }
        }
    });
}

// Animation de compteur pour les changements de valeur
function animateCounterUpdate(element, startValue, endValue) {
    const duration = 1000; // 1 seconde
    const startTime = performance.now();
    
    function updateValue(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Utiliser une fonction d'easing pour une animation plus fluide
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.round(startValue + (endValue - startValue) * easeOut);
        
        element.textContent = currentValue;
        
        if (progress < 1) {
            requestAnimationFrame(updateValue);
        } else {
            element.textContent = endValue; // S'assurer que la valeur finale est exacte
        }
    }
    
    requestAnimationFrame(updateValue);
}

// Mettre à jour les données supplémentaires
function updateAdditionalData(data) {
    // Afficher un résumé des paiements si disponible
    if (data.payment_summary) {
        const summary = data.payment_summary;
        const totalPaid = summary.total_paid || 0;
        
        // Créer ou mettre à jour un indicateur de paiement
        updatePaymentIndicator(totalPaid, summary.payment_count || 0);
    }
    
    // Mettre à jour l'activité récente
    if (data.recent_activity && data.recent_activity.length > 0) {
        updateRecentActivityIndicator(data.recent_activity);
    }
}

// Mettre à jour l'indicateur de paiement
function updatePaymentIndicator(totalPaid, paymentCount) {
    let indicator = document.getElementById('payment-indicator');
    if (!indicator) {
        // Créer l'indicateur s'il n'existe pas
        const header = document.querySelector('.gradient-bg .row .col-md-8');
        if (header) {
            indicator = document.createElement('div');
            indicator.id = 'payment-indicator';
            indicator.className = 'mt-2';
            header.appendChild(indicator);
        }
    }
    
    if (indicator && totalPaid > 0) {
        indicator.innerHTML = `
            <small class="opacity-75">
                <i class="fas fa-credit-card me-1"></i>
                Total payé: ${new Intl.NumberFormat('fr-FR').format(totalPaid)} FCFA 
                (${paymentCount} paiement${paymentCount > 1 ? 's' : ''})
            </small>
        `;
    }
}

// Mettre à jour l'indicateur d'activité récente
function updateRecentActivityIndicator(recentActivity) {
    const lastActivity = recentActivity[0];
    if (lastActivity) {
        let activityIndicator = document.getElementById('activity-indicator');
        if (!activityIndicator) {
            const header = document.querySelector('.gradient-bg .row .col-md-4');
            if (header) {
                activityIndicator = document.createElement('div');
                activityIndicator.id = 'activity-indicator';
                activityIndicator.className = 'mb-2';
                header.insertBefore(activityIndicator, header.firstChild);
            }
        }
        
        if (activityIndicator) {
            activityIndicator.innerHTML = `
                <small class="opacity-75">
                    <i class="fas fa-clock me-1"></i>
                    Dernière activité: ${lastActivity.updated_ago}
                </small>
            `;
        }
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
            // Supprimer la notification de l'interface
            const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                notificationElement.style.opacity = '0';
                notificationElement.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    notificationElement.remove();
                    
                    // Vérifier s'il reste des notifications
                    const remainingNotifications = document.querySelectorAll('.notification-item').length;
                    if (remainingNotifications === 0) {
                        // Masquer tout le widget de notifications
                        const notificationWidget = document.querySelector('.notification-compact');
                        if (notificationWidget) {
                            notificationWidget.style.transition = 'opacity 0.5s ease';
                            notificationWidget.style.opacity = '0';
                            setTimeout(() => {
                                notificationWidget.style.display = 'none';
                            }, 500);
                        }
                    }
                }, 300);
            }
            
            // Mettre à jour le compteur dans l'en-tête
            updateNotificationBadge();
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
            // Masquer toutes les notifications avec animation
            const notificationWidget = document.querySelector('.notification-compact');
            if (notificationWidget) {
                notificationWidget.style.transition = 'all 0.5s ease';
                notificationWidget.style.opacity = '0';
                notificationWidget.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    notificationWidget.style.display = 'none';
                    
                    // Afficher un message de confirmation temporaire
                    const confirmationMessage = document.createElement('div');
                    confirmationMessage.className = 'alert alert-success alert-dismissible fade show';
                    confirmationMessage.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        Toutes les notifications ont été marquées comme lues.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    notificationWidget.parentNode.insertBefore(confirmationMessage, notificationWidget);
                    
                    // Masquer le message après 5 secondes
                    setTimeout(() => {
                        if (confirmationMessage.parentNode) {
                            confirmationMessage.remove();
                        }
                    }, 5000);
                }, 500);
            }
            
            // Mettre à jour le compteur dans l'en-tête
            updateNotificationBadge();
        }
    } catch (error) {
        console.error('Erreur lors du marquage des notifications:', error);
    }
}

// Gestion des événements de la page
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
    
    // Gestion de la visibilité de l'onglet pour optimiser les performances
    document.addEventListener('visibilitychange', function() {
        isPageVisible = !document.hidden;
        
        if (document.hidden) {
            // Page cachée - ralentir les mises à jour
            refreshIntervalTime = 120000; // 2 minutes
        } else {
            // Page visible - reprendre le rythme normal
            refreshIntervalTime = 30000; // 30 secondes
            // Faire une mise à jour immédiate quand l'utilisateur revient
            refreshData();
        }
    });
    
    // Détecter l'inactivité de l'utilisateur
    let inactivityTimer;
    const inactivityDelay = 300000; // 5 minutes
    
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(() => {
            // Utilisateur inactif - réduire la fréquence
            refreshIntervalTime = 120000; // 2 minutes
        }, inactivityDelay);
    }
    
    // Événements d'activité utilisateur
    ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, resetInactivityTimer, true);
    });
    
    resetInactivityTimer();
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
