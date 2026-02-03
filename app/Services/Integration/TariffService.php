<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с тарифами.
 * 
 * Отвечает за:
 * - Получение списка тарифов
 * - Информация о тарифе
 * - Интеграция с внешним API (в будущем)
 */
class TariffService
{
    /**
     * Получить список всех тарифов.
     * 
     * TODO: Заменить на реальный API-запрос
     * 
     * @return array Массив тарифов
     */
    public function getTariffs(): array
    {
        // TODO: Заменить на реальный API-запрос
        // Пример будущего кода:
        // $response = Http::get(config('services.backend.url') . '/api/tariffs');
        // return $response->json();

        Log::debug("TariffService::getTariffs called");

        // Заглушка — возвращаем тестовые данные
        return [
            [
                'id' => 1,
                'name' => 'Start',
                'price' => 299,
                'period' => 'month',
                'description' => 'Базовый тариф на 1 месяц',
            ],
            [
                'id' => 2,
                'name' => 'Годовой',
                'price' => 2990,
                'period' => 'year',
                'description' => 'Выгодный годовой тариф',
            ],
            [
                'id' => 3,
                'name' => 'Gold',
                'price' => 499,
                'period' => 'month',
                'description' => 'Премиум тариф с расширенными возможностями',
            ],
        ];
    }

    /**
     * Получить информацию о тарифе по ID.
     * 
     * TODO: Заменить на реальный API-запрос
     */
    public function getTariffById(int $id): ?array
    {
        // TODO: Заменить на реальный API-запрос
        
        $tariffs = $this->getTariffs();
        
        foreach ($tariffs as $tariff) {
            if ($tariff['id'] === $id) {
                return $tariff;
            }
        }
        
        return null;
    }

    /**
     * Получить информацию о тарифе по ключу экрана.
     */
    public function getTariffByScreenKey(string $screenKey): ?array
    {
        // Маппинг ключей экранов на ID тарифов
        $mapping = [
            'tariffs.pricing.1month' => 1,
            'tariffs.pricing.3months' => 1, // Start на 3 месяца
            'tariffs.pricing.6months' => 1, // Start на 6 месяцев
            'tariffs.gold.1month' => 3,
            'tariffs.gold.3months' => 3,
            'tariffs.gold.6months' => 3,
        ];

        $tariffId = $mapping[$screenKey] ?? null;
        
        if ($tariffId) {
            return $this->getTariffById($tariffId);
        }
        
        return null;
    }

    /**
     * Сохранить выбранный тариф в кэш.
     */
    public function setSelectedTariff(int $chatId, string $tariffKey): void
    {
        Cache::put("user_tariff_{$chatId}", $tariffKey, now()->addHours(1));
    }

    /**
     * Получить выбранный тариф из кэша.
     */
    public function getSelectedTariff(int $chatId): ?string
    {
        return Cache::get("user_tariff_{$chatId}");
    }
}
