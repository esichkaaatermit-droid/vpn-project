<?php

namespace App\Handlers;

use App\Models\User;
use App\Services\UserService;

/**
 * Обработчик: показать информацию о пользователе.
 */
class ShowUserInfoHandler extends AbstractHandler
{
    public function __construct(
        protected UserService $userService
    ) {}

    public static function getId(): string
    {
        return 'show_user_info';
    }

    protected function execute(int $chatId, ?User $user, array $context): string
    {
        if (!$user) {
            return "👤 Ваш Chat ID: {$chatId}";
        }

        $info = $this->userService->getUserInfo($user);
        
        return $this->formatUserInfo($info);
    }

    protected function formatUserInfo(array $info): string
    {
        $lines = ["👤 <b>Информация о профиле</b>"];
        
        if ($info['username']) {
            $lines[] = "Username: @{$info['username']}";
        }
        
        if ($info['name']) {
            $lines[] = "Имя: {$info['name']}";
        }
        
        $lines[] = "ID: {$info['telegram_id']}";
        
        if ($info['subscription']) {
            $lines[] = "";
            $lines[] = "📦 <b>Подписка</b>";
            $lines[] = "Тариф: {$info['subscription']['name']}";
            $lines[] = "Действует до: {$info['subscription']['expires_at']}";
        } else {
            $lines[] = "";
            $lines[] = "📦 Подписка: не активна";
        }
        
        return implode("\n", $lines);
    }
}
