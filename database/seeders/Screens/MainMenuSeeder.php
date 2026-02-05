<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class MainMenuSeeder extends Seeder
{
    /**
     * Сидер главного меню.
     */
    public function run(): void
    {
        $mainMenu = Screen::create([
            'key' => 'main.menu',
            'title' => 'Главное меню',
            'text' => 'Добро пожаловать! Выберите интересующий вас раздел:',
            'handler_id' => 'main.menu',
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Установить Easy Light',
            'next_screen_key' => 'install.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Вопросы и ответы',
            'next_screen_key' => 'faq.main',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Тарифы',
            'next_screen_key' => 'tariffs.main',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Профиль',
            'next_screen_key' => 'profile.main',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Документация',
            'next_screen_key' => 'docs.main',
            'order' => 5,
        ]);
    }
}
