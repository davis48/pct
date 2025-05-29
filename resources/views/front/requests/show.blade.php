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
                            @if(is_string($attachment))
                                <span>{{ basename($attachment) }}</span>
                                <a href="{{ asset('storage/' . $attachment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            @elseif(is_array($attachment) && isset($attachment['name']))
                                <span>{{ $attachment['name'] }}</span>
                                <a href="{{ asset('storage/' . ($attachment['path'] ?? '')) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                            @else
                                <span>Pièce jointe</span>
                                <a href="#" class="btn btn-sm btn-outline-secondary disabled">
                                    <i class="fas fa-exclamation-circle me-1"></i>Format non supporté
                                </a>
                            @endif
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
        
        <!-- Section de paiement -->
        @if($request->requiresPayment() && ($request->payment_status === 'unpaid' || $request->payment_status === 'cancelled'))
        <div class="card shadow mt-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Paiement requis</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Votre demande nécessite un paiement pour être traitée.
                </div>
                
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h5>Frais de traitement</h5>
                        <p>Pour finaliser votre demande de document, veuillez procéder au paiement des frais de traitement. Une fois le paiement effectué, votre demande sera transmise à nos services pour traitement.</p>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                            <a href="{{ route('payments.show', $request) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i> Procéder au paiement
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="bg-light p-4 rounded">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Frais de traitement</span>
                                <span>{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Frais de service</span>
                                <span>0 FCFA</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total à payer</span>
                                <span class="text-primary">{{ number_format(\App\Services\PaymentService::getPriceForDocumentType($request->type), 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($request->payment_status === 'pending')
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-0">Paiement en cours</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i> Votre paiement est en cours de traitement.
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Statut du paiement</h5>
                        <p>Votre paiement est en cours de traitement. Veuillez patienter ou vérifier son statut.</p>
                    </div>
                    
                    @if($request->latestPayment)
                        <a href="{{ route('payments.status', $request->latestPayment) }}" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-2"></i> Vérifier le statut
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @elseif($request->payment_status === 'paid')
        <div class="card shadow mt-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Paiement effectué</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> Le paiement pour cette demande a été effectué avec succès.
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Votre demande est en cours de traitement</h5>
                        <p>Nous avons bien reçu votre paiement et votre demande est maintenant en cours de traitement par nos services.</p>
                    </div>
                    
                    @if($request->latestPayment)
                        <a href="{{ route('payments.status', $request->latestPayment) }}" class="btn btn-outline-success">
                            <i class="fas fa-receipt me-2"></i> Voir le reçu
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
