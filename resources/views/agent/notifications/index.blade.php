@extends('layouts.agent')

@section('title', 'Notifications')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Notifications</h1>
            <p class="text-blue-100 mt-1">Vos demandes en attente et en cours de traitement</p>
        </div>
        <div>
            <button onclick="markAllAsRead()" 
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="fas fa-check-double mr-2"></i>Tout marquer comme lu
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    @if($notifications->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <li class="p-4 hover:bg-gray-50 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Demande #{{ $notification->reference_number }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        @if($notification->status === 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        @elseif($notification->status === 'in_progress')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                En cours
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                                {{ $notification->user->nom }} {{ $notification->user->prenoms }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-file-alt flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                                {{ $notification->document->title ?? 'Document non spécifié' }}
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-clock flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                            <p>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex space-x-4">
                                @if(!$notification->is_read)
                                    <button onclick="markAsRead('{{ $notification->id }}')"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-check mr-2"></i>
                                        Marquer comme lu
                                    </button>
                                @endif
                                <a href="{{ route('agent.requests.process', $notification->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-tasks mr-2"></i>
                                    Traiter
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-12 text-center">
                <i class="fas fa-bell text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Aucune notification</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Vous n'avez aucune notification pour le moment.
                </p>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/agent/notifications/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              window.location.reload();
          }
      });
}

function markAllAsRead() {
    fetch('/agent/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              window.location.reload();
          }
      });
}

// Mettre à jour le compteur de notifications
function updateNotificationCount() {
    fetch('/agent/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        });
}

// Mettre à jour le compteur toutes les 30 secondes
setInterval(updateNotificationCount, 30000);
</script>
@endpush
@endsection
