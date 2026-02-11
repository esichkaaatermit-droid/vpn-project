<?php

namespace App\Services\Integration;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

/**
 * Сервис для работы с пользователями.
 * 
 * Отвечает за:
 * - Получение профиля пользователя
 * - Управление подписками
 * - Привязка email к Telegram
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

    /**
     * Запросить привязку email к Telegram.
     * Отправляет письмо с ссылкой для подтверждения.
     *
     * @return array{success: bool, message: string}
     */
    public function requestEmailVerification(int $telegramId, string $email): array
    {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Неверный формат email. Проверьте введённый адрес.'];
        }

        // Rate-limit: не более 3 запросов в 10 минут на пользователя
        $rateLimitKey = "email_verify_rate:{$telegramId}";
        $attempts = (int) Cache::get($rateLimitKey, 0);

        if ($attempts >= 3) {
            return ['success' => false, 'message' => 'Слишком много запросов. Попробуйте через 10 минут.'];
        }

        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(10));

        // Удаляем старые токены для этой пары email+telegram
        DB::table('email_verification_tokens')
            ->where('email', $email)
            ->where('telegram_id', $telegramId)
            ->delete();

        $token = Str::random(64);
        $expiresAt = now()->addDay();

        DB::table('email_verification_tokens')->insert([
            'email' => $email,
            'telegram_id' => $telegramId,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $confirmUrl = url("/auth/email/confirm/{$token}");

        try {
            Mail::to($email)->send(new EmailVerificationMail($email, $confirmUrl));
        } catch (\Throwable $e) {
            Log::error('Failed to send email verification', [
                'email' => $email,
                'telegram_id' => $telegramId,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Не удалось отправить письмо. Попробуйте позже.',
            ];
        }

        return [
            'success' => true,
            'message' => "На {$email} отправлено письмо с ссылкой для подтверждения. Перейдите по ссылке в письме.",
        ];
    }
}
