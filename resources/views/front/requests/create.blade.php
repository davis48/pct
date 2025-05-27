@extends('layouts.front.app')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('requests.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Retour aux demandes
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white">
                <h2 class="mb-0">Nouvelle demande</h2>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de demande</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="" selected disabled>Sélectionnez un type de demande</option>
                            <option value="attestation" {{ old('type') == 'attestation' ? 'selected' : '' }}>Attestation de domicile</option>
                            <option value="legalisation" {{ old('type') == 'legalisation' ? 'selected' : '' }}>Légalisation de document</option>
                            <option value="mariage" {{ old('type') == 'mariage' ? 'selected' : '' }}>Certificat de mariage</option>
                            <option value="extrait-acte" {{ old('type') == 'extrait-acte' ? 'selected' : '' }}>Extrait d'acte de naissance</option>
                            <option value="certificat" {{ old('type') == 'certificat' ? 'selected' : '' }}>Certificat de célibat</option>
                            <option value="information" {{ old('type') == 'information' ? 'selected' : '' }}>Demande d'information</option>
                            <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="document_id" class="form-label">Document associé (optionnel)</label>
                        <select class="form-select @error('document_id') is-invalid @enderror" id="document_id" name="document_id">
                            <option value="">Aucun document spécifique</option>
                            @foreach($documents as $document)
                            <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                                {{ $document->title }}
                            </option>
                            @endforeach
                        </select>
                        @error('document_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description détaillée de votre demande</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Veuillez décrire précisément votre demande et inclure toutes les informations pertinentes.</div>
                    </div>

                    <div class="mb-4">
                        <label for="attachments" class="form-label">Pièces jointes (optionnel)</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple>
                        @error('attachments.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Formats acceptés: PDF, JPG, PNG (max: 2MB par fichier). Vous pouvez joindre jusqu'à 5 fichiers.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Soumettre la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
