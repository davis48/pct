@extends('layouts.front.app')

@push('styles')
<style>
    .step-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
    }
    
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #667eea;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
    
    .info-card {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .document-preview {
        background: white;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        margin-top: 1rem;
    }
    
    .file-item {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        margin: 0.5rem 0;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .progress-tracker {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }
    
    .progress-step::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    
    .progress-step:last-child::before {
        display: none;
    }
    
    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        position: relative;
        z-index: 2;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .step-circle.active {
        background: #667eea;
    }
    
    .step-circle.completed {
        background: #28a745;
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('requests.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Retour aux demandes
            </a>
        </div>

        <!-- Tracker de progression -->
        <div class="progress-tracker">
            <div class="progress-step">
                <div class="step-circle active">1</div>
                <span class="text-sm">Type de demande</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">2</div>
                <span class="text-sm">Informations</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <span class="text-sm">Documents</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">4</div>
                <span class="text-sm">Validation</span>
            </div>
        </div>

        <div class="card shadow-lg border-0">
            <div class="step-header">
                <h2 class="mb-0">
                    <i class="fas fa-file-plus me-2"></i>
                    Nouvelle demande de document administratif
                </h2>
                <p class="mb-0 mt-2 opacity-90">
                    Remplissez soigneusement ce formulaire pour traiter votre demande dans les meilleurs délais
                </p>
            </div>
            
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Veuillez corriger les erreurs suivantes :</h6>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" id="request-form">
                    @csrf
                    
                    <!-- Section 1: Type de demande -->
                    <div class="form-section">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Informations sur la demande
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label required-field">Type de demande</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section d'invitation au téléchargement du formulaire -->
                        <div id="form-download-invitation" class="alert alert-info border-0 shadow-sm mt-3" style="display: none;">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-download fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-2">
                                        <i class="fas fa-file-pdf me-2"></i>Formulaire disponible
                                    </h5>
                                    <p class="mb-3">
                                        Pour vous faciliter la tâche, vous pouvez télécharger le formulaire pré-rempli correspondant à votre demande. 
                                        Cela vous permettra de préparer vos informations à l'avance.
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="#" id="download-form-btn" class="btn btn-info btn-sm">
                                            <i class="fas fa-download me-2"></i>Télécharger le formulaire
                                        </a>
                                        <a href="{{ route('formulaires.index') }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-list me-2"></i>Voir tous les formulaires
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Le téléchargement du formulaire est optionnel. Vous pouvez continuer à remplir directement ce formulaire en ligne.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                                <label for="document_id" class="form-label required-field">Document associé</label>
                                <select class="form-select @error('document_id') is-invalid @enderror" id="document_id" name="document_id" required>
                                    <option value="" selected disabled>Sélectionnez un document</option>
                                    @foreach($documents as $document)
                                    <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                                        {{ $document->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('document_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Le modèle officiel de document qui sera généré
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="urgency" class="form-label">Niveau d'urgence</label>
                            <select class="form-select @error('urgency') is-invalid @enderror" id="urgency" name="urgency">
                                <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>
                                    🔵 Normal (7-10 jours ouvrables)
                                </option>
                                <option value="urgent" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>
                                    🟡 Urgent (3-5 jours ouvrables) - Frais supplémentaires
                                </option>
                                <option value="very_urgent" {{ old('urgency') == 'very_urgent' ? 'selected' : '' }}>
                                    🔴 Très urgent (24-48h) - Frais supplémentaires
                                </option>
                            </select>
                            @error('urgency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label required-field">Motif de la demande</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3" 
                                      placeholder="Ex: Pour constituer un dossier de candidature, pour un mariage, etc." required>{{ old('reason') }}</textarea>
                            @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                Précisez l'usage prévu du document pour faciliter le traitement
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informations personnelles complémentaires -->
                    <div class="form-section">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-user-edit me-2"></i>
                            Informations personnelles pour le document
                        </h4>
                        
                        <div class="info-card">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <div>
                                    <strong>Pourquoi ces informations ?</strong>
                                    <br><small class="text-muted">Ces informations seront utilisées pour générer automatiquement votre document officiel. Assurez-vous qu'elles soient exactes.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="place_of_birth" class="form-label required-field">Lieu de naissance</label>
                                <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" 
                                       id="place_of_birth" name="place_of_birth" 
                                       value="{{ old('place_of_birth', Auth::user()->place_of_birth) }}" 
                                       placeholder="Ex: Abidjan, Côte d'Ivoire" required>
                                @error('place_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="profession" class="form-label required-field">Profession</label>
                                <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                       id="profession" name="profession" 
                                       value="{{ old('profession', Auth::user()->profession) }}" 
                                       placeholder="Ex: Étudiant, Commerçant, Fonctionnaire" required>
                                @error('profession')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cin_number" class="form-label required-field">Numéro de CNI</label>
                                <input type="text" class="form-control @error('cin_number') is-invalid @enderror" 
                                       id="cin_number" name="cin_number" 
                                       value="{{ old('cin_number', Auth::user()->cin_number) }}" 
                                       placeholder="Ex: CI0123456789" 
                                       pattern="[A-Z]{2}[0-9]{10}" required>
                                @error('cin_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: 2 lettres suivies de 10 chiffres (ex: CI0123456789)</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label required-field">Nationalité</label>
                                <select class="form-select @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required>
                                    <option value="Ivoirienne" {{ old('nationality', Auth::user()->nationality ?? 'Ivoirienne') == 'Ivoirienne' ? 'selected' : '' }}>Ivoirienne</option>
                                    <option value="Française" {{ old('nationality') == 'Française' ? 'selected' : '' }}>Française</option>
                                    <option value="Burkinabé" {{ old('nationality') == 'Burkinabé' ? 'selected' : '' }}>Burkinabé</option>
                                    <option value="Malienne" {{ old('nationality') == 'Malienne' ? 'selected' : '' }}>Malienne</option>
                                    <option value="Ghanéenne" {{ old('nationality') == 'Ghanéenne' ? 'selected' : '' }}>Ghanéenne</option>
                                    <option value="Autre" {{ old('nationality') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="complete_address" class="form-label required-field">Adresse complète actuelle</label>
                            <textarea class="form-control @error('complete_address') is-invalid @enderror" 
                                      id="complete_address" name="complete_address" rows="3"
                                      placeholder="Ex: Lot 123, Rue des Jardins, Quartier Plateau, Commune de Cocody, Abidjan" required>{{ old('complete_address', Auth::user()->address) }}</textarea>
                            @error('complete_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Indiquez votre adresse complète avec quartier, commune et ville
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Description et contexte -->
                    <div class="form-section">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-edit me-2"></i>
                            Description de votre demande
                        </h4>

                        <div class="mb-3">
                            <label for="description" class="form-label required-field">Description détaillée</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Décrivez précisément votre demande, le contexte et toute information utile au traitement..." required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Plus votre description est précise, plus le traitement sera rapide et efficace
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_preference" class="form-label">Préférence de contact</label>
                            <select class="form-select @error('contact_preference') is-invalid @enderror" id="contact_preference" name="contact_preference">
                                <option value="email" {{ old('contact_preference') == 'email' ? 'selected' : '' }}>
                                    📧 Email (par défaut)
                                </option>
                                <option value="phone" {{ old('contact_preference') == 'phone' ? 'selected' : '' }}>
                                    📞 Téléphone
                                </option>
                                <option value="both" {{ old('contact_preference') == 'both' ? 'selected' : '' }}>
                                    📧📞 Email et téléphone
                                </option>
                            </select>
                            @error('contact_preference')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Section 4: Documents joints -->
                    <div class="form-section">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-paperclip me-2"></i>
                            Documents justificatifs
                        </h4>

                        <div class="info-card">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-upload text-primary me-2 mt-1"></i>
                                <div>
                                    <strong>Documents requis selon le type de demande :</strong>
                                    <ul class="mt-2 mb-0" id="required-docs-list">
                                        <li>Copie de votre pièce d'identité (CNI, passeport)</li>
                                        <li>Documents justificatifs selon le type de demande</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="attachments" class="form-label required-field">Pièces jointes</label>
                            <input type="file" 
                                   class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   required>
                            @error('attachments')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats acceptés: PDF, JPG, PNG (max: 5MB par fichier, 5 fichiers maximum)
                            </div>
                            
                            <!-- Prévisualisation des fichiers -->
                            <div id="files-preview" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Section 5: Résumé et validation -->
                    <div class="form-section">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            Validation de la demande
                        </h4>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                <div>
                                    <strong>Prochaines étapes :</strong>
                                    <ol class="mt-2 mb-0">
                                        <li>Votre demande sera examinée par nos services</li>
                                        <li>Vous recevrez une notification de validation ou de demande de complément</li>
                                        <li>Une fois validée, vous pourrez effectuer le paiement</li>
                                        <li>Votre document sera traité et vous pourrez le télécharger</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm_accuracy" name="confirm_accuracy" required>
                            <label class="form-check-label" for="confirm_accuracy">
                                <strong>Je certifie que toutes les informations fournies sont exactes et complètes</strong>
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                J'accepte les <a href="#" class="text-decoration-none">conditions générales d'utilisation</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Soumettre la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('request-form');
    const fileInput = document.getElementById('attachments');
    const documentSelect = document.getElementById('document_id');
    const typeSelect = document.getElementById('type');
    const filesPreview = document.getElementById('files-preview');
    const progressSteps = document.querySelectorAll('.step-circle');
    const requiredDocsList = document.getElementById('required-docs-list');
    
    // Définition des documents requis selon le type de demande
    const requiredDocuments = {
        'attestation': [
            'Copie de votre CNI ou passeport',
            'Justificatif de domicile (facture d\'eau, électricité ou téléphone récente)',
            'Photo d\'identité récente'
        ],
        'legalisation': [
            'Document original à légaliser',
            'Copie de votre CNI',
            'Justificatif du motif de légalisation'
        ],
        'mariage': [
            'Copie CNI des deux époux',
            'Certificat de célibat de chaque époux',
            'Certificat médical prénuptial',
            'Photos d\'identité récentes (4 par personne)'
        ],
        'extrait-acte': [
            'Copie de votre CNI',
            'Justificatif de filiation si nécessaire',
            'Photo d\'identité récente'
        ],
        'declaration-naissance': [
            'Certificat de naissance de l\'hôpital',
            'CNI des parents',
            'Certificat de mariage des parents (si applicable)',
            'Témoins avec leurs CNI'
        ],
        'certificat': [
            'Copie de votre CNI',
            'Certificat de résidence',
            'Photo d\'identité récente'
        ],
        'information': [
            'Copie de votre CNI',
            'Documents relatifs à votre demande d\'information'
        ],
        'autre': [
            'Copie de votre CNI',
            'Tous documents justificatifs pertinents'
        ]
    };

    // Mise à jour des documents requis selon le type sélectionné
    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        if (selectedType && requiredDocuments[selectedType]) {
            requiredDocsList.innerHTML = '';
            requiredDocuments[selectedType].forEach(doc => {
                const li = document.createElement('li');
                li.textContent = doc;
                requiredDocsList.appendChild(li);
            });
        }
        updateProgressTracker();
    });

    // Validation en temps réel et progression
    function updateProgressTracker() {
        const formData = new FormData(form);
        let completedSteps = 0;
        
        // Étape 1: Type de demande
        if (formData.get('type') && formData.get('document_id')) {
            completedSteps = 1;
        }
        
        // Étape 2: Informations personnelles
        if (formData.get('place_of_birth') && formData.get('profession') && 
            formData.get('cin_number') && formData.get('complete_address')) {
            completedSteps = 2;
        }
        
        // Étape 3: Documents
        if (fileInput.files.length > 0) {
            completedSteps = 3;
        }
        
        // Étape 4: Validation
        if (formData.get('confirm_accuracy') && formData.get('accept_terms')) {
            completedSteps = 4;
        }
        
        // Mise à jour visuelle des étapes
        progressSteps.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index < completedSteps) {
                step.classList.add('completed');
                step.innerHTML = '<i class="fas fa-check"></i>';
            } else if (index === completedSteps) {
                step.classList.add('active');
                step.textContent = index + 1;
            } else {
                step.textContent = index + 1;
            }
        });
    }

    // Validation du formulaire avant soumission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessages = [];
        
        // Vérification des fichiers
        if (fileInput.files.length === 0) {
            isValid = false;
            errorMessages.push('Veuillez joindre au moins un document à votre demande.');
            fileInput.classList.add('is-invalid');
        } else {
            // Vérification de la taille des fichiers
            for (let i = 0; i < fileInput.files.length; i++) {
                if (fileInput.files[i].size > 5 * 1024 * 1024) { // 5MB
                    isValid = false;
                    errorMessages.push(`Le fichier "${fileInput.files[i].name}" dépasse la taille maximale de 5MB.`);
                }
            }
            fileInput.classList.remove('is-invalid');
        }
        
        // Vérification du document sélectionné
        if (!documentSelect.value) {
            isValid = false;
            errorMessages.push('Veuillez sélectionner un document associé à votre demande.');
            documentSelect.classList.add('is-invalid');
        } else {
            documentSelect.classList.remove('is-invalid');
        }
        
        // Validation du numéro CNI
        const cinNumber = document.getElementById('cin_number').value;
        const cinPattern = /^[A-Z]{2}[0-9]{10}$/;
        if (cinNumber && !cinPattern.test(cinNumber)) {
            isValid = false;
            errorMessages.push('Le format du numéro CNI est incorrect (ex: CI0123456789).');
            document.getElementById('cin_number').classList.add('is-invalid');
        } else {
            document.getElementById('cin_number').classList.remove('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            
            // Affichage des erreurs dans une alerte moderne
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Veuillez corriger les erreurs suivantes :</h6>
                    <ul class="mb-0 mt-2">
                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Insertion de l'alerte en haut du formulaire
            const existingAlert = form.querySelector('.alert-danger');
            if (existingAlert) {
                existingAlert.remove();
            }
            form.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Scroll vers le haut pour voir les erreurs
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            // Affichage d'un indicateur de soumission
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
            submitBtn.disabled = true;
        }
    });
    
    // Prévisualisation des fichiers sélectionnés avec amélioration visuelle
    fileInput.addEventListener('change', function() {
        filesPreview.innerHTML = '';
        
        if (this.files.length > 0) {
            const container = document.createElement('div');
            container.className = 'row g-3';
            
            Array.from(this.files).forEach((file, index) => {
                const col = document.createElement('div');
                col.className = 'col-md-6';
                
                const fileCard = document.createElement('div');
                fileCard.className = 'file-item';
                
                // Icône selon le type de fichier
                let icon = 'fas fa-file';
                if (file.type.includes('pdf')) icon = 'fas fa-file-pdf text-danger';
                else if (file.type.includes('image')) icon = 'fas fa-file-image text-primary';
                
                // Taille du fichier formatée
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const sizeColor = fileSize > 5 ? 'text-danger' : 'text-success';
                
                fileCard.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="${icon} me-3" style="font-size: 1.5rem;"></i>
                        <div class="flex-grow-1">
                            <div class="fw-medium">${file.name}</div>
                            <small class="${sizeColor}">${fileSize} MB</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                col.appendChild(fileCard);
                container.appendChild(col);
            });
            
            filesPreview.appendChild(container);
        }
        
        updateProgressTracker();
    });

    // Fonction pour supprimer un fichier (simulation - nécessite une implémentation plus complexe pour les vrais fichiers)
    window.removeFile = function(index) {
        // Cette fonction nécessiterait une implémentation plus complexe pour modifier l'input file
        alert('Pour supprimer un fichier, veuillez le désélectionner et sélectionner à nouveau vos fichiers.');
    };
    
    // Auto-complétion et validation en temps réel
    const inputFields = ['place_of_birth', 'profession', 'cin_number', 'complete_address'];
    inputFields.forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('input', updateProgressTracker);
    });
    
    // Validation du format CNI en temps réel
    document.getElementById('cin_number').addEventListener('input', function() {
        const value = this.value.toUpperCase();
        this.value = value;
        
        const pattern = /^[A-Z]{2}[0-9]{10}$/;
        if (value.length > 0) {
            if (pattern.test(value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    // Sauvegarde automatique des données du formulaire dans le localStorage
    function saveFormData() {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key !== 'attachments[]') { // Exclure les fichiers
                data[key] = value;
            }
        }
        localStorage.setItem('requestFormData', JSON.stringify(data));
    }
    
    // Restauration des données sauvegardées
    function loadFormData() {
        const savedData = localStorage.getItem('requestFormData');
        if (savedData) {
            const data = JSON.parse(savedData);
            Object.keys(data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'file') {
                    field.value = data[key];
                    if (field.type === 'checkbox') {
                        field.checked = data[key] === 'on';
                    }
                }
            });
        }
    }
    
    // Sauvegarde automatique toutes les 30 secondes
    setInterval(saveFormData, 30000);
    
    // Charger les données sauvegardées au chargement de la page
    loadFormData();
    
    // Vider le localStorage après soumission réussie
    form.addEventListener('submit', function() {
        setTimeout(() => localStorage.removeItem('requestFormData'), 1000);
    });
    
    // Gestion de l'invitation au téléchargement du formulaire
    const formDownloadInvitation = document.getElementById('form-download-invitation');
    const downloadFormBtn = document.getElementById('download-form-btn');
    
    // Mapping des types vers les formulaires
    const formMappings = {
        'attestation': {
            name: 'le formulaire d\'attestation de domicile',
            url: '{{ route("formulaires.download", "attestation-domicile") }}'
        },
        'legalisation': {
            name: 'le formulaire de légalisation',
            url: '{{ route("formulaires.index") }}' // Pas de formulaire spécifique pour légalisation
        },
        'mariage': {
            name: 'le formulaire de certificat de mariage',
            url: '{{ route("formulaires.download", "certificat-mariage") }}'
        },
        'extrait-acte': {
            name: 'le formulaire d\'extrait d\'acte de naissance',
            url: '{{ route("formulaires.download", "extrait-acte-naissance") }}'
        },
        'declaration-naissance': {
            name: 'le formulaire de déclaration de naissance',
            url: '{{ route("formulaires.download", "declaration-naissance") }}'
        },
        'certificat': {
            name: 'le formulaire de certificat de célibat',
            url: '{{ route("formulaires.download", "certificat-celibat") }}'
        }
    };
    
    // Fonction pour gérer l'affichage de l'invitation au téléchargement
    function handleFormDownloadInvitation() {
        const selectedType = typeSelect.value;
        
        if (selectedType && formMappings[selectedType]) {
            const mapping = formMappings[selectedType];
            downloadFormBtn.innerHTML = `<i class="fas fa-download me-2"></i>Télécharger ${mapping.name}`;
            downloadFormBtn.href = mapping.url;
            formDownloadInvitation.style.display = 'block';
            
            // Animation d'apparition
            formDownloadInvitation.style.opacity = '0';
            formDownloadInvitation.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                formDownloadInvitation.style.transition = 'all 0.3s ease';
                formDownloadInvitation.style.opacity = '1';
                formDownloadInvitation.style.transform = 'translateY(0)';
            }, 10);
        } else {
            formDownloadInvitation.style.display = 'none';
        }
    }
    
    // Ajouter l'événement pour l'invitation au téléchargement
    typeSelect.addEventListener('change', handleFormDownloadInvitation);
    
    // Initialisation
    updateProgressTracker();
});
</script>
@endsection
