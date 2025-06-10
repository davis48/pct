<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Mariage | PCT UVCI</title>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .back-link {
            color: #3b82f6;
            font-size: 1.25rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #1d4ed8;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .title-icon {
            color: #ef4444;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .progress-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .progress-step {
            color: #3b82f6;
            font-weight: 600;
        }
        
        .progress-label {
            color: #6b7280;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #f3f4f6;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            width: 33.33%;
            transition: width 0.3s ease;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .form-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .form-description {
            opacity: 0.9;
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-icon {
            color: #3b82f6;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-grid-full {
                grid-column: 1 / -1;
            }
        }
        
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .input-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .input-required {
            color: #ef4444;
        }
        
        .input-field {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            color: #1f2937;
            font-family: inherit;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-actions {
            padding: 2rem;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.875rem 2rem;
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
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            color: #374151;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-info {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1d4ed8;
        }
        
        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            color: #d97706;
        }
        
        .info-box {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-title {
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 0.5rem;
        }
        
        .info-text {
            color: #075985;
            font-size: 0.875rem;
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
            <!-- En-tête de page -->
            <div class="page-header">
                <a href="{{ route('interactive-forms.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-heart title-icon"></i>
                        Certificat de Mariage
                    </h1>
                    <p class="page-subtitle">Formulaire interactif - Temps estimé: 5-10 minutes</p>
                </div>
            </div>

            <!-- Indicateur de progression -->
            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-step">Étape 1 sur 3</span>
                    <span class="progress-label">Informations personnelles</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <!-- Information importante -->
            <div class="info-box">
                <div class="info-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informations importantes
                </div>
                <div class="info-text">
                    Ce formulaire vous permet de générer automatiquement votre certificat de mariage. 
                    Vérifiez bien toutes les informations avant de valider.
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="form-container">
                <div class="form-header">
                    <i class="fas fa-heart form-icon"></i>
                    <h2 class="form-title">Certificat de Mariage</h2>
                    <p class="form-description">Remplissez les informations ci-dessous pour générer votre certificat</p>
                </div>

                <form action="{{ route('interactive-forms.generate', 'certificat-mariage') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <!-- Informations de l'époux -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-male section-icon"></i>
                                Informations de l'époux
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom_epoux">
                                        Nom <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nom_epoux" id="nom_epoux" 
                                           class="input-field" value="{{ old('nom_epoux') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms_epoux">
                                        Prénoms <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="prenoms_epoux" id="prenoms_epoux" 
                                           class="input-field" value="{{ old('prenoms_epoux') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_naissance_epoux">
                                        Date de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_naissance_epoux" id="date_naissance_epoux" 
                                           class="input-field" value="{{ old('date_naissance_epoux') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_naissance_epoux">
                                        Lieu de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="lieu_naissance_epoux" id="lieu_naissance_epoux" 
                                           class="input-field" value="{{ old('lieu_naissance_epoux') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession_epoux">
                                        Profession <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="profession_epoux" id="profession_epoux" 
                                           class="input-field" value="{{ old('profession_epoux') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="domicile_epoux">
                                        Domicile <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="domicile_epoux" id="domicile_epoux" 
                                           class="input-field" value="{{ old('domicile_epoux') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de l'épouse -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-female section-icon"></i>
                                Informations de l'épouse
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom_epouse">
                                        Nom <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nom_epouse" id="nom_epouse" 
                                           class="input-field" value="{{ old('nom_epouse') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms_epouse">
                                        Prénoms <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="prenoms_epouse" id="prenoms_epouse" 
                                           class="input-field" value="{{ old('prenoms_epouse') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_naissance_epouse">
                                        Date de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_naissance_epouse" id="date_naissance_epouse" 
                                           class="input-field" value="{{ old('date_naissance_epouse') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_naissance_epouse">
                                        Lieu de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="lieu_naissance_epouse" id="lieu_naissance_epouse" 
                                           class="input-field" value="{{ old('lieu_naissance_epouse') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession_epouse">
                                        Profession <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="profession_epouse" id="profession_epouse" 
                                           class="input-field" value="{{ old('profession_epouse') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="domicile_epouse">
                                        Domicile <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="domicile_epouse" id="domicile_epouse" 
                                           class="input-field" value="{{ old('domicile_epouse') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du mariage -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-calendar-heart section-icon"></i>
                                Informations du mariage
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="date_mariage">
                                        Date du mariage <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_mariage" id="date_mariage" 
                                           class="input-field" value="{{ old('date_mariage') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_mariage">
                                        Lieu du mariage <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="lieu_mariage" id="lieu_mariage" 
                                           class="input-field" value="{{ old('lieu_mariage') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="regime_matrimonial">
                                        Régime matrimonial
                                    </label>
                                    <select name="regime_matrimonial" id="regime_matrimonial" class="input-field">
                                        <option value="">Sélectionner</option>
                                        <option value="Communauté de biens">Communauté de biens</option>
                                        <option value="Séparation de biens">Séparation de biens</option>
                                        <option value="Participation aux acquêts">Participation aux acquêts</option>
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="officiant">
                                        Officiant <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="officiant" id="officiant" 
                                           class="input-field" value="{{ old('officiant') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions du formulaire -->
                    <div class="form-actions">
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i>
                            Générer le certificat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
