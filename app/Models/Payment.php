<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * Статусы платежа
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Время ожидания оплаты в минутах
     */
    public const EXPIRATION_MINUTES = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'tariff_key',
        'tariff_name',
        'amount',
        'status',
        'expires_at',
        'paid_at',
        'external_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Пользователь, создавший платёж.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Проверить, истёк ли срок ожидания платежа.
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_PENDING 
            && $this->expires_at->isPast();
    }

    /**
     * Проверить, ожидает ли платёж оплаты.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Проверить, оплачен ли платёж.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Отметить платёж как оплаченный.
     */
    public function markAsPaid(?string $externalId = null): bool
    {
        return $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'external_id' => $externalId,
        ]);
    }

    /**
     * Отметить платёж как просроченный.
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Отменить платёж.
     */
    public function cancel(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Создать новый платёж.
     */
    public static function createForUser(
        User $user,
        string $tariffKey,
        string $tariffName,
        float $amount
    ): self {
        return self::create([
            'user_id' => $user->id,
            'tariff_key' => $tariffKey,
            'tariff_name' => $tariffName,
            'amount' => $amount,
            'status' => self::STATUS_PENDING,
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);
    }

    /**
     * Получить все просроченные платежи.
     */
    public static function getExpiredPending()
    {
        return self::where('status', self::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->get();
    }
}
