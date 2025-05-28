@extends('layouts.agent')

@section('title', 'Mes assignations')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Mes assignations</h1>
        <p class="text-blue-100 mt-1">Demandes qui vous sont assignées en attente de traitement</p>
    </div>
    <div>
        <a href="{{ route('agent.requests.index') }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux demandes
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtres et recherche -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <form action="{{ route('agent.requests.my-assignments') }}" method="GET" class="flex space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Recherche</label>
                    <input type="text" name="search" id="search"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Rechercher par référence, nom, email..."
                           value="{{ request('search') }}">
                </div>
                <div class="w-48">
                    <label for="status" class="sr-only">Statut</label>
                    <select name="status" id="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    </select>
                </div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-search -ml-1 mr-2"></i>
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Mes demandes assignées ({{ $requests->total() }})
            </h3>
        </div>
        <div class="border-t border-gray-200">
            @if($requests->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <li>
                            <a href="{{ route('agent.requests.show', $request->id) }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($request->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        En attente
                                                    </span>
                                                @elseif($request->status == 'in_progress')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        En cours
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="ml-4 text-sm font-medium text-indigo-600 truncate">
                                                {{ $request->reference }} - {{ $request->document->name ?? 'Document non spécifié' }}
                                            </p>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $request->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                                {{ $request->user->nom ?? '' }} {{ $request->user->prenoms ?? '' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-envelope flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                                {{ $request->user->email ?? '' }}
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-clock flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                                            <p>
                                                Assignée {{ $request->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Aucune demande assignée</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Vous n'avez actuellement aucune demande assignée en attente de traitement.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('agent.assign-next') }}" onclick="event.preventDefault(); document.getElementById('assign-next-form').submit();" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus -ml-1 mr-2"></i>
                            Prendre une demande
                        </a>
                        <form id="assign-next-form" action="{{ route('agent.assign-next') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection
