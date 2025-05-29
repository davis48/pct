<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'citizen_request_id',
        'amount',
        'reference',
        'phone_number',
        'provider',
        'status',
        'transaction_id',
        'payment_method',
        'notes',
        'callback_data',
        'paid_at',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'callback_data' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Les statuts possibles pour un paiement
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Les méthodes de paiement disponibles
     */
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_CARD = 'card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';

    /**
     * Les fournisseurs de paiement mobile money
     */
    const PROVIDER_CINET = 'cinet';
    const PROVIDER_MOOV = 'moov';
    const PROVIDER_MTN = 'mtn';
    const PROVIDER_ORANGE = 'orange';
    const PROVIDER_WAVE = 'wave';

    /**
     * Génère une référence unique pour le paiement.
     *
     * @return string
     */
    public static function generateReference()
    {
        return 'PAY-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Get the citizen request that owns the payment.
     */
    public function citizenRequest()
    {
        return $this->belongsTo(CitizenRequest::class);
    }

    /**
     * Scope pour les paiements en attente.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope pour les paiements complétés.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope pour les paiements échoués.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Vérifie si le paiement est en attente.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifie si le paiement est complété.
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifie si le paiement a échoué.
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Vérifie si le paiement a été annulé.
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
