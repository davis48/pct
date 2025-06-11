@extends('layouts.app')

@section('title', 'Test des Notifications Synchronisées')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header de test -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">🔔 Test des Notifications Synchronisées</h1>
                    <p class="text-gray-600 mt-1">Vérifiez que les notifications se synchronisent correctement entre tous les composants</p>
                </div>
                <div class="flex space-x-3">
                    <span id="current-layout" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        Layout: App
                    </span>
                </div>
            </div>
        </div>

        <!-- Indicateurs de statut -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i id="sync-status-icon" class="fas fa-sync text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Système de Sync</h3>
                        <p id="sync-status" class="text-gray-600">Vérification...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i id="badge-status-icon" class="fas fa-bell text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Badge Navbar</h3>
                        <p id="badge-status" class="text-gray-600">Vérification...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i id="dropdown-status-icon" class="fas fa-list text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Dropdown</h3>
                        <p id="dropdown-status" class="text-gray-600">Vérification...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions de test -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions de Test</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <button onclick="testNotificationCreation()" class="btn btn-primary flex items-center justify-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Créer notification test</span>
                </button>
                
                <button onclick="testMarkAsRead()" class="btn btn-secondary flex items-center justify-center space-x-2">
                    <i class="fas fa-check"></i>
                    <span>Marquer comme lue</span>
                </button>
                
                <button onclick="testMarkAllAsRead()" class="btn btn-warning flex items-center justify-center space-x-2">
                    <i class="fas fa-check-double"></i>
                    <span>Marquer toutes comme lues</span>
                </button>
                
                <button onclick="forceSync()" class="btn btn-info flex items-center justify-center space-x-2">
                    <i class="fas fa-sync"></i>
                    <span>Forcer la synchronisation</span>
                </button>
            </div>
        </div>

        <!-- Logs de test -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Log de Test</h3>
                <button onclick="clearTestLog()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eraser mr-2"></i>Vider
                </button>
            </div>
            <div id="test-log" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm h-64 overflow-y-auto">
                <!-- Les logs apparaîtront ici -->
            </div>
        </div>
    </div>
</div>

<style>
.btn {
    @apply px-4 py-2 rounded-lg font-medium transition-all duration-200 text-white;
}

.btn-primary {
    @apply bg-blue-600 hover:bg-blue-700;
}

.btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700;
}

.btn-warning {
    @apply bg-yellow-600 hover:bg-yellow-700;
}

.btn-info {
    @apply bg-cyan-600 hover:bg-cyan-700;
}

.btn-outline-secondary {
    @apply border border-gray-300 text-gray-700 bg-white hover:bg-gray-50;
}

.btn-sm {
    @apply px-3 py-1 text-xs;
}
</style>

<script>
let testLog = [];

function log(message, type = 'info') {
    const timestamp = new Date().toLocaleTimeString();
    const logEntry = `[${timestamp}] ${message}`;
    testLog.push(logEntry);
    
    const logElement = document.getElementById('test-log');
    logElement.innerHTML = testLog.join('\n');
    logElement.scrollTop = logElement.scrollHeight;
    
    console.log(`🔔 Test:`, message);
}

function clearTestLog() {
    testLog = [];
    document.getElementById('test-log').innerHTML = '';
}

function updateStatus(component, status, icon) {
    const statusElement = document.getElementById(`${component}-status`);
    const iconElement = document.getElementById(`${component}-status-icon`);
    
    if (statusElement) statusElement.textContent = status;
    if (iconElement) iconElement.className = `fas ${icon}`;
}

// Tests de base
function checkSyncSystem() {
    if (window.notificationSync) {
        updateStatus('sync', 'Actif ✅', 'fa-check-circle text-green-600');
        log('✅ Système de synchronisation détecté et actif');
        return true;
    } else {
        updateStatus('sync', 'Inactif ❌', 'fa-times-circle text-red-600');
        log('❌ Système de synchronisation non disponible');
        return false;
    }
}

function checkBadge() {
    const badges = [
        document.querySelector('.notification-badge'),
        document.getElementById('notification-badge'),
        document.getElementById('notification-count')
    ].filter(Boolean);
    
    if (badges.length > 0) {
        updateStatus('badge', `${badges.length} badge(s) trouvé(s) ✅`, 'fa-check-circle text-green-600');
        log(`✅ ${badges.length} badge(s) de notification trouvé(s)`);
        return true;
    } else {
        updateStatus('badge', 'Aucun badge trouvé ❌', 'fa-times-circle text-red-600');
        log('❌ Aucun badge de notification trouvé');
        return false;
    }
}

function checkDropdown() {
    const dropdowns = [
        document.getElementById('notificationsList'),
        document.getElementById('notifications-list'),
        document.getElementById('notificationDropdown')
    ].filter(Boolean);
    
    if (dropdowns.length > 0) {
        updateStatus('dropdown', `${dropdowns.length} dropdown(s) trouvé(s) ✅`, 'fa-check-circle text-green-600');
        log(`✅ ${dropdowns.length} dropdown(s) de notification trouvé(s)`);
        return true;
    } else {
        updateStatus('dropdown', 'Aucun dropdown trouvé ❌', 'fa-times-circle text-red-600');
        log('❌ Aucun dropdown de notification trouvé');
        return false;
    }
}

// Actions de test
function testNotificationCreation() {
    log('🔄 Test de création de notification...');
    
    fetch('{{ route("citizen.test-notification") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            log('✅ Notification de test créée avec succès');
            setTimeout(() => {
                if (window.notificationSync) {
                    window.notificationSync.syncAll();
                    log('🔄 Synchronisation forcée après création');
                }
            }, 500);
        } else {
            log('❌ Erreur lors de la création de la notification: ' + data.message);
        }
    })
    .catch(error => {
        log('❌ Erreur réseau: ' + error.message);
    });
}

function testMarkAsRead() {
    log('🔄 Test de marquage comme lue...');
    
    if (window.notificationSync) {
        // Trouver la première notification disponible
        const firstNotification = document.querySelector('.notification-item');
        if (firstNotification) {
            const notificationId = firstNotification.getAttribute('data-id');
            if (notificationId) {
                window.notificationSync.markAsRead(notificationId);
                log(`✅ Tentative de marquage de la notification ${notificationId} comme lue`);
            } else {
                log('❌ Impossible de trouver l\'ID de la notification');
            }
        } else {
            log('❌ Aucune notification disponible pour le test');
        }
    } else {
        log('❌ Système de synchronisation non disponible');
    }
}

function testMarkAllAsRead() {
    log('🔄 Test de marquage de toutes les notifications comme lues...');
    
    if (window.notificationSync) {
        window.notificationSync.markAllAsRead();
        log('✅ Commande de marquage de toutes les notifications envoyée');
    } else {
        log('❌ Système de synchronisation non disponible');
    }
}

function forceSync() {
    log('🔄 Synchronisation forcée...');
    
    if (window.notificationSync) {
        window.notificationSync.syncAll();
        log('✅ Synchronisation forcée exécutée');
    } else {
        log('❌ Système de synchronisation non disponible');
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    log('🚀 Initialisation du test des notifications');
    
    // Vérifier les composants
    setTimeout(() => {
        checkSyncSystem();
        checkBadge();
        checkDropdown();
        
        log('📊 Vérification des composants terminée');
    }, 1000);
    
    // Écouter les événements du système de synchronisation
    document.addEventListener('notificationsUpdated', function(event) {
        log(`📱 Événement: notificationsUpdated - ${event.detail.count} notifications`);
    });
    
    document.addEventListener('notificationRead', function(event) {
        log(`📖 Événement: notificationRead - ID: ${event.detail.notificationId}`);
    });
    
    document.addEventListener('allNotificationsRead', function(event) {
        log(`📚 Événement: allNotificationsRead - Toutes les notifications marquées comme lues`);
    });
});
</script>
@endsection
