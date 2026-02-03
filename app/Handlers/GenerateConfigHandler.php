<?php

namespace App\Handlers;

use App\Models\User;
use App\Services\ConfigService;

/**
 * Обработчик: генерация VPN конфига.
 */
class GenerateConfigHandler extends AbstractHandler
{
    public function __construct(
        protected ConfigService $configService
    ) {}

    public static function getId(): string
    {
        return 'generate_config';
    }

    protected function execute(int $chatId, ?User $user, array $context): string
    {
        if (!$user) {
            return "❌ Для получения конфигурации необходимо авторизоваться.";
        }

        // Проверяем, есть ли активная подписка
        if (!$this->configService->hasActiveSubscription($user)) {
            return "❌ У вас нет активной подписки.\n\nОформите тариф, чтобы получить конфигурацию.";
        }

        $config = $this->configService->generateConfig($user);
        
        if (!$config) {
            return "⚠️ Не удалось сгенерировать конфигурацию. Попробуйте позже.";
        }
        
        return $this->formatConfig($config);
    }

    protected function formatConfig(array $config): string
    {
        $lines = [
            "⚙️ <b>Ваша конфигурация готова!</b>",
            "",
            "🔑 Ключ: <code>{$config['key']}</code>",
            "🌍 Сервер: {$config['server']}",
            "",
            "📥 Скопируйте ключ и вставьте в приложение.",
        ];
        
        return implode("\n", $lines);
    }
}
