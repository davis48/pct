<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Téléchargement de Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-file-download me-2"></i>
                            Test - Téléchargement de Documents
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Page de test</strong> - Cette page permet de tester la génération et le téléchargement des documents PDF.
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <h5 class="mb-3">Demandes approuvées disponibles pour téléchargement :</h5>

                        @if($approvedRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Référence</th>
                                            <th>Type</th>
                                            <th>Demandeur</th>
                                            <th>Date d'approbation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approvedRequests as $request)
                                            <tr>
                                                <td>
                                                    <code>{{ $request->reference_number ?: 'REF-' . $request->id }}</code>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucfirst($request->type) }}</span>
                                                </td>
                                                <td>{{ $request->user->name }}</td>
                                                <td>{{ $request->updated_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('documents.preview', $request) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           target="_blank"
                                                           title="Prévisualiser">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('documents.download', $request) }}" 
                                                           class="btn btn-sm btn-success"
                                                           title="Télécharger">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Aucune demande approuvée trouvée. Assurez-vous qu'il y ait des demandes avec le statut "approved" dans la base de données.
                            </div>
                        @endif

                        <hr>

                        <h5 class="mb-3">Templates de documents disponibles :</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-home me-2 text-primary"></i>
                                            Attestation de Domicile
                                        </h6>
                                        <p class="card-text text-muted">Certificat attestant du domicile du citoyen</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-baby me-2 text-success"></i>
                                            Extrait d'Acte de Naissance
                                        </h6>
                                        <p class="card-text text-muted">Extrait officiel d'acte de naissance</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-heart me-2 text-danger"></i>
                                            Certificat de Célibat
                                        </h6>
                                        <p class="card-text text-muted">Certificat attestant du statut de célibataire</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-rings-wedding me-2 text-warning"></i>
                                            Certificat de Mariage
                                        </h6>
                                        <p class="card-text text-muted">Certificat officiel de mariage</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-stamp me-2 text-info"></i>
                                            Légalisation
                                        </h6>
                                        <p class="card-text text-muted">Légalisation de documents officiels</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-file-alt me-2 text-secondary"></i>
                                            Document Générique
                                        </h6>
                                        <p class="card-text text-muted">Template pour autres types de documents</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ url('/') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
