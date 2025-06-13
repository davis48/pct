@php
use App\Models\CitizenRequest;

// Statistiques pour les badges - calcul direct pour éviter les dépendances
$pendingCount = CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)->count();
$inProgressCount = CitizenRequest::where('status', CitizenRequest::STATUS_IN_PROGRESS)->count();
$myAssignedCount = CitizenRequest::where('assigned_to', auth()->id())
                                ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                                ->count();
$myCompletedCount = CitizenRequest::where('processed_by', auth()->id())
                                 ->whereIn('status', [CitizenRequest::STATUS_APPROVED, CitizenRequest::STATUS_REJECTED])
                                 ->count();
$remindersCount = CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
                               ->where('created_at', '<=', now()->subDays(3))
                               ->count();
@endphp

<div class="sidebar-gradient flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center">
                <i class="fas fa-user-shield text-white text-xl"></i>
            </div>
            <div class="ml-3">
                <h2 class="text-lg font-bold text-white">Agent PCT</h2>
                <p class="text-xs text-indigo-200">Interface de gestion</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('agent.dashboard') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt h-6 w-6 shrink-0"></i>
                            Tableau de bord
                        </a>
                    </li>

                    <!-- Gestion des demandes -->
                    <li>
                        <a href="{{ route('agent.requests.index') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.requests.index') ? 'active' : '' }}">
                            <i class="fas fa-file-alt h-6 w-6 shrink-0"></i>
                            Toutes les demandes
                        </a>
                    </li>
                    
                    <!-- Demandes en attente -->
                    <li>
                        <a href="{{ route('agent.requests.pending') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.requests.pending') ? 'active' : '' }}">
                            <i class="fas fa-hourglass-half h-6 w-6 shrink-0"></i>
                            Demandes en attente
                            @if($pendingCount > 0)
                                <span id="badge-pending" class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center notification-badge">
                                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- Demandes en cours -->
                    <li>
                        <a href="{{ route('agent.requests.in-progress') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.requests.in-progress') ? 'active' : '' }}">
                            <i class="fas fa-spinner h-6 w-6 shrink-0"></i>
                            Demandes en cours
                            @if($inProgressCount > 0)
                                <span id="badge-in-progress" class="ml-auto bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center notification-badge">
                                    {{ $inProgressCount > 9 ? '9+' : $inProgressCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- Rappels -->
                    <li>
                        <a href="{{ route('agent.requests.reminders') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.requests.reminders') ? 'active' : '' }}">
                            <i class="fas fa-bell h-6 w-6 shrink-0"></i>
                            Rappels
                            @if($remindersCount > 0)
                                <span id="badge-reminders" class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center notification-badge">
                                    {{ $remindersCount > 9 ? '9+' : $remindersCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- Citoyens -->
                    <li>
                        <a href="{{ route('agent.citizens.index') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.citizens.*') ? 'active' : '' }}">
                            <i class="fas fa-users h-6 w-6 shrink-0"></i>
                            Citoyens
                        </a>
                    </li>

                    <!-- Documents -->
                    <li>
                        <a href="{{ route('agent.documents.index') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.documents.*') ? 'active' : '' }}">
                            <i class="fas fa-folder-open h-6 w-6 shrink-0"></i>
                            Documents
                        </a>
                    </li>

                    <!-- Statistiques -->
                    <li>
                        <a href="{{ route('agent.statistics') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white {{ request()->routeIs('agent.statistics') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar h-6 w-6 shrink-0"></i>
                            Statistiques
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Mes activités -->
            <li>
                <div class="text-xs font-semibold leading-6 text-indigo-200">Mes activités</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <a href="{{ route('agent.requests.my-assignments') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white">
                            <i class="fas fa-tasks h-6 w-6 shrink-0"></i>
                            Mes assignations
                            @if($myAssignedCount > 0)
                                <span id="badge-my-assignments" class="ml-auto bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $myAssignedCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agent.requests.my-completed') }}"
                           class="nav-item group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-white">
                            <i class="fas fa-check-circle h-6 w-6 shrink-0"></i>
                            Mes traitements
                            @if($myCompletedCount > 0)
                                <span id="badge-my-completed" class="ml-auto bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $myCompletedCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Actions rapides -->
            <li class="mt-auto">
                <div class="bg-white/10 rounded-lg p-4 space-y-3">
                    <h3 class="text-sm font-semibold text-white">Actions rapides</h3>
                    <div class="space-y-2">
                        <button onclick="assignNextRequest()"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-md px-3 py-2 text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Prendre une demande
                        </button>
                        <a href="{{ route('agent.requests.index', ['status' => 'en_attente']) }}"
                           class="w-full bg-secondary-600 hover:bg-secondary-700 text-white rounded-md px-3 py-2 text-sm font-medium transition-colors block text-center">
                            <i class="fas fa-clock mr-2"></i>Demandes urgentes
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</div>

<script>
function assignNextRequest() {
    // Créer un formulaire temporaire et le soumettre
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/agent/assign-next';
    
    // Ajouter le token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Ajouter le formulaire au document et le soumettre
    document.body.appendChild(form);
    form.submit();
}

// Fonction pour mettre à jour les badges de la sidebar
function updateSidebarBadges() {
    fetch('/agent/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Badge demandes en attente
            const pendingBadge = document.getElementById('badge-pending');
            if (pendingBadge) {
                if (data.pendingRequests > 0) {
                    pendingBadge.textContent = data.pendingRequests > 9 ? '9+' : data.pendingRequests;
                    pendingBadge.style.display = 'flex';
                } else {
                    pendingBadge.style.display = 'none';
                }
            }
            
            // Badge demandes en cours
            const inProgressBadge = document.getElementById('badge-in-progress');
            if (inProgressBadge) {
                if (data.inProgressRequests > 0) {
                    inProgressBadge.textContent = data.inProgressRequests > 9 ? '9+' : data.inProgressRequests;
                    inProgressBadge.style.display = 'flex';
                } else {
                    inProgressBadge.style.display = 'none';
                }
            }
            
            // Badge rappels
            const remindersBadge = document.getElementById('badge-reminders');
            if (remindersBadge) {
                if (data.remindersCount > 0) {
                    remindersBadge.textContent = data.remindersCount > 9 ? '9+' : data.remindersCount;
                    remindersBadge.style.display = 'flex';
                } else {
                    remindersBadge.style.display = 'none';
                }
            }
            
            // Badge mes assignations
            const myAssignmentsBadge = document.getElementById('badge-my-assignments');
            if (myAssignmentsBadge) {
                if (data.myAssignedRequests > 0) {
                    myAssignmentsBadge.textContent = data.myAssignedRequests;
                    myAssignmentsBadge.style.display = 'flex';
                } else {
                    myAssignmentsBadge.style.display = 'none';
                }
            }
            
            // Badge mes traitements
            const myCompletedBadge = document.getElementById('badge-my-completed');
            if (myCompletedBadge) {
                if (data.myCompletedRequests > 0) {
                    myCompletedBadge.textContent = data.myCompletedRequests;
                    myCompletedBadge.style.display = 'flex';
                } else {
                    myCompletedBadge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.log('Erreur lors de la mise à jour des badges:', error);
        });
}

// Mettre à jour les badges au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateSidebarBadges();
    
    // Mettre à jour les badges toutes les 30 secondes
    setInterval(updateSidebarBadges, 30000);
});

// Mettre à jour les badges lorsque la page devient visible (changement d'onglet)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateSidebarBadges();
    }
});
</script>
