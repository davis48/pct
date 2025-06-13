@extends('layouts.modern')

@section('title', 'Accueil - PCT UVCI')

@push('body-class', 'welcome-page homepage')

@push('styles')
<style>
    .gradient-bg {
        background: #1976d2;
        position: relative;
        overflow: hidden;
    }
    .gradient-bg-light {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    .gradient-text {
        background: linear-gradient(135deg, #1976d2 0%, #43a047 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .gradient-text {
        background: linear-gradient(135deg, #1976d2 0%, #43a047 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .feature-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    .feature-card:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    .stats-counter {
        animation: countUp 2s ease-out;
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section avec message de bienvenue -->
    <section class="gradient-bg text-white py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="z-10 relative">
                    <h1 class="text-6xl md:text-7xl font-bold mb-6 leading-tight text-white">
                        Bienvenue sur la plateforme de demande d'acte d'état civil en ligne
                    </h1>
                    <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                        Simplifiez vos démarches administratives avec notre plateforme numérique moderne.
                        Obtenez vos documents officiels rapidement et en toute sécurité.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        @auth
                            @php
                                $dashboardUrl = '/dashboard';
                                if (auth()->user()->isAdmin()) {
                                    $dashboardUrl = '/admin/dashboard';
                                } elseif (auth()->user()->isAgent()) {
                                    $dashboardUrl = '/agent/dashboard';
                                }
                            @endphp
                            <a href="{{ url($dashboardUrl) }}" class="group bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 text-center transform hover:scale-105 hover:shadow-xl">
                                <i class="fas fa-tachometer-alt mr-2 group-hover:animate-spin"></i>Mon Tableau de Bord
                            </a>
                        @else
                            <a href="{{ route('choose.role') }}" class="group bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all duration-300 text-center transform hover:scale-105 hover:shadow-xl">
                                <i class="fas fa-sign-in-alt mr-2 group-hover:translate-x-1 transition-transform"></i>Se Connecter
                            </a>
                            <a href="{{ route('register.standalone') }}" class="group border-2 border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-blue-600 transition-all duration-300 text-center transform hover:scale-105">
                                <i class="fas fa-user-plus mr-2 group-hover:rotate-12 transition-transform"></i>S'inscrire
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div class="text-center">
                            <div class="text-3xl font-bold stats-counter">1000+</div>
                            <div class="text-blue-200 text-sm">Citoyens inscrits</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold stats-counter">500+</div>
                            <div class="text-blue-200 text-sm">Documents générés</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold stats-counter">24/7</div>
                            <div class="text-blue-200 text-sm">Service disponible</div>
                        </div>
                    </div>
                </div>
                <div class="relative z-10">
                    <div class="relative">
                        <!-- Main illustration -->
                        <div class="relative bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-3xl p-8 border border-white border-opacity-20">
                            <div class="text-center">
                                <div class="text-8xl mb-6">📋</div>
                                <h3 class="text-2xl font-bold mb-4">Services Administratifs</h3>
                                <p class="text-blue-100">Tous vos documents officiels en quelques clics</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Animated background elements -->
        <div class="absolute top-10 left-10 text-white opacity-10">
            <i class="fas fa-file-alt text-4xl"></i>
        </div>
        <div class="absolute top-32 right-20 text-white opacity-10">
            <i class="fas fa-stamp text-3xl"></i>
        </div>
        <div class="absolute bottom-20 left-1/4 text-white opacity-10">
            <i class="fas fa-certificate text-5xl"></i>
        </div>
    </section>

    <!-- Présentation de la plateforme -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Une Plateforme Moderne pour Tous vos Documents</h2>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Notre plateforme digitale révolutionne l'accès aux services administratifs.
                    Conçue pour les citoyens et gérée par des agents qualifiés, elle garantit
                    rapidité, sécurité et simplicité dans toutes vos démarches.
                </p>
            </div>
        </div>
    </section>

    <!-- Services disponibles -->
    <section class="py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold gradient-text mb-6">Nos Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez l'ensemble de nos services numériques conçus pour simplifier vos démarches administratives
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
                <!-- Service 1: Demandes en Ligne -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-file-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Demandes en Ligne</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Effectuez vos demandes de documents administratifs sans vous déplacer.
                        Processus entièrement dématérialisé avec validation automatique.
                    </p>
                    <div class="mt-4 flex items-center text-blue-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>100% numérique</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
                
                <!-- Service 2: Suivi en Temps Réel -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Suivi en Temps Réel</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Suivez l'état d'avancement de vos demandes étape par étape avec
                        des notifications automatiques et un tableau de bord interactif.
                    </p>
                    <div class="mt-4 flex items-center text-yellow-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>Notifications push</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
                
                <!-- Service 3: Téléchargement Sécurisé -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-download text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Téléchargement Sécurisé</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Récupérez vos documents officiels directement depuis votre espace
                        personnel sécurisé avec cryptage de bout en bout.
                    </p>
                    <div class="mt-4 flex items-center text-green-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>Cryptage SSL</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
                
                <!-- Service 4: Support Dédié -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-headset text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Support Dédié</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Une équipe d'agents qualifiés vous accompagne et répond à toutes
                        vos questions via chat, email ou téléphone.
                    </p>
                    <div class="mt-4 flex items-center text-cyan-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>Assistance 24/7</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
                
                <!-- Service 5: Traitement Rapide -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-bolt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Traitement Rapide</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-red-500 to-red-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Délais de traitement optimisés grâce à notre workflow
                        numérique intelligent et automatisé.
                    </p>
                    <div class="mt-4 flex items-center text-red-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>Sous 48h</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
                
                <!-- Service 6: Archivage Numérique -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 transform hover:-translate-y-1 transition-all duration-300 group border border-gray-100">
                    <div class="flex items-start mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-archive text-white text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Archivage Numérique</h5>
                            <div class="w-8 h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Conservez un historique complet de tous vos documents
                        dans votre espace personnel avec sauvegarde automatique.
                    </p>
                    <div class="mt-4 flex items-center text-blue-600 font-medium text-sm group-hover:translate-x-1 transition-transform duration-300">
                        <span>Stockage illimité</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Formulaires Interactifs -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-edit text-green-600 mr-3"></i>
                    Formulaires Interactifs
                </h2>
                <p class="text-xl text-gray-600">
                    Nouveauté ! Remplissez vos documents directement en ligne et téléchargez-les instantanément.
                    Plus rapide, plus simple, plus efficace.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                    <div class="text-center">
                        <div class="bg-pink-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-baby text-pink-600 text-3xl"></i>
                        </div>
                        <h5 class="text-xl font-bold mb-3">Extrait de Naissance</h5>
                        <p class="text-gray-600 text-sm mb-4">
                            Formulaire interactif pour générer votre extrait de naissance avec tous les champs requis.
                        </p>
                        <a href="{{ route('interactive-forms.show', 'extrait-naissance') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Remplir maintenant
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                    <div class="text-center">
                        <div class="bg-red-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heart text-red-600 text-3xl"></i>
                        </div>
                        <h5 class="text-xl font-bold mb-3">Certificat de Mariage</h5>
                        <p class="text-gray-600 text-sm mb-4">
                            Formulaire interactif pour demander votre certificat de mariage officiel.
                        </p>
                        <a href="{{ route('interactive-forms.show', 'certificat-mariage') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Remplir maintenant
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h5 class="text-xl font-bold mb-3">Certificat de Célibat</h5>
                        <p class="text-gray-600 text-sm mb-4">
                            Formulaire interactif pour obtenir votre certificat de célibat.
                        </p>
                        <a href="{{ route('interactive-forms.show', 'certificat-celibat') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Remplir maintenant
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 hover-lift">
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-home text-green-600 text-3xl"></i>
                        </div>
                        <h5 class="text-xl font-bold mb-3">Attestation de Domicile</h5>
                        <p class="text-gray-600 text-sm mb-4">
                            Formulaire interactif pour votre attestation de domicile.
                        </p>
                        <a href="{{ route('interactive-forms.show', 'attestation-domicile') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                            <i class="fas fa-edit mr-1"></i>
                            Remplir maintenant
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('interactive-forms.index') }}" class="bg-green-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-list mr-2"></i>
                    Voir tous les formulaires interactifs
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action final -->
    <section class="py-20 gradient-bg-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Prêt à Commencer ?</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Rejoignez des milliers de citoyens qui font déjà confiance à notre plateforme
                    pour leurs démarches administratives.
                </p>
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register.standalone') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-user-plus mr-2"></i>Créer un Compte Citoyen
                        </a>
                        <a href="{{ route('choose.role') }}" class="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Se Connecter
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Inscription gratuite • Données sécurisées • Support 24/7
                    </p>
                @else
                    <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-2"></i>Accéder à Mon Espace
                    </a>
                @endguest
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Animation des compteurs au scroll
    function animateCounters() {
        const counters = document.querySelectorAll('.stats-counter');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = counter.textContent;
                    const numericTarget = parseInt(target.replace(/[^0-9]/g, ''));
                    
                    if (numericTarget && !counter.classList.contains('animated')) {
                        counter.classList.add('animated');
                        animateValue(counter, 0, numericTarget, 2000, target);
                    }
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => observer.observe(counter));
    }
    
    function animateValue(element, start, end, duration, originalText) {
        const startTimestamp = performance.now();
        const suffix = originalText.replace(/[0-9]/g, '');
        
        function updateValue(currentTimestamp) {
            const elapsed = currentTimestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function pour une animation plus fluide
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(start + (end - start) * easeOut);
            
            element.textContent = current + suffix;
            
            if (progress < 1) {
                requestAnimationFrame(updateValue);
            } else {
                element.textContent = originalText;
            }
        }
        
        requestAnimationFrame(updateValue);
    }
    
    // Animation des cartes au scroll
    function animateCards() {
        const cards = document.querySelectorAll('.feature-card, .hover-lift');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 200);
                }
            });
        }, { threshold: 0.1 });
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    }
    
    // Effet de parallaxe léger pour les éléments flottants
    function parallaxEffect() {
        const floatingElements = document.querySelectorAll('.floating-icon');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            
            floatingElements.forEach((element, index) => {
                const rate = scrolled * -0.5 * (index + 1);
                element.style.transform = `translateY(${rate}px)`;
            });
        });
    }
    
    // Effet de typing pour le titre principal
    function typingEffect() {
        const title = document.querySelector('.gradient-bg h1');
        if (title) {
            const text = title.textContent;
            title.textContent = '';
            title.style.opacity = '1';
            
            let i = 0;
            const typeInterval = setInterval(() => {
                title.textContent += text.charAt(i);
                i++;
                
                if (i > text.length) {
                    clearInterval(typeInterval);
                }
            }, 50);
        }
    }
    
    // Smooth scroll pour les liens d'ancrage
    function smoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                // Ignore empty hash or just '#'
                if (!href || href === '#' || href.length <= 1) {
                    return;
                }
                
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Délai pour permettre au CSS de se charger
        setTimeout(() => {
            animateCounters();
            animateCards();
            parallaxEffect();
            smoothScroll();
            // typingEffect(); // Désactivé pour le moment
        }, 500);
        
        // Effet hover pour les boutons
        const buttons = document.querySelectorAll('.btn, .bg-blue-600, .bg-green-600');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Animation des icônes au hover
        const iconContainers = document.querySelectorAll('.feature-card .bg-gradient-to-r');
        iconContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'rotate(360deg) scale(1.2)';
                    icon.style.transition = 'transform 0.6s ease';
                }
            });
            
            container.addEventListener('mouseleave', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'rotate(0deg) scale(1)';
                }
            });
        });
    });
    
    // Performance: désactiver les animations sur les appareils avec une préférence pour un mouvement réduit
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--animation-duration', '0s');
    }
</script>
@endpush
