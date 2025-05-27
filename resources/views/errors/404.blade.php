@extends('layouts.front.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">404</h1>
            <h2 class="mb-4">Page non trouvée</h2>
            <p class="mb-4">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
</div>
@endsection
