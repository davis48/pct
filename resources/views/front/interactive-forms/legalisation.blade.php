@extends('layouts.front')

@section('title', 'Légalisation de Document')

@section('content')
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

        <form action="{{ route('interactive-forms.generate', 'legalisation') }}" method="POST" class="space-y-8" id="legalisationForm">
            @csrf
            
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
                               value="{{ old('nom') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Votre nom et prénoms">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                        <input type="date" id="date_naissance" name="date_naissance" required 
                               value="{{ old('date_naissance') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance *</label>
                        <input type="text" id="lieu_naissance" name="lieu_naissance" required 
                               value="{{ old('lieu_naissance') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Ville ou commune de naissance">
                        @error('lieu_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profession *</label>
                        <input type="text" id="profession" name="profession" required 
                               value="{{ old('profession') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Votre profession">
                        @error('profession')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse complète *</label>
                        <textarea id="adresse" name="adresse" required rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Votre adresse complète">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="numero_cni" class="block text-sm font-medium text-gray-700 mb-2">Numéro CNI *</label>
                        <input type="text" id="numero_cni" name="numero_cni" required 
                               value="{{ old('numero_cni') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Numéro de votre CNI">
                        @error('numero_cni')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            <option value="certificat_naissance" {{ old('document_type') == 'certificat_naissance' ? 'selected' : '' }}>Certificat de naissance</option>
                            <option value="certificat_mariage" {{ old('document_type') == 'certificat_mariage' ? 'selected' : '' }}>Certificat de mariage</option>
                            <option value="certificat_deces" {{ old('document_type') == 'certificat_deces' ? 'selected' : '' }}>Certificat de décès</option>
                            <option value="diplome" {{ old('document_type') == 'diplome' ? 'selected' : '' }}>Diplôme</option>
                            <option value="attestation" {{ old('document_type') == 'attestation' ? 'selected' : '' }}>Attestation</option>
                            <option value="contrat" {{ old('document_type') == 'contrat' ? 'selected' : '' }}>Contrat</option>
                            <option value="autre" {{ old('document_type') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_date" class="block text-sm font-medium text-gray-700 mb-2">Date du document original *</label>
                        <input type="date" id="document_date" name="document_date" required 
                               value="{{ old('document_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        @error('document_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="issuing_authority" class="block text-sm font-medium text-gray-700 mb-2">Autorité émettrice *</label>
                        <input type="text" id="issuing_authority" name="issuing_authority" required 
                               value="{{ old('issuing_authority') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Ex: Mairie de Cocody, Ministère de l'Education">
                        @error('issuing_authority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro du document</label>
                        <input type="text" id="document_number" name="document_number" 
                               value="{{ old('document_number') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Numéro de référence du document (si applicable)">
                        @error('document_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="motif_demande" class="block text-sm font-medium text-gray-700 mb-2">Motif de la demande *</label>
                        <select id="motif_demande" name="motif_demande" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            <option value="">Sélectionnez le motif</option>
                            <option value="usage_etranger" {{ old('motif_demande') == 'usage_etranger' ? 'selected' : '' }}>Usage à l'étranger</option>
                            <option value="procedure_administrative" {{ old('motif_demande') == 'procedure_administrative' ? 'selected' : '' }}>Procédure administrative</option>
                            <option value="procedure_judiciaire" {{ old('motif_demande') == 'procedure_judiciaire' ? 'selected' : '' }}>Procédure judiciaire</option>
                            <option value="scolarite" {{ old('motif_demande') == 'scolarite' ? 'selected' : '' }}>Scolarité/Études</option>
                            <option value="emploi" {{ old('motif_demande') == 'emploi' ? 'selected' : '' }}>Emploi</option>
                            <option value="autre" {{ old('motif_demande') == 'autre' ? 'selected' : '' }}>Autre motif</option>
                        </select>
                        @error('motif_demande')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination du document</label>
                        <input type="text" id="destination" name="destination" 
                               value="{{ old('destination') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Pays ou institution de destination (si applicable)">
                        @error('destination')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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

                    <div class="flex items-center">
                        <input id="accept_terms" name="accept_terms" type="checkbox" required 
                               {{ old('accept_terms') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                            J'accepte les <a href="#" class="text-blue-600 hover:text-blue-500">conditions générales</a> et certifie l'exactitude des informations fournies *
                        </label>
                    </div>
                    @error('accept_terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <a href="{{ route('interactive-forms.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Générer la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('legalisationForm');
    const progressBar = document.querySelector('.bg-blue-600');
    
    // Simuler la progression
    let currentStep = 1;
    const totalSteps = 3;
    
    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Validation en temps réel
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
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
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (field.value.trim() === '') {
                isValid = false;
                field.classList.add('border-red-300');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
    });
});
</script>
@endsection
