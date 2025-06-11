<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Légalisation de Document - Mairie d'Abidjan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Légalisation de Document</h1>
                <p class="text-lg text-gray-600">Demande de légalisation d'un document officiel</p>
                <div class="mt-4 text-sm text-gray-500">
                    <p>Mairie d'Abidjan - Service de Légalisation</p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                    <span>Informations du demandeur</span>
                    <span>Document à légaliser</span>
                    <span>Finalisation</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                </div>
            </div>

            <form action="#" method="POST" class="space-y-8" id="legalisationForm" enctype="multipart/form-data">
                <!-- Informations personnelles -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">1</div>
                        <h2 class="text-2xl font-semibold text-gray-900">Informations du demandeur</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" id="nom" name="nom" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Votre nom et prénoms">
                        </div>

                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                            <input type="date" id="date_naissance" name="date_naissance" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <div>
                            <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance *</label>
                            <input type="text" id="lieu_naissance" name="lieu_naissance" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Ville ou commune de naissance">
                        </div>

                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profession *</label>
                            <input type="text" id="profession" name="profession" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Votre profession">
                        </div>

                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète *</label>
                            <textarea id="adresse" name="adresse" required rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                      placeholder="Votre adresse complète"></textarea>
                        </div>

                        <div>
                            <label for="numero_cni" class="block text-sm font-medium text-gray-700 mb-2">Numéro CNI *</label>
                            <input type="text" id="numero_cni" name="numero_cni" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Numéro de votre CNI">
                        </div>
                    </div>
                </div>

                <!-- Informations du document -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">2</div>
                        <h2 class="text-2xl font-semibold text-gray-900">Document à légaliser</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Type de document *</label>
                            <select id="document_type" name="document_type" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez le type de document</option>
                                <option value="certificat_naissance">Certificat de naissance</option>
                                <option value="certificat_mariage">Certificat de mariage</option>
                                <option value="certificat_deces">Certificat de décès</option>
                                <option value="diplome">Diplôme</option>
                                <option value="attestation">Attestation</option>
                                <option value="contrat">Contrat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="document_date" class="block text-sm font-medium text-gray-700 mb-2">Date du document original *</label>
                            <input type="date" id="document_date" name="document_date" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>

                        <div>
                            <label for="issuing_authority" class="block text-sm font-medium text-gray-700 mb-2">Autorité émettrice *</label>
                            <input type="text" id="issuing_authority" name="issuing_authority" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Ex: Mairie de Cocody, Ministère de l'Education">
                        </div>

                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro du document</label>
                            <input type="text" id="document_number" name="document_number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Numéro de référence du document (si applicable)">
                        </div>

                        <div class="md:col-span-2">
                            <label for="motif_demande" class="block text-sm font-medium text-gray-700 mb-2">Motif de la demande *</label>
                            <select id="motif_demande" name="motif_demande" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Sélectionnez le motif</option>
                                <option value="usage_etranger">Usage à l'étranger</option>
                                <option value="procedure_administrative">Procédure administrative</option>
                                <option value="procedure_judiciaire">Procédure judiciaire</option>
                                <option value="scolarite">Scolarité/Études</option>
                                <option value="emploi">Emploi</option>
                                <option value="autre">Autre motif</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination du document</label>
                            <input type="text" id="destination" name="destination" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Pays ou institution de destination (si applicable)">
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">3</div>
                        <h2 class="text-2xl font-semibold text-gray-900">Finalisation</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-blue-900">Informations importantes</h3>
                                    <div class="mt-2 text-sm text-blue-800">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>La légalisation certifie uniquement l'authenticité de la signature et du cachet</li>
                                            <li>Elle ne préjuge pas du contenu du document</li>
                                            <li>Pour un usage à l'étranger, une apostille peut être nécessaire</li>
                                            <li>Le document original doit être présenté lors du retrait</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-yellow-900">Pièces à fournir</h3>
                                    <div class="mt-2 text-sm text-yellow-800">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Document original à légaliser</li>
                                            <li>Copie de la CNI du demandeur</li>
                                            <li>Justificatif du motif de la demande (si applicable)</li>
                                            <li>Frais de légalisation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>                        <div class="flex items-center">
                            <input id="accept_terms" name="accept_terms" type="checkbox" required 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                                J'accepte les conditions générales et certifie l'exactitude des informations fournies *
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Documents Justificatifs -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 000-2.828z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900">Documents Justificatifs</h2>
                    </div>
                    
                    <!-- Directives -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-blue-800 font-semibold mb-2">Documents requis pour une légalisation</h3>
                                <ul class="text-blue-700 text-sm space-y-1 list-disc list-inside">
                                    <li>Document original à légaliser</li>
                                    <li>Copie de la pièce d'identité du demandeur</li>
                                    <li>Justificatif de domicile récent</li>
                                    <li>Procuration si vous agissez au nom d'un tiers</li>
                                    <li>Justificatif de paiement des frais de légalisation</li>
                                </ul>
                                <p class="text-blue-600 text-sm mt-2 italic">
                                    Formats acceptés: PDF, JPG, PNG. Taille max: 5MB par fichier.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Zone d'upload -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 hover:border-blue-400 transition-all duration-200 cursor-pointer" onclick="document.getElementById('documents').click()">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <div class="text-lg font-medium text-gray-700 mb-2">Cliquez ici pour sélectionner vos documents</div>
                        <div class="text-gray-500">ou glissez-déposez vos fichiers ici</div>
                    </div>
                    
                    <input type="file" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleFileSelect(event)">
                    
                    <!-- Liste des fichiers -->
                    <div id="fileList" class="mt-4 space-y-2"></div>
                    <div id="fileCounter" class="text-sm text-gray-600 mt-2">0/8 documents sélectionnés</div>
                    
                    <!-- Messages -->
                    <div id="errorMessage" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"></div>
                    <div id="successMessage" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"></div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="button" onclick="window.print()" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimer le formulaire
                    </button>
                    
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('legalisationForm');
        const progressBar = document.querySelector('.bg-blue-600');
        
        // Simuler la progression basée sur les sections remplies
        function updateProgress() {
            const sections = document.querySelectorAll('.bg-white');
            let completedSections = 0;
            
            sections.forEach((section, index) => {
                const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
                let filledInputs = 0;
                
                inputs.forEach(input => {
                    if (input.value.trim() !== '') {
                        filledInputs++;
                    }
                });
                
                if (filledInputs === inputs.length) {
                    completedSections++;
                }
            });
            
            const progress = (completedSections / 3) * 100;
            progressBar.style.width = progress + '%';
        }
        
        // Validation en temps réel
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-300');
                    this.classList.remove('border-gray-300');
                } else {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-gray-300');
                }
            });
        });
        
        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const errors = [];
            
            requiredFields.forEach(field => {
                if (field.value.trim() === '') {
                    isValid = false;
                    field.classList.add('border-red-300');
                    
                    const label = field.previousElementSibling?.textContent || field.name;
                    errors.push(label);
                }
            });
            
            if (!isValid) {
                alert('Veuillez remplir tous les champs obligatoires:\n• ' + errors.join('\n• '));
                return false;
            }
              // Simulation de soumission
            alert('Formulaire soumis avec succès!\n\nVotre demande de légalisation a été enregistrée.\nUn numéro de référence vous sera communiqué par email.');
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
              updateFileList();
            updateFileCounter();
            updateFileInput();
            
            if (selectedFiles.length > 0) {
                showSuccess(`${selectedFiles.length} document(s) sélectionné(s) avec succès.`);
            }
        }
        
        function updateFileList() {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                fileItem.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${file.name}</div>
                            <div class="text-xs text-gray-500">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                fileList.appendChild(fileItem);
            });
        }
        
        function updateFileCounter() {
            const counter = document.getElementById('fileCounter');
            counter.textContent = `${selectedFiles.length}/${maxFiles} documents sélectionnés`;
        }
        
        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
            updateFileCounter();
            updateFileInput();
        };
        
        function updateFileInput() {
            const input = document.getElementById('documents');
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
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
        const uploadArea = document.querySelector('.border-dashed');
        
        if (uploadArea) {
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = Array.from(e.dataTransfer.files);
                processFiles(files);
            });
        }
    });
    </script>
</body>
</html>
