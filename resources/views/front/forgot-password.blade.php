@extends('layouts.front.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Mot de passe oublié</h3>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pour réinitialiser votre mot de passe, veuillez contacter l'administrateur du système à l'adresse suivante :
                    </div>

                    <div class="text-center">
                        <p class="mb-3">
                            <strong>Email :</strong> admin@pct-uvci.ci<br>
                            <strong>Téléphone :</strong> +225 XX XX XX XX
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
