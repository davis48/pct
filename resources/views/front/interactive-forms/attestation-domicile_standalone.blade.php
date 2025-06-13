<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation de Domicile | PCT UVCI</title>
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
            overflow-x: hidden;        }
        
        .hidden {
            display: none !important;
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
            color: #f59e0b;
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
            background: linear-gradient(135deg, #f59e0b, #d97706);
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
            color: #f59e0b;
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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
            background: #f59e0b;
            color: white;
        }
        
        .btn-primary:hover {
            background: #d97706;
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
            background: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-title {
            font-weight: 600;
            color: #d97706;
            margin-bottom: 0.5rem;
        }
        
        .info-text {
            color: #92400e;
            font-size: 0.875rem;
        }
        
        /* Styles pour l'upload de documents */
        .document-upload-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .upload-area {
            border: 2px dashed #fbbf24;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #fffbeb;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #f59e0b;
            background: #fef3c7;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #fbbf24;
            margin-bottom: 1rem;
        }
        
        .upload-text {
            color: #d97706;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .upload-hint {
            color: #fbbf24;
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
            background: #fffbeb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #fed7aa;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .file-icon {
            color: #f59e0b;
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
            color: #d97706;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .guidelines {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .guidelines-title {
            font-weight: 600;
            color: #d97706;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .guidelines-list {
            color: #d97706;
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
                        <i class="fas fa-home title-icon"></i>
                        Attestation de Domicile
                    </h1>
                    <p class="page-subtitle">Formulaire interactif - Temps estimé: 3-5 minutes</p>
                </div>
            </div>

            <!-- Information importante -->
            <div class="info-box">
                <div class="info-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informations importantes
                </div>
                <div class="info-text">
                    Cette attestation certifie votre domicile actuel. 
                    Assurez-vous que l'adresse indiquée correspond bien à votre lieu de résidence.
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="form-container">
                <div class="form-header">
                    <i class="fas fa-home form-icon"></i>
                    <h2 class="form-title">Attestation de Domicile</h2>
                    <p class="form-description">Remplissez vos informations de résidence ci-dessous</p>
                </div>

                <form action="{{ route('interactive-forms.generate', 'attestation-domicile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <!-- Informations personnelles -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user section-icon"></i>
                                Informations personnelles
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
                                    <label class="input-label" for="date_naissance">
                                        Date de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_naissance" id="date_naissance" 
                                           class="input-field" value="{{ old('date_naissance', $userData['date_of_birth'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_naissance">
                                        Lieu de naissance <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="lieu_naissance" id="lieu_naissance" 
                                           class="input-field" value="{{ old('lieu_naissance', $userData['place_of_birth'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="nationalite">
                                        Nationalité <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="nationalite" id="nationalite" 
                                           class="input-field" value="{{ old('nationalite', $userData['nationality'] ?? 'Ivoirienne') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession">
                                        Profession <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="profession" id="profession" 
                                           class="input-field" value="{{ old('profession', $userData['profession'] ?? '') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="cin_number">
                                        Numéro de CNI
                                    </label>
                                    <input type="text" name="cin_number" id="cin_number" 
                                           class="input-field" value="{{ old('cin_number') }}"
                                           placeholder="Ex: CI0123456789">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="telephone">
                                        Téléphone
                                    </label>
                                    <input type="tel" name="telephone" id="telephone" 
                                           class="input-field" value="{{ old('telephone', $userData['phone'] ?? '') }}"
                                           placeholder="Ex: +225 07 00 00 00 00">
                                </div>
                            </div>
                        </div>

                        <!-- Informations de domicile -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt section-icon"></i>
                                Informations de domicile
                            </h3>
                            <div class="form-grid">
                                <div class="input-group form-grid-full">
                                    <label class="input-label" for="adresse_complete">
                                        Adresse complète <span class="input-required">*</span>
                                    </label>
                                    <textarea name="adresse_complete" id="adresse_complete" 
                                              class="input-field" rows="3" required>{{ old('adresse_complete', $userData['address'] ?? '') }}</textarea>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="commune">
                                        Commune <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="commune" id="commune" 
                                           class="input-field" value="{{ old('commune') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="quartier">
                                        Quartier <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="quartier" id="quartier" 
                                           class="input-field" value="{{ old('quartier') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="date_installation">
                                        Date d'installation <span class="input-required">*</span>
                                    </label>
                                    <input type="date" name="date_installation" id="date_installation" 
                                           class="input-field" value="{{ old('date_installation') }}" required>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="statut_logement">
                                        Statut du logement
                                    </label>
                                    <select name="statut_logement" id="statut_logement" class="input-field">
                                        <option value="">Sélectionner</option>
                                        <option value="Propriétaire" {{ old('statut_logement') == 'Propriétaire' ? 'selected' : '' }}>Propriétaire</option>
                                        <option value="Locataire" {{ old('statut_logement') == 'Locataire' ? 'selected' : '' }}>Locataire</option>
                                        <option value="Hébergé" {{ old('statut_logement') == 'Hébergé' ? 'selected' : '' }}>Hébergé</option>
                                        <option value="Autre" {{ old('statut_logement') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Témoin/Garant -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-users section-icon"></i>
                                Témoin/Garant (optionnel)
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="nom_temoin">
                                        Nom du témoin
                                    </label>
                                    <input type="text" name="nom_temoin" id="nom_temoin" 
                                           class="input-field" value="{{ old('nom_temoin') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="prenoms_temoin">
                                        Prénoms du témoin
                                    </label>
                                    <input type="text" name="prenoms_temoin" id="prenoms_temoin" 
                                           class="input-field" value="{{ old('prenoms_temoin') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="profession_temoin">
                                        Profession du témoin
                                    </label>
                                    <input type="text" name="profession_temoin" id="profession_temoin" 
                                           class="input-field" value="{{ old('profession_temoin') }}">
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="telephone_temoin">
                                        Téléphone du témoin
                                    </label>
                                    <input type="tel" name="telephone_temoin" id="telephone_temoin" 
                                           class="input-field" value="{{ old('telephone_temoin') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Informations complémentaires -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-clipboard-list section-icon"></i>
                                Informations complémentaires
                            </h3>
                            <div class="form-grid">
                                <div class="input-group">
                                    <label class="input-label" for="motif">
                                        Motif de la demande
                                    </label>
                                    <select name="motif" id="motif" class="input-field">
                                        <option value="">Sélectionner un motif</option>
                                        <option value="Inscription scolaire">Inscription scolaire</option>
                                        <option value="Ouverture de compte bancaire">Ouverture de compte bancaire</option>
                                        <option value="Demande d'emploi">Demande d'emploi</option>
                                        <option value="Demande de raccordement">Demande de raccordement (eau, électricité...)</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <label class="input-label" for="lieu_delivrance">
                                        Lieu de délivrance
                                    </label>
                                    <input type="text" name="lieu_delivrance" id="lieu_delivrance" 
                                           class="input-field" value="{{ old('lieu_delivrance', 'ABIDJAN') }}">
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
                                Documents requis pour une attestation de domicile
                            </div>
                            <div class="guidelines-list">
                                <ul>
                                    <li>Copie de la pièce d'identité du demandeur</li>
                                    <li>Justificatif de domicile récent (facture d'électricité, d'eau, etc.)</li>
                                    <li>Contrat de bail ou titre de propriété</li>
                                    <li>Procuration si vous agissez au nom d'un tiers</li>
                                    <li>Témoin attestant de votre résidence (facultatif)</li>
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
                            Générer l'attestation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Script pour gérer l'upload de documents
        document.addEventListener('DOMContentLoaded', function() {
            var uploadArea = document.getElementById('uploadArea');
            var fileList = document.getElementById('fileList');

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
                handleFiles(e.dataTransfer.files);
            });

            uploadArea.addEventListener('click', function() {
                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.multiple = true;
                fileInput.accept = '.pdf, .jpg, .png';
                fileInput.addEventListener('change', function() {
                    handleFiles(fileInput.files);
                });
                fileInput.click();
            });

            function handleFiles(files) {
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <div class="file-info">
                            <i class="fas fa-file file-icon"></i>
                            <div class="file-details">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${(file.size / 1024).toFixed(2)} Ko</div>
                            </div>
                        </div>
                        <div class="remove-file" data-index="${i}">
                            <i class="fas fa-times"></i>
                        </div>
                    `;
                    fileList.appendChild(fileItem);
                }
            }            fileList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    var index = e.target.getAttribute('data-index');
                    fileList.removeChild(fileList.children[index]);
                }
            });
            
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
                
                if (selectedFiles.length === 0) {
                    showSuccess('Tous les documents ont été supprimés.');
                } else {
                    showSuccess(`Document supprimé. ${selectedFiles.length} document(s) restant(s).`);
                }
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
        });
    </script>
</body>
</html>



