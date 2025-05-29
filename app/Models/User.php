<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les rôles disponibles dans le système
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_AGENT = 'agent';
    const ROLE_CITIZEN = 'citizen';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenoms',
        'date_naissance',
        'genre',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
        ];
    }

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is an agent
     *
     * @return bool
     */
    public function isAgent()
    {
        return $this->role === self::ROLE_AGENT;
    }

    /**
     * Check if user is a citizen
     *
     * @return bool
     */
    public function isCitizen()
    {
        return $this->role === self::ROLE_CITIZEN;
    }

    /**
     * Check if user has one of the given roles
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        return in_array($this->role, $roles);
    }

    /**
     * Check if user has permission to perform an action
     *
     * @param string $action
     * @return bool
     */
    public function hasPermission($action)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isAgent()) {
            $agentPermissions = [
                'view-requests',
                'process-requests',
                'update-requests',
                'reject-requests',
                'view-citizens',
            ];

            return in_array($action, $agentPermissions);
        }

        return false;
    }

    /**
     * Get all requests made by the user
     */
    public function requests()
    {
        return $this->hasMany(CitizenRequest::class);
    }
}
