<?php

namespace App\Bot\Handlers;

use App\Bot\Handlers\Concerns\BuildsButtons;
use App\Models\Screen;
use App\Services\Integration\TariffService;

/**
 * Обработчик тарифов.
 * 
 * Использует TariffService для получения списка тарифов.
 */
class TariffHandler implements HandlerInterface
{
    use BuildsButtons;

    public function __construct(
        protected TariffService $tariffService
    ) {}

    /**
     * Обработать экран тарифов.
     */
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        // Получаем тарифы из сервиса
        $tariffs = $this->tariffService->getTariffs();

        // Формируем текст
        $text = $this->formatTariffsText($screen->text, $tariffs);

        return [
            'text' => $text,
            'buttons' => $this->buildButtons($screen),
        ];
    }

    /**
     * Форматировать текст с тарифами.
     */
    protected function formatTariffsText(string $baseText, array $tariffs): string
    {
        $lines = [$baseText, ""];
        $lines[] = "💰 <b>Актуальные тарифы:</b>";
        $lines[] = "";

        foreach ($tariffs as $tariff) {
            $period = match ($tariff['period']) {
                'month' => 'мес',
                'year' => 'год',
                default => $tariff['period'],
            };
            
            $lines[] = "• <b>{$tariff['name']}</b> — {$tariff['price']} ₽/{$period}";
            
            if (!empty($tariff['description'])) {
                $lines[] = "  {$tariff['description']}";
            }
        }

        return implode("\n", $lines);
    }
}
