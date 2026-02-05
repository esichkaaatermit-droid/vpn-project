<?php

namespace Database\Seeders;

use Database\Seeders\Screens\DocsSeeder;
use Database\Seeders\Screens\FaqSeeder;
use Database\Seeders\Screens\InstallSeeder;
use Database\Seeders\Screens\MainMenuSeeder;
use Database\Seeders\Screens\ProfileSeeder;
use Database\Seeders\Screens\TariffsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScreensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Вызывает все сидеры экранов в правильном порядке,
     * затем валидирует связи между экранами.
     */
    public function run(): void
    {
        // Вызываем сидеры в порядке главного меню
        $this->call([
            MainMenuSeeder::class,   // 1. Главное меню
            InstallSeeder::class,    // 2. Установить Easy Light
            FaqSeeder::class,        // 3. Вопросы и ответы + Что-то сломалось
            TariffsSeeder::class,    // 4. Тарифы
            ProfileSeeder::class,    // 5. Профиль
            DocsSeeder::class,       // 6. Документация
        ]);

        // Валидация связей после сидинга
        $this->validateScreenLinks();

        $this->command->info('All screens seeded successfully!');
    }

    /**
     * Валидация связей между экранами.
     * 
     * Проверяет, что все next_screen_key в кнопках
     * ссылаются на существующие экраны.
     */
    protected function validateScreenLinks(): void
    {
        $this->command->info('Validating screen links...');

        // Получаем все существующие ключи экранов
        $existingKeys = DB::table('screens')->pluck('key')->toArray();

        // Получаем все кнопки с next_screen_key
        $buttons = DB::table('screen_buttons')
            ->whereNotNull('next_screen_key')
            ->join('screens', 'screen_buttons.screen_id', '=', 'screens.id')
            ->select('screen_buttons.text', 'screen_buttons.next_screen_key', 'screens.key as screen_key')
            ->get();

        $errors = [];

        foreach ($buttons as $button) {
            if (!in_array($button->next_screen_key, $existingKeys)) {
                $errors[] = [
                    'screen' => $button->screen_key,
                    'button' => $button->text,
                    'missing_key' => $button->next_screen_key,
                ];
            }
        }

        if (empty($errors)) {
            $this->command->info('✓ All screen links are valid!');
        } else {
            $this->command->warn('⚠ Found ' . count($errors) . ' broken link(s):');
            foreach ($errors as $error) {
                $this->command->error(
                    "  Screen '{$error['screen']}' → Button '{$error['button']}' → Missing: '{$error['missing_key']}'"
                );
            }
        }
    }
}
