<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Interactifs | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            color: #1f2937;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
            color: #1f2937;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .navbar-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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
            color: #6b7280;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #3b82f6;
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
            text-align: center;
            margin-bottom: 3rem;
            background: white;
            padding: 3rem 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .page-icon {
            color: #3b82f6;
            font-size: 2rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .benefits-section {
            margin-bottom: 3rem;
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        @media (min-width: 768px) {
            .benefits-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .benefit-card {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .benefit-card:hover {
            transform: translateY(-5px);
        }
        
        .benefit-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .benefit-icon.green {
            background: #f0fdf4;
            color: #16a34a;
        }
        
        .benefit-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }
        
        .benefit-icon.purple {
            background: #faf5ff;
            color: #7c3aed;
        }
        
        .benefit-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .benefit-description {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .forms-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        @media (min-width: 768px) {
            .forms-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .forms-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .form-card-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .form-card-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .form-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-card-subtitle {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        .form-card-body {
            padding: 1.5rem;
        }
        
        .form-card-description {
            color: #6b7280;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        
        .form-card-features {
            list-style: none;
            margin-bottom: 1.5rem;
        }
        
        .form-card-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
        }
        
        .feature-icon {
            color: #16a34a;
            font-size: 0.75rem;
        }
        
        .form-card-footer {
            display: flex;
            gap: 0.75rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-family: inherit;
            text-align: center;
            justify-content: center;
            flex: 1;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            color: white;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .section-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                PCT UVCI
            </a>
            
            <div class="navbar-nav">
                @auth
                    <a href="{{ route('citizen.dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Mon Espace
                    </a>
                    <a href="{{ route('requests.index') }}" class="nav-link">
                        <i class="fas fa-file-alt mr-2"></i>
                        Mes Demandes
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('choose.role') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="container">
            <!-- En-tête -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit page-icon"></i>
                    Formulaires Interactifs
                </h1>
                <p class="page-subtitle">
                    Remplissez et générez vos documents officiels directement en ligne. 
                    Rapide, sécurisé et disponible 24h/24.
                </p>
            </div>

            <!-- Section avantages -->
            <div class="benefits-section">
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon green">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="benefit-title">Gain de Temps</h3>
                        <p class="benefit-description">Remplissez vos formulaires en quelques minutes seulement</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon blue">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="benefit-title">Sécurisé</h3>
                        <p class="benefit-description">Vos données sont protégées et chiffrées</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon purple">
                            <i class="fas fa-download"></i>
                        </div>
                        <h3 class="benefit-title">Téléchargement Instantané</h3>
                        <p class="benefit-description">Récupérez vos documents immédiatement</p>
                    </div>
                </div>
            </div>

            <!-- Section formulaires -->
            <div class="forms-section">
                <h2 class="section-title">Formulaires Disponibles</h2>
                
                <div class="forms-grid">
                    @foreach($availableForms as $formType => $form)
                    <div class="form-card">
                        <div class="form-card-header">
                            <i class="fas {{ $form['icon'] }} form-card-icon"></i>
                            <h3 class="form-card-title">{{ $form['title'] }}</h3>
                            <p class="form-card-subtitle">{{ $form['subtitle'] ?? 'Document officiel' }}</p>
                        </div>
                        <div class="form-card-body">
                            <p class="form-card-description">{{ $form['description'] }}</p>
                            
                            <ul class="form-card-features">
                                <li class="form-card-feature">
                                    <i class="fas fa-check feature-icon"></i>
                                    <span>Génération automatique</span>
                                </li>
                                <li class="form-card-feature">
                                    <i class="fas fa-check feature-icon"></i>
                                    <span>Format PDF officiel</span>
                                </li>
                                <li class="form-card-feature">
                                    <i class="fas fa-check feature-icon"></i>
                                    <span>Téléchargement immédiat</span>
                                </li>
                                @if(isset($form['duration']))
                                <li class="form-card-feature">
                                    <i class="fas fa-clock feature-icon"></i>
                                    <span>{{ $form['duration'] }}</span>
                                </li>
                                @endif
                            </ul>
                            
                            <div class="form-card-footer">
                                <a href="{{ route('interactive-forms.show', $formType) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Remplir le formulaire
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</body>
</html>
