@extends('layouts.agent')

@section('title', 'Gestion des documents')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Gestion des documents</h1>
            <p class="text-blue-100 mt-1">Vue d'ensemble des demandes de documents et leur traitement</p>
        </div>
        <div class="flex items-center space-x-4">
            <button onclick="generateReport()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-chart-line mr-2"></i>Rapport
            </button>
            <button onclick="exportDocuments()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-download mr-2"></i>Exporter
            </button>
            <button onclick="refreshData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-sync-alt mr-2"></i>Actualiser
            </button>
        </div>
    </div>
@endsection

@section('content')
<div x-data="documentsApp()" x-init="init()" class="space-y-8">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Documents</p>
                    <p class="text-3xl font-bold">{{ $stats['documents'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">En attente</p>
                    <p class="text-3xl font-bold">{{ $stats['pendingRequests'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Mes assignées</p>
                    <p class="text-3xl font-bold">{{ $stats['myAssignedRequests'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Mes terminées</p>
                    <p class="text-3xl font-bold">{{ $stats['myCompletedRequests'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Demandes</p>
                    <p class="text-3xl font-bold">{{ $stats['requests'] }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-list-alt text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques temps réel -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Métriques en temps réel</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600" id="today-new">-</div>
                <div class="text-sm text-gray-500">Nouvelles demandes aujourd'hui</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600" id="today-completed">-</div>
                <div class="text-sm text-gray-500">Traitées aujourd'hui</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600" id="queue-size">-</div>
                <div class="text-sm text-gray-500">File d'attente</div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Référence, demandeur..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de document</label>
                <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous</option>
                    @foreach($documentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('document_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date à</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Demandes de documents</h3>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $requests->total() }} demandes</span>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pièces</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->reference_number }}</div>
                                <div class="text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    <div class="text-sm text-gray-900">{{ $request->document ? $request->document->title : 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-medium">
                                            {{ substr($request->user->nom, 0, 1) }}{{ substr($request->user->prenoms, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->user->nom }} {{ $request->user->prenoms }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                    @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <span class="w-2 h-2 rounded-full mr-1
                                        @if($request->status === 'pending') bg-yellow-400
                                        @elseif($request->status === 'processing') bg-blue-400
                                        @elseif($request->status === 'completed') bg-green-400
                                        @elseif($request->status === 'rejected') bg-red-400
                                        @else bg-gray-400 @endif"></span>
                                    @if($request->status === 'pending') En attente
                                    @elseif($request->status === 'processing') En cours
                                    @elseif($request->status === 'completed') Terminé
                                    @elseif($request->status === 'rejected') Rejeté
                                    @else {{ $request->status }} @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($request->agent)
                                    {{ $request->agent->nom }} {{ $request->agent->prenoms }}
                                @else
                                    <span class="text-gray-400">Non assigné</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $request->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->attachments && count($request->attachments) > 0)
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-paperclip text-gray-400"></i>
                                        <span class="text-sm text-gray-600">{{ count($request->attachments) }}</span>
                                        <button onclick="showAttachments({{ $request->id }})" class="text-blue-600 hover:text-blue-800 text-xs">
                                            voir
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Aucune</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('agent.documents.show', $request) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </a>
                                    @if(in_array($request->status, ['pending', 'processing']))
                                        <a href="{{ route('agent.requests.process', $request) }}"
                                           class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Traiter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Aucune demande trouvée</p>
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
                @forelse($requests as $request)
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr($request->user->nom, 0, 1) }}{{ substr($request->user->prenoms, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $request->user->nom }} {{ $request->user->prenoms }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->reference_number }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($request->status === 'completed') bg-green-100 text-green-800
                                @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($request->status === 'pending') En attente
                                @elseif($request->status === 'processing') En cours
                                @elseif($request->status === 'completed') Terminé
                                @elseif($request->status === 'rejected') Rejeté
                                @else {{ $request->status }} @endif
                            </span>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">{{ $request->document ? $request->document->title : 'N/A' }}</h4>
                            <p class="text-xs text-gray-500">Demandé le {{ $request->created_at->format('d/m/Y à H:i') }}</p>
                            @if($request->attachments && count($request->attachments) > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-paperclip mr-1"></i>
                                    {{ count($request->attachments) }} pièce(s) jointe(s)
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $request->created_at->diffForHumans() }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('agent.documents.show', $request) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                    Voir
                                </a>
                                @if(in_array($request->status, ['pending', 'processing']))
                                    <a href="{{ route('agent.requests.process', $request) }}"
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                        Traiter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucune demande trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal des pièces jointes -->
<div x-show="showAttachmentsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showAttachmentsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div x-show="showAttachmentsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Pièces jointes</h3>
                    <button @click="showAttachmentsModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="attachments-content">
                    <!-- Contenu des pièces jointes chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function documentsApp() {
    return {
        viewMode: 'table',
        showAttachmentsModal: false,

        init() {
            this.loadMetrics();
            setInterval(() => this.loadMetrics(), 30000); // Actualisation toutes les 30 secondes
        },

        toggleView() {
            this.viewMode = this.viewMode === 'table' ? 'cards' : 'table';
        },

        loadMetrics() {
            fetch('/agent/documents/metrics/realtime')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('today-new').textContent = data.today.new_requests;
                    document.getElementById('today-completed').textContent = data.today.completed;
                    document.getElementById('queue-size').textContent = data.queue.pending + data.queue.processing;
                })
                .catch(error => console.error('Erreur lors du chargement des métriques:', error));
        }
    }
}

function showAttachments(requestId) {
    // Afficher les pièces jointes d'une demande
    const modal = document.querySelector('[x-data="documentsApp()"]').__x.$data;
    modal.showAttachmentsModal = true;

    fetch(`/agent/documents/${requestId}`)
        .then(response => response.text())
        .then(html => {
            // Extraire les pièces jointes de la réponse HTML
            document.getElementById('attachments-content').innerHTML = '<p>Chargement des pièces jointes...</p>';
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('attachments-content').innerHTML = '<p class="text-red-500">Erreur lors du chargement</p>';
        });
}

function exportDocuments() {
    // Exporter les demandes de documents
    const params = new URLSearchParams(window.location.search);
    const exportUrl = '/agent/documents/export/csv?' + params.toString();
    window.open(exportUrl, '_blank');
}

function generateReport() {
    // Générer un rapport des documents
    window.open('/agent/statistics/generate-report?type=documents', '_blank');
}

function refreshData() {
    // Actualiser les données
    window.location.reload();
}
</script>
@endpush
