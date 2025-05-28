@extends('layouts.agent')

@section('title', 'Gestion des citoyens')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Gestion des citoyens</h1>
            <p class="text-blue-100 mt-1">Vue d'ensemble des citoyens et de leurs demandes</p>
        </div>
        <div class="flex items-center space-x-4">
            <button onclick="exportCitizens()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-download mr-2"></i>Exporter
            </button>
            <button onclick="refreshData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-sync-alt mr-2"></i>Actualiser
            </button>
        </div>
    </div>
@endsection

@section('content')
<div x-data="citizensApp()" x-init="init()" class="space-y-8">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total citoyens</p>
                    <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Citoyens actifs</p>
                    <p class="text-3xl font-bold">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Nouveaux ce mois</p>
                    <p class="text-3xl font-bold">{{ $stats['newToday'] ?? 0 }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nom, email, téléphone..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                    <option value="nom" {{ request('sort') === 'nom' ? 'selected' : '' }}>Nom</option>
                    <option value="requests_count" {{ request('sort') === 'requests_count' ? 'selected' : '' }}>Nombre de demandes</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des citoyens -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Liste des citoyens</h3>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $citizens->total() }} citoyens</span>
                    <button @click="toggleView()" class="text-gray-600 hover:text-gray-800">
                        <i :class="viewMode === 'table' ? 'fas fa-th-large' : 'fas fa-list'" class="text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Vue tableau -->
        <div x-show="viewMode === 'table'" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citoyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière activité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($citizens as $citizen)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-medium">
                                            {{ substr($citizen->nom, 0, 1) }}{{ substr($citizen->prenoms, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $citizen->nom }} {{ $citizen->prenoms }}</div>
                                        <div class="text-sm text-gray-500">Inscrit le {{ $citizen->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $citizen->email }}</div>
                                <div class="text-sm text-gray-500">{{ $citizen->telephone ?? 'Non renseigné' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $citizen->requests->count() }} total
                                    </span>
                                    @if($citizen->requests->where('status', 'pending')->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $citizen->requests->where('status', 'pending')->count() }} en attente
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($citizen->requests->count() > 0)
                                    {{ $citizen->requests->first()->created_at->diffForHumans() }}
                                @else
                                    Aucune activité
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($citizen->requests->whereIn('status', ['pending', 'processing'])->count() > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="viewCitizen({{ $citizen->id }})"
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </button>
                                    <a href="{{ route('agent.citizens.show', $citizen) }}"
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                        <i class="fas fa-info-circle mr-1"></i>Détails
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Aucun citoyen trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Vue cartes -->
        <div x-show="viewMode === 'cards'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($citizens as $citizen)
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-lg font-medium">
                                    {{ substr($citizen->nom, 0, 1) }}{{ substr($citizen->prenoms, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $citizen->nom }} {{ $citizen->prenoms }}</h3>
                                    <p class="text-sm text-gray-500">{{ $citizen->email }}</p>
                                </div>
                            </div>
                            @if($citizen->requests->whereIn('status', ['pending', 'processing'])->count() > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactif
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $citizen->requests->count() }}</div>
                                <div class="text-xs text-gray-500">Demandes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $citizen->requests->where('status', 'pending')->count() }}</div>
                                <div class="text-xs text-gray-500">En attente</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $citizen->requests->where('status', 'completed')->count() }}</div>
                                <div class="text-xs text-gray-500">Terminées</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Inscrit le {{ $citizen->created_at->format('d/m/Y') }}
                            </div>
                            <a href="{{ route('agent.citizens.show', $citizen) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                Voir détails
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center py-12">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucun citoyen trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($citizens->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $citizens->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de détails rapide -->
<div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="modalData.title"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div x-html="modalData.content"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function citizensApp() {
    return {
        viewMode: 'table',
        showModal: false,
        modalData: {
            title: '',
            content: ''
        },

        init() {
            // Initialisation
        },

        toggleView() {
            this.viewMode = this.viewMode === 'table' ? 'cards' : 'table';
        }
    }
}

function viewCitizen(citizenId) {
    // Afficher les détails rapides du citoyen
    fetch(`/agent/citizens/${citizenId}/data`)
        .then(response => response.json())
        .then(data => {
            // Afficher dans un modal ou rediriger
            window.location.href = `/agent/citizens/${citizenId}`;
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function exportCitizens() {
    // Exporter la liste des citoyens
    window.open('/agent/citizens/export/csv', '_blank');
}

function refreshData() {
    // Actualiser les données
    window.location.reload();
}
</script>
@endpush
