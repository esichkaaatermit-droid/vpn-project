<?php

namespace App\Bot\Handlers;

use App\Bot\Handlers\Concerns\BuildsButtons;
use App\Models\Screen;
use App\Models\User;
use App\Services\Integration\ConfigService;
use App\Services\Integration\UserService;

/**
 * Обработчик выдачи конфигов и инструкций при установке VPN.
 * 
 * Проверяет: email, подписку. Выдаёт конфиг или ссылку через ConfigService.
 */
class InstallConfigHandler implements HandlerInterface
{
    use BuildsButtons;

    public function __construct(
        protected UserService $userService,
        protected ConfigService $configService
    ) {}

    /**
     * Обработать экран выдачи конфига.
     */
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        /** @var User|null $user */
        $user = $update['user'] ?? null;

        if (!$user || !$user->email) {
            return [
                'text' => "📧 <b>Сначала привяжите email</b>\n\n"
                    . "Для получения конфигурации необходимо привязать email в разделе «Профиль».",
                'buttons' => $this->buildButtons($screen),
            ];
        }

        if (!$this->userService->hasActiveSubscription($user)) {
            return [
                'text' => "⚠️ <b>Нет активной подписки</b>\n\n"
                    . "Оформите тариф в разделе «Тарифы», чтобы получить доступ к конфигурациям.",
                'buttons' => $this->buildButtons($screen),
            ];
        }

        $config = $this->configService->getVpnConfig($user->email);
        $platform = $this->getPlatformFromKey($screen->key);

        $text = ($screen->text ?: '') . "\n\n";
        $text .= "🔗 <b>Ссылка на конфиг:</b>\n";
        $text .= "<code>{$config['config_url']}</code>\n\n";
        $text .= "Скопируйте ссылку и добавьте в приложение VPN согласно инструкции для {$platform}.";

        $result = [
            'text' => $text,
            'buttons' => $this->buildButtons($screen),
        ];

        // Если ConfigService вернул URL файла — можно отправить как документ
        if (!empty($config['config_url']) && $this->isValidConfigUrl($config['config_url'])) {
            $result['document'] = $config['config_url'];
        }

        return $result;
    }

    protected function getPlatformFromKey(string $key): string
    {
        return match (true) {
            str_contains($key, 'android') => 'Android',
            str_contains($key, 'iphone') || str_contains($key, 'ipad') => 'iPhone/iPad',
            str_contains($key, 'appletv') => 'Apple TV',
            str_contains($key, 'windows') => 'Windows',
            str_contains($key, 'mac') => 'Mac',
            default => 'устройства',
        };
    }

    protected function isValidConfigUrl(string $url): bool
    {
        // Заглушка возвращает example.com — не отправляем как документ
        return !str_contains($url, 'example.com');
    }
}
