<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\User;
use App\Services\BotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Количество попыток выполнения задачи.
     */
    public int $tries = 3;

    /**
     * ID платежа для проверки.
     */
    protected int $paymentId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(BotService $botService): void
    {
        $payment = Payment::find($this->paymentId);

        if (!$payment) {
            Log::warning("CheckPaymentJob: Payment #{$this->paymentId} not found");
            return;
        }

        // Если платёж уже не в статусе pending — ничего не делаем
        if (!$payment->isPending()) {
            Log::info("CheckPaymentJob: Payment #{$this->paymentId} is not pending (status: {$payment->status})");
            return;
        }

        // Платёж истёк — отмечаем и отправляем сообщение пользователю
        Log::info("CheckPaymentJob: Payment #{$this->paymentId} expired, notifying user");
        
        $payment->markAsExpired();

        // Получаем пользователя
        $user = $payment->user;

        if (!$user || !$user->telegram_id) {
            Log::warning("CheckPaymentJob: User not found or no telegram_id for payment #{$this->paymentId}");
            return;
        }

        // Отправляем сообщение об истечении срока оплаты
        $botService->sendPaymentExpiredMessage($user, $payment);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CheckPaymentJob failed for payment #{$this->paymentId}: " . $exception->getMessage());
    }
}
