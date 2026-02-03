<?php

namespace App\Bot\Handlers;

use App\Models\Screen;

/**
 * Обработчик главного меню.
 * 
 * Возвращает стандартный текст и кнопки экрана.
 * Без дополнительной логики — просто отображение.
 */
class MainMenuHandler implements HandlerInterface
{
    /**
     * Обработать экран главного меню.
     */
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        // Простой обработчик — возвращаем текст и кнопки из экрана
        $buttons = [];
        
        foreach ($screen->buttons as $button) {
            $buttons[] = [
                'text' => $button->text,
                'callback_data' => $button->next_screen_key ?? 'noop',
            ];
        }

        return [
            'text' => $screen->text,
            'buttons' => $buttons,
        ];
    }
}
