<?php

namespace App\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';
    protected $description = 'Установить webhook для Telegram бота';

    public function handle(BotService $botService): int
    {
        $url = $this->argument('url') ?? route('telegram.webhook');
        $secret = config('services.telegram.webhook_secret');

        $this->info("Устанавливаю webhook: {$url}");

        $result = $botService->setWebhook($url, $secret);

        if ($result['ok'] ?? false) {
            $this->info('✅ Webhook успешно установлен!');
            return 0;
        }

        $this->error('❌ Ошибка: ' . ($result['description'] ?? 'Неизвестная ошибка'));
        return 1;
    }
}
