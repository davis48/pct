@extends('layouts.front.app')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen flex items-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-primary-600 to-primary-700 rounded-full flex items-center justify-center shadow-lg mb-4">
                            <i class="fas fa-sign-in-alt text-2xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold gradient-text mb-2">Connexion</h1>
                        <p class="text-gray-600">Accédez à votre espace personnalisé</p>
                    </div>

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

                    <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="role" value="{{ $selectedRole }}">
                        
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                Email ou Numéro de téléphone
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('login') border-red-500 ring-2 ring-red-200 @enderror"
                                   id="login" 
                                   name="login" 
                                   value="{{ old('login') }}" 
                                   required 
                                   autofocus
                                   placeholder="exemple@email.com ou +225 XX XX XX XX">
                            @error('login')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Vous pouvez vous connecter avec votre email ou votre numéro de téléphone.
                            </p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>
                                Mot de passe
                            </label>
                            <input type="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Votre mot de passe">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" 
                                       id="remember" 
                                       name="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-700" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                                Mot de passe oublié ?
                            </a>
                        </div>

                        <button type="submit" 
                                class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-primary-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Pas encore de compte ? 
                            <a href="{{ url('/inscription') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                                S'inscrire
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
