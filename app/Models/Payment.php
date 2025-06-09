<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    /**
     * Statuts de paiement
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Méthodes de paiement
     */
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_CARD = 'card';
    const METHOD_CASH = 'cash';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'citizen_request_id',
        'amount',
        'reference',
        'status',
        'payment_method',
        'provider',
        'phone_number',
        'transaction_id',
        'paid_at',
        'notes'
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Les fournisseurs de paiement mobile money
     */
    const PROVIDER_CINET = 'cinet';
    const PROVIDER_MOOV = 'moov';
    const PROVIDER_MTN = 'mtn';
    const PROVIDER_ORANGE = 'orange';
    const PROVIDER_WAVE = 'wave';

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

    /**
     * Generate a unique reference number.
     */
    protected static function generateReference()
    {
        do {
            $reference = 'PAY-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (static::where('reference', $reference)->exists());

        return $reference;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->reference)) {
                $payment->reference = static::generateReference();
            }
        });
    }
}
