@extends('layouts.agent')

@section('title', 'Demandes en cours')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">Demandes en cours</h1>
        <div class="flex space-x-3">
            <a href="{{ route('agent.dashboard') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-home -ml-1 mr-2"></i>
                Tableau de bord
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filtres et recherche -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <form action="{{ route('agent.requests.in-progress') }}" method="GET" class="flex space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Recherche</label>
                    <input type="text" name="search" id="search"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Rechercher par référence, nom, email..."
                           value="{{ request('search') }}">
                </div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Demandes en cours ({{ $requests->total() }})
            </h3>
        </div>
        <div class="border-t border-gray-200 divide-y divide-gray-200">
            @forelse($requests as $request)
                <div class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    En cours
                                </div>
                                <p class="ml-2 text-sm font-medium text-gray-900 truncate">
                                    {{ $request->reference_number }}
                                </p>
                            </div>
                            <p class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="truncate">{{ $request->document->title ?? 'Document non spécifié' }}</span>
                            </p>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400"></i>
                                        <span>{{ $request->user->nom }} {{ $request->user->prenoms }}</span>
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                        <i class="fas fa-user-tie flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400"></i>
                                        <span>Assignée à: {{ $request->assignedAgent->nom ?? 'Non assignée' }} {{ $request->assignedAgent->prenoms ?? '' }}</span>
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <i class="fas fa-clock flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400"></i>
                                    <p>
                                        Mise à jour <time datetime="{{ $request->updated_at->toIso8601String() }}">{{ $request->updated_at->diffForHumans() }}</time>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5 flex justify-end space-x-2">
                            <a href="{{ route('agent.requests.show', $request->id) }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-eye mr-2"></i>
                                Détails
                            </a>
                            <a href="{{ route('agent.requests.process', $request->id) }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-check-circle mr-2"></i>
                                Traiter
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-info-circle text-xl mb-2"></i>
                        <p>Aucune demande en cours pour le moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
