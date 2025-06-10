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
    </li>    <li>
        <a href="{{ route('citizen.notifications.center') }}" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white {{ request()->routeIs('citizen.notifications.*') ? 'active' : '' }}">
            <i class="fas fa-bell mr-3"></i>
            Notifications
            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>
    </li>
    <li class="mt-8 pt-4 border-t border-blue-700">
        <a href="#" class="sidebar-item flex items-center p-3 text-blue-100 hover:text-white">
            <i class="fas fa-question-circle mr-3"></i>
            Aide & Support
        </a>
    </li>
@endsection

@push('styles')
<style>
    .stat-card-primary { --card-color-1: #3b82f6; --card-color-2: #1d4ed8; }
    .stat-card-secondary { --card-color-1: #6b7280; --card-color-2: #4b5563; }
    .stat-card-warning { --card-color-1: #f59e0b; --card-color-2: #d97706; }
    .stat-card-info { --card-color-1: #06b6d4; --card-color-2: #0891b2; }
    .stat-card-success { --card-color-1: #10b981; --card-color-2: #059669; }
    .stat-card-dark { --card-color-1: #374151; --card-color-2: #1f2937; }
    .stat-card-danger { --card-color-1: #ef4444; --card-color-2: #dc2626; }
    
    .status-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }
    
    .request-item {
        transition: all 0.3s ease;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .request-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
</style>
@endpush

@section('dashboard-content')
<!-- Header -->
<div class="gradient-bg text-white rounded-2xl p-6 mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-user-circle mr-3"></i>
                Bienvenue{{ Auth::check() && Auth::user()->name ? ', ' . Auth::user()->name : '' }}
            </h1>
            <p class="text-blue-100">Gérez vos demandes administratives facilement</p>
        </div>
        <div class="mt-4 lg:mt-0 text-right">
            <div class="flex items-center text-blue-100 mb-1">
                <i class="fas fa-sync-alt mr-2"></i>
                <span class="text-sm">Dernière mise à jour: {{ now()->format('H:i') }}</span>
            </div>
            <button onclick="refreshData()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200">
                <i class="fas fa-refresh mr-2"></i>Actualiser
            </button>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-card p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Actions Rapides</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <a href="{{ route('requests.create') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all duration-200 hover:scale-105">
            <div class="bg-blue-600 text-white p-3 rounded-full mb-2">
                <i class="fas fa-plus"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Nouvelle Demande</span>
        </a>
        
        <a href="{{ route('interactive-forms.index') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-all duration-200 hover:scale-105">
            <div class="bg-green-600 text-white p-3 rounded-full mb-2">
                <i class="fas fa-edit"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Formulaires</span>
        </a>
        
        <a href="{{ route('requests.index') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all duration-200 hover:scale-105">
            <div class="bg-purple-600 text-white p-3 rounded-full mb-2">
                <i class="fas fa-list"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Mes Demandes</span>
        </a>
        
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-xl transition-all duration-200 hover:scale-105">
            <div class="bg-orange-600 text-white p-3 rounded-full mb-2">
                <i class="fas fa-user"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Mon Profil</span>
        </a>
        
        <button onclick="openHelpModal()" class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200 hover:scale-105">
            <div class="bg-gray-600 text-white p-3 rounded-full mb-2">
                <i class="fas fa-question-circle"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Aide</span>
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid mb-8">
    <div class="stat-card stat-card-primary">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Demandes</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['total_requests'] }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-secondary">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-gray-100 text-sm font-medium">À Payer</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['draft_requests'] ?? 0 }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-warning">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-yellow-100 text-sm font-medium">En Attente</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['pending_requests'] }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-info">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-cyan-100 text-sm font-medium">En Cours</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['in_progress_requests'] }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-spinner"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-success">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-green-100 text-sm font-medium">Approuvées</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['approved_requests'] }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-dark">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-gray-100 text-sm font-medium">Complétées</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['completed_requests'] ?? 0 }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-flag-checkered"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card stat-card-danger">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-red-100 text-sm font-medium">Rejetées</p>
                <p class="text-4xl font-bold stat-number">{{ $stats['rejected_requests'] ?? 0 }}</p>
            </div>
            <div class="text-4xl opacity-50">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

@if(isset($additionalData['payment_summary']) && $additionalData['payment_summary']['total_paid'] > 0)
<!-- Payment Summary -->
<div class="content-card p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
        <i class="fas fa-chart-line text-green-600 mr-2"></i>
        Résumé des Paiements
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
            <p class="text-2xl font-bold text-green-600">{{ number_format($additionalData['payment_summary']['total_paid'], 0, ',', ' ') }}</p>
            <p class="text-sm text-gray-500">FCFA Payés</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $additionalData['payment_summary']['payment_count'] }}</p>
            <p class="text-sm text-gray-500">Paiements</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-purple-600">{{ number_format($additionalData['payment_summary']['average_amount'], 0, ',', ' ') }}</p>
            <p class="text-sm text-gray-500">Moyenne</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-orange-600">{{ number_format($additionalData['payment_summary']['total_pending'], 0, ',', ' ') }}</p>
            <p class="text-sm text-gray-500">En Attente</p>
        </div>
    </div>
</div>
@endif

<!-- Main Content Grid -->
<div class="grid lg:grid-cols-3 gap-8">
    <!-- Recent Requests -->
    <div class="lg:col-span-2">
        <div class="content-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Mes Demandes Récentes
                </h3>
                <a href="{{ route('requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($requests->take(5) as $request)
                <div class="request-item p-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-start space-x-3 mb-3 md:mb-0">
                            <div class="flex-shrink-0">
                                @switch($request->status)
                                    @case('pending')
                                        <span class="status-badge bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> En attente
                                        </span>
                                        @break
                                    @case('in_progress')
                                        <span class="status-badge bg-blue-100 text-blue-800">
                                            <i class="fas fa-cogs mr-1"></i> En cours
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="status-badge bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Approuvée
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="status-badge bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Rejetée
                                        </span>
                                        @break
                                    @default
                                        <span class="status-badge bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i> {{ ucfirst($request->status) }}
                                        </span>
                                @endswitch
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900">{{ ucfirst($request->type) ?? 'Type non spécifié' }}</p>
                                <p class="text-sm text-gray-500">#{{ $request->reference_number ?? $request->id }}</p>
                                <p class="text-xs text-gray-400">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('citizen.request.show', $request->id) }}" 
                               class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($request->status === 'approved')
                                <a href="{{ route('documents.download', $request) }}" 
                                   class="bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-lg transition-colors duration-200"
                                   title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-500 mb-2">Aucune demande</h4>
                    <p class="text-gray-400 mb-4">Vous n'avez pas encore créé de demande</p>
                    <a href="{{ route('requests.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Créer ma première demande
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Stats & Actions -->
    <div class="space-y-6">
        <!-- Progress Overview -->
        <div class="content-card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">
                <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                Aperçu
            </h3>
            <div class="space-y-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_requests'] }}</div>
                    <div class="text-sm text-gray-500">En attente</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['in_progress_requests'] }}</div>
                    <div class="text-sm text-gray-500">En cours</div>
                </div>
                
                @php
                    $progressPercentage = $stats['total_requests'] > 0 ? 
                        round(($stats['approved_requests'] / $stats['total_requests']) * 100, 1) : 0;
                @endphp
                
                <div class="pt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Taux de réussite</span>
                        <span>{{ $progressPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulaires Interactifs -->
        <div class="content-card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">
                <i class="fas fa-edit text-green-600 mr-2"></i>
                Formulaires Rapides
            </h3>
            <div class="space-y-3">
                <a href="{{ route('interactive-forms.show', 'extrait-naissance') }}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-baby text-pink-500 mr-3"></i>
                        <span class="text-sm font-medium">Extrait de Naissance</span>
                    </div>
                </a>
                <a href="{{ route('interactive-forms.show', 'certificat-mariage') }}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-heart text-red-500 mr-3"></i>
                        <span class="text-sm font-medium">Certificat de Mariage</span>
                    </div>
                </a>
                <a href="{{ route('interactive-forms.show', 'certificat-celibat') }}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-500 mr-3"></i>
                        <span class="text-sm font-medium">Certificat de Célibat</span>
                    </div>
                </a>
                <a href="{{ route('interactive-forms.index') }}" class="block p-3 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-200 text-center">
                    <span class="text-sm font-medium">Voir tous les formulaires</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonction de rafraîchissement
async function refreshData() {
    const refreshBtn = document.querySelector('[onclick="refreshData()"]');
    const originalContent = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
    refreshBtn.disabled = true;
    
    try {
        // Simulation du rafraîchissement
        await new Promise(resolve => setTimeout(resolve, 1000));
        location.reload();
    } catch (error) {
        console.error('Erreur lors du rafraîchissement:', error);
    } finally {
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    }
}

// Fonction pour ouvrir le modal d'aide
function openHelpModal() {
    // Création d'un modal simple
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-6 max-w-md w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Aide & Support</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4 text-sm text-gray-600">
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Comment faire une demande ?</h4>
                    <p>Cliquez sur "Nouvelle Demande" et suivez les étapes.</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Suivre mes demandes</h4>
                    <p>Consultez l'état de vos demandes dans "Mes Demandes".</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Formulaires interactifs</h4>
                    <p>Remplissez et générez vos documents en ligne.</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <button onclick="this.closest('.fixed').remove()" class="btn-primary">
                    Compris
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>
@endpush
