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
                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->is('requests*') ? 'active bg-white bg-opacity-10' : '' }} hover-grow" href="{{ url('/requests') }}">
                        <i class="fas fa-file-alt me-2"></i>Faire une demande
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
                    <div class="dropdown">
                        <button class="btn btn-link nav-link dropdown-toggle text-white text-decoration-none" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userMenuButton">
                            <li><a class="dropdown-item" href="{{ url('/dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                            </a></li>
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
                        <a href="{{ url('/connexion') }}" class="btn btn-outline-light btn-sm px-3 hover-grow" style="border-width: 2px; font-weight: 500;">
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
</style>
