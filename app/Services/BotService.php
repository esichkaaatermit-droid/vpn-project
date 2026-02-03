<?php

namespace App\Services;

use App\Models\Screen;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BotService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Handle incoming webhook update.
     */
    public function handleUpdate(array $update): void
    {
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        } elseif (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        }
    }

    /**
     * Handle incoming message.
     */
    protected function handleMessage(array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Проверяем команду /start
        if ($text === '/start') {
            $this->showScreen($chatId, 'start');
            return;
        }

        // Ищем кнопку по тексту в текущем экране пользователя
        $currentScreenKey = $this->getUserCurrentScreen($chatId);
        
        if ($currentScreenKey) {
            $screen = Screen::findByKey($currentScreenKey);
            
            if ($screen) {
                $button = $screen->buttons()->where('text', $text)->first();
                
                if ($button && $button->next_screen_key) {
                    $this->showScreen($chatId, $button->next_screen_key);
                    return;
                }
            }
        }

        // Если текст не распознан, показываем стартовый экран
        $this->showScreen($chatId, 'start');
    }

    /**
     * Handle callback query (inline button press).
     */
    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'] ?? '';

        // Отвечаем на callback query
        $this->answerCallbackQuery($callbackQuery['id']);

        // Показываем экран по ключу из callback_data
        if ($data) {
            $this->showScreen($chatId, $data);
        }
    }

    /**
     * Show a screen to the user.
     */
    public function showScreen(int $chatId, string $screenKey): bool
    {
        $screen = Screen::findByKey($screenKey);

        if (!$screen) {
            Log::warning("Screen not found: {$screenKey}");
            // Показываем пользователю сообщение об ошибке
            $this->sendMessage($chatId, "😕 Что-то пошло не так.\n\nНажмите /start чтобы начать сначала.");
            return false;
        }

        // Сохраняем текущий экран пользователя
        $this->setUserCurrentScreen($chatId, $screenKey);

        // Выполняем обработчик, если есть
        $additionalText = '';
        if ($screen->hasHandler()) {
            $additionalText = $this->executeHandler($screen->handler_id, $chatId);
        }

        // Формируем текст сообщения
        $text = $screen->text;
        if ($additionalText) {
            $text .= "\n\n" . $additionalText;
        }

        // Формируем клавиатуру
        $keyboard = $this->buildKeyboard($screen);

        // Отправляем сообщение
        return $this->sendMessage($chatId, $text, $keyboard);
    }

    /**
     * Build keyboard from screen buttons.
     */
    protected function buildKeyboard(Screen $screen): array
    {
        $buttons = $screen->buttons;

        if ($buttons->isEmpty()) {
            return [];
        }

        $keyboard = [];
        foreach ($buttons as $button) {
            $keyboard[] = [
                [
                    'text' => $button->text,
                    'callback_data' => $button->next_screen_key ?? 'noop',
                ]
            ];
        }

        return [
            'inline_keyboard' => $keyboard,
        ];
    }

    /**
     * Send message to Telegram.
     */
    public function sendMessage(int $chatId, string $text, array $replyMarkup = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if (!empty($replyMarkup)) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }

        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", $params);
            
            if (!$response->successful()) {
                Log::error('Telegram API error', [
                    'response' => $response->json(),
                    'params' => $params,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram API exception', [
                'message' => $e->getMessage(),
                'params' => $params,
            ]);
            return false;
        }
    }

    /**
     * Answer callback query.
     */
    protected function answerCallbackQuery(string $callbackQueryId): void
    {
        try {
            Http::post("{$this->apiUrl}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to answer callback query', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Execute a handler by ID.
     */
    protected function executeHandler(string $handlerId, int $chatId): string
    {
        // Здесь можно добавить кастомную логику для различных обработчиков
        // Используем строковые ID вместо имен классов
        
        return match ($handlerId) {
            'show_user_info' => $this->handlerShowUserInfo($chatId),
            'show_tariffs' => $this->handlerShowTariffs($chatId),
            'show_connection_status' => $this->handlerShowConnectionStatus($chatId),
            'generate_config' => $this->handlerGenerateConfig($chatId),
            default => '',
        };
    }

    /**
     * Handler: Show user info.
     */
    protected function handlerShowUserInfo(int $chatId): string
    {
        // Пример: возвращаем информацию о пользователе
        return "👤 Ваш Chat ID: {$chatId}";
    }

    /**
     * Handler: Show tariffs.
     */
    protected function handlerShowTariffs(int $chatId): string
    {
        // Пример: возвращаем информацию о тарифах
        return "💰 Актуальные тарифы загружаются динамически...";
    }

    /**
     * Handler: Show connection status.
     */
    protected function handlerShowConnectionStatus(int $chatId): string
    {
        // Пример: возвращаем статус подключения
        return "🔗 Статус подключения: проверяется...";
    }

    /**
     * Handler: Generate VPN config.
     */
    protected function handlerGenerateConfig(int $chatId): string
    {
        // Пример: генерация конфига
        return "⚙️ Конфигурация генерируется...";
    }

    /**
     * Get user's current screen from cache.
     */
    protected function getUserCurrentScreen(int $chatId): ?string
    {
        return Cache::get("user_screen_{$chatId}");
    }

    /**
     * Set user's current screen in cache.
     */
    protected function setUserCurrentScreen(int $chatId, string $screenKey): void
    {
        Cache::put("user_screen_{$chatId}", $screenKey, now()->addHours(24));
    }

    /**
     * Set webhook URL for the bot.
     */
    public function setWebhook(string $url, ?string $secretToken = null): array
    {
        try {
            $params = ['url' => $url];
            
            // Добавляем секретный токен для верификации
            if ($secretToken) {
                $params['secret_token'] = $secretToken;
            }

            $response = Http::post("{$this->apiUrl}/setWebhook", $params);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get webhook info.
     */
    public function getWebhookInfo(): array
    {
        try {
            $response = Http::get("{$this->apiUrl}/getWebhookInfo");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete webhook.
     */
    public function deleteWebhook(): array
    {
        try {
            $response = Http::post("{$this->apiUrl}/deleteWebhook");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'ok' => false,
                'description' => $e->getMessage(),
            ];
        }
    }
}
