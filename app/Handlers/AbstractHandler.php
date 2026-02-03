<?php

namespace App\Handlers;

use App\Contracts\HandlerInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Базовый абстрактный класс для обработчиков.
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * Выполнить обработчик с логированием.
     */
    public function handle(int $chatId, ?User $user, array $context = []): string
    {
        try {
            return $this->execute($chatId, $user, $context);
        } catch (\Throwable $e) {
            Log::error("Handler error: " . static::getId(), [
                'chat_id' => $chatId,
                'user_id' => $user?->id,
                'error' => $e->getMessage(),
            ]);
            
            return $this->getErrorMessage();
        }
    }

    /**
     * Основная логика обработчика.
     */
    abstract protected function execute(int $chatId, ?User $user, array $context): string;

    /**
     * Сообщение об ошибке.
     */
    protected function getErrorMessage(): string
    {
        return "⚠️ Произошла ошибка. Попробуйте позже.";
    }
}
