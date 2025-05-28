@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h2 class="text-center mb-4">Choisissez votre type de compte</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <a href="{{ route('login', ['role' => 'agent']) }}" class="card h-100 text-decoration-none">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-user-tie fa-3x text-success"></i>
                                </div>
                                <h3 class="card-title h5">Je suis un Agent</h3>
                                <p class="card-text text-muted">Accédez à votre espace agent pour gérer les demandes citoyennes</p>
                                <div class="mt-3">
                                    <span class="btn btn-success">Connexion Agent</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('login', ['role' => 'citizen']) }}" class="card h-100 text-decoration-none">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-user fa-3x text-primary"></i>
                                </div>
                                <h3 class="card-title h5">Je suis un Citoyen</h3>
                                <p class="card-text text-muted">Accédez à votre espace citoyen pour gérer vos demandes</p>
                                <div class="mt-3">
                                    <span class="btn btn-primary">Connexion Citoyen</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
