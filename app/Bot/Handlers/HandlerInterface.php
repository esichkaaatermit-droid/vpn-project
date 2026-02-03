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
 * - Отправка файлов и изображений
 */
interface HandlerInterface
{
    /**
     * Обработать экран.
     * 
     * @param Screen $screen Модель экрана
     * @param int $chatId Telegram chat ID
     * @param array $update Дополнительные данные (user, user_state, etc.)
     * @return array Возможные ключи:
     *   - 'text'     => string  Текст сообщения (HTML)
     *   - 'buttons'  => array   Массив кнопок [['text' => '...', 'callback_data' => '...']]
     *   - 'photo'    => string  URL или file_id изображения (опционально)
     *   - 'document' => string  URL, file_id или путь к файлу (опционально)
     */
    public function handle(Screen $screen, int $chatId, array $update): array;
}
