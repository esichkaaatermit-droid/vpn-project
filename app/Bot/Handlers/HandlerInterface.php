<?php

namespace App\Bot\Handlers;

use App\Models\Screen;

/**
 * Интерфейс для обработчиков экранов бота.
 * 
 * Обработчики добавляют динамическую логику к экранам:
 * - Загрузка данных пользователя
 * - Формирование динамического контента
 * - Выполнение действий
 */
interface HandlerInterface
{
    /**
     * Обработать экран.
     * 
     * @param Screen $screen Модель экрана
     * @param int $chatId Telegram chat ID
     * @param array $update Дополнительные данные (user, user_state, etc.)
     * @return array ['text' => '...', 'buttons' => [...]]
     */
    public function handle(Screen $screen, int $chatId, array $update): array;
}
