@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Modifier la demande #{{ $request->reference_number }}</h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-info btn-sm mb-0">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.requests.update', $request->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_number" class="form-control-label">Numéro de référence</label>
                                    <input class="form-control" type="text" id="reference_number" value="{{ $request->reference_number }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="pending" {{ old('status', $request->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="processing" {{ old('status', $request->status) === 'processing' ? 'selected' : '' }}>En traitement</option>
                                        <option value="approved" {{ old('status', $request->status) === 'approved' ? 'selected' : '' }}>Approuvée</option>
                                        <option value="rejected" {{ old('status', $request->status) === 'rejected' ? 'selected' : '' }}>Rejetée</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admin_notes" class="form-control-label">Notes administratives</label>
                                    <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes" rows="4">{{ old('admin_notes', $request->admin_notes) }}</textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ces notes sont visibles uniquement par les administrateurs.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admin_response" class="form-control-label">Réponse au citoyen</label>
                                    <textarea class="form-control @error('admin_response') is-invalid @enderror" id="admin_response" name="admin_response" rows="4">{{ old('admin_response', $request->admin_response) }}</textarea>
                                    @error('admin_response')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Cette réponse sera visible par le citoyen dans son espace personnel.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notify_user" name="notify_user" value="1" {{ old('notify_user') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notify_user">Notifier l'utilisateur par email</label>
                                </div>
                                <small class="form-text text-muted">Cochez cette case pour envoyer un email à l'utilisateur concernant la mise à jour de sa demande.</small>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Informations du document</h6>
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
                                <p class="text-sm mb-2">{{ Str::limit($request->document->description, 100) }}</p>
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
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Informations du citoyen</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-3x text-info"></i>
                        </div>
                        <div>
                            <h5>{{ $request->user->nom }} {{ $request->user->prenoms }}</h5>
                            <p class="text-sm mb-0">Email: {{ $request->user->email }}</p>
                            @if($request->user->phone)
                            <p class="text-sm mb-0">Téléphone: {{ $request->user->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
