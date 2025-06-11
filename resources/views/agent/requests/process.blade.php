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
                                            <button class="font-medium text-green-600 hover:text-green-500 preview-attachment-btn"
                                                   data-attachment-id="{{ $attachment->id }}"
                                                   data-attachment-name="{{ $attachment->file_name }}"
                                                   title="Prévisualiser">
                                               <i class="fas fa-eye mr-1"></i> Prévisualiser
                                            </button>
                                            <a href="{{ route('agent.requests.attachment.download', $attachment->id) }}" 
                                               class="font-medium text-indigo-600 hover:text-indigo-500"
                                               target="_blank">
                                               <i class="fas fa-download mr-1"></i> Télécharger
                                            </a>
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               class="font-medium text-blue-600 hover:text-blue-500"
                                               target="_blank">
                                               <i class="fas fa-external-link-alt mr-1"></i> Ouvrir
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

    <!-- Informations du formulaire interactif -->
    @if($request->additional_data)
        @php
            $additionalData = json_decode($request->additional_data, true);
            $formData = $additionalData['form_data'] ?? [];
        @endphp
        @if(!empty($formData))
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center">
                        <i class="fas fa-magic text-blue-600 mr-2"></i>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informations saisies dans le formulaire</h3>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Vérifiez toutes les informations fournies par le citoyen</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        @if(isset($formData['nom']) && isset($formData['prenoms']))
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom et Prénoms</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['nom'] . ' ' . $formData['prenoms'] }}</dd>
                            </div>
                        @elseif(isset($formData['name']) && $formData['name'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom et Prénoms</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['name'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['gender']) && $formData['gender'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Sexe</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['gender'] == 'M' ? 'Masculin' : 'Féminin' }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['date_naissance']) && $formData['date_naissance'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['date_naissance'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @elseif(isset($formData['date_of_birth']) && $formData['date_of_birth'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['date_of_birth'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['birth_time']) && $formData['birth_time'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Heure de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['birth_time'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['lieu_naissance']) && $formData['lieu_naissance'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lieu de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['lieu_naissance'] }}</dd>
                            </div>
                        @elseif(isset($formData['place_of_birth']) && $formData['place_of_birth'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lieu de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['place_of_birth'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['nationalite']) && $formData['nationalite'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nationalité</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['nationalite'] }}</dd>
                            </div>
                        @elseif(isset($formData['nationality']) && $formData['nationality'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nationalité</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['nationality'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['profession']) && $formData['profession'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Profession</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['profession'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['cin_number']) && $formData['cin_number'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro CNI</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['cin_number'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['telephone']) && $formData['telephone'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['telephone'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['adresse_complete']) && $formData['adresse_complete'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adresse complète</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['adresse_complete'] }}</dd>
                            </div>
                        @elseif(isset($formData['address']) && $formData['address'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['address'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['commune']) && $formData['commune'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Commune</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['commune'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['quartier']) && $formData['quartier'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Quartier</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['quartier'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['date_installation']) && $formData['date_installation'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date d'installation</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['date_installation'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['statut_logement']) && $formData['statut_logement'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Statut du logement</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['statut_logement'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['nom_temoin']) && $formData['nom_temoin'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom du témoin</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['nom_temoin'] . ' ' . ($formData['prenoms_temoin'] ?? '') }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['profession_temoin']) && $formData['profession_temoin'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Profession du témoin</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['profession_temoin'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['telephone_temoin']) && $formData['telephone_temoin'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Téléphone du témoin</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['telephone_temoin'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['motif']) && $formData['motif'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Motif de la demande</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['motif'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['lieu_delivrance']) && $formData['lieu_delivrance'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lieu de délivrance</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['lieu_delivrance'] }}</dd>
                            </div>
                        @endif
                        
                        <!-- Informations de filiation -->
                        @if((isset($formData['father_name']) && $formData['father_name']) || (isset($formData['mother_name']) && $formData['mother_name']))
                            <div class="bg-blue-50 px-4 py-3">
                                <h4 class="text-md font-medium text-blue-900 flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    Informations de filiation
                                </h4>
                            </div>
                        @endif
                        
                        @if(isset($formData['father_name']) && $formData['father_name'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['father_name'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['father_profession']) && $formData['father_profession'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Profession du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['father_profession'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['mother_name']) && $formData['mother_name'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nom de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['mother_name'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['mother_profession']) && $formData['mother_profession'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Profession de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['mother_profession'] }}</dd>
                            </div>
                        @endif
                        
                        <!-- Informations d'enregistrement -->
                        @if((isset($formData['registry_number']) && $formData['registry_number']) || (isset($formData['registration_date']) && $formData['registration_date']) || (isset($formData['declarant_name']) && $formData['declarant_name']))
                            <div class="bg-purple-50 px-4 py-3">
                                <h4 class="text-md font-medium text-purple-900 flex items-center">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Informations d'enregistrement
                                </h4>
                            </div>
                        @endif
                        
                        @if(isset($formData['registry_number']) && $formData['registry_number'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro de registre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['registry_number'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['registration_date']) && $formData['registration_date'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date d'enregistrement</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['registration_date'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['declarant_name']) && $formData['declarant_name'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Déclarant</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['declarant_name'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['registration_number']) && $formData['registration_number'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro d'acte</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['registration_number'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['centre_etat_civil']) && $formData['centre_etat_civil'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Centre d'état civil</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['centre_etat_civil'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['numero_acte']) && $formData['numero_acte'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro d'acte (détaillé)</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['numero_acte'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['date_declaration']) && $formData['date_declaration'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de déclaration</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['date_declaration'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['annee_registre']) && $formData['annee_registre'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Année du registre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['annee_registre'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['prenoms_pere']) && $formData['prenoms_pere'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Prénoms du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['prenoms_pere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['age_pere']) && $formData['age_pere'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Âge du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['age_pere'] }} ans</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['lieu_naissance_pere']) && $formData['lieu_naissance_pere'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lieu de naissance du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['lieu_naissance_pere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['domicile_pere']) && $formData['domicile_pere'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Domicile du père</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['domicile_pere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['prenoms_mere']) && $formData['prenoms_mere'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Prénoms de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['prenoms_mere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['age_mere']) && $formData['age_mere'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Âge de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['age_mere'] }} ans</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['lieu_naissance_mere']) && $formData['lieu_naissance_mere'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lieu de naissance de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['lieu_naissance_mere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['domicile_mere']) && $formData['domicile_mere'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Domicile de la mère</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['domicile_mere'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['centre_etat_civil']) && $formData['centre_etat_civil'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Centre d'état civil</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['centre_etat_civil'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['numero_acte']) && $formData['numero_acte'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Numéro d'acte</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['numero_acte'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['date_declaration']) && $formData['date_declaration'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de déclaration</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($formData['date_declaration'])->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['annee_registre']) && $formData['annee_registre'])
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Année de registre</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['annee_registre'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($formData['first_names']) && $formData['first_names'])
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Prénoms</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $formData['first_names'] }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endif
    @endif

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

<!-- Modal de prévisualisation -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900" id="previewTitle">Prévisualisation</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closePreviewModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <div id="previewContent" class="text-center">
                    <div id="previewLoader" class="flex items-center justify-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mr-3"></i>
                        <span class="text-gray-500">Chargement de la prévisualisation...</span>
                    </div>
                    <div id="previewError" class="hidden py-8 text-red-600">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Impossible de prévisualiser ce fichier</p>
                    </div>
                    <div id="previewFrame" class="hidden w-full" style="height: 70vh;">
                        <iframe id="previewIframe" class="w-full h-full border-0 rounded"></iframe>
                    </div>
                    <div id="previewImage" class="hidden">
                        <img id="previewImg" class="max-w-full h-auto max-h-96 mx-auto rounded" alt="Prévisualisation">
                    </div>
                </div>
            </div>
            <div class="flex items-center px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b">
                <div class="flex-1">
                    <span id="previewFileName" class="text-sm text-gray-500"></span>
                </div>
                <button type="button" 
                        onclick="closePreviewModal()"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
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

    // Gestion de la prévisualisation des pièces jointes
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter les événements pour les boutons de prévisualisation
        document.querySelectorAll('.preview-attachment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const attachmentId = this.dataset.attachmentId;
                const attachmentName = this.dataset.attachmentName;
                previewAttachment(attachmentId, attachmentName);
            });
        });
    });

    function previewAttachment(attachmentId, fileName) {
        // Debug : vérifier que l'attachmentId est bien récupéré
        console.log('Preview attachment called with:', { attachmentId, fileName });
        
        if (!attachmentId) {
            console.error('Attachment ID is empty!');
            alert('Erreur : ID de fichier manquant');
            return;
        }

        const modal = document.getElementById('previewModal');
        const title = document.getElementById('previewTitle');
        const fileNameSpan = document.getElementById('previewFileName');
        const loader = document.getElementById('previewLoader');
        const error = document.getElementById('previewError');
        const frame = document.getElementById('previewFrame');
        const iframe = document.getElementById('previewIframe');
        const image = document.getElementById('previewImage');
        const img = document.getElementById('previewImg');

        // Afficher le modal
        modal.classList.remove('hidden');
        title.textContent = `Prévisualisation - ${fileName}`;
        fileNameSpan.textContent = fileName;

        // Reset et afficher le loader
        loader.classList.remove('hidden');
        error.classList.add('hidden');
        frame.classList.add('hidden');
        image.classList.add('hidden');

        // URL de prévisualisation - utiliser la route Laravel avec un placeholder
        const baseUrl = `{{ route('agent.requests.attachment.preview', 'PLACEHOLDER') }}`;
        const previewUrl = baseUrl.replace('PLACEHOLDER', attachmentId);
        
        // URL pour l'affichage direct (nouvelle route)
        const baseViewUrl = `{{ route('agent.requests.attachment.view', 'PLACEHOLDER') }}`;
        const viewUrl = baseViewUrl.replace('PLACEHOLDER', attachmentId);
        
        // URL pour les données base64
        const baseDataUrl = `{{ route('agent.requests.attachment.data', 'PLACEHOLDER') }}`;
        const dataUrl = baseDataUrl.replace('PLACEHOLDER', attachmentId);
        
        console.log('Preview URL generated:', previewUrl);
        console.log('View URL generated:', viewUrl);

        // Détecter le type de fichier
        const fileExtension = fileName.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'].includes(fileExtension);
        const isPdf = fileExtension === 'pdf';
        const isText = ['txt', 'csv', 'log'].includes(fileExtension);

        // Simuler un petit délai pour l'effet de chargement
        setTimeout(() => {
            loader.classList.add('hidden');

            if (isImage) {
                // Afficher l'image
                console.log('Loading image:', previewUrl);
                img.src = previewUrl;
                img.onload = function() {
                    console.log('Image loaded successfully');
                    image.classList.remove('hidden');
                };
                img.onerror = function() {
                    console.error('Error loading image');
                    error.classList.remove('hidden');
                };
            } else if (isPdf) {
                // Pour les PDFs, utiliser PDF.js via une URL de données encodée
                console.log('Loading PDF using alternative method');
                
                // Essayer d'abord d'afficher avec PDF.js intégré du navigateur
                const pdfJsUrl = `/web/viewer.html?file=${encodeURIComponent(viewUrl)}`;
                
                // Si PDF.js n'est pas disponible, utiliser Google Docs Viewer comme fallback
                const googleDocsUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(window.location.origin + viewUrl)}&embedded=true`;
                
                // Créer un bouton pour voir le PDF dans un nouvel onglet
                frame.innerHTML = `
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <i class="fas fa-file-pdf text-red-500 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Document PDF</h3>
                            <p class="text-gray-600 mb-4">Ce document PDF ne peut pas être affiché directement dans cette fenêtre.</p>
                        </div>
                        <div class="space-y-3">
                            <button onclick="window.open('${viewUrl}', '_blank')" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors mr-3">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Ouvrir dans un nouvel onglet
                            </button>
                            <button onclick="window.open('${googleDocsUrl}', '_blank')" 
                                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Voir avec Google Docs
                            </button>
                            <div class="mt-4">
                                <a href="${previewUrl}" download="${fileName}" 
                                   class="text-gray-600 hover:text-gray-800 underline">
                                    <i class="fas fa-download mr-1"></i>
                                    Télécharger le document
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                
                frame.classList.remove('hidden');
                console.log('PDF preview options displayed');
                
            } else if (isText) {
                // Pour les fichiers texte, utiliser l'iframe directement
                console.log('Loading text file in iframe:', previewUrl);
                iframe.src = previewUrl;
                frame.classList.remove('hidden');
                
                iframe.onerror = function() {
                    console.error('Error loading text file');
                    error.classList.remove('hidden');
                };
            } else {
                // Type de fichier non supporté pour la prévisualisation
                error.classList.remove('hidden');
                document.querySelector('#previewError p').textContent = 
                    'Ce type de fichier ne peut pas être prévisualisé. Utilisez le bouton "Télécharger" pour l\'ouvrir.';
            }
        }, 500);
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModal');
        const iframe = document.getElementById('previewIframe');
        const img = document.getElementById('previewImg');
        const frame = document.getElementById('previewFrame');
        
        // Nettoyer les sources pour éviter les fuites mémoire (seulement si les éléments existent)
        if (iframe) {
            iframe.src = 'about:blank';
        }
        if (img) {
            img.src = '';
        }
        
        // Réinitialiser le contenu du frame s'il a été modifié
        if (frame) {
            frame.innerHTML = '<iframe id="previewIframe" class="w-full h-full border-0 rounded"></iframe>';
        }
        
        // Cacher le modal
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Fermer le modal en cliquant en dehors
    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreviewModal();
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('previewModal');
            if (!modal.classList.contains('hidden')) {
                closePreviewModal();
            }
        }
    });
</script>
@endpush
