@extends('layouts.front.app')

@section('title', 'Modifier mon profil | Plateforme Administrative')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h2 class="card-title h4 mb-0">Modifier mon profil</h2>
                        </div>
                        
                        <div class="card-body p-4">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">
                                    <div class="col-md-3 text-center">
                                        <div class="position-relative mb-3">
                                            @if(Auth::user()->profile_photo)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                                    alt="Photo de profil" class="rounded-circle img-thumbnail" 
                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                                                    style="width: 150px; height: 150px;">
                                                    <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="profile_photo" class="form-label">Changer ma photo</label>
                                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                                id="profile_photo" name="profile_photo" accept="image/*">
                                            @error('profile_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Formats acceptés : JPG, PNG. Max 2Mo.</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <h5 class="mb-3">Informations personnelles</h5>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                                    id="nom" name="nom" value="{{ old('nom', Auth::user()->nom) }}" required>
                                                @error('nom')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="prenoms" class="form-label">Prénoms</label>
                                                <input type="text" class="form-control @error('prenoms') is-invalid @enderror"
                                                    id="prenoms" name="prenoms" value="{{ old('prenoms', Auth::user()->prenoms) }}" required>
                                                @error('prenoms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                                    id="date_naissance" name="date_naissance"
                                                    value="{{ old('date_naissance', Auth::user()->date_naissance) }}" required>
                                                @error('date_naissance')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="genre" class="form-label">Genre</label>
                                                <select class="form-select @error('genre') is-invalid @enderror"
                                                        id="genre" name="genre" required>
                                                    <option value="">Sélectionner...</option>
                                                    <option value="M" {{ old('genre', Auth::user()->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                                                    <option value="F" {{ old('genre', Auth::user()->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
                                                    <option value="Autre" {{ old('genre', Auth::user()->genre) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                                </select>
                                                @error('genre')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="place_of_birth" class="form-label">Lieu de naissance</label>
                                            <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror"
                                                id="place_of_birth" name="place_of_birth" 
                                                value="{{ old('place_of_birth', Auth::user()->place_of_birth) }}" 
                                                placeholder="Ex: Abidjan, Bouaké, Yamoussoukro..." required>
                                            @error('place_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Adresse</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                    id="address" name="address" rows="2">{{ old('address', Auth::user()->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">Changer de mot de passe</h5>
                                <p class="text-muted small mb-3">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.</p>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" minlength="8">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control"
                                            id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour au tableau de bord
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // S'assurer que la date est correctement formatée
        const dateNaissanceInput = document.getElementById('date_naissance');
        
        if (dateNaissanceInput) {
            // Log la valeur actuelle pour le débogage
            console.log('Valeur initiale date:', dateNaissanceInput.value);
            
            // Fonction pour formater correctement la date au format YYYY-MM-DD
            function formatDateForInput(dateString) {
                if (!dateString) return '';
                
                // Si c'est déjà un format valide YYYY-MM-DD, on le garde
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                    return dateString;
                }
                
                // Essayer de parser la date
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;
                
                // Formater au format YYYY-MM-DD
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                
                return `${year}-${month}-${day}`;
            }
            
            // Appliquer le format si nécessaire
            if (dateNaissanceInput.value) {
                const formattedDate = formatDateForInput(dateNaissanceInput.value);
                dateNaissanceInput.value = formattedDate;
                console.log('Date formatée:', formattedDate);
            }
            
            // Ajouter un événement de validation avant la soumission du formulaire
            dateNaissanceInput.closest('form').addEventListener('submit', function(e) {
                if (dateNaissanceInput.value) {
                    const formattedDate = formatDateForInput(dateNaissanceInput.value);
                    dateNaissanceInput.value = formattedDate;
                    console.log('Date soumise:', formattedDate);
                }
            });
        }
    });
</script>
@endpush
