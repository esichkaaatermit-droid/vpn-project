<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:migrate 
                            {--rollback : Rollback the last batch of migrations}
                            {--step=1 : Number of batches to rollback}
                            {--status : Show the status of each migration}';

    /**
     * The console command description.
     */
    protected $description = 'Run data migrations for bot structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Проверяем существование таблицы
        if (!$this->tableExists()) {
            $this->error('Table "data_migrations" does not exist. Run: php artisan migrate');
            return self::FAILURE;
        }

        if ($this->option('status')) {
            return $this->showStatus();
        }

        if ($this->option('rollback')) {
            return $this->rollback();
        }

        return $this->migrate();
    }

    /**
     * Run pending migrations.
     */
    protected function migrate(): int
    {
        $migrations = $this->getPendingMigrations();

        if (empty($migrations)) {
            $this->info('Nothing to migrate.');
            return self::SUCCESS;
        }

        $batch = $this->getNextBatchNumber();
        $count = count($migrations);

        $this->info("Running {$count} migration(s)...");
        $this->newLine();

        foreach ($migrations as $migration) {
            $this->runMigration($migration, $batch);
        }

        $this->newLine();
        $this->info("Migrated {$count} migration(s) successfully.");

        return self::SUCCESS;
    }

    /**
     * Rollback migrations.
     */
    protected function rollback(): int
    {
        $steps = (int) $this->option('step');
        $migrations = $this->getMigrationsToRollback($steps);

        if (empty($migrations)) {
            $this->info('Nothing to rollback.');
            return self::SUCCESS;
        }

        $count = count($migrations);
        $this->info("Rolling back {$count} migration(s)...");
        $this->newLine();

        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration);
        }

        $this->newLine();
        $this->info("Rolled back {$count} migration(s) successfully.");

        return self::SUCCESS;
    }

    /**
     * Show migration status.
     */
    protected function showStatus(): int
    {
        $ran = $this->getRanMigrations();
        $all = $this->getAllMigrationFiles();

        $rows = [];
        foreach ($all as $file) {
            $name = $this->getMigrationName($file);
            $rows[] = [
                $name,
                in_array($name, $ran) ? '<info>Ran</info>' : '<comment>Pending</comment>',
            ];
        }

        $this->table(['Migration', 'Status'], $rows);

        return self::SUCCESS;
    }

    /**
     * Run a single migration.
     */
    protected function runMigration(string $file, int $batch): void
    {
        $name = $this->getMigrationName($file);
        $instance = $this->resolve($file);

        $description = $instance->description();
        $this->components->task(
            $name . ($description ? " ({$description})" : ''),
            function () use ($instance, $name, $batch) {
                DB::transaction(function () use ($instance, $name, $batch) {
                    $instance->up();

                    DB::table('data_migrations')->insert([
                        'migration' => $name,
                        'batch' => $batch,
                    ]);
                });
            }
        );
    }

    /**
     * Rollback a single migration.
     */
    protected function rollbackMigration(object $migration): void
    {
        $file = $this->getMigrationPath() . '/' . $migration->migration . '.php';

        if (!File::exists($file)) {
            $this->warn("Migration file not found: {$migration->migration}");
            DB::table('data_migrations')->where('id', $migration->id)->delete();
            return;
        }

        $instance = $this->resolve($file);

        $this->components->task($migration->migration, function () use ($instance, $migration) {
            DB::transaction(function () use ($instance, $migration) {
                $instance->down();
                DB::table('data_migrations')->where('id', $migration->id)->delete();
            });
        });
    }

    /**
     * Get pending migrations.
     */
    protected function getPendingMigrations(): array
    {
        $ran = $this->getRanMigrations();
        $all = $this->getAllMigrationFiles();

        return array_filter($all, function ($file) use ($ran) {
            return !in_array($this->getMigrationName($file), $ran);
        });
    }

    /**
     * Get ran migrations.
     */
    protected function getRanMigrations(): array
    {
        return DB::table('data_migrations')
            ->orderBy('batch')
            ->orderBy('migration')
            ->pluck('migration')
            ->toArray();
    }

    /**
     * Get migrations to rollback.
     */
    protected function getMigrationsToRollback(int $steps): array
    {
        return DB::table('data_migrations')
            ->where('batch', '>=', $this->getLastBatchNumber() - $steps + 1)
            ->orderByDesc('batch')
            ->orderByDesc('migration')
            ->get()
            ->toArray();
    }

    /**
     * Get all migration files.
     */
    protected function getAllMigrationFiles(): array
    {
        $path = $this->getMigrationPath();

        if (!File::isDirectory($path)) {
            return [];
        }

        $files = File::glob($path . '/*_*.php');
        sort($files);

        return $files;
    }

    /**
     * Get migration name from file path.
     */
    protected function getMigrationName(string $file): string
    {
        return str_replace('.php', '', basename($file));
    }

    /**
     * Resolve migration instance.
     */
    protected function resolve(string $file): object
    {
        $class = require $file;

        if (is_object($class)) {
            return $class;
        }

        throw new \RuntimeException("Migration file must return a class instance: {$file}");
    }

    /**
     * Get next batch number.
     */
    protected function getNextBatchNumber(): int
    {
        return $this->getLastBatchNumber() + 1;
    }

    /**
     * Get last batch number.
     */
    protected function getLastBatchNumber(): int
    {
        return (int) DB::table('data_migrations')->max('batch');
    }

    /**
     * Get migration path.
     */
    protected function getMigrationPath(): string
    {
        return database_path('data_migrations');
    }

    /**
     * Check if table exists.
     */
    protected function tableExists(): bool
    {
        return DB::getSchemaBuilder()->hasTable('data_migrations');
    }
}
