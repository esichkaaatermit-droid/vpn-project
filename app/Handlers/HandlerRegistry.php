<?php

namespace App\Handlers;

use App\Contracts\HandlerInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Реестр обработчиков экранов.
 * 
 * Маппит строковые handler_id из БД на классы обработчиков.
 * Все обработчики регистрируются здесь.
 */
class HandlerRegistry
{
    /**
     * Зарегистрированные обработчики.
     * 
     * @var array<string, class-string<HandlerInterface>>
     */
    protected static array $handlers = [];

    /**
     * Инстансы обработчиков (singleton pattern).
     * 
     * @var array<string, HandlerInterface>
     */
    protected static array $instances = [];

    /**
     * Зарегистрировать обработчик.
     *
     * @param class-string<HandlerInterface> $handlerClass
     */
    public static function register(string $handlerClass): void
    {
        $id = $handlerClass::getId();
        static::$handlers[$id] = $handlerClass;
    }

    /**
     * Зарегистрировать несколько обработчиков.
     *
     * @param array<class-string<HandlerInterface>> $handlerClasses
     */
    public static function registerMany(array $handlerClasses): void
    {
        foreach ($handlerClasses as $handlerClass) {
            static::register($handlerClass);
        }
    }

    /**
     * Проверить, зарегистрирован ли обработчик.
     */
    public static function has(string $handlerId): bool
    {
        return isset(static::$handlers[$handlerId]);
    }

    /**
     * Получить инстанс обработчика.
     */
    public static function get(string $handlerId): ?HandlerInterface
    {
        if (!static::has($handlerId)) {
            return null;
        }

        // Singleton pattern - создаём инстанс один раз
        if (!isset(static::$instances[$handlerId])) {
            $class = static::$handlers[$handlerId];
            static::$instances[$handlerId] = app($class);
        }

        return static::$instances[$handlerId];
    }

    /**
     * Выполнить обработчик по ID.
     */
    public static function execute(string $handlerId, int $chatId, ?User $user = null, array $context = []): string
    {
        $handler = static::get($handlerId);

        if (!$handler) {
            Log::warning("Handler not found: {$handlerId}");
            return '';
        }

        return $handler->handle($chatId, $user, $context);
    }

    /**
     * Получить все зарегистрированные обработчики.
     *
     * @return array<string, class-string<HandlerInterface>>
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
