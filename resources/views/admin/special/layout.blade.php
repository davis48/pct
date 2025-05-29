<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Interface Admin Spéciale</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Chart.js -->    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stats-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .success-gradient {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .warning-gradient {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Form Controls */
        input, select, textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }
        
        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        .btn-success:hover {
            background-color: #059669;
        }
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f8fafc;
            font-weight: 600;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 gradient-bg shadow-2xl">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">Admin Spécial</h1>
                        <p class="text-blue-100 text-sm">Interface Avancée</p>
                    </div>
                </div>
            </div>

            <nav class="mt-8">
                <div class="px-6 space-y-2">
                    <a href="{{ route('admin.special.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Tableau de Bord
                    </a>

                    <a href="{{ route('admin.special.statistics') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.statistics') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistiques
                    </a>

                    <a href="{{ route('admin.special.system-info') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.system-info') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Infos Système
                    </a>

                    <a href="{{ route('admin.special.performance') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.performance') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Performance
                    </a>

                    <a href="{{ route('admin.special.maintenance') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.maintenance') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Maintenance
                    </a>

                    <a href="{{ route('admin.special.logs') }}" 
                       class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200 {{ request()->routeIs('admin.special.logs') ? 'bg-white bg-opacity-20' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Journaux
                    </a>
                </div>

                <div class="mt-8 px-6">                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse-slow mr-2"></div>
                            <span class="text-white text-sm">Système en ligne</span>
                        </div>
                        <p class="text-blue-100 text-xs mt-1">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Interface Admin Spéciale')</h2>
                        <p class="text-gray-600 text-sm">@yield('subtitle', 'Gestion avancée du système')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Dropdown des notifications -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- Notification 1 -->
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-info text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-800">Nouvelle demande reçue</p>
                                                <p class="text-xs text-gray-500 mt-1">Une nouvelle demande d'attestation a été soumise</p>
                                                <p class="text-xs text-gray-400 mt-1">Il y a 5 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Notification 2 -->
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-800">Demande approuvée</p>
                                                <p class="text-xs text-gray-500 mt-1">La demande REQ-2025-ABC123 a été approuvée</p>
                                                <p class="text-xs text-gray-400 mt-1">Il y a 1 heure</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Notification 3 -->
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-800">Système de sauvegarde</p>
                                                <p class="text-xs text-gray-500 mt-1">Sauvegarde automatique terminée avec succès</p>
                                                <p class="text-xs text-gray-400 mt-1">Il y a 2 heures</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Notification 4 -->
                                    <div class="px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-users text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-800">Nouvel agent inscrit</p>
                                                <p class="text-xs text-gray-500 mt-1">Un nouvel agent s'est inscrit sur la plateforme</p>
                                                <p class="text-xs text-gray-400 mt-1">Il y a 4 heures</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 border-t border-gray-200">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Voir toutes les notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">                            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                <a href="{{ route('admin.special.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tableau de bord</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Succès!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Erreur!</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
