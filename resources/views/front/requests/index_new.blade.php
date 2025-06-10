@extends('layouts.front.app')
@section('title', 'Mes Demandes - PCT UVCI')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v3.0 -->
<section class="py-8 bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- En-tête avec bouton d'action -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-8">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold gradient-text mb-2 flex items-center">
                    <i class="fas fa-folder-open mr-3 text-blue-600"></i>
                    Mes demandes
                </h1>
                <p class="text-gray-600">Suivez le statut de toutes vos demandes de documents</p>
            </div>
            <a href="{{ route('requests.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-plus-circle mr-2"></i>
                Nouvelle demande
            </a>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 animate-fade-in">
            <div class="flex items-center text-green-800">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Contenu principal -->
        @if(count($requests) > 0)
        
        <!-- Vue Desktop : Tableau -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Référence</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Type</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Date</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Document</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Statut</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($requests as $request)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6">
                                <span class="text-sm font-medium text-gray-900">#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-gray-700 capitalize">{{ $request->type }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-gray-700">{{ $request->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-gray-700">{{ $request->document ? $request->document->title : 'Document général' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($request->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                                    En attente
                                </span>
                                @elseif($request->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Approuvée
                                </span>
                                @elseif($request->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                    Rejetée
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    {{ ucfirst($request->status) }}
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('requests.show', $request->id) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors duration-200"
                                       title="Voir les détails">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if($request->status == 'approved')
                                    <a href="{{ route('documents.download', $request->id) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-colors duration-200"
                                       title="Télécharger le document">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vue Mobile/Tablet : Cards -->
        <div class="lg:hidden space-y-4">
            @foreach($requests as $request)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <!-- Header de la card -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-sm text-gray-500 capitalize">{{ $request->type }}</p>
                        </div>
                        @if($request->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                            En attente
                        </span>
                        @elseif($request->status == 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Approuvée
                        </span>
                        @elseif($request->status == 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Rejetée
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                            {{ ucfirst($request->status) }}
                        </span>
                        @endif
                    </div>

                    <!-- Informations -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span>Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-file-alt mr-2 text-green-500"></i>
                            <span>{{ $request->document ? $request->document->title : 'Document général' }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('requests.show', $request->id) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Détails
                        </a>
                        @if($request->status == 'approved')
                        <a href="{{ route('documents.download', $request->id) }}" 
                           class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition-colors duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        
        <!-- État vide -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-16 text-center">
            <div class="mb-6">
                <i class="fas fa-inbox text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Aucune demande trouvée</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Vous n'avez pas encore effectué de demandes de documents. Commencez dès maintenant en soumettant votre première demande !
            </p>
            <a href="{{ route('requests.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-plus-circle mr-2"></i>
                Créer ma première demande
            </a>
        </div>
        
        @endif

        <!-- Section d'aide -->
        <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informations utiles
            </h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm text-blue-800">
                <div class="flex items-start">
                    <i class="fas fa-clock mr-2 text-blue-600 mt-1"></i>
                    <div>
                        <strong>Délai de traitement :</strong><br>
                        2 à 5 jours ouvrables selon le type de document
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-bell mr-2 text-blue-600 mt-1"></i>
                    <div>
                        <strong>Notifications :</strong><br>
                        Vous recevrez un email à chaque mise à jour
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-download mr-2 text-blue-600 mt-1"></i>
                    <div>
                        <strong>Téléchargement :</strong><br>
                        Disponible dès l'approbation de votre demande
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
