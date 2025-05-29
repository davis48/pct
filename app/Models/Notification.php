<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include notifications of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Get the notification icon based on type.
     */
    public function getIconAttribute()
    {
        $icons = [
            'success' => 'fas fa-check-circle text-green-500',
            'error' => 'fas fa-exclamation-triangle text-red-500',
            'warning' => 'fas fa-exclamation-circle text-yellow-500',
            'info' => 'fas fa-info-circle text-blue-500',
        ];

        return $icons[$this->type] ?? 'fas fa-bell text-gray-500';
    }

    /**
     * Get the notification color class based on type.
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'success' => 'bg-green-50 border-green-200',
            'error' => 'bg-red-50 border-red-200',
            'warning' => 'bg-yellow-50 border-yellow-200',
            'info' => 'bg-blue-50 border-blue-200',
        ];

        return $colors[$this->type] ?? 'bg-gray-50 border-gray-200';
    }
}
