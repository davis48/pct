<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenRequest extends Model
{
    use HasFactory;

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
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            default => 'Inconnu'
        };
    }
}
