@extends('layouts.agent')

@section('title', 'Demandes en attente')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">Demandes en attente</h1>
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
            <form action="{{ route('agent.requests.pending') }}" method="GET" class="flex space-x-4">
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
                Demandes en attente ({{ $requests->total() }})
            </h3>
            <div>
                <a href="{{ route('agent.assign-next') }}" 
                   onclick="event.preventDefault(); document.getElementById('assign-next-form').submit();" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-hand-pointer -ml-1 mr-2"></i>
                    Prendre la prochaine demande
                </a>
                <form id="assign-next-form" action="{{ route('agent.assign-next') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
        <div class="border-t border-gray-200">
            @if($requests->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Référence
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Demandeur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de création
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $request->reference_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->document)
                                        <div class="text-sm text-gray-900">{{ $request->document->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->document->category }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">Document non spécifié</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->user->nom }} {{ $request->user->prenoms }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('agent.requests.show', $request) }}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye -ml-0.5 mr-1"></i>
                                        Voir
                                    </a>
                                    <form action="{{ route('agent.requests.assign', $request->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-hand-pointer -ml-0.5 mr-1"></i>
                                            Prendre
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande en attente</h3>
                    <p class="mt-1 text-sm text-gray-500">Toutes les demandes ont été assignées ou il n'y a pas de nouvelles demandes.</p>
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
