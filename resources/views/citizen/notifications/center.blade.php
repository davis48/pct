@extends('layouts.front.app')

@section('title', 'Centre de Notifications')

@push('styles')
<style>
    .notification-center {
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .notification-item {
        transition: all 0.3s ease;
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }
    
    .notification-item:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .notification-unread {
        border-left: 4px solid #0d6efd;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    }
    
    .notification-read {
        opacity: 0.85;
        border-left: 4px solid #6c757d;
        background: #f8f9fa;
    }
    
    .filter-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .search-box {
        border-radius: 25px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .search-box:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .action-btn {
        border-radius: 20px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    
    .bulk-actions {
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
        border-radius: 10px;
        color: white;
    }
    
    .notification-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .notification-content {
        line-height: 1.6;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        opacity: 0.3;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    /* Styles pour l'aperçu enrichi des notifications */
    .notification-preview-item {
        background: #f8f9fa;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }
    
    .notification-preview-item:hover {
        background: #e9ecef;
    }
    
    .notification-item .notification-content .small {
        line-height: 1.2;
    }
    
    .notification-item .notification-content .row > .col-sm-6,
    .notification-item .notification-content .row > .col-lg-4 {
        margin-bottom: 0.5rem;
    }
    
    /* Amélioration des badges de priorité */
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white;
    }
    
    /* Animation pour les notifications non lues */
    .notification-unread {
        animation: subtle-pulse 2s infinite;
    }
    
    @keyframes subtle-pulse {
        0%, 100% { 
            border-left-color: #0d6efd; 
        }
        50% { 
            border-left-color: #0a58ca; 
        }
    }
    
    /* Amélioration responsive */
    @media (max-width: 768px) {
        .notification-item .d-flex.flex-wrap.gap-2 {
            flex-direction: column;
        }
        
        .notification-item .notification-content .row > div {
            margin-bottom: 0.25rem;
        }
        
        .notification-meta {
            font-size: 0.75rem;
        }
    }
    
    /* Styles pour les notifications détaillées */
    .notification-item {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #0d6efd;
    }
    
    .notification-unread {
        border-left: 4px solid #0d6efd;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    }
    
    .notification-read {
        opacity: 0.85;
        border-left: 4px solid #6c757d;
        background: #f8f9fa;
    }
    
    .notification-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .notification-title {
        color: #1d2b36;
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .notification-message {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #495057;
    }
    
    .notification-expand-icon {
        transition: transform 0.3s ease;
        font-size: 0.9rem;
    }
    
    .notification-expand-icon.expanded {
        transform: rotate(180deg);
    }
    
    .notification-details {
        border-top: 1px solid #e9ecef;
        background: rgba(248, 249, 250, 0.5);
        border-radius: 0 0 8px 8px;
        margin: -1rem -1rem 0 -1rem;
        padding: 1rem;
    }
    
    .notification-details h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .notification-details p {
        font-size: 0.85rem;
        line-height: 1.5;
    }
    
    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            max-height: 1000px;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            max-height: 1000px;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
    }
    
    .notification-details.show {
        animation: slideDown 0.3s ease-out;
    }
    
    .notification-details.hide {
        animation: slideUp 0.3s ease-out;
    }
    
    /* Badges et indicateurs */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .notification-item .badge.bg-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
    }
    
    .notification-item .badge.bg-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .notification-item {
            margin-bottom: 1rem;
        }
        
        .notification-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .notification-title {
            font-size: 1rem;
        }
        
        .notification-message {
            font-size: 0.9rem;
        }
        
        .notification-details .row > div {
            margin-bottom: 1rem;
        }
    }
    
    /* États de notification spéciaux */
    .notification-item[data-priority="high"] {
        border-left-color: #dc3545;
        background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
    }
    
    .notification-item[data-priority="high"]:hover {
        border-color: #dc3545;
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.15);
    }
    
    /* Boutons d'action */
    .notification-details .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .notification-details .btn:hover {
        transform: translateY(-1px);
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        opacity: 0.3;
    }
    
    /* Alertes dans les détails */
    .notification-details .alert {
        font-size: 0.85rem;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border-radius: 6px;
    }
    
    .notification-details .alert h6 {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    /* Loading state */
    .notification-item.loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .notification-item.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endpush

@section('content')
<div class="notification-center">
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1">Centre de Notifications</h1>
                        <p class="text-muted mb-0">Gérez toutes vos notifications en un seul endroit</p>
                    </div>
                    <div>
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Total</h6>
                                <h3 class="mb-0 text-primary">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-bell text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Non lues</h6>
                                <h3 class="mb-0 text-warning">{{ $stats['unread'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-exclamation-circle text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Aujourd'hui</h6>
                                <h3 class="mb-0 text-success">{{ $stats['today'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-calendar-day text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-1">Cette semaine</h6>
                                <h3 class="mb-0 text-info">{{ $stats['this_week'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-calendar-week text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Filtres et recherche -->
            <div class="col-lg-3 mb-4">
                <div class="filter-card p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-filter me-2"></i>Filtres
                    </h5>
                    
                    <form id="filterForm">
                        <!-- Recherche -->
                        <div class="mb-3">
                            <label class="form-label">Rechercher</label>
                            <input type="text" class="form-control search-box" name="search" 
                                   placeholder="Rechercher dans les notifications..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-select" name="status">
                                <option value="">Tous</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues</option>
                            </select>
                        </div>

                        <!-- Période -->
                        <div class="mb-3">
                            <label class="form-label">Période</label>
                            <select class="form-select" name="period">
                                <option value="">Toutes</option>
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                            </select>
                        </div>

                        <!-- Date personnalisée -->
                        <div class="mb-3">
                            <label class="form-label">Du</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Au</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary action-btn">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('citizen.notifications.center') }}" class="btn btn-outline-secondary action-btn">
                                <i class="fas fa-times me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="col-lg-9">
                <!-- Actions en masse -->
                @if($notifications->count() > 0)
                <div class="bulk-actions p-3 mb-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="me-3">
                            <strong>Actions en masse :</strong>
                        </span>
                        <button class="btn btn-light btn-sm action-btn" onclick="markAllAsRead()" 
                                {{ $stats['unread'] == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle me-1"></i>Marquer tout comme lu
                        </button>
                        <button class="btn btn-light btn-sm action-btn" onclick="deleteReadNotifications()" 
                                {{ $stats['read'] == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash me-1"></i>Supprimer les lues
                        </button>
                        <button class="btn btn-danger btn-sm action-btn" onclick="deleteAllNotifications()">
                            <i class="fas fa-trash-alt me-1"></i>Tout supprimer
                        </button>
                    </div>
                </div>
                @endif

                <!-- Liste des notifications -->
                <div id="notificationsList">
                    @include('citizen.notifications.partials.list')
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Confirmer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit du formulaire de filtre
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('input, select');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.type !== 'text') {
                filterForm.submit();
            }
        });
    });

    // Recherche avec délai
    const searchInput = filterForm.querySelector('input[name="search"]');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
});

// Fonction pour développer/réduire les notifications
function toggleNotificationDetails(notificationId) {
    const detailsDiv = document.getElementById('details-' + notificationId);
    const expandIcon = document.querySelector(`[data-notification-id="${notificationId}"] .notification-expand-icon`);
    
    if (detailsDiv.style.display === 'none') {
        // Développer
        detailsDiv.style.display = 'block';
        detailsDiv.style.animation = 'slideDown 0.3s ease-out';
        expandIcon.classList.remove('fa-chevron-down');
        expandIcon.classList.add('fa-chevron-up');
        
        // Marquer automatiquement comme lu si pas encore lu
        const notificationCard = document.querySelector(`[data-notification-id="${notificationId}"]`);
        if (notificationCard.classList.contains('notification-unread')) {
            markAsRead(notificationId);
        }
    } else {
        // Réduire
        detailsDiv.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            detailsDiv.style.display = 'none';
        }, 300);
        expandIcon.classList.remove('fa-chevron-up');
        expandIcon.classList.add('fa-chevron-down');
    }
}

// Marquer une notification comme lue
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`{{ route('citizen.notifications.read', '') }}/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            // Mettre à jour l'interface
            const notificationCard = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationCard) {
                notificationCard.classList.remove('notification-unread');
                notificationCard.classList.add('notification-read');
                
                // Supprimer le badge "Nouveau"
                const newBadge = notificationCard.querySelector('.badge');
                if (newBadge && newBadge.textContent === 'Nouveau') {
                    newBadge.remove();
                }
                
                // Supprimer le bouton "Marquer comme lue"
                const markReadBtn = notificationCard.querySelector('button[onclick*="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            }
            
            // Mettre à jour les statistiques
            updateNotificationStats();
        }
    } catch (error) {
        console.error('Erreur lors du marquage de la notification:', error);
    }
}

// Supprimer une notification
async function deleteNotification(notificationId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route('citizen.notifications.delete', '') }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            // Supprimer de l'interface avec animation
            const notificationCard = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationCard) {
                notificationCard.style.transition = 'all 0.3s ease';
                notificationCard.style.opacity = '0';
                notificationCard.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    notificationCard.remove();
                    
                    // Vérifier s'il reste des notifications
                    const remainingNotifications = document.querySelectorAll('.notification-item').length;
                    if (remainingNotifications === 0) {
                        // Afficher le message "aucune notification"
                        const container = document.querySelector('.notifications-list-container');
                        if (container) {
                            container.innerHTML = `
                                <div class="empty-state text-center py-5">
                                    <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
                                    <h5 class="text-muted">Aucune notification</h5>
                                    <p class="text-muted">Vous n'avez actuellement aucune notification.</p>
                                </div>
                            `;
                        }
                    }
                    
                    // Mettre à jour les statistiques
                    updateNotificationStats();
                }, 300);
            }
        }
    } catch (error) {
        console.error('Erreur lors de la suppression de la notification:', error);
    }
}

// Mettre à jour les statistiques de notifications
function updateNotificationStats() {
    // Compter les notifications restantes
    const totalNotifications = document.querySelectorAll('.notification-item').length;
    const unreadNotifications = document.querySelectorAll('.notification-unread').length;
    
    // Mettre à jour les cartes de statistiques si elles existent
    const totalCard = document.querySelector('.stats-card h3.text-primary');
    const unreadCard = document.querySelector('.stats-card h3.text-warning');
    
    if (totalCard) {
        totalCard.textContent = totalNotifications;
    }
    
    if (unreadCard) {
        unreadCard.textContent = unreadNotifications;
    }
    
    // Mettre à jour le badge dans l'en-tête si nécessaire
    const headerBadge = document.querySelector('.notification-badge');
    if (headerBadge) {
        if (unreadNotifications > 0) {
            headerBadge.textContent = unreadNotifications > 99 ? '99+' : unreadNotifications;
        } else {
            headerBadge.remove();
        }
    }
}

// Marquer toutes les notifications comme lues
async function markAllAsRead() {
    if (!confirm('Marquer toutes les notifications comme lues ?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("citizen.notifications.read-all") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            // Mettre à jour toutes les notifications
            const unreadNotifications = document.querySelectorAll('.notification-unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('notification-unread');
                notification.classList.add('notification-read');
                
                // Supprimer les badges "Nouveau"
                const badge = notification.querySelector('.badge');
                if (badge && badge.textContent === 'Nouveau') {
                    badge.remove();
                }
                
                // Supprimer les boutons "Marquer comme lue"
                const markReadBtn = notification.querySelector('button[onclick*="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            });
            
            // Mettre à jour les statistiques
            updateNotificationStats();
            
            // Afficher un message de succès
            showSuccessMessage('Toutes les notifications ont été marquées comme lues.');
        }
    } catch (error) {
        console.error('Erreur lors du marquage de toutes les notifications:', error);
    }
}

// Supprimer toutes les notifications lues
async function deleteReadNotifications() {
    if (!confirm('Supprimer toutes les notifications lues ?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("citizen.notifications.delete-read") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            // Supprimer toutes les notifications lues de l'interface
            const readNotifications = document.querySelectorAll('.notification-read');
            readNotifications.forEach(notification => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    notification.remove();
                }, 300);
            });
            
            // Mettre à jour les statistiques après un délai
            setTimeout(() => {
                updateNotificationStats();
                
                // Vérifier s'il reste des notifications
                const remainingNotifications = document.querySelectorAll('.notification-item').length;
                if (remainingNotifications === 0) {
                    const container = document.querySelector('.notifications-list-container');
                    if (container) {
                        container.innerHTML = `
                            <div class="empty-state text-center py-5">
                                <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
                                <h5 class="text-muted">Aucune notification</h5>
                                <p class="text-muted">Vous n'avez actuellement aucune notification.</p>
                            </div>
                        `;
                    }
                }
            }, 400);
            
            showSuccessMessage('Toutes les notifications lues ont été supprimées.');
        }
    } catch (error) {
        console.error('Erreur lors de la suppression des notifications lues:', error);
    }
}

// Afficher un message de succès temporaire
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insérer au début de la page
    const container = document.querySelector('.container-fluid');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// CSS pour les animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            max-height: 500px;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            max-height: 500px;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
    }
    
    .notification-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .notification-expand-icon {
        transition: transform 0.3s ease;
    }
    
    .notification-unread {
        border-left: 4px solid #0d6efd;
    }
    
    .notification-read {
        opacity: 0.8;
        border-left: 4px solid #6c757d;
    }
`;
document.head.appendChild(style);
</script>
@endpush
