@extends('layouts.front.app')

@section('title', 'Détails de la demande')

@push('styles')
<style>
    /* Styles pour les toggles de notification */
    input[type="checkbox"] + label {
        transition: background-color 0.3s ease;
        position: relative;
    }
    
    input[type="checkbox"] + label:before {
        content: "";
        position: absolute;
        top: 0.125rem;
        left: 0.125rem;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        background-color: white;
        transition: transform 0.3s ease;
    }
    
    input[type="checkbox"]:checked + label:before {
        transform: translateX(1rem);
    }
    
    /* Animation des notifications */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
        }
    }
    
    .notification-pulse {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Mon Espace
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails de la demande</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header de la demande -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $request->type_label }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Demande #{{ $request->id }} • Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end">
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i> En attente
                                </span>
                                @break
                            @case('in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-cog mr-2"></i> En cours de traitement
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-2"></i> Approuvé
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-2"></i> Rejeté
                                </span>
                                @break
                        @endswitch
                        
                        @if($request->status === 'approved')
                            <button class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le document
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Chronologie du traitement -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Suivi de la demande</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Étape 1: Soumission -->
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-paper-plane text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Demande soumise
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Étape 2: Attribution (si applicable) -->
                                @if($request->assigned_to)
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Assignée à l'agent <span class="font-medium text-gray-900">{{ $request->assignedAgent->name }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Étape 3: En cours (si applicable) -->
                                @if(in_array($request->status, ['in_progress', 'approved', 'rejected']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(!in_array($request->status, ['approved', 'rejected']))
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-cog text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Traitement en cours
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Étape finale -->
                                @if(in_array($request->status, ['approved', 'rejected']))
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @if($request->status === 'approved')
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-check text-white text-sm"></i>
                                                    </span>
                                                @else
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-times text-white text-sm"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $request->status === 'approved' ? 'Demande approuvée' : 'Demande rejetée' }}
                                                        @if($request->processedBy)
                                                            par <span class="font-medium text-gray-900">{{ $request->processedBy->name }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->processed_at ? $request->processed_at->format('d/m/Y H:i') : $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Motif de rejet (si applicable) -->
                @if($request->status === 'rejected' && $request->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-red-900 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Motif du rejet
                        </h3>
                        <p class="text-red-700">{{ $request->rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Informations de la demande -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6">                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de demande</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->type_label }}</dd>
                            </div>
                            
                            @if($request->document)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Document associé</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->document->title ?? 'Non spécifié' }}</dd>
                            </div>
                            @endif
                            
                            @if($request->document && $request->document->description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->document->description }}</dd>
                            </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de soumission</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>

                            @if($request->assignedAgent)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Agent assigné</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->assignedAgent->name }}</dd>
                            </div>
                            @endif

                            @if($request->processed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de traitement</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->processed_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6 space-y-3">
                        @if($request->status === 'approved')
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le document
                            </button>
                        @endif

                        @if($request->status === 'draft')
                            <form action="{{ route('requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer cette demande
                                </button>
                            </form>
                        @endif

                        <button onclick="window.print()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-print mr-2"></i>
                            Imprimer cette page
                        </button>

                        <!-- Bouton des préférences de notifications -->
                        <button onclick="openNotificationPreferences()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-bell mr-2"></i>
                            Préférences de notifications
                        </button>

                        @if(in_array($request->status, ['pending', 'rejected']))
                            <a href="{{ route('requests.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle demande
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Aide -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-3">
                            <i class="fas fa-question-circle mr-2"></i>
                            Besoin d'aide ?
                        </h3>
                        <p class="text-blue-700 text-sm mb-3">
                            Si vous avez des questions concernant votre demande, n'hésitez pas à nous contacter.
                        </p>
                        <div class="space-y-2">
                            <p class="text-blue-700 text-sm">
                                <i class="fas fa-phone mr-2"></i>
                                +225 XX XX XX XX
                            </p>
                            <p class="text-blue-700 text-sm">
                                <i class="fas fa-envelope mr-2"></i>
                                support@pct-uvci.gov.ci
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal des préférences de notifications -->
<div class="fixed inset-0 overflow-y-auto hidden" id="notificationPreferencesModal" aria-modal="true" role="dialog">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeNotificationPreferences()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-bell text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Préférences de notifications
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Notifications sonores</h4>
                                    <p class="text-xs text-gray-500">Activer les sons lors de la réception de notifications</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="soundToggle" class="sr-only" checked>
                                    <label for="soundToggle" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Notifications de bureau</h4>
                                    <p class="text-xs text-gray-500">Afficher des notifications sur votre bureau</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="desktopToggle" class="sr-only" checked>
                                    <label for="desktopToggle" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">SMS</h4>
                                    <p class="text-xs text-gray-500">Recevoir des notifications par SMS</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="smsToggle" class="sr-only" checked>
                                    <label for="smsToggle" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Email</h4>
                                    <p class="text-xs text-gray-500">Recevoir des notifications par email</p>
                                </div>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="emailToggle" class="sr-only" checked>
                                    <label for="emailToggle" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="saveNotificationPreferences()">
                    Enregistrer
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="testNotification()">
                    Tester
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeNotificationPreferences()">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Fonctions pour gérer les préférences de notification -->
<script>
function openNotificationPreferences() {
    const modal = document.getElementById('notificationPreferencesModal');
    if (modal) {
        modal.classList.remove('hidden');
        initNotificationPreferences();
    }
}

function closeNotificationPreferences() {
    const modal = document.getElementById('notificationPreferencesModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function initNotificationPreferences() {
    if (typeof window.notificationSystem === 'undefined') return;
    
    // Initialiser les toggles selon les préférences sauvegardées
    document.getElementById('soundToggle').checked = notificationSystem.soundEnabled;
    document.getElementById('desktopToggle').checked = notificationSystem.desktopEnabled;
    
    // Récupérer les préférences SMS et Email depuis localStorage ou initialiser à true
    document.getElementById('smsToggle').checked = localStorage.getItem('sms_notifications') !== 'disabled';
    document.getElementById('emailToggle').checked = localStorage.getItem('email_notifications') !== 'disabled';
    
    // Appliquer le style des toggles
    applyToggleStyles();
}

function applyToggleStyles() {
    // Styliser les toggles
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        const label = checkbox.nextElementSibling;
        if (checkbox.checked) {
            label.classList.add('bg-blue-600');
            label.classList.remove('bg-gray-300');
        } else {
            label.classList.remove('bg-blue-600');
            label.classList.add('bg-gray-300');
        }
    });
}

function saveNotificationPreferences() {
    if (typeof window.notificationSystem === 'undefined') return;
    
    // Récupérer les valeurs des toggles
    const soundEnabled = document.getElementById('soundToggle').checked;
    const desktopEnabled = document.getElementById('desktopToggle').checked;
    const smsEnabled = document.getElementById('smsToggle').checked;
    const emailEnabled = document.getElementById('emailToggle').checked;
    
    // Sauvegarder les préférences
    localStorage.setItem('notification_sound', soundEnabled ? 'enabled' : 'disabled');
    localStorage.setItem('desktop_notifications', desktopEnabled ? 'enabled' : 'disabled');
    localStorage.setItem('sms_notifications', smsEnabled ? 'enabled' : 'disabled');
    localStorage.setItem('email_notifications', emailEnabled ? 'enabled' : 'disabled');
    
    // Mettre à jour le système de notification
    notificationSystem.soundEnabled = soundEnabled;
    notificationSystem.desktopEnabled = desktopEnabled;
    
    // Si les notifications de bureau sont activées, demander la permission
    if (desktopEnabled) {
        notificationSystem.requestPermission();
    }
    
    // Fermer le modal
    closeNotificationPreferences();
    
    // Afficher un message de confirmation
    notificationSystem.showToast('Vos préférences de notifications ont été enregistrées', 'success');
}

// Tester les notifications
function testNotification() {
    if (typeof window.notificationSystem === 'undefined') {
        alert('Le système de notification n\'est pas disponible');
        return;
    }
    
    const message = `Ceci est une notification de test pour la demande #"{{ $request->id }}"`;
    
    // Jouer le son de notification
    if (notificationSystem.soundEnabled) {
        notificationSystem.playSound();
    }
    
    // Afficher une notification de bureau
    if (notificationSystem.desktopEnabled && notificationSystem.hasPermission) {
        notificationSystem.showDesktopNotification('Notification de test', message);
    }
    
    // Afficher une notification toast
    notificationSystem.showToast(message, 'info');
    
    // Informer l'utilisateur sur l'état des notifications
    let status = 'Notification toast affichée';
    
    if (notificationSystem.soundEnabled) {
        status += ', son joué';
    }
    
    if (notificationSystem.desktopEnabled && notificationSystem.hasPermission) {
        status += ', notification de bureau affichée';
    } else if (notificationSystem.desktopEnabled && !notificationSystem.hasPermission) {
        status += ' (notification de bureau non affichée: permission non accordée)';
    }
    
    console.log('Test de notification:', status);
}
</script>

@push('scripts')
<script>
// Actualisation automatique du statut
function checkForUpdates() {
    fetch(`{{ route('citizen.request.updates', $request->id) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.status !== '{{ $request->status }}') {
                // Le statut a changé, recharger la page
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification des mises à jour:', error);
        });
}

// Vérifier les mises à jour toutes les 30 secondes
setInterval(checkForUpdates, 30000);

// Intégration avec le système de notification moderne
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si le système de notification est disponible
    if (typeof window.notificationSystem !== 'undefined') {
        // Écouter les changements de statut pour les notifications
        const requestId = "{{ $request->id }}";
        const currentStatus = '{{ $request->status }}';
        
        // Si le statut change et que le notificationSystem est disponible, nous pouvons
        // afficher une notification directement sans recharger la page complètement
        const statusChangeListener = function(event) {
            const data = event.detail;
            if (data.requestId === requestId && data.newStatus !== currentStatus) {
                // Afficher une notification via le système de notification
                const message = `Le statut de votre demande #${requestId} a changé: ${getStatusText(data.newStatus)}`;
                notificationSystem.showToast(message, getStatusType(data.newStatus));
                
                // Rafraîchir la page après un court délai pour afficher les nouvelles informations
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        };
        
        // Ajouter l'écouteur d'événement
        document.addEventListener('requestStatusChanged', statusChangeListener);
        
        // Initialiser les valeurs des toggles dans le modal
        initNotificationPreferences();
    }
});

// Convertir le code de statut en texte lisible
function getStatusText(status) {
    const statusTexts = {
        'pending': 'En attente',
        'in_progress': 'En cours de traitement',
        'approved': 'Approuvé',
        'rejected': 'Rejeté'
    };
    return statusTexts[status] || status;
}

// Obtenir le type de notification en fonction du statut
function getStatusType(status) {
    const statusTypes = {
        'pending': 'info',
        'in_progress': 'info',
        'approved': 'success',
        'rejected': 'error'
    };
    return statusTypes[status] || 'info';
}
</script>

<!-- Charger le système de notification -->
<script src="{{ asset('js/notification-system.js') }}"></script>
@endpush
@endsection
