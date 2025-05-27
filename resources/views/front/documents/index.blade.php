@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="mb-4">Mes documents</h1>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            @if(count($documents) > 0)
                @foreach($documents as $document)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
                                <h5 class="card-title mb-0">{{ $document->title }}</h5>
                            </div>
                            <p class="card-text text-muted small">Catégorie: {{ $document->category }}</p>
                            <p class="card-text">{{ Str::limit($document->description, 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('documents.show', $document->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-info-circle me-1"></i>Détails
                            </a>
                            <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="col-12">
                <div class="alert alert-info">
                    <p class="mb-0">Aucun document disponible pour le moment.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
