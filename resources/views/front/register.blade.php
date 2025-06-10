@extends('layouts.front.app')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen flex items-center">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center shadow-lg mb-4">
                            <i class="fas fa-user-plus text-2xl text-white"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-white mb-2">Créer un compte</h1>
                        <p class="text-blue-100">Rejoignez notre plateforme administrative</p>
                    </div>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 mb-1">Erreurs de validation</h3>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center">
                                                <i class="fas fa-circle text-xs mr-2"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form id="registerForm" method="POST" action="{{ route('register.post') }}" class="space-y-6">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('nom') border-red-500 ring-2 ring-red-200 @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}" 
                                       required
                                       placeholder="Votre nom de famille">
                                @error('nom')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="prenoms" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                    Prénoms <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('prenoms') border-red-500 ring-2 ring-red-200 @enderror" 
                                       id="prenoms" 
                                       name="prenoms" 
                                       value="{{ old('prenoms') }}" 
                                       required
                                       placeholder="Vos prénoms">
                                @error('prenoms')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                    Date de naissance <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('date_naissance') border-red-500 ring-2 ring-red-200 @enderror" 
                                       id="date_naissance" 
                                       name="date_naissance" 
                                       value="{{ old('date_naissance') }}" 
                                       required>
                                @error('date_naissance')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="genre" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-venus-mars mr-2 text-gray-400"></i>
                                    Genre <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('genre') border-red-500 ring-2 ring-red-200 @enderror" 
                                        id="genre" 
                                        name="genre" 
                                        required>
                                    <option value="">Sélectionner...</option>
                                    <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                                    <option value="Autre" {{ old('genre') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('genre')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                Lieu de naissance <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('place_of_birth') border-red-500 ring-2 ring-red-200 @enderror" 
                                   id="place_of_birth" 
                                   name="place_of_birth" 
                                   value="{{ old('place_of_birth') }}" 
                                   placeholder="Ex: Abidjan, Bouaké, Yamoussoukro..." 
                                   required>
                            @error('place_of_birth')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <input type="hidden" name="role" value="citizen">

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="votre@email.com">
                            <p class="mt-1 text-sm text-gray-500">Nous ne partagerons jamais votre email.</p>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                Téléphone
                            </label>
                            <input type="tel" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('phone') border-red-500 ring-2 ring-red-200 @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="+225 XX XX XX XX">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-home mr-2 text-gray-400"></i>
                                Adresse
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('address') border-red-500 ring-2 ring-red-200 @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Votre adresse complète...">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-gray-400"></i>
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Votre mot de passe">
                                <div class="mt-2">
                                    <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 transition-all duration-300" id="passwordStrength" style="width: 0%"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500" id="passwordHelp">Le mot de passe doit contenir au moins 8 caractères.</p>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-gray-400"></i>
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" 
                                       id="password-confirm" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Confirmez votre mot de passe">
                                <p class="mt-1 text-xs text-red-500 hidden" id="passwordError">Les mots de passe ne correspondent pas.</p>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-primary-200">
                            <i class="fas fa-user-plus mr-2"></i>
                            S'inscrire
                        </button>

                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Déjà un compte ? 
                                <a href="{{ route('choose.role') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de confirmation -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" id="confirmationModal">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="modalContent">
            <div class="p-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Inscription réussie !</h3>
                    <p class="text-gray-600 mb-6">Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.</p>
                    <button type="button" 
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200" 
                            onclick="closeModal()">
                        Continuer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password-confirm');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordHelp = document.getElementById('passwordHelp');
        const passwordError = document.getElementById('passwordError');
        const form = document.getElementById('registerForm');

        // Vérification de la force du mot de passe
        password.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;
            let feedback = '';

            if (value.length >= 8) strength += 25;
            if (/[a-z]/.test(value)) strength += 25;
            if (/[A-Z]/.test(value)) strength += 25;
            if (/[0-9]/.test(value)) strength += 25;

            passwordStrength.style.width = strength + '%';
            
            if (strength <= 25) {
                passwordStrength.className = 'h-full bg-red-500 transition-all duration-300';
                feedback = 'Mot de passe faible';
            } else if (strength <= 50) {
                passwordStrength.className = 'h-full bg-yellow-500 transition-all duration-300';
                feedback = 'Mot de passe moyen';
            } else if (strength <= 75) {
                passwordStrength.className = 'h-full bg-blue-500 transition-all duration-300';
                feedback = 'Mot de passe correct';
            } else {
                passwordStrength.className = 'h-full bg-green-500 transition-all duration-300';
                feedback = 'Mot de passe fort';
            }

            passwordHelp.textContent = feedback;
        });

        // Vérification de la confirmation du mot de passe
        function checkPasswordMatch() {
            if (passwordConfirm.value && password.value !== passwordConfirm.value) {
                passwordError.classList.remove('hidden');
                passwordConfirm.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            } else {
                passwordError.classList.add('hidden');
                passwordConfirm.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            }
        }

        passwordConfirm.addEventListener('input', checkPasswordMatch);
        password.addEventListener('input', checkPasswordMatch);

        // Soumission du formulaire
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Inscription en cours...';
            submitButton.className = 'w-full flex items-center justify-center px-6 py-3 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed';
        });

        // Fonction pour fermer le modal
        window.closeModal = function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            window.location.href = '{{ route("choose.role") }}';
        };

        // Animations d'entrée
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach((input, index) => {
            input.style.opacity = '0';
            input.style.transform = 'translateY(20px)';
            setTimeout(() => {
                input.style.transition = 'all 0.5s ease';
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
</script>
@endpush
@endsection
