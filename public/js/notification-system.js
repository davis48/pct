// Notification.js - Système de notification moderne pour l'interface citoyen

class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.lastUpdate = new Date();
        this.soundEnabled = localStorage.getItem('notification_sound') !== 'disabled';
        this.desktopEnabled = localStorage.getItem('desktop_notifications') !== 'disabled';
        this.hasPermission = false;
        
        // Vérifier si les notifications de bureau sont supportées
        if ('Notification' in window) {
            this.checkPermissions();
        }
        
        // Charger le son de notification
        this.notificationSound = new Audio('/audio/notification.mp3');
    }
    
    // Vérifier les permissions pour les notifications de bureau
    async checkPermissions() {
        if (Notification.permission === 'granted') {
            this.hasPermission = true;
        } else if (Notification.permission !== 'denied') {
            const permission = await Notification.requestPermission();
            this.hasPermission = permission === 'granted';
        }
    }
    
    // Demander la permission pour les notifications de bureau
    async requestPermission() {
        if (!('Notification' in window)) {
            console.log('Ce navigateur ne prend pas en charge les notifications de bureau');
            return false;
        }
        
        const permission = await Notification.requestPermission();
        this.hasPermission = permission === 'granted';
        
        if (this.hasPermission) {
            localStorage.setItem('desktop_notifications', 'enabled');
            this.desktopEnabled = true;
        }
        
        return this.hasPermission;
    }
    
    // Activer/désactiver le son des notifications
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('notification_sound', this.soundEnabled ? 'enabled' : 'disabled');
    }
    
    // Activer/désactiver les notifications de bureau
    toggleDesktopNotifications() {
        if (!this.desktopEnabled) {
            this.requestPermission();
        } else {
            this.desktopEnabled = false;
            localStorage.setItem('desktop_notifications', 'disabled');
        }
    }
    
    // Jouer le son de notification
    playSound() {
        if (this.soundEnabled) {
            this.notificationSound.play().catch(e => console.log('Erreur de lecture audio:', e));
        }
    }
    
    // Montrer une notification de bureau
    showDesktopNotification(title, message, icon = '/images/logo.png') {
        if (this.desktopEnabled && this.hasPermission) {
            const notification = new Notification(title, {
                body: message,
                icon: icon
            });
            
            notification.onclick = function() {
                window.focus();
                notification.close();
            };
            
            setTimeout(() => notification.close(), 10000);
        }
    }
    
    // Traiter les nouvelles notifications
    processNewNotifications(notifications) {
        if (!notifications || !notifications.length) return;
        
        // Vérifier s'il y a de nouvelles notifications
        const newNotifications = notifications.filter(notification => {
            return !this.notifications.some(n => n.id === notification.id);
        });
        
        if (newNotifications.length > 0) {
            // Mettre à jour la liste des notifications
            this.notifications = [...newNotifications, ...this.notifications];
            this.unreadCount += newNotifications.length;
            
            // Afficher la notification la plus récente (la première du tableau)
            const latestNotification = newNotifications[0];
            
            // Jouer le son de notification
            this.playSound();
            
            // Afficher la notification de bureau
            this.showDesktopNotification(
                latestNotification.title, 
                latestNotification.message
            );
            
            // Afficher une notification toast
            this.showToast(latestNotification.message, latestNotification.type);
            
            // Mettre à jour l'interface utilisateur
            this.updateUI();
        }
    }
    
    // Mettre à jour l'interface utilisateur
    updateUI() {
        // Mettre à jour le compteur de notifications
        const countElement = document.getElementById('notification-count');
        if (countElement) {
            countElement.textContent = this.unreadCount;
            countElement.style.display = this.unreadCount > 0 ? 'inline-flex' : 'none';
        }
        
        // Mettre à jour la liste des notifications dans le menu
        this.renderNotifications();
    }
    
    // Afficher les notifications dans l'interface
    renderNotifications() {
        const container = document.getElementById('notifications-container');
        if (!container) return;
        
        if (this.notifications.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-bell-slash me-2"></i>Aucune notification</div>';
            return;
        }
        
        const html = this.notifications.map(notification => `
            <div class="notification-item border rounded-3 p-3 mb-3" data-id="${notification.id}">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-${this.getIconForType(notification.type)} text-${this.getColorForType(notification.type)} me-2"></i>
                            <h6 class="mb-0">${notification.title}</h6>
                        </div>
                        <p class="text-muted mb-1 small">${notification.message}</p>
                        <span class="badge bg-light text-dark">${notification.created_at}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="notificationSystem.markAsRead(${notification.id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `).join('');
        
        container.innerHTML = html;
    }
    
    // Obtenir l'icône en fonction du type de notification
    getIconForType(type) {
        const icons = {
            'success': 'check-circle',
            'error': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        
        return icons[type] || 'bell';
    }
    
    // Obtenir la couleur en fonction du type de notification
    getColorForType(type) {
        const colors = {
            'success': 'success',
            'error': 'danger',
            'warning': 'warning',
            'info': 'primary'
        };
        
        return colors[type] || 'primary';
    }
    
    // Marquer une notification comme lue
    async markAsRead(notificationId) {
        try {
            // Appel à l'API pour marquer la notification comme lue
            const response = await fetch(`/citizen/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Mettre à jour localement
                const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.style.transition = 'opacity 0.3s ease';
                    notificationElement.style.opacity = '0.5';
                    setTimeout(() => {
                        notificationElement.remove();
                    }, 300);
                }
                
                // Mettre à jour le compteur
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                this.updateUI();
            }
        } catch (error) {
            console.error('Erreur lors du marquage de la notification:', error);
        }
    }
    
    // Marquer toutes les notifications comme lues
    async markAllAsRead() {
        try {
            const response = await fetch('/citizen/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                // Vider la liste des notifications
                this.notifications = [];
                this.unreadCount = 0;
                
                // Mettre à jour l'interface
                const container = document.getElementById('notifications-container');
                if (container) {
                    container.style.transition = 'opacity 0.3s ease';
                    container.style.opacity = '0.5';
                    setTimeout(() => {
                        container.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-check-circle me-2"></i>Toutes les notifications ont été marquées comme lues</div>';
                        container.style.opacity = '1';
                    }, 300);
                }
                
                this.updateUI();
            }
        } catch (error) {
            console.error('Erreur lors du marquage des notifications:', error);
        }
    }
    
    // Afficher une notification toast
    showToast(message, type = 'info') {
        // Créer le conteneur de toast s'il n'existe pas
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Créer le toast
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast show bg-${this.getColorForType(type)} text-white`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="toast-header bg-${this.getColorForType(type)} text-white">
                <i class="fas fa-${this.getIconForType(type)} me-2"></i>
                <strong class="me-auto">Notification</strong>
                <small>à l'instant</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        // Ajouter le toast au conteneur
        toastContainer.appendChild(toast);
        
        // Supprimer le toast après 5 secondes
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
    
    // Rafraîchir les notifications depuis le serveur
    async refreshNotifications() {
        try {
            const response = await fetch('/citizen/notifications');
            if (response.ok) {
                const data = await response.json();
                this.processNewNotifications(data.notifications);
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour des notifications:', error);
        }
    }
}

// Initialiser le système de notification
const notificationSystem = new NotificationSystem();

// Exporter pour une utilisation globale
window.notificationSystem = notificationSystem;
