@extends('layouts.front.app')
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4">Connexion</h2>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <input type="hidden" name="role" value="{{ $selectedRole }}">
                                <div class="mb-3">
                                    <label for="login" class="form-label">Email ou Numéro de téléphone</label>
                                    <input type="text" class="form-control @error('login') is-invalid @enderror"
                                           id="login" name="login" value="{{ old('login') }}" required autofocus
                                           placeholder="exemple@email.com ou +225 XX XX XX XX">
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Vous pouvez vous connecter avec votre email ou votre numéro de téléphone.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                </div>
                                <button type="submit" class="btn btn-secondary w-100">Se connecter</button>
                                <div class="text-center mt-3">
                                    <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p>Pas encore de compte ? <a href="{{ url('/inscription') }}">S'inscrire</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
