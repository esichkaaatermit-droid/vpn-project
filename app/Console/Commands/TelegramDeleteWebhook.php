<?php

namespace App\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramDeleteWebhook extends Command
{
    protected $signature = 'telegram:delete-webhook';
    protected $description = 'Удалить webhook Telegram бота';

    public function handle(BotService $botService): int
    {
        if (!$this->confirm('Вы уверены, что хотите удалить webhook?')) {
            $this->info('Отменено.');
            return 0;
        }

        $result = $botService->deleteWebhook();

        if ($result['ok'] ?? false) {
            $this->info('✅ Webhook удалён!');
            return 0;
        }

        $this->error('❌ Ошибка: ' . ($result['description'] ?? 'Неизвестная ошибка'));
        return 1;
    }
}
