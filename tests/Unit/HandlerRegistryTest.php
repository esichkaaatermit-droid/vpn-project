<?php

namespace Tests\Unit;

use App\Bot\HandlerRegistry;
use App\Bot\Handlers\HandlerInterface;
use App\Bot\Handlers\MainMenuHandler;
use App\Bot\Handlers\ProfileHandler;
use App\Bot\Handlers\TariffHandler;
use App\Bot\Handlers\InstallConfigHandler;
use Tests\TestCase;

/**
 * Тесты реестра обработчиков.
 */
class HandlerRegistryTest extends TestCase
{
    public function test_all_registered_handlers_exist(): void
    {
        $handlers = HandlerRegistry::all();

        $this->assertNotEmpty($handlers, 'HandlerRegistry не должен быть пустым');

        foreach ($handlers as $handlerId => $handlerClass) {
            $this->assertTrue(
                class_exists($handlerClass),
                "Класс {$handlerClass} для handler_id '{$handlerId}' не найден"
            );

            $this->assertTrue(
                in_array(HandlerInterface::class, class_implements($handlerClass)),
                "Класс {$handlerClass} должен реализовывать HandlerInterface"
            );
        }
    }

    public function test_has_returns_true_for_registered_handlers(): void
    {
        $this->assertTrue(HandlerRegistry::has('main.menu'));
        $this->assertTrue(HandlerRegistry::has('profile.my'));
        $this->assertTrue(HandlerRegistry::has('tariffs.pricing'));
    }

    public function test_has_returns_false_for_unknown_handler(): void
    {
        $this->assertFalse(HandlerRegistry::has('nonexistent.handler'));
    }

    public function test_resolve_returns_null_for_unknown_handler(): void
    {
        $this->assertNull(HandlerRegistry::resolve('nonexistent.handler'));
    }

    public function test_register_adds_new_handler(): void
    {
        $this->assertFalse(HandlerRegistry::has('test.custom.handler'));

        HandlerRegistry::register('test.custom.handler', MainMenuHandler::class);

        $this->assertTrue(HandlerRegistry::has('test.custom.handler'));
    }

    public function test_no_duplicate_profile_my_profile_alias(): void
    {
        $handlers = HandlerRegistry::all();
        $this->assertArrayNotHasKey('profile.my_profile', $handlers);
        $this->assertArrayHasKey('profile.my', $handlers);
    }

    public function test_install_handlers_registered(): void
    {
        $this->assertTrue(HandlerRegistry::has('install.android.config'));
        $this->assertTrue(HandlerRegistry::has('install.iphone'));
        $this->assertTrue(HandlerRegistry::has('install.appletv'));
        $this->assertTrue(HandlerRegistry::has('install.windows'));
        $this->assertTrue(HandlerRegistry::has('install.mac'));
    }
}
