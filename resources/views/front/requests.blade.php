@extends('layouts.front.app')
@section('content')
      <section class="py-5">
        <div class="container">
            <h1 class="mb-4">Nouvelle demande</h1>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form id="requestForm" method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="documentType" class="form-label">Type de document</label>
                            <select class="form-select" id="documentType" name="type" required>
                                <option value="" selected disabled>Choisissez un document</option>
                                <option value="attestation">Attestation de domicile</option>
                                <option value="legalisation">Legalisation de document</option>
                                <option value="mariage">Demande de certificat de mariage</option>
                                <option value="extrait-acte">Extrait d'acte de naissance</option>
                                <option value="certificat">Certificat de célibat</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="document_id" class="form-label">Document requis</label>
                            <select class="form-select" id="document_id" name="document_id">
                                <option value="" selected>Aucun document spécifique</option>
                                @foreach($documents as $document)
                                    <option value="{{ $document->id }}">{{ $document->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description de votre demande</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pièces jointes</label>
                            <input type="file" class="form-control" name="attachments[]" multiple>
                            <div class="form-text">Vous pouvez joindre jusqu'à 5 fichiers (PDF, JPG, PNG)</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
