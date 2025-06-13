<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PCT UVCI - Espace Citoyen')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSS pour tous les dropdowns -->
    <link rel="stylesheet" href="{{ asset('css/dropdown-fix-global.css') }}">    
    <!-- CSS personnalisés -->
    <link rel="stylesheet" href="{{ asset('css/cocody-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-accessibility-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/standalone-hover-effects.css') }}">
    
    <!-- TailwindCSS CDN AVANT la configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js pour les dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
                            900: '#1e3a8a'
                        },
                        cocody: {
                            primary: '#1976d2',
                            secondary: '#43a047',
                            'secondary-light': '#66bb6a'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 80px; /* Espace pour navbar fixe */
        }
          /* Navbar fixe avec fond bleu */
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, #1976d2, #1565c0);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
          /* Logo en blanc pour fond bleu */
        .logo-gradient {
            color: white;
            font-weight: bold;
        }
          /* Liens navbar - couleurs blanches pour fond bleu */
        .navbar-link {
            color: white !important;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .navbar-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .navbar-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.25);
            font-weight: 600;
        }
        
        /* Badge de notifications */
        .notification-badge {
            background: #dc2626;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            min-width: 1.25rem;
            text-align: center;
        }
        
        /* Dropdown styles */
        .dropdown-content {
            min-width: 200px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .dropdown-item {
            color: #374151 !important;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .dropdown-item:hover {
            background: #f8fafc;
            color: #1976d2 !important;
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item.danger {
            color: #dc2626 !important;
        }
        
        .dropdown-item.danger:hover {
            background: #fef2f2;
            color: #b91c1c !important;
        }
        
        /* Mobile menu */
        .mobile-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }
        
        /* Avatar utilisateur */
        .user-avatar {
            background: linear-gradient(135deg, #1976d2, #43a047);
            color: white;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
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
<body class="bg-gray-50">
    <!-- Navbar fixe professionnelle -->
    <nav class="navbar-fixed">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-file-contract text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold logo-gradient">PCT UVCI</span>
                    </a>
                </div>

                <!-- Navigation Desktop -->
                <div class="hidden md:flex items-center space-x-1">
                    @auth
                        @if(auth()->user()->isCitizen())
                            <!-- Dashboard -->
                            <a href="{{ route('citizen.dashboard') }}" 
                               class="navbar-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home mr-2"></i>
                                Accueil
                            </a>
                            
                            <!-- Mes Demandes -->
                            <a href="{{ route('requests.index') }}" 
                               class="navbar-link {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                                <i class="fas fa-folder-open mr-2"></i>
                                Mes Demandes
                            </a>
                            
                            <!-- Formulaires avec dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="navbar-link flex items-center {{ request()->routeIs('interactive-forms.*') ? 'active' : '' }}">
                                    <i class="fas fa-edit mr-2"></i>
                                    Formulaires
                                    <i class="fas fa-chevron-down ml-1 text-sm" :class="{ 'rotate-180': open }"></i>
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-1 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-1 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute left-0 mt-2 dropdown-content z-50">
                                    <a href="{{ route('interactive-forms.index') }}" class="dropdown-item">
                                        <i class="fas fa-list mr-2 text-blue-600"></i>
                                        Tous les formulaires
                                    </a>
                                    <a href="{{ route('interactive-forms.show', 'extrait-naissance') }}" class="dropdown-item">
                                        <i class="fas fa-baby mr-2 text-green-600"></i>
                                        Extrait de naissance
                                    </a>
                                    <a href="{{ route('interactive-forms.show', 'certificat-mariage') }}" class="dropdown-item">
                                        <i class="fas fa-heart mr-2 text-pink-600"></i>
                                        Certificat de mariage
                                    </a>
                                    <a href="{{ route('interactive-forms.show', 'certificat-deces') }}" class="dropdown-item">
                                        <i class="fas fa-cross mr-2 text-gray-600"></i>
                                        Certificat de décès
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Notifications -->
                            @php
                                $notificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="navbar-link relative">
                                    <i class="fas fa-bell mr-1"></i>
                                    @if($notificationCount > 0)
                                        <span class="notification-badge absolute -top-1 -right-1">
                                            {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                        </span>
                                    @endif
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-1 transform scale-100"
                                     class="absolute right-0 mt-2 w-80 dropdown-content z-50 max-h-96 overflow-y-auto">
                                    <div class="p-3 border-b border-gray-200 bg-gray-50">
                                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                                    </div>
                                    @if($notificationCount > 0)
                                        <div class="p-2">
                                            <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                                                Tout marquer comme lu
                                            </button>
                                        </div>
                                    @endif
                                    <div class="max-h-64 overflow-y-auto">
                                        @forelse(\App\Models\Notification::where('user_id', Auth::id())->latest()->take(5)->get() as $notification)
                                            <div class="dropdown-item {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                                <div class="text-sm">{{ $notification->title }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        @empty
                                            <div class="dropdown-item text-center text-gray-500">
                                                Aucune notification
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Menu Utilisateur -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 navbar-link">
                                <div class="w-8 h-8 rounded-full user-avatar flex items-center justify-center">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                             alt="Photo" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <span class="text-sm font-semibold">
                                            {{ strtoupper(substr(Auth::user()->prenoms ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="hidden lg:block font-medium">{{ Auth::user()->prenoms }} {{ Auth::user()->nom }}</span>
                                <i class="fas fa-chevron-down text-sm" :class="{ 'rotate-180': open }"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-1 transform scale-100"
                                 class="absolute right-0 mt-2 dropdown-content z-50">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-user mr-2 text-blue-600"></i>
                                    Mon Profil
                                </a>
                                <a href="{{ route('citizen.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt mr-2 text-green-600"></i>
                                    Tableau de bord                                </a>
                                <a href="{{ route('citizen.notifications.center') }}" class="dropdown-item">
                                    <i class="fas fa-bell mr-2 text-yellow-600"></i>
                                    Notifications
                                </a>
                                <hr class="border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger w-full text-left">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('choose.role') }}" class="navbar-link">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-user-plus mr-2"></i>
                            S'inscrire
                        </a>
                    @endauth

                    <!-- Bouton menu mobile -->
                    <div class="md:hidden">
                        <button @click="mobileOpen = !mobileOpen" class="navbar-link">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div x-show="mobileOpen" 
             @click.away="mobileOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-1 transform translate-y-0"
             class="md:hidden mobile-menu border-t border-gray-200"
             x-data="{ mobileOpen: false }">
            <div class="px-4 py-6 space-y-3">
                @auth
                    @if(auth()->user()->isCitizen())
                        <a href="{{ route('citizen.dashboard') }}" class="block navbar-link">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                        <a href="{{ route('requests.index') }}" class="block navbar-link">
                            <i class="fas fa-folder-open mr-2"></i>Mes Demandes
                        </a>
                        <a href="{{ route('interactive-forms.index') }}" class="block navbar-link">
                            <i class="fas fa-edit mr-2"></i>Formulaires
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block navbar-link">
                            <i class="fas fa-user mr-2"></i>Mon Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left navbar-link danger">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>    <!-- Scripts -->
    <script>
        // Toggle menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }    <!-- Scripts -->
    <script>
        // Toggle menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Effet de scroll sur la navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-fixed');
            if (window.scrollY > 10) {
                navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                navbar.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
