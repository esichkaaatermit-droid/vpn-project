<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screen extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'key',
        'title',
        'text',
        'handler_id',
    ];

    /**
     * Get the buttons for the screen.
     */
    public function buttons(): HasMany
    {
        return $this->hasMany(ScreenButton::class)->orderBy('order');
    }

    /**
     * Find a screen by its key.
     */
    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Check if screen has a handler.
     */
    public function hasHandler(): bool
    {
        return !empty($this->handler_id);
    }

    /**
     * Get section from key (e.g., 'faq' from 'faq.start').
     */
    public function getSection(): ?string
    {
        if (!$this->key) {
            return null;
        }

        $parts = explode('.', $this->key);
        return $parts[0] ?? null;
    }
}
