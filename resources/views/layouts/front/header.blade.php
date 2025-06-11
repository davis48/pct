<nav class="bg-gradient-to-r from-primary-600 to-primary-800 shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo et titre -->
            <a class="flex items-center space-x-3 text-white hover:text-blue-100 transition-all duration-300 transform hover:scale-105" href="{{ url('/') }}">
                <i class="fas fa-landmark text-2xl"></i>
                <span class="font-bold text-lg hidden md:block">Plateforme de demande d'acte civil</span>
                <span class="font-bold text-lg md:hidden">PCT UVCI</span>
            </a>
            
            <!-- Menu mobile toggle -->
            <button id="mobile-menu-toggle" class="md:hidden text-white focus:outline-none focus:ring-2 focus:ring-blue-300 p-2 rounded-lg">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Menu principal -->
            <div id="main-menu" class="hidden md:flex items-center space-x-1">
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('/') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ url('/') }}">
                    <i class="fas fa-home mr-2"></i>Accueil
                </a>
                @auth
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('*dashboard*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" 
                   href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isAgent()){{ route('agent.dashboard') }}@else{{ route('citizen.dashboard') }}@endif">
                    <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                </a>
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('requests*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ url('/requests') }}">
                    <i class="fas fa-file-alt mr-2"></i>Mes demandes
                </a>
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('formulaires*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('formulaires.index') }}">
                    <i class="fas fa-download mr-2"></i>Formulaires
                </a>
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('formulaires-interactifs*') || request()->is('interactive-forms*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('interactive-forms.index') }}">
                    <i class="fas fa-edit mr-2"></i>Formulaires Interactifs
                </a>
                <a class="px-4 py-2 rounded-full text-white transition-all duration-300 {{ request()->is('documents*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('documents.index') }}">
                    <i class="fas fa-folder-open mr-2"></i>Mes documents
                </a>
                @endauth
            </div>
            
            <!-- Menu utilisateur -->
            <div class="flex items-center space-x-4">
                @auth                    <!-- Notifications pour les citoyens -->
                    @if(Auth::check() && Auth::user()->isCitizen())
                        <div class="relative">
                            <button id="notifications-btn" class="text-white hover:text-blue-100 transition-colors duration-300 p-2 rounded-full hover:bg-white hover:bg-opacity-10 relative">
                                <i class="fas fa-bell text-lg"></i>
                                @php
                                    $notificationCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div id="notifications-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                                <div class="px-4 py-2 border-b bg-gray-50">
                                    <div class="flex justify-between items-center">
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
                    @endif
                    
                    <!-- Menu utilisateur avec dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-white hover:text-blue-100 transition-colors duration-300 p-2 rounded-full hover:bg-white hover:bg-opacity-10">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-8 h-8 rounded-full object-cover" alt="Photo de profil">
                            @else
                                <i class="fas fa-user-circle text-2xl"></i>
                            @endif
                            <span class="hidden md:block font-medium">{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</span>
                            <i class="fas fa-chevron-down text-sm transform transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-100 z-50">
                            <div class="p-3 border-b border-gray-100">
                                <div class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-id-badge mr-2"></i>
                                    {{ Auth::user()->isAdmin() ? 'Administrateur' : (Auth::user()->isAgent() ? 'Agent' : 'Citoyen') }}
                                </div>
                            </div>
                            
                            <div class="py-2">
                                @php
                                    $dashboardUrl = '/dashboard';
                                    if (Auth::user()->isAdmin()) {
                                        $dashboardUrl = '/admin/dashboard';
                                    } elseif (Auth::user()->isAgent()) {
                                        $dashboardUrl = '/agent/dashboard';
                                    }
                                @endphp
                                <a href="{{ url($dashboardUrl) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-3 text-blue-500"></i>
                                    Tableau de bord
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-user-edit mr-3 text-blue-500"></i>
                                    Modifier mon profil
                                </a>
                            </div>
                            
                            <div class="border-t border-gray-100 py-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Boutons de connexion/inscription -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('choose.role') }}" class="px-4 py-2 border-2 border-white text-white rounded-full hover:bg-white hover:text-primary-600 transition-all duration-300 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                        <a href="{{ url('/inscription') }}" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-full hover:bg-yellow-300 transition-all duration-300 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Inscription
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
            <div class="space-y-2">
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('/') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ url('/') }}">
                    <i class="fas fa-home mr-2"></i>Accueil
                </a>
                @auth
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('*dashboard*') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" 
                   href="@if(Auth::user()->isAdmin()){{ route('admin.dashboard') }}@elseif(Auth::user()->isAgent()){{ route('agent.dashboard') }}@else{{ route('citizen.dashboard') }}@endif">
                    <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                </a>
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('requests*') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ url('/requests') }}">
                    <i class="fas fa-file-alt mr-2"></i>Mes demandes
                </a>
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('formulaires*') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('formulaires.index') }}">
                    <i class="fas fa-download mr-2"></i>Formulaires
                </a>
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('formulaires-interactifs*') || request()->is('interactive-forms*') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('interactive-forms.index') }}">
                    <i class="fas fa-edit mr-2"></i>Formulaires Interactifs
                </a>
                <a class="block px-4 py-2 rounded-lg text-white transition-all duration-300 {{ request()->is('documents*') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }}" href="{{ route('documents.index') }}">
                    <i class="fas fa-folder-open mr-2"></i>Mes documents
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Script pour le menu mobile et Alpine.js -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                mobileMenu.classList.toggle('hidden');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });
        }
    });
</script>
