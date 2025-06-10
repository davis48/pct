@extends('layouts.front.app')
@section('title', 'Détails de la demande #' . $request->id . ' - PCT UVCI')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Navigation de retour -->
            <div class="mb-6">
                <a href="{{ route('requests.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux demandes
                </a>
            </div>

            <!-- En-tête de la demande -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-white">Demande #{{ $request->id }}</h1>
                        <div class="flex items-center">
                            @if($request->status == 'pending' || $request->status == 'en_attente')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                                En attente
                            </span>
                            @elseif($request->status == 'in_progress')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></span>
                                En cours de traitement
                            </span>
                            @elseif($request->status == 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Approuvée
                            </span>
                            @elseif($request->status == 'processed' || $request->status == 'ready')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Document prêt
                            </span>
                            @elseif($request->status == 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Terminée
                            </span>
                            @elseif($request->status == 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                Rejetée
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                {{ ucfirst($request->status) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Informations principales -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Type de demande</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($request->type) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-green-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Date de soumission</p>
                                    <p class="font-semibold text-gray-900">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-purple-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Dernière mise à jour</p>
                                    <p class="font-semibold text-gray-900">{{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-folder text-orange-500 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Document associé</p>
                                    <p class="font-semibold text-gray-900">{{ $request->document ? $request->document->title : 'Aucun' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-edit mr-2 text-blue-500"></i>Description de la demande
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-gray-700 leading-relaxed">{{ $request->description }}</p>
                        </div>
                    </div>

                    <!-- Commentaires de l'administration -->
                    @if($request->admin_comments)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-comments mr-2 text-green-500"></i>Commentaires de l'administration
                        </h3>
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <p class="text-green-800 leading-relaxed">{{ $request->admin_comments }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Pièces jointes -->
                    @if($request->attachments && count($request->attachments) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-paperclip mr-2 text-purple-500"></i>Pièces jointes
                        </h3>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($request->attachments as $index => $attachment)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                    <div class="flex-1 min-w-0">
                                        @if(is_string($attachment))
                                        <p class="text-sm font-medium text-gray-900 truncate">Fichier {{ $index + 1 }}</p>
                                        <a href="{{ asset('storage/' . $attachment) }}" 
                                           target="_blank"
                                           class="text-xs text-blue-600 hover:text-blue-800">
                                            Télécharger
                                        </a>
                                        @elseif(is_array($attachment) && isset($attachment['path']))
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment['name'] ?? 'Fichier ' . ($index + 1) }}</p>
                                        <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                           target="_blank"
                                           class="text-xs text-blue-600 hover:text-blue-800">
                                            Télécharger
                                        </a>
                                        @else
                                        <p class="text-sm text-gray-500">Fichier non disponible</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Notification de statut -->
                    @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    🎉 Votre document est prêt !
                                </h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>Votre demande a été traitée avec succès. Vous pouvez maintenant télécharger votre document en cliquant sur le bouton ci-dessous.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($request->status == 'in_progress')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-blue-400 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Traitement en cours
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Votre demande est actuellement en cours de traitement par nos équipes. Nous vous tiendrons informé de l'avancement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($request->status == 'pending' || $request->status == 'en_attente')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-hourglass-half text-yellow-400 mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    En attente de traitement
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Votre demande a été reçue et est en attente de traitement. Nous nous en occuperons dans les plus brefs délais.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions disponibles -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
                            <a href="{{ route('documents.download', $request->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-download mr-2"></i>Télécharger le document
                            </a>
                            @endif
                            
                            @if($request->status == 'approved' && $request->document)
                            <a href="{{ route('documents.show', $request->document->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-file-alt mr-2"></i>Voir les détails
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section de suivi chronologique -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-6 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-blue-500"></i>Suivi de la demande
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Étape soumission -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Demande soumise</p>
                                <p class="text-sm text-gray-500">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <!-- Étape traitement -->
                        @if($request->status != 'pending')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 {{ $request->status == 'approved' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $request->status == 'approved' ? 'fa-check text-green-600' : 'fa-times text-red-600' }}"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">
                                    Demande {{ $request->status == 'approved' ? 'approuvée' : 'rejetée' }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 animate-pulse"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">En cours de traitement</p>
                                <p class="text-sm text-gray-500">Votre demande est en cours d'examen</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($request->status == 'pending')
            <!-- Actions pour l'administrateur (si connecté en tant qu'admin) -->
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions administrateur</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Approuver -->
                            <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaires (optionnel)</label>
                                    <textarea name="comments" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                              placeholder="Ajoutez des commentaires pour le citoyen..."></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                    <i class="fas fa-check mr-2"></i>Approuver la demande
                                </button>
                            </form>
                            
                            <!-- Rejeter -->
                            <form method="POST" action="{{ route('admin.requests.reject', $request->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Raison du rejet</label>
                                    <textarea name="comments" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                              placeholder="Expliquez la raison du rejet..." required></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>Rejeter la demande
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endauth
            @endif
        </div>
    </div>
</section>
@endsection
