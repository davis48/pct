@extends('layouts.front.app')
@section('content')
    <!-- Hero Section avec message de bienvenue -->
    <section class="hero-section" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; padding: 6rem 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Bienvenue sur la Plateforme Citoyenne</h1>
                    <p class="fs-4 mb-4">
                        Simplifiez vos démarches administratives avec notre plateforme numérique moderne.
                        Obtenez vos documents officiels rapidement et en toute sécurité.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        @auth
                            @php
                                $dashboardUrl = '/dashboard';
                                if (auth()->user()->isAdmin()) {
                                    $dashboardUrl = '/admin/dashboard';
                                } elseif (auth()->user()->isAgent()) {
                                    $dashboardUrl = '/agent/dashboard';
                                }
                            @endphp
                            <a href="{{ url($dashboardUrl) }}" class="btn btn-light btn-lg px-4 py-3">
                                <i class="fas fa-tachometer-alt me-2"></i>Mon Tableau de Bord
                            </a>
                        @else
                            <a href="{{ route('choose.role') }}" class="btn btn-light btn-lg px-4 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-user-plus me-2"></i>S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <i class="fas fa-city" style="font-size: 15rem; opacity: 0.3;"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-file-shield text-warning" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Présentation de la plateforme -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4">Une Plateforme Moderne pour Tous vos Documents</h2>
                    <p class="lead text-muted mb-5">
                        Notre plateforme digitale révolutionne l'accès aux services administratifs.
                        Conçue pour les citoyens et gérée par des agents qualifiés, elle garantit
                        rapidité, sécurité et simplicité dans toutes vos démarches.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Avantages de la plateforme -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 display-6 fw-bold">Pourquoi Choisir Notre Plateforme ?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-clock text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Gain de Temps</h5>
                            <p class="card-text text-muted">
                                Fini les files d'attente ! Effectuez vos demandes 24h/24, 7j/7
                                depuis chez vous ou en déplacement.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-shield-alt text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Sécurité Maximale</h5>
                            <p class="card-text text-muted">
                                Vos données personnelles sont protégées par des systèmes de sécurité
                                de dernière génération et conformes aux normes RGPD.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-mobile-alt text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title fw-bold">Accessible Partout</h5>
                            <p class="card-text text-muted">
                                Interface responsive et intuitive, accessible depuis tous vos appareils :
                                ordinateur, tablette ou smartphone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services disponibles -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 display-6 fw-bold">Nos Services</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Demandes en Ligne</h5>
                            </div>
                            <p class="card-text text-muted">
                                Effectuez vos demandes de documents administratifs sans vous déplacer.
                                Processus entièrement dématérialisé.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-search text-warning"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Suivi en Temps Réel</h5>
                            </div>
                            <p class="card-text text-muted">
                                Suivez l'état d'avancement de vos demandes étape par étape avec
                                des notifications automatiques.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-download text-success"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Téléchargement Sécurisé</h5>
                            </div>
                            <p class="card-text text-muted">
                                Récupérez vos documents officiels directement depuis votre espace
                                personnel sécurisé.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-headset text-info"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Support Dédié</h5>
                            </div>
                            <p class="card-text text-muted">
                                Une équipe d'agents qualifiés vous accompagne et répond à toutes
                                vos questions.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-clock text-danger"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Traitement Rapide</h5>
                            </div>
                            <p class="card-text text-muted">
                                Délais de traitement optimisés grâce à notre workflow
                                numérique efficace.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-archive text-secondary"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Archivage Numérique</h5>
                            </div>
                            <p class="card-text text-muted">
                                Conservez un historique complet de tous vos documents
                                dans votre espace personnel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action final -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-6 fw-bold mb-4">Prêt à Commencer ?</h2>
                    <p class="lead text-muted mb-4">
                        Rejoignez des milliers de citoyens qui font déjà confiance à notre plateforme
                        pour leurs démarches administratives.
                    </p>
                    @guest
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-user-plus me-2"></i>Créer un Compte Citoyen
                            </a>
                            <a href="{{ route('choose.role') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
                            </a>
                        </div>
                        <p class="small text-muted mt-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Inscription gratuite • Données sécurisées • Support 24/7
                        </p>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>Accéder à Mon Espace
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection
