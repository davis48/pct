@extends('layouts.app')

@section('title', 'Extrait de Naissance - Formulaire Interactif')

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
                        <i class="fas fa-baby text-pink-500 mr-2"></i>
                        Extrait de Naissance
                    </h1>
                    <p class="text-gray-600">Formulaire interactif - Temps estimé: 3-5 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('interactive-forms.generate', 'extrait-naissance') }}" method="POST">
            @csrf

            <!-- Section 1: Informations personnelles -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
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
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexe <span class="text-red-500">*</span>
                        </label>
                        <select id="gender" name="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Sélectionner...</option>
                            <option value="Masculin" {{ old('gender') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                            <option value="Féminin" {{ old('gender') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                        </select>
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
                        <label for="birth_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure de naissance
                        </label>
                        <input type="time" id="birth_time" name="birth_time"
                               value="{{ old('birth_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                </div>
            </div>

            <!-- Section 2: Filiation -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-users text-green-600 mr-3"></i>
                    Filiation
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du père <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="father_name" name="father_name"
                               value="{{ old('father_name', $userData['father_name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la mère <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="mother_name" name="mother_name"
                               value="{{ old('mother_name', $userData['mother_name'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="father_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession du père
                        </label>
                        <input type="text" id="father_profession" name="father_profession"
                               value="{{ old('father_profession') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="mother_profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession de la mère
                        </label>
                        <input type="text" id="mother_profession" name="mother_profession"
                               value="{{ old('mother_profession') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Informations d'enregistrement (NOUVEAUX CHAMPS) -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                    Informations d'Enregistrement
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="registry_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de registre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="registry_number" name="registry_number"
                               value="{{ old('registry_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: 2025/001/123"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Format : Année/Série/Numéro</p>
                    </div>

                    <div>
                        <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de déclaration <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="registration_date" name="registration_date"
                               value="{{ old('registration_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro d'acte <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="registration_number" name="registration_number"
                               value="{{ old('registration_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Numéro de l'acte si connu"
                               required>
                    </div>

                    <div>
                        <label for="declarant_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Déclarant
                        </label>
                        <input type="text" id="declarant_name" name="declarant_name"
                               value="{{ old('declarant_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nom de la personne qui a déclaré la naissance">
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between">
                    <a href="{{ route('interactive-forms.index') }}"
                       class="bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i> Générer l'extrait
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
