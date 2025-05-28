@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Détails de la demande #{{ $request->reference_number }}</h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.requests.edit', $request->id) }}" class="btn btn-warning btn-sm mb-0">
                                <i class="fas fa-edit"></i>&nbsp;&nbsp;Modifier
                            </a>
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-info btn-sm mb-0">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Informations de la demande</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Référence</label>
                                                <p class="form-control-static">#{{ $request->reference_number }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Statut</label>
                                                <p class="form-control-static">
                                                    @if($request->status === 'pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @elseif($request->status === 'approved')
                                                        <span class="badge bg-success">Approuvée</span>
                                                    @elseif($request->status === 'rejected')
                                                        <span class="badge bg-danger">Rejetée</span>
                                                    @else
                                                        <span class="badge bg-info">En traitement</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Date de création</label>
                                                <p class="form-control-static">{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Dernière mise à jour</label>
                                                <p class="form-control-static">{{ $request->updated_at ? $request->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Message</label>
                                                <p class="form-control-static">{{ $request->message ?? 'Aucun message' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($request->attachments)
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Pièces jointes</label>
                                                <div class="list-group mt-2">
                                                    @foreach($request->attachments as $attachment)
                                                    <a href="{{ Storage::url($attachment) }}" class="list-group-item list-group-item-action" target="_blank">
                                                        <i class="fas fa-paperclip me-2"></i>{{ basename($attachment) }}
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($request->admin_notes)
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Notes administratives</label>
                                                <div class="p-3 border rounded bg-light">
                                                    {{ $request->admin_notes }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Document demandé</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                                        </div>
                                        <div>
                                            @if($request->document)
                                                <h5>{{ $request->document->title }}</h5>
                                                <p class="text-sm mb-0">Catégorie: <span class="badge bg-primary">{{ $request->document->category }}</span></p>
                                                <p class="text-sm mb-2">{{ Str::limit($request->document->description, 150) }}</p>
                                                <a href="{{ route('admin.documents.show', $request->document->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Voir le document
                                                </a>
                                            @else
                                                <h5 class="text-muted">Document non spécifié</h5>
                                                <p class="text-sm mb-0">Catégorie: <span class="badge bg-secondary">Non définie</span></p>
                                                <p class="text-sm mb-2">Aucun document associé à cette demande.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Informations du citoyen</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-user-circle fa-3x text-info"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $request->user->nom }} {{ $request->user->prenoms }}</h5>
                                            <p class="text-xs text-secondary mb-0">{{ $request->user->email }}</p>
                                        </div>
                                    </div>

                                    @if($request->user->phone)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3 text-center" style="width: 24px;">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">{{ $request->user->phone }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($request->user->address)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3 text-center" style="width: 24px;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">{{ $request->user->address }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-center" style="width: 24px;">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">Membre depuis {{ $request->user->created_at ? $request->user->created_at->format('d/m/Y') : 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <a href="{{ route('admin.users.show', $request->user->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-user me-1"></i> Voir le profil
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.requests.update', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-control-label">Changer le statut</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                                <option value="processing" {{ $request->status === 'processing' ? 'selected' : '' }}>En traitement</option>
                                                <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Approuvée</option>
                                                <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Rejetée</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="admin_notes" class="form-control-label">Notes administratives</label>
                                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4">{{ $request->admin_notes }}</textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
