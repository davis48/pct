@extends('layouts.agent')

@section('title', 'Demandes en attente')

@section('styles')
<style>
    /* Styles pour la compatibilité avec l'ancienne interface si nécessaire */
    .actions-column {
        min-width: 180px !important;
        width: 180px !important;
    }
    .actions-container {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 4px !important;
        flex-direction: column !important;
    }
    .action-button {
        flex-shrink: 0 !important;
        white-space: nowrap !important;
        width: 100% !important;
    }
    
    /* Responsive improvements */
    @media (max-width: 1023px) {
        .actions-column,
        .actions-container {
            all: unset !important;
        }
    }
</style>
@endsection

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
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search"
                               class="block w-full pl-10 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Rechercher par référence, nom, email..."
                               value="{{ request('search') }}">
                    </div>
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
        <!-- Version mobile responsive avec cartes -->
        <div class="block lg:hidden">
            @if($requests->count() > 0)
                <div class="space-y-4">
                    @foreach($requests as $request)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">#{{ $request->reference_number }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex flex-col space-y-1 text-right">
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                    @if($request->payment_required && $request->payment_status === 'paid')
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Payé
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Type de demande:</span>
                                    <p class="text-sm text-gray-900">{{ ucfirst($request->type) ?? 'Type non spécifié' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Document associé:</span>
                                    @if($request->document)
                                        <p class="text-sm text-gray-900">{{ $request->document->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $request->document->category }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Document non spécifié</p>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Demandeur:</span>
                                    <p class="text-sm text-gray-900">{{ $request->user->nom }} {{ $request->user->prenoms }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('agent.requests.show', $request) }}"
                                   class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </a>
                                
                                <form action="{{ route('agent.requests.assign', $request->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                        <i class="fas fa-hand-pointer mr-1"></i>Prendre
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
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

        <!-- Version desktop avec tableau -->
        <div class="hidden lg:block border-t border-gray-200 overflow-x-auto">
            @if($requests->count() > 0)
                <div class="min-w-full overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                    Référence
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                    Document & Demandeur
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">
                                    Statut & Paiement
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                    Date
                                </th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[180px]">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $request->reference_number }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="space-y-1">
                                            <!-- Document -->
                                            @if($request->document)
                                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($request->document->title, 25) }}</div>
                                                <div class="text-xs text-gray-500">{{ $request->document->category }}</div>
                                            @else
                                                <div class="text-sm text-gray-500">Document non spécifié</div>
                                            @endif
                                            <!-- Demandeur -->
                                            <div class="text-sm text-gray-700 border-t pt-1">
                                                {{ Str::limit($request->user->nom . ' ' . $request->user->prenoms, 25) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($request->user->email, 30) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="space-y-2">
                                            <!-- Statut principal -->
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                            <!-- Statut de paiement -->
                                            @if($request->payment_required)
                                                <div>
                                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                                        @if($request->payment_status === 'paid') bg-green-100 text-green-800
                                                        @elseif($request->payment_status === 'unpaid') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        @if($request->payment_status === 'paid') ✓ Payé
                                                        @elseif($request->payment_status === 'unpaid') ⏳ Non payé
                                                        @else {{ $request->payment_status }} @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs">{{ $request->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('agent.requests.show', $request) }}"
                                               class="inline-flex items-center justify-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>Voir
                                            </a>
                                            
                                            <!-- Bouton Prendre -->
                                            <form action="{{ route('agent.requests.assign', $request->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                                    <i class="fas fa-hand-pointer mr-1"></i>Prendre
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-center">
                    {{ $requests->links() }}
                </div>
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
</div>
@endsection
