<?php

namespace Tests\Unit;

use App\Bot\Handlers\Concerns\BuildsButtons;
use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты трейта BuildsButtons.
 */
class BuildsButtonsTraitTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_buttons_from_screen(): void
    {
        $screen = Screen::create([
            'key' => 'test.screen',
            'title' => 'Test',
            'text' => 'Test text',
        ]);

        ScreenButton::create([
            'screen_id' => $screen->id,
            'text' => 'Кнопка 1',
            'next_screen_key' => 'screen.one',
            'order' => 1,
            'row' => 0,
        ]);

        ScreenButton::create([
            'screen_id' => $screen->id,
            'text' => 'Кнопка 2',
            'next_screen_key' => 'screen.two',
            'order' => 2,
            'row' => 1,
        ]);

        $handler = new class {
            use BuildsButtons;

            public function getButtons(Screen $screen): array
            {
                return $this->buildButtons($screen);
            }
        };

        $buttons = $handler->getButtons($screen->fresh());

        $this->assertCount(2, $buttons);
        $this->assertEquals('Кнопка 1', $buttons[0]['text']);
        $this->assertEquals('screen.one', $buttons[0]['callback_data']);
        $this->assertEquals('Кнопка 2', $buttons[1]['text']);
        $this->assertEquals('screen.two', $buttons[1]['callback_data']);
    }

    public function test_buttons_without_next_screen_get_noop(): void
    {
        $screen = Screen::create([
            'key' => 'test.noop',
            'title' => 'Test',
            'text' => 'Test text',
        ]);

        ScreenButton::create([
            'screen_id' => $screen->id,
            'text' => 'Пустая кнопка',
            'next_screen_key' => null,
            'order' => 1,
        ]);

        $handler = new class {
            use BuildsButtons;

            public function getButtons(Screen $screen): array
            {
                return $this->buildButtons($screen);
            }
        };

        $buttons = $handler->getButtons($screen->fresh());

        $this->assertEquals('noop', $buttons[0]['callback_data']);
    }
}
