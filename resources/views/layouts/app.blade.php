<!DOCTYPE html>
<html lang="fr">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PCT UVCI - Services en ligne')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
      <!-- Correctifs d'accessibilité pour la lisibilité des navbars -->
    <link rel="stylesheet" href="{{ asset('css/navbar-accessibility-fix.css') }}">
    <!-- CSS global pour tous les dropdowns -->
    <link rel="stylesheet" href="{{ asset('css/dropdown-fix-global.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .form-input:focus {
            outline: 2px solid #3b82f6;
            border-color: #3b82f6;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        /* Navbar animations */
        .navbar-glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link:before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover:before {
            width: 100%;
        }
        
        .nav-link:hover {
            color: #3b82f6 !important;
            transform: translateY(-1px);
        }
        
        /* Logo animation */
        .logo-container {
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.05);
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }
        
        .logo-container:hover .logo-icon {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: rotate(5deg);
        }        /* Dropdown avec hover ET click pour meilleure UX */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 9999 !important;
            position: absolute !important;
        }
        
        .dropdown.active .dropdown-menu,
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
            z-index: 9999 !important;
        }
        
        /* Garde le dropdown ouvert quand la souris est sur le menu */
        .dropdown-menu:hover {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            z-index: 9999 !important;
        }
        
        /* Zone de tolérance pour la navigation souris */
        .dropdown {
            position: relative;
        }
        
        .dropdown::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 10px;
            background: transparent;
            z-index: 40;
        }
        
        /* Notification bell animation */
        .notification-bell {
            transition: all 0.3s ease;
        }
        
        .notification-bell:hover {
            transform: scale(1.1) rotate(10deg);
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Mobile menu animations */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .mobile-menu.open {
            max-height: 500px;
        }
        
        .mobile-menu-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s ease;
        }
        
        .mobile-menu.open .mobile-menu-item {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Notification badge animation */
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Scroll effect */
        .navbar-scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.98);
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
        }
        
        /* Glassmorphism effect for buttons */
        .glass-btn {
            background: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }
        
        .glass-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
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
    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full top-0 z-50 navbar-glass transition-all duration-300 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="logo-container flex items-center">
                        <div class="logo-icon w-10 h-10 text-white rounded-lg flex items-center justify-center font-bold text-lg mr-3">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-gray-900 leading-tight">PCT UVCI</span>
                            <span class="text-xs text-gray-500 leading-tight">Services Numériques</span>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="nav-link text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Accueil
                    </a>
                      <div class="dropdown relative">
                        <button class="nav-link text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium flex items-center">
                            <i class="fas fa-stamp mr-2"></i>Services
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-menu absolute top-full left-0 mt-1 w-64 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <a href="{{ route('interactive-forms.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-file-signature mr-3 text-blue-500"></i>
                                <div>
                                    <div class="font-medium">Formulaires Interactifs</div>
                                    <div class="text-xs text-gray-500">Remplir vos documents en ligne</div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-folder-open mr-3 text-green-500"></i>
                                <div>
                                    <div class="font-medium">Suivi de Demandes</div>
                                    <div class="text-xs text-gray-500">Suivre l'état de vos demandes</div>
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-credit-card mr-3 text-purple-500"></i>
                                <div>
                                    <div class="font-medium">Paiements</div>
                                    <div class="text-xs text-gray-500">Régler vos frais en ligne</div>
                                </div>
                            </a>                        </div>
                    </div>
                    
                    @auth                        <!-- Notifications Bell -->
                        <div class="dropdown relative">                            <button id="notificationToggle" class="notification-bell nav-link text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium relative">
                                <i class="fas fa-clipboard-check text-lg"></i>
                                @php
                                    $notificationCount = Auth::check() ? \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() : 0;
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $notificationCount }}
                                    </span>
                                @endif
                            </button>
                            <div id="notificationDropdown" class="dropdown-menu absolute top-full right-0 mt-1 w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 max-h-96 overflow-y-auto">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-900 flex justify-between items-center">
                                        <span>Notifications</span>
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
                                    <a href="{{ route('citizen.notifications') }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 text-center">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                          <a href="{{ route('citizen.dashboard') }}" class="nav-link text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-city mr-2"></i>Tableau de bord
                        </a>
                        
                        <!-- Profile Dropdown -->
                        <div class="dropdown relative">
                            <button id="profileToggle" class="nav-link flex items-center text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold mr-2">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                {{ Str::limit(Auth::user()->name, 15) }}
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div id="profileDropdown" class="dropdown-menu absolute top-full right-0 mt-1 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                </div>                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-id-card mr-2"></i>Mon Profil
                                </a>
                                <a href="{{ route('citizen.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-city mr-2"></i>Tableau de bord
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-cog mr-2"></i>Paramètres
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link text-gray-600 hover:text-blue-600 px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                        
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Inscription
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 p-2 rounded-lg transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu md:hidden bg-white border-t border-gray-100">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-home mr-3"></i>Accueil
                    </a>                    <a href="{{ route('interactive-forms.index') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-stamp mr-3"></i>Formulaires
                    </a>
                      @auth
                        <a href="{{ route('citizen.notifications.center') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors relative">
                            <i class="fas fa-clipboard-check mr-3"></i>Notifications
                            @php
                                $mobileNotificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            @if($mobileNotificationCount > 0)
                                <span class="absolute top-2 right-3 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $mobileNotificationCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('citizen.dashboard') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-tachometer-alt mr-3"></i>Tableau de bord
                        </a>
                        <a href="{{ route('profile.edit') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-user mr-3"></i>Mon Profil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="mobile-menu-item">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-sign-out-alt mr-3"></i>Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mobile-menu-item block px-3 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-in-alt mr-3"></i>Connexion
                        </a>
                        <a href="{{ route('register') }}" class="mobile-menu-item block px-3 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors text-center">
                            <i class="fas fa-user-plus mr-2"></i>Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Spacer for fixed navbar -->
    <div class="h-16"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 no-print">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 no-print">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-auto no-print">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-xl mr-4">
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">PCT UVCI</h3>
                            <p class="text-blue-300">Services Numériques</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Plateforme de services administratifs en ligne pour faciliter vos démarches. 
                        Modernité, efficacité et sécurité au service des citoyens.
                    </p>
                    
                    <!-- Social Links -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 hover:bg-blue-500 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-700 hover:bg-blue-800 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 flex items-center">
                        <i class="fas fa-cogs mr-2 text-blue-400"></i>
                        Services
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('interactive-forms.index') }}" class="text-gray-300 hover:text-white hover:pl-2 transition-all duration-300 flex items-center group">
                                <i class="fas fa-file-alt mr-2 text-blue-400 group-hover:text-blue-300"></i>
                                Formulaires interactifs
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white hover:pl-2 transition-all duration-300 flex items-center group">
                                <i class="fas fa-search mr-2 text-green-400 group-hover:text-green-300"></i>
                                Suivi de demandes
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white hover:pl-2 transition-all duration-300 flex items-center group">
                                <i class="fas fa-credit-card mr-2 text-purple-400 group-hover:text-purple-300"></i>
                                Paiements en ligne
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white hover:pl-2 transition-all duration-300 flex items-center group">
                                <i class="fas fa-download mr-2 text-yellow-400 group-hover:text-yellow-300"></i>
                                Téléchargements
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Support & Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 flex items-center">
                        <i class="fas fa-headset mr-2 text-blue-400"></i>
                        Support
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-phone mr-3 text-green-400"></i>
                            <div>
                                <div class="text-sm">Téléphone</div>
                                <div class="font-semibold">+225 XX XX XX XX</div>
                            </div>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-envelope mr-3 text-blue-400"></i>
                            <div>
                                <div class="text-sm">Email</div>
                                <div class="font-semibold">contact@pct-uvci.ci</div>
                            </div>
                        </li>
                        <li class="flex items-start text-gray-300">
                            <i class="fas fa-map-marker-alt mr-3 text-red-400 mt-1"></i>
                            <div>
                                <div class="text-sm">Adresse</div>
                                <div class="font-semibold">Université Virtuelle de Côte d'Ivoire<br>Abidjan, Côte d'Ivoire</div>
                            </div>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <i class="fas fa-clock mr-3 text-yellow-400"></i>
                            <div>
                                <div class="text-sm">Heures d'ouverture</div>
                                <div class="font-semibold">Lun - Ven: 8h - 17h</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Newsletter Section -->
        <div class="bg-gray-800 border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h4 class="text-xl font-semibold mb-2">Restez informé</h4>
                    <p class="text-gray-400 mb-6">Recevez les dernières actualités et mises à jour de nos services</p>
                    <form class="max-w-md mx-auto flex">
                        <input type="email" placeholder="Votre adresse email" class="flex-1 px-4 py-3 bg-gray-700 text-white rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-r-lg font-semibold transition-colors duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="bg-gray-800 border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-4 md:mb-0">
                        <p>&copy; {{ date('Y') }} PCT UVCI. Tous droits réservés.</p>
                    </div>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Politique de confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Conditions d'utilisation</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Aide</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const icon = this.querySelector('i');
            
            if (mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                mobileMenu.classList.add('open');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                
                // Animate menu items
                setTimeout(() => {
                    const items = mobileMenu.querySelectorAll('.mobile-menu-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.transitionDelay = `${index * 0.1}s`;
                        }, index * 50);
                    });
                }, 100);
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.remove('open');
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
          // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                // Ignore empty hash or just '#'
                if (!href || href === '#' || href.length <= 1) {
                    return;
                }
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 500);
            });
        }, 5000);
        
        // Add loading states to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
                    
                    // Re-enable after 3 seconds (fallback)
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 3000);
                }
            });
        });
          // Newsletter form
        document.querySelector('footer form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'text-green-400 text-sm mt-2';
                successDiv.textContent = 'Merci ! Vous serez bientôt informé de nos actualités.';
                this.appendChild(successDiv);
                
                // Clear form
                this.querySelector('input[type="email"]').value = '';
                
                // Remove message after 3 seconds
                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
            }
        });        // Dropdown functionality
        function initializeDropdowns() {
            console.log('Initializing dropdowns...');
            
            // Profile dropdown
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');
            
            console.log('Profile toggle:', profileToggle);
            console.log('Profile dropdown:', profileDropdown);
              if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    console.log('👤 Profile toggle clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        if (dropdown !== this.parentElement) {
                            dropdown.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    this.parentElement.classList.toggle('active');
                    console.log('👤 Profile dropdown active:', this.parentElement.classList.contains('active'));
                });
            } else {
                console.error('❌ Profile dropdown elements not found');
            }
                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        if (dropdown !== this.parentElement) {
                            dropdown.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    this.parentElement.classList.toggle('active');
                    console.log('Profile dropdown toggled, active:', this.parentElement.classList.contains('active'));
                });
            }
            
            // Notification dropdown
            const notificationToggle = document.getElementById('notificationToggle');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            console.log('Notification toggle:', notificationToggle);
            console.log('Notification dropdown:', notificationDropdown);
            
            if (notificationToggle && notificationDropdown) {
                notificationToggle.addEventListener('click', function(e) {
                    console.log('Notification toggle clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        if (dropdown !== this.parentElement) {
                            dropdown.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    this.parentElement.classList.toggle('active');
                    console.log('Notification dropdown toggled, active:', this.parentElement.classList.contains('active'));
                    
                    // Load notifications if opening
                    if (this.parentElement.classList.contains('active')) {
                        loadNotifications();
                    }
                });
            }
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown.active').forEach(dropdown => {
                        dropdown.classList.remove('active');
                    });
                }
            });
        }

        // Load notifications via AJAX
        function loadNotifications() {
            const notificationsList = document.getElementById('notificationsList');
            if (!notificationsList) return;
            
            // Show loading
            notificationsList.innerHTML = `
                <div class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Chargement...</p>
                </div>
            `;
              // Fetch notifications
            fetch('{{ route("citizen.notifications.ajax") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.notifications && data.notifications.length > 0) {
                        notificationsList.innerHTML = data.notifications.map(notification => `
                            <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 notification-item" data-id="${notification.id}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">${notification.title}</div>
                                        <div class="text-xs text-gray-500 mt-1">${notification.message}</div>
                                        <div class="text-xs text-gray-400 mt-1">${notification.time_ago}</div>
                                    </div>
                                    <button onclick="markNotificationAsRead('${notification.id}')" class="text-blue-600 hover:text-blue-800 text-xs ml-2">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        notificationsList.innerHTML = `
                            <div class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>Aucune notification</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des notifications:', error);
                    notificationsList.innerHTML = `
                        <div class="px-4 py-8 text-center text-red-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Erreur de chargement</p>
                        </div>
                    `;
                });
        }        // Mark notification as read
        function markNotificationAsRead(notificationId) {
            // Utiliser le système de synchronisation global s'il est disponible
            if (window.notificationSync) {
                return window.notificationSync.markAsRead(notificationId);
            }
            
            // Fallback vers l'ancienne méthode
            fetch(`{{ route("citizen.notifications.read", "") }}/${notificationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove notification from UI
                    const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
                    if (notificationElement) {
                        notificationElement.remove();
                    }
                    
                    // Déclencher la synchronisation globale
                    if (window.notificationSync) {
                        window.notificationSync.syncAll();
                    } else {
                        updateNotificationBadge();
                    }
                    
                    // Reload notifications in dropdown
                    loadNotifications();
                    
                    // Déclencher l'événement personnalisé pour informer les autres composants
                    document.dispatchEvent(new CustomEvent('notificationRead', {
                        detail: { notificationId: notificationId }
                    }));
                }
            })
            .catch(error => {
                console.error('Erreur lors du marquage de la notification:', error);
            });
        }        // Mark all notifications as read
        function markAllAsRead() {
            // Utiliser le système de synchronisation global s'il est disponible
            if (window.notificationSync) {
                return window.notificationSync.markAllAsRead();
            }
            
            // Fallback vers l'ancienne méthode
            fetch('{{ route("citizen.notifications.read-all") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear notifications list
                    const notificationsList = document.getElementById('notificationsList');
                    if (notificationsList) {
                        notificationsList.innerHTML = `
                            <div class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>Aucune notification</p>
                            </div>
                        `;
                    }
                    
                    // Déclencher la synchronisation globale
                    if (window.notificationSync) {
                        window.notificationSync.syncAll();
                    } else {
                        updateNotificationBadge();
                    }
                    
                    // Déclencher l'événement personnalisé pour informer les autres composants
                    document.dispatchEvent(new CustomEvent('allNotificationsRead'));
                }
            })
            .catch(error => {
                console.error('Erreur lors du marquage des notifications:', error);
            });
        }// Update notification badge
        function updateNotificationBadge() {
            fetch('{{ route("citizen.notifications.ajax") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.style.display = 'flex';
                        } else {
                            // Créer le badge s'il n'existe pas
                            const notificationToggle = document.getElementById('notificationToggle');
                            if (notificationToggle) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center';
                                newBadge.textContent = data.count > 99 ? '99+' : data.count;
                                notificationToggle.appendChild(newBadge);
                            }
                        }
                    } else {
                        if (badge) {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du badge:', error);
                });
        }        // Initialize dropdowns when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeDropdowns();
            
            // Initial badge update
            updateNotificationBadge();
            
            // Auto-refresh notifications every 30 seconds
            setInterval(function() {
                updateNotificationBadge();
                // Also refresh the dropdown content if it's open
                const dropdown = document.querySelector('.dropdown.active #notificationToggle');
                if (dropdown) {
                    loadNotifications();
                }
            }, 30000);
        });
    </script>    
    <!-- Notification System -->
    <script src="{{ asset('js/notification-sync.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
