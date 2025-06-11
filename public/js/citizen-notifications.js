// Système de notification amélioré pour l'interface citoyen
(function() {
    'use strict';

    // Configuration CSRF automatique
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Écouter les événements du système de synchronisation global
    document.addEventListener('notificationsUpdated', function(event) {
        console.log('🔔 Mise à jour des notifications reçue:', event.detail);
        updateNotificationCount();
    });

    document.addEventListener('notificationRead', function(event) {
        console.log('🔔 Notification marquée comme lue:', event.detail.notificationId);
        updateNotificationCount();
    });

    document.addEventListener('allNotificationsRead', function(event) {
        console.log('🔔 Toutes les notifications marquées comme lues');
        updateNotificationCount();
    });

    // Fonction pour marquer une notification comme lue (compatible avec l'ancien système)
    window.markAsRead = function(notificationId) {
        console.log('Tentative de suppression de la notification ID:', notificationId);
        
        // Utiliser le nouveau système de synchronisation si disponible
        if (window.notificationSync) {
            return window.notificationSync.markAsRead(notificationId);
        }
        
        // Fallback vers l'ancienne méthode
        const url = '/citizen/notifications/' + notificationId + '/read';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Réponse du serveur:', response);
                if (response.success) {
                    // Supprimer visuellement la notification
                    const notificationElement = $(`.notification-item[data-id="${notificationId}"]`);
                    notificationElement.fadeOut(300, function() {
                        $(this).remove();
                        updateNotificationCount();
                    });
                } else {
                    console.error('Échec de la suppression:', response);
                    alert('Erreur lors de la suppression de la notification');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                alert('Erreur lors de la suppression de la notification: ' + error);
            }
        });
    };    // Fonction pour marquer toutes les notifications comme lues
    window.markAllAsRead = function() {
        console.log('Tentative de suppression de toutes les notifications');
        
        // Utiliser le nouveau système de synchronisation si disponible
        if (window.notificationSync) {
            return window.notificationSync.markAllAsRead();
        }
        
        // Fallback vers l'ancienne méthode
        $.ajax({
            url: '/citizen/notifications/read-all',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Réponse du serveur:', response);
                if (response.success) {
                    // Masquer toute la section des notifications
                    $('#notifications-container').parent().parent().fadeOut(300);
                } else {
                    console.error('Échec de la suppression:', response);
                    alert('Erreur lors de la suppression des notifications');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                alert('Erreur lors de la suppression des notifications: ' + error);
            }
        });
    };

    // Fonction pour mettre à jour le compteur de notifications
    window.updateNotificationCount = function() {
        const remainingNotifications = $('.notification-item:visible').length;
        console.log('Notifications restantes:', remainingNotifications);
        
        if (remainingNotifications === 0) {
            $('.card').has('#notifications-container').fadeOut(300);
        } else {
            // Mettre à jour le titre s'il existe
            const notificationTitle = $('.card-header h5').filter(function() {
                return $(this).text().includes('Notifications récentes');
            });
            
            if (notificationTitle.length) {
                notificationTitle.html(`<i class="fas fa-bell me-2"></i>Notifications récentes (${remainingNotifications})`);
            }
        }
    };

    // Initialisation au chargement de la page
    $(document).ready(function() {
        console.log('Système de notification initialisé');
        
        // Vérifier que les métadonnées CSRF sont présentes
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error('Token CSRF manquant dans les métadonnées');
        } else {
            console.log('Token CSRF détecté:', csrfToken.substring(0, 10) + '...');
        }

        // Ajouter des gestionnaires d'événements pour les boutons existants
        $(document).on('click', '.notification-item button[onclick*="markAsRead"]', function(e) {
            e.preventDefault();
            const onClickAttr = $(this).attr('onclick');
            const notificationId = onClickAttr.match(/markAsRead\((\d+)\)/);
            if (notificationId && notificationId[1]) {
                markAsRead(parseInt(notificationId[1]));
            }
        });

        // Gestionnaire pour le bouton "Tout marquer comme lu"
        $(document).on('click', 'button[onclick*="markAllAsRead"]', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    });

})();
