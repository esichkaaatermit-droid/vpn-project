<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * HTTP-клиент для Telegram Bot API.
 *
 * Отвечает ТОЛЬКО за отправку запросов к Telegram:
 * - sendMessage, editMessageText, deleteMessage
 * - sendPhoto, sendDocument
 * - answerCallbackQuery
 * - Webhook management (set, get, delete)
 *
 * Вся бизнес-логика (навигация, хендлеры, состояние) — в BotService.
 */
class TelegramApiClient
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('telegram.bot_token')
            ?? throw new \RuntimeException('TELEGRAM_BOT_TOKEN не задан. Укажите его в .env файле.');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * HTTP-клиент с таймаутом.
     */
    protected function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout(10);
    }

    /**
     * Отправить сообщение с кнопками.
     */
    public function sendMessage(int $chatId, string $text, array $buttons = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        $this->attachInlineKeyboard($params, $buttons);

        try {
            $response = $this->http()->post("{$this->apiUrl}/sendMessage", $params);

            if (!$response->successful()) {
                Log::error('Telegram API error', [
                    'response' => $response->json(),
                    'params' => array_diff_key($params, ['reply_markup' => 1]),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram API exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Редактировать существующее сообщение.
     */
    public function editMessage(int $chatId, int $messageId, string $text, array $buttons = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        $this->attachInlineKeyboard($params, $buttons);

        try {
            $response = $this->http()->post("{$this->apiUrl}/editMessageText", $params);

            if (!$response->successful()) {
                $error = $response->json();

                // Если сообщение не изменилось — это не ошибка
                if (str_contains($error['description'] ?? '', 'message is not modified')) {
                    return true;
                }

                Log::error('Telegram editMessage error', [
                    'response' => $error,
                    'params' => array_diff_key($params, ['reply_markup' => 1]),
                ]);

                // Fallback: отправляем новое сообщение
                return $this->sendMessage($chatId, $text, $buttons);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram editMessage exception', [
                'message' => $e->getMessage(),
            ]);
            // Fallback: отправляем новое сообщение
            return $this->sendMessage($chatId, $text, $buttons);
        }
    }

    /**
     * Удалить сообщение.
     */
    public function deleteMessage(int $chatId, int $messageId): bool
    {
        try {
            $response = $this->http()->post("{$this->apiUrl}/deleteMessage", [
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram deleteMessage exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Отправить фото с подписью и кнопками.
     */
    public function sendPhoto(int $chatId, string $photo, ?string $caption = null, array $buttons = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'photo' => $photo,
        ];

        if ($caption) {
            $params['caption'] = $caption;
            $params['parse_mode'] = 'HTML';
        }

        $this->attachInlineKeyboard($params, $buttons);

        try {
            $response = $this->http()->post("{$this->apiUrl}/sendPhoto", $params);

            if (!$response->successful()) {
                Log::error('Telegram sendPhoto error', [
                    'response' => $response->json(),
                    'photo' => $photo,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram sendPhoto exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Отправить документ (файл) с подписью и кнопками.
     */
    public function sendDocument(int $chatId, string $document, ?string $caption = null, array $buttons = [], ?string $filename = null): bool
    {
        $params = [
            'chat_id' => $chatId,
        ];

        if ($caption) {
            $params['caption'] = $caption;
            $params['parse_mode'] = 'HTML';
        }

        $this->attachInlineKeyboard($params, $buttons);

        try {
            if (file_exists($document)) {
                $response = $this->http()->attach(
                    'document',
                    file_get_contents($document),
                    $filename ?? basename($document)
                )->post("{$this->apiUrl}/sendDocument", $params);
            } else {
                $params['document'] = $document;
                $response = $this->http()->post("{$this->apiUrl}/sendDocument", $params);
            }

            if (!$response->successful()) {
                Log::error('Telegram sendDocument error', [
                    'response' => $response->json(),
                    'document' => $document,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram sendDocument exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Ответить на callback query.
     */
    public function answerCallbackQuery(string $callbackQueryId, ?string $text = null): void
    {
        try {
            $params = ['callback_query_id' => $callbackQueryId];
            if ($text) {
                $params['text'] = $text;
            }
            $this->http()->post("{$this->apiUrl}/answerCallbackQuery", $params);
        } catch (\Exception $e) {
            Log::error('Failed to answer callback query', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Установить webhook.
     */
    public function setWebhook(?string $url = null, ?string $secretToken = null): array
    {
        $url = $url ?? config('telegram.webhook_url');
        $secretToken = $secretToken ?? config('telegram.webhook_secret');

        try {
            $params = ['url' => $url];
            if ($secretToken) {
                $params['secret_token'] = $secretToken;
            }

            $response = $this->http()->post("{$this->apiUrl}/setWebhook", $params);
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    /**
     * Получить информацию о webhook.
     */
    public function getWebhookInfo(): array
    {
        try {
            $response = $this->http()->get("{$this->apiUrl}/getWebhookInfo");
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    /**
     * Удалить webhook.
     */
    public function deleteWebhook(): array
    {
        try {
            $response = $this->http()->post("{$this->apiUrl}/deleteWebhook");
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    /**
     * Добавить inline keyboard к параметрам запроса.
     *
     * Логика row:
     * - Кнопки с одинаковым row объединяются в один ряд
     * - row=0 означает "свой отдельный ряд" (не группируется)
     */
    protected function attachInlineKeyboard(array &$params, array $buttons): void
    {
        if (empty($buttons)) {
            return;
        }

        $keyboard = [];
        $usedRows = [];

        foreach ($buttons as $button) {
            $rowNum = $button['row'] ?? 0;
            $buttonData = [
                'text' => $button['text'],
                'callback_data' => $button['callback_data'] ?? 'noop',
            ];

            if ($rowNum === 0) {
                $keyboard[] = [$buttonData];
            } else {
                if (isset($usedRows[$rowNum])) {
                    $keyboard[$usedRows[$rowNum]][] = $buttonData;
                } else {
                    $keyboard[] = [$buttonData];
                    $usedRows[$rowNum] = count($keyboard) - 1;
                }
            }
        }

        $params['reply_markup'] = json_encode([
            'inline_keyboard' => $keyboard,
        ]);
    }
}
