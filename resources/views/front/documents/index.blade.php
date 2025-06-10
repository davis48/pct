@extends('layouts.front.app')
@section('title', 'Mes Documents - PCT UVCI')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- En-tête moderne -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold gradient-text mb-3 flex items-center">
                <i class="fas fa-folder-open mr-3 text-primary-600"></i>
                Mes documents
            </h1>
            <p class="text-gray-600">Consultez et téléchargez vos documents officiels approuvés</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 animate-fade-in">
            <div class="flex items-center text-green-800">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 animate-fade-in">
            <div class="flex items-center text-red-800">
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Documents Grid -->
        @if($documents && count($documents) > 0)
        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">
            @foreach($documents as $document)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden transform hover:-translate-y-1 transition-all duration-300 group">
                <div class="p-6">
                    <!-- Header du document -->
                    <div class="flex items-start mb-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-file-alt text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $document->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $document->category ?? 'Document officiel' }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                        {{ Str::limit($document->description ?? 'Document officiel généré automatiquement', 100) }}
                    </p>

                    <!-- Métadonnées -->
                    <div class="space-y-2 mb-6">
                        @if($document->created_at)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span>Généré le {{ $document->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        @endif
                        
                        @if($document->file_path)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                            <span>Format PDF</span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('documents.show', $document->id) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-all duration-200 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Détails
                        </a>
                        
                        @if($document->file_path)
                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                           target="_blank"
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition-all duration-200 text-sm">
                            <i class="fas fa-download mr-2"></i>Télécharger
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Badge de statut -->
                <div class="px-6 pb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Disponible
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($documents, 'links'))
        <div class="mt-8">
            {{ $documents->links() }}
        </div>
        @endif

        @else
        <!-- État vide -->
        <div class="text-center py-16">
            <div class="mb-6">
                <i class="fas fa-folder-open text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Aucun document disponible</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Vous n'avez pas encore de documents approuvés. Faites une demande pour obtenir vos documents officiels.
            </p>
            <a href="{{ route('requests.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-plus-circle mr-2"></i>
                Faire une nouvelle demande
            </a>
        </div>
        @endif

        <!-- Section d'aide -->
        <div class="mt-12 bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-lightbulb mr-2"></i>Conseils d'utilisation
            </h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-blue-800">
                <div class="flex items-start">
                    <i class="fas fa-download mr-2 text-blue-600 mt-1"></i>
                    <div>
                        <strong>Téléchargement :</strong><br>
                        Cliquez sur "Télécharger" pour sauvegarder le PDF sur votre appareil
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-shield-alt mr-2 text-blue-600 mt-1"></i>
                    <div>
                        <strong>Sécurité :</strong><br>
                        Tous vos documents sont cryptés et sécurisés
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
