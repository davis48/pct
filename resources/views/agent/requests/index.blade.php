@extends('layouts.agent')

@section('title', 'Gestion des demandes')

@section('header', 'Gestion des demandes')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total des demandes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-yellow-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-lg font-medium text-yellow-600">{{ $stats['pending'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-spinner text-2xl text-blue-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En cours</dt>
                            <dd class="text-lg font-medium text-blue-600">{{ $stats['in_progress'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Traitables</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['processable'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-2xl text-red-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Non payées</dt>
                            <dd class="text-lg font-medium text-red-600">{{ $stats['unpaid'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-4">
            <form action="{{ route('agent.requests.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Référence ou nom du citoyen">
                </div>

                <div class="w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetée</option>
                    </select>
                </div>

                <div class="flex items-end space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="assigned_to_me" id="assigned_to_me" value="1"
                               {{ request('assigned_to_me') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="assigned_to_me" class="ml-2 block text-sm text-gray-900">Mes demandes</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="processable_only" id="processable_only" value="1"
                               {{ request('processable_only') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="processable_only" class="ml-2 block text-sm text-gray-900">Traitables uniquement</label>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Liste des demandes ({{ $requests->total() }})
            </h3>
            <div>
                <a href="{{ route('agent.requests.pending') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus -ml-1 mr-2"></i>
                    Voir les demandes en attente
                </a>
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
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                        @if($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($request->status === 'pending') En attente
                                        @elseif($request->status === 'in_progress') En cours
                                        @elseif($request->status === 'approved') Approuvée
                                        @elseif($request->status === 'rejected') Rejetée
                                        @else {{ $request->status }} @endif
                                    </span>
                                    @if($request->payment_required)
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                            @if($request->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($request->payment_status === 'unpaid') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($request->payment_status === 'paid') ✓ Payé
                                            @elseif($request->payment_status === 'unpaid') ⏳ Non payé
                                            @else {{ $request->payment_status }} @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Document:</span>
                                    @if($request->document)
                                        <p class="text-sm text-gray-900">{{ $request->document->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $request->document->category }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Document non spécifié</p>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Citoyen:</span>
                                    <p class="text-sm text-gray-900">{{ $request->user->nom }} {{ $request->user->prenoms }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('agent.requests.show', $request) }}"
                                   class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </a>
                                
                                @if($request->status === 'pending' && $request->payment_status === 'paid')
                                    <a href="{{ route('agent.requests.process', $request) }}"
                                       class="inline-flex items-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                        <i class="fas fa-cogs mr-1"></i>Traiter
                                    </a>
                                @elseif($request->status === 'pending' && $request->payment_status !== 'paid')
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-yellow-700 bg-yellow-50">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @elseif($request->status === 'in_progress' && $request->assigned_to === Auth::id())
                                    <a href="{{ route('agent.requests.edit', $request) }}"
                                       class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-center">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p class="text-lg">Aucune demande trouvée</p>
                        <p class="text-sm mt-1">Essayez de modifier vos filtres ou consultez les demandes en attente</p>
                    </div>
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
                                    Document & Citoyen
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">
                                    Statut & Paiement
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                    Date
                                </th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[160px]">
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
                                            <!-- Citoyen -->
                                            <div class="text-sm text-gray-700 border-t pt-1">
                                                {{ Str::limit($request->user->nom . ' ' . $request->user->prenoms, 25) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="space-y-2">
                                            <!-- Statut principal -->
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                                @if($request->status === 'approved') bg-green-100 text-green-800
                                                @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                                @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($request->status === 'pending') En attente
                                                @elseif($request->status === 'in_progress') En cours
                                                @elseif($request->status === 'approved') Approuvée
                                                @elseif($request->status === 'rejected') Rejetée
                                                @else {{ $request->status }} @endif
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
                                            
                                            <!-- Bouton Traiter (si disponible) -->
                                            @if($request->status === 'pending' && $request->payment_status === 'paid')
                                                <a href="{{ route('agent.requests.process', $request) }}"
                                                   class="inline-flex items-center justify-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                                    <i class="fas fa-cogs mr-1"></i>Traiter
                                                </a>
                                            @elseif($request->status === 'pending' && $request->payment_status !== 'paid')
                                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium rounded-md text-yellow-700 bg-yellow-50">
                                                    <i class="fas fa-clock mr-1"></i>En attente
                                                </span>
                                            @elseif($request->status === 'in_progress' && $request->assigned_to === Auth::id())
                                                <a href="{{ route('agent.requests.edit', $request) }}"
                                                   class="inline-flex items-center justify-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>Modifier
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p class="text-lg">Aucune demande trouvée</p>
                        <p class="text-sm mt-1">Essayez de modifier vos filtres ou consultez les demandes en attente</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(config('app.debug'))
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Informations de débogage</h3>
            <dl class="text-xs text-gray-500">
                <div class="grid grid-cols-2 gap-2">
                    <dt>Total des demandes:</dt>
                    <dd>{{ $stats['total'] }}</dd>
                    <dt>Demandes en attente:</dt>
                    <dd>{{ $stats['pending'] }}</dd>
                    <dt>Demandes en cours:</dt>
                    <dd>{{ $stats['in_progress'] }}</dd>
                    <dt>Demandes traitables:</dt>
                    <dd>{{ $stats['processable'] }}</dd>
                    <dt>Demandes non payées:</dt>
                    <dd>{{ $stats['unpaid'] }}</dd>
                </div>
            </dl>
        </div>
    @endif
</div>
@endsection
