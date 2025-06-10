@extends('layouts.front.app')

@section('title', 'Nouvelle demande | Plateforme Administrative')

@push('styles')
<style>
    .step-progress {
        position: relative;
    }
    
    .step-progress::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, #10b981, #3b82f6, #6366f1, #8b5cf6);
        z-index: 1;
    }
    
    .step-item {
        position: relative;
        z-index: 2;
    }
    
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .step-item.completed .step-circle {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .step-item.active .step-circle {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        animation: pulse 2s infinite;
    }
    
    .step-item.pending .step-circle {
        background: #f3f4f6;
        color: #9ca3af;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        50% { box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6); }
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .form-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #3b82f6;
    }
    
    .input-group-modern {
        position: relative;
    }
    
    .input-modern {
        padding-left: 3rem;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 10;
    }
    
    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
    }
    
    .file-upload-area:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .file-upload-area.dragover {
        border-color: #10b981;
        background: #ecfdf5;
        transform: scale(1.02);
    }
    
    .file-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin: 0.5rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
      .file-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold gradient-text mb-2">Nouvelle demande</h1>
                <p class="text-gray-600">Remplissez soigneusement ce formulaire pour traiter votre demande dans les meilleurs délais</p>
            </div>

            <!-- Indicateur de progression -->
            <div class="step-progress flex justify-between mb-12">
                <div class="step-item active flex flex-col items-center">
                    <div class="step-circle text-sm">1</div>
                    <span class="mt-2 text-xs font-medium text-primary-600">Type de demande</span>
                </div>
                <div class="step-item pending flex flex-col items-center">
                    <div class="step-circle text-sm">2</div>
                    <span class="mt-2 text-xs font-medium text-gray-400">Informations</span>
                </div>
                <div class="step-item pending flex flex-col items-center">
                    <div class="step-circle text-sm">3</div>
                    <span class="mt-2 text-xs font-medium text-gray-400">Documents</span>
                </div>
                <div class="step-item pending flex flex-col items-center">
                    <div class="step-circle text-sm">4</div>
                    <span class="mt-2 text-xs font-medium text-gray-400">Validation</span>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 mb-1">Veuillez corriger les erreurs suivantes :</h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center">
                                        <i class="fas fa-circle text-xs mr-2"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="form-card">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-file-plus mr-3"></i>
                        Nouvelle demande de document administratif
                    </h2>
                </div>
                
                <div class="p-8">
                    <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" id="request-form" class="space-y-8">
                        @csrf
                        
                        <!-- Section 1: Type de demande -->
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-clipboard-list mr-2 text-primary-600"></i>
                                Informations sur la demande
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-list-alt mr-2 text-gray-400"></i>
                                        Type de demande <span class="text-red-500">*</span>
                                    </label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('type') border-red-500 ring-2 ring-red-200 @enderror" 
                                            id="type" name="type" required>
                                        <option value="" selected disabled>Sélectionnez un type de demande</option>
                                        <option value="attestation" {{ old('type') == 'attestation' ? 'selected' : '' }}>
                                            📄 Attestation de domicile
                                        </option>
                                        <option value="legalisation" {{ old('type') == 'legalisation' ? 'selected' : '' }}>
                                            ✅ Légalisation de document
                                        </option>
                                        <option value="mariage" {{ old('type') == 'mariage' ? 'selected' : '' }}>
                                            💒 Certificat de mariage
                                        </option>
                                        <option value="extrait-acte" {{ old('type') == 'extrait-acte' ? 'selected' : '' }}>
                                            🎂 Extrait d'acte de naissance
                                        </option>
                                        <option value="declaration-naissance" {{ old('type') == 'declaration-naissance' ? 'selected' : '' }}>
                                            👶 Déclaration de naissance
                                        </option>
                                        <option value="certificat" {{ old('type') == 'certificat' ? 'selected' : '' }}>
                                            💍 Certificat de célibat
                                        </option>
                                        <option value="information" {{ old('type') == 'information' ? 'selected' : '' }}>
                                            ℹ️ Demande d'information
                                        </option>
                                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>
                                            📋 Autre
                                        </option>
                                    </select>
                                    @error('type')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="urgent" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                                        Priorité
                                    </label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" 
                                            id="urgent" name="urgent">
                                        <option value="0" {{ old('urgent') == '0' ? 'selected' : '' }}>🕐 Normal (5-7 jours)</option>
                                        <option value="1" {{ old('urgent') == '1' ? 'selected' : '' }}>⚡ Urgent (2-3 jours)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-comment-alt mr-2 text-gray-400"></i>
                                    Description de la demande
                                </label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Décrivez brièvement votre demande et toute information importante...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section 2: Informations personnelles -->
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-primary-600"></i>
                                Informations personnelles
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="input-group-modern">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénom(s) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                               class="input-modern w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('first_name') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="first_name" 
                                               name="first_name" 
                                               value="{{ old('first_name') }}" 
                                               required
                                               placeholder="Votre prénom">
                                    </div>
                                    @error('first_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="input-group-modern">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom de famille <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                               class="input-modern w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('last_name') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="last_name" 
                                               name="last_name" 
                                               value="{{ old('last_name') }}" 
                                               required
                                               placeholder="Votre nom de famille">
                                    </div>
                                    @error('last_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="input-group-modern">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Numéro de téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="tel" 
                                               class="input-modern w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('phone') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               required
                                               placeholder="+225 XX XX XX XX">
                                    </div>
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="input-group-modern">
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date de naissance
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-calendar input-icon"></i>
                                        <input type="date" 
                                               class="input-modern w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('birth_date') border-red-500 ring-2 ring-red-200 @enderror" 
                                               id="birth_date" 
                                               name="birth_date" 
                                               value="{{ old('birth_date') }}">
                                    </div>
                                    @error('birth_date')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    Adresse complète <span class="text-red-500">*</span>
                                </label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('address') border-red-500 ring-2 ring-red-200 @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          required
                                          placeholder="Quartier, rue, ville, code postal...">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section 3: Documents -->
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-paperclip mr-2 text-primary-600"></i>
                                Documents justificatifs
                            </h3>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">Documents recommandés</h4>
                                        <div class="mt-1 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Pièce d'identité (CNI, passeport)</li>
                                                <li>Justificatif de domicile</li>
                                                <li>Tout document relatif à votre demande</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="file-upload-area" id="file-upload-area">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <h4 class="text-lg font-medium text-gray-700 mb-2">Glissez vos fichiers ici</h4>
                                    <p class="text-sm text-gray-500 mb-4">ou cliquez pour sélectionner</p>
                                    <input type="file" 
                                           class="hidden" 
                                           id="attachments" 
                                           name="attachments[]" 
                                           multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <button type="button" 
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200"
                                            onclick="document.getElementById('attachments').click()">
                                        <i class="fas fa-plus mr-2"></i>
                                        Ajouter des fichiers
                                    </button>
                                    <p class="text-xs text-gray-400 mt-2">PDF, JPG, PNG, DOC - Max 5MB par fichier</p>
                                </div>
                            </div>

                            <div id="file-list" class="mt-4 space-y-2"></div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard') }}" 
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-primary-200">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Soumettre la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileUploadArea = document.getElementById('file-upload-area');
        const fileInput = document.getElementById('attachments');
        const fileList = document.getElementById('file-list');
        let uploadedFiles = [];

        // Gestion du drag & drop
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // Gestion de la sélection de fichiers
        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (validateFile(file)) {
                    uploadedFiles.push(file);
                    displayFile(file);
                }
            });
        }

        function validateFile(file) {
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                showNotification('Type de fichier non autorisé: ' + file.name, 'error');
                return false;
            }

            if (file.size > maxSize) {
                showNotification('Fichier trop volumineux: ' + file.name, 'error');
                return false;
            }

            return true;
        }

        function displayFile(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            
            const fileIcon = getFileIcon(file.type);
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <i class="${fileIcon} text-primary-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-900">${file.name}</div>
                        <div class="text-sm text-gray-500">${fileSize}</div>
                    </div>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeFile(this, '${file.name}')">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }

        function getFileIcon(type) {
            if (type.includes('pdf')) return 'fas fa-file-pdf';
            if (type.includes('image')) return 'fas fa-file-image';
            if (type.includes('word')) return 'fas fa-file-word';
            return 'fas fa-file';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        window.removeFile = function(button, fileName) {
            uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
            button.closest('.file-item').remove();
        };

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
            const icon = type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500';
            
            notification.className = `fixed top-24 right-4 z-50 ${bgColor} border rounded-lg p-4 shadow-2xl transform transition-all duration-500 translate-x-full`;
            notification.style.minWidth = '350px';
            
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${icon} text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="text-sm">${message}</div>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                        <span class="sr-only">Fermer</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }, 5000);
        }

        // Animation des étapes
        document.querySelectorAll('.step-item').forEach(function(step, index) {
            step.style.opacity = '0';
            step.style.transform = 'translateY(20px)';
            setTimeout(function() {
                step.style.transition = 'all 0.5s ease';
                step.style.opacity = '1';
                step.style.transform = 'translateY(0)';
            }, 200 * index);
        });

        // Validation du formulaire
        document.getElementById('request-form').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Envoi en cours...';
        });
    });
</script>
@endpush
