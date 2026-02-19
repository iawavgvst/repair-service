<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    /**
     * Назначенные заявки мастеру
     *
     * @return HasMany
     */
    public function assignedRequests(): HasMany
    {
        return $this->hasMany(RepairRequest::class, 'assigned_to');
    }

    /**
     * Проверка, является ли пользователь диспетчером
     *
     * @return bool
     */
    public function isDispatcher(): bool
    {
        return $this->role === 'dispatcher';
    }

    /**
     * Проверка, является ли пользователь мастером
     *
     * @return bool
     */
    public function isMaster(): bool
    {
        return $this->role === 'master';
    }
}
