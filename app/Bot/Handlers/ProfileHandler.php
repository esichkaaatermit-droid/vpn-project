<?php

namespace App\Bot\Handlers;

use App\Models\Screen;
use App\Models\User;
use App\Services\Integration\UserService;

/**
 * Обработчик профиля пользователя.
 * 
 * Использует UserService для получения данных профиля.
 */
class ProfileHandler implements HandlerInterface
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Обработать экран профиля.
     */
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        /** @var User|null $user */
        $user = $update['user'] ?? null;

        // Формируем текст профиля
        $text = $this->formatProfileText($user, $chatId);

        // Кнопки из экрана
        $buttons = [];
        foreach ($screen->buttons as $button) {
            $buttons[] = [
                'text' => $button->text,
                'callback_data' => $button->next_screen_key ?? 'noop',
            ];
        }

        return [
            'text' => $text,
            'buttons' => $buttons,
        ];
    }

    /**
     * Форматировать текст профиля.
     */
    protected function formatProfileText(?User $user, int $chatId): string
    {
        $lines = ["👤 <b>Мой профиль</b>", ""];

        if (!$user) {
            $lines[] = "ID: {$chatId}";
            $lines[] = "";
            $lines[] = "ℹ️ Вы не авторизованы.";
            return implode("\n", $lines);
        }

        // Основная информация
        if ($user->telegram_username) {
            $lines[] = "Username: @{$user->telegram_username}";
        }
        if ($user->name) {
            $lines[] = "Имя: {$user->name}";
        }
        $lines[] = "Telegram ID: {$user->telegram_id}";

        // Если есть email — получаем профиль из API
        if ($user->email) {
            $profile = $this->userService->getUserProfile($user->email);
            
            $lines[] = "";
            $lines[] = "📦 <b>Подписка</b>";
            $lines[] = "Тариф: {$profile['tariff']}";
            $lines[] = "Действует до: {$profile['expires_at']}";
            $lines[] = "Устройств: {$profile['devices_used']}/{$profile['devices_limit']}";
        } else {
            $lines[] = "";
            $lines[] = "📦 Подписка: не оформлена";
        }

        return implode("\n", $lines);
    }
}
