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
            from { opacity: 0; transform: translateX(-100%); }
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
        
        .sidebar {
            transition: transform 0.3s ease;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        @media (min-width: 1024px) {
            .sidebar.collapsed {
                transform: translateX(-220px);
            }
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
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold gradient-text">PCT UVCI</span>
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
                            </a>                        @endif
                          <!-- Notifications -->
                        <div class="relative">
                            <button id="notifications-btn" class="text-gray-700 hover:text-blue-600 transition-colors duration-200 relative">
                                <i class="fas fa-bell text-xl"></i>
                                @php
                                    $notificationCount = Auth::check() ? \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() : 0;
                                @endphp
                                @if($notificationCount > 0)
                                    <span id="notification-badge" class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    </span>
                                @else
                                    <span id="notification-badge" class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
                                @endif
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div id="notifications-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                                <div class="px-4 py-2 border-b bg-gray-50">
                                    <div class="flex justify-content-between align-items-center">
                                        <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                                        <button onclick="markAllNotificationsAsRead()" class="text-xs text-blue-600 hover:text-blue-800">
                                            Marquer tout comme lu
                                        </button>
                                    </div>
                                </div>
                                <div id="notifications-list" class="max-h-64 overflow-y-auto">
                                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                        Aucune notification
                                    </div>
                                </div>
                                <div class="px-4 py-2 border-t bg-gray-50">
                                    <a href="{{ route('citizen.notifications.center') }}" class="text-xs text-blue-600 hover:text-blue-800">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:block">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-user mr-2"></i>
                                    Mon Profil
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-cog mr-2"></i>
                                    Paramètres
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Se Déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Accueil</a>
                        <a href="{{ route('interactive-forms.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Services</a>
                        <a href="{{ route('choose.role') }}" class="text-gray-700 hover:text-blue-600 transition-colors duration-200">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary">
                            <i class="fas fa-user-plus"></i>
                            S'inscrire
                        </a>
                    @endauth
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
        <div id="mobile-menu" class="mobile-menu fixed top-16 left-0 w-full h-screen bg-white shadow-lg md:hidden z-40">
            <div class="p-4 space-y-4">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                        </a>
                    @elseif(auth()->user()->isAgent())
                        <a href="{{ route('agent.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Agent
                        </a>
                    @else
                        <a href="{{ route('citizen.dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>Mon Espace
                        </a>
                        <a href="{{ route('requests.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-file-alt mr-2"></i>Mes Demandes
                        </a>
                        <a href="{{ route('interactive-forms.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Formulaires
                        </a>
                    @endif
                    
                    <hr class="my-4">
                    <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-user mr-2"></i>Mon Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Se Déconnecter
                        </button>
                    </form>
                @else
                    <a href="{{ route('home') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">Accueil</a>
                    <a href="{{ route('interactive-forms.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">Services</a>
                    <a href="{{ route('choose.role') }}" class="block text-gray-700 hover:text-blue-600 transition-colors duration-200">Connexion</a>
                    <a href="{{ route('register') }}" class="block btn-primary w-full text-center mt-4">
                        <i class="fas fa-user-plus mr-2"></i>S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-20 right-4 z-50 no-print animate-fade-in">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg max-w-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 right-4 z-50 no-print animate-fade-in">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg max-w-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-auto no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- À propos -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-lg mr-3">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold">PCT UVCI</h3>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Plateforme moderne de services administratifs en ligne pour faciliter vos démarches citoyennes avec sécurité et efficacité.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-200">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors duration-200">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors duration-200">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Services -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Services</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('interactive-forms.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-edit mr-2 text-blue-400"></i>Formulaires Interactifs
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-green-400"></i>Documents Officiels
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-yellow-400"></i>Paiements en Ligne
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-headset mr-2 text-purple-400"></i>Support Client
                        </a></li>
                    </ul>
                </div>
                
                <!-- Informations -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Informations</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-400"></i>À Propos
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-question-circle mr-2 text-green-400"></i>FAQ
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-yellow-400"></i>Confidentialité
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-gavel mr-2 text-red-400"></i>Mentions Légales
                        </a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Contact</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
                            <span class="text-gray-300">Abidjan, Côte d'Ivoire</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3 text-green-400"></i>
                            <span class="text-gray-300">+225 XX XX XX XX</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-purple-400"></i>
                            <span class="text-gray-300">contact@pctuvci.ci</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 text-yellow-400"></i>
                            <span class="text-gray-300">Lun-Ven: 8h-17h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ligne de séparation -->
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} PCT UVCI. Tous droits réservés.
                    </p>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <span class="text-gray-400 text-sm">Plateforme sécurisée</span>
                        <div class="flex items-center">
                            <i class="fas fa-lock text-green-400 mr-1"></i>
                            <span class="text-green-400 text-sm">SSL</span>
                        </div>
                    </div>
                </div>
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

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.animate-fade-in');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);        // Smooth scrolling for anchor links
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

        // Loading state for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Chargement...';
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);        // Observe elements with animation class
        document.querySelectorAll('.hover-lift, .stat-card').forEach(el => {
            observer.observe(el);
        });        // Dropdown management
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) {
                return; // Simply return without warning since we now check upstream
            }
            
            const isVisible = !dropdown.classList.contains('hidden');
            
            // Hide all dropdowns first
            hideAllDropdowns();
            
            if (!isVisible) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('animate-fade-in');
            }
        }

        function hideAllDropdowns() {
            const dropdowns = ['user-dropdown', 'notifications-dropdown'];
            dropdowns.forEach(id => {
                const dropdown = document.getElementById(id);
                if (dropdown) {
                    dropdown.classList.add('hidden');
                    dropdown.classList.remove('animate-fade-in');
                }
            });
        }        // User menu dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuBtn = document.getElementById('user-menu-btn');
            const notificationsBtn = document.getElementById('notifications-btn');
            const userDropdown = document.getElementById('user-dropdown');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            
            // Only set up dropdown functionality if the elements exist
            if (!userDropdown && !notificationsDropdown) {
                return; // Exit early if no dropdowns are present
            }
            
            if (userMenuBtn) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleDropdown('user-dropdown');
                });
            }
            
            if (notificationsBtn) {
                notificationsBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleDropdown('notifications-dropdown');
                    loadNotifications();
                });
            }

            // Close dropdowns when clicking outside (only if dropdowns exist)
            document.addEventListener('click', function(e) {
                const userDropdown = document.getElementById('user-dropdown');
                const notificationsDropdown = document.getElementById('notifications-dropdown');
                const userMenuBtn = document.getElementById('user-menu-btn');
                const notificationsBtn = document.getElementById('notifications-btn');
                
                if (!userMenuBtn?.contains(e.target) && !userDropdown?.contains(e.target)) {
                    if (userDropdown) userDropdown.classList.add('hidden');
                }
                
                if (!notificationsBtn?.contains(e.target) && !notificationsDropdown?.contains(e.target)) {
                    if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
                }
            });

            // Load notifications on page load for authenticated users
            @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isCitizen())
            loadNotifications();
            
            // Auto-refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
            @endif
            @endauth
        });

        // Notifications functionality
        function loadNotifications() {
            @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isCitizen())
            fetch('{{ route("citizen.notifications.ajax") }}')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des notifications:', error);
                });
            @endif
            @endauth
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        function renderNotifications(notifications) {
            const list = document.getElementById('notifications-list');
            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = `
                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                        Aucune notification
                    </div>
                `;
                return;
            }

            list.innerHTML = notifications.map(notification => `
                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 ${notification.read_at ? '' : 'bg-blue-50'}" data-id="${notification.id}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas ${getNotificationIcon(notification.type)} text-blue-600"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${notification.time_ago}</p>
                        </div>
                        ${!notification.read_at ? `
                            <button onclick="markNotificationAsRead('${notification.id}')" class="flex-shrink-0 ml-2 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        }

        function getNotificationIcon(type) {
            const icons = {
                'request_update': 'fa-file-alt',
                'payment_success': 'fa-credit-card',
                'document_ready': 'fa-download',
                'system': 'fa-info-circle',
                'default': 'fa-bell'
            };
            return icons[type] || icons.default;
        }

        function markNotificationAsRead(notificationId) {
            @auth
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
                    loadNotifications(); // Reload notifications
                }
            })
            .catch(error => {
                console.error('Erreur lors du marquage de la notification:', error);
            });
            @endauth
        }

        function markAllNotificationsAsRead() {
            @auth
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
                    loadNotifications(); // Reload notifications
                }
            })
            .catch(error => {
                console.error('Erreur lors du marquage des notifications:', error);
            });
            @endauth        }
    </script>
    
    <!-- Notification System -->
    @auth
        @if(auth()->user()->isCitizen())
            <script src="{{ asset('js/notification-sync.js') }}"></script>
        @endif
    @endauth
    
    @stack('scripts')
</body>
</html>
