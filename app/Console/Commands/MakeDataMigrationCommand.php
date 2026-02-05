<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDataMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:data-migration {name : The name of the migration}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new data migration file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = Str::snake($this->argument('name'));
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$name}.php";
        $path = database_path("data_migrations/{$filename}");

        // Создаём директорию если не существует
        File::ensureDirectoryExists(database_path('data_migrations'));

        // Генерируем содержимое
        $stub = $this->getStub($name);

        File::put($path, $stub);

        $this->components->info("Data migration [{$filename}] created successfully.");

        return self::SUCCESS;
    }

    /**
     * Get the migration stub.
     */
    protected function getStub(string $name): string
    {
        $className = Str::studly($name);

        return <<<PHP
<?php

use App\DataMigrations\DataMigration;

return new class extends DataMigration
{
    /**
     * Описание миграции.
     */
    public function description(): string
    {
        return '';
    }

    /**
     * Run the data migration.
     */
    public function up(): void
    {
        // Примеры:
        
        // Обновить текст экрана:
        // \$this->updateScreenText('main.menu', 'Новый текст приветствия');
        
        // Обновить текст кнопки:
        // \$this->updateButtonText('main.menu', 'Старый текст', 'Новый текст');
        
        // Создать/обновить экран:
        // \$this->upsertScreen('new.screen', [
        //     'title' => 'Новый экран',
        //     'text' => 'Текст экрана',
        //     'handler_id' => 'new.screen',
        // ]);
        
        // Создать/обновить кнопку:
        // \$this->upsertButton('main.menu', 'Новая кнопка', [
        //     'next_screen_key' => 'new.screen',
        //     'order' => 6,
        // ]);
        
        // Удалить экран и его кнопки:
        // \$this->deleteScreen('old.screen');
        
        // Удалить кнопку:
        // \$this->deleteButton('main.menu', 'Удаляемая кнопка');
    }

    /**
     * Reverse the data migration.
     */
    public function down(): void
    {
        // Откат изменений (опционально)
    }
};
PHP;
    }
}
