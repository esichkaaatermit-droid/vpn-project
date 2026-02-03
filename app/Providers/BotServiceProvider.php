<?php

namespace App\Providers;

use App\Services\Integration\ConfigService;
use App\Services\Integration\TariffService;
use App\Services\Integration\UserService;
use App\Services\Telegram\BotService;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider для бота и интеграционных сервисов.
 */
class BotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Интеграционные сервисы (заглушки под будущее API)
        $this->app->singleton(UserService::class);
        $this->app->singleton(TariffService::class);
        
        $this->app->singleton(ConfigService::class, function ($app) {
            return new ConfigService($app->make(UserService::class));
        });

        // Основной сервис бота
        $this->app->singleton(BotService::class, function ($app) {
            return new BotService($app->make(UserService::class));
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
