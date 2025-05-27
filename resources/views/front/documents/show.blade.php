@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('documents.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Retour aux documents
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-white">
                <h2 class="mb-0">{{ $document->title }}</h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Catégorie:</strong> {{ $document->category }}</p>
                        <p><strong>Statut:</strong>
                            @if($document->status == 'active')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Archivé</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-download me-2"></i>Télécharger le document
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $document->description }}
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Prévisualisation du document</h5>
                    <div class="ratio ratio-16x9">
                        @if(pathinfo($document->file_path, PATHINFO_EXTENSION) == 'pdf')
                            <iframe src="{{ asset('storage/' . $document->file_path) }}" allowfullscreen></iframe>
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light">
                                <p class="text-muted">La prévisualisation n'est disponible que pour les documents PDF.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="mb-0">Faire une demande liée à ce document</h3>
            </div>
            <div class="card-body">
                <p>Vous avez besoin d'aide ou d'informations supplémentaires concernant ce document?</p>
                <a href="{{ route('requests.create', ['document_id' => $document->id]) }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus-circle me-2"></i>Créer une demande liée
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
