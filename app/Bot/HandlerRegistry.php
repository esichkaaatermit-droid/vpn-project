<?php

namespace App\Bot;

use App\Bot\Handlers\HandlerInterface;
use App\Bot\Handlers\InstallConfigHandler;
use App\Bot\Handlers\MainMenuHandler;
use App\Bot\Handlers\ProfileHandler;
use App\Bot\Handlers\TariffHandler;
use App\Models\Screen;
use Illuminate\Support\Facades\Log;

/**
 * Реестр обработчиков экранов.
 * 
 * Маппит строковые handler_id из БД на классы обработчиков.
 * В БД хранятся ТОЛЬКО строковые ID (например, "profile.my"),
 * НЕ имена классов.
 */
class HandlerRegistry
{
    /**
     * Маппинг handler_id → класс обработчика.
     * 
     * Ключ — строковый ID из поля screens.handler_id
     * Значение — класс, реализующий HandlerInterface
     */
    protected static array $handlers = [
        'main.menu' => MainMenuHandler::class,
        'profile.my' => ProfileHandler::class,
        'tariffs.pricing' => TariffHandler::class,
        // Выдача конфигов при установке
        'install.android.config' => InstallConfigHandler::class,
        'install.iphone' => InstallConfigHandler::class,
        'install.appletv' => InstallConfigHandler::class,
        'install.windows' => InstallConfigHandler::class,
        'install.mac' => InstallConfigHandler::class,
    ];

    /**
     * Инстансы обработчиков (singleton).
     */
    protected static array $instances = [];

    /**
     * Зарегистрировать обработчик.
     */
    public static function register(string $handlerId, string $handlerClass): void
    {
        static::$handlers[$handlerId] = $handlerClass;
    }

    /**
     * Проверить, существует ли обработчик.
     */
    public static function has(string $handlerId): bool
    {
        return isset(static::$handlers[$handlerId]);
    }

    /**
     * Получить обработчик по ID.
     * 
     * @return HandlerInterface|null
     */
    public static function resolve(string $handlerId): ?HandlerInterface
    {
        if (!static::has($handlerId)) {
            return null;
        }

        // Singleton pattern
        if (!isset(static::$instances[$handlerId])) {
            $class = static::$handlers[$handlerId];
            static::$instances[$handlerId] = app($class);
        }

        return static::$instances[$handlerId];
    }

    /**
     * Выполнить обработчик.
     * 
     * @param string $handlerId ID обработчика
     * @param Screen $screen Модель экрана
     * @param int $chatId Telegram chat ID
     * @param array $update Дополнительные данные
     * @return array|null Результат обработки или null если обработчик не найден
     */
    public static function execute(string $handlerId, Screen $screen, int $chatId, array $update = []): ?array
    {
        $handler = static::resolve($handlerId);

        if (!$handler) {
            Log::warning("Handler not found: {$handlerId}");
            return null;
        }

        try {
            return $handler->handle($screen, $chatId, $update);
        } catch (\Throwable $e) {
            Log::error("Handler error: {$handlerId}", [
                'message' => $e->getMessage(),
                'chat_id' => $chatId,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Получить все зарегистрированные обработчики.
     */
    public static function all(): array
    {
        return static::$handlers;
    }

    /**
     * Очистить реестр (для тестов).
     */
    public static function clear(): void
    {
        static::$handlers = [];
        static::$instances = [];
    }
}
