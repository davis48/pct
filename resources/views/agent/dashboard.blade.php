@extends('layouts.agent')

@section('title', 'Tableau de bord')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Tableau de bord Agent</h1>
            <p class="text-blue-100 mt-1">Bienvenue, {{ auth()->user()->nom }} {{ auth()->user()->prenoms }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 text-white">
                <i class="fas fa-clock mr-2"></i>
                <span id="current-time"></span>
            </div>
            <button onclick="refreshDashboard()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-sync-alt mr-2"></i>Actualiser
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8" x-data="{
    statusFilter: '',
    viewMode: 'table',
    notifications: [],
    filterRequests() {
        // Filtrer les demandes selon le statut
        const rows = document.querySelectorAll('#requests-table-body tr');
        rows.forEach(row => {
            if (this.statusFilter === '' || row.dataset.status === this.statusFilter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    },
    toggleView() {
        this.viewMode = this.viewMode === 'table' ? 'cards' : 'table';
    }
}">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Demandes en attente -->
        <a href="{{ route('agent.requests.pending') }}" class="bg-gradient-to-br from-orange-400 to-red-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Demandes en attente</p>
                    <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-orange-100 text-xs mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        À traiter rapidement
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Demandes en cours -->
        <a href="{{ route('agent.requests.in-progress') }}" class="bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Demandes en cours</p>
                    <p class="text-3xl font-bold">{{ $stats['in_progress'] ?? 0 }}</p>
                    <p class="text-cyan-100 text-xs mt-1">
                        <i class="fas fa-spinner mr-1"></i>
                        En traitement
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-spinner text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Mes assignations -->
        <a href="{{ route('agent.requests.my-assignments') }}" class="bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Mes assignations</p>
                    <p class="text-3xl font-bold">{{ $stats['assigned'] ?? 0 }}</p>
                    <p class="text-blue-100 text-xs mt-1">
                        <i class="fas fa-user-check mr-1"></i>
                        Assignées à moi
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Complétées aujourd'hui -->
        <a href="{{ route('agent.requests.my-completed') }}" class="bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Complétées aujourd'hui</p>
                    <p class="text-3xl font-bold">{{ $stats['completed_today'] ?? 0 }}</p>
                    <p class="text-green-100 text-xs mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        Excellent travail !
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Total ce mois -->
        <a href="{{ route('agent.statistics') }}" class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total ce mois</p>
                    <p class="text-3xl font-bold">{{ $stats['monthly_total'] ?? 0 }}</p>
                    <p class="text-purple-100 text-xs mt-1">
                        <i class="fas fa-chart-line mr-1"></i>
                        Ma performance mensuelle
                    </p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <form action="{{ route('agent.assign-next') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-forward mr-2"></i>
                    Prendre la prochaine demande
                </button>
            </form>
            <a href="{{ route('agent.requests.index') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 text-center">
                <i class="fas fa-list mr-2"></i>
                Voir toutes les demandes
            </a>
            <button onclick="generateReport()" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-chart-bar mr-2"></i>
                Générer un rapport
            </button>
        </div>
    </div>

    <!-- Demandes récentes -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Demandes récentes</h3>
                <div class="flex items-center space-x-4">
                    <select x-model="statusFilter" @change="filterRequests()" class="text-sm border rounded-lg px-3 py-1">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="processing">En cours</option>
                        <option value="completed">Terminé</option>
                    </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="requests-table-body">
                    @forelse($pendingRequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200" data-status="{{ $request->status }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->reference_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->document ? $request->document->title : 'N/A' }}</div>
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
                                    @if($request->status === 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'processing' || $request->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($request->status === 'en_attente') En attente
                                    @elseif($request->status === 'processing' || $request->status === 'in_progress') En cours
                                    @elseif($request->status === 'completed') Terminé
                                    @else {{ $request->status }} @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('agent.requests.show', $request->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                <a href="{{ route('agent.requests.process', $request->id) }}" class="ml-3 text-green-600 hover:text-green-900">Traiter</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Aucune demande en attente pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Vue cartes -->
        <div x-show="viewMode === 'cards'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pendingRequests as $request)
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">{{ $request->document ? $request->document->title : 'N/A' }}</h4>
                            <p class="text-xs text-gray-500">Demandé le {{ $request->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $request->created_at->diffForHumans() }}
                            </div>
                            <a href="{{ route('agent.requests.process', $request) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                                Traiter
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucune demande en attente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    <div x-show="notifications.length > 0" class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Notifications récentes</h3>
        <div class="space-y-3">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bell text-blue-500"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm text-gray-900" x-text="notification.message"></p>
                        <p class="text-xs text-gray-500" x-text="notification.time"></p>
                    </div>
                    <button @click="removeNotification(notification.id)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('scripts')
<script>
    // Fonction pour mettre à jour l'heure
    function updateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('current-time').textContent = now.toLocaleDateString('fr-FR', options);
    }
    
    // Mettre à jour l'heure chaque seconde
    updateTime();
    setInterval(updateTime, 1000);
    
    // Actualisation automatique des statistiques toutes les 30 secondes
    
    function updateDashboardStats() {
        fetch('/agent/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les statistiques principales
                document.querySelector('.from-orange-400 .text-3xl.font-bold').textContent = data.pending;
                document.querySelector('.from-blue-400 .text-3xl.font-bold').textContent = data.assigned;
                document.querySelector('.from-green-400 .text-3xl.font-bold').textContent = data.completed_today;
                document.querySelector('.from-purple-400 .text-3xl.font-bold').textContent = data.monthly_total;
                
                // Mettre à jour les compteurs du menu latéral si présents
                const pendingBadge = document.querySelector('[data-stat="pendingRequests"]');
                if (pendingBadge) pendingBadge.textContent = data.pendingRequests > 9 ? '9+' : data.pendingRequests;
                
                const assignedBadge = document.querySelector('[data-stat="myAssignedRequests"]');
                if (assignedBadge) assignedBadge.textContent = data.myAssignedRequests;
                
                const completedBadge = document.querySelector('[data-stat="myCompletedRequests"]');
                if (completedBadge) completedBadge.textContent = data.myCompletedRequests;
            })
            .catch(error => console.error('Erreur lors de la mise à jour des statistiques:', error));
    }
    // Démarrer l'actualisation automatique
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initialisation des graphiques...');
        console.log('Initialisation du tableau de bord...');
        
        // Mettre à jour les statistiques toutes les 30 secondes
        setInterval(updateDashboardStats, 30000);
        
        console.log('Initialisation terminée');
    });
    
    // Fonction pour prendre la prochaine demande
    function assignNextRequest() {
        document.getElementById('assign-next-form').submit();
    }
    
    // Formulaire caché pour l'assignation de la prochaine demande
    document.body.insertAdjacentHTML('beforeend', `
        <form id="assign-next-form" action="{{ route('agent.assign-next') }}" method="POST" class="hidden">
            @csrf
        </form>
    `);
    
    // Fonction pour actualiser manuellement le tableau de bord
    function refreshDashboard() {
        updateDashboardStats();
        updateCharts();
        
        // Afficher une notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transition-opacity duration-500';
        toast.textContent = 'Tableau de bord actualisé';
        document.body.appendChild(toast);
        
        // Faire disparaître la notification après 3 secondes
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
    
    // Fonction pour générer un rapport
    function generateReport() {
        // À implémenter
        alert('Fonctionnalité de génération de rapport à implémenter');
    }
</script>
@endsection
