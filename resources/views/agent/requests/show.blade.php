@extends('layouts.agent')

@section('title', 'Détails de la demande #' . $request->reference_number)

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Demande #{{ $request->reference_number }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('agent.requests.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
            @if($request->status === 'pending' || $request->status === 'in_progress')
                <a href="{{ route('agent.requests.process', $request->id) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Traiter cette demande
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
<!-- Lien de debug temporaire -->
<div class="mb-4">
    <a href="{{ route('agent.requests.debug-attachments', $request->id) }}"
       target="_blank"
       class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100">
        🐛 Debug Attachments
    </a>
</div>

<div class="space-y-6">
    <!-- Statut et informations générales -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informations générales</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Détails de la demande et statut actuel</p>
                </div>
                <div>
                    @if($request->status === 'pending')
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            En attente
                        </span>
                    @elseif($request->status === 'in_progress')
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            En cours
                        </span>
                    @elseif($request->status === 'approved')
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Approuvée
                        </span>
                    @elseif($request->status === 'rejected')
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Rejetée
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Référence</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">#{{ $request->reference_number }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Document demandé</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div>
                            <p class="font-medium">{{ $request->getDocumentTitle() }}</p>
                            <p class="text-gray-500">{{ $request->getDocumentCategory() }}</p>
                            @if($request->additional_data)
                                @php
                                    $additionalData = json_decode($request->additional_data, true);
                                @endphp
                                @if(isset($additionalData['generated_via']) && $additionalData['generated_via'] === 'interactive_form')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        <i class="fas fa-magic mr-1"></i>
                                        Formulaire interactif
                                    </span>
                                @endif
                            @endif
                            @if($request->document && $request->document->description)
                                <p class="mt-1 text-sm text-gray-600">{{ $request->document->description }}</p>
                            @endif
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                </div>
                @if($request->assigned_to)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Assigné à</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($request->assignedAgent)
                                {{ $request->assignedAgent->nom }} {{ $request->assignedAgent->prenoms }}
                            @else
                                Agent (ID: {{ $request->assigned_to }})
                            @endif
                        </dd>
                    </div>
                @endif
                @if($request->completed_at)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date de traitement</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->completed_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                @endif
                @if($request->notes)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Informations du demandeur -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du demandeur</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Coordonnées et informations personnelles</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->user->nom }} {{ $request->user->prenoms }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="mailto:{{ $request->user->email }}" class="text-indigo-600 hover:text-indigo-500">
                            {{ $request->user->email }}
                        </a>
                    </dd>
                </div>
                @if($request->user->telephone)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="tel:{{ $request->user->telephone }}" class="text-indigo-600 hover:text-indigo-500">
                                {{ $request->user->telephone }}
                            </a>
                        </dd>
                    </div>
                @endif
                @if($request->user->date_naissance)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ \Carbon\Carbon::parse($request->user->date_naissance)->format('d/m/Y') }}
                        </dd>
                    </div>
                @endif
                @if($request->user->lieu_naissance)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Lieu de naissance</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->user->lieu_naissance }}</dd>
                    </div>
                @endif
                @if($request->user->adresse)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->user->adresse }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Documents joints -->
    @if(($request->uploaded_document) || ($request->attachments && count($request->attachments) > 0))
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Documents joints</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Documents fournis par le demandeur</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                {{-- Document unique (uploaded_document) --}}
                @if($request->uploaded_document)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-3">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Document joint</p>
                                <p class="text-sm text-gray-500">{{ basename($request->uploaded_document) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('agent.requests.download-document', ['id' => $request->id, 'type' => 'uploaded']) }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger
                        </a>
                    </div>
                @endif

                {{-- Attachments de la table séparée --}}
                @foreach($request->attachments as $attachment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-3">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $attachment->file_name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $attachment->file_type }} 
                                    @if($attachment->file_size)
                                        - {{ number_format($attachment->file_size / 1024, 0) }} KB
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('agent.requests.attachment.download', $attachment->id) }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Document traité (si disponible) -->
    @if($request->processed_document)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Document traité</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Document final généré après traitement</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Document final</p>
                            <p class="text-sm text-gray-500">{{ basename($request->processed_document) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('agent.requests.download-document', ['id' => $request->id, 'type' => 'processed']) }}"
                       class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Télécharger
                    </a>
                </div>
            </div>
        </div>
    @endif


</div>
@endsection

@section('actions')
    <div class="flex space-x-2 mt-4">
        @if($request->status === 'en_attente')
            <a href="{{ route('agent.requests.process', $request) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-play mr-2"></i>Traiter cette demande
            </a>
        @elseif($request->status === 'en_cours')
            <form method="POST" action="{{ route('agent.requests.approve', $request) }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-check mr-2"></i>Approuver
                </button>
            </form>
            <form method="POST" action="{{ route('agent.requests.reject', $request) }}" class="inline ml-2">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i>Rejeter
                </button>
            </form>
            <form method="POST" action="{{ route('agent.requests.complete', $request) }}" class="inline ml-2">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700">
                    <i class="fas fa-flag-checkered mr-2"></i>Marquer comme terminée
                </button>
            </form>
        @endif
    </div>
@endsection
