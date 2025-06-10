@extends('layouts.front.app')

@section('title', 'Formulaires Interactifs')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Formulaires Interactifs
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Remplissez et générez vos documents officiels directement en ligne. 
                    Rapide, sécurisé et disponible 24h/24.
                </p>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Gain de Temps</h3>
                <p class="text-gray-600">Remplissez vos formulaires en quelques minutes seulement</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Sécurisé</h3>
                <p class="text-gray-600">Vos données sont protégées et chiffrées</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-download text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Téléchargement Instantané</h3>
                <p class="text-gray-600">Récupérez vos documents immédiatement</p>
            </div>
        </div>

        <!-- Forms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($availableForms as $formType => $formInfo)
            <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                            <i class="{{ $formInfo['icon'] }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $formInfo['title'] }}</h3>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $formInfo['estimated_time'] }}
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6">{{ $formInfo['description'] }}</p>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('interactive-forms.show', $formType) }}" 
                           class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Remplir en ligne
                        </a>
                        <a href="{{ route('documents.download-template', $formType) }}" 
                           class="bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Help Section -->
        <div class="mt-16 bg-blue-50 rounded-lg p-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                Besoin d'aide ?
            </h2>
            <p class="text-gray-600 mb-6">
                Notre équipe est là pour vous accompagner dans vos démarches administratives.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-phone mr-2"></i>
                    Nous contacter
                </a>
                <a href="#" class="bg-white text-blue-600 border border-blue-600 py-3 px-6 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                    <i class="fas fa-book mr-2"></i>
                    Guide d'utilisation
                </a>
            </div>
        </div>

        <!-- Legal Notice -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>
                <i class="fas fa-info-circle mr-1"></i>
                Les documents générés ont la même valeur légale que ceux obtenus en mairie.
                Conservez précieusement vos références de téléchargement.
            </p>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.grid > div {
    animation: fadeInUp 0.6s ease-out;
}

.grid > div:nth-child(2) {
    animation-delay: 0.1s;
}

.grid > div:nth-child(3) {
    animation-delay: 0.2s;
}

.grid > div:nth-child(4) {
    animation-delay: 0.3s;
}

.grid > div:nth-child(5) {
    animation-delay: 0.4s;
}

.grid > div:nth-child(6) {
    animation-delay: 0.5s;
}
</style>
@endsection
