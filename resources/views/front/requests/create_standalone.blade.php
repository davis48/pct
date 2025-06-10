<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande | PCT UVCI</title>
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
            background: #f8fafc;
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
            text-align: center;
            margin-bottom: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .step-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            padding: 2rem;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            background: #f3f4f6;
            color: #9ca3af;
            border: 2px solid #e5e7eb;
        }
        
        .step-item.active .step-circle {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .step-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
        }
        
        .step-item.active .step-label {
            color: #3b82f6;
        }
        
        .form-section {
            padding: 2rem;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-icon {
            color: #3b82f6;
            font-size: 1.125rem;
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
        
        .file-upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: #f9fafb;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-zone:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        
        .file-upload-icon {
            font-size: 3rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }
        
        .file-upload-text {
            font-size: 1.125rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .file-upload-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .submit-section {
            padding: 2rem;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
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
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
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
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
            cursor: pointer;
        }
        
        .checkbox-input {
            margin: 0;
            margin-top: 0.25rem;
        }
        
        .checkbox-label {
            font-size: 0.875rem;
            color: #374151;
            line-height: 1.5;
        }
        
        .file-list {
            margin-top: 1rem;
            display: none;
        }
        
        .file-list.show {
            display: block;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f9fafb;
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 0.5rem;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .file-icon {
            color: #3b82f6;
        }
        
        .file-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1f2937;
        }
        
        .file-size {
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .file-status {
            color: #10b981;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .button-group {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
        
        .hidden {
            display: none;
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
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="container">
            <!-- En-tête -->
            <div class="page-header">
                <h1 class="page-title">Nouvelle demande de document</h1>
                <p class="page-subtitle">Remplissez ce formulaire pour soumettre votre demande officielle</p>
            </div>

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <p style="font-weight: 600;">Veuillez corriger les erreurs suivantes :</p>
                        <ul style="margin-top: 0.5rem; list-style: disc; padding-left: 1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Formulaire -->
            <div class="form-container">
                <!-- Indicateur de progression -->
                <div class="step-progress">
                    <div class="step-item active">
                        <div class="step-circle">1</div>
                        <span class="step-label">Informations</span>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">2</div>
                        <span class="step-label">Documents</span>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">3</div>
                        <span class="step-label">Confirmation</span>
                    </div>
                </div>

                <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Type de document -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-file-alt section-icon"></i>
                            Type de document demandé
                        </h2>
                        
                        <div class="form-grid">
                            <div class="input-group form-grid-full">
                                <label class="input-label" for="document_id">
                                    Document souhaité <span class="input-required">*</span>
                                </label>
                                <select name="document_id" id="document_id" class="input-field" required>
                                    <option value="">Sélectionnez un document</option>
                                    @foreach($documents as $document)
                                        <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                                            {{ $document->name }} - {{ $document->price }}€
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="type">
                                    Type de demande <span class="input-required">*</span>
                                </label>
                                <select name="type" id="type" class="input-field" required>
                                    <option value="">Choisir le type</option>
                                    <option value="original" {{ old('type') == 'original' ? 'selected' : '' }}>Original</option>
                                    <option value="copie" {{ old('type') == 'copie' ? 'selected' : '' }}>Copie certifiée</option>
                                    <option value="duplicata" {{ old('type') == 'duplicata' ? 'selected' : '' }}>Duplicata</option>
                                </select>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="urgency">Niveau d'urgence</label>
                                <select name="urgency" id="urgency" class="input-field">
                                    <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>Normal (7-10 jours)</option>
                                    <option value="urgent" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>Urgent (3-5 jours)</option>
                                    <option value="very_urgent" {{ old('urgency') == 'very_urgent' ? 'selected' : '' }}>Très urgent (24-48h)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informations personnelles -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-user section-icon"></i>
                            Informations personnelles
                        </h2>
                        
                        <div class="form-grid">
                            <div class="input-group">
                                <label class="input-label" for="place_of_birth">
                                    Lieu de naissance <span class="input-required">*</span>
                                </label>
                                <input type="text" name="place_of_birth" id="place_of_birth" 
                                       class="input-field" value="{{ old('place_of_birth') }}" required>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="profession">
                                    Profession <span class="input-required">*</span>
                                </label>
                                <input type="text" name="profession" id="profession" 
                                       class="input-field" value="{{ old('profession') }}" required>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="cin_number">
                                    Numéro CNI <span class="input-required">*</span>
                                </label>
                                <input type="text" name="cin_number" id="cin_number" 
                                       class="input-field" value="{{ old('cin_number') }}" 
                                       placeholder="Ex: CI0123456789" required>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="nationality">
                                    Nationalité <span class="input-required">*</span>
                                </label>
                                <input type="text" name="nationality" id="nationality" 
                                       class="input-field" value="{{ old('nationality', 'Ivoirienne') }}" required>
                            </div>
                            
                            <div class="input-group form-grid-full">
                                <label class="input-label" for="complete_address">
                                    Adresse complète <span class="input-required">*</span>
                                </label>
                                <textarea name="complete_address" id="complete_address" 
                                          class="input-field" rows="3" required>{{ old('complete_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Détails de la demande -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-clipboard-list section-icon"></i>
                            Détails de la demande
                        </h2>
                        
                        <div class="form-grid">
                            <div class="input-group form-grid-full">
                                <label class="input-label" for="description">
                                    Description de la demande <span class="input-required">*</span>
                                </label>
                                <textarea name="description" id="description" 
                                          class="input-field" rows="4" required 
                                          placeholder="Décrivez votre demande en détail...">{{ old('description') }}</textarea>
                            </div>
                            
                            <div class="input-group form-grid-full">
                                <label class="input-label" for="reason">
                                    Motif de la demande <span class="input-required">*</span>
                                </label>
                                <textarea name="reason" id="reason" 
                                          class="input-field" rows="3" required 
                                          placeholder="Indiquez pourquoi vous avez besoin de ce document...">{{ old('reason') }}</textarea>
                            </div>
                            
                            <div class="input-group">
                                <label class="input-label" for="contact_preference">Préférence de contact</label>
                                <select name="contact_preference" id="contact_preference" class="input-field">
                                    <option value="email" {{ old('contact_preference') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="phone" {{ old('contact_preference') == 'phone' ? 'selected' : '' }}>Téléphone</option>
                                    <option value="both" {{ old('contact_preference') == 'both' ? 'selected' : '' }}>Email et téléphone</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Documents à joindre -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-paperclip section-icon"></i>
                            Documents à joindre
                        </h2>
                        
                        <div class="file-upload-zone" onclick="document.getElementById('attachments').click()">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <p class="file-upload-text">Glissez vos fichiers ici ou cliquez pour sélectionner</p>
                            <p class="file-upload-subtitle">Formats acceptés : PDF, JPG, JPEG, PNG (max 5MB par fichier)</p>
                            <input type="file" name="attachments[]" id="attachments" multiple 
                                   accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
                        </div>
                        
                        <div id="file-list" class="file-list">
                            <h4 style="font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Fichiers sélectionnés :</h4>
                            <div id="files-container"></div>
                        </div>
                    </div>

                    <!-- Section 5: Validation -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-check-circle section-icon"></i>
                            Validation et conditions
                        </h2>
                        
                        <div>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="confirm_accuracy" class="checkbox-input" required>
                                <span class="checkbox-label">
                                    Je certifie que toutes les informations fournies sont exactes et complètes. 
                                    Je m'engage à fournir les documents originaux lors du retrait si nécessaire.
                                </span>
                            </label>
                            
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="accept_terms" class="checkbox-input" required>
                                <span class="checkbox-label">
                                    J'accepte les conditions générales d'utilisation et la politique de confidentialité. 
                                    Je comprends que cette demande sera traitée selon les délais annoncés.
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Boutons de soumission -->
                    <div class="submit-section">
                        <div class="button-group">
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Retour aux demandes
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Soumettre la demande
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('attachments');
        const fileList = document.getElementById('file-list');
        const filesContainer = document.getElementById('files-container');
        const uploadZone = document.querySelector('.file-upload-zone');

        fileInput.addEventListener('change', handleFiles);
        
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#3b82f6';
            uploadZone.style.background = '#eff6ff';
        });
        
        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#d1d5db';
            uploadZone.style.background = '#f9fafb';
        });
        
        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#d1d5db';
            uploadZone.style.background = '#f9fafb';
            fileInput.files = e.dataTransfer.files;
            handleFiles();
        });

        function handleFiles() {
            const files = fileInput.files;
            if (files.length > 0) {
                fileList.classList.add('show');
                filesContainer.innerHTML = '';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <div class="file-info">
                            <i class="fas fa-file-alt file-icon"></i>
                            <div>
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                        </div>
                        <i class="fas fa-check-circle file-status"></i>
                    `;
                    filesContainer.appendChild(fileItem);
                }
            } else {
                fileList.classList.remove('show');
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
    </script>
</body>
</html>
