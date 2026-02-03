<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BotMapController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/bot-map', [BotMapController::class, 'index'])->name('admin.bot-map');
});
