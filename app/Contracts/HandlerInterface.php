<?php

namespace App\Contracts;

use App\Models\User;

/**
 * Интерфейс для обработчиков экранов бота.
 * 
 * Обработчики добавляют динамическую логику к экранам:
 * - Загрузка данных пользователя
 * - Формирование динамического контента
 * - Выполнение действий (создание платежа, генерация конфига и т.д.)
 */
interface HandlerInterface
{
    /**
     * Выполнить обработчик.
     *
     * @param int $chatId Telegram chat ID
     * @param User|null $user Модель пользователя (если авторизован)
     * @param array $context Дополнительный контекст (screen_key, etc.)
     * @return string Дополнительный текст для добавления к экрану
     */
    public function handle(int $chatId, ?User $user, array $context = []): string;

    /**
     * Получить ID обработчика.
     *
     * @return string
     */
    public static function getId(): string;
}
