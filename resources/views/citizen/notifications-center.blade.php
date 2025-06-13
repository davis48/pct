@extends('layouts.modern')

@section('title', 'Centre de Notifications')

@push('styles')
<style>
    /* Navbar bleu spécifique pour le centre de notifications */
    .navbar-glass {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%) !important;
        border-bottom: 1px solid rgba(25, 118, 210, 0.3) !important;
    }
    
    /* Liens de navigation en blanc */
    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 6px !important;
        transform: translateY(-1px) !important;
    }
    
    /* Logo en blanc */
    .logo-container span {
        color: white !important;
    }
    
    .logo-icon {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    /* Menu mobile en blanc */
    .mobile-menu-item {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    .mobile-menu-item:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Bouton menu mobile */
    #mobile-menu-button {
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    #mobile-menu-button:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 6px !important;
    }
    
    /* Icônes en blanc */
    .nav-link i {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .nav-link:hover i {
        color: white !important;
    }
      /* Dropdown de profil visible sur navbar bleu */
    .dropdown-menu {
        background: white !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        z-index: 10000 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transform: translateY(-10px) !important;
        transition: all 0.3s ease !important;
        pointer-events: none !important;
    }
    
    .dropdown.active .dropdown-menu {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
        pointer-events: auto !important;
        position: absolute !important;
        z-index: 10000 !important;
        background: white !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Force le z-index pour que le dropdown soit au-dessus de tout */
    .dropdown.active .dropdown-menu {
        z-index: 99999 !important;
        position: fixed !important;
        top: auto !important;
        right: 1rem !important;
        margin-top: 0.5rem !important;
    }
    
    /* Debug: couleur de fond pour voir si le dropdown est là */
    .dropdown.active .dropdown-menu {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(5px) !important;
    }
    
    /* Force la visibilité lors du hover aussi */
    .dropdown:hover .dropdown-menu {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
        pointer-events: auto !important;
    }
    
    /* Amélioration de la visibilité du bouton profile sur navbar bleu */
    #profileToggle {
        color: rgba(255, 255, 255, 0.9) !important;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        transition: all 0.3s ease !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    
    #profileToggle:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.2) !important;
        transform: translateY(-1px) !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
    }
    
    /* Avatar dans le bouton profile */
    #profileToggle .rounded-full {
        border: 2px solid rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Debug - montrer le dropdown avec une bordure rouge si visible */
    .dropdown.active .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -1px;
        left: -1px;
        right: -1px;
        bottom: -1px;
        border: 2px solid red;
        z-index: -1;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Centre de Notifications</h1>
                    <p class="text-gray-600 mt-1">Gérez toutes vos notifications en un seul endroit</p>
                </div>                <div class="flex space-x-3">
                    <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Tout marquer lu
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }} bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-id="{{ $notification->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="mr-3">
                                    @switch($notification->type)
                                        @case('success')
                                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                            @break
                                        @case('info')
                                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                            @break
                                        @case('payment')
                                            <i class="fas fa-credit-card text-purple-500 text-xl"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-gray-500 text-xl"></i>
                                    @endswitch
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                @if(!$notification->is_read)
                                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Nouveau</span>
                                @endif
                            </div>
                            <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>                        <div class="flex space-x-2 ml-4">
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50" title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <button onclick="deleteNotification({{ $notification->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
                    <i class="fas fa-bell-slash text-gray-400 text-3xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
                    <p class="text-gray-600">Il n'y a aucune notification à afficher pour le moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(8px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.notification-item.unread {
    border-left: 4px solid #3b82f6;
    background: #fafbff;
}

.notification-item.read {
    opacity: 0.7;
}
</style>

<script>
function markAllAsRead() {
    // Afficher un indicateur de chargement
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
    
    fetch('/citizen/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Marquer toutes les notifications comme lues visuellement
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('unread');
                notification.classList.add('read');
                
                // Supprimer le badge "Nouveau"
                const newBadge = notification.querySelector('.bg-blue-100');
                if (newBadge) {
                    newBadge.remove();
                }
                
                // Supprimer le bouton "Marquer comme lu"
                const markReadBtn = notification.querySelector('button[onclick^="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.parentElement.remove();
                }
            });
            
            // Afficher un message de succès
            showSuccessMessage('Toutes les notifications ont été marquées comme lues !');
        } else {
            showErrorMessage('Erreur lors du marquage des notifications.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showErrorMessage('Une erreur est survenue. Veuillez réessayer.');
    })
    .finally(() => {
        // Restaurer le bouton
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function markAsRead(notificationId) {
    fetch(`/citizen/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (notification) {
                notification.classList.remove('unread');
                notification.classList.add('read');
                
                // Supprimer le badge "Nouveau"
                const newBadge = notification.querySelector('.bg-blue-100');
                if (newBadge) {
                    newBadge.remove();
                }
                
                // Supprimer le bouton "Marquer comme lu"
                const markReadBtn = notification.querySelector('button[onclick^="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.parentElement.remove();
                }
            }
            
            showSuccessMessage('Notification marquée comme lue !');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showErrorMessage('Erreur lors du marquage de la notification.');
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        return;
    }
    
    fetch(`/citizen/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (notification) {
                // Animation de suppression
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
            
            showSuccessMessage('Notification supprimée !');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showErrorMessage('Erreur lors de la suppression de la notification.');
    });
}

function showSuccessMessage(message) {
    showMessage(message, 'success');
}

function showErrorMessage(message) {
    showMessage(message, 'error');
}

function showMessage(message, type) {
    // Créer le message de notification
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        messageDiv.className += ' bg-green-500 text-white';
        messageDiv.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    } else {
        messageDiv.className += ' bg-red-500 text-white';
        messageDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i>${message}`;
    }
    
    document.body.appendChild(messageDiv);
    
    // Animer l'entrée
    setTimeout(() => {
        messageDiv.classList.remove('translate-x-full');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        messageDiv.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(messageDiv);
        }, 300);
    }, 3000);
}
</script>

@endsection
