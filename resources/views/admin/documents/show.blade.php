@extends('layouts.admin.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Détails du document</h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-warning btn-sm mb-0">
                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Modifier
                            </a>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-info btn-sm mb-0">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $document->title }}</h4>
                            <p class="text-sm text-muted">Catégorie: <span class="badge bg-primary">{{ $document->category }}</span></p>
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description:</h6>
                                <p>{{ $document->description }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Statut:</h6>
                                <p>
                                    @if($document->status === 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($document->status === 'inactive')
                                        <span class="badge bg-secondary">Inactif</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Visibilité:</h6>
                                <p>
                                    @if($document->is_public)
                                        <span class="badge bg-info">Public</span>
                                    @else
                                        <span class="badge bg-dark">Privé</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Dates:</h6>
                                <p class="text-sm mb-1">Créé le: {{ $document->created_at ? $document->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="text-sm">Dernière mise à jour: {{ $document->updated_at ? $document->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Fichier du document</h6>
                                </div>
                                <div class="card-body text-center py-4">
                                    @if($document->file_path)
                                        <div class="document-preview mb-3">
                                            <i class="fas fa-file-pdf fa-5x text-danger"></i>
                                        </div>
                                        <a href="{{ Storage::url($document->file_path) }}" class="btn btn-primary" target="_blank">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Aucun fichier associé
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Statistiques</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-sm">Demandes totales:</span>
                                        <span class="text-sm font-weight-bold">{{ $document->requests->count() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-sm">Demandes en attente:</span>
                                        <span class="text-sm font-weight-bold">{{ $document->requests->where('status', 'pending')->count() }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-sm">Demandes approuvées:</span>
                                        <span class="text-sm font-weight-bold">{{ $document->requests->where('status', 'approved')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($document->requests->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Demandes récentes</h6>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Utilisateur</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Statut</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                    <th class="text-secondary opacity-7"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($document->requests->take(5) as $request)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <i class="fas fa-user text-secondary opacity-10 fa-lg me-3"></i>
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $request->user->nom }} {{ $request->user->prenoms }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $request->user->email }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        @if($request->status === 'pending')
                                                            <span class="badge badge-sm bg-warning">En attente</span>
                                                        @elseif($request->status === 'approved')
                                                            <span class="badge badge-sm bg-success">Approuvée</span>
                                                        @elseif($request->status === 'rejected')
                                                            <span class="badge badge-sm bg-danger">Rejetée</span>
                                                        @else
                                                            <span class="badge badge-sm bg-info">En traitement</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-xs font-weight-bold">{{ $request->created_at ? $request->created_at->format('d/m/Y') : 'N/A' }}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
