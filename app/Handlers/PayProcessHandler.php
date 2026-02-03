<?php

namespace App\Handlers;

use App\Jobs\CheckPaymentJob;
use App\Models\Payment;
use App\Models\User;
use App\Services\TariffService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Обработчик: процесс оплаты.
 * Создаёт платёж и запускает отложенную проверку.
 */
class PayProcessHandler extends AbstractHandler
{
    public function __construct(
        protected TariffService $tariffService
    ) {}

    public static function getId(): string
    {
        return 'tariffs.pay.process';
    }

    protected function execute(int $chatId, ?User $user, array $context): string
    {
        if (!$user) {
            return "❌ Ошибка: пользователь не найден";
        }

        // Получаем информацию о выбранном тарифе из кэша
        $tariffKey = $this->getUserSelectedTariff($chatId);
        $tariffInfo = $this->tariffService->getTariffInfo($tariffKey);

        // Отменяем предыдущие pending платежи этого пользователя
        Payment::where('user_id', $user->id)
            ->where('status', Payment::STATUS_PENDING)
            ->update(['status' => Payment::STATUS_CANCELLED]);

        // Создаём новый платёж
        $payment = Payment::createForUser(
            $user,
            $tariffInfo['key'],
            $tariffInfo['name'],
            $tariffInfo['amount']
        );

        // Запускаем отложенную проверку через 15 минут
        CheckPaymentJob::dispatch($payment->id)
            ->delay(now()->addMinutes(Payment::EXPIRATION_MINUTES));

        Log::info("Payment created", [
            'payment_id' => $payment->id,
            'user_id' => $user->id,
            'tariff' => $tariffInfo['name'],
            'expires_at' => $payment->expires_at,
        ]);

        return "⏳ Ожидаем оплату...\n\n" .
               "💰 Тариф: {$tariffInfo['name']}\n" .
               "💵 Сумма: {$tariffInfo['amount']} ₽\n\n" .
               "⚠️ Оплата будет отменена через 15 минут, если не поступит подтверждение.";
    }

    protected function getUserSelectedTariff(int $chatId): ?string
    {
        return Cache::get("user_tariff_{$chatId}", 'tariffs.pricing.1month');
    }
}
