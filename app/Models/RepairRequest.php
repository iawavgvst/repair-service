<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_name',
        'phone',
        'address',
        'problem_text',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Мастер, который выполняет заявки
     *
     * @return BelongsTo
     */
    public function assignedMaster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Только новые заявки
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    /**
     * Назначенные заявки
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAssigned(Builder $query): Builder
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Заявки в работе
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Завершенные заявки
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDone(Builder $query): Builder
    {
        return $query->where('status', 'done');
    }

    /**
     * Отмененные заявки
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCanceled(Builder $query): Builder
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Заявки для конкретного мастера
     *
     * @param Builder $query
     * @param int $masterId
     * @return Builder
     */
    public function scopeForMaster(Builder $query, int $masterId): Builder
    {
        return $query->where('assigned_to', $masterId)
            ->whereIn('status', ['assigned', 'in_progress']);
    }

    /**
     * Все активные заявки (не завершенные и не отмененные)
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['new', 'assigned', 'in_progress']);
    }

    /**
     * Проверка, можно ли назначить заявку
     *
     * @return bool
     */
    public function canBeAssigned(): bool
    {
        return $this->status === 'new';
    }

    /**
     * Проверка, можно ли взять заявку в работу
     *
     * @return bool
     */
    public function canBeTaken(): bool
    {
        return $this->status === 'assigned';
    }

    /**
     * Проверка, можно ли завершить заявку
     *
     * @return bool
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Проверка, можно ли отменить заявку
     *
     * @return bool
     */
    public function canBeCanceled(): bool
    {
        return in_array($this->status, ['new', 'assigned']);
    }
}
