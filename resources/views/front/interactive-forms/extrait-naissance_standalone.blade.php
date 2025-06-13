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
    <link rel="stylesheet" href="{{ asset('css/standalone-hover-effects.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-blue-standalone.css') }}">
    
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
            border: 2px dashed #c4b5fd;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #faf5ff;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #7c3aed;
            background: #f3e8ff;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #a855f7;
            margin-bottom: 1rem;
        }
        
        .upload-text {
            color: #6b21a8;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .upload-hint {
            color: #a855f7;
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
            background: #faf5ff;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .file-icon {
            color: #7c3aed;
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
            color: #6b21a8;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .guidelines {
            background: #f3e8ff;
            border: 1px solid #c4b5fd;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .guidelines-title {
            font-weight: 600;
            color: #6b21a8;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .guidelines-list {
            color: #6b21a8;
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
                    <i class="fas fa-file-contract"></i>
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

            <!-- Messages d'erreur et de succès -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h4><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            <style>
                .alert {
                    padding: 1rem;
                    margin-bottom: 1rem;
                    border-radius: 8px;
                    border: 1px solid;
                }
                .alert-danger {
                    background-color: #fee2e2;
                    border-color: #fecaca;
                    color: #dc2626;
                }
                .alert-success {
                    background-color: #d1fae5;
                    border-color: #a7f3d0;
                    color: #065f46;
                }
                .alert-info {
                    background-color: #dbeafe;
                    border-color: #93c5fd;
                    color: #0d47a1;
                }
            </style>

            <!-- Formulaire principal -->
            <div class="form-container">
                <div class="form-header">
                    <i class="fas fa-baby form-icon"></i>
                    <h2 class="form-title">Extrait de Naissance</h2>
                    <p class="form-description">Remplissez les informations de naissance ci-dessous</p>
                </div>                <form action="{{ route('interactive-forms.standalone.generate', 'extrait-naissance') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type_document" value="extrait-naissance">
                    <div class="form-body">
                        <!-- Informations de la personne née -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user section-icon"></i>
                                Informations de la personne
                            </h3>
                            <div class="form-grid">                                <div class="input-group">
                                    <label class="input-label" for="name">
                                        Nom <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="input-field" value="{{ old('name', $userData['name'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="first_names">
                                        Prénoms <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="first_names" id="first_names" 
                                           class="input-field" value="{{ old('first_names') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="gender">
                                        Sexe <span class="input-required">*</span>
                                    </label>
                                    <select name="gender" id="gender" class="input-field" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Masculin" {{ old('gender') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Féminin" {{ old('gender') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_of_birth">
                                        Date de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" 
                                           class="input-field" value="{{ old('date_of_birth', $userData['date_of_birth'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="heure_naissance">
                                        Heure de naissance
                                    </label>                                    <input type="time" name="birth_time" id="birth_time" 
                                           class="input-field" value="{{ old('birth_time') }}">
                                </div>
                                  <div class="input-group">
                                    <label class="input-label" for="place_of_birth">
                                        Lieu de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="place_of_birth" id="place_of_birth" 
                                           class="input-field" value="{{ old('place_of_birth', $userData['place_of_birth'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="nationality">
                                        Nationalité <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nationality" id="nationality" 
                                           class="input-field" value="{{ old('nationality', $userData['nationality'] ?? 'Ivoirienne') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du père -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-male section-icon"></i>
                                Informations du père
                            </h3>
                            <div class="form-grid">                                <div class="input-group">
                                    <label class="input-label" for="father_name">
                                        Nom du père <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="father_name" id="father_name" 
                                           class="input-field" value="{{ old('father_name', $userData['father_name'] ?? '') }}" required>
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
                            <div class="form-grid">                                <div class="input-group">
                                    <label class="input-label" for="mother_name">
                                        Nom de la mère <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="mother_name" id="mother_name" 
                                           class="input-field" value="{{ old('mother_name', $userData['mother_name'] ?? '') }}" required>
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
                                    <label class="input-label" for="registry_number">
                                        Numéro de registre <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="registry_number" id="registry_number" 
                                           class="input-field" value="{{ old('registry_number') }}"
                                           placeholder="Ex: 2025/001/123" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="registration_date">
                                        Date d'enregistrement <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="registration_date" id="registration_date" 
                                           class="input-field" value="{{ old('registration_date') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="declarant_name">
                                        Déclarant
                                    </label>
                                    <input type="text" name="declarant_name" id="declarant_name" 
                                           class="input-field" value="{{ old('declarant_name') }}"
                                           placeholder="Nom de la personne qui a déclaré la naissance">
                                </div>
                            </div>                        </div>

                    <!-- Documents Justificatifs -->
                    <div class="document-upload-section">
                        <h3 class="section-title">
                            <i class="fas fa-paperclip"></i>
                            Documents Justificatifs
                        </h3>
                        
                        <div class="guidelines">
                            <div class="guidelines-title">
                                <i class="fas fa-info-circle"></i>
                                Documents requis pour un extrait de naissance
                            </div>
                            <div class="guidelines-list">
                                <ul>
                                    <li>Copie de la pièce d'identité du demandeur</li>
                                    <li>Justificatif de domicile récent (facture, quittance de loyer, etc.)</li>
                                    <li>Acte de naissance original ou copie certifiée conforme (si disponible)</li>
                                    <li>Procuration si vous agissez au nom d'un tiers</li>
                                    <li>Justificatif de lien de parenté (livret de famille, etc.)</li>
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
                               accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="handleFileSelect(event)">
                        
                        <div class="file-list" id="fileList"></div>
                        <div class="file-counter" id="fileCounter">0/8 documents sélectionnés</div>
                        
                        <div class="error-message" id="errorMessage"></div>
                        <div class="success-message" id="successMessage"></div>
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
    </main>    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                
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
                errorMessage.style.display = 'block';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            
            function showSuccess(message) {
                const successMessage = document.getElementById('successMessage');
                successMessage.textContent = message;
                successMessage.style.display = 'block';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 3000);
            }
              // Drag and drop functionality
            const uploadArea = document.querySelector('.upload-area');
            
            if (uploadArea) {
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
            
            // Form submission handler
            const form = document.querySelector('form[action*="generate"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // S'assurer que l'input de fichiers est à jour
                    updateDocumentFileInput();
                    
                    // Optionnel: validation côté client
                    const requiredFields = form.querySelectorAll('input[required], select[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = '#ef4444';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        showError('Veuillez remplir tous les champs obligatoires.');
                        return false;
                    }
                    
                    // Afficher un message de chargement
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
                    }
                });
            }
        });
    </script>
</body>
</html>



