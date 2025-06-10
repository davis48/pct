@extends('layouts.front.app')
@section('title', 'Nouvelle Demande - PCT UVCI')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header avec animation -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-lg">
                    <i class="fas fa-file-plus text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold gradient-text mb-4">Nouvelle Demande</h1>
                <p class="text-gray-600 text-lg">Remplissez le formulaire ci-dessous pour soumettre votre demande de document</p>
            </div>

            <!-- Messages d'alerte -->
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 animate-fade-in">
                <div class="flex items-center text-green-800">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 animate-fade-in">
                <div class="flex items-start text-red-800">
                    <i class="fas fa-exclamation-circle mr-3 text-xl mt-1"></i>
                    <div>
                        <h4 class="font-medium mb-2">Erreurs détectées :</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Formulaire moderne -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <form id="requestForm" method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Type de document -->
                        <div class="group">
                            <label for="documentType" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>Type de document
                            </label>
                            <select 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-gray-50 hover:bg-white" 
                                id="documentType" 
                                name="type" 
                                required
                            >
                                <option value="" selected disabled>Choisissez un type de document</option>
                                <option value="attestation">📄 Attestation de domicile</option>
                                <option value="legalisation">✅ Légalisation de document</option>
                                <option value="mariage">💒 Certificat de mariage</option>
                                <option value="extrait-acte">📋 Extrait d'acte de naissance</option>
                                <option value="certificat">📜 Certificat de célibat</option>
                            </select>
                        </div>

                        <!-- Document requis -->
                        <div class="group">
                            <label for="document_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-folder-open mr-2 text-green-500"></i>Document spécifique (optionnel)
                            </label>
                            <select 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 bg-gray-50 hover:bg-white" 
                                id="document_id" 
                                name="document_id"
                            >
                                <option value="" selected>Aucun document spécifique</option>
                                @foreach($documents as $document)
                                    <option value="{{ $document->id }}">{{ $document->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="group">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-edit mr-2 text-purple-500"></i>Description de votre demande
                            </label>
                            <textarea 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-gray-50 hover:bg-white resize-none" 
                                id="description" 
                                name="description" 
                                rows="4" 
                                placeholder="Décrivez précisément votre demande et toute information utile..."
                                required
                            ></textarea>
                        </div>

                        <!-- Pièces jointes avec drag & drop -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-paperclip mr-2 text-orange-500"></i>Pièces jointes
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors duration-300 bg-gray-50 hover:bg-blue-50">
                                <input type="file" class="hidden" name="attachments[]" multiple id="file-input" accept=".pdf,.jpg,.jpeg,.png">
                                <label for="file-input" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 font-medium mb-2">Cliquez pour choisir vos fichiers</p>
                                    <p class="text-sm text-gray-500">ou glissez-déposez vos fichiers ici</p>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Formats acceptés : PDF, JPG, PNG • Taille max : 10 MB par fichier • Maximum 5 fichiers
                            </p>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button 
                                type="button" 
                                onclick="window.history.back()" 
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>Retour
                            </button>
                            <button 
                                type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Soumettre la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section d'aide -->
            <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                    <i class="fas fa-question-circle mr-2"></i>Besoin d'aide ?
                </h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-blue-800">
                    <div class="flex items-start">
                        <i class="fas fa-clock mr-2 text-blue-600 mt-1"></i>
                        <div>
                            <strong>Délai de traitement :</strong><br>
                            2 à 5 jours ouvrables selon le type de document
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-phone mr-2 text-blue-600 mt-1"></i>
                        <div>
                            <strong>Support :</strong><br>
                            +225 XX XX XX XX (8h-18h)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des champs au focus
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-105');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-105');
        });
    });

    // Validation en temps réel
    const form = document.getElementById('requestForm');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection
    </section>
@endsection
