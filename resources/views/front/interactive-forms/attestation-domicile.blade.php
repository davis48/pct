@extends('layouts.app')

@section('title', 'Attestation de Domicile - Formulaire Interactif')

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
                        <i class="fas fa-home text-green-500 mr-2"></i>
                        Attestation de Domicile
                    </h1>
                    <p class="text-gray-600">Formulaire interactif - Temps estimé: 3-5 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('interactive-forms.generate', 'attestation-domicile') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user-circle text-blue-600 mr-3"></i>
                    Informations Personnelles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom et Prénoms <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name', $userData['name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth" 
                               value="{{ old('date_of_birth', $userData['date_of_birth'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu de naissance <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="place_of_birth" name="place_of_birth" 
                               value="{{ old('place_of_birth', $userData['place_of_birth'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                            Nationalité <span class="text-red-500">*</span>
                        </label>
                        <select id="nationality" name="nationality" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="Ivoirienne" {{ old('nationality', $userData['nationality'] ?? 'Ivoirienne') == 'Ivoirienne' ? 'selected' : '' }}>Ivoirienne</option>
                            <option value="Française" {{ old('nationality') == 'Française' ? 'selected' : '' }}>Française</option>
                            <option value="Autre" {{ old('nationality') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession
                        </label>
                        <input type="text" id="profession" name="profession" 
                               value="{{ old('profession', $userData['profession'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de pièce d'identité
                        </label>
                        <input type="text" id="id_number" name="id_number" 
                               value="{{ old('id_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="CNI, Passeport, etc.">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mt-8 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-green-600 mr-3"></i>
                    Informations de Domicile
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse complète <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Rue, quartier, ville..."
                                  required>{{ old('address', $userData['address'] ?? '') }}</textarea>
                    </div>
                    
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                            Commune/District <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="district" name="district" 
                               value="{{ old('district', 'Abidjan') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="residence_duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Durée de résidence <span class="text-red-500">*</span>
                        </label>
                        <select id="residence_duration" name="residence_duration" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Sélectionner...</option>
                            <option value="Moins de 6 mois" {{ old('residence_duration') == 'Moins de 6 mois' ? 'selected' : '' }}>Moins de 6 mois</option>
                            <option value="6 mois à 1 an" {{ old('residence_duration') == '6 mois à 1 an' ? 'selected' : '' }}>6 mois à 1 an</option>
                            <option value="1 à 2 ans" {{ old('residence_duration') == '1 à 2 ans' ? 'selected' : '' }}>1 à 2 ans</option>
                            <option value="2 à 5 ans" {{ old('residence_duration') == '2 à 5 ans' ? 'selected' : '' }}>2 à 5 ans</option>
                            <option value="Plus de 5 ans" {{ old('residence_duration') == 'Plus de 5 ans' ? 'selected' : '' }}>Plus de 5 ans</option>
                            <option value="Depuis la naissance" {{ old('residence_duration') == 'Depuis la naissance' ? 'selected' : '' }}>Depuis la naissance</option>
                        </select>
                    </div>

                    <div>
                        <label for="housing_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de logement
                        </label>
                        <select id="housing_type" name="housing_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner...</option>
                            <option value="Propriétaire" {{ old('housing_type') == 'Propriétaire' ? 'selected' : '' }}>Propriétaire</option>
                            <option value="Locataire" {{ old('housing_type') == 'Locataire' ? 'selected' : '' }}>Locataire</option>
                            <option value="Hébergé" {{ old('housing_type') == 'Hébergé' ? 'selected' : '' }}>Hébergé</option>
                            <option value="Autre" {{ old('housing_type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif de la demande
                        </label>
                        <select id="purpose" name="purpose" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner...</option>
                            <option value="Inscription scolaire" {{ old('purpose') == 'Inscription scolaire' ? 'selected' : '' }}>Inscription scolaire</option>
                            <option value="Demande d'emploi" {{ old('purpose') == 'Demande d\'emploi' ? 'selected' : '' }}>Demande d'emploi</option>
                            <option value="Démarches bancaires" {{ old('purpose') == 'Démarches bancaires' ? 'selected' : '' }}>Démarches bancaires</option>
                            <option value="Demande de visa" {{ old('purpose') == 'Demande de visa' ? 'selected' : '' }}>Demande de visa</option>
                            <option value="Autre" {{ old('purpose') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">
                            Informations complémentaires
                        </label>
                        <textarea id="additional_info" name="additional_info" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Détails supplémentaires si nécessaire...">{{ old('additional_info') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-between">
                    <a href="{{ route('interactive-forms.index') }}" 
                       class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i> Générer l'attestation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
