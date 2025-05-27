@extends('layouts.front.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Tableau de bord</h2>
                    <p class="card-text">Bienvenue {{ Auth::user()->nom }} {{ Auth::user()->prenoms }} sur votre espace personnel.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Mes demandes</h5>
                    <p class="card-text">Consultez l'état de vos demandes en cours.</p>
                    <a href="{{ url('/requests') }}" class="btn btn-primary">Voir mes demandes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-plus-circle fa-3x mb-3 text-success"></i>
                    <h5 class="card-title">Nouvelle demande</h5>
                    <p class="card-text">Effectuez une nouvelle demande de document.</p>
                    <a href="{{ url('/requests/create') }}" class="btn btn-success">Créer une demande</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-download fa-3x mb-3 text-info"></i>
                    <h5 class="card-title">Mes documents</h5>
                    <p class="card-text">Téléchargez vos documents approuvés.</p>
                    <a href="{{ route('documents.index') }}" class="btn btn-info">Voir mes documents</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Demandes récentes</h5>
                </div>
                <div class="card-body">
                    @if(Auth::user()->requests && count(Auth::user()->requests) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach(Auth::user()->requests()->latest()->take(5)->get() as $request)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $request->type }}
                                    <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }} rounded-pill">
                                        {{ $request->status == 'pending' ? 'En attente' : ($request->status == 'approved' ? 'Approuvé' : 'Rejeté') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center my-3">Vous n'avez pas encore effectué de demande.</p>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ url('/requests') }}" class="btn btn-sm btn-outline-primary">Voir toutes mes demandes</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mes informations</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Nom complet :</strong> {{ Auth::user()->nom }} {{ Auth::user()->prenoms }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email :</strong> {{ Auth::user()->email }}
                        </li>
                        @if(Auth::user()->phone)
                        <li class="list-group-item">
                            <strong>Téléphone :</strong> {{ Auth::user()->phone }}
                        </li>
                        @endif
                        @if(Auth::user()->address)
                        <li class="list-group-item">
                            <strong>Adresse :</strong> {{ Auth::user()->address }}
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-user-edit me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
