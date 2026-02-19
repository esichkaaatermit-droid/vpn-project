<?php

namespace App\Services\Integration;

use App\Models\User;
use App\Services\Integration\Concerns\SendsBackendAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Сервис для работы с VPN конфигурациями.
 * 
 * Отвечает за:
 * - Генерация VPN конфигов
 * - Получение QR-кодов
 * - Интеграция с внешним API (в будущем)
 */
class ConfigService
{
    use SendsBackendAuth;

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Получить VPN конфигурацию для пользователя.
     * 
     * TODO: Заменить на реальный API-запрос
     * 
     * @param string $email Email пользователя
     * @return array Конфигурация
     */
    public function getVpnConfig(string $email): array
    {
        $baseUrl = config('services.backend.url');
        if (empty($baseUrl)) {
            Log::debug("ConfigService::getVpnConfig called (stub)", ['email' => $email]);
            return [
                'config_url' => 'https://example.com/config.ovpn',
                'qr_code' => 'base64_encoded_qr_' . Str::random(32),
            ];
        }

        $response = Http::withHeaders($this->backendHeaders())
            ->get($baseUrl . '/api/vpn/config', ['email' => $email]);

        if ($response->successful()) {
            return $response->json() ?? ['config_url' => '', 'qr_code' => ''];
        }

        Log::warning('ConfigService::getVpnConfig API error', [
            'email' => $email,
            'status' => $response->status(),
        ]);
        return ['config_url' => '', 'qr_code' => ''];
    }

    /**
     * Сгенерировать конфигурацию для пользователя.
     * 
     * TODO: Заменить на реальный API-запрос
     */
    public function generateConfig(User $user): ?array
    {
        // TODO: Заменить на реальный API-запрос к VPN серверу
        
        // Проверяем подписку
        if (!$this->userService->hasActiveSubscription($user)) {
            return null;
        }

        // Генерируем ключ (заглушка)
        $key = $this->generateKey($user);
        
        return [
            'key' => $key,
            'server' => $this->getServerForUser($user),
            'protocol' => 'shadowsocks',
            'created_at' => now()->toISOString(),
        ];
    }

    /**
     * Получить статус подключения.
     * 
     * TODO: Заменить на реальный API-запрос
     */
    public function getConnectionStatus(User $user): array
    {
        // TODO: Заменить на реальный API-запрос
        
        return [
            'connected' => false,
            'server' => null,
            'ip' => null,
            'last_seen' => null,
            'data_used' => null,
        ];
    }

    /**
     * Сгенерировать ключ (заглушка).
     */
    protected function generateKey(User $user): string
    {
        // TODO: Заменить на реальную генерацию через API
        $base = "ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTp";
        $uniquePart = base64_encode("{$user->id}:" . Str::random(16));
        
        return $base . $uniquePart . "@vpn.example.com:8388";
    }

    /**
     * Получить сервер для пользователя (заглушка).
     */
    protected function getServerForUser(User $user): string
    {
        // TODO: Логика выбора сервера через API
        $servers = ['nl-1.vpn.example.com', 'de-1.vpn.example.com', 'us-1.vpn.example.com'];
        return $servers[array_rand($servers)];
    }
}
