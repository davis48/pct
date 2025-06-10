@extends('layouts.front.app')

@section('title', 'Traitement du paiement | Plateforme Administrative')

@push('styles')
<style>
    .phone-simulation {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 25px;
        padding: 20px;
        color: white;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transform: perspective(1000px) rotateY(-5deg);
        transition: all 0.3s ease;
    }
    
    .phone-simulation:hover {
        transform: perspective(1000px) rotateY(0deg);
    }
    
    .phone-content {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin: 15px 0;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .notification-item {
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.5s ease;
        border-left: 4px solid rgba(255,255,255,0.3);
    }
    
    .notification-item.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, #10b981, #3b82f6, #6366f1);
        z-index: 1;
    }
    
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }
    
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .step-item.completed .step-circle {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .step-item.active .step-circle {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        animation: pulse 2s infinite;
    }
    
    .step-item.pending .step-circle {
        background: #f3f4f6;
        color: #9ca3af;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        50% { box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6); }
    }
    
    .processing-animation {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold gradient-text mb-2">Traitement du paiement</h1>
                <p class="text-gray-600">Suivez le processus de votre transaction en temps réel</p>
            </div>

            <!-- Indicateur d'étapes moderne -->
            <div class="step-indicator mb-12">
                <div class="step-item completed">
                    <div class="step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="mt-2 text-sm font-medium text-gray-700">Demande</div>
                </div>
                <div class="step-item completed">
                    <div class="step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="mt-2 text-sm font-medium text-gray-700">Paiement</div>
                </div>
                <div class="step-item active">
                    <div class="step-circle">
                        <i class="fas fa-sync processing-animation"></i>
                    </div>
                    <div class="mt-2 text-sm font-medium text-primary-600">Traitement</div>
                </div>
                <div class="step-item pending">
                    <div class="step-circle">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="mt-2 text-sm font-medium text-gray-400">Réception</div>
                </div>
            </div>

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center text-red-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Simulation mobile -->
                <div class="space-y-6">
                    <div class="phone-simulation">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex space-x-2">
                                <i class="fas fa-signal text-white"></i>
                                <i class="fas fa-wifi text-white"></i>
                            </div>
                            <div class="font-bold text-white">{{ strtoupper($payment->provider) }} Money</div>
                            <div>
                                <i class="fas fa-battery-three-quarters text-white"></i>
                            </div>
                        </div>
                        
                        <div class="phone-content">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center mb-4">
                                    <img src="{{ asset('images/logos/' . $payment->provider . '.png') }}" 
                                         alt="{{ ucfirst($payment->provider) }} Logo" 
                                         class="w-10 h-10 object-contain"
                                         onerror="this.src='{{ asset('images/logos/default.png') }}'">
                                </div>
                                <h3 class="text-xl font-bold text-white">Demande de paiement</h3>
                            </div>
                            
                            <div class="space-y-3" id="phone-notifications">
                                <div class="notification-item" id="notification-1">
                                    <div class="text-xs text-white opacity-75 mb-1">De: Service des documents administratifs</div>
                                    <div class="font-semibold">Paiement de {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                </div>
                                
                                <div class="notification-item" id="notification-2">
                                    <div class="text-xs text-white opacity-75 mb-1">Référence</div>
                                    <div class="font-semibold">{{ $payment->reference }}</div>
                                </div>
                                
                                <div class="notification-item" id="notification-3">
                                    <div class="text-xs text-white opacity-75 mb-1">Numéro</div>
                                    <div class="font-semibold">{{ $payment->phone_number }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('payments.simulate', $payment->id) }}" method="POST" id="payment-form">
                            @csrf
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-200" 
                                    id="confirm-btn">
                                <i class="fas fa-check mr-2"></i>
                                <span>Confirmer le paiement</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Détails de la transaction -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-receipt mr-3"></i>
                                Détails de la transaction
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid gap-4">
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-500">Numéro de référence</div>
                                    <div class="font-semibold text-gray-900">{{ $payment->reference }}</div>
                                </div>
                                
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-500">Méthode de paiement</div>
                                    <div class="flex items-center font-semibold text-gray-900">
                                        @if($payment->payment_method === 'mobile_money')
                                            <i class="fas fa-mobile-alt mr-2 text-primary-600"></i>
                                            Mobile Money ({{ ucfirst($payment->provider) }})
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-500">Numéro</div>
                                    <div class="font-semibold text-gray-900">{{ $payment->phone_number }}</div>
                                </div>
                                
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-500">Date</div>
                                    <div class="font-semibold text-gray-900">{{ $payment->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                
                                <div class="flex justify-between items-center py-3">
                                    <div class="text-sm font-medium text-gray-500">Montant</div>
                                    <div class="text-2xl font-bold text-primary-600">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Mode démonstration</h3>
                                        <div class="mt-1 text-sm text-blue-700">
                                            Pour ce démonstrateur, le paiement est simulé. Aucun frais réel ne sera prélevé. 
                                            Cliquez sur "Confirmer le paiement" pour simuler un paiement réussi.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton d'annulation -->
                    <a href="{{ route('payments.cancel', $payment->id) }}" 
                       class="w-full inline-flex items-center justify-center px-6 py-3 border border-red-300 text-red-700 bg-white hover:bg-red-50 font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md"
                       onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement?')">
                        <i class="fas fa-times mr-2"></i>
                        Annuler le paiement
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animation des notifications avec délai
        setTimeout(function() {
            document.getElementById('notification-1').classList.add('show');
            
            setTimeout(function() {
                document.getElementById('notification-2').classList.add('show');
                
                setTimeout(function() {
                    document.getElementById('notification-3').classList.add('show');
                }, 500);
            }, 500);
        }, 1000);

        // Animation du bouton de confirmation
        const paymentForm = document.getElementById('payment-form');
        const confirmBtn = document.getElementById('confirm-btn');
        
        paymentForm.addEventListener('submit', function(e) {
            confirmBtn.disabled = true;
            confirmBtn.className = 'w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed';
            confirmBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Traitement en cours...';
            
            // Afficher une notification moderne
            showModernNotification('Paiement en cours de traitement...', 'info');
            
            // Simulation du succès du paiement
            setTimeout(() => {
                showModernNotification('✅ Paiement effectué avec succès !', 'success');
            }, 3000);
        });
        
        // Fonction pour afficher les notifications modernes
        function showModernNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                           type === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800' : 
                           'bg-red-50 border-red-200 text-red-800';
            
            const icon = type === 'success' ? 'fa-check-circle text-green-500' : 
                        type === 'info' ? 'fa-info-circle text-blue-500' : 
                        'fa-exclamation-circle text-red-500';
            
            notification.className = `fixed top-24 right-4 z-50 ${bgColor} border rounded-lg p-4 shadow-2xl transform transition-all duration-500 translate-x-full`;
            notification.style.minWidth = '350px';
            
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${icon} text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium">${type === 'success' ? 'Succès !' : type === 'info' ? 'Information' : 'Erreur'}</h3>
                        <div class="mt-1 text-sm">${message}</div>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                        <span class="sr-only">Fermer</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animer l'entrée
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto-disparition après 5 secondes
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }, 5000);
        }
        
        // Animation des étapes
        document.querySelectorAll('.step-item').forEach(function(step, index) {
            step.style.opacity = '0';
            step.style.transform = 'translateY(20px)';
            setTimeout(function() {
                step.style.transition = 'all 0.5s ease';
                step.style.opacity = '1';
                step.style.transform = 'translateY(0)';
            }, 200 * index);
        });
    });
</script>
@endpush
