@extends('layouts.modern')
@section('content')
<!-- MODERNE TAILWINDCSS VIEW - v2.0 -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold gradient-text mb-4">Choisissez votre type de compte</h1>
                <p class="text-gray-600 text-lg">Sélectionnez votre profil pour accéder à votre espace personnalisé</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Espace Agent -->
                <a href="{{ route('login', ['role' => 'agent']) }}" class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <div class="p-8 text-center">
                            <div class="mb-6 relative">
                                <div class="w-20 h-20 mx-auto bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-user-tie text-3xl text-white"></i>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full animate-pulse"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Je suis un Agent</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">Accédez à votre espace agent pour gérer les demandes citoyennes et traiter les dossiers administratifs</p>
                            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg group-hover:from-green-600 group-hover:to-emerald-700 transition-all duration-300 shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Connexion Agent
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-green-500 to-emerald-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    </div>
                </a>

                <!-- Espace Citoyen -->
                <a href="{{ route('login', ['role' => 'citizen']) }}" class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                        <div class="p-8 text-center">
                            <div class="mb-6 relative">
                                <div class="w-20 h-20 mx-auto bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-user text-3xl text-white"></i>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary-500 rounded-full animate-pulse"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Je suis un Citoyen</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">Accédez à votre espace citoyen pour créer vos demandes administratives et suivre leur progression</p>
                            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg group-hover:from-primary-700 group-hover:to-primary-800 transition-all duration-300 shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Connexion Citoyen
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-primary-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
