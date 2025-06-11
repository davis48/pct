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
            background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
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
            color: #6b7280;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #1976d2;
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
            color: #1976d2;
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
            color: #1976d2;
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
            background: linear-gradient(135deg, #1976d2, #1565c0);
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
            background: linear-gradient(135deg, #1976d2, #1565c0);
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
            color: #1976d2;
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
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
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
        
        /* Styles pour l'upload de documents */
        .document-upload-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .upload-area {
            border: 2px dashed #93c5fd;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #eff6ff;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #1976d2;
            background: #dbeafe;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #60a5fa;
            margin-bottom: 1rem;
        }
        
        .upload-text {
            color: #1d4ed8;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .upload-hint {
            color: #60a5fa;
            font-size: 0.875rem;
        }
        
        .file-list {
            margin-top: 1rem;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .file-icon {
            color: #1976d2;
            font-size: 1.25rem;
        }
        
        .file-details {
            flex: 1;
        }
        
        .file-name {
            font-weight: 500;
            color: #1f2937;
            font-size: 0.875rem;
        }
        
        .file-size {
            color: #6b7280;
            font-size: 0.75rem;
        }
        
        .remove-file {
            color: #ef4444;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: background-color 0.2s;
            font-size: 1rem;
        }
        
        .remove-file:hover {
            background: #fee2e2;
        }
        
        .file-counter {
            color: #1d4ed8;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .guidelines {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .guidelines-title {
            font-weight: 600;
            color: #1d4ed8;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .guidelines-list {
            color: #1d4ed8;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        
        .guidelines-list ul {
            margin-left: 1rem;
            list-style-type: disc;
        }
        
        .guidelines-list li {
            margin-bottom: 0.25rem;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
            background: #fef2f2;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #fecaca;
        }
        
        .success-message {
            color: #059669;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
            background: #f0fdf4;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #bbf7d0;
        }
        
        .hidden {
            display: none !important;
        }
        
        @media (max-width: 640px) {
            .file-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .file-info {
                width: 100%;
            }
            
            .document-upload-section {
                padding: 1rem;
            }
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

                <form action="{{ route('interactive-forms.generate', 'certificat-mariage') }}" method="POST" enctype="multipart/form-data">
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
                            </div>                        </div>
                    </div>

                    <!-- Actions du formulaire -->
                    <!-- Documents Justificatifs -->
                    <div class="document-upload-section">
                        <h3 class="section-title">
                            <i class="fas fa-paperclip"></i>
                            Documents Justificatifs
                        </h3>
                        
                        <div class="guidelines">
                            <div class="guidelines-title">
                                <i class="fas fa-info-circle"></i>
                                Documents requis pour un certificat de mariage
                            </div>
                            <div class="guidelines-list">
                                <ul>
                                    <li>Copie de la pièce d'identité des époux</li>
                                    <li>Acte de mariage original ou copie certifiée conforme</li>
                                    <li>Justificatif de domicile du demandeur</li>
                                    <li>Procuration si vous agissez au nom d'un tiers</li>
                                    <li>Livret de famille (si disponible)</li>
                                </ul>
                                <p style="margin-top: 0.5rem; font-style: italic;">
                                    Formats acceptés: PDF, JPG, PNG. Taille max: 5MB par fichier.
                                </p>
                            </div>
                        </div>
                        
                        <div class="upload-area" onclick="document.getElementById('documents').click()">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Cliquez ici pour sélectionner vos documents</div>
                            <div class="upload-hint">ou glissez-déposez vos fichiers ici</div>
                        </div>
                          <input type="file" id="documents" name="documents[]" multiple 
                               accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleFileSelect(event)">
                        
                        <div class="file-list" id="fileList"></div>
                        <div class="file-counter" id="fileCounter">0/8 documents sélectionnés</div>
                        
                        <div class="error-message hidden" id="errorMessage"></div>
                        <div class="success-message hidden" id="successMessage"></div>
                    </div>

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

    <script>
        // Script pour gérer l'upload de documents
        document.addEventListener('DOMContentLoaded', function() {
            // Debug pour l'upload
            console.log('🔍 Debug certificat de mariage - Upload');
            console.log('Upload area:', document.querySelector('.upload-area'));
            console.log('File input:', document.getElementById('documents'));
            console.log('handleFileSelect:', window.handleFileSelect);
            
            // Test manuel du clic
            const uploadArea = document.querySelector('.upload-area');
            if (uploadArea) {
                uploadArea.addEventListener('click', function() {
                    console.log('🖱️ Upload area cliquée');
                    const fileInput = document.getElementById('documents');
                    if (fileInput) {
                        console.log('📁 Ouverture du sélecteur de fichiers');
                        fileInput.click();
                    } else {
                        console.log('❌ Aucun input file trouvé');
                    }
                });
            }
            
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function() {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');

                var files = e.dataTransfer.files;
                handleFiles(files);
            });

            function handleFiles(files) {
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var listItem = document.createElement('div');
                    listItem.className = 'file-item';

                    var fileInfo = document.createElement('div');
                    fileInfo.className = 'file-info';
                    fileInfo.innerHTML = '<i class="fas fa-file file-icon"></i>' + file.name;

                    var fileDetails = document.createElement('div');
                    fileDetails.className = 'file-details';
                    fileDetails.innerHTML = '<div class="file-name">' + file.name + '</div>' +
                                            '<div class="file-size">' + (file.size / 1024).toFixed(2) + ' Ko</div>';

                    var removeFile = document.createElement('div');
                    removeFile.className = 'remove-file';
                    removeFile.innerHTML = '<i class="fas fa-times"></i>';
                    removeFile.onclick = (function(file) {
                        return function() {
                            var index = Array.prototype.indexOf.call(fileList.children, listItem);
                            if (index !== -1) {
                                fileList.removeChild(listItem);
                            }
                        };
                    })(file);                    listItem.appendChild(fileInfo);
                    listItem.appendChild(fileDetails);
                    listItem.appendChild(removeFile);
                    fileList.appendChild(listItem);
                }
            }
            
            // Gestion de l'upload de documents
            let selectedFiles = [];
            const maxFiles = 8;
            const maxFileSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            
            window.handleFileSelect = function(event) {
                const files = Array.from(event.target.files);
                processFiles(files);
            };
            
            function processFiles(files) {
                const errorMessage = document.getElementById('errorMessage');
                const successMessage = document.getElementById('successMessage');
                
                errorMessage.classList.add('hidden');
                successMessage.classList.add('hidden');
                
                for (let file of files) {
                    if (selectedFiles.length >= maxFiles) {
                        showError(`Vous ne pouvez sélectionner que ${maxFiles} documents maximum.`);
                        break;
                    }
                    
                    if (!allowedTypes.includes(file.type)) {
                        showError(`Format non autorisé pour ${file.name}. Utilisez PDF, JPG ou PNG.`);
                        continue;
                    }
                    
                    if (file.size > maxFileSize) {
                        showError(`${file.name} est trop volumineux. Taille maximum: 5MB.`);
                        continue;
                    }
                    
                    if (selectedFiles.find(f => f.name === file.name)) {
                        showError(`${file.name} est déjà sélectionné.`);
                        continue;
                    }
                    
                    selectedFiles.push(file);
                }
                  updateDocumentFileList();
                updateDocumentFileCounter();
                updateDocumentFileInput();
                
                if (selectedFiles.length > 0) {
                    showSuccess(`${selectedFiles.length} document(s) sélectionné(s) avec succès.`);
                }
            }
            
            function updateDocumentFileList() {
                const fileList = document.getElementById('fileList');
                fileList.innerHTML = '';
                
                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <div class="file-info">
                            <i class="fas fa-file-${getFileIcon(file.type)} file-icon"></i>
                            <div class="file-details">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                        </div>
                        <i class="fas fa-times remove-file" onclick="removeDocumentFile(${index})"></i>
                    `;
                    fileList.appendChild(fileItem);
                });
            }
            
            function updateDocumentFileCounter() {
                const counter = document.getElementById('fileCounter');
                counter.textContent = `${selectedFiles.length}/${maxFiles} documents sélectionnés`;
            }
            
            window.removeDocumentFile = function(index) {
                selectedFiles.splice(index, 1);
                updateDocumentFileList();
                updateDocumentFileCounter();
                updateDocumentFileInput();
            };
            
            function updateDocumentFileInput() {
                const input = document.getElementById('documents');
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                input.files = dt.files;
            }
            
            function getFileIcon(type) {
                if (type === 'application/pdf') return 'pdf';
                if (type.startsWith('image/')) return 'image';
                return 'alt';
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            function showError(message) {
                const errorMessage = document.getElementById('errorMessage');
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');
                setTimeout(() => {
                    errorMessage.classList.add('hidden');
                }, 5000);
            }
            
            function showSuccess(message) {
                const successMessage = document.getElementById('successMessage');
                successMessage.textContent = message;
                successMessage.classList.remove('hidden');
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 3000);
            }
              // Drag and drop functionality
            const uploadArea = document.querySelector('.upload-area');
            
            console.log('🔧 Vérification upload certificat mariage:', {
                uploadArea: !!uploadArea,
                fileInput: !!document.getElementById('documents'),
                handleFileSelect: !!window.handleFileSelect
            });
            
            if (uploadArea) {
                // Test manuel du clic 
                uploadArea.addEventListener('click', function(e) {
                    console.log('🖱️ Zone upload cliquée');
                    e.preventDefault();
                    e.stopPropagation();
                    const fileInput = document.getElementById('documents');
                    if (fileInput) {
                        console.log('📁 Ouverture sélecteur fichiers');
                        fileInput.click();
                    }
                });
                
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('dragover');
                });
                
                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');
                });
                
                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('dragover');
                    
                    const files = Array.from(e.dataTransfer.files);
                    processFiles(files);
                });
            }
        });
    </script>
</body>
</html>
