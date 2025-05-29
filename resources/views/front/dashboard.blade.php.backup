@extends('layouts.front.app')

@section('title', 'Tableau de bord | Plateforme Administrative')

@push('styles')
<style>
    .status-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .action-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }
    
    .action-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .action-card .icon-container {
        height: 80px;
        width: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1rem auto;
    }
    
    .gradient-bg-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
    
    .gradient-bg-success {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    }
    
    .gradient-bg-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    }
    
    .status-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50rem;
    }
    
    .request-item {
        transition: all 0.2s ease;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .request-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .info-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, #4158D0 0%, #C850C0 50%, #FFCC70 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        background: rgba(255,255,255,0.1);
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        background: rgba(255,255,255,0.1);
        width: 160px;
        height: 160px;
        border-radius: 50%;
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Bannière de bienvenue -->
    <div class="welcome-banner shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-2">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</h1>
                <p class="lead mb-0">Gérez vos demandes et documents administratifs en toute simplicité.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fas fa-user-shield fa-5x text-white-50 animate-float"></i>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <h4 class="fw-bold mb-4"><i class="fas fa-bolt me-2 text-warning"></i>Actions rapides</h4>
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card action-card shadow h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-container gradient-bg-primary mb-3">
                        <i class="fas fa-list-alt fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Mes demandes</h5>
                    <p class="card-text text-muted">Consultez et suivez l'état de vos demandes en cours.</p>
                    <a href="{{ url('/requests') }}" class="btn btn-primary w-100 rounded-pill">
                        <i class="fas fa-arrow-right me-2"></i>Voir mes demandes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card action-card shadow h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-container gradient-bg-success mb-3">
                        <i class="fas fa-plus-circle fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Nouvelle demande</h5>
                    <p class="card-text text-muted">Effectuez une nouvelle demande de document administratif.</p>
                    <a href="{{ url('/requests/create') }}" class="btn btn-success w-100 rounded-pill">
                        <i class="fas fa-file-signature me-2"></i>Créer une demande
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card action-card shadow h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-container gradient-bg-info mb-3">
                        <i class="fas fa-file-download fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Mes documents</h5>
                    <p class="card-text text-muted">Téléchargez et gérez vos documents approuvés.</p>
                    <a href="{{ route('documents.index') }}" class="btn btn-info w-100 rounded-pill text-white">
                        <i class="fas fa-download me-2"></i>Accéder aux documents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm info-card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>Demandes récentes</h5>
                        <span class="badge bg-primary rounded-pill">
                            @php
                                $requestsCount = Auth::user()->requests()->count();
                            @endphp
                            {{ $requestsCount }} {{ $requestsCount <= 1 ? 'demande' : 'demandes' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $userRequests = Auth::user()->requests()->latest()->take(5)->get();
                    @endphp
                    @if($userRequests->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($userRequests as $request)
                                <div class="list-group-item request-item p-3 border mb-2 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $request->type }}</h6>
                                            <p class="mb-1 small text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>{{ $request->created_at->format('d/m/Y') }}
                                                @if($request->processed_at)
                                                 • Traité le {{ \Carbon\Carbon::parse($request->processed_at)->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            @if($request->status == 'pending')
                                                <span class="status-badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            @elseif($request->status == 'approved')
                                                <span class="status-badge bg-success text-white">
                                                    <i class="fas fa-check-circle me-1"></i>Approuvé
                                                </span>
                                            @elseif($request->status == 'rejected')
                                                <span class="status-badge bg-danger text-white">
                                                    <i class="fas fa-times-circle me-1"></i>Rejeté
                                                </span>
                                            @else
                                                <span class="status-badge bg-secondary text-white">
                                                    <i class="fas fa-question-circle me-1"></i>{{ $request->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ url('/requests/' . $request->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Détails
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ url('/requests') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-list me-2"></i>Voir toutes mes demandes
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="mb-3">Vous n'avez pas encore effectué de demande.</p>
                            <a href="{{ url('/requests/create') }}" class="btn btn-primary rounded-pill">
                                <i class="fas fa-plus-circle me-2"></i>Créer votre première demande
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm info-card h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Mon profil</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle shadow-sm mb-3" width="100" height="100" alt="Photo de profil">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                            </div>
                        @endif
                        <h5 class="fw-bold">{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-id-badge me-1"></i>
                            {{ Auth::user()->isAdmin() ? 'Administrateur' : (Auth::user()->isAgent() ? 'Agent' : 'Citoyen') }}
                        </p>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="fw-medium mb-0">Email</p>
                                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::user()->phone)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="fw-medium mb-0">Téléphone</p>
                                    <p class="text-muted mb-0">{{ Auth::user()->phone }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(Auth::user()->address)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="fw-medium mb-0">Adresse</p>
                                    <p class="text-muted mb-0">{{ Auth::user()->address }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(Auth::user()->date_naissance)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-birthday-cake fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="fw-medium mb-0">Date de naissance</p>
                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse(Auth::user()->date_naissance)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animation pour les éléments au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.action-card, .info-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = '';
                }, 100 * index);
            }, 0);
        });
    });
</script>
@endpush
@endsection
