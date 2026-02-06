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
        $messageId = $callbackQuery['message']['message_id'];
        $data = $callbackQuery['data'] ?? '';

        // Отвечаем на callback
        $this->answerCallbackQuery($callbackQuery['id']);

        // Получаем состояние
        $userState = UserState::findOrCreateByChatId($chatId);

        // Показываем экран по ключу (редактируем текущее сообщение)
        if ($data) {
            $this->showScreen($chatId, $data, $userState, $messageId);
        }
    }

    /**
     * Показать экран пользователю.
     * 
     * @param int $chatId ID чата
     * @param string $screenKey Ключ экрана
     * @param UserState|null $userState Состояние пользователя
     * @param int|null $messageId ID сообщения для редактирования (если null — отправляется новое)
     */
    public function showScreen(int $chatId, string $screenKey, ?UserState $userState = null, ?int $messageId = null): bool
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
        $photo = null;
        $document = null;

        // Если есть handler_id — вызываем обработчик
        if ($screen->hasHandler()) {
            $user = User::findByTelegramId($chatId);
            $result = HandlerRegistry::execute($screen->handler_id, $screen, $chatId, [
                'user' => $user,
                'user_state' => $userState,
            ]);
            
            if ($result) {
                // Обработчик может переопределить текст, кнопки, добавить медиа
                $text = $result['text'] ?? $text;
                $buttons = $result['buttons'] ?? [];
                $photo = $result['photo'] ?? null;
                $document = $result['document'] ?? null;
            }
        }

        // Если обработчик не вернул кнопки — берём из БД
        if (empty($buttons)) {
            $buttons = $this->buildButtonsFromScreen($screen);
        }

        // Если есть медиа — отправляем новое сообщение (редактировать нельзя)
        if ($document) {
            // Удаляем старое сообщение если было
            if ($messageId) {
                $this->deleteMessage($chatId, $messageId);
            }
            return $this->sendDocument($chatId, $document, $text, $buttons);
        }
        
        if ($photo) {
            // Удаляем старое сообщение если было
            if ($messageId) {
                $this->deleteMessage($chatId, $messageId);
            }
            return $this->sendPhoto($chatId, $photo, $text, $buttons);
        }

        // Если есть messageId — редактируем существующее сообщение
        if ($messageId) {
            return $this->editMessage($chatId, $messageId, $text, $buttons);
        }

        // Иначе отправляем новое сообщение
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
                'row' => $button->row ?? 0,
            ];
        }
        
        return $buttons;
    }

    /**
     * Редактировать существующее сообщение.
     * 
     * @param int $chatId ID чата
     * @param int $messageId ID сообщения для редактирования
     * @param string $text Новый текст сообщения
     * @param array $buttons Массив кнопок [['text' => '...', 'callback_data' => '...']]
     */
    public function editMessage(int $chatId, int $messageId, string $text, array $buttons = []): bool
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        // Формируем inline keyboard
        $this->attachInlineKeyboard($params, $buttons);

        try {
            $response = Http::post("{$this->apiUrl}/editMessageText", $params);
            
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
     * 
     * @param int $chatId ID чата
     * @param int $messageId ID сообщения для удаления
     */
    public function deleteMessage(int $chatId, int $messageId): bool
    {
        try {
            $response = Http::post("{$this->apiUrl}/deleteMessage", [
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
        $this->attachInlineKeyboard($params, $buttons);

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
     * Отправить фото с подписью и кнопками.
     * 
     * @param int $chatId ID чата
     * @param string $photo URL или file_id фото
     * @param string|null $caption Подпись к фото
     * @param array $buttons Массив кнопок
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

        // Формируем inline keyboard
        $this->attachInlineKeyboard($params, $buttons);

        try {
            $response = Http::post("{$this->apiUrl}/sendPhoto", $params);
            
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
     * 
     * @param int $chatId ID чата
     * @param string $document URL, file_id или путь к файлу
     * @param string|null $caption Подпись к документу
     * @param array $buttons Массив кнопок
     * @param string|null $filename Имя файла для отображения
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

        // Формируем inline keyboard
        $this->attachInlineKeyboard($params, $buttons);

        try {
            // Проверяем, это локальный файл или URL/file_id
            if (file_exists($document)) {
                // Локальный файл — отправляем как multipart
                $response = Http::attach(
                    'document',
                    file_get_contents($document),
                    $filename ?? basename($document)
                )->post("{$this->apiUrl}/sendDocument", $params);
            } else {
                // URL или file_id
                $params['document'] = $document;
                $response = Http::post("{$this->apiUrl}/sendDocument", $params);
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
     * Добавить inline keyboard к параметрам запроса.
     *
     * @param array &$params Параметры запроса (модифицируются по ссылке)
     * @param array $buttons Массив кнопок [['text' => '...', 'callback_data' => '...', 'row' => int]]
     * 
     * Логика row:
     * - Кнопки с одинаковым row объединяются в один ряд
     * - row=0 означает "свой отдельный ряд" (не группируется)
     * - Порядок рядов определяется порядком кнопок (по order)
     */
    protected function attachInlineKeyboard(array &$params, array $buttons): void
    {
        if (empty($buttons)) {
            return;
        }

        $keyboard = [];
        $rowIndex = 0;
        $usedRows = [];  // Запоминаем какие row уже обработаны
        
        foreach ($buttons as $index => $button) {
            $rowNum = $button['row'] ?? 0;
            $buttonData = [
                'text' => $button['text'],
                'callback_data' => $button['callback_data'] ?? 'noop',
            ];
            
            if ($rowNum === 0) {
                // row = 0 — отдельный ряд для этой кнопки
                $keyboard[] = [$buttonData];
            } else {
                // Проверяем, был ли уже этот row
                if (isset($usedRows[$rowNum])) {
                    // Добавляем в существующий ряд
                    $keyboard[$usedRows[$rowNum]][] = $buttonData;
                } else {
                    // Создаём новый ряд
                    $keyboard[] = [$buttonData];
                    $usedRows[$rowNum] = count($keyboard) - 1;
                }
            }
        }

        $params['reply_markup'] = json_encode([
            'inline_keyboard' => $keyboard,
        ]);
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
     * Отправить уведомление об истечении срока оплаты.
     *
     * TODO: Доработать при подключении платёжной системы
     */
    public function sendPaymentExpiredMessage(User $user, \App\Models\Payment $payment): bool
    {
        if (!$user->telegram_id) {
            return false;
        }

        $text = "⏳ Время ожидания оплаты истекло.\n\n"
            . "Тариф: {$payment->tariff_name}\n"
            . "Сумма: {$payment->amount} ₽\n\n"
            . "Вы можете повторить оплату в разделе «Тарифы».";

        return $this->sendMessage($user->telegram_id, $text);
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
