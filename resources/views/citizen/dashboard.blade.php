@extends('layouts.front.app')

@section('title', 'Mon Espace Citoyen')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mon Espace Citoyen</h1>
            <p class="mt-2 text-gray-600">Suivez l'état de vos demandes en temps réel</p>
        </div>

        <!-- Notifications -->
        @if($notifications->count() > 0)
        <div class="mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-blue-900">
                        <i class="fas fa-bell mr-2"></i>
                        Notifications récentes ({{ $notifications->count() }})
                    </h3>
                    <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm">
                        Tout marquer comme lu
                    </button>
                </div>
                <div id="notifications-container" class="space-y-3">
                    @foreach($notifications as $notification)
                    <div class="notification-item bg-white border border-gray-200 rounded-lg p-3 {{ $notification->color_class }}" data-id="{{ $notification->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="{{ $notification->icon }} mr-2"></i>
                                    <h4 class="font-semibold text-gray-900">{{ $notification->title }}</h4>
                                </div>
                                <p class="text-gray-700 mt-1">{{ $notification->message }}</p>
                                <span class="text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <button onclick="markAsRead({{ $notification->id }})" class="ml-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total des demandes</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-2xl text-yellow-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-cog text-2xl text-blue-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">En cours</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['in_progress_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-2xl text-green-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approuvées</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['approved_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-2xl text-red-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Rejetées</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['rejected_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Mes demandes</h3>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-500">Mise à jour automatique</span>
                        <div id="auto-refresh-indicator" class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
            
            <div id="requests-container">
                @if($requests->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <li class="request-item" data-id="{{ $request->id }}">
                        <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @switch($request->status)
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> En attente
                                            </span>
                                            @break
                                        @case('in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-cog mr-1"></i> En cours
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Approuvé
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i> Rejeté
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->document->name ?? 'Document non spécifié' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Soumis le {{ $request->created_at->format('d/m/Y à H:i') }}
                                        @if($request->updated_at != $request->created_at)
                                            • Mis à jour le {{ $request->updated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </div>
                                    @if($request->assignedAgent)
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            Agent assigné: {{ $request->assignedAgent->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($request->status === 'approved')
                                    <button class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>
                                        Télécharger
                                    </button>
                                @endif
                                <a href="{{ route('citizen.request.show', $request->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>
                                    Détails
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par soumettre votre première demande.</p>
                    <div class="mt-6">
                        <a href="{{ route('requests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle demande
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Rafraîchissement automatique des données
let refreshInterval;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        refreshRequestsData();
        refreshNotifications();
    }, 30000); // Rafraîchir toutes les 30 secondes
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

// Rafraîchir les données des demandes
async function refreshRequestsData() {
    try {
        const response = await fetch('{{ route("citizen.requests.updates") }}');
        const data = await response.json();
        
        if (data.requests) {
            updateRequestsDisplay(data.requests);
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des demandes:', error);
    }
}

// Rafraîchir les notifications
async function refreshNotifications() {
    try {
        const response = await fetch('{{ route("citizen.notifications") }}');
        const data = await response.json();
        
        if (data.notifications && data.notifications.length > 0) {
            updateNotificationsDisplay(data.notifications);
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des notifications:', error);
    }
}

// Mettre à jour l'affichage des demandes
function updateRequestsDisplay(requests) {
    // Logique pour mettre à jour l'affichage des demandes
    // Animation de l'indicateur de mise à jour
    const indicator = document.getElementById('auto-refresh-indicator');
    indicator.classList.add('animate-ping');
    setTimeout(() => {
        indicator.classList.remove('animate-ping');
    }, 1000);
}

// Mettre à jour l'affichage des notifications
function updateNotificationsDisplay(notifications) {
    // Logique pour mettre à jour l'affichage des notifications
    console.log('Nouvelles notifications:', notifications);
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
                notificationsContainer.style.opacity = '0.5';
                setTimeout(() => {
                    notificationsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Aucune nouvelle notification</p>';
                }, 300);
            }
        }
    } catch (error) {
        console.error('Erreur lors du marquage des notifications:', error);
    }
}

// Démarrer le rafraîchissement automatique au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
});

// Arrêter le rafraîchissement quand l'utilisateur quitte la page
window.addEventListener('beforeunload', function() {
    stopAutoRefresh();
});

// Pausé le rafraîchissement quand l'onglet n'est pas visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
});
</script>
@endpush
@endsection
