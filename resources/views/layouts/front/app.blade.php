<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PCT UVCI - Plateforme Citoyenne')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Correctifs d'accessibilité pour la lisibilité des navbars -->
    <link rel="stylesheet" href="{{ asset('css/navbar-accessibility-fix.css') }}">
    <!-- CSS global pour tous les dropdowns -->
    <link rel="stylesheet" href="{{ asset('css/dropdown-fix-global.css') }}">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes bounceSubtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
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
        
        @media print {
            .no-print {
                display: none !important;
            }
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        
        /* Dropdown styles */
        .dropdown-menu {
            display: none;
        }
        
        .dropdown.active .dropdown-menu {
            display: block;
        }
        
        /* Notification dropdown specific styles */
        #notificationDropdown {
            min-width: 320px;
            max-width: 400px;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.show {
            transform: translateX(0);
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* CSS spécifique pour les dropdowns - évite les conflits avec le CSS d'accessibilité */
        #user-dropdown a,
        #user-dropdown button,
        #notificationDropdown a,
        #notificationDropdown span,
        #notificationDropdown p {
            color: inherit !important; /* Préserve les couleurs originales */
        }
        
        /* Force les couleurs spécifiques pour le dropdown utilisateur */
        #user-dropdown .text-gray-800 {
            color: #1f2937 !important;
        }
        
        #user-dropdown .text-red-600 {
            color: #dc2626 !important;
        }
        
        #user-dropdown .text-blue-600 {
            color: #2563eb !important;
        }
        
        /* Force les couleurs spécifiques pour le dropdown notifications */
        #notificationDropdown .text-gray-900 {
            color: #111827 !important;
        }
        
        #notificationDropdown .text-blue-600 {
            color: #2563eb !important;
        }
        
        #notificationDropdown .text-gray-500 {
            color: #6b7280 !important;
        }
        
        /* CORRECTION URGENTE - FORCE LES COULEURS DES DROPDOWNS */
        /* Priority sur tous les autres CSS avec !important */
        
        /* Tous les dropdowns */
        #user-dropdown *,
        #profileDropdown *,
        #notificationDropdown *,
        #notifications-dropdown *,
        .dropdown-menu *,
        div[id*="dropdown"] *,
        div[class*="dropdown"] * {
            color: #1f2937 !important; /* Force gris foncé */
        }
        
        /* Liens dans les dropdowns */
        #user-dropdown a,
        #profileDropdown a, 
        #notificationDropdown a,
        #notifications-dropdown a,
        .dropdown-menu a {
            color: #1f2937 !important;
            text-decoration: none !important;
        }
        
        /* Boutons dans les dropdowns */
        #user-dropdown button,
        #profileDropdown button,
        #notificationDropdown button,
        #notifications-dropdown button,
        .dropdown-menu button {
            color: #1f2937 !important;
        }
        
        /* Icônes dans les dropdowns */
        #user-dropdown i,
        #profileDropdown i,
        #notificationDropdown i,
        #notifications-dropdown i,
        .dropdown-menu i {
            color: #2563eb !important;
        }
        
        /* Boutons de déconnexion en rouge */
        #user-dropdown button[type="submit"],
        #profileDropdown button[type="submit"],
        .dropdown-menu button[type="submit"] {
            color: #dc2626 !important;
        }
        
        /* Hover effects */
        #user-dropdown a:hover,
        #profileDropdown a:hover,
        #notificationDropdown a:hover,
        #notifications-dropdown a:hover,
        .dropdown-menu a:hover {
            color: #1d4ed8 !important;
            background-color: #f3f4f6 !important;
        }
        
        /* Force l'affichage des dropdowns quand ils sont ouverts */
        #user-dropdown:not(.hidden),
        #profileDropdown:not(.hidden),
        #notificationDropdown:not(.hidden),
        #notifications-dropdown:not(.hidden) {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 9999 !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="bg-blue-600 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-file-contract text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">PCT UVCI</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard Admin
                            </a>
                        @elseif(auth()->user()->isAgent())
                            <a href="{{ route('agent.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard Agent
                            </a>
                        @else
                            <a href="{{ route('citizen.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Mon Espace
                            </a>
                            <a href="{{ route('requests.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                Mes Demandes
                            </a>
                            <a href="{{ route('interactive-forms.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Formulaires
                            </a>
                        @endif
                        
                        <!-- Notifications Bell (for citizens only) -->
                        @if(auth()->user()->isCitizen())
                        <div class="relative">
                            <button id="notificationToggle" class="relative p-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-bell text-lg"></i>
                                @php
                                    $notificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div id="notificationDropdown" class="dropdown-menu absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 max-h-96 overflow-y-auto hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-900">Notifications</span>
                                        <button onclick="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800">
                                            Tout marquer lu
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="notificationsList">
                                    <!-- Les notifications seront chargées ici via AJAX -->
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                        <p>Aucune notification</p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100 mt-2">
                                    <a href="{{ route('citizen.notifications.center') }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 text-center">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center text-white hover:text-blue-100 transition-colors duration-200" style="color: white !important;">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-2">
                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->prenoms ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span class="hidden lg:block" style="color: white !important;">{{ auth()->user()->prenoms }} {{ auth()->user()->nom }}</span>
                                <i class="fas fa-chevron-down ml-2 text-sm" style="color: white !important;"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50 border border-gray-200">
                                <a href="{{ route('profile.edit') }}" style="color: #1f2937 !important;" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                    <i class="fas fa-user mr-2" style="color: #2563eb !important;"></i>
                                    Mon Profil
                                </a>
                                <a href="{{ route('profile.edit') }}" style="color: #1f2937 !important;" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                    <i class="fas fa-cog mr-2" style="color: #2563eb !important;"></i>
                                    Paramètres
                                </a>
                                <hr class="my-2 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="color: #dc2626 !important;" class="block w-full text-left px-4 py-2 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2" style="color: #dc2626 !important;"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                        <a href="{{ route('choose.role') }}" class="btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden mobile-menu fixed top-16 left-0 w-full h-screen bg-white shadow-lg z-40">
            <div class="px-4 py-6 space-y-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard Admin
                        </a>
                    @elseif(auth()->user()->isAgent())
                        <a href="{{ route('agent.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard Agent
                        </a>
                    @else
                        <a href="{{ route('citizen.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Mon Espace
                        </a>
                        <a href="{{ route('requests.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-file-alt mr-2"></i>
                            Mes Demandes
                        </a>
                        <a href="{{ route('interactive-forms.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Formulaires
                        </a>
                        <a href="{{ route('citizen.notifications.center') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-bell mr-2"></i>
                            Notifications
                            @php
                                $mobileNotificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            @if($mobileNotificationCount > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $mobileNotificationCount }}</span>
                            @endif
                        </a>
                    @endif
                    <hr class="my-4">
                    <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>
                        Mon Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Accueil
                    </a>
                    <a href="{{ route('choose.role') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-16 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Logo et description -->
                <div class="md:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold">PCT UVCI</span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Votre plateforme citoyenne moderne pour toutes vos démarches administratives en ligne.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Demandes en ligne</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Suivi de dossier</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Téléchargement sécurisé</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Support en ligne</a></li>
                    </ul>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Accueil</a></li>
                        <li><a href="{{ route('choose.role') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Se connecter</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors duration-200">S'inscrire</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Abidjan, Côte d'Ivoire
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            +225 XX XX XX XX
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            contact@pct-uvci.ci
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            24h/24 - 7j/7
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300 text-sm">
                    © {{ date('Y') }} PCT UVCI. Tous droits réservés. 
                    <span class="mx-2">|</span>
                    <a href="#" class="hover:text-white transition-colors duration-200">Mentions légales</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="hover:text-white transition-colors duration-200">Politique de confidentialité</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll');
            } else {
                navbar.classList.remove('navbar-scroll');
            }
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('open');
        });

        // User dropdown toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.remove('open');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>