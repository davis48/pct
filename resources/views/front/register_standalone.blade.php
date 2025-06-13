<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
      <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/navbar-blue-standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/standalone-hover-effects.css') }}">
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>    
    <style>
        /* Styles pour le navbar moderne */
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            color: white;
        }
        
        .navbar-scroll {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Styles pour les formulaires */
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.1);
            border: 1px solid #e5e7eb;
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
        
        .alert-info {
            background: #dbeafe;
            border: 1px solid #60a5fa;
            color: #1e3a8a;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-label {
            display: block;
            font-weight: 500;
            color: #374151;            margin-bottom: 0.5rem;
        }
        
        .input-required {
            color: #ef4444;
        }
        
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background: white;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .input-field.error {
            border-color: #ef4444;
        }
        
        .error-text {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .pending-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .pending-info h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;        }
        
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 0 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
                border-radius: 12px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">                    <a href="{{ route('home') }}" class="flex items-center group">                        <div class="bg-blue-600 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-file-contract text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold gradient-text">PCT UVCI</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Accueil</a>
                    <a href="{{ route('interactive-forms.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Services</a>
                    <a href="{{ route('choose.role') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        <i class="fas fa-user-plus"></i>
                        S'inscrire
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50">
            <div class="p-4">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">                        <div class="bg-blue-600 p-2 rounded-lg mr-3">                            <i class="fas fa-file-contract text-white"></i>
                        </div>
                        <span class="text-lg font-bold gradient-text">PCT UVCI</span>
                    </div>
                    <button id="mobile-menu-close" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <nav class="space-y-4">
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200 py-2">
                        <i class="fas fa-home mr-2"></i>
                        Accueil
                    </a>
                    <a href="{{ route('interactive-forms.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200 py-2">
                        <i class="fas fa-file-alt mr-2"></i>
                        Services
                    </a>
                    <a href="{{ route('choose.role') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200 py-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="block btn-primary w-full mt-4">
                        <i class="fas fa-user-plus mr-2"></i>
                        S'inscrire
                    </a>
                </nav>
            </div>
        </div>
    </nav>

    <!-- Overlay pour le menu mobile -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>    <!-- Main Content -->
    <main class="pt-20 min-h-screen">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-plus"></i>
                    Créer un compte
                </h1>
                <p class="page-subtitle">
                    Inscrivez-vous pour accéder aux services en ligne de PCT UVCI
                </p>
            </div>

            <!-- Affichage des informations sur la demande en attente -->
            @if(session('pending_form_submission'))
                <div class="pending-info">
                    <h4><i class="fas fa-clock"></i> Demande en attente</h4>
                    <p>Vous avez une demande de {{ session('pending_form_submission.form_type') }} en attente. Après inscription, votre demande sera automatiquement traitée.</p>
                </div>
            @endif

            <!-- Messages d'alerte -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            <!-- Affichage des erreurs de validation -->
            @if($errors->any())
                <div class="alert alert-error">
                    <h4><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h4>
                    <ul style="margin: 0.5rem 0 0 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-container">
                <form action="{{ route('register.standalone') }}" method="POST">
                    @csrf
                      <div class="input-group">
                        <label class="input-label" for="nom">
                            Nom de famille <span class="input-required">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" 
                               class="input-field @error('nom') error @enderror" 
                               value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="prenoms">
                            Prénoms <span class="input-required">*</span>
                        </label>
                        <input type="text" name="prenoms" id="prenoms" 
                               class="input-field @error('prenoms') error @enderror" 
                               value="{{ old('prenoms') }}" required>
                        @error('prenoms')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="email">
                            Adresse e-mail <span class="input-required">*</span>
                        </label>
                        <input type="email" name="email" id="email" 
                               class="input-field @error('email') error @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="phone">
                            Numéro de téléphone <span class="input-required">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" 
                               class="input-field @error('phone') error @enderror" 
                               value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="date_naissance">
                            Date de naissance
                        </label>
                        <input type="date" name="date_naissance" id="date_naissance" 
                               class="input-field @error('date_naissance') error @enderror" 
                               value="{{ old('date_naissance') }}">
                        @error('date_naissance')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="place_of_birth">
                            Lieu de naissance
                        </label>
                        <input type="text" name="place_of_birth" id="place_of_birth" 
                               class="input-field @error('place_of_birth') error @enderror" 
                               value="{{ old('place_of_birth') }}" 
                               placeholder="Ex: Abidjan, Côte d'Ivoire">
                        @error('place_of_birth')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="genre">
                            Sexe
                        </label>
                        <select name="genre" id="genre" class="input-field @error('genre') error @enderror">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('genre')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="nationality">
                            Nationalité
                        </label>
                        <input type="text" name="nationality" id="nationality" 
                               class="input-field @error('nationality') error @enderror" 
                               value="{{ old('nationality', 'Ivoirienne') }}">
                        @error('nationality')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="address">
                            Adresse
                        </label>
                        <textarea name="address" id="address" rows="3"
                               class="input-field @error('address') error @enderror" 
                               placeholder="Adresse complète">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="profession">
                            Profession
                        </label>
                        <input type="text" name="profession" id="profession" 
                               class="input-field @error('profession') error @enderror" 
                               value="{{ old('profession') }}" 
                               placeholder="Ex: Étudiant, Commerçant, Fonctionnaire">
                        @error('profession')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password">
                            Mot de passe <span class="input-required">*</span>
                        </label>
                        <input type="password" name="password" id="password" 
                               class="input-field @error('password') error @enderror" required>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password_confirmation">
                            Confirmer le mot de passe <span class="input-required">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="input-field" required>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                          <button type="submit" class="btn btn-primary shine-effect">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
                        </button>
                    </div>
                </form>                <div class="login-link">
                    <p>Vous avez déjà un compte ? <a href="{{ route('login.standalone') }}">Se connecter</a></p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // JavaScript pour le navbar moderne
        const navbar = document.getElementById('navbar');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileOverlay = document.getElementById('mobile-overlay');

        // Effet de scroll sur le navbar
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll');
            } else {
                navbar.classList.remove('navbar-scroll');
            }
        });

        // Menu mobile
        function openMobileMenu() {
            mobileMenu.classList.add('open');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('open');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenu);
        }

        // Fermer le menu mobile avec Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Initialiser l'état du navbar au chargement
        window.addEventListener('load', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll');
            }
        });
    </script>
</body>
</html>

