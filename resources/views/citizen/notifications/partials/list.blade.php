@if($notifications->count() > 0)
    @foreach($notifications as $notification)
        <div class="card notification-item {{ $notification->is_read ? 'notification-read' : 'notification-unread' }} mb-3 cursor-pointer" 
             data-notification-id="{{ $notification->id }}"
             onclick="toggleNotificationDetails({{ $notification->id }})">
            <div class="card-body p-4">
                <div class="row align-items-start">
                    <div class="col-auto">
                        @php
                            $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                            $iconClass = 'fas fa-bell text-primary';
                            $bgClass = 'bg-primary';
                            
                            if (isset($data['type'])) {
                                switch($data['type']) {
                                    case 'status_change':
                                        if (isset($data['new_status'])) {
                                            switch($data['new_status']) {
                                                case 'pending':
                                                    $iconClass = 'fas fa-clock text-info';
                                                    $bgClass = 'bg-info';
                                                    break;
                                                case 'in_progress':
                                                    $iconClass = 'fas fa-cogs text-warning';
                                                    $bgClass = 'bg-warning';
                                                    break;
                                                case 'approved':
                                                    $iconClass = 'fas fa-check-circle text-success';
                                                    $bgClass = 'bg-success';
                                                    break;
                                                case 'rejected':
                                                    $iconClass = 'fas fa-times-circle text-danger';
                                                    $bgClass = 'bg-danger';
                                                    break;
                                                case 'completed':
                                                    $iconClass = 'fas fa-flag-checkered text-success';
                                                    $bgClass = 'bg-success';
                                                    break;
                                            }
                                        } else {
                                            $iconClass = 'fas fa-sync text-warning';
                                            $bgClass = 'bg-warning';
                                        }
                                        break;
                                    case 'request_submitted':
                                        $iconClass = 'fas fa-paper-plane text-info';
                                        $bgClass = 'bg-info';
                                        break;
                                    case 'payment_completed':
                                        $iconClass = 'fas fa-credit-card text-success';
                                        $bgClass = 'bg-success';
                                        break;
                                    case 'processing_started':
                                        $iconClass = 'fas fa-play-circle text-info';
                                        $bgClass = 'bg-info';
                                        break;
                                    case 'document_ready':
                                        $iconClass = 'fas fa-file-check text-success';
                                        $bgClass = 'bg-success';
                                        break;
                                    default:
                                        $iconClass = 'fas fa-info-circle text-primary';
                                        break;
                                }
                            }
                        @endphp
                        <div class="notification-icon {{ $bgClass }} bg-opacity-10 p-3 rounded-circle">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="notification-title mb-0 fw-bold">
                                {{ $notification->title }}
                            </h6>
                            <div class="d-flex align-items-center">
                                @if(!$notification->is_read)
                                    <span class="badge bg-primary bg-opacity-20 text-primary me-2">Nouveau</span>
                                @endif
                                @if(isset($data['priority']) && $data['priority'] === 'high')
                                    <span class="badge bg-danger me-2">Urgent</span>
                                @endif
                                <i class="fas fa-chevron-down notification-expand-icon text-muted" id="icon-{{ $notification->id }}"></i>
                            </div>
                        </div>
                        
                        <div class="notification-preview">
                            <p class="notification-message text-dark mb-3">
                                {{ $notification->message }}
                            </p>
                            
                            <!-- Informations rapides visibles -->
                            <div class="row text-sm mb-3">
                                @if(isset($data['request_id']))
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-hashtag me-2 text-primary"></i>
                                        <strong>Demande:</strong>&nbsp;#{{ $data['request_id'] }}
                                    </small>
                                </div>
                                @endif
                                
                                @if(isset($data['document_name']))
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-file-alt me-2 text-info"></i>
                                        <strong>Document:</strong>&nbsp;{{ $data['document_name'] }}
                                    </small>
                                </div>
                                @endif
                                
                                @if(isset($data['new_status']))
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-flag me-2 text-warning"></i>
                                        <strong>Statut:</strong>&nbsp;
                                        @php
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'in_progress' => 'En cours',
                                                'approved' => 'Approuvée',
                                                'rejected' => 'Rejetée',
                                                'completed' => 'Terminée'
                                            ];
                                        @endphp
                                        <span class="badge bg-secondary">{{ $statusLabels[$data['new_status']] ?? $data['new_status'] }}</span>
                                    </small>
                                </div>
                                @endif
                                
                                @if(isset($data['amount']) && $data['amount'] > 0)
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                        <strong>Montant:</strong>&nbsp;{{ number_format($data['amount'], 0, ',', ' ') }} FCFA
                                    </small>
                                </div>
                                @endif
                                
                                @if(isset($data['estimated_completion']))
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-calendar me-2 text-info"></i>
                                        <strong>Estimé:</strong>&nbsp;{{ $data['estimated_completion'] }}
                                    </small>
                                </div>
                                @endif
                                
                                <div class="col-md-4 mb-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="fas fa-clock me-2 text-secondary"></i>
                                        <strong>Reçu:</strong>&nbsp;{{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Détails étendus (masqués par défaut) -->
                        <div class="notification-details" id="details-{{ $notification->id }}" style="display: none;">
                            <hr class="my-3">
                            
                            @if(isset($data['details']))
                            <div class="alert alert-light border-start border-primary border-4">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Informations détaillées
                                </h6>
                                <p class="mb-0">{{ $data['details'] }}</p>
                            </div>
                            @endif
                            
                            <div class="row">
                                @if(isset($data['next_step']))
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        Prochaine étape
                                    </h6>
                                    <p class="mb-0">{{ $data['next_step'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($data['agent_assigned']) && $data['agent_assigned'])
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-warning">
                                        <i class="fas fa-user me-1"></i>
                                        Agent assigné
                                    </h6>
                                    <p class="mb-0">{{ $data['agent_assigned'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($data['processed_by']) && $data['processed_by'])
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-info">
                                        <i class="fas fa-user-check me-1"></i>
                                        Traité par
                                    </h6>
                                    <p class="mb-0">{{ $data['processed_by'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($data['reason']) && $data['reason'])
                                <div class="col-12 mb-3">
                                    <h6 class="text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Motif
                                    </h6>
                                    <div class="alert alert-danger">
                                        {{ $data['reason'] }}
                                    </div>
                                </div>
                                @endif
                                
                                @if(isset($data['pickup_location']))
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-success">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Lieu de retrait
                                    </h6>
                                    <p class="mb-0">{{ $data['pickup_location'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($data['pickup_hours']))
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-info">
                                        <i class="fas fa-clock me-1"></i>
                                        Horaires
                                    </h6>
                                    <p class="mb-0">{{ $data['pickup_hours'] }}</p>
                                </div>
                                @endif
                                
                                @if(isset($data['actions_required']))
                                <div class="col-12 mb-3">
                                    <h6 class="text-warning">
                                        <i class="fas fa-tasks me-1"></i>
                                        Action requise
                                    </h6>
                                    <div class="alert alert-warning">
                                        {{ $data['actions_required'] }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-3 pt-3 border-top d-flex flex-wrap gap-2">
                                @if(isset($data['request_id']))
                                <a href="{{ route('citizen.request.show', $data['request_id']) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Voir la demande
                                </a>
                                @endif
                                
                                @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-check me-1"></i>
                                    Marquer comme lue
                                </button>
                                @endif
                                
                                <button onclick="deleteNotification({{ $notification->id }})" 
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    @endforeach
@else
    <div class="empty-state text-center py-5">
        <div class="mb-4">
            <i class="fas fa-bell-slash fa-4x text-muted"></i>
        </div>
        <h5 class="text-muted mb-3">Aucune notification trouvée</h5>
        <p class="text-muted mb-4">
            @if(request()->hasAny(['search', 'status', 'period', 'date_from', 'date_to']))
                Aucune notification ne correspond à vos critères de recherche.<br>
                Essayez de modifier vos filtres ou de supprimer certains critères.
            @else
                Vous n'avez pas encore de notifications.<br>
                Les nouvelles notifications apparaîtront ici.
            @endif
        </p>
        
        @if(request()->hasAny(['search', 'status', 'period', 'date_from', 'date_to']))
            <a href="{{ route('citizen.notifications.center') }}" class="btn btn-outline-primary">
                <i class="fas fa-times me-2"></i>Réinitialiser les filtres
            </a>
        @endif
    </div>
@endif

<!-- Indicateur de chargement -->
<div id="loadingIndicator" class="text-center py-3" style="display: none;">
    <div class="spinner-border spinner-border-sm text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
    <span class="ms-2">Chargement...</span>
</div>
