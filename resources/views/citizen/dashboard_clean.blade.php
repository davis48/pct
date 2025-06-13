@extends('layouts.citizen-navbar')

@section('title', 'Mon Espace Citoyen | PCT UVCI')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }
    
    .quick-action-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    
    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: inherit;
    }
    
    .gradient-bg-blue {
        background: linear-gradient(135deg, #1976d2, #1565c0);
    }
    
    .gradient-bg-green {
        background: linear-gradient(135deg, #43a047, #388e3c);
    }
    
    .gradient-bg-purple {
        background: linear-gradient(135deg, #7b1fa2, #6a1b9a);
    }
    
    .gradient-bg-orange {
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de bienvenue -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Bonjour {{ Auth::user()->prenoms }} {{ Auth::user()->nom }} 👋
            </h1>
            <p class="text-gray-600">Bienvenue dans votre espace personnel PCT UVCI</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total des demandes -->
            <div class="stat-card p-6">
                <div class="flex items-center">
                    <div class="stat-icon gradient-bg-blue">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total demandes</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Request::where('user_id', Auth::id())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Demandes en attente -->
            <div class="stat-card p-6">
                <div class="flex items-center">
                    <div class="stat-icon gradient-bg-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Request::where('user_id', Auth::id())->where('status', 'en_attente')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Demandes approuvées -->
            <div class="stat-card p-6">
                <div class="flex items-center">
                    <div class="stat-icon gradient-bg-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approuvées</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Request::where('user_id', Auth::id())->where('status', 'approved')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notifications non lues -->
            <div class="stat-card p-6">
                <div class="flex items-center">
                    <div class="stat-icon gradient-bg-purple">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Notifications</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Nouvelle demande -->
                <a href="{{ route('requests.create') }}" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-blue">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Nouvelle demande</h3>
                            <p class="text-sm text-gray-600">Créer une nouvelle demande de document</p>
                        </div>
                    </div>
                </a>

                <!-- Formulaires interactifs -->
                <a href="{{ route('interactive-forms.index') }}" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-green">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Formulaires</h3>
                            <p class="text-sm text-gray-600">Accéder aux formulaires interactifs</p>
                        </div>
                    </div>
                </a>

                <!-- Mes demandes -->
                <a href="{{ route('requests.index') }}" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-purple">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Mes demandes</h3>
                            <p class="text-sm text-gray-600">Suivre l'état de mes demandes</p>
                        </div>
                    </div>
                </a>

                <!-- Profil -->
                <a href="{{ route('profile.edit') }}" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-orange">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Mon profil</h3>
                            <p class="text-sm text-gray-600">Modifier mes informations</p>
                        </div>
                    </div>
                </a>

                <!-- Notifications -->
                <a href="{{ route('notifications.index') }}" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-blue">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                            <p class="text-sm text-gray-600">Voir toutes mes notifications</p>
                        </div>
                    </div>
                </a>

                <!-- Aide -->
                <a href="#" class="quick-action-card p-6">
                    <div class="flex items-center">
                        <div class="stat-icon gradient-bg-green">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Aide</h3>
                            <p class="text-sm text-gray-600">Centre d'aide et FAQ</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Demandes récentes -->
        <div class="bg-white rounded-12 p-6 shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Demandes récentes</h2>
                <a href="{{ route('requests.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                $recentRequests = \App\Models\Request::where('user_id', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp

            @if($recentRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRequests as $request)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $request->document_type }}</h4>
                                    <p class="text-sm text-gray-500">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($request->status == 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                                <a href="{{ route('citizen.request.standalone.show', $request) }}" 
                                   class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande</h3>
                    <p class="text-gray-500 mb-4">Vous n'avez pas encore créé de demande</p>
                    <a href="{{ route('requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Créer ma première demande
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
