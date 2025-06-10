@extends('layouts.app')

@section('title', 'Certificat de Célibat - Formulaire Interactif')

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
                        <i class="fas fa-user text-blue-500 mr-2"></i>
                        Certificat de Célibat
                    </h1>
                    <p class="text-gray-600">Formulaire interactif - Temps estimé: 3-5 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('interactive-forms.generate', 'certificat-celibat') }}" method="POST">
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
                            Profession <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="profession" name="profession" 
                               value="{{ old('profession', $userData['profession'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="address" name="address" 
                               value="{{ old('address', $userData['address'] ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
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
                
                <div class="mt-8 flex justify-between">
                    <a href="{{ route('interactive-forms.index') }}" 
                       class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i> Générer le certificat
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
