@extends('layouts.admin.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Ajouter un nouveau document</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Titre du document</label>
                                    <input class="form-control @error('title') is-invalid @enderror" type="text" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-control-label">Catégorie</label>
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="carte_nationale" {{ old('category') == 'carte_nationale' ? 'selected' : '' }}>Carte Nationale d'Identité</option>
                                        <option value="passeport" {{ old('category') == 'passeport' ? 'selected' : '' }}>Passeport</option>
                                        <option value="certificat" {{ old('category') == 'certificat' ? 'selected' : '' }}>Certificat</option>
                                        <option value="attestation" {{ old('category') == 'attestation' ? 'selected' : '' }}>Attestation</option>
                                        <option value="permis" {{ old('category') == 'permis' ? 'selected' : '' }}>Permis de conduire</option>
                                        <option value="autre" {{ old('category') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file" class="form-control-label">Fichier du document</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                                    <small class="form-text text-muted">Formats acceptés: PDF, DOC, DOCX (max 2 Mo)</small>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_public">Document accessible au public</label>
                                </div>
                                <small class="form-text text-muted">Cochez cette case si le document doit être visible par tous les utilisateurs sans authentification.</small>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
