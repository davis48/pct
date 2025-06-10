@extends('layouts.app')

@section('title', 'Certificat de Mariage - Formulaire Interactif')

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
                        <i class="fas fa-heart text-red-500 mr-2"></i>
                        Certificat de Mariage
                    </h1>
                    <p class="text-gray-600">Formulaire interactif - Temps estimé: 5-10 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between text-sm">
                <span class="text-blue-600 font-medium">Étape 1 sur 3</span>
                <span class="text-gray-500">Informations personnelles</span>
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form id="marriageForm" action="{{ route('interactive-forms.generate', 'certificat-mariage') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Informations Époux -->
            <div class="bg-white rounded-lg shadow-lg p-6" id="step1">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-male text-blue-600 mr-3"></i>
                    Informations de l'Époux
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
                        <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession
                        </label>
                        <input type="text" id="profession" name="profession" 
                               value="{{ old('profession', $userData['profession'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Domicile
                        </label>
                        <input type="text" id="address" name="address" 
                               value="{{ old('address', $userData['address'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                        <label for="father_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du père
                        </label>
                        <input type="text" id="father_name" name="father_name" 
                               value="{{ old('father_name', $userData['father_name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la mère
                        </label>
                        <input type="text" id="mother_name" name="mother_name" 
                               value="{{ old('mother_name', $userData['mother_name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="nextStep()" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Informations Épouse -->
            <div class="bg-white rounded-lg shadow-lg p-6 hidden" id="step2">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-female text-pink-600 mr-3"></i>
                    Informations de l'Épouse
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="spouse_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom et Prénoms <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="spouse_name" name="spouse_name" 
                               value="{{ old('spouse_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="spouse_birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="spouse_birth_date" name="spouse_birth_date" 
                               value="{{ old('spouse_birth_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="spouse_birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu de naissance <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="spouse_birth_place" name="spouse_birth_place" 
                               value="{{ old('spouse_birth_place') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="spouse_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession
                        </label>
                        <input type="text" id="spouse_profession" name="spouse_profession" 
                               value="{{ old('spouse_profession') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="spouse_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Domicile
                        </label>
                        <input type="text" id="spouse_address" name="spouse_address" 
                               value="{{ old('spouse_address') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="spouse_nationality" class="block text-sm font-medium text-gray-700 mb-2">
                            Nationalité
                        </label>
                        <select id="spouse_nationality" name="spouse_nationality" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Ivoirienne" {{ old('spouse_nationality', 'Ivoirienne') == 'Ivoirienne' ? 'selected' : '' }}>Ivoirienne</option>
                            <option value="Française" {{ old('spouse_nationality') == 'Française' ? 'selected' : '' }}>Française</option>
                            <option value="Autre" {{ old('spouse_nationality') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="spouse_father_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du père
                        </label>
                        <input type="text" id="spouse_father_name" name="spouse_father_name" 
                               value="{{ old('spouse_father_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="spouse_mother_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la mère
                        </label>
                        <input type="text" id="spouse_mother_name" name="spouse_mother_name" 
                               value="{{ old('spouse_mother_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="previousStep()" class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Précédent
                    </button>
                    <button type="button" onclick="nextStep()" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Détails du Mariage -->
            <div class="bg-white rounded-lg shadow-lg p-6 hidden" id="step3">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-ring text-yellow-600 mr-3"></i>
                    Détails du Mariage
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="marriage_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de mariage <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="marriage_date" name="marriage_date" 
                               value="{{ old('marriage_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="marriage_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure de la cérémonie <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="marriage_time" name="marriage_time" 
                               value="{{ old('marriage_time', '10:00') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="matrimonial_regime" class="block text-sm font-medium text-gray-700 mb-2">
                            Régime matrimonial
                        </label>
                        <select id="matrimonial_regime" name="matrimonial_regime" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Communauté de biens réduite aux acquêts">Communauté de biens réduite aux acquêts</option>
                            <option value="Séparation de biens">Séparation de biens</option>
                            <option value="Communauté universelle">Communauté universelle</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="witness1_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Témoin de l'époux <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="witness1_name" name="witness1_name" 
                               value="{{ old('witness1_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="witness1_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession du témoin (époux)
                        </label>
                        <input type="text" id="witness1_profession" name="witness1_profession" 
                               value="{{ old('witness1_profession') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="witness2_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Témoin de l'épouse <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="witness2_name" name="witness2_name" 
                               value="{{ old('witness2_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="witness2_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession du témoin (épouse)
                        </label>
                        <input type="text" id="witness2_profession" name="witness2_profession" 
                               value="{{ old('witness2_profession') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="marginal_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Mentions marginales (optionnel)
                        </label>
                        <textarea id="marginal_notes" name="marginal_notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Mentions particulières...">{{ old('marginal_notes') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="previousStep()" class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Précédent
                    </button>
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i> Générer le document
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

function updateProgressBar() {
    const progress = (currentStep / totalSteps) * 100;
    document.querySelector('.bg-blue-600').style.width = progress + '%';
    
    // Update step info
    const stepInfo = document.querySelector('.flex.items-center.justify-between.text-sm');
    stepInfo.children[0].textContent = `Étape ${currentStep} sur ${totalSteps}`;
    
    const stepLabels = [
        'Informations de l\'époux',
        'Informations de l\'épouse', 
        'Détails du mariage'
    ];
    stepInfo.children[1].textContent = stepLabels[currentStep - 1];
}

function nextStep() {
    if (currentStep < totalSteps) {
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep++;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');
        updateProgressBar();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function previousStep() {
    if (currentStep > 1) {
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');
        updateProgressBar();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Initialize
updateProgressBar();
</script>

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
