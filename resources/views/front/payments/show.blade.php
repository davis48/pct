@extends('layouts.front.app')

@section('title', 'Paiement | PCT UVCI')

@push('styles')
<style>
    .payment-option {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .payment-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .provider-logo {
        height: 50px;
        object-fit: contain;
    }
</style>
@endpush
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
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Indicateur d'étapes -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex flex-col items-center text-center flex-1 relative">
                <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center relative z-10">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <div class="text-sm mt-2 font-medium text-green-600">Demande</div>
                <div class="absolute top-5 left-1/2 w-full h-0.5 bg-green-500 hidden lg:block" style="transform: translateX(50%);"></div>
            </div>
            <div class="flex flex-col items-center text-center flex-1 relative">
                <div class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center relative z-10">
                    <i class="fas fa-credit-card text-sm"></i>
                </div>
                <div class="text-sm mt-2 font-medium text-primary-600">Paiement</div>
                <div class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-300 hidden lg:block" style="transform: translateX(50%);"></div>
            </div>
            <div class="flex flex-col items-center text-center flex-1 relative">
                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center relative z-10">
                    <i class="fas fa-file-alt text-sm"></i>
                </div>
                <div class="text-sm mt-2 font-medium text-gray-600">Traitement</div>
                <div class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-300 hidden lg:block" style="transform: translateX(50%);"></div>
            </div>
            <div class="flex flex-col items-center text-center flex-1">
                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-sm"></i>
                </div>
                <div class="text-sm mt-2 font-medium text-gray-600">Réception</div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white p-6">
                <h4 class="text-2xl font-bold">Paiement de votre demande</h4>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Formulaire de paiement -->
                    <div class="lg:col-span-2">
                        <h5 class="text-xl font-semibold mb-6 text-gray-900">Choisissez votre méthode de paiement</h5>
                        
                        @if (session('error'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center text-red-800">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif
                        
                        <form action="{{ route('payments.initialize', $request->id) }}" method="POST" id="payment-form" class="space-y-6">
                            @csrf
                            
                            <!-- Option Mobile Money -->
                            <div class="payment-option">
                                <input type="radio" class="sr-only" name="payment_method" id="mobile_money" value="mobile_money" checked>
                                <label class="border-2 border-primary-200 rounded-lg p-4 cursor-pointer transition-all duration-300 hover:border-primary-400 hover:bg-primary-50 w-full flex justify-between items-center" for="mobile_money">
                                    <span class="flex items-center text-gray-900 font-medium">
                                        <i class="fas fa-mobile-alt mr-3 text-primary-600 text-lg"></i> Mobile Money
                                    </span>
                                    <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-medium">Recommandé</span>
                                </label>

                                <div id="mobile_money_options" class="mt-4 pl-4 border-l-2 border-primary-200 space-y-4">
                                    <div>
                                        <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">Choisissez votre opérateur</label>
                                        <select name="provider" id="provider" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('provider') border-red-500 @enderror" required>
                                            <option value="">Sélectionner un opérateur</option>
                                            @foreach($providers as $key => $name)
                                                <option value="{{ $key }}" {{ old('provider') == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('provider')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-phone text-gray-400"></i>
                                            </div>
                                            <input type="tel" name="phone_number" id="phone_number" 
                                                   class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('phone_number') border-red-500 @enderror" 
                                                   placeholder="Ex: 07XXXXXXXX" 
                                                   value="{{ old('phone_number', Auth::user()->phone) }}" 
                                                   required>
                                        </div>
                                        @error('phone_number')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                        <p class="text-gray-500 text-sm mt-1">Entrez le numéro associé à votre compte Mobile Money</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Options désactivées mais visibles -->
                            <div class="space-y-3">
                                <div class="border border-gray-200 rounded-lg p-4 opacity-50 cursor-not-allowed">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center text-gray-500">
                                            <i class="fas fa-credit-card mr-3 text-lg"></i> Carte bancaire
                                        </span>
                                        <span class="text-sm text-gray-400">(Bientôt disponible)</span>
                                    </div>
                                </div>
                                
                                <div class="border border-gray-200 rounded-lg p-4 opacity-50 cursor-not-allowed">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center text-gray-500">
                                            <i class="fas fa-university mr-3 text-lg"></i> Virement bancaire
                                        </span>
                                        <span class="text-sm text-gray-400">(Bientôt disponible)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Boutons d'action -->
                            <div class="space-y-3 pt-4">
                                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-lg font-medium hover:from-primary-700 hover:to-primary-800 transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-lock mr-2"></i> Procéder au paiement
                                </button>
                                <a href="{{ route('requests.show', $request->id) }}" class="w-full border-2 border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour à ma demande
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Récapitulatif -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 sticky top-4">
                            <h5 class="text-xl font-semibold mb-6 text-gray-900">Récapitulatif</h5>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-500 mb-1">Référence</div>
                                    <div class="text-gray-900 font-mono">{{ $request->reference_number }}</div>
                                </div>
                                
                                <div>
                                    <div class="text-sm font-medium text-gray-500 mb-1">Type de document</div>                                    <div class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->type)) }}</div>
                                </div>
                                
                                <div>
                                    <div class="text-sm font-medium text-gray-500 mb-1">Date de demande</div>
                                    <div class="text-gray-900">{{ $request->created_at->format('d/m/Y') }}</div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-gray-700">Frais de traitement</span>
                                        <span class="font-medium text-gray-900">{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-gray-700">Frais de service</span>
                                        <span class="font-medium text-gray-900">0 FCFA</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 pt-4">
                                        <span class="text-gray-900">Total à payer</span>
                                        <span class="text-primary-600">{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="space-y-2 text-sm text-gray-500">
                                        <p class="flex items-center">
                                            <i class="fas fa-shield-alt mr-2 text-green-500"></i> 
                                            Paiement 100% sécurisé
                                        </p>
                                        <p class="flex items-start">
                                            <i class="fas fa-info-circle mr-2 text-blue-500 mt-0.5"></i> 
                                            Pour ce démonstrateur, le paiement est simulé. Aucun frais réel ne sera prélevé.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($pendingPayment))
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-yellow-800">
                            Vous avez un paiement en attente initié le {{ $pendingPayment->created_at->format('d/m/Y à H:i') }}.
                            <a href="{{ route('payments.process', $pendingPayment->id) }}" class="font-medium text-yellow-900 underline hover:text-yellow-700">Continuer ce paiement</a> ou
                            <a href="{{ route('payments.cancel', $pendingPayment->id) }}" class="font-medium text-yellow-900 underline hover:text-yellow-700"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement?')">l'annuler</a>.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validation du formulaire moderne
        const paymentForm = document.getElementById('payment-form');
        const phoneInput = document.getElementById('phone_number');
        const providerSelect = document.getElementById('provider');
        
        // Animation d'entrée moderne
        const animateElements = document.querySelectorAll('.payment-option, .bg-gray-50');
        animateElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'all 0.6s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Validation avec feedback moderne
        paymentForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validation provider
            if (!providerSelect.value) {
                providerSelect.classList.add('border-red-500');
                providerSelect.classList.remove('border-gray-300');
                isValid = false;
            } else {
                providerSelect.classList.remove('border-red-500');
                providerSelect.classList.add('border-gray-300');
            }
            
            // Validation numéro de téléphone
            if (!phoneInput.value || phoneInput.value.length < 8) {
                phoneInput.classList.add('border-red-500');
                phoneInput.classList.remove('border-gray-300');
                isValid = false;
            } else {
                phoneInput.classList.remove('border-red-500');
                phoneInput.classList.add('border-gray-300');
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scrolling fluide vers le premier champ en erreur
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Interactions modernes
        const paymentOptions = document.querySelectorAll('.payment-option');
        paymentOptions.forEach(option => {
            option.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
            });
            
            option.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush
