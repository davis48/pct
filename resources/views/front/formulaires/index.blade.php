@extends('layouts.front.app')

@section('content')
<section class="py-8">
    <div class="container mx-auto px-4">
        <!-- En-tête moderne -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-3 flex items-center">
                <i class="fas fa-file-download mr-3 text-primary-600"></i>
                Formulaires à télécharger
            </h1>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-lg mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Information importante</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            Ces formulaires peuvent être téléchargés, pré-remplis et utilisés pour faire votre demande. 
                            Vous pouvez également faire votre demande directement en ligne sans télécharger ces formulaires.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres modernes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="search-formulaires" class="block text-sm font-medium text-gray-700 mb-2">Rechercher un formulaire</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="search-formulaires" placeholder="Rechercher un formulaire...">
                    </div>
                </div>
                <div>
                    <label for="filter-category" class="block text-sm font-medium text-gray-700 mb-2">Filtrer par catégorie</label>
                    <select class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="filter-category">
                        <option value="">Toutes les catégories</option>
                        <option value="État civil">État civil</option>
                        <option value="Résidence">Résidence</option>
                    </select>
                </div>
            </div>
        </div>
        </div>        <!-- Grille des formulaires moderne -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="formulaires-grid">
            @foreach($formulaires as $formulaire)
            <div class="formulaire-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" data-category="{{ $formulaire['category'] }}" data-title="{{ strtolower($formulaire['title']) }}">
                <div class="p-6">
                    <!-- Header avec icône -->
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="{{ $formulaire['icon'] }} text-primary-600 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $formulaire['title'] }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $formulaire['category'] }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-6 leading-relaxed">{{ $formulaire['description'] }}</p>
                    
                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('formulaires.show', $formulaire['id']) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-primary-300 text-primary-700 text-sm font-medium rounded-lg hover:bg-primary-50 transition-colors duration-200" 
                           target="_blank">
                            <i class="fas fa-eye mr-2 text-xs"></i>
                            Prévisualiser
                        </a>
                        <a href="{{ route('formulaires.download', $formulaire['id']) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200">
                            <i class="fas fa-download mr-2 text-xs"></i>
                            Télécharger PDF
                            </a>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('requests.create') }}?type={{ $formulaire['id'] }}" 
                               class="btn btn-success btn-sm w-100">
                                <i class="fas fa-plus me-1"></i>Faire une demande en ligne
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Section d'aide -->
        <div class="row mt-5">                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Section d'aide moderne -->
        <div class="mt-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-200">
            <div class="text-center mb-6">
                <i class="fas fa-question-circle text-3xl text-blue-600 mb-3"></i>
                <h3 class="text-xl font-semibold text-gray-900">Comment utiliser ces formulaires ?</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-download text-blue-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Option 1 : Télécharger et imprimer</h4>
                    </div>
                    <ol class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">1</span>
                            Téléchargez le formulaire PDF
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">2</span>
                            Imprimez-le et remplissez-le à la main
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">3</span>
                            Venez le déposer avec les pièces justificatives
                        </li>
                    </ol>
                </div>
                
                <div class="bg-white rounded-lg p-6 shadow-sm border-2 border-green-200">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-laptop text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Option 2 : Demande en ligne</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Recommandé
                            </span>
                        </div>
                    </div>
                    <ol class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">1</span>
                            Cliquez sur "Faire une demande en ligne"
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">2</span>
                            Remplissez le formulaire numérique
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs font-medium mr-2 mt-0.5">3</span>
                            Joignez vos documents et soumettez
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Message si aucun résultat -->
        <div id="no-results" class="text-center py-12 hidden">
            <div class="mb-6">
                <i class="fas fa-search text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun formulaire trouvé</h3>
            <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-formulaires');
    const categoryFilter = document.getElementById('filter-category');
    const formulaireCards = document.querySelectorAll('.formulaire-card');
    const noResults = document.getElementById('no-results');

    function filterFormulaires() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        let visibleCards = 0;

        formulaireCards.forEach(card => {
            const title = card.dataset.title;
            const category = card.dataset.category;
            
            const matchesSearch = title.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                card.classList.remove('hidden');
                card.style.display = 'block';
                visibleCards++;
            } else {
                card.classList.add('hidden');
                card.style.display = 'none';
            }
        });
        
        // Afficher/masquer le message "aucun résultat"
        if (visibleCards === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', filterFormulaires);
    categoryFilter.addEventListener('change', filterFormulaires);
    
    // Animation d'entrée moderne
    formulaireCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
