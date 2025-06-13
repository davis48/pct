<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/standalone-hover-effects.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
            border-bottom: 1px solid rgba(25, 118, 210, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .navbar-icon {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .navbar-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: white;
        }

        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        @media (min-width: 1024px) {
            .page-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .page-header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header-icon {
            color: #1976d2;
            font-size: 1.5rem;
        }

        .page-header-content p {
            color: #6b7280;
            font-size: 1rem;
        }

        .requests-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .requests-header {
            background: #f8fafc;
            padding: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .requests-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .requests-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .requests-meta {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .requests-content {
            padding: 0;
        }

        .request-card {
            padding: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .request-card:last-child {
            border-bottom: none;
        }

        .request-card:hover {
            background: #f8fafc;
        }

        .request-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .request-ref {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }

        .request-date {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-en_attente {
            background: #fef3c7;
            color: #d97706;
        }

        .status-en_cours {
            background: #dbeafe;
            color: #2563eb;
        }
          .status-approved, .status-processed, .status-ready, .status-completed {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-in_progress, .status-en_cours {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .request-details {
            margin-bottom: 1rem;
        }

        .request-title {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .request-description {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .request-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: inherit;
        }

        .btn-primary {
            background: #1976d2;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            color: #374151;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
        }
        
        .btn-download-approved {
            background: #16a34a !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
            animation: subtle-pulse 2s infinite;
        }
        
        @keyframes subtle-pulse {
            0% {
                box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
            }
            50% {
                box-shadow: 0 4px 12px rgba(22, 163, 74, 0.5);
            }
            100% {
                box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
            }
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .table-view {
            display: none;
        }

        @media (min-width: 1024px) {
            .card-view {
                display: none;
            }

            .table-view {
                display: block;
            }

            .requests-table {
                width: 100%;
                border-collapse: collapse;
            }

            .requests-table th {
                background: #f9fafb;
                padding: 1rem;
                text-align: left;
                font-weight: 600;
                color: #374151;
                font-size: 0.875rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .requests-table td {
                padding: 1rem;
                border-bottom: 1px solid #f3f4f6;
                vertical-align: middle;
            }

            .requests-table tr:hover {
                background: #f8fafc;
            }
        }

        .create-button {
            background: #1976d2;
            color: white;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .create-button:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
            color: white;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .alert-message {
            font-weight: 500;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                PCT UVCI
            </a>

            <div class="navbar-nav">
                <a href="{{ route('citizen.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Mon Espace
                </a>
                <a href="{{ route('interactive-forms.index') }}" class="nav-link">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle demande
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="container">
            <!-- En-tête -->
            <div class="page-header">
                <div class="page-header-content">
                    <h1>
                        <i class="fas fa-folder-open page-header-icon"></i>
                        Mes demandes
                    </h1>
                    <p>Suivez le statut de toutes vos demandes de documents</p>
                </div>
                <a href="{{ route('interactive-forms.index') }}" class="create-button">
                    <i class="fas fa-plus-circle"></i>
                    Nouvelle demande
                </a>
            </div>

            <!-- Messages de succès -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <span class="alert-message">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Contenu principal -->
            <div class="requests-container">
                @if(count($requests) > 0)
                    <div class="requests-header">
                        <div class="requests-header-content">
                            <h2 class="requests-title">
                                {{ count($requests) }} demande{{ count($requests) > 1 ? 's' : '' }} trouvée{{ count($requests) > 1 ? 's' : '' }}
                            </h2>
                            <div class="requests-meta">
                                Dernière mise à jour : {{ now()->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Vue Desktop : Tableau -->
                    <div class="table-view">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Document</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                <tr>
                                    <td style="font-weight: 500; color: #1f2937;">#{{ $request->reference_number }}</td>
                                    <td style="color: #6b7280;">{{ ucfirst($request->type) }}</td>
                                    <td style="color: #6b7280;">{{ $request->document->name ?? 'N/A' }}</td>
                                    <td style="color: #9ca3af; font-size: 0.875rem;">{{ $request->created_at->format('d/m/Y') }}</td>                                    <td>
                                        <span class="status-badge status-{{ $request->status }}">
                                            @switch($request->status)
                                                @case('en_attente')
                                                @case('pending')
                                                    En attente
                                                    @break
                                                @case('en_cours')
                                                @case('in_progress')
                                                    En cours
                                                    @break
                                                @case('approved')
                                                    Approuvée
                                                    @break
                                                @case('processed')
                                                @case('ready')
                                                    Document prêt
                                                    @break
                                                @case('completed')
                                                    Terminée
                                                    @break
                                                @case('rejected')
                                                    Rejetée
                                                    @break
                                                @default
                                                    {{ ucfirst($request->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>                                        <div class="btn-group">
                                            <a href="{{ route('citizen.request.standalone.show', $request) }}" class="btn btn-primary">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>

                                            @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
                                                <a href="{{ route('documents.download', $request) }}" class="btn btn-success @if($request->status == 'approved') btn-download-approved @endif">
                                                    <i class="fas fa-download"></i>
                                                    Télécharger
                                                </a>
                                            @endif

                                            @if($request->status === 'en_attente')
                                                <form action="{{ route('requests.destroy', $request) }}" method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times"></i>
                                                        Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Vue Mobile : Cartes -->
                    <div class="card-view requests-content">
                        @foreach($requests as $request)
                        <div class="request-card">
                            <div class="request-meta">
                                <span class="request-ref">#{{ $request->reference_number }}</span>
                                <span class="request-date">{{ $request->created_at->format('d/m/Y à H:i') }}</span>                                <span class="status-badge status-{{ $request->status }}">
                                    @switch($request->status)
                                        @case('en_attente')
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('en_cours')
                                        @case('in_progress')
                                            En cours
                                            @break
                                        @case('approved')
                                            Approuvée
                                            @break
                                        @case('processed')
                                        @case('ready')                                            Document prêt
                                            @break
                                        @case('completed')
                                            Terminée
                                            @break
                                        @case('rejected')
                                            Rejetée
                                            @break
                                        @default
                                            {{ ucfirst($request->status) }}
                                    @endswitch
                                </span>
                            </div>

                            <div class="request-details">
                                <div class="request-title">
                                    {{ $request->document->name ?? 'Document non spécifié' }} ({{ ucfirst($request->type) }})
                                </div>
                                <div class="request-description">
                                    {{ Str::limit($request->description, 100) }}
                                </div>
                                <div class="request-form-data">
                                    <h4>Informations saisies :</h4>
                                    <ul>
                                        <li><strong>Nom et Prénoms :</strong> {{ $request->form_data['name'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Sexe :</strong> {{ $request->form_data['gender'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Date de naissance :</strong> {{ $request->form_data['date_of_birth'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Lieu de naissance :</strong> {{ $request->form_data['place_of_birth'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Nationalité :</strong> {{ $request->form_data['nationality'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Nom du père :</strong> {{ $request->form_data['father_name'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Nom de la mère :</strong> {{ $request->form_data['mother_name'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Numéro de registre :</strong> {{ $request->form_data['registry_number'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Date d'enregistrement :</strong> {{ $request->form_data['registration_date'] ?? 'Non spécifié' }}</li>
                                        <li><strong>Déclarant :</strong> {{ $request->form_data['declarant_name'] ?? 'Non spécifié' }}</li>
                                    </ul>
                                </div>
                            </div>
                              <div class="request-actions">
                                <a href="{{ route('citizen.request.standalone.show', $request) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Voir détails
                                </a>
                                  @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
                                    <a href="{{ route('documents.download', $request) }}" class="btn btn-success @if($request->status == 'approved') btn-download-approved @endif">
                                        <i class="fas fa-download"></i>
                                        Télécharger
                                    </a>
                                @endif

                                @if($request->status === 'en_attente')
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times"></i>
                                            Annuler
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- État vide -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3>Aucune demande trouvée</h3>
                        <p>Vous n'avez pas encore soumis de demande de document.</p>
                        <a href="{{ route('interactive-forms.index') }}" class="create-button">
                            <i class="fas fa-plus-circle"></i>
                            Créer votre première demande
                        </a>
                    </div>
                @endif
            </div>
        </div>    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Compter les documents réellement téléchargeables (même condition que pour afficher le bouton)
        @php
            $downloadableCount = $requests->filter(function($request) {
                return in_array($request->status, ['approved', 'processed', 'ready', 'completed']) 
                    || ($request->status == 'in_progress' && $request->processed_by);
            })->count();
        @endphp
        const downloadableDocuments = {{ $downloadableCount }};

        if (downloadableDocuments > 0) {
            // Créer une notification toast
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #16a34a;
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                z-index: 1000;
                max-width: 350px;
                font-family: 'Inter', sans-serif;
                animation: slideInRight 0.5s ease-out;
            `;

            toast.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-check-circle" style="margin-right: 12px; font-size: 20px;"></i>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 4px;">Documents prêts !</div>
                        <div style="font-size: 14px; opacity: 0.9;">
                            Vous avez ${downloadableDocuments} document${downloadableDocuments > 1 ? 's' : ''} prêt${downloadableDocuments > 1 ? 's' : ''} au téléchargement
                        </div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                            style="margin-left: 16px; background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            // Auto-masquer après 8 secondes
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.transition = 'all 0.5s ease-out';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 8000);
        }
    });
    </script>

    <style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    </style>
</body>
</html>

