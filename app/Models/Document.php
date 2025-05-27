<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'file_path',
        'is_public',
        'status',
    ];

    /**
     * Get all requests related to this document
     */
    public function requests()
    {
        return $this->hasMany(CitizenRequest::class);
    }
}
