@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('requests.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Retour aux demandes
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-white">
                <h2 class="mb-0">Demande #{{ $request->id }}</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Type de demande:</strong> {{ ucfirst($request->type) }}</p>
                        <p><strong>Date de soumission:</strong> {{ $request->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Dernière mise à jour:</strong> {{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Statut:</strong>
                            @if($request->status == 'pending')
                            <span class="badge bg-warning text-dark">En attente</span>
                            @elseif($request->status == 'approved')
                            <span class="badge bg-success">Approuvée</span>
                            @elseif($request->status == 'rejected')
                            <span class="badge bg-danger">Rejetée</span>
                            @endif
                        </p>
                        <p><strong>Document associé:</strong> {{ $request->document ? $request->document->title : 'Aucun' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description de la demande</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $request->description }}
                    </div>
                </div>

                @if($request->admin_comments)
                <div class="mb-4">
                    <h5>Commentaires de l'administration</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $request->admin_comments }}
                    </div>
                </div>
                @endif

                @if($request->attachments && count($request->attachments) > 0)
                <div class="mb-4">
                    <h5>Pièces jointes</h5>
                    <ul class="list-group">
                        @foreach($request->attachments as $attachment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ basename($attachment) }}</span>
                            <a href="{{ asset('storage/' . $attachment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        @if($request->status == 'approved' && $request->document)
        <div class="card shadow">
            <div class="card-header bg-white">
                <h3 class="mb-0">Document disponible</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    @if($request->document)
                        <div>
                            <h5>{{ $request->document->title }}</h5>
                            <p class="text-muted mb-0">Vous pouvez télécharger ce document</p>
                        </div>
                        <a href="{{ asset('storage/' . $request->document->file_path) }}" class="btn btn-success" target="_blank">
                            <i class="fas fa-file-download me-2"></i>Télécharger le document
                        </a>
                    @else
                        <div>
                            <h5 class="text-muted">Aucun document associé</h5>
                            <p class="text-muted mb-0">Aucun document n'est associé à cette demande</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
