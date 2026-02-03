<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель состояния пользователя в боте.
 * 
 * Хранит:
 * - chat_id — Telegram ID чата
 * - current_screen_key — ключ текущего экрана
 * - data — дополнительные данные сессии (JSON)
 * 
 * @property int $id
 * @property int $chat_id
 * @property string|null $current_screen_key
 * @property array|null $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserState extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'chat_id',
        'current_screen_key',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'chat_id' => 'integer',
        'data' => 'array',
    ];

    /**
     * Связь с текущим экраном.
     */
    public function currentScreen(): BelongsTo
    {
        return $this->belongsTo(Screen::class, 'current_screen_key', 'key');
    }

    /**
     * Найти или создать состояние по chat_id.
     */
    public static function findOrCreateByChatId(int $chatId): self
    {
        return static::firstOrCreate(
            ['chat_id' => $chatId],
            ['current_screen_key' => null, 'data' => []]
        );
    }

    /**
     * Найти состояние по chat_id.
     */
    public static function findByChatId(int $chatId): ?self
    {
        return static::where('chat_id', $chatId)->first();
    }

    /**
     * Установить текущий экран.
     */
    public function setCurrentScreen(string $screenKey): self
    {
        $this->current_screen_key = $screenKey;
        $this->save();
        
        return $this;
    }

    /**
     * Получить значение из data.
     */
    public function getData(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Установить значение в data.
     */
    public function setData(string $key, mixed $value): self
    {
        $data = $this->data ?? [];
        $data[$key] = $value;
        $this->data = $data;
        $this->save();
        
        return $this;
    }

    /**
     * Очистить данные сессии.
     */
    public function clearData(): self
    {
        $this->data = [];
        $this->save();
        
        return $this;
    }
}
