<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Telegram\WebhookController;

// Telegram Webhook routes
Route::prefix('telegram')->group(function () {
    // Основной webhook — принимает сообщения от Telegram
    Route::post('/webhook', [WebhookController::class, 'handle'])->name('telegram.webhook');
    
    // Просмотр информации — безопасно, только чтение
    Route::get('/webhook-info', [WebhookController::class, 'getWebhookInfo'])->name('telegram.webhook-info');
});
