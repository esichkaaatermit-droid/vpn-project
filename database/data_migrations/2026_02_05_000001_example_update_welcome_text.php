<?php

use App\DataMigrations\DataMigration;

return new class extends DataMigration
{
    /**
     * Описание миграции.
     */
    public function description(): string
    {
        return 'Пример: обновление приветственного текста';
    }

    /**
     * Run the data migration.
     */
    public function up(): void
    {
        // Это пример миграции данных.
        // Раскомментируйте и измените по необходимости:
        
        // $this->updateScreenText(
        //     'main.menu', 
        //     'Добро пожаловать в Easy Light VPN! Выберите интересующий вас раздел:'
        // );
    }

    /**
     * Reverse the data migration.
     */
    public function down(): void
    {
        // $this->updateScreenText(
        //     'main.menu',
        //     'Добро пожаловать! Выберите интересующий вас раздел:'
        // );
    }
};
