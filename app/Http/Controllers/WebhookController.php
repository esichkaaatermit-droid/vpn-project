<?php

namespace App\Http\Controllers;

use App\Services\BotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected BotService $botService;

    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }

    /**
     * Handle incoming Telegram webhook.
     */
    public function handle(Request $request): JsonResponse
    {
        // Проверяем секретный токен от Telegram
        $secretToken = $request->header('X-Telegram-Bot-Api-Secret-Token');
        $expectedToken = config('services.telegram.webhook_secret');

        if ($expectedToken && $secretToken !== $expectedToken) {
            Log::warning('Invalid webhook secret token');
            return response()->json(['ok' => false], 403);
        }

        $update = $request->all();

        // Логируем только ID обновления (без персональных данных)
        Log::info('Telegram webhook received', ['update_id' => $update['update_id'] ?? null]);

        try {
            $this->botService->handleUpdate($update);
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Get webhook info (только просмотр — безопасно).
     */
    public function getWebhookInfo(): JsonResponse
    {
        $result = $this->botService->getWebhookInfo();

        return response()->json($result);
    }
}
