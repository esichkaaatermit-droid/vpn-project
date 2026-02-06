<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreenButton extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'screen_id',
        'text',
        'next_screen_key',
        'order',
        'row',
    ];

    /**
     * Get the screen that owns the button.
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Get the next screen for this button.
     */
    public function nextScreen(): ?Screen
    {
        if (!$this->next_screen_key) {
            return null;
        }

        return Screen::findByKey($this->next_screen_key);
    }

    /**
     * Check if button leads to another screen.
     */
    public function hasNextScreen(): bool
    {
        return !empty($this->next_screen_key);
    }
}
