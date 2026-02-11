<?php

namespace App\Services\Telegram;

use App\Bot\HandlerRegistry;
use App\Models\Screen;
use App\Models\User;
use App\Models\UserState;
use App\Services\Integration\UserService;
use Illuminate\Support\Facades\Log;

/**
 * Основной сервис для работы с Telegram ботом.
 * 
 * Отвечает за:
 * - Обработку входящих сообщений и callback-запросов
 * - Отображение экранов
 * - Управление состоянием пользователя
 * 
 * HTTP-вызовы к Telegram API делегирует в TelegramApiClient.
 */
class BotService
{
    public function __construct(
        protected UserService $userService,
        protected TelegramApiClient $telegram
    ) {}

    // ─────────────────────────────────────────────
    // Обработка входящих update
    // ─────────────────────────────────────────────

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

        // Команда /start — сбрасывает режим ввода
        if ($text === '/start') {
            $userState->clearData();
            $startScreen = config('telegram.settings.start_screen', 'main.menu');
            $this->showScreen($chatId, $startScreen, $userState);
            return;
        }

        // Режим ввода email (ожидание после "Привязать email")
        $awaitingInput = $userState->getData('awaiting_input');
        if ($awaitingInput === 'email') {
            $this->handleEmailInput($chatId, $text, $userState);
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
     * Обработка введённого email (привязка аккаунта).
     */
    protected function handleEmailInput(int $chatId, string $text, UserState $userState): void
    {
        $userState->clearData();

        $result = $this->userService->requestEmailVerification($chatId, $text);

        $this->telegram->sendMessage($chatId, $result['message']);

        if ($result['success']) {
            $this->showScreen($chatId, 'profile.my', $userState);
        } else {
            $this->telegram->sendMessage($chatId, "Нажмите /start чтобы вернуться в главное меню.");
        }
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
        $this->telegram->answerCallbackQuery($callbackQuery['id']);

        // Получаем состояние
        $userState = UserState::findOrCreateByChatId($chatId);

        // Обработка специальных действий (action:*)
        if (str_starts_with($data, 'action:')) {
            $this->handleActionCallback($chatId, $data, $userState, $messageId);
            return;
        }

        // Показываем экран по ключу (редактируем текущее сообщение)
        if ($data) {
            $this->showScreen($chatId, $data, $userState, $messageId);
        }
    }

    /**
     * Обработка callback с action:* (привязка email, отмена ввода и т.д.)
     */
    protected function handleActionCallback(int $chatId, string $data, UserState $userState, int $messageId): void
    {
        if ($data === 'action:bind_email') {
            $userState->setData('awaiting_input', 'email');
            $this->telegram->sendMessage($chatId, "📧 Введите ваш email для привязки аккаунта:", [
                ['text' => '❌ Отмена', 'callback_data' => 'action:cancel_input', 'row' => 0],
            ]);
        } elseif ($data === 'action:cancel_input') {
            $userState->clearData();
            $this->showScreen($chatId, 'profile.my', $userState, $messageId);
        }
    }

    // ─────────────────────────────────────────────
    // Отображение экранов
    // ─────────────────────────────────────────────

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
            $this->telegram->sendMessage($chatId, "😕 Экран не найден.\n\nНажмите /start чтобы начать сначала.");
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
            if ($messageId) {
                $this->telegram->deleteMessage($chatId, $messageId);
            }
            return $this->telegram->sendDocument($chatId, $document, $text, $buttons);
        }
        
        if ($photo) {
            if ($messageId) {
                $this->telegram->deleteMessage($chatId, $messageId);
            }
            return $this->telegram->sendPhoto($chatId, $photo, $text, $buttons);
        }

        // Если есть messageId — редактируем существующее сообщение
        if ($messageId) {
            return $this->telegram->editMessage($chatId, $messageId, $text, $buttons);
        }

        // Иначе отправляем новое сообщение
        return $this->telegram->sendMessage($chatId, $text, $buttons);
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

    // ─────────────────────────────────────────────
    // Публичные методы (делегация в TelegramApiClient)
    // ─────────────────────────────────────────────

    /**
     * Отправить сообщение (публичный доступ для других сервисов).
     */
    public function sendMessage(int $chatId, string $text, array $buttons = []): bool
    {
        return $this->telegram->sendMessage($chatId, $text, $buttons);
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

        return $this->telegram->sendMessage($user->telegram_id, $text);
    }

    /**
     * Установить webhook.
     */
    public function setWebhook(?string $url = null, ?string $secretToken = null): array
    {
        return $this->telegram->setWebhook($url, $secretToken);
    }

    /**
     * Получить информацию о webhook.
     */
    public function getWebhookInfo(): array
    {
        return $this->telegram->getWebhookInfo();
    }

    /**
     * Удалить webhook.
     */
    public function deleteWebhook(): array
    {
        return $this->telegram->deleteWebhook();
    }
}
