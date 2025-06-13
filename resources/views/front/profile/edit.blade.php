@extends('layouts.front.app')

@section('title', 'Modifier mon profil | Plateforme Administrative')

@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold gradient-text mb-2">Mon profil</h1>
                <p class="text-gray-600">Modifiez vos informations personnelles</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

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
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-user-edit mr-3"></i>
                        Informations personnelles
                    </h2>
                </div>
                
                <div class="p-8">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Photo de profil -->
                        <div class="flex flex-col lg:flex-row lg:items-start gap-8">
                            <div class="lg:w-1/3">
                                <div class="text-center">
                                    <div class="relative inline-block mb-4">
                                        @if(Auth::user()->profile_photo)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                                alt="Photo de profil" 
                                                class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg" 
                                                id="profilePreview">
                                        @else
                                            <div class="w-32 h-32 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center border-4 border-white shadow-lg" id="profilePreview">
                                                <i class="fas fa-user text-4xl text-white"></i>
                                            </div>
                                        @endif
                                        <div class="absolute bottom-0 right-0 bg-primary-600 rounded-full p-2 cursor-pointer hover:bg-primary-700 transition-colors duration-200" onclick="document.getElementById('profile_photo').click()">
                                            <i class="fas fa-camera text-white text-sm"></i>
                                        </div>
                                    </div>
                                    <input type="file" 
                                           class="hidden" 
                                           id="profile_photo" 
                                           name="profile_photo" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->prenoms }} {{ Auth::user()->nom }}</h3>
                                    <p class="text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                            </div>

                            <div class="lg:w-2/3">
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
                                               value="{{ old('nom', Auth::user()->nom) }}" 
                                               required>
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
                                               value="{{ old('prenoms', Auth::user()->prenoms) }}" 
                                               required>
                                        @error('prenoms')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', Auth::user()->email) }}" 
                                               required>
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
                                               value="{{ old('phone', Auth::user()->phone) }}"
                                               placeholder="+225 XX XX XX XX">
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>                                    <div>
                                        <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                            Date de naissance <span class="text-red-500">*</span>
                                        </label>                                        <input type="date" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('date_naissance') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="date_naissance" 
                                               name="date_naissance" 
                                               value="{{ old('date_naissance', Auth::user()->date_naissance ? Auth::user()->date_naissance->format('Y-m-d') : '') }}"
                                               required>
                                        @error('date_naissance')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div><div>
                                        <label for="genre" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-venus-mars mr-2 text-gray-400"></i>
                                            Genre <span class="text-red-500">*</span>
                                        </label>
                                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('genre') border-red-500 ring-2 ring-red-200 @enderror" 
                                                id="genre" 
                                                name="genre"
                                                required>
                                            <option value="">Sélectionner...</option>
                                            <option value="M" {{ old('genre', Auth::user()->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                                            <option value="F" {{ old('genre', Auth::user()->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
                                            <option value="Autre" {{ old('genre', Auth::user()->genre) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('genre')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
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
                                               value="{{ old('place_of_birth', Auth::user()->place_of_birth) }}"
                                               placeholder="Ville/Pays de naissance"
                                               required>
                                        @error('place_of_birth')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-home mr-2 text-gray-400"></i>
                                        Adresse
                                    </label>
                                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('address') border-red-500 ring-2 ring-red-200 @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3"
                                              placeholder="Votre adresse complète...">{{ old('address', Auth::user()->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section changement de mot de passe -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-lock mr-2 text-primary-600"></i>
                                Changer le mot de passe
                            </h3>
                            
                            <div class="grid md:grid-cols-3 gap-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mot de passe actuel
                                    </label>
                                    <input type="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('current_password') border-red-500 ring-2 ring-red-200 @enderror" 
                                           id="current_password" 
                                           name="current_password"
                                           placeholder="Mot de passe actuel">
                                    @error('current_password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nouveau mot de passe
                                    </label>
                                    <input type="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                                           id="password" 
                                           name="password"
                                           placeholder="Nouveau mot de passe">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmer le mot de passe
                                    </label>
                                    <input type="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           placeholder="Confirmer le mot de passe">
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard') }}" 
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour au tableau de bord
                            </a>
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-primary-200">
                                <i class="fas fa-save mr-2"></i>
                                Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Animation des éléments
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach((input, index) => {
            input.style.opacity = '0';
            input.style.transform = 'translateY(20px)';
            setTimeout(() => {
                input.style.transition = 'all 0.5s ease';
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, 50 * index);
        });

        // Gestion du formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Mise à jour en cours...';
            submitButton.className = 'flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed';
        });
    });
</script>
@endpush
