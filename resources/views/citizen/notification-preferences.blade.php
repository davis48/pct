@extends('layouts.front.app')

@section('title', 'Préférences de notifications')

@push('styles')
<style>
    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
        background-color: #e5e7eb;
        border-radius: 30px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .toggle-switch.active {
        background-color: #3b82f6;
    }
    
    .toggle-slider {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .toggle-switch.active .toggle-slider {
        transform: translateX(30px);
    }
    
    .notification-category {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background-color: #f9fafb;
    }
    
    .notification-category.important {
        border-color: #fbbf24;
        background-color: #fffbeb;
    }
    
    .notification-category.critical {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Préférences de notifications</h1>
                    <p class="mt-2 text-gray-600">Personnalisez vos notifications pour recevoir uniquement les informations importantes</p>
                </div>
                <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au tableau de bord
                </a>
            </div>
        </div>

        <form id="notification-preferences-form">
            @csrf
            
            <!-- Notifications critiques (toujours activées) -->
            <div class="notification-category critical">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Notifications critiques</h3>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Obligatoires
                    </span>
                </div>
                <p class="text-gray-600 mb-4">Ces notifications sont essentielles et ne peuvent pas être désactivées.</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Approbation / Rejet de demande</h4>
                            <p class="text-sm text-gray-500">Notification quand votre demande est approuvée ou rejetée</p>
                        </div>
                        <div class="toggle-switch active" data-disabled="true">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Document prêt pour retrait</h4>
                            <p class="text-sm text-gray-500">Notification quand votre document est prêt</p>
                        </div>
                        <div class="toggle-switch active" data-disabled="true">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Confirmation de paiement</h4>
                            <p class="text-sm text-gray-500">Notification de confirmation de paiement</p>
                        </div>
                        <div class="toggle-switch active" data-disabled="true">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications importantes -->
            <div class="notification-category important">
                <div class="flex items-center mb-4">
                    <i class="fas fa-star text-yellow-500 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Notifications importantes</h3>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Recommandées
                    </span>
                </div>
                <p class="text-gray-600 mb-4">Notifications importantes pour suivre l'évolution de vos demandes.</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Changements de statut majeurs</h4>
                            <p class="text-sm text-gray-500">En attente → En cours, En cours → Approuvé/Rejeté</p>
                        </div>
                        <div class="toggle-switch active" data-preference="status_notifications">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Assignation d'un agent</h4>
                            <p class="text-sm text-gray-500">Notification quand un agent est assigné à votre demande</p>
                        </div>
                        <div class="toggle-switch active" data-preference="assignment_notifications">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Confirmation de soumission</h4>
                            <p class="text-sm text-gray-500">Confirmation quand vous soumettez une nouvelle demande</p>
                        </div>
                        <div class="toggle-switch active" data-preference="submission_confirmations">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications optionnelles -->
            <div class="notification-category">
                <div class="flex items-center mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Notifications optionnelles</h3>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Optionnelles
                    </span>
                </div>
                <p class="text-gray-600 mb-4">Notifications supplémentaires que vous pouvez activer selon vos préférences.</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Début de traitement</h4>
                            <p class="text-sm text-gray-500">Notification quand l'agent commence à traiter votre demande</p>
                        </div>
                        <div class="toggle-switch" data-preference="processing_notifications">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Changements mineurs</h4>
                            <p class="text-sm text-gray-500">Tous les petits changements de statut intermédiaires</p>
                        </div>
                        <div class="toggle-switch" data-preference="intermediate_notifications">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Rappels automatiques</h4>
                            <p class="text-sm text-gray-500">Rappels pour vos demandes en attente d'action</p>
                        </div>
                        <div class="toggle-switch" data-preference="reminder_notifications">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Canaux de notification -->
            <div class="notification-category">
                <div class="flex items-center mb-4">
                    <i class="fas fa-paper-plane text-green-500 mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Canaux de notification</h3>
                </div>
                <p class="text-gray-600 mb-4">Choisissez comment vous voulez recevoir vos notifications importantes.</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Notifications dans l'application</h4>
                            <p class="text-sm text-gray-500">Notifications affichées dans votre tableau de bord</p>
                        </div>
                        <div class="toggle-switch active" data-disabled="true">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Notifications par SMS</h4>
                            <p class="text-sm text-gray-500">Recevoir des SMS pour les notifications importantes</p>
                        </div>
                        <div class="toggle-switch active" data-preference="sms_enabled">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900">Notifications par email</h4>
                            <p class="text-sm text-gray-500">Recevoir des emails pour les notifications importantes</p>
                        </div>
                        <div class="toggle-switch active" data-preference="email_enabled">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-2"></i>
                    Vos préférences sont automatiquement sauvegardées
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="resetToDefaults()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </button>
                    
                    <button type="button" onclick="testNotifications()" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100">
                        <i class="fas fa-bell mr-2"></i>
                        Tester
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Préférences sauvegardées</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Vos préférences de notification ont été mises à jour avec succès.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmOk" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPreferences();
    setupToggleHandlers();
});

function setupToggleHandlers() {
    document.querySelectorAll('.toggle-switch').forEach(toggle => {
        if (!toggle.dataset.disabled) {
            toggle.addEventListener('click', function() {
                this.classList.toggle('active');
                savePreferences();
            });
        }
    });
}

function loadPreferences() {
    // Charger les préférences depuis le serveur
    fetch('/citizen/notification-preferences')
        .then(response => response.json())
        .then(preferences => {
            Object.keys(preferences).forEach(key => {
                const toggle = document.querySelector(`[data-preference="${key}"]`);
                if (toggle) {
                    if (preferences[key]) {
                        toggle.classList.add('active');
                    } else {
                        toggle.classList.remove('active');
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des préférences:', error);
        });
}

function savePreferences() {
    const preferences = {};
    
    document.querySelectorAll('[data-preference]').forEach(toggle => {
        const key = toggle.dataset.preference;
        preferences[key] = toggle.classList.contains('active');
    });
    
    fetch('/citizen/notification-preferences', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(preferences)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showConfirmation();
        }
    })
    .catch(error => {
        console.error('Erreur lors de la sauvegarde:', error);
        alert('Erreur lors de la sauvegarde des préférences');
    });
}

function resetToDefaults() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser vos préférences aux valeurs par défaut ?')) {
        // Réinitialiser aux valeurs par défaut
        const defaults = {
            'status_notifications': true,
            'assignment_notifications': true,
            'submission_confirmations': true,
            'processing_notifications': false,
            'intermediate_notifications': false,
            'reminder_notifications': false,
            'sms_enabled': true,
            'email_enabled': true
        };
        
        Object.keys(defaults).forEach(key => {
            const toggle = document.querySelector(`[data-preference="${key}"]`);
            if (toggle) {
                if (defaults[key]) {
                    toggle.classList.add('active');
                } else {
                    toggle.classList.remove('active');
                }
            }
        });
        
        savePreferences();
    }
}

function testNotifications() {
    fetch('/citizen/test-notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Notification de test envoyée ! Vérifiez votre tableau de bord.');
    })
    .catch(error => {
        console.error('Erreur lors du test:', error);
        alert('Erreur lors de l\'envoi du test');
    });
}

function showConfirmation() {
    const modal = document.getElementById('confirmationModal');
    modal.classList.remove('hidden');
    
    document.getElementById('confirmOk').onclick = function() {
        modal.classList.add('hidden');
    };
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 3000);
}
</script>
@endpush
