<?php

use App\DataMigrations\DataMigration;

return new class extends DataMigration
{
    /**
     * Описание миграции.
     */
    public function description(): string
    {
        return 'Фикс зацикливания навигации: кнопка «Назад» из списка проблем вела в faq.broken вместо install.main, '
            . 'а на экранах install.*.problem отсутствовала кнопка «Назад»';
    }

    /**
     * Run the data migration.
     */
    public function up(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1. Изменяем кнопку «Назад» на экранах faq.broken.{платформа}
        //    Было: Назад → faq.broken (цикл)
        //    Стало: Назад → install.main (выбор устройства)
        // ─────────────────────────────────────────────────────────────

        $faqBrokenScreens = [
            'faq.broken.android',
            'faq.broken.iphone',
            'faq.broken.androidtv',
            'faq.broken.appletv',
            'faq.broken.windows',
            'faq.broken.mac',
        ];

        foreach ($faqBrokenScreens as $screenKey) {
            $this->upsertButton($screenKey, 'Назад', [
                'next_screen_key' => 'install.main',
            ]);
        }

        // ─────────────────────────────────────────────────────────────
        // 2. Добавляем кнопку «Назад» на экранах install.*.problem
        //    Раньше кнопки «Назад» не было — тупик
        // ─────────────────────────────────────────────────────────────

        $problemScreensWithParent = [
            'install.android.problem'              => 'install.android.instructions',
            'install.iphone.problem'               => 'install.iphone',
            'install.iphone.other.problem'         => 'install.iphone.other.instructions',
            'install.appletv.problem'              => 'install.appletv',
            'install.appletv.other.problem'        => 'install.appletv.other.instructions',
            'install.windows.problem'              => 'install.windows',
            'install.windows.other.problem'        => 'install.windows.other.instructions',
            'install.mac.problem'                  => 'install.mac',
            'install.mac.other.problem'            => 'install.mac.other.instructions',
        ];

        foreach ($problemScreensWithParent as $screenKey => $parentScreenKey) {
            $this->upsertButton($screenKey, 'Назад', [
                'next_screen_key' => $parentScreenKey,
                'order' => 10,
                'row' => 0,
            ]);
        }
    }

    /**
     * Reverse the data migration.
     */
    public function down(): void
    {
        // Возвращаем кнопки «Назад» на faq.broken.* обратно на faq.broken
        $faqBrokenScreens = [
            'faq.broken.android',
            'faq.broken.iphone',
            'faq.broken.androidtv',
            'faq.broken.appletv',
            'faq.broken.windows',
            'faq.broken.mac',
        ];

        foreach ($faqBrokenScreens as $screenKey) {
            $this->upsertButton($screenKey, 'Назад', [
                'next_screen_key' => 'faq.broken',
            ]);
        }

        // Удаляем добавленные кнопки «Назад» с install.*.problem
        $problemScreens = [
            'install.android.problem',
            'install.iphone.problem',
            'install.iphone.other.problem',
            'install.appletv.problem',
            'install.appletv.other.problem',
            'install.windows.problem',
            'install.windows.other.problem',
            'install.mac.problem',
            'install.mac.other.problem',
        ];

        foreach ($problemScreens as $screenKey) {
            $this->deleteButton($screenKey, 'Назад');
        }
    }
};
