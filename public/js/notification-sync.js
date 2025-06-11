/**
 * Système de synchronisation global des notifications
 * Assure la cohérence entre la navbar, le sidebar et tous les composants de notification
 */

class NotificationSync {
    constructor() {
        this.isInitialized = false;
        this.refreshInterval = null;
        this.lastNotificationCount = 0;
        
        this.init();
    }
    
    init() {
        if (this.isInitialized) return;
        
        // Attendre que le DOM soit prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }
    
    setup() {
        console.log('🔔 Initialisation du système de synchronisation des notifications');
        
        // Configuration CSRF pour tous les appels AJAX
        this.setupCSRF();
        
        // Première synchronisation
        this.syncAll();
        
        // Auto-refresh toutes les 30 secondes
        this.startAutoRefresh();
        
        // Écouter les événements de changement de focus pour rafraîchir
        this.setupFocusHandlers();
        
        this.isInitialized = true;
    }
    
    setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            // Configuration globale pour jQuery si disponible
            if (window.$ && $.ajaxSetup) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token.getAttribute('content')
                    }
                });
            }
        }
    }
    
    setupFocusHandlers() {
        // Rafraîchir quand l'utilisateur revient sur la page
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                console.log('🔔 Page redevenue visible, synchronisation des notifications');
                this.syncAll();
            }
        });
        
        window.addEventListener('focus', () => {
            console.log('🔔 Fenêtre reçu le focus, synchronisation des notifications');
            this.syncAll();
        });
    }
    
    startAutoRefresh() {
        // Nettoyer l'ancien interval si il existe
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        // Nouveau rafraîchissement toutes les 30 secondes
        this.refreshInterval = setInterval(() => {
            console.log('🔔 Rafraîchissement automatique des notifications');
            this.syncAll();
        }, 30000);
    }
    
    async syncAll() {
        try {
            // Récupérer les dernières données de notification
            const data = await this.fetchNotificationData();
            
            // Mettre à jour tous les composants
            this.updateBadge(data.count);
            this.updateDropdown(data.notifications);
            this.updateSidebar(data.notifications);
            
            // Vérifier si il y a de nouvelles notifications
            this.checkForNewNotifications(data.count);
            
        } catch (error) {
            console.error('🔔 Erreur lors de la synchronisation:', error);
        }
    }
      async fetchNotificationData() {
        // Utiliser l'URL générée par Laravel
        const url = window.location.origin + '/citizen/notifications/ajax';
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return await response.json();
    }
      updateBadge(count) {
        // Chercher les différents types de badges de notification
        const badges = [
            document.querySelector('.notification-badge'),
            document.getElementById('notification-badge'),
            document.getElementById('notification-count'),
            document.querySelector('.badge.bg-danger'),
            document.querySelector('.bg-red-500')
        ].filter(Boolean);
        
        const notificationToggles = [
            document.getElementById('notificationToggle'),
            document.getElementById('notifications-btn'),
            document.getElementById('notifications-btn')
        ].filter(Boolean);
        
        if (count > 0) {
            badges.forEach(badge => {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
                if (badge.classList.contains('hidden')) {
                    badge.classList.remove('hidden');
                }
            });
            
            // Créer un badge s'il n'existe pas
            if (badges.length === 0 && notificationToggles.length > 0) {
                notificationToggles.forEach(toggle => {
                    const existingBadge = toggle.querySelector('.notification-badge, .badge, .bg-red-500');
                    if (!existingBadge) {
                        const newBadge = document.createElement('span');
                        
                        // Détecter le framework et appliquer les bonnes classes
                        if (document.querySelector('.bootstrap')) {
                            // Bootstrap
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                        } else {
                            // Tailwind/moderne
                            newBadge.className = 'notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center';
                        }
                        
                        newBadge.textContent = count > 99 ? '99+' : count;
                        toggle.appendChild(newBadge);
                    }
                });
            }
        } else {
            badges.forEach(badge => {
                badge.style.display = 'none';
                if (!badge.classList.contains('hidden')) {
                    badge.classList.add('hidden');
                }
            });
        }
    }
      updateDropdown(notifications) {
        // Chercher les différents types de conteneurs de notifications
        const notificationsList = document.getElementById('notificationsList') || 
                                 document.getElementById('notifications-list') ||
                                 document.getElementById('notificationDropdown');
        
        if (!notificationsList) return;
        
        if (notifications && notifications.length > 0) {
            const notificationsHTML = notifications.map(notification => {
                // Adapter le format selon le framework CSS utilisé
                if (document.querySelector('.dropdown-menu')) {
                    // Format Bootstrap
                    return `
                        <div class="dropdown-item p-3 border-bottom notification-item" data-id="${notification.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark">${this.escapeHtml(notification.title)}</h6>
                                    <p class="mb-1 text-muted small">${this.escapeHtml(notification.message)}</p>
                                    <small class="text-muted">${notification.time_ago}</small>
                                </div>
                                <button onclick="notificationSync.markAsRead('${notification.id}')" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                } else {
                    // Format Tailwind/moderne
                    return `
                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 notification-item" data-id="${notification.id}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">${this.escapeHtml(notification.title)}</div>
                                    <div class="text-xs text-gray-500 mt-1">${this.escapeHtml(notification.message)}</div>
                                    <div class="text-xs text-gray-400 mt-1">${notification.time_ago}</div>
                                </div>
                                <button onclick="notificationSync.markAsRead('${notification.id}')" class="text-blue-600 hover:text-blue-800 text-xs ml-2">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            }).join('');
            
            notificationsList.innerHTML = notificationsHTML;
        } else {
            // Message vide adapté au framework
            if (document.querySelector('.dropdown-menu')) {
                // Format Bootstrap
                notificationsList.innerHTML = `
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-bell-slash mb-2 d-block"></i>
                        Aucune notification
                    </div>
                `;
            } else {
                // Format Tailwind/moderne
                notificationsList.innerHTML = `
                    <div class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                        <p>Aucune notification</p>
                    </div>
                `;
            }
        }
    }
    
    updateSidebar(notifications) {
        // Mettre à jour le widget de notification dans le sidebar s'il existe
        const notificationWidget = document.getElementById('notifications-container');
        if (notificationWidget && notifications) {
            // Déclencher un événement personnalisé pour que d'autres composants puissent réagir
            const event = new CustomEvent('notificationsUpdated', {
                detail: { notifications, count: notifications.length }
            });
            document.dispatchEvent(event);
        }
    }
    
    checkForNewNotifications(currentCount) {
        if (this.lastNotificationCount < currentCount && this.lastNotificationCount > 0) {
            console.log('🔔 Nouvelles notifications détectées');
            // Optionnel: jouer un son ou afficher une animation
            this.animateBell();
        }
        this.lastNotificationCount = currentCount;
    }
    
    animateBell() {
        const bellIcon = document.querySelector('#notificationToggle i');
        if (bellIcon) {
            bellIcon.style.animation = 'none';
            setTimeout(() => {
                bellIcon.style.animation = 'bell-ring 0.5s ease-in-out 3';
            }, 10);
        }
    }
      async markAsRead(notificationId) {
        try {
            const url = window.location.origin + `/citizen/notifications/${notificationId}/read`;
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('🔔 Notification marquée comme lue:', notificationId);
                
                // Supprimer visuellement la notification
                const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.remove();
                }
                
                // Re-synchroniser tout
                await this.syncAll();
                
                // Déclencher un événement pour les autres composants
                const event = new CustomEvent('notificationRead', { 
                    detail: { notificationId } 
                });
                document.dispatchEvent(event);
                
            } else {
                console.error('🔔 Erreur lors du marquage de la notification:', data);
            }
        } catch (error) {
            console.error('🔔 Erreur lors du marquage de la notification:', error);
        }
    }
      async markAllAsRead() {
        try {
            const url = window.location.origin + '/citizen/notifications/read-all';
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('🔔 Toutes les notifications marquées comme lues');
                
                // Re-synchroniser tout
                await this.syncAll();
                
                // Déclencher un événement pour les autres composants
                const event = new CustomEvent('allNotificationsRead');
                document.dispatchEvent(event);
                
            } else {
                console.error('🔔 Erreur lors du marquage de toutes les notifications:', data);
            }
        } catch (error) {
            console.error('🔔 Erreur lors du marquage de toutes les notifications:', error);
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        this.isInitialized = false;
    }
}

// CSS pour l'animation de la cloche
if (!document.getElementById('notification-bell-animation')) {
    const style = document.createElement('style');
    style.id = 'notification-bell-animation';
    style.textContent = `
        @keyframes bell-ring {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(10deg); }
            20%, 40%, 60%, 80% { transform: rotate(-10deg); }
        }
    `;
    document.head.appendChild(style);
}

// Initialiser le système global
window.notificationSync = new NotificationSync();

// Compatibilité avec les anciennes fonctions
window.markNotificationAsRead = (id) => window.notificationSync.markAsRead(id);
window.markAllAsRead = () => window.notificationSync.markAllAsRead();
window.updateNotificationBadge = () => window.notificationSync.syncAll();
