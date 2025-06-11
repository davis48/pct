<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Célibat | PCT UVCI</title>
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
            color: #10b981;
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
            background: linear-gradient(135deg, #10b981, #059669);
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
            color: #10b981;
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
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
            background: #10b981;
            color: white;
        }
        
        .btn-primary:hover {
            background: #059669;
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
            background: #f0fdf4;
            border: 1px solid #16a34a;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-title {
            font-weight: 600;
            color: #15803d;
            margin-bottom: 0.5rem;
        }
        
        .info-text {
            color: #166534;
            font-size: 0.875rem;
        }
        
        /* Styles pour l'upload de documents */
        .document-upload-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .upload-area {
            border: 2px dashed #6ee7b7;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #f0fdf4;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #10b981;
            background: #ecfdf5;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #34d399;
            margin-bottom: 1rem;
        }
        
        .upload-text {
            color: #059669;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .upload-hint {
            color: #34d399;
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
            background: #f0fdf4;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #bbf7d0;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .file-icon {
            color: #10b981;
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
            color: #059669;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .guidelines {
            background: #f0fdf4;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .guidelines-title {
            font-weight: 600;
            color: #059669;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .guidelines-list {
            color: #059669;
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
                        <i class="fas fa-user-check title-icon"></i>
                        Certificat de Célibat
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
                    Ce formulaire permet de générer automatiquement votre certificat de célibat. 
                    Assurez-vous que toutes les informations saisies sont exactes.
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="form-container">
                <div class="form-header">
                    <i class="fas fa-user-check form-icon"></i>
                    <h2 class="form-title">Certificat de Célibat</h2>
                    <p class="form-description">Remplissez vos informations personnelles ci-dessous</p>
                </div>

                <form action="{{ route('interactive-forms.generate', 'certificat-celibat') }}" method="POST" enctype="multipart/form-data">
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
                                
                                <div class="input-group form-grid-full">
                                    <label class="input-label" for="domicile">
                                        Domicile/Adresse <span class="input-required">*</span>
                                    </label>
                                    <input type="text" name="domicile" id="domicile" 
                                           class="input-field" value="{{ old('domicile', $userData['address'] ?? '') }}" required>
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

                        <!-- Déclaration -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-pen-fancy section-icon"></i>
                                Déclaration
                            </h3>
                            <div class="form-grid">
                                <div class="input-group form-grid-full">
                                    <label class="input-label" for="motif">
                                        Motif de la demande
                                    </label>
                                    <select name="motif" id="motif" class="input-field">
                                        <option value="">Sélectionner un motif</option>
                                        <option value="Concours administratif">Concours administratif</option>
                                        <option value="Recrutement">Recrutement</option>
                                        <option value="Voyage">Voyage</option>
                                        <option value="Mariage">Mariage</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="input-group form-grid-full">
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
                                Documents requis pour un certificat de célibat
                            </div>
                            <div class="guidelines-list">
                                <ul>
                                    <li>Copie de la pièce d'identité du demandeur</li>
                                    <li>Justificatif de domicile récent</li>
                                    <li>Acte de naissance récent (moins de 3 mois)</li>
                                    <li>Procuration si vous agissez au nom d'un tiers</li>
                                    <li>Affidavit ou déclaration sous serment</li>
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
            var uploadArea = document.getElementById('uploadArea');
            var fileList = document.getElementById('fileList');

            uploadArea.addEventListener('click', function() {
                document.getElementById('fileInput').click();
            });

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

            function handleFiles(files) {
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (validateFile(file)) {
                        addFileToList(file);
                    }
                }
            }

            function validateFile(file) {
                var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                return allowedTypes.includes(file.type);
            }

            function addFileToList(file) {
                var fileItem = document.createElement('div');
                fileItem.classList.add('file-item');
                fileItem.innerHTML = `
                    <div class="file-info">
                        <i class="fas fa-file file-icon"></i>
                        <div class="file-details">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${(file.size / 1024).toFixed(2)} Ko</div>
                        </div>
                    </div>
                    <div class="remove-file" data-file="${file.name}">
                        <i class="fas fa-times"></i>
                    </div>
                `;
                fileList.appendChild(fileItem);
            }

            fileList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    var fileName = e.target.getAttribute('data-file');
                    removeFile(fileName);
                }            });

            function removeFile(fileName) {
                var fileItems = document.querySelectorAll('.file-item');
                fileItems.forEach(function(item) {
                    if (item.querySelector('.remove-file').getAttribute('data-file') === fileName) {
                        item.remove();
                    }
                });
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
        });
    </script>
</body>
</html>
