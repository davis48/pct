@extends('layouts.front.app')
@section('content')
    <section class="hero-section bg-light py-5">
        <div class="container text-center">
            <h1 class="display-4">Plateforme de Documents Administratifs</h1>
            <p class="lead">Gérez toutes vos demandes administratives en ligne</p>
            @auth
                <a href="{{ url('/requests') }}" class="btn btn-primary btn-lg mt-3">Faire une demande</a>
            @else
                <a href="{{ url('/connexion') }}" class="btn btn-primary btn-lg mt-3">Connectez-vous pour faire une demande</a>
            @endauth
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nos Services</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Demandes en ligne</h5>
                            <p class="card-text">Effectuez vos demandes de documents administratifs sans vous déplacer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-history fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Suivi des demandes</h5>
                            <p class="card-text">Suivez l'état d'avancement de vos demandes en temps réel.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-download fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Téléchargement</h5>
                            <p class="card-text">Récupérez vos documents directement depuis votre espace personnel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
