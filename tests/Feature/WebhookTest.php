<?php

namespace Tests\Feature;

use App\Models\Screen;
use App\Models\ScreenButton;
use App\Models\User;
use App\Models\UserState;
use App\Services\Telegram\TelegramApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature-тесты webhook-контроллера и навигации бота.
 */
class WebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Мокаем TelegramApiClient чтобы не делать реальных запросов
        $this->mock(TelegramApiClient::class, function ($mock) {
            $mock->shouldReceive('sendMessage')->andReturn(true);
            $mock->shouldReceive('editMessage')->andReturn(true);
            $mock->shouldReceive('deleteMessage')->andReturn(true);
            $mock->shouldReceive('answerCallbackQuery')->andReturn(null);
            $mock->shouldReceive('sendPhoto')->andReturn(true);
            $mock->shouldReceive('sendDocument')->andReturn(true);
        });

        // Создаём минимальное дерево экранов для тестов
        $mainMenu = Screen::create([
            'key' => 'main.menu',
            'title' => 'Главное меню',
            'text' => 'Добро пожаловать!',
            'handler_id' => 'main.menu',
        ]);

        $profile = Screen::create([
            'key' => 'profile.my',
            'title' => 'Профиль',
            'text' => 'Ваш профиль',
            'handler_id' => 'profile.my',
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => '👤 Профиль',
            'next_screen_key' => 'profile.my',
            'order' => 1,
            'row' => 0,
        ]);

        ScreenButton::create([
            'screen_id' => $profile->id,
            'text' => '🔙 Назад',
            'next_screen_key' => 'main.menu',
            'order' => 1,
            'row' => 0,
        ]);
    }

    public function test_webhook_rejects_invalid_secret_token(): void
    {
        config(['telegram.webhook_secret' => 'valid-secret']);

        $response = $this->postJson('/api/telegram/webhook', ['message' => []], [
            'X-Telegram-Bot-Api-Secret-Token' => 'wrong-secret',
        ]);

        $response->assertStatus(403);
    }

    public function test_webhook_accepts_valid_secret_token(): void
    {
        config(['telegram.webhook_secret' => 'valid-secret']);

        $payload = $this->makeMessagePayload(123, '/start');

        $response = $this->postJson('/api/telegram/webhook', $payload, [
            'X-Telegram-Bot-Api-Secret-Token' => 'valid-secret',
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'ok']);
    }

    public function test_webhook_without_secret_when_not_configured(): void
    {
        config(['telegram.webhook_secret' => null]);

        $response = $this->postJson('/api/telegram/webhook', []);

        $response->assertOk();
    }

    public function test_start_command_creates_user_and_state(): void
    {
        config(['telegram.webhook_secret' => null]);

        $chatId = 111222333;
        $payload = $this->makeMessagePayload($chatId, '/start', 'testuser');

        $response = $this->postJson('/api/telegram/webhook', $payload);
        $response->assertOk();

        // Пользователь создан
        $this->assertDatabaseHas('users', [
            'telegram_id' => $chatId,
            'telegram_username' => 'testuser',
        ]);

        // Состояние создано и установлено на main.menu
        $this->assertDatabaseHas('user_states', [
            'chat_id' => $chatId,
            'current_screen_key' => 'main.menu',
        ]);
    }

    public function test_callback_query_navigates_to_screen(): void
    {
        config(['telegram.webhook_secret' => null]);

        $chatId = 444555666;

        // Создаём пользователя и состояние
        User::create([
            'telegram_id' => $chatId,
            'name' => 'Test User',
        ]);
        UserState::create([
            'chat_id' => $chatId,
            'current_screen_key' => 'main.menu',
        ]);

        // Нажимаем кнопку "Профиль"
        $payload = $this->makeCallbackPayload($chatId, 'profile.my');

        $response = $this->postJson('/api/telegram/webhook', $payload);
        $response->assertOk();

        // Состояние обновлено
        $this->assertDatabaseHas('user_states', [
            'chat_id' => $chatId,
            'current_screen_key' => 'profile.my',
        ]);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    protected function makeMessagePayload(int $chatId, string $text, ?string $username = null): array
    {
        return [
            'update_id' => rand(100000, 999999),
            'message' => [
                'message_id' => rand(1, 9999),
                'from' => [
                    'id' => $chatId,
                    'is_bot' => false,
                    'first_name' => 'Test',
                    'username' => $username ?? 'test',
                ],
                'chat' => [
                    'id' => $chatId,
                    'type' => 'private',
                ],
                'text' => $text,
                'date' => time(),
            ],
        ];
    }

    protected function makeCallbackPayload(int $chatId, string $data): array
    {
        return [
            'update_id' => rand(100000, 999999),
            'callback_query' => [
                'id' => (string) rand(100000, 999999),
                'from' => [
                    'id' => $chatId,
                    'is_bot' => false,
                    'first_name' => 'Test',
                ],
                'message' => [
                    'message_id' => rand(1, 9999),
                    'chat' => [
                        'id' => $chatId,
                        'type' => 'private',
                    ],
                    'text' => 'Previous message',
                    'date' => time(),
                ],
                'data' => $data,
            ],
        ];
    }
}
