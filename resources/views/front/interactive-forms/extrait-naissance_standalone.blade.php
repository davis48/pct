<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrait de Naissance | PCT UVCI</title>
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
            color: #7c3aed;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
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
            color: #7c3aed;
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
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
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
            background: #7c3aed;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5b21b6;
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
        
        .info-box {
            background: #faf5ff;
            border: 1px solid #7c3aed;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-title {
            font-weight: 600;
            color: #6b21a8;
            margin-bottom: 0.5rem;
        }
        
        .info-text {
            color: #7c2d92;
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
                        <i class="fas fa-baby title-icon"></i>
                        Extrait de Naissance
                    </h1>
                    <p class="page-subtitle">Formulaire interactif - Temps estimé: 5-8 minutes</p>
                </div>
            </div>

            <!-- Information importante -->
            <div class="info-box">
                <div class="info-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informations importantes
                </div>
                <div class="info-text">
                    Ce formulaire permet de générer automatiquement votre extrait de naissance. 
                    Vérifiez l'exactitude de toutes les informations avant la validation.
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="form-container">
                <div class="form-header">
                    <i class="fas fa-baby form-icon"></i>
                    <h2 class="form-title">Extrait de Naissance</h2>
                    <p class="form-description">Remplissez les informations de naissance ci-dessous</p>
                </div>

                <form action="{{ route('interactive-forms.generate', 'extrait-naissance') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <!-- Informations de la personne née -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user section-icon"></i>
                                Informations de la personne
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom">
                                        Nom <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nom" id="nom" 
                                           class="input-field" value="{{ old('nom', $userData['name'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms">
                                        Prénoms <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="prenoms" id="prenoms" 
                                           class="input-field" value="{{ old('prenoms') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="sexe">
                                        Sexe <span class="input-required">*</span>
                                    </label>
                                    <select name="sexe" id="sexe" class="input-field" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Féminin" {{ old('sexe') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_naissance">
                                        Date de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_naissance" id="date_naissance" 
                                           class="input-field" value="{{ old('date_naissance', $userData['date_of_birth'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="heure_naissance">
                                        Heure de naissance
                                    </label>
                                    <input type="time" name="heure_naissance" id="heure_naissance" 
                                           class="input-field" value="{{ old('heure_naissance') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_naissance">
                                        Lieu de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="lieu_naissance" id="lieu_naissance" 
                                           class="input-field" value="{{ old('lieu_naissance', $userData['place_of_birth'] ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du père -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-male section-icon"></i>
                                Informations du père
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom_pere">
                                        Nom du père <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nom_pere" id="nom_pere" 
                                           class="input-field" value="{{ old('nom_pere', $userData['father_name'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms_pere">
                                        Prénoms du père
                                    </label>
                                    <input type="text" name="prenoms_pere" id="prenoms_pere" 
                                           class="input-field" value="{{ old('prenoms_pere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="age_pere">
                                        Âge du père à la naissance
                                    </label>
                                    <input type="number" name="age_pere" id="age_pere" min="15" max="100"
                                           class="input-field" value="{{ old('age_pere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession_pere">
                                        Profession du père
                                    </label>
                                    <input type="text" name="profession_pere" id="profession_pere" 
                                           class="input-field" value="{{ old('profession_pere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="domicile_pere">
                                        Domicile du père
                                    </label>
                                    <input type="text" name="domicile_pere" id="domicile_pere" 
                                           class="input-field" value="{{ old('domicile_pere') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Informations de la mère -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-female section-icon"></i>
                                Informations de la mère
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom_mere">
                                        Nom de la mère <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nom_mere" id="nom_mere" 
                                           class="input-field" value="{{ old('nom_mere', $userData['mother_name'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms_mere">
                                        Prénoms de la mère
                                    </label>
                                    <input type="text" name="prenoms_mere" id="prenoms_mere" 
                                           class="input-field" value="{{ old('prenoms_mere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="age_mere">
                                        Âge de la mère à la naissance
                                    </label>
                                    <input type="number" name="age_mere" id="age_mere" min="15" max="100"
                                           class="input-field" value="{{ old('age_mere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession_mere">
                                        Profession de la mère
                                    </label>
                                    <input type="text" name="profession_mere" id="profession_mere" 
                                           class="input-field" value="{{ old('profession_mere') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="domicile_mere">
                                        Domicile de la mère
                                    </label>
                                    <input type="text" name="domicile_mere" id="domicile_mere" 
                                           class="input-field" value="{{ old('domicile_mere') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Informations administratives -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-stamp section-icon"></i>
                                Informations administratives
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="centre_etat_civil">
                                        Centre d'état civil <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="centre_etat_civil" id="centre_etat_civil" 
                                           class="input-field" value="{{ old('centre_etat_civil', 'ABIDJAN') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="numero_acte">
                                        Numéro d'acte
                                    </label>
                                    <input type="text" name="numero_acte" id="numero_acte" 
                                           class="input-field" value="{{ old('numero_acte') }}"
                                           placeholder="Ex: 2024/001">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_declaration">
                                        Date de déclaration
                                    </label>
                                    <input type="date" name="date_declaration" id="date_declaration" 
                                           class="input-field" value="{{ old('date_declaration') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="declarant">
                                        Déclarant
                                    </label>
                                    <input type="text" name="declarant" id="declarant" 
                                           class="input-field" value="{{ old('declarant') }}"
                                           placeholder="Nom de la personne qui a déclaré la naissance">
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
                            Générer l'extrait
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
