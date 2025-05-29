@extends('layouts.front.app')

@section('title', 'Traitement du paiement | Plateforme Administrative')

@push('styles')
<style>
    .payment-processing {
        text-align: center;
        padding: 2rem;
    }
    
    .payment-animation {
        width: 150px;
        height: 150px;
        margin: 0 auto 2rem;
        position: relative;
    }
    
    .payment-animation .circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #0d6efd;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .transaction-details {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 2rem;
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    
    .step {
        text-align: center;
        flex: 1;
        position: relative;
    }
    
    .step:not(:last-child):after {
        content: '';
        position: absolute;
        top: 15px;
        right: -50%;
        width: 100%;
        height: 3px;
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .step.active:not(:last-child):after,
    .step.completed:not(:last-child):after {
        background-color: #0d6efd;
    }
    
    .step-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        position: relative;
        z-index: 1;
    }
    
    .step.active .step-icon {
        background-color: #0d6efd;
        color: white;
    }
    
    .step.completed .step-icon {
        background-color: #198754;
        color: white;
    }
    
    /* Simulation d'écran de téléphone */
    .phone-simulation {
        max-width: 300px;
        margin: 0 auto;
        border: 10px solid #333;
        border-radius: 30px;
        padding: 20px;
        background-color: #fff;
        position: relative;
    }
    
    .phone-simulation:before {
        content: '';
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 10px;
        background-color: #333;
        border-radius: 0 0 10px 10px;
    }
    
    .phone-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .phone-content {
        padding: 10px 0;
    }
    
    .phone-footer {
        border-top: 1px solid #eee;
        padding-top: 10px;
        margin-top: 15px;
    }
    
    /* Animation de notification */
    .notification {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        transform: translateY(20px);
        opacity: 0;
    }
    
    .notification.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    /* Bouton de confirmation */
    .confirm-btn {
        position: relative;
        overflow: hidden;
    }
    
    .confirm-btn .overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        background-color: rgba(255,255,255,0.3);
        transition: width 1.5s ease;
    }
    
    .confirm-btn.processing .overlay {
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Indicateur d'étapes -->
            <div class="step-indicator mb-4">
                <div class="step completed">
                    <div class="step-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="step-label">Demande</div>
                </div>
                <div class="step completed">
                    <div class="step-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="step-label">Paiement</div>
                </div>
                <div class="step active">
                    <div class="step-icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <div class="step-label">Traitement</div>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="step-label">Réception</div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Traitement de votre paiement</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="phone-simulation">
                                <div class="phone-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><i class="fas fa-signal"></i> <i class="fas fa-wifi"></i></div>
                                        <div><strong>{{ strtoupper($payment->provider) }} Money</strong></div>
                                        <div><i class="fas fa-battery-three-quarters"></i></div>
                                    </div>
                                </div>
                                
                                <div class="phone-content" id="phone-content">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('images/logos/' . $payment->provider . '.png') }}" 
                                             alt="{{ ucfirst($payment->provider) }} Logo" 
                                             class="img-fluid mb-2" 
                                             style="max-height: 40px;"
                                             onerror="this.src='{{ asset('images/logos/default.png') }}'">
                                        <h5>Demande de paiement</h5>
                                    </div>
                                    
                                    <div class="notification" id="notification-1">
                                        <small class="text-muted">De: Service des documents administratifs</small>
                                        <p class="mb-0">Paiement de <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></p>
                                    </div>
                                    
                                    <div class="notification" id="notification-2">
                                        <small class="text-muted">Référence</small>
                                        <p class="mb-0">{{ $payment->reference }}</p>
                                    </div>
                                    
                                    <div class="notification" id="notification-3">
                                        <small class="text-muted">Numéro</small>
                                        <p class="mb-0">{{ $payment->phone_number }}</p>
                                    </div>
                                </div>
                                
                                <div class="phone-footer">
                                    <form action="{{ route('payments.simulate', $payment->id) }}" method="POST" id="payment-form">
                                        @csrf
                                        <input type="hidden" name="confirm" value="1">
                                        <button type="submit" class="btn btn-success btn-sm w-100 confirm-btn" id="confirm-btn">
                                            <span>Confirmer le paiement</span>
                                            <div class="overlay"></div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="transaction-details">
                                <h5 class="mb-4">Détails de la transaction</h5>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Numéro de référence</div>
                                    <div>{{ $payment->reference }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Méthode de paiement</div>
                                    <div>
                                        @if($payment->payment_method === 'mobile_money')
                                            <i class="fas fa-mobile-alt me-2"></i> Mobile Money ({{ ucfirst($payment->provider) }})
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Numéro</div>
                                    <div>{{ $payment->phone_number }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Date</div>
                                    <div>{{ $payment->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Montant</div>
                                    <div class="fs-5 fw-bold text-primary">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                </div>
                                
                                <hr>
                                
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Pour ce démonstrateur, le paiement est simulé. Aucun frais réel ne sera prélevé. Cliquez sur "Confirmer le paiement" pour simuler un paiement réussi.
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('payments.cancel', $payment->id) }}" class="btn btn-outline-danger w-100"
                                   onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement?')">
                                    <i class="fas fa-times me-2"></i> Annuler le paiement
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Afficher les notifications avec délai
        setTimeout(function() {
            document.getElementById('notification-1').classList.add('show');
            
            setTimeout(function() {
                document.getElementById('notification-2').classList.add('show');
                
                setTimeout(function() {
                    document.getElementById('notification-3').classList.add('show');
                }, 500);
            }, 500);
        }, 500);
        
        // Animation du bouton de confirmation
        const paymentForm = document.getElementById('payment-form');
        const confirmBtn = document.getElementById('confirm-btn');
        
        paymentForm.addEventListener('submit', function(e) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('processing');
            confirmBtn.innerHTML = '<span><i class="fas fa-circle-notch fa-spin me-2"></i> Traitement en cours...</span><div class="overlay"></div>';
        });
        
        // Animation des étapes
        document.querySelectorAll('.step').forEach(function(step, index) {
            setTimeout(function() {
                step.style.opacity = '0';
                setTimeout(function() {
                    step.style.transition = 'all 0.5s ease';
                    step.style.opacity = '1';
                }, 100 * index);
            }, 0);
        });
    });
</script>
@endpush
