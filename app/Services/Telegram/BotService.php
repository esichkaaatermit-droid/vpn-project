<?php

namespace App\Services\Telegram;

use App\Bot\HandlerRegistry;
use App\Models\Screen;
use App\Models\User;
use App\Models\UserState;
use App\Services\Integration\UserService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Основной сервис для работы с Telegram ботом.
 * 
 * Отвечает за:
 * - Обработку входящих сообщений и callback-запросов
 * - Отображение экранов
 * - Управление состоянием пользователя
 * - Управление webhook
 */
class BotService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct(
        protected UserService $userService
    ) {
        $this->token = config('telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Обработка входящего update от Telegram.
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
     * Обработка текстового сообщения.
     */
    protected function handleMessage(array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Создаём/получаем пользователя
        $username = $message['from']['username'] ?? null;
        $firstName = $message['from']['first_name'] ?? null;
        $this->userService->findOrCreateByTelegramId($chatId, $username, $firstName);

        // Получаем состояние пользователя
        $userState = UserState::findOrCreateByChatId($chatId);

        // Команда /start
        if ($text === '/start') {
            $startScreen = config('telegram.settings.start_screen', 'main.menu');
            $this->showScreen($chatId, $startScreen, $userState);
            return;
        }

        // Ищем кнопку по тексту в текущем экране
        if ($userState->current_screen_key) {
            $screen = Screen::findByKey($userState->current_screen_key);
            
            if ($screen) {
                $button = $screen->buttons()->where('text', $text)->first();
                
                if ($button && $button->next_screen_key) {
                    $this->showScreen($chatId, $button->next_screen_key, $userState);
                    return;
                }
            }
        }

        // Если не распознали — показываем главное меню
        $this->showScreen($chatId, 'main.menu', $userState);
    }

    /**
     * Обработка нажатия inline-кнопки.
     */
    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'] ?? '';

        // Отвечаем на callback
        $this->answerCallbackQuery($callbackQuery['id']);

        // Получаем состояние
        $userState = UserState::findOrCreateByChatId($chatId);

        // Показываем экран по ключу
        if ($data) {
            $this->showScreen($chatId, $data, $userState);
        }
    }

    /**
     * Показать экран пользователю.
     */
    public function showScreen(int $chatId, string $screenKey, ?UserState $userState = null): bool
    {
        $screen = Screen::findByKey($screenKey);

        if (!$screen) {
            Log::warning("Screen not found: {$screenKey}");
            $this->sendMessage($chatId, "😕 Экран не найден.\n\nНажмите /start чтобы начать сначала.");
            return false;
        }

        // Обновляем состояние пользователя
        if (!$userState) {
            $userState = UserState::findOrCreateByChatId($chatId);
        }
        $userState->setCurrentScreen($screenKey);

        // Получаем текст и кнопки
        $text = $screen->text;
        $buttons = [];

        // Если есть handler_id — вызываем обработчик
        if ($screen->hasHandler()) {
            $user = User::findByTelegramId($chatId);
            $result = HandlerRegistry::execute($screen->handler_id, $screen, $chatId, [
                'user' => $user,
                'user_state' => $userState,
            ]);
            
            if ($result) {
                // Обработчик может переопределить текст и кнопки
                $text = $result['text'] ?? $text;
                $buttons = $result['buttons'] ?? [];
            }
        }

        // Если обработчик не вернул кнопки — берём из БД
        if (empty($buttons)) {
            $buttons = $this->buildButtonsFromScreen($screen);
        }

        // Отправляем сообщение
        return $this->sendMessage($chatId, $text, $buttons);
    }

    /**
     * Построить массив кнопок из экрана.
     */
    protected function buildButtonsFromScreen(Screen $screen): array
    {
        $buttons = [];
        
        foreach ($screen->buttons as $button) {
            $buttons[] = [
                'text' => $button->text,
                'callback_data' => $button->next_screen_key ?? 'noop',
            ];
        }
        
        return $buttons;
    }

    /**
     * Отправить сообщение с кнопками.
     * 
     * @param int $chatId ID чата
     * @param string $text Текст сообщения
     * @param array $buttons Массив кнопок [['text' => '...', 'callback_data' => '...']]
     */
    public function sendMessage(int $chatId, string $text, array $buttons = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        // Формируем inline keyboard
        if (!empty($buttons)) {
            $keyboard = [];
            foreach ($buttons as $button) {
                $keyboard[] = [
                    [
                        'text' => $button['text'],
                        'callback_data' => $button['callback_data'] ?? 'noop',
                    ]
                ];
            }
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => $keyboard,
            ]);
        }

        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", $params);
            
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
     * Ответить на callback query.
     */
    protected function answerCallbackQuery(string $callbackQueryId, ?string $text = null): void
    {
        try {
            $params = ['callback_query_id' => $callbackQueryId];
            if ($text) {
                $params['text'] = $text;
            }
            Http::post("{$this->apiUrl}/answerCallbackQuery", $params);
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

            $response = Http::post("{$this->apiUrl}/setWebhook", $params);
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
            $response = Http::get("{$this->apiUrl}/getWebhookInfo");
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
            $response = Http::post("{$this->apiUrl}/deleteWebhook");
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }
}
