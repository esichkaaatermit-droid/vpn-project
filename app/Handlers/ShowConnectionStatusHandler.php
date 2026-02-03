<?php

namespace App\Handlers;

use App\Models\User;
use App\Services\ConfigService;

/**
 * Обработчик: показать статус подключения.
 */
class ShowConnectionStatusHandler extends AbstractHandler
{
    public function __construct(
        protected ConfigService $configService
    ) {}

    public static function getId(): string
    {
        return 'show_connection_status';
    }

    protected function execute(int $chatId, ?User $user, array $context): string
    {
        if (!$user) {
            return "🔗 Статус: не авторизован";
        }

        $status = $this->configService->getConnectionStatus($user);
        
        return $this->formatStatus($status);
    }

    protected function formatStatus(array $status): string
    {
        $icon = $status['connected'] ? '🟢' : '🔴';
        $statusText = $status['connected'] ? 'Подключен' : 'Отключен';
        
        $lines = [
            "{$icon} <b>Статус подключения:</b> {$statusText}",
        ];
        
        if ($status['server']) {
            $lines[] = "🌍 Сервер: {$status['server']}";
        }
        
        if ($status['ip']) {
            $lines[] = "🔢 IP: {$status['ip']}";
        }
        
        if ($status['last_seen']) {
            $lines[] = "🕐 Последняя активность: {$status['last_seen']}";
        }
        
        return implode("\n", $lines);
    }
}
