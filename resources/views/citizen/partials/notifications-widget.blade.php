{{-- Widget de notifications compact pour le tableau de bord avec synchronisation --}}
@if($notifications->count() > 0)
<div class="card border-0 shadow-sm notification-compact" id="notification-widget">
    <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold d-flex align-items-center" id="notification-widget-title">
                <i class="fas fa-bell me-2 {{ $unreadNotificationsCount > 0 ? 'notification-bell' : '' }}"></i>
                Dernières Notifications
                @if($unreadNotificationsCount > 3)
                    <span class="badge bg-warning text-dark ms-2 notification-badge-pulse">
                        +{{ $unreadNotificationsCount - 3 }}
                    </span>
                @endif
            </h6>
            <div class="d-flex gap-2">
                <a href="{{ route('citizen.notifications.center') }}" 
                   class="btn notification-center-btn btn-sm"
                   title="Voir toutes les notifications">
                    <i class="fas fa-bell-concierge me-1"></i>
                    Centre
                    @if($unreadNotificationsCount > 3)
                        <span class="badge bg-danger ms-1 text-white">{{ $unreadNotificationsCount - 3 }}+</span>
                    @endif
                </a>
                <button class="btn btn-outline-light btn-sm" 
                        onclick="markAllAsRead()"
                        title="Marquer toutes comme lues">
                    <i class="fas fa-check-double"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="notifications-container">
            @foreach($notifications as $notification)
            <div class="notification-item d-flex align-items-center p-3 border-bottom" 
                 data-id="{{ $notification->id }}">
                <div class="notification-icon me-3">
                    @php
                        $iconClass = 'fas fa-info-circle';
                        $bgClass = 'bg-primary';
                        
                        // Déterminer l'icône et la couleur selon le type de notification
                        if(isset($notification->data['type'])) {
                            switch($notification->data['type']) {
                                case 'status_change':
                                    $iconClass = 'fas fa-sync';
                                    $bgClass = 'bg-info';
                                    break;
                                case 'assignment':
                                    $iconClass = 'fas fa-user-check';
                                    $bgClass = 'bg-success';
                                    break;
                                case 'payment':
                                    $iconClass = 'fas fa-credit-card';
                                    $bgClass = 'bg-warning';
                                    break;
                                case 'document_ready':
                                    $iconClass = 'fas fa-file-check';
                                    $bgClass = 'bg-success';
                                    break;
                                default:
                                    $iconClass = 'fas fa-bell';
                                    break;
                            }
                        }
                    @endphp
                    <i class="{{ $iconClass }}"></i>
                </div>
                <div class="notification-content flex-grow-1">
                    <h6 class="mb-1">{{ $notification->title ?? 'Notification' }}</h6>
                    <p class="text-muted mb-1">
                        {{ Str::limit($notification->message ?? $notification->data['message'] ?? 'Nouvelle notification', 65) }}
                    </p>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-clock me-1"></i>
                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                        @if(isset($notification->data['request_id']))
                            <span class="mx-2">•</span>
                            <i class="fas fa-hashtag me-1"></i>
                            <span>{{ $notification->data['request_id'] }}</span>
                        @endif
                    </div>
                </div>
                <div class="notification-actions d-flex gap-1">
                    @if(isset($notification->data['request_id']))
                        <a href="{{ route('citizen.request.show', $notification->data['request_id']) }}" 
                           class="btn btn-outline-primary btn-sm" 
                           title="Voir la demande">
                            <i class="fas fa-eye"></i>
                        </a>
                    @endif
                    <button class="btn btn-outline-success btn-sm" 
                            onclick="markAsRead({{ $notification->id }})"
                            title="Marquer comme lue">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($unreadNotificationsCount > 3)
        <div class="text-center p-3 bg-light border-top">
            <a href="{{ route('citizen.notifications.center') }}" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus me-1"></i>
                Voir {{ $unreadNotificationsCount - 3 }} notifications supplémentaires
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Script de synchronisation pour le widget -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Écouter les événements de synchronisation des notifications
    document.addEventListener('notificationsUpdated', function(event) {
        console.log('📱 Widget: Notifications mises à jour');
        updateWidgetDisplay(event.detail);
    });
    
    document.addEventListener('notificationRead', function(event) {
        console.log('📱 Widget: Notification marquée comme lue');
        hideNotification(event.detail.notificationId);
    });
    
    document.addEventListener('allNotificationsRead', function(event) {
        console.log('📱 Widget: Toutes les notifications marquées comme lues');
        hideAllNotifications();
    });
});

function updateWidgetDisplay(data) {
    const widget = document.getElementById('notification-widget');
    const title = document.getElementById('notification-widget-title');
    
    // Mettre à jour les badges du centre de notifications dans le widget
    const widgetCenterBadges = widget ? widget.querySelectorAll('.badge.bg-danger, .badge.bg-warning') : [];
    widgetCenterBadges.forEach(badge => {
        if (data.count > 0) {
            badge.textContent = data.count > 99 ? '99+' : data.count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
    
    if (data.count === 0) {
        // Masquer le widget s'il n'y a plus de notifications
        if (widget) {
            widget.style.transition = 'opacity 0.3s ease';
            widget.style.opacity = '0';
            setTimeout(() => {
                widget.style.display = 'none';
            }, 300);
        }
    } else {
        // Afficher le widget s'il est masqué
        if (widget && widget.style.display === 'none') {
            widget.style.display = 'block';
            widget.style.opacity = '1';
        }
        
        // Mettre à jour le titre
        if (title) {
            const bellIcon = data.count > 0 ? 'notification-bell' : '';
            const unreadCount = data.count;
            const displayedCount = Math.min(unreadCount, 3);
            const additionalCount = Math.max(0, unreadCount - 3);
            
            // Rebuild the title with correct structure
            title.innerHTML = `
                <i class="fas fa-bell me-2 ${bellIcon}"></i>
                Dernières Notifications
                ${additionalCount > 0 ? `<span class="badge bg-warning text-dark ms-2 notification-badge-pulse">+${additionalCount}</span>` : ''}
            `;
        }
    }
}

function hideNotification(notificationId) {
    const notification = document.querySelector(`#notification-widget [data-id="${notificationId}"]`);
    if (notification) {
        notification.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(20px)';
        setTimeout(() => {
            notification.remove();
            
            // Vérifier s'il reste des notifications
            const remainingNotifications = document.querySelectorAll('#notification-widget .notification-item').length;
            if (remainingNotifications === 0) {
                hideAllNotifications();
            }
        }, 300);
    }
}

function hideAllNotifications() {
    const widget = document.getElementById('notification-widget');
    if (widget) {
        widget.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        widget.style.opacity = '0';
        widget.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            widget.style.display = 'none';
        }, 500);
    }
}
</script>

@elseif(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
{{-- Afficher un message si toutes les notifications sont dans le centre --}}
<div class="card border-0 shadow-sm notification-compact">
    <div class="card-body text-center py-4">
        <div class="mb-3">
            <i class="fas fa-bell text-primary fa-2x mb-2"></i>
            <h6 class="text-muted">Vous avez {{ $unreadNotificationsCount }} notification{{ $unreadNotificationsCount > 1 ? 's' : '' }}</h6>
        </div>
        <a href="{{ route('citizen.notifications.center') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-bell-concierge me-1"></i>
            Voir toutes les notifications
        </a>
    </div>
</div>
@endif
