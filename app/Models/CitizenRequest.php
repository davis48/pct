<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenRequest extends Model
{
    use HasFactory;

    /**
     * Constants pour les statuts
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'en_attente'; // Changé de 'pending' à 'en_attente' pour correspondre à l'interface
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Constants pour les statuts de paiement
     */
    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'document_id',
        'type',
        'description',
        'status',
        'admin_comments',
        'additional_requirements',
        'attachments',
        'reference_number',
        'assigned_to',
        'processed_by',
        'processed_at',
        'is_read',
        'payment_status',
        'payment_required',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->reference_number)) {
                $request->reference_number = static::generateReferenceNumber();
            }
        });
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Écouter les changements de statut pour envoyer des notifications
        static::updating(function ($request) {
            if ($request->isDirty('status')) {
                $oldStatus = $request->getOriginal('status');
                $newStatus = $request->status;
                
                // Envoyer la notification de changement de statut
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendStatusChangeNotification($request, $oldStatus, $newStatus);
            }
            
            // Notification lors de l'assignation d'un agent
            if ($request->isDirty('assigned_to') && $request->assigned_to) {
                $agent = User::find($request->assigned_to);
                if ($agent) {
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->sendProcessingStartedNotification($request, $agent);
                }
            }
            
            // Notification lors de la finalisation du paiement
            if ($request->isDirty('payment_status') && $request->payment_status === 'paid') {
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendPaymentCompletedNotification($request);
            }
        });
        
        // Notification lors de la création d'une nouvelle demande
        static::created(function ($request) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendRequestSubmittedNotification($request);
        });
    }

    /**
     * Generate a unique reference number.
     */
    protected static function generateReferenceNumber()
    {
        do {
            $referenceNumber = 'REQ-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (static::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }

    /**
     * Get the user that owns the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document associated with the request
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the agent assigned to this request
     */
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the agent who processed this request
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get all attachments for this request
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get citizen attachments only
     */
    public function citizenAttachments()
    {
        return $this->hasMany(Attachment::class)->where('type', 'citizen');
    }

    /**
     * Get agent attachments only
     */
    public function agentAttachments()
    {
        return $this->hasMany(Attachment::class)->where('type', 'agent');
    }

    /**
     * Get the payments for the citizen request.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the latest payment for the citizen request.
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    /**
     * Check if the request has a successful payment.
     */
    public function hasSuccessfulPayment()
    {
        return $this->payments()->where('status', Payment::STATUS_COMPLETED)->exists();
    }

    /**
     * Check if the request has a pending payment.
     */
    public function hasPendingPayment()
    {
        return $this->payments()->where('status', Payment::STATUS_PENDING)->exists();
    }

    /**
     * Check if payment is required for this request.
     */
    public function requiresPayment()
    {
        return $this->payment_required ?? true;
    }

    /**
     * Check if the request is in draft status (not submitted yet)
     */
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the request is properly submitted (payment completed)
     */
    public function isSubmitted()
    {
        return !$this->isDraft() && ($this->payment_status === self::PAYMENT_STATUS_PAID || !$this->requiresPayment());
    }

    /**
     * Check if the request can be processed (payment is complete or not required)
     */
    public function canBeProcessed()
    {
        // Une demande peut être traitée si :
        // 1. Le paiement est marqué comme payé, OU
        // 2. Le paiement n'est pas requis pour cette demande
        // ET le statut permet le traitement
        $paymentOk = ($this->payment_status === self::PAYMENT_STATUS_PAID || !$this->requiresPayment());
        
        // Gérer les statuts en BD qui peuvent être 'pending' ou 'en_attente'
        $statusOk = in_array($this->status, [
            self::STATUS_PENDING,    // 'en_attente'
            'pending',               // Statut legacy en BD
            self::STATUS_IN_PROGRESS // 'in_progress'
        ]);
        
        return $paymentOk && $statusOk;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_PENDING => 'En attente',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_APPROVED => 'Approuvée',
            self::STATUS_REJECTED => 'Rejetée',
            default => 'Inconnu'
        };
    }

    /**
     * Get formatted title for the request based on type
     */
    public function getTitleAttribute()
    {
        return ucfirst($this->type) ?? 'Type non spécifié';
    }

    /**
     * Get formatted type label
     */
    public function getTypeLabelAttribute()
    {
        return match(strtolower($this->type)) {
            'certificate' => 'Certificat',
            'authorization' => 'Autorisation',
            'complaint' => 'Plainte',
            'license' => 'Licence',
            'permit' => 'Permis',
            'birth_certificate' => 'Acte de naissance',
            'death_certificate' => 'Acte de décès',
            'marriage_certificate' => 'Acte de mariage',
            'residence_certificate' => 'Certificat de résidence',
            'identity_card' => 'Carte d\'identité',
            'passport' => 'Passeport',
            default => ucfirst($this->type) ?? 'Type non spécifié'
        };
    }
}
