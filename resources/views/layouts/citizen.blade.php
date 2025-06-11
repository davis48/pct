<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Citoyen - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #1e40af;
            min-height: 100vh;
            width: 250px;
        }
        .sidebar .nav-link {
            color: #dbeafe;
            border-radius: 0.375rem;
            margin: 0.125rem 0;
        }
        .sidebar .nav-link:hover {
            background-color: #1d4ed8;
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: #1e3a8a;
            color: white;
        }
        .main-content {
            flex: 1;
            background-color: #f8f9fa;
        }
        .top-header {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white p-3">
            <div class="mb-4">
                <h2 class="h4 mb-0">Espace Citoyen</h2>
            </div>
            <nav>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('citizen.dashboard') }}" class="nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-city me-2"></i>
                            Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('interactive-forms.index') }}" class="nav-link {{ request()->routeIs('interactive-forms.*') ? 'active' : '' }}">
                            <i class="fas fa-file-signature me-2"></i>
                            Nouvelle demande
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('interactive-forms.index') }}" class="nav-link {{ request()->routeIs('interactive-forms.*') ? 'active' : '' }}">
                            <i class="fas fa-stamp me-2"></i>
                            Formulaires Interactifs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('requests.index') }}" class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                            <i class="fas fa-folder-open me-2"></i>
                            Mes demandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                            <i class="fas fa-archive me-2"></i>
                            Documents disponibles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="fas fa-id-card me-2"></i>
                            Mon profil
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top header -->
            <header class="top-header">
                <div class="container-fluid py-3 px-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-dark">
                        @yield('header')
                    </h3>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-3">{{ Auth::user()->nom }} {{ Auth::user()->prenoms }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
