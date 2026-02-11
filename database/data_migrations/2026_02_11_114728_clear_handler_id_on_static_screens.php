<?php

use App\DataMigrations\DataMigration;
use App\Bot\HandlerRegistry;
use App\Models\Screen;

return new class extends DataMigration
{
    /**
     * Описание миграции.
     */
    public function description(): string
    {
        return 'Очистить handler_id у статических экранов, которые не имеют обработчика в HandlerRegistry';
    }

    /**
     * Run the data migration.
     */
    public function up(): void
    {
        // Экраны с handler_id, для которых нет обработчика в реестре — это статические экраны.
        // handler_id должен быть только у экранов с динамической логикой.
        $screens = Screen::whereNotNull('handler_id')->get();

        $cleared = 0;
        foreach ($screens as $screen) {
            if (!HandlerRegistry::has($screen->handler_id)) {
                $screen->handler_id = null;
                $screen->save();
                $cleared++;
            }
        }

        echo "  Очищено handler_id у {$cleared} статических экранов.\n";
    }

    /**
     * Reverse the data migration.
     */
    public function down(): void
    {
        // Откат: восстановить handler_id = key для всех экранов
        // (не рекомендуется — оригинальное поведение было ошибочным)
    }
};
