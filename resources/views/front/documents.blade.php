@extends('layouts.front.app')
@section('content')
<section class="py-8">
    <div class="container mx-auto px-4">
        <!-- En-tête moderne -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-folder-open mr-3 text-primary-600"></i>
                Mes documents
            </h1>
            <p class="text-gray-600">Consultez et téléchargez vos documents officiels</p>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">3</h3>
                        <p class="text-sm text-gray-600">Documents terminés</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">2</h3>
                        <p class="text-sm text-gray-600">En traitement</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">5</h3>
                        <p class="text-sm text-gray-600">Total demandes</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Historique des demandes -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-gray-600"></i>
                    Historique des demandes
                </h2>
            </div>
            <div class="p-6">
                <!-- Version desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Référence</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Type de document</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Date de demande</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Statut</th>
                                <th class="text-left py-4 px-4 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">#AD20230001</td>
                                <td class="py-4 px-4 text-sm text-gray-700">Attestation de domicile</td>
                                <td class="py-4 px-4 text-sm text-gray-700">15/06/2023</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                        Terminé
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <button class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                                        <i class="fas fa-download mr-2 text-xs"></i>
                                        Télécharger
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">#AD20230002</td>
                                <td class="py-4 px-4 text-sm text-gray-700">Extrait d'acte de naissance</td>
                                <td class="py-4 px-4 text-sm text-gray-700">20/06/2023</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-400 rounded-full"></span>
                                        En traitement
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <button class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed" disabled>
                                        <i class="fas fa-clock mr-2 text-xs"></i>
                                        En attente
                                    </button>
                                </td>
                            </tr>
                                </tr>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">#AD20230003</td>
                                <td class="py-4 px-4 text-sm text-gray-700">Demande de passeport</td>
                                <td class="py-4 px-4 text-sm text-gray-700">25/06/2023</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-blue-400 rounded-full"></span>
                                        En vérification
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <button class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed" disabled>
                                        <i class="fas fa-clock mr-2 text-xs"></i>
                                        En attente
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Version mobile -->
                <div class="md:hidden space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-medium text-gray-900">#AD20230001</h3>
                                <p class="text-sm text-gray-600">Attestation de domicile</p>
                                <p class="text-xs text-gray-500">15/06/2023</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Terminé</span>
                        </div>
                        <div class="flex justify-end">
                            <button class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors duration-200">
                                <i class="fas fa-download mr-2 text-xs"></i>
                                Télécharger
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-medium text-gray-900">#AD20230002</h3>
                                <p class="text-sm text-gray-600">Extrait d'acte de naissance</p>
                                <p class="text-xs text-gray-500">20/06/2023</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En traitement</span>
                        </div>
                        <div class="flex justify-end">
                            <button class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed" disabled>
                                <i class="fas fa-clock mr-2 text-xs"></i>
                                En attente
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-medium text-gray-900">#AD20230003</h3>
                                <p class="text-sm text-gray-600">Demande de passeport</p>
                                <p class="text-xs text-gray-500">25/06/2023</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">En vérification</span>
                        </div>
                        <div class="flex justify-end">
                            <button class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed" disabled>
                                <i class="fas fa-clock mr-2 text-xs"></i>
                                En attente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section d'aide -->
        <div class="mt-8 text-center">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-200">
                <div class="mb-4">
                    <i class="fas fa-question-circle text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Vous ne trouvez pas votre document ?</h3>
                <p class="text-gray-600 mb-6">Créez une nouvelle demande pour obtenir un document officiel</p>
                <a href="{{ route('requests.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Nouvelle demande
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
