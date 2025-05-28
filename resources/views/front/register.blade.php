@extends('layouts.front.app')
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4">Créer un compte</h2>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="registerForm" method="POST" action="{{ route('register.post') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="prenoms" class="form-label">Prénoms</label>
                                        <input type="text" class="form-control @error('prenoms') is-invalid @enderror" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" required>
                                        @error('prenoms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_naissance" class="form-label">Date de naissance</label>
                                        <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                        @error('date_naissance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="genre" class="form-label">Genre</label>
                                        <select class="form-select @error('genre') is-invalid @enderror" id="genre" name="genre" required>
                                            <option value="">Sélectionner...</option>
                                            <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                                            <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                                            <option value="Autre" {{ old('genre') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('genre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <input type="hidden" name="role" value="citizen">

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    <div class="form-text">Nous ne partagerons jamais votre email.</div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                        <div class="password-strength mt-1">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" id="passwordStrength" role="progressbar"
                                                    style="width: 0%"></div>
                                            </div>
                                            <small id="passwordHelp" class="form-text text-muted">Le mot de passe doit
                                                contenir au moins 8 caractères.</small>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                                        <div class="invalid-feedback" id="passwordError">Les mots de passe ne correspondent
                                            pas.</div>
                                    </div>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">J'accepte les <a href="#"
                                            data-bs-toggle="modal" data-bs-target="#termsModal">conditions
                                            d'utilisation</a></label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-secondary">S'inscrire</button>
                                </div>
                            </form>

                            <div class="text-center mt-3">
                                <p>Déjà inscrit ? <a href="{{ url('/connexion') }}">Se connecter</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Conditions d'utilisation -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Conditions d'utilisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptation des conditions</h6>
                    <p>En utilisant cette plateforme, vous acceptez ces conditions d'utilisation...</p>

                    <h6>2. Compte utilisateur</h6>
                    <p>Vous êtes responsable de maintenir la confidentialité de votre compte et mot de passe...</p>

                    <h6>3. Données personnelles</h6>
                    <p>Nous collectons et utilisons vos informations personnelles conformément à notre politique de
                        confidentialité...</p>

                    <h6>4. Responsabilités</h6>
                    <p>Vous êtes responsable de l'exactitude des informations fournies...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection
