<?php

namespace App\Providers;

use App\Services\BotService;
use Illuminate\Support\ServiceProvider;

class BotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BotService::class, function ($app) {
            return new BotService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
