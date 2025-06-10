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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
                            </a>
                        @endif
                        
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
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
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
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 no-print">
    
    <!-- Flash Messages -->
    @if(session()->has('payment_success') || session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
        <div id="flash-messages" class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4">
            @if(session('payment_success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg mb-4 animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-green-800">Paiement réussi !</h3>
                            <div class="mt-1 text-sm text-green-700">{{ session('payment_success') }}</div>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-green-800">Succès !</h3>
                            <div class="mt-1 text-sm text-green-700">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">Erreur !</h3>
                            <div class="mt-1 text-sm text-red-700">{{ session('error') }}</div>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow-lg mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-yellow-800">Attention !</h3>
                            <div class="mt-1 text-sm text-yellow-700">{{ session('warning') }}</div>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-lg mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-blue-800">Information</h3>
                            <div class="mt-1 text-sm text-blue-700">{{ session('info') }}</div>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/front.js') }}" defer></script>
    <script>
        // Modern JavaScript for enhanced UI interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages after 8 seconds (for payment success, longer to read)
            setTimeout(function() {
                const flashMessages = document.querySelectorAll('#flash-messages > div');
                flashMessages.forEach(function(alert) {
                    if (alert.textContent.includes('Paiement')) {
                        // Longer time for payment success messages
                        setTimeout(function() {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-20px)';
                            setTimeout(function() {
                                alert.remove();
                            }, 300);
                        }, 8000);
                    } else {
                        // Standard time for other messages
                        setTimeout(function() {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-20px)';
                            setTimeout(function() {
                                alert.remove();
                            }, 300);
                        }, 5000);
                    }
                });
            }, 100);
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>