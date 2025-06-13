@extends('layouts.citizen-navbar')

@section('title', 'Modifier mon profil | PCT UVCI')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }
    
    .form-input {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
    }
    
    .form-input:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        outline: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1976d2, #1565c0);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(25, 118, 210, 0.3);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
        color: #374151;
        text-decoration: none;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-label .required {
        color: #ef4444;
    }
    
    .error-text {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background: #d1fae5;
        border: 1px solid #34d399;
        color: #064e3b;
    }
    
    .alert-error {
        background: #fee2e2;
        border: 1px solid #f87171;
        color: #7f1d1d;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier mon profil</h1>
            <p class="text-gray-600">Mettez à jour vos informations personnelles</p>
        </div>

        <!-- Messages d'alerte -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Erreur :</strong> Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif

        <!-- Formulaire -->
        <div class="form-container p-8">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div class="form-group">
                        <label for="nom" class="form-label">
                            Nom <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom', $user->nom) }}" 
                               class="form-input" 
                               required>
                        @error('nom')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prénoms -->
                    <div class="form-group">
                        <label for="prenoms" class="form-label">
                            Prénoms <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="prenoms" 
                               name="prenoms" 
                               value="{{ old('prenoms', $user->prenoms) }}" 
                               class="form-input" 
                               required>
                        @error('prenoms')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Adresse email <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="form-input" 
                               required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="telephone" class="form-label">
                            Téléphone <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="telephone" 
                               name="telephone" 
                               value="{{ old('telephone', $user->telephone) }}" 
                               class="form-input" 
                               required>
                        @error('telephone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">
                            Date de naissance
                        </label>
                        <input type="date" 
                               id="date_of_birth" 
                               name="date_of_birth" 
                               value="{{ old('date_of_birth', $user->date_of_birth) }}" 
                               class="form-input">
                        @error('date_of_birth')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lieu de naissance -->
                    <div class="form-group">
                        <label for="place_of_birth" class="form-label">
                            Lieu de naissance
                        </label>
                        <input type="text" 
                               id="place_of_birth" 
                               name="place_of_birth" 
                               value="{{ old('place_of_birth', $user->place_of_birth) }}" 
                               class="form-input">
                        @error('place_of_birth')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nationalité -->
                    <div class="form-group">
                        <label for="nationality" class="form-label">
                            Nationalité
                        </label>
                        <input type="text" 
                               id="nationality" 
                               name="nationality" 
                               value="{{ old('nationality', $user->nationality ?? 'Ivoirienne') }}" 
                               class="form-input">
                        @error('nationality')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="form-group">
                        <label for="address" class="form-label">
                            Adresse
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address', $user->address) }}" 
                               class="form-input">
                        @error('address')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Nom du père -->
                <div class="form-group mt-6">
                    <label for="father_name" class="form-label">
                        Nom du père
                    </label>
                    <input type="text" 
                           id="father_name" 
                           name="father_name" 
                           value="{{ old('father_name', $user->father_name) }}" 
                           class="form-input">
                    @error('father_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Photo de profil -->
                <div class="form-group">
                    <label for="profile_photo" class="form-label">
                        Photo de profil
                    </label>
                    <input type="file" 
                           id="profile_photo" 
                           name="profile_photo" 
                           accept="image/*" 
                           class="form-input">
                    @if($user->profile_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="Photo actuelle" 
                                 class="w-20 h-20 rounded-full object-cover">
                        </div>
                    @endif
                    @error('profile_photo')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('citizen.dashboard') }}" class="btn-secondary text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour au dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prévisualisation de la photo
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Créer ou mettre à jour l'aperçu
                let preview = document.querySelector('.photo-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'photo-preview w-20 h-20 rounded-full object-cover mt-2';
                    e.target.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
