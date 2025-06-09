@extends('layouts.front.app')

@section('title', 'Statut du paiement | Plateforme Administrative')

@push('styles')
<style>
    .payment-status {
        text-align: center;
        padding: 2rem;
    }
    
    .status-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
    
    .status-icon.success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .status-icon.failed {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .status-icon.pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
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
    
    /* Animation de la liste de vérification */
    .check-list {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }
    
    .check-list li {
        padding: 10px 0;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
        display: flex;
        align-items: center;
    }
    
    .check-list li.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .check-list li i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .check-list li i.success {
        color: #198754;
    }
    
    .check-list li i.failed {
        color: #dc3545;
    }
    
    .receipt {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        position: relative;
    }
    
    .receipt:before {
        content: '';
        position: absolute;
        top: -5px;
        left: 5%;
        width: 90%;
        height: 10px;
        background-color: #f8f9fa;
        border-radius: 50%;
    }
    
    .receipt-header {
        text-align: center;
        border-bottom: 1px dashed #dee2e6;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .receipt-body {
        margin-bottom: 15px;
    }
    
    .receipt-footer {
        text-align: center;
        border-top: 1px dashed #dee2e6;
        padding-top: 15px;
    }
      .qr-code {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        background-color: #f8f9fa;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Animation pour le message de succès */
    .success-alert {
        animation: successPulse 2s ease-in-out;
        box-shadow: 0 4px 20px rgba(25, 135, 84, 0.3);
    }
    
    @keyframes successPulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
        }
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
                <div class="step completed">
                    <div class="step-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="step-label">Traitement</div>
                </div>
                <div class="step {{ $payment->isCompleted() ? 'active' : '' }}">
                    <div class="step-icon">
                        <i class="fas fa-{{ $payment->isCompleted() ? 'check-circle' : 'clock' }}"></i>
                    </div>
                    <div class="step-label">Réception</div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-{{ $payment->isCompleted() ? 'success' : ($payment->isPending() ? 'warning' : 'danger') }} text-white">
                    <h4 class="mb-0">
                        @if ($payment->isCompleted())
                            Paiement réussi
                        @elseif ($payment->isPending())
                            Paiement en attente
                        @elseif ($payment->isCancelled())
                            Paiement annulé
                        @else
                            Paiement échoué
                        @endif
                    </h4>                </div>
                <div class="card-body p-4">                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 success-alert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>🎉 Félicitations !</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreur :</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="payment-status">
                                @if ($payment->isCompleted())
                                    <div class="status-icon success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="mb-3">Paiement effectué avec succès!</h3>
                                    <p class="text-muted">Votre paiement a été traité et votre demande est maintenant en cours de traitement.</p>
                                    
                                    <ul class="check-list">
                                        <li><i class="fas fa-check-circle success"></i> Transaction approuvée</li>
                                        <li><i class="fas fa-check-circle success"></i> Paiement reçu</li>
                                        <li><i class="fas fa-check-circle success"></i> Demande en cours de traitement</li>
                                        <li><i class="fas fa-check-circle success"></i> Notification envoyée</li>
                                    </ul>
                                @elseif ($payment->isPending())
                                    <div class="status-icon pending">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h3 class="mb-3">Paiement en cours de traitement</h3>
                                    <p class="text-muted">Votre paiement est en cours de traitement. Veuillez patienter...</p>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('payments.status', $payment->id) }}" class="btn btn-primary">
                                            <i class="fas fa-sync-alt me-2"></i> Actualiser le statut
                                        </a>
                                    </div>
                                @elseif ($payment->isCancelled())
                                    <div class="status-icon failed">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <h3 class="mb-3">Paiement annulé</h3>
                                    <p class="text-muted">Votre paiement a été annulé. Vous pouvez réessayer ultérieurement.</p>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('payments.show', $payment->citizenRequest->id) }}" class="btn btn-primary">
                                            <i class="fas fa-redo me-2"></i> Réessayer le paiement
                                        </a>
                                    </div>
                                @else
                                    <div class="status-icon failed">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <h3 class="mb-3">Paiement échoué</h3>
                                    <p class="text-muted">Votre paiement n'a pas pu être traité. Veuillez réessayer.</p>
                                    
                                    <ul class="check-list">
                                        <li><i class="fas fa-times-circle failed"></i> Transaction refusée</li>
                                        <li><i class="fas fa-times-circle failed"></i> {{ $payment->callback_data['message'] ?? 'Erreur de paiement' }}</li>
                                    </ul>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('payments.show', $payment->citizenRequest->id) }}" class="btn btn-primary">
                                            <i class="fas fa-redo me-2"></i> Réessayer le paiement
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if ($payment->isCompleted())
                                <div class="receipt">
                                    <div class="receipt-header">
                                        <h5>Reçu de paiement</h5>
                                        <p class="mb-0 text-muted">{{ now()->format('d/m/Y H:i') }}</p>
                                    </div>
                                    
                                    <div class="receipt-body">
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">N° Transaction:</div>
                                            <div class="col-6 text-end">{{ $payment->transaction_id }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Référence:</div>
                                            <div class="col-6 text-end">{{ $payment->reference }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Date:</div>
                                            <div class="col-6 text-end">{{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Méthode:</div>
                                            <div class="col-6 text-end">
                                                @if($payment->payment_method === 'mobile_money')
                                                    Mobile Money ({{ ucfirst($payment->provider) }})
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Numéro:</div>
                                            <div class="col-6 text-end">{{ $payment->phone_number }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Montant:</div>
                                            <div class="col-6 text-end fw-bold">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 text-muted">Statut:</div>
                                            <div class="col-6 text-end text-success">Payé</div>
                                        </div>
                                    </div>
                                    
                                    <div class="receipt-footer">
                                        <div class="qr-code mb-2">
                                            <i class="fas fa-qrcode fa-3x text-muted"></i>
                                        </div>
                                        <small class="text-muted">Merci de votre paiement</small>
                                    </div>
                                </div>
                                  <div class="d-grid gap-2 mt-4">
                                    <a href="{{ route('citizen.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-tachometer-alt me-2"></i> Mes Demandes
                                    </a>
                                    <a href="{{ route('requests.show', $payment->citizenRequest->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-file-alt me-2"></i> Voir ma demande
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                                        <i class="fas fa-print me-2"></i> Imprimer le reçu
                                    </a>
                                </div>
                            @else
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
                                    
                                    <div class="mb-3">
                                        <div class="fw-bold text-muted">Statut</div>
                                        <div>
                                            @if ($payment->isCompleted())
                                                <span class="badge bg-success">Payé</span>
                                            @elseif ($payment->isPending())
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @elseif ($payment->isCancelled())
                                                <span class="badge bg-secondary">Annulé</span>
                                            @else
                                                <span class="badge bg-danger">Échoué</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('requests.show', $payment->citizenRequest->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i> Retour à ma demande
                                        </a>
                                    </div>
                                </div>
                            @endif
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
        
        // Animation de la liste de vérification
        const checkItems = document.querySelectorAll('.check-list li');
        checkItems.forEach(function(item, index) {
            setTimeout(function() {
                item.classList.add('show');
            }, 500 + (index * 300));
        });
    });
</script>
@endpush
