<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la demande | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
        /* Styles pour les toggles de notification */
        input[type="checkbox"] + label {
            transition: background-color 0.3s ease;
            position: relative;
        }
        
        input[type="checkbox"] + label:before {
            content: "";
            position: absolute;
            top: 0.125rem;
            left: 0.125rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background-color: white;
            transition: transform 0.3s ease;
        }
        
        input[type="checkbox"]:checked + label:before {
            transform: translateX(1rem);
        }
        
        /* Animation des notifications */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
        
        .notification-pulse {
            animation: pulse 2s infinite;
        }

        /* Styles pour la navbar moderne */
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
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(25, 118, 210, 0.2);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
            transition: all 0.3s ease;
        }
        
        /* Logo pour navbar bleue */
        .logo-gradient {
            color: white;
            font-weight: bold;
        }
        
        /* Liens navbar - couleurs claires pour fond bleu */
        .navbar-link {
            color: rgba(255, 255, 255, 0.9) !important;
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
            background: rgba(255, 255, 255, 0.2);
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
        }        /* Dropdown styles - harmonisés avec le dashboard */
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
            display: block;
            text-decoration: none;
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
        
        /* Mobile menu - fond bleu */
        .mobile-menu {
            background: #1565c0;
            backdrop-filter: blur(10px);
        }
        
        /* Avatar utilisateur - plus visible */
        .user-avatar {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        
        /* Styles additionnels pour la page */
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
        }

        /* Styles supplémentaires pour navbar bleue */
        .navbar-fixed {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Accentuation des liens navbar */
        .navbar-link {
            color: white !important;
            font-weight: 600 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-link:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        
        .navbar-link.active {
            background: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.3);
        }
        
        /* Amélioration des icônes */
        .navbar-fixed i {
            color: rgba(255, 255, 255, 0.95) !important;
        }
        
        /* Adaptation badges notifications */
        .notification-badge {
            background: #ff3d00 !important;
            box-shadow: 0 0 0 2px #1565c0;
        }
        
        /* Style spécifique pour boutons et dropdown */
        .navbar-fixed button[type="submit"],
        .navbar-fixed a.dropdown-item {
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .mobile-menu a, 
            .mobile-menu button {
                color: white !important;
            }
            
            .mobile-menu a:hover, 
            .mobile-menu button:hover {
                background: rgba(255, 255, 255, 0.15) !important;
            }
        }
    </style>
    
    <!-- Navbar fixe professionnelle -->
    <nav class="navbar-fixed no-print">
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
                                        <span id="notification-badge" class="notification-badge absolute -top-1 -right-1">
                                            {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                        </span>
                                    @else
                                        <span id="notification-badge" class="notification-badge absolute -top-1 -right-1" style="display: none;">0</span>
                                    @endif
                                </button>                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-1 transform scale-100"
                                     class="absolute right-0 mt-2 w-80 dropdown-content z-50">
                                    <div class="py-3 px-4 bg-blue-50 border-b border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <h3 class="font-semibold text-gray-800">Notifications</h3>
                                            @if($notificationCount > 0)
                                                <button onclick="markAllNotificationsAsRead()" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-check-double mr-1"></i> Tout marquer lu
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="notification-list" class="max-h-64 overflow-y-auto">
                                        <div class="px-4 py-6 text-center text-gray-500">
                                            <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent mx-auto mb-2"></div>
                                            Chargement...
                                        </div>
                                    </div>
                                    <a href="{{ route('citizen.notifications.center') }}" class="dropdown-item border-t border-gray-200 text-center text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-bell mr-1"></i> Voir toutes les notifications
                                    </a>
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
                                 class="absolute right-0 mt-2 dropdown-content z-50 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100 w-56">
                                <div class="py-3 px-4 bg-blue-50 border-b border-blue-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->prenoms }} {{ Auth::user()->nom }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item flex items-center">
                                    <i class="fas fa-user mr-3 text-blue-600 w-5 text-center"></i>
                                    <span>Mon Profil</span>
                                </a>
                                <a href="{{ route('citizen.dashboard') }}" class="dropdown-item flex items-center">
                                    <i class="fas fa-tachometer-alt mr-3 text-green-600 w-5 text-center"></i>
                                    <span>Tableau de bord</span>
                                </a>
                                <a href="{{ route('citizen.notifications.center') }}" class="dropdown-item flex items-center">
                                    <i class="fas fa-bell mr-3 text-yellow-600 w-5 text-center"></i>
                                    <span>Notifications</span>
                                    @if($notificationCount > 0)
                                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $notificationCount }}</span>
                                    @endif
                                </a>
                                <hr class="border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger w-full text-left flex items-center">
                                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>                    @else
                        <a href="{{ route('choose.role') }}" class="navbar-link">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors duration-200 font-medium border border-white hover:border-blue-100">
                            <i class="fas fa-user-plus mr-2"></i>
                            S'inscrire
                        </a>
                    @endauth

                    <!-- Bouton menu mobile -->
                    <div class="md:hidden" x-data="{ mobileOpen: false }">
                        <button @click="mobileOpen = !mobileOpen" class="navbar-link">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Menu Mobile -->
                        <div x-show="mobileOpen" 
                             @click.away="mobileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-1 transform translate-y-0"
                             class="fixed inset-x-0 top-20 md:hidden mobile-menu border-t border-gray-200">                            <div class="px-4 py-6 space-y-3 bg-blue-700 shadow-lg border-t border-blue-800">
                                @auth
                                    @if(auth()->user()->isCitizen())
                                        <a href="{{ route('citizen.dashboard') }}" class="block navbar-link text-white">
                                            <i class="fas fa-home mr-2"></i>Accueil
                                        </a>
                                        <a href="{{ route('requests.index') }}" class="block navbar-link text-white">
                                            <i class="fas fa-folder-open mr-2"></i>Mes Demandes
                                        </a>
                                        <a href="{{ route('interactive-forms.index') }}" class="block navbar-link text-white">
                                            <i class="fas fa-edit mr-2"></i>Formulaires
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="block navbar-link text-white">
                                            <i class="fas fa-user mr-2"></i>Mon Profil
                                        </a>                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left navbar-link danger">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>    <div class="min-h-screen bg-gray-50 py-8 pt-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails de la demande</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header de la demande -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $request->description }}
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Référence: {{ $request->reference_number }} • Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end">
                            @switch($request->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i> En attente
                                    </span>
                                    @break
                                @case('in_progress')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-cog mr-2"></i> En cours de traitement
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-2"></i> Approuvé
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Terminé
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-2"></i> Rejeté
                                    </span>
                                    @break
                                @case('draft')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-edit mr-2"></i> Brouillon
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($request->status) }}
                                    </span>
                            @endswitch
                            
                            @if($request->payment_status)
                                @switch($request->payment_status)
                                    @case('paid')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Payé
                                        </span>
                                        @break
                                    @case('unpaid')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-credit-card mr-1"></i> Non payé
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Paiement en cours
                                        </span>
                                        @break
                                @endswitch
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Actions</h3>                    <div class="flex flex-wrap gap-3">
                        @if(in_array($request->status, ['approved', 'completed']) && $request->payments()->where('status', 'completed')->exists())
                            @php
                                $documentName = 'le document';
                                if ($request->description) {
                                    $desc = strtolower($request->description);
                                    if (str_contains($desc, 'extrait') && str_contains($desc, 'naissance')) {
                                        $documentName = "l'extrait de naissance";
                                    } elseif (str_contains($desc, 'certificat') && str_contains($desc, 'mariage')) {
                                        $documentName = "le certificat de mariage";
                                    } elseif (str_contains($desc, 'certificat') && str_contains($desc, 'décès')) {
                                        $documentName = "le certificat de décès";
                                    } elseif (str_contains($desc, 'certificat') && str_contains($desc, 'célibat')) {
                                        $documentName = "le certificat de célibat";
                                    } elseif (str_contains($desc, 'attestation') && str_contains($desc, 'domicile')) {
                                        $documentName = "l'attestation de domicile";
                                    } elseif (str_contains($desc, 'légalisation')) {
                                        $documentName = "la légalisation";
                                    } elseif (str_contains($desc, 'carte') && str_contains($desc, 'identité')) {
                                        $documentName = "la carte d'identité";
                                    } elseif (str_contains($desc, 'passeport')) {
                                        $documentName = "le passeport";
                                    }
                                }
                            @endphp
                            <a href="{{ route('documents.download', $request) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger {{ $documentName }}
                            </a>
                        @endif
                          <button type="button" onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-print mr-2"></i>
                            Imprimer la page
                        </button>
                          <a href="{{ route('citizen.dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations de la demande -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Détails de la demande -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informations de la demande
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Type de demande</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->description }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Référence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <code class="bg-gray-100 px-2 py-1 rounded">{{ $request->reference_number }}</code>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de soumission</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Urgence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @switch($request->urgency)
                                        @case('urgent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Urgent
                                            </span>
                                            @break
                                        @case('normal')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-clock mr-1"></i> Normal
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($request->urgency) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Paiement -->
                @if($request->payment_required)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-credit-card mr-2 text-green-600"></i>
                            Informations de paiement
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Statut du paiement</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @switch($request->payment_status)
                                        @case('paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Payé
                                            </span>
                                            @break
                                        @case('unpaid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Non payé
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> En cours
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($request->payment_status) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Montant</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @php
                                        $amount = 500; // Montant par défaut
                                        
                                        // Essayer d'abord de récupérer le montant du paiement existant
                                        $payment = $request->payments()->latest()->first();
                                        if ($payment && $payment->amount) {
                                            $amount = $payment->amount;
                                        } else {
                                            // Sinon, calculer le montant basé sur le type de document
                                            $amount = \App\Services\PaymentService::getPriceForDocumentType($request->type ?? 'default');
                                        }
                                    @endphp
                                    <span class="font-semibold">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                                </dd>
                            </div>
                            @if($request->payments()->where('status', 'completed')->exists())
                                @php $payment = $request->payments()->where('status', 'completed')->first(); @endphp
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($payment->payment_method ?? 'N/A') }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Date de paiement</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->completed_at ? $payment->completed_at->format('d/m/Y à H:i') : 'N/A' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">ID Transaction</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <code class="bg-gray-100 px-2 py-1 rounded">{{ $payment->transaction_id ?? 'N/A' }}</code>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            <!-- Données du formulaire -->
            @if($request->additional_data)
                @php
                    $additionalData = json_decode($request->additional_data, true);
                    $formData = $additionalData['form_data'] ?? [];
                @endphp
                @if(!empty($formData))
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                            Informations saisies
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach($formData as $key => $value)
                                @if(!is_array($value) && !empty($value))
                                    <div class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $value }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
                @endif
            @endif

            <!-- Pièces jointes -->
            @if($request->attachments && $request->attachments->count() > 0)
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-paperclip mr-2 text-yellow-600"></i>
                        Pièces jointes
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @foreach($request->attachments as $attachment)
                        <li class="px-4 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-3 text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $attachment->file_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $attachment->file_type }} • {{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Voir
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; 2025 PCT UVCI. Tous droits réservés.</p>
                <p class="text-gray-400 text-sm mt-2">Suivi de vos demandes en temps réel</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
            });
        }

        // User dropdown toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }        // Chargement initial des notifications
        document.addEventListener('DOMContentLoaded', function() {
            // Précharger les notifications pour mettre à jour le badge
            loadNotifications();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (userDropdown && !userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
            if (notificationsDropdown && !notificationsDropdown.classList.contains('hidden')) {
                notificationsDropdown.classList.add('hidden');
            }
        });        // Navbar scroll effect - maintient le style bleu
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-fixed');
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 10) {
                navbar.style.boxShadow = '0 4px 12px rgba(13, 71, 161, 0.4)';
                navbar.style.background = 'linear-gradient(135deg, #1976d2 0%, #1565c0 70%, #0d47a1 100%)';
            } else {
                navbar.style.boxShadow = '0 2px 8px rgba(13, 71, 161, 0.3)';
                navbar.style.background = 'linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%)';
            }
            
            lastScrollTop = scrollTop;
        });

        // Load notifications function
        function loadNotifications() {
            const notificationsList = document.getElementById('notification-list');
            const notificationBadge = document.getElementById('notification-badge');
            
            // Afficher l'état de chargement
            notificationsList.innerHTML = `
                <div class="px-4 py-6 text-center text-gray-500">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mb-2"></div>
                    <p>Chargement des notifications...</p>
                </div>
            `;            // Charger les notifications générales de l'utilisateur
            const url = '/citizen/notifications';
            
            fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    notificationsList.innerHTML = data.notifications.map(notification => {
                        let icon = 'fas fa-bell';
                        let iconColor = 'text-blue-500';
                        
                        // Assigner des icônes selon le type de notification
                        if (notification.data && notification.data.type) {
                            switch(notification.data.type) {
                                case 'status_update':
                                    icon = 'fas fa-sync';
                                    iconColor = 'text-green-500';
                                    break;
                                case 'payment':
                                    icon = 'fas fa-money-bill';
                                    iconColor = 'text-emerald-500';
                                    break;
                                case 'document':
                                    icon = 'fas fa-file-alt';
                                    iconColor = 'text-amber-500';
                                    break;
                                case 'alert':
                                    icon = 'fas fa-exclamation-triangle';
                                    iconColor = 'text-red-500';
                                    break;
                            }
                        }
                        
                        return `                            <div class="dropdown-item ${notification.is_read ? '' : 'bg-blue-50'}">
                                <div class="flex items-start">
                                    <div class="mr-3 mt-1">
                                        <i class="${icon} ${iconColor} w-5 text-center"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">${notification.title || (notification.data ? notification.data.title : '') || 'Notification'}</p>
                                        <p class="text-sm text-gray-600 mt-1">${notification.message || (notification.data ? notification.data.message : '')}</p>
                                        <p class="text-xs text-gray-400 mt-1">${notification.created_at}</p>
                                    </div>
                                    ${!notification.is_read ? '<div class="ml-2 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>' : ''}
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    // Mettre à jour le badge avec le nombre exact
                    const unreadCount = data.unread_count || data.notifications.filter(n => !n.is_read).length;
                    if (unreadCount > 0) {
                        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        notificationBadge.style.display = 'flex';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                } else {                    notificationsList.innerHTML = `
                        <div class="dropdown-item text-center py-4">
                            <i class="fas fa-bell-slash text-xl mb-2 text-gray-400"></i>
                            <p class="text-gray-500">Aucune notification</p>
                        </div>
                    `;
                    notificationBadge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des notifications:', error);
                notificationsList.innerHTML = `
                    <div class="dropdown-item text-center py-4">
                        <i class="fas fa-exclamation-circle text-xl mb-2 text-red-500"></i>
                        <p class="text-red-500 mb-2">Erreur lors du chargement</p>
                        <button onclick="loadNotifications()" class="mt-1 text-sm text-blue-600 hover:underline">
                            <i class="fas fa-redo mr-1"></i> Réessayer
                        </button>
                    </div>
                `;
            });
        }

        // Mark all notifications as read
        function markAllNotificationsAsRead() {
            fetch('/citizen/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour des notifications:', error);
            });
        }
    </script>
</body>
</html>
