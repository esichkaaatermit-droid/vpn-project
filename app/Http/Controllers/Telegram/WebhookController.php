<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Services\Telegram\BotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Контроллер для обработки webhook от Telegram.
 */
class WebhookController extends Controller
{
    public function __construct(
        protected BotService $botService
    ) {}

    /**
     * Обработка входящего webhook от Telegram.
     */
    public function handle(Request $request): JsonResponse
    {
        // Верификация секретного токена (опционально)
        $secretToken = config('telegram.webhook_secret');
        if ($secretToken) {
            $headerToken = $request->header('X-Telegram-Bot-Api-Secret-Token');
            if ($headerToken !== $secretToken) {
                Log::warning('Telegram webhook: invalid secret token');
                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 403);
            }
        }

        $update = $request->all();

        // Логируем входящий update (в dev режиме)
        if (config('app.debug')) {
            Log::debug('Telegram webhook received', ['update' => $update]);
        }

        try {
            $this->botService->handleUpdate($update);
            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            Log::error('Telegram webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Возвращаем 200, чтобы Telegram не ретраил
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Получить информацию о webhook.
     */
    public function getWebhookInfo(): JsonResponse
    {
        $info = $this->botService->getWebhookInfo();
        return response()->json($info);
    }
}
