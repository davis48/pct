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
        'reason',
        'urgency',
        'contact_preference',
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
        'additional_data',
        'uploaded_document',
        'processed_document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'additional_data' => 'array',
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
     * Obtenir le titre du document demandé de manière intelligente
     * Prend en compte les formulaires interactifs et les demandes classiques
     */
    public function getDocumentTitle()
    {
        // Si la demande a un document associé (demandes classiques)
        if ($this->document) {
            return $this->document->title;
        }

        // Pour les formulaires interactifs, utiliser les données additionnelles
        if ($this->additional_data) {
            $additionalData = json_decode($this->additional_data, true);
            
            // Si c'est un formulaire interactif, utiliser le form_type
            if (isset($additionalData['form_type'])) {
                return $this->getFormTypeTitle($additionalData['form_type']);
            }
        }

        // Fallback : utiliser le type de demande
        return $this->getTypeTitle($this->type);
    }

    /**
     * Obtenir le titre selon le form_type des formulaires interactifs
     */
    private function getFormTypeTitle($formType)
    {
        $titles = [
            'certificat-mariage' => 'Certificat de Mariage',
            'certificat-celibat' => 'Certificat de Célibat',
            'extrait-naissance' => 'Extrait de Naissance',
            'certificat-deces' => 'Certificat de Décès',
            'attestation-domicile' => 'Attestation de Domicile',
            'legalisation' => 'Légalisation de Document'
        ];

        return $titles[$formType] ?? ucfirst(str_replace('-', ' ', $formType));
    }

    /**
     * Obtenir le titre selon le type de demande
     */
    private function getTypeTitle($type)
    {
        $titles = [
            'mariage' => 'Certificat de Mariage',
            'certificat' => 'Certificat',
            'extrait-acte' => 'Extrait d\'Acte',
            'attestation' => 'Attestation',
            'legalisation' => 'Légalisation de Document',
            'autre' => 'Autre Document'
        ];

        return $titles[$type] ?? ucfirst($type);
    }

    /**
     * Obtenir la catégorie du document demandé
     */
    public function getDocumentCategory()
    {
        // Si la demande a un document associé (demandes classiques)
        if ($this->document) {
            return $this->document->category;
        }

        // Pour les formulaires interactifs, déduire la catégorie
        if ($this->additional_data) {
            $additionalData = json_decode($this->additional_data, true);
            
            if (isset($additionalData['form_type'])) {
                return $this->getFormTypeCategory($additionalData['form_type']);
            }
        }

        // Fallback : déduire de la catégorie selon le type
        return $this->getTypeCategory($this->type);
    }

    /**
     * Obtenir la catégorie selon le form_type
     */
    private function getFormTypeCategory($formType)
    {
        $categories = [
            'certificat-mariage' => 'État civil',
            'certificat-celibat' => 'État civil',
            'extrait-naissance' => 'État civil',
            'certificat-deces' => 'État civil',
            'attestation-domicile' => 'Résidence',
            'legalisation' => 'Légalisation'
        ];

        return $categories[$formType] ?? 'Autre';
    }

    /**
     * Obtenir la catégorie selon le type
     */
    private function getTypeCategory($type)
    {
        $categories = [
            'mariage' => 'État civil',
            'certificat' => 'État civil',
            'extrait-acte' => 'État civil',
            'attestation' => 'Résidence',
            'legalisation' => 'Légalisation',
            'autre' => 'Autre'
        ];

        return $categories[$type] ?? 'Autre';
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
     * Vérifier si le document de cette demande est téléchargeable
     */
    public function isDownloadable(): bool
    {
        $downloadableStatuses = ['approved', 'processed', 'ready', 'completed'];
        return in_array($this->status, $downloadableStatuses) || 
               ($this->status == 'in_progress' && $this->processed_by);
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
     * Obtenir le libellé du statut pour l'affichage
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'En attente',
            'en_attente' => 'En attente',
            'in_progress' => 'En cours',
            'approved' => 'Approuvée',
            'processed' => 'Prêt',
            'ready' => 'Prêt',
            'completed' => 'Terminé',
            'rejected' => 'Rejetée',
            'draft' => 'Brouillon'
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Obtenir la classe CSS pour le statut
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'processed' => 'bg-green-100 text-green-800',
            'ready' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'draft' => 'bg-gray-100 text-gray-800'
        ];
        
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
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
