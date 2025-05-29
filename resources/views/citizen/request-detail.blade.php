@extends('layouts.front.app')

@section('title', 'Détails de la demande')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Mon Espace
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails de la demande</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header de la demande -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $request->document->name ?? 'Document non spécifié' }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Demande #{{ $request->id }} • Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end">
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i> En attente
                                </span>
                                @break
                            @case('in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-cog mr-2"></i> En cours de traitement
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-2"></i> Approuvé
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-2"></i> Rejeté
                                </span>
                                @break
                        @endswitch
                        
                        @if($request->status === 'approved')
                            <button class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le document
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Chronologie du traitement -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Suivi de la demande</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <!-- Étape 1: Soumission -->
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-paper-plane text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Demande soumise
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Étape 2: Attribution (si applicable) -->
                                @if($request->assigned_to)
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Assignée à l'agent <span class="font-medium text-gray-900">{{ $request->assignedAgent->name }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Étape 3: En cours (si applicable) -->
                                @if(in_array($request->status, ['in_progress', 'approved', 'rejected']))
                                <li>
                                    <div class="relative pb-8">
                                        @if(!in_array($request->status, ['approved', 'rejected']))
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-cog text-white text-sm"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Traitement en cours
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif

                                <!-- Étape finale -->
                                @if(in_array($request->status, ['approved', 'rejected']))
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @if($request->status === 'approved')
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-check text-white text-sm"></i>
                                                    </span>
                                                @else
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-times text-white text-sm"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $request->status === 'approved' ? 'Demande approuvée' : 'Demande rejetée' }}
                                                        @if($request->processedBy)
                                                            par <span class="font-medium text-gray-900">{{ $request->processedBy->name }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $request->processed_at ? $request->processed_at->format('d/m/Y H:i') : $request->updated_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Motif de rejet (si applicable) -->
                @if($request->status === 'rejected' && $request->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-red-900 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Motif du rejet
                        </h3>
                        <p class="text-red-700">{{ $request->rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Informations de la demande -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de document</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->document->name ?? 'Non spécifié' }}</dd>
                            </div>
                            
                            @if($request->document && $request->document->description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->document->description }}</dd>
                            </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de soumission</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>

                            @if($request->assignedAgent)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Agent assigné</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->assignedAgent->name }}</dd>
                            </div>
                            @endif

                            @if($request->processed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de traitement</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $request->processed_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                    </div>
                    <div class="px-4 py-5 sm:px-6 space-y-3">
                        @if($request->status === 'approved')
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le document
                            </button>
                        @endif

                        <button onclick="window.print()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-print mr-2"></i>
                            Imprimer cette page
                        </button>

                        @if(in_array($request->status, ['pending', 'rejected']))
                            <a href="{{ route('requests.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle demande
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Aide -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-3">
                            <i class="fas fa-question-circle mr-2"></i>
                            Besoin d'aide ?
                        </h3>
                        <p class="text-blue-700 text-sm mb-3">
                            Si vous avez des questions concernant votre demande, n'hésitez pas à nous contacter.
                        </p>
                        <div class="space-y-2">
                            <p class="text-blue-700 text-sm">
                                <i class="fas fa-phone mr-2"></i>
                                +225 XX XX XX XX
                            </p>
                            <p class="text-blue-700 text-sm">
                                <i class="fas fa-envelope mr-2"></i>
                                support@pct-uvci.gov.ci
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Actualisation automatique du statut
function checkForUpdates() {
    fetch(`{{ route('citizen.request.updates', $request->id) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.status !== '{{ $request->status }}') {
                // Le statut a changé, recharger la page
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification des mises à jour:', error);
        });
}

// Vérifier les mises à jour toutes les 30 secondes
setInterval(checkForUpdates, 30000);
</script>
@endpush
@endsection
