<nav id="sidebarMenu" class="sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Utilisateurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                    <i class="fas fa-file-alt me-2"></i>
                    Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}" href="{{ route('admin.requests.index') }}">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Demandes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.special.statistics') ? 'active' : '' }}" href="{{ route('admin.special.statistics') }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Administration</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>
                    Voir le site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Déconnexion
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>
