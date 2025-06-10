@extends('layouts.agent')

@section('title', 'Détails du Document - ' . $request->reference_number)

@section('content')
<div class="space-y-6" x-data="documentDetails">
    <!-- En-tête avec informations principales -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('agent.documents.index') }}"
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $request->reference_number }}</h1>
                    <p class="text-gray-600">{{ $request->getDocumentTitle() }}</p>
                    <p class="text-sm text-gray-500">{{ $request->getDocumentCategory() }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800'
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$documentRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($documentRequest->status) }}
                </span>

                @if($documentRequest->status === 'pending')
                    <button @click="showProcessModal = true"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Traiter
                    </button>
                @endif
            </div>
        </div>

        <!-- Informations principales en grille -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informations du demandeur -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Demandeur
                </h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Nom:</span> {{ $documentRequest->user->nom }} {{ $documentRequest->user->prenoms }}</p>
                    <p><span class="font-medium">Email:</span> {{ $documentRequest->user->email }}</p>
                    <p><span class="font-medium">Téléphone:</span> {{ $documentRequest->user->telephone ?? 'Non renseigné' }}</p>
                    <p><span class="font-medium">Adresse:</span> {{ $documentRequest->user->adresse ?? 'Non renseignée' }}</p>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Détails
                </h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Date de demande:</span> {{ $documentRequest->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-medium">Date de traitement:</span> {{ $documentRequest->processed_at ? $documentRequest->processed_at->format('d/m/Y H:i') : 'En attente' }}</p>
                    <p><span class="font-medium">Agent assigné:</span> {{ $documentRequest->processed_by ? $documentRequest->processedBy->nom . ' ' . $documentRequest->processedBy->prenoms : 'Non assigné' }}</p>
                    <p><span class="font-medium">Priorité:</span>
                        <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $documentRequest->priority === 'urgent' ? 'bg-red-100 text-red-800' : ($documentRequest->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $documentRequest->priority ?? 'Normal' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informations
                </h3>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Coût:</span> {{ number_format($documentRequest->document->price, 0, ',', ' ') }} FCFA</p>
                    <p><span class="font-medium">Délai de traitement:</span> {{ $documentRequest->document->processing_time ?? 'Non défini' }}</p>
                    <p><span class="font-medium">Validité:</span> {{ $documentRequest->document->validity_period ?? 'Non définie' }}</p>
                    <p><span class="font-medium">Dernière mise à jour:</span> {{ $documentRequest->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Description de la demande -->
    @if($documentRequest->description)
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            Description de la demande
        </h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-700">{{ $documentRequest->description }}</p>
        </div>
    </div>
    @endif

    <!-- Pièces jointes -->
    @if($request->citizenAttachments->count() > 0 || ($request->attachments && is_array($request->attachments) && count($request->attachments) > 0))
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            Pièces jointes 
            @if($request->citizenAttachments->count() > 0)
                ({{ $request->citizenAttachments->count() }})
            @else
                ({{ count($request->attachments) }} - format legacy)
            @endif
        </h3>

        {{-- Nouvelles pièces jointes (table attachments) --}}
        @if($request->citizenAttachments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($request->citizenAttachments as $attachment)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            @php
                                $extension = pathinfo($attachment->file_path, PATHINFO_EXTENSION);
                                $iconClass = match($extension) {
                                    'pdf' => 'text-red-600',
                                    'doc', 'docx' => 'text-blue-600',
                                    'jpg', 'jpeg', 'png', 'gif' => 'text-green-600',
                                    default => 'text-gray-600'
                                };
                            @endphp
                            <svg class="w-8 h-8 {{ $iconClass }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-sm truncate max-w-32" title="{{ $attachment->file_name }}">
                                    {{ $attachment->file_name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $attachment->file_size ? number_format($attachment->file_size / 1024, 1) . ' KB' : '' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('agent.requests.attachment.download', $attachment->id) }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded transition-colors text-center">
                            <i class="fas fa-download mr-1"></i> Télécharger
                        </a>
                        <a href="{{ Storage::url($attachment->file_path) }}" 
                           target="_blank"
                           class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded transition-colors text-center">
                            <i class="fas fa-eye mr-1"></i> Voir
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        
        {{-- Anciennes pièces jointes (format JSON legacy) --}}
        @elseif($request->attachments && is_array($request->attachments) && count($request->attachments) > 0)
            <div class="bg-orange-50 border border-orange-200 rounded-md p-3 mb-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-orange-400 mr-2 mt-0.5"></i>
                    <div class="text-sm text-orange-700">
                        Cette demande utilise l'ancien système de fichiers. Les fichiers peuvent ne pas être accessibles.
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($request->attachments as $key => $attachment)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-sm truncate max-w-32">
                                    {{ is_string($attachment) ? $attachment : ($attachment['name'] ?? 'Fichier') }}
                                </p>
                                <p class="text-xs text-gray-500">Format legacy</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('agent.requests.citizen-attachment.download', ['requestId' => $request->id, 'fileIndex' => $key]) }}" 
                           class="flex-1 bg-orange-600 hover:bg-orange-700 text-white text-xs px-3 py-2 rounded transition-colors text-center">
                            <i class="fas fa-download mr-1"></i> Télécharger (Legacy)
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

                <div class="flex space-x-2">
                    <a href="{{ route('agent.documents.attachment.preview', $attachment) }}"
                       target="_blank"
                       class="flex-1 bg-blue-50 text-blue-700 px-3 py-2 rounded text-sm hover:bg-blue-100 transition-colors text-center">
                        Aperçu
                    </a>
                    <a href="{{ route('agent.documents.attachment.download', $attachment) }}"
                       class="flex-1 bg-gray-50 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-100 transition-colors text-center">
                        Télécharger
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Historique des actions -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Historique
        </h3>

        <div class="space-y-4">
            <!-- Timeline des événements -->
            <div class="flex items-start space-x-3">
                <div class="w-3 h-3 bg-blue-600 rounded-full mt-1.5"></div>
                <div>
                    <p class="font-medium">Demande créée</p>
                    <p class="text-sm text-gray-600">{{ $documentRequest->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>

            @if($documentRequest->processed_at)
            <div class="flex items-start space-x-3">
                <div class="w-3 h-3 bg-green-600 rounded-full mt-1.5"></div>
                <div>
                    <p class="font-medium">Traitement commencé</p>
                    <p class="text-sm text-gray-600">{{ $documentRequest->processed_at->format('d/m/Y à H:i') }}</p>
                    @if($documentRequest->processedBy)
                        <p class="text-sm text-gray-500">Par {{ $documentRequest->processedBy->nom }} {{ $documentRequest->processedBy->prenoms }}</p>
                    @endif
                </div>
            </div>
            @endif

            @if($documentRequest->status === 'completed')
            <div class="flex items-start space-x-3">
                <div class="w-3 h-3 bg-green-600 rounded-full mt-1.5"></div>
                <div>
                    <p class="font-medium">Demande approuvée</p>
                    <p class="text-sm text-gray-600">{{ $documentRequest->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            @endif

            @if($documentRequest->status === 'rejected' && $documentRequest->rejection_reason)
            <div class="flex items-start space-x-3">
                <div class="w-3 h-3 bg-red-600 rounded-full mt-1.5"></div>
                <div>
                    <p class="font-medium">Demande rejetée</p>
                    <p class="text-sm text-gray-600">{{ $documentRequest->updated_at->format('d/m/Y à H:i') }}</p>
                    <div class="mt-2 bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-800">{{ $documentRequest->rejection_reason }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de traitement -->
    <div x-show="showProcessModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
         @click="showProcessModal = false">

        <div @click.stop class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Traiter la demande</h3>

            <form action="{{ route('agent.requests.process', $documentRequest) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                        <select name="action" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner une action</option>
                            <option value="approve">Approuver</option>
                            <option value="reject">Rejeter</option>
                        </select>
                    </div>

                    <div x-show="$el.querySelector('select[name=action]').value === 'reject'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motif de rejet</label>
                        <textarea name="rejection_reason"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  rows="3"
                                  placeholder="Expliquez la raison du rejet..."></textarea>
                    </div>

                    <div x-show="$el.querySelector('select[name=action]').value === 'approve'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Document généré (optionnel)</label>
                        <input type="file"
                               name="generated_document"
                               accept=".pdf,.doc,.docx"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Commentaires</label>
                        <textarea name="comments"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  rows="2"
                                  placeholder="Commentaires additionnels..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            @click="showProcessModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('documentDetails', () => ({
        showProcessModal: false
    }))
})
</script>
@endsection
