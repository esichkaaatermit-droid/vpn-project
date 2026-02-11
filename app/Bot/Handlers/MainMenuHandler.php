<?php

namespace App\Bot\Handlers;

use App\Bot\Handlers\Concerns\BuildsButtons;
use App\Models\Screen;

/**
 * Обработчик главного меню.
 * 
 * Возвращает стандартный текст и кнопки экрана.
 * Без дополнительной логики — просто отображение.
 */
class MainMenuHandler implements HandlerInterface
{
    use BuildsButtons;

    /**
     * Обработать экран главного меню.
     */
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        return [
            'text' => $screen->text,
            'buttons' => $this->buildButtons($screen),
        ];
    }
}
