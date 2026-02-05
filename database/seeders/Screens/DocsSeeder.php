<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class DocsSeeder extends Seeder
{
    /**
     * Сидер ветки "Docs".
     */
    public function run(): void
    {
        $docsMain = Screen::create([
            'key' => 'docs.main',
            'title' => 'Документация',
            'text' => 'Выберите документ:',
            'handler_id' => 'docs.main',
        ]);

        ScreenButton::create([
            'screen_id' => $docsMain->id,
            'text' => 'Политика конфиденциальности',
            'next_screen_key' => 'docs.privacy',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $docsMain->id,
            'text' => 'Пользовательское соглашение',
            'next_screen_key' => 'docs.terms',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $docsMain->id,
            'text' => 'Политика обработки персональных данных',
            'next_screen_key' => 'docs.personal_data',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $docsMain->id,
            'text' => 'Условия возврата',
            'next_screen_key' => 'docs.refund',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $docsMain->id,
            'text' => 'Назад',
            'next_screen_key' => 'main.menu',
            'order' => 5,
        ]);

        // Политика конфиденциальности
        $docsPrivacy = Screen::create([
            'key' => 'docs.privacy',
            'title' => 'Политика конфиденциальности',
            'text' => 'Текст политики конфиденциальности',
            'handler_id' => 'docs.privacy',
        ]);

        ScreenButton::create([
            'screen_id' => $docsPrivacy->id,
            'text' => 'Назад',
            'next_screen_key' => 'docs.main',
            'order' => 1,
        ]);

        // Пользовательское соглашение
        $docsTerms = Screen::create([
            'key' => 'docs.terms',
            'title' => 'Пользовательское соглашение',
            'text' => 'Текст пользовательского соглашения',
            'handler_id' => 'docs.terms',
        ]);

        ScreenButton::create([
            'screen_id' => $docsTerms->id,
            'text' => 'Назад',
            'next_screen_key' => 'docs.main',
            'order' => 1,
        ]);

        // Политика обработки персональных данных
        $docsPersonalData = Screen::create([
            'key' => 'docs.personal_data',
            'title' => 'Политика обработки персональных данных',
            'text' => 'Текст политики обработки персональных данных',
            'handler_id' => 'docs.personal_data',
        ]);

        ScreenButton::create([
            'screen_id' => $docsPersonalData->id,
            'text' => 'Назад',
            'next_screen_key' => 'docs.main',
            'order' => 1,
        ]);

        // Условия возврата
        $docsRefund = Screen::create([
            'key' => 'docs.refund',
            'title' => 'Условия возврата',
            'text' => 'Текст условий возврата',
            'handler_id' => 'docs.refund',
        ]);

        ScreenButton::create([
            'screen_id' => $docsRefund->id,
            'text' => 'Назад',
            'next_screen_key' => 'docs.main',
            'order' => 1,
        ]);
    }
}
