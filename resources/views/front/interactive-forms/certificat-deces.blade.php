@extends('layouts.app')

@section('title', 'Certificat de Décès - Formulaire Interactif')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <a href="{{ route('interactive-forms.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-heart-broken text-red-500 mr-2"></i>
                        Certificat de Décès
                    </h1>
                    <p class="text-gray-600">Formulaire interactif - Temps estimé: 5-8 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('interactive-forms.generate', 'certificat-deces') }}" method="POST">
            @csrf
            
            <!-- Informations du Défunt -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user-times text-red-600 mr-3"></i>
                    Informations sur le Défunt
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="deceased_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom(s) du défunt <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="deceased_last_name" name="deceased_last_name" 
                               value="{{ old('deceased_last_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="deceased_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom(s) du défunt <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="deceased_first_name" name="deceased_first_name" 
                               value="{{ old('deceased_first_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="deceased_birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance du défunt <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="deceased_birth_date" name="deceased_birth_date" 
                               value="{{ old('deceased_birth_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="deceased_birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu de naissance du défunt <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="deceased_birth_place" name="deceased_birth_place" 
                               value="{{ old('deceased_birth_place') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="death_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date du décès <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="death_date" name="death_date" 
                               value="{{ old('death_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="death_place" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu du décès <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="death_place" name="death_place" 
                               value="{{ old('death_place') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>
            </div>

            <!-- Informations du Déclarant -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user-circle text-blue-600 mr-3"></i>
                    Informations du Déclarant
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="declarant_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom et Prénoms du déclarant <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="declarant_name" name="declarant_name" 
                               value="{{ old('declarant_name', $userData['name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="declarant_birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance du déclarant <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="declarant_birth_date" name="declarant_birth_date" 
                               value="{{ old('declarant_birth_date', $userData['date_naissance'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="declarant_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession du déclarant
                        </label>
                        <input type="text" id="declarant_profession" name="declarant_profession" 
                               value="{{ old('declarant_profession', $userData['profession'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="declarant_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Domicile du déclarant <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="declarant_address" name="declarant_address" 
                               value="{{ old('declarant_address', $userData['address'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="relationship_to_deceased" class="block text-sm font-medium text-gray-700 mb-2">
                            Lien avec le défunt <span class="text-red-500">*</span>
                        </label>
                        <select id="relationship_to_deceased" name="relationship_to_deceased" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Sélectionnez le lien de parenté</option>
                            <option value="Époux/Épouse" {{ old('relationship_to_deceased') == 'Époux/Épouse' ? 'selected' : '' }}>Époux/Épouse</option>
                            <option value="Fils/Fille" {{ old('relationship_to_deceased') == 'Fils/Fille' ? 'selected' : '' }}>Fils/Fille</option>
                            <option value="Père/Mère" {{ old('relationship_to_deceased') == 'Père/Mère' ? 'selected' : '' }}>Père/Mère</option>
                            <option value="Frère/Sœur" {{ old('relationship_to_deceased') == 'Frère/Sœur' ? 'selected' : '' }}>Frère/Sœur</option>
                            <option value="Petit-fils/Petite-fille" {{ old('relationship_to_deceased') == 'Petit-fils/Petite-fille' ? 'selected' : '' }}>Petit-fils/Petite-fille</option>
                            <option value="Neveu/Nièce" {{ old('relationship_to_deceased') == 'Neveu/Nièce' ? 'selected' : '' }}>Neveu/Nièce</option>
                            <option value="Ami(e)" {{ old('relationship_to_deceased') == 'Ami(e)' ? 'selected' : '' }}>Ami(e)</option>
                            <option value="Autre" {{ old('relationship_to_deceased') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    Informations Complémentaires
                </h2>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif de la demande <span class="text-red-500">*</span>
                        </label>
                        <select id="purpose" name="purpose" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Sélectionnez le motif</option>
                            <option value="Succession" {{ old('purpose') == 'Succession' ? 'selected' : '' }}>Succession</option>
                            <option value="Démarches bancaires" {{ old('purpose') == 'Démarches bancaires' ? 'selected' : '' }}>Démarches bancaires</option>
                            <option value="Assurance" {{ old('purpose') == 'Assurance' ? 'selected' : '' }}>Assurance</option>
                            <option value="Pension" {{ old('purpose') == 'Pension' ? 'selected' : '' }}>Pension</option>
                            <option value="Autre" {{ old('purpose') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Remarques ou informations complémentaires
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Toute information complémentaire utile...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Déclaration et soumission -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="mb-6">
                    <div class="flex items-start">
                        <input type="checkbox" id="declaration" name="declaration" 
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                               required>
                        <label for="declaration" class="ml-3 text-sm text-gray-700">
                            Je déclare sur l'honneur que les informations fournies sont exactes et complètes. Je suis conscient(e) que toute fausse déclaration peut entraîner des poursuites judiciaires. <span class="text-red-500">*</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" name="action" value="preview"
                            class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser le document
                    </button>
                    
                    <button type="submit" name="action" value="generate"
                            class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 font-medium transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Générer le document
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const deathDate = document.getElementById('death_date');
    const birthDate = document.getElementById('deceased_birth_date');
    
    function validateDates() {
        if (birthDate.value && deathDate.value) {
            const birth = new Date(birthDate.value);
            const death = new Date(deathDate.value);
            
            if (death <= birth) {
                deathDate.setCustomValidity('La date de décès doit être postérieure à la date de naissance');
            } else {
                deathDate.setCustomValidity('');
            }
        }
    }
    
    birthDate.addEventListener('change', validateDates);
    deathDate.addEventListener('change', validateDates);
    
    // Validation de la date de décès (ne peut pas être dans le futur)
    deathDate.addEventListener('change', function() {
        const today = new Date();
        const selectedDate = new Date(this.value);
        
        if (selectedDate > today) {
            this.setCustomValidity('La date de décès ne peut pas être dans le futur');
        } else {
            this.setCustomValidity('');
        }
        validateDates();
    });
});
</script>
@endpush
@endsection
