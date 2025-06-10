@extends('layouts.agent')

@section('title', 'Traiter la demande #' . $request->reference_number)

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">Traiter la demande #{{ $request->reference_number }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('agent.requests.show', $request) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left -ml-1 mr-2"></i>
                Retour aux détails
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    @if(session('debug'))
        <div class="bg-blue-50 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informations de débogage</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <pre>{{ print_r(session('debug'), true) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- État actuel et actions rapides -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">État de la demande</h3>
                    <div class="mt-2 flex items-center">
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i> En attente
                                </span>
                                @break
                            @case('in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-spinner mr-2"></i> En cours
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-2"></i> Approuvée
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-2"></i> Rejetée
                                </span>
                                @break
                            @case('pending_info')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-info-circle mr-2"></i> En attente d'informations
                                </span>
                                @break
                        @endswitch

                        @if($request->assignedAgent)
                            <span class="ml-3 text-sm text-gray-500">
                                Assignée à {{ $request->assignedAgent->prenoms }} {{ $request->assignedAgent->nom }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de la demande -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de la demande</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Résumé des informations avant traitement</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Demandeur</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $request->user->nom }} {{ $request->user->prenoms }}
                        <br>
                        <span class="text-gray-500">{{ $request->user->email }}</span>
                    </dd>
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
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                </div>
                @if($request->uploaded_document)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Document joint</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="{{ Storage::url($request->uploaded_document) }}"
                               target="_blank"
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ basename($request->uploaded_document) }}
                            </a>
                        </dd>
                    </div>
                @endif
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Pièces jointes du citoyen</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="mb-2 flex justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $request->citizenAttachments->count() }} fichier(s) joint(s)
                            </span>
                            @if($request->citizenAttachments->count() == 0 && $request->attachments && is_array($request->attachments) && count($request->attachments) > 0)
                                <span class="text-xs text-orange-500">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Fichiers dans l'ancien format
                                </span>
                            @endif
                        </div>

                        {{-- Nouvelle méthode : utiliser la relation attachments --}}
                        @if($request->citizenAttachments->count() > 0)
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach($request->citizenAttachments as $attachment)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <i class="fas fa-paperclip flex-shrink-0 text-gray-400"></i>
                                            <span class="ml-2 flex-1 w-0 truncate">{{ $attachment->file_name }}</span>
                                            <span class="ml-2 text-xs text-gray-500">{{ number_format($attachment->file_size / 1024, 1) }} KB</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            <a href="{{ route('agent.requests.attachment.download', $attachment->id) }}" 
                                               class="font-medium text-indigo-600 hover:text-indigo-500"
                                               target="_blank">
                                               <i class="fas fa-download mr-1"></i> Télécharger
                                            </a>
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               class="font-medium text-blue-600 hover:text-blue-500"
                                               target="_blank">
                                               <i class="fas fa-eye mr-1"></i> Voir
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        
                        {{-- Ancienne méthode : pour les anciennes demandes --}}
                        @elseif($request->attachments && is_array($request->attachments) && count($request->attachments) > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-md p-3 mb-3">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-orange-400 mr-2 mt-0.5"></i>
                                    <div class="text-sm text-orange-700">
                                        Cette demande utilise l'ancien système de fichiers. Les fichiers peuvent ne pas être accessibles.
                                    </div>
                                </div>
                            </div>
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach($request->attachments as $key => $attachment)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <i class="fas fa-paperclip flex-shrink-0 text-gray-400"></i>
                                            <span class="ml-2 flex-1 w-0 truncate">{{ is_string($attachment) ? $attachment : ($attachment['name'] ?? 'Fichier') }}</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            <a href="{{ route('agent.requests.citizen-attachment.download', ['requestId' => $request->id, 'fileIndex' => $key]) }}" 
                                               class="font-medium text-indigo-600 hover:text-indigo-500"
                                               target="_blank">
                                               <i class="fas fa-download mr-1"></i> Télécharger (Legacy)
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">Aucune pièce jointe</p>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Formulaire de traitement -->
    <form action="{{ route('agent.requests.update', $request->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('POST')
        
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Des erreurs sont survenues :
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut de la demande</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ $request->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Commentaires administratifs -->
                    <div>
                        <label for="admin_comments" class="block text-sm font-medium text-gray-700">
                            Commentaires administratifs
                        </label>
                        <div class="mt-1">
                            <textarea id="admin_comments"
                                    name="admin_comments"
                                    rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Vos commentaires sur la demande...">{{ old('admin_comments', $request->admin_comments) }}</textarea>
                        </div>
                    </div>

                    <!-- Exigences supplémentaires -->
                    <div x-data="{ showExigences: {{ $request->status === 'pending_info' ? 'true' : 'false' }} }" x-show="showExigences">
                        <label for="additional_requirements" class="block text-sm font-medium text-gray-700">
                            Exigences supplémentaires
                        </label>
                        <div class="mt-1">
                            <textarea id="additional_requirements"
                                    name="additional_requirements"
                                    rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Informations ou documents supplémentaires requis...">{{ old('additional_requirements', $request->additional_requirements) }}</textarea>
                        </div>
                    </div>

                    <!-- Upload de fichiers -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Ajouter des pièces jointes
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-upload mx-auto h-12 w-12 text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Téléverser des fichiers</span>
                                        <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, PDF jusqu'à 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour la demande
                </button>
            </div>
        </div>
    </form>

    <!-- Historique des actions -->
    @if($request->history && $request->history->count() > 0)
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Historique des actions
            </h3>
            <div class="mt-6 flow-root">
                <ul class="-mb-8">
                    @foreach($request->history as $action)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                            <i class="fas fa-history text-white"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $action->description }}</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $action->created_at }}">{{ $action->created_at->format('d/m/Y H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Gérer l'affichage du champ des exigences supplémentaires
    document.getElementById('status').addEventListener('change', function() {
        const showExigences = this.value === 'pending_info';
        document.querySelector('[x-data]').__x.$data.showExigences = showExigences;
    });

    // Prévisualisation des fichiers
    const input = document.querySelector('input[type="file"]');
    input.addEventListener('change', function() {
        const container = this.parentElement.parentElement;
        const list = document.createElement('ul');
        list.className = 'mt-2 text-sm text-gray-600';
        
        Array.from(this.files).forEach(file => {
            const li = document.createElement('li');
            li.textContent = `${file.name} (${Math.round(file.size / 1024)}KB)`;
            list.appendChild(li);
        });
        
        const existingList = container.querySelector('ul');
        if (existingList) {
            container.removeChild(existingList);
        }
        container.appendChild(list);
    });

    // Confirmer la mise à jour si le statut est changé en approuvé ou rejeté
    document.querySelector('form').addEventListener('submit', function(e) {
        const status = document.getElementById('status').value;
        if (status === 'approved' || status === 'rejected') {
            if (!confirm(`Êtes-vous sûr de vouloir ${status === 'approved' ? 'approuver' : 'rejeter'} cette demande ?`)) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endpush
