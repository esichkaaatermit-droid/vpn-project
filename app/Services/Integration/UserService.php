<?php

namespace App\Services\Integration;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с пользователями.
 * 
 * Отвечает за:
 * - Получение профиля пользователя
 * - Управление подписками
 * - Интеграция с внешним API (в будущем)
 */
class UserService
{
    /**
     * Найти или создать пользователя по Telegram ID.
     */
    public function findOrCreateByTelegramId(int $telegramId, ?string $username = null, ?string $name = null): User
    {
        return User::findOrCreateByTelegramId($telegramId, $username, $name);
    }

    /**
     * Найти пользователя по Telegram ID.
     */
    public function findByTelegramId(int $telegramId): ?User
    {
        return User::findByTelegramId($telegramId);
    }

    /**
     * Получить профиль пользователя.
     * 
     * TODO: Заменить на реальный API-запрос к backend
     * 
     * @param string $email Email пользователя
     * @return array Данные профиля
     */
    public function getUserProfile(string $email): array
    {
        // TODO: Заменить на реальный API-запрос к backend
        // Пример будущего кода:
        // $response = Http::get(config('services.backend.url') . '/api/users/profile', [
        //     'email' => $email
        // ]);
        // return $response->json();

        Log::debug("UserService::getUserProfile called", ['email' => $email]);

        // Заглушка — возвращаем тестовые данные
        return [
            'email' => $email,
            'tariff' => 'Start',
            'expires_at' => '2026-12-31',
            'devices_used' => 2,
            'devices_limit' => 5,
        ];
    }

    /**
     * Получить информацию о подписке пользователя.
     * 
     * TODO: Заменить на реальный API-запрос
     */
    public function getSubscriptionInfo(User $user): ?array
    {
        // TODO: Заменить на реальный API-запрос
        
        if (!$user->email) {
            return null;
        }

        $profile = $this->getUserProfile($user->email);
        
        return [
            'tariff_name' => $profile['tariff'],
            'expires_at' => $profile['expires_at'],
            'devices_used' => $profile['devices_used'],
            'devices_limit' => $profile['devices_limit'],
            'is_active' => strtotime($profile['expires_at']) > time(),
        ];
    }

    /**
     * Проверить, есть ли активная подписка.
     */
    public function hasActiveSubscription(User $user): bool
    {
        $subscription = $this->getSubscriptionInfo($user);
        return $subscription !== null && ($subscription['is_active'] ?? false);
    }
}
