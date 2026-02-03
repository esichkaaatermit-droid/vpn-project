<?php

namespace App\Handlers;

use App\Models\User;
use App\Services\TariffService;

/**
 * Обработчик: показать тарифы.
 */
class ShowTariffsHandler extends AbstractHandler
{
    public function __construct(
        protected TariffService $tariffService
    ) {}

    public static function getId(): string
    {
        return 'show_tariffs';
    }

    protected function execute(int $chatId, ?User $user, array $context): string
    {
        $tariffs = $this->tariffService->getAvailableTariffs();
        
        return $this->formatTariffs($tariffs);
    }

    protected function formatTariffs(array $tariffs): string
    {
        $lines = ["💰 <b>Актуальные тарифы:</b>", ""];
        
        foreach ($tariffs as $tariff) {
            $lines[] = "• {$tariff['name']} — {$tariff['amount']} ₽";
        }
        
        return implode("\n", $lines);
    }
}
