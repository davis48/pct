@extends('layouts.front.app')

@section('title', 'Paiement | Plateforme Administrative')

@push('styles')
<style>
    .payment-option {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid #eee;
        border-radius: 10px;
    }
    
    .payment-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .payment-option.selected {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .provider-logo {
        height: 50px;
        object-fit: contain;
    }
    
    .payment-details {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
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
    
    .step.active:not(:last-child):after {
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
                <div class="step active">
                    <div class="step-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="step-label">Paiement</div>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-file-alt"></i>
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
                    <h4 class="mb-0">Paiement de votre demande</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-7">
                            <h5 class="mb-4">Choisissez votre méthode de paiement</h5>
                            
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <form action="{{ route('payments.initialize', $request->id) }}" method="POST" id="payment-form">
                                @csrf
                                
                                <div class="mb-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="mobile_money" value="mobile_money" checked>
                                    <label class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center mb-2" for="mobile_money">
                                        <span><i class="fas fa-mobile-alt me-2"></i> Mobile Money</span>
                                        <span class="badge bg-primary">Recommandé</span>
                                    </label>

                                    <div id="mobile_money_options" class="mt-3 mb-4">
                                        <div class="mb-3">
                                            <label for="provider" class="form-label">Choisissez votre opérateur</label>
                                            <select name="provider" id="provider" class="form-select @error('provider') is-invalid @enderror" required>
                                                <option value="">Sélectionner un opérateur</option>
                                                @foreach($providers as $key => $name)
                                                    <option value="{{ $key }}" {{ old('provider') == $key ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('provider')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Numéro de téléphone</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="tel" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                                                    placeholder="Ex: 07XXXXXXXX" value="{{ old('phone_number', Auth::user()->phone) }}" required>
                                                @error('phone_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Entrez le numéro associé à votre compte Mobile Money</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Options désactivées mais visibles pour montrer les possibilités futures -->
                                <div class="mb-3">
                                    <input type="radio" class="btn-check" name="payment_method_disabled" id="card_payment" value="card" disabled>
                                    <label class="btn btn-outline-secondary w-100 text-start opacity-50" for="card_payment">
                                        <i class="fas fa-credit-card me-2"></i> Carte bancaire <small>(Bientôt disponible)</small>
                                    </label>
                                </div>
                                
                                <div class="mb-4">
                                    <input type="radio" class="btn-check" name="payment_method_disabled" id="bank_transfer" value="bank_transfer" disabled>
                                    <label class="btn btn-outline-secondary w-100 text-start opacity-50" for="bank_transfer">
                                        <i class="fas fa-university me-2"></i> Virement bancaire <small>(Bientôt disponible)</small>
                                    </label>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-lock me-2"></i> Procéder au paiement
                                    </button>
                                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Retour à ma demande
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="payment-details">
                                <h5 class="mb-4">Récapitulatif</h5>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Référence</div>
                                    <div>{{ $request->reference_number }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Type de document</div>
                                    <div>{{ ucfirst(str_replace('_', ' ', $request->type)) }}</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="fw-bold text-muted">Date de demande</div>
                                    <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                </div>
                                
                                <hr>
                                  <div class="d-flex justify-content-between mb-2">
                                    <span>Frais de traitement</span>
                                    <span id="processing-fee">{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Frais de service</span>
                                    <span>0 FCFA</span>
                                </div>
                                
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total à payer</span>
                                    <span class="text-primary">{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                                </div>
                                
                                <hr>
                                
                                <div class="small text-muted">
                                    <p class="mb-1"><i class="fas fa-shield-alt me-2"></i> Paiement 100% sécurisé</p>
                                    <p class="mb-0"><i class="fas fa-info-circle me-2"></i> Pour ce démonstrateur, le paiement est simulé. Aucun frais réel ne sera prélevé.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($pendingPayment))
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Vous avez un paiement en attente initié le {{ $pendingPayment->created_at->format('d/m/Y à H:i') }}.
                    <a href="{{ route('payments.process', $pendingPayment->id) }}" class="alert-link">Continuer ce paiement</a> ou
                    <a href="{{ route('payments.cancel', $pendingPayment->id) }}" class="alert-link"
                       onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement?')">l'annuler</a>.
                </div>
            @endif
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
        
        // Validation du formulaire
        const paymentForm = document.getElementById('payment-form');
        const phoneInput = document.getElementById('phone_number');
        const providerSelect = document.getElementById('provider');
        
        paymentForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (!providerSelect.value) {
                providerSelect.classList.add('is-invalid');
                isValid = false;
            } else {
                providerSelect.classList.remove('is-invalid');
            }
            
            if (!phoneInput.value || phoneInput.value.length < 8) {
                phoneInput.classList.add('is-invalid');
                isValid = false;
            } else {
                phoneInput.classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
