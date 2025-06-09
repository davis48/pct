<nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center transition-all hover-lift" href="{{ url('/') }}">
            <i class="fas fa-landmark me-2 fa-lg"></i>
            <span class="fw-bold">Plateforme de demande d'acte civil</span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mx-2">
                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('/') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ url('/') }}">
                        <i class="fas fa-home me-2"></i>Accueil
                    </a>
                </li>
                @auth
                <li class="nav-item mx-2">
                    @if(Auth::user()->isAdmin())
                        <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('admin/dashboard') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ route('admin.dashboard') }}">
                    @elseif(Auth::user()->isAgent())
                        <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('agent/dashboard') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ route('agent.dashboard') }}">
                    @else
                        <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('citizen/dashboard') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ route('citizen.dashboard') }}">
                    @endif
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('requests*') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ url('/requests') }}">
                        <i class="fas fa-file-alt me-2"></i>Mes demandes
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('documents*') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ route('documents.index') }}">
                        <i class="fas fa-folder-open me-2"></i>Mes documents
                    </a>
                </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center">
                @if (Auth::check())
                    <!-- Notification Bell pour les citoyens -->
                    @if(Auth::user()->isCitizen())
                        <div class="notification-bell-header me-3">
                            <a href="{{ route('citizen.notifications.center') }}" class="btn btn-link text-white text-decoration-none p-2" title="Centre de notifications">
                                <i class="fas fa-bell fa-lg"></i>
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="notification-badge badge rounded-pill bg-danger">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        <span class="visually-hidden">notifications non lues</span>
                                    </span>
                                @endif
                            </a>
                        </div>
                    @endif
                    
                    <div class="dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle text-white text-decoration-none d-flex align-items-center" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="rounded-circle me-2" width="30" height="30" alt="Photo de profil">
                            @else
                                <i class="fas fa-user-circle me-2 fa-lg"></i>
                            @endif
                            <span>{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenuButton">
                            <li class="px-3 py-1 text-muted small">
                                <i class="fas fa-id-badge me-1"></i>{{ Auth::user()->isAdmin() ? 'Administrateur' : (Auth::user()->isAgent() ? 'Agent' : 'Citoyen') }}
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                @php
                                    $dashboardUrl = '/dashboard';
                                    if (Auth::user()->isAdmin()) {
                                        $dashboardUrl = '/admin/dashboard';
                                    } elseif (Auth::user()->isAgent()) {
                                        $dashboardUrl = '/agent/dashboard';
                                    }
                                @endphp
                                <a class="dropdown-item" href="{{ url($dashboardUrl) }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('choose.role') }}" class="btn btn-outline-light btn-sm px-3 hover-grow" style="border-width: 2px; font-weight: 500;">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                        <a href="{{ url('/inscription') }}" class="btn btn-warning btn-sm px-3 hover-grow text-dark" style="font-weight: 500; background-color: #ffc107;">
                            <i class="fas fa-user-plus me-2"></i>Inscription
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Ajout du CSS pour les animations -->
<style>
.hover-grow {
    transition: all 0.3s ease;
}
.hover-grow:hover {
    transform: scale(1.05);
}

.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
}

.navbar .nav-link {
    transition: all 0.3s ease;
    position: relative;
}

.navbar .nav-link:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background-color: white;
    transition: all 0.3s ease;
}

.navbar .nav-link:hover:after {
    width: 80%;
    left: 10%;
}

.transition-all {
    transition: all 0.3s ease;
}

.dropdown-menu {
    animation: dropdownFade 0.3s ease;
}

@keyframes dropdownFade {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Styles personnalisés pour les boutons de la navbar */
.navbar .btn-outline-light {
    border-color: rgba(255, 255, 255, 0.8);
    color: rgba(255, 255, 255, 0.9);
}

.navbar .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #ffffff;
    color: #ffffff;
}

.navbar .btn-warning {
    border: none;
    color: #2b2b2b;
}

.navbar .btn-warning:hover {
    background-color: #ffcd39;
    color: #000000;
}

.navbar .nav-link {
    color: rgba(255, 255, 255, 0.9);
}

.navbar .nav-link:hover {
    color: #ffffff;
}

.navbar .dropdown-item {
    transition: all 0.2s ease;
}

.navbar .dropdown-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.25rem;
}

.navbar .dropdown-item.text-danger:hover {
    background-color: #dc3545;
    color: white !important;
}

/* CSS pour la cloche de notification dans l'en-tête */
.notification-bell-header {
    position: relative;
    transition: all 0.3s ease;
}

.notification-bell-header:hover {
    transform: scale(1.1);
}

.notification-bell-header .fa-bell {
    animation: gentle-bell-shake 3s ease-in-out infinite;
}

@keyframes gentle-bell-shake {
    0%, 90%, 100% { transform: rotate(0deg); }
    2%, 6%, 10% { transform: rotate(8deg); }
    4%, 8% { transform: rotate(-8deg); }
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: badge-pulse 2s ease-in-out infinite;
}

@keyframes badge-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

/* Responsive design */
@media (max-width: 768px) {
    .notification-bell-header {
        margin-right: 0.5rem;
    }

    .notification-badge {
        font-size: 9px;
        min-width: 16px;
        height: 16px;
    }
}
</style>

<!-- Script pour mise à jour automatique du compteur de notifications -->
<script>
    // Fonction pour mettre à jour le compteur de notifications dans l'en-tête
    function updateNotificationBadge() {
        @if(Auth::check() && Auth::user()->isCitizen())
        fetch('{{ route("citizen.notifications") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                const bellIcon = document.querySelector('.notification-bell-header .fa-bell');
                
                if (data.count > 0) {
                    if (!badge) {
                        // Créer le badge s'il n'existe pas
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge badge rounded-pill bg-danger';
                        newBadge.innerHTML = `${data.count > 99 ? '99+' : data.count}<span class="visually-hidden">notifications non lues</span>`;
                        document.querySelector('.notification-bell-header a').appendChild(newBadge);
                    } else {
                        // Mettre à jour le badge existant
                        badge.innerHTML = `${data.count > 99 ? '99+' : data.count}<span class="visually-hidden">notifications non lues</span>`;
                    }
                    
                    // Ajouter l'animation si il y a des notifications
                    if (bellIcon) {
                        bellIcon.style.animation = 'gentle-bell-shake 3s ease-in-out infinite';
                    }
                } else {
                    // Supprimer le badge s'il n'y a pas de notifications
                    if (badge) {
                        badge.remove();
                    }
                    
                    // Arrêter l'animation
                    if (bellIcon) {
                        bellIcon.style.animation = 'none';
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la mise à jour du compteur de notifications:', error));
        @endif
    }
    
    // Mettre à jour le compteur toutes les 30 secondes
    setInterval(updateNotificationBadge, 30000);
    
    // Première mise à jour après 5 secondes
    setTimeout(updateNotificationBadge, 5000);
</script>
