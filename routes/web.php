<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BotMapController;
use App\Http\Controllers\Auth\EmailVerificationController;

Route::get('/', function () {
    return view('welcome');
});

// Подтверждение email (ссылка из письма)
Route::get('/auth/email/confirm/{token}', [EmailVerificationController::class, 'confirm'])
    ->name('auth.email.confirm');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/bot-map', [BotMapController::class, 'index'])->name('admin.bot-map');
});
