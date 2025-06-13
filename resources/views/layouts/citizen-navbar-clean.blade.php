<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PCT UVCI')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/navbar-accessibility-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/standalone-hover-effects.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }
        
        .navbar-link {
            @apply text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200;
        }
        
        .dropdown-item {
            @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200;
        }
        
        .dropdown-item.danger {
            @apply text-red-600 hover:bg-red-50 hover:text-red-700;
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar-fixed bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo et nom -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <span class="text-xl font-bold text-gray-900">PCT UVCI</span>
                        </div>
                    </a>
                </div>

                <!-- Menu principal -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        @if(auth()->user()->isCitizen())
                            <a href="{{ route('citizen.dashboard') }}" class="navbar-link">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('requests.index') }}" class="navbar-link">
                                <i class="fas fa-file-alt mr-2"></i>
                                Mes Demandes
                            </a>
                            
                            <a href="{{ route('interactive-forms.index') }}" class="navbar-link">
                                <i class="fas fa-edit mr-2"></i>
                                Formulaires
                            </a>
                            
                            <a href="{{ route('citizen.notifications.center') }}" class="navbar-link relative">
                                <i class="fas fa-bell mr-2"></i>
                                Notifications
                                @php
                                    $notificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        
                        <!-- Menu utilisateur -->
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center navbar-link">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-2">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Photo" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <span class="text-white text-sm font-semibold">{{ strtoupper(substr(Auth::user()->prenoms, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span class="hidden lg:block">{{ Auth::user()->prenoms }} {{ Auth::user()->nom }}</span>
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50 border border-gray-200">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-user mr-2 text-blue-600"></i>
                                    Mon Profil
                                </a>
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
                                    Dashboard
                                </a>
                                <hr class="my-2 border-gray-200">
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
                        <button onclick="toggleMobileMenu()" class="navbar-link">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @auth
                    @if(auth()->user()->isCitizen())
                        <a href="{{ route('citizen.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('requests.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-file-alt mr-2"></i>
                            Mes Demandes
                        </a>
                        
                        <a href="{{ route('interactive-forms.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-edit mr-2"></i>
                            Formulaires
                        </a>
                        
                        <a href="{{ route('citizen.notifications.center') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-bell mr-2"></i>
                            Notifications
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>
                            Mon Profil
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('choose.role') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-user-plus mr-2"></i>
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal avec padding pour compenser la navbar fixe -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Toggle menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Toggle dropdown utilisateur
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuBtn = document.getElementById('user-menu-btn');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                // Fermer le dropdown quand on clique ailleurs
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });

        // Effet de scroll sur la navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-fixed');
            if (window.scrollY > 10) {
                navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                navbar.style.boxShadow = 'none';
                navbar.style.background = 'white';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
