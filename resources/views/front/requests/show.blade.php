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
        
        <!-- Section spécifique pour les demandes en brouillon -->
        @if($request->status == 'draft')
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Demande en brouillon
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i> 
                    Cette demande est en mode brouillon. Vous pouvez la soumettre ou la supprimer.
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Statut : Brouillon</h5>
                        <p class="mb-0">Vous pouvez confirmer cette demande pour qu'elle soit traitée par nos agents, ou la supprimer si vous ne souhaitez pas la soumettre.</p>
                    </div>
                    
                    <div>
                        <span class="badge bg-warning fs-6 p-3">
                            <i class="fas fa-pencil-alt me-1"></i>
                            Brouillon
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 d-flex justify-content-between">
                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                    
                    <form action="{{ route('requests.confirm', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Confirmer et soumettre
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Section de statut avec gestion des paiements -->
        @if($request->status != 'draft')
            @if($request->status == 'approved' && $request->requiresPayment() && !$request->hasSuccessfulPayment())
                <!-- Demande approuvée - Paiement requis -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            Paiement requis
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> 
                            Félicitations ! Votre demande a été approuvée. Veuillez procéder au paiement pour finaliser le traitement.
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Statut : Approuvée - Paiement en attente</h5>
                                <p class="mb-0">
                                    <strong>Montant à payer :</strong> 25 000 FCFA<br>
                                    <small class="text-muted">Cliquez sur "Procéder au paiement" pour effectuer votre paiement en ligne.</small>
                                </p>
                            </div>
                            
                            <div>
                                <span class="badge bg-warning fs-6 p-3">
                                    <i class="fas fa-credit-card me-1"></i>
                                    Paiement requis
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('payments.show', $request->id) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>
                                Procéder au paiement
                            </a>
                        </div>
                    </div>
                </div>
            @elseif($request->hasSuccessfulPayment())
                <!-- Demande payée - En traitement -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Paiement effectué - En traitement
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> 
                            Votre paiement a été effectué avec succès ! Votre demande est maintenant en cours de traitement par nos services.
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Statut : {{ ucfirst($request->status) }} - Payée</h5>
                                <p class="mb-0">Nos agents traitent actuellement votre demande. Vous recevrez une notification dès qu'elle sera prête.</p>
                            </div>
                            
                            <div>
                                <span class="badge bg-success fs-6 p-3">
                                    <i class="fas fa-cogs me-1"></i>
                                    En traitement
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Autres statuts -->
                <div class="card shadow mt-4">
                    <div class="card-header {{ $request->status == 'approved' ? 'bg-success' : ($request->status == 'rejected' ? 'bg-danger' : 'bg-warning') }} text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-{{ $request->status == 'approved' ? 'check-circle' : ($request->status == 'rejected' ? 'times-circle' : 'clock') }} me-2"></i>
                            Statut de la demande
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                            <i class="fas fa-info-circle me-2"></i> 
                            @if($request->status == 'pending')
                                Votre demande a été soumise avec succès et est en attente d'examen par nos agents.
                            @elseif($request->status == 'approved')
                                Votre demande a été approuvée avec succès !
                            @elseif($request->status == 'rejected')
                                Malheureusement, votre demande a été rejetée.
                            @else
                                Votre demande est en cours de traitement.
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Statut : {{ ucfirst($request->status) }}</h5>
                                <p class="mb-0">
                                    @if($request->status == 'pending')
                                        Nos agents examinent actuellement votre demande.
                                    @elseif($request->status == 'approved')
                                        Votre demande a été approuvée !
                                    @elseif($request->status == 'rejected')
                                        @if($request->admin_comments)
                                            Raison: {{ $request->admin_comments }}
                                        @endif
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }} fs-6 p-3">
                                    <i class="fas fa-{{ $request->status == 'approved' ? 'check' : ($request->status == 'rejected' ? 'times' : 'clock') }} me-1"></i>
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
