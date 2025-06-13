<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Interface Agent PCT UVCI</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Thème Cocody -->
    <link rel="stylesheet" href="{{ asset('css/cocody-theme.css') }}">

    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .sidebar-gradient {
            background: linear-gradient(145deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
            box-shadow: 2px 0 15px rgba(25, 118, 210, 0.15);
        }

        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(25, 118, 210, 0.1);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.08);
        }

        /* Uniformisation fond blanc */
        body {
            background-color: #ffffff !important;
        }
        
        .main-content {
            background-color: #ffffff !important;
        }
        
        /* Assurer la lisibilité sur fond blanc */
        .text-dark {
            color: #1f2937 !important;
        }
        
        .bg-white-uniform {
            background-color: #ffffff !important;
            color: #1f2937 !important;
        }

        .nav-item {
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 4px solid #43a047;
            font-weight: 600;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .notification-badge {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            animation: bounce 1s infinite;
            border: 2px solid white;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-8px,0); }
            70% { transform: translate3d(0,-4px,0); }
            90% { transform: translate3d(0,-2px,0); }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Sidebar mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-cloak>
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">

                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Fermer le menu</span>
                            <i class="fas fa-times h-6 w-6 text-white"></i>
                        </button>
                    </div>

                    <!-- Sidebar mobile content -->
                    @include('layouts.partials.agent-sidebar')
                </div>
            </div>
        </div>

        <!-- Sidebar desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            @include('layouts.partials.agent-sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Ouvrir le menu</span>
                    <i class="fas fa-bars h-5 w-5"></i>
                </button>

                <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="relative flex flex-1">
                        <!-- Search bar -->
                        <div class="relative w-full max-w-lg">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="text"
                                   placeholder="Rechercher une demande..."
                                   class="block w-full rounded-md border-0 py-1.5 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                   x-data="{ searchQuery: '' }"
                                   x-model="searchQuery"
                                   @keyup.enter="window.location.href = '/agent/requests?search=' + encodeURIComponent(searchQuery)">
                        </div>
                    </div>

                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications -->
                        <a href="{{ route('agent.notifications.index') }}" 
                           class="relative -m-2.5 p-2.5 text-gray-400 hover:text-gray-500"
                           x-data="{ count: 0 }"
                           x-init="fetch('/agent/notifications/count')
                                .then(response => response.json())
                                .then(data => count = data.count);
                                setInterval(() => {
                                    fetch('/agent/notifications/count')
                                        .then(response => response.json())
                                        .then(data => count = data.count);
                                }, 30000)">
                            <span class="sr-only">Voir les notifications</span>
                            <i class="fas fa-bell h-6 w-6"></i>
                            <template x-if="count > 0">
                                <span class="notification-badge absolute -top-1 -right-1 h-5 w-5 rounded-full text-xs text-white flex items-center justify-center">
                                    <span x-text="count > 99 ? '99+' : count"></span>
                                </span>
                            </template>
                        </a>

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="-m-1.5 flex items-center p-1.5" id="user-menu-button">
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr(auth()->user()->prenoms, 0, 1) }}{{ substr(auth()->user()->nom, 0, 1) }}
                                    </span>
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900">{{ auth()->user()->prenoms }} {{ auth()->user()->nom }}</span>
                                    <i class="fas fa-chevron-down ml-2 h-5 w-5 text-gray-400"></i>
                                </span>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5"
                                 x-cloak>
                                <a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-6 bg-white-uniform">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 bg-white">
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 animate-pulse" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle h-5 w-5 text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-times h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 animate-pulse" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle h-5 w-5 text-red-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
