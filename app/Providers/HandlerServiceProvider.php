<?php

namespace App\Providers;

use App\Handlers\GenerateConfigHandler;
use App\Handlers\HandlerRegistry;
use App\Handlers\PayProcessHandler;
use App\Handlers\ShowConnectionStatusHandler;
use App\Handlers\ShowTariffsHandler;
use App\Handlers\ShowUserInfoHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider для регистрации обработчиков.
 */
class HandlerServiceProvider extends ServiceProvider
{
    /**
     * Список всех обработчиков для регистрации.
     */
    protected array $handlers = [
        ShowUserInfoHandler::class,
        ShowTariffsHandler::class,
        ShowConnectionStatusHandler::class,
        GenerateConfigHandler::class,
        PayProcessHandler::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Регистрируем все обработчики
        HandlerRegistry::registerMany($this->handlers);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
