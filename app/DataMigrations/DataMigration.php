<?php

namespace App\DataMigrations;

use Illuminate\Support\Facades\DB;

abstract class DataMigration
{
    /**
     * Run the data migration.
     */
    abstract public function up(): void;

    /**
     * Reverse the data migration (optional).
     */
    public function down(): void
    {
        // По умолчанию откат не реализован
    }

    /**
     * Get the migration description.
     */
    public function description(): string
    {
        return '';
    }

    /**
     * Helper: Update or create a screen.
     */
    protected function upsertScreen(string $key, array $data): int
    {
        return DB::table('screens')->updateOrInsert(
            ['key' => $key],
            array_merge($data, ['updated_at' => now()])
        );
    }

    /**
     * Helper: Update or create a button.
     */
    protected function upsertButton(string $screenKey, string $buttonText, array $data): void
    {
        $screen = DB::table('screens')->where('key', $screenKey)->first();
        
        if (!$screen) {
            throw new \RuntimeException("Screen '{$screenKey}' not found");
        }

        DB::table('screen_buttons')->updateOrInsert(
            ['screen_id' => $screen->id, 'text' => $buttonText],
            array_merge($data, ['updated_at' => now()])
        );
    }

    /**
     * Helper: Update screen text.
     */
    protected function updateScreenText(string $key, string $newText): int
    {
        return DB::table('screens')
            ->where('key', $key)
            ->update(['text' => $newText, 'updated_at' => now()]);
    }

    /**
     * Helper: Update button text.
     */
    protected function updateButtonText(string $screenKey, string $oldText, string $newText): int
    {
        $screen = DB::table('screens')->where('key', $screenKey)->first();
        
        if (!$screen) {
            return 0;
        }

        return DB::table('screen_buttons')
            ->where('screen_id', $screen->id)
            ->where('text', $oldText)
            ->update(['text' => $newText, 'updated_at' => now()]);
    }

    /**
     * Helper: Delete a screen and its buttons.
     */
    protected function deleteScreen(string $key): void
    {
        $screen = DB::table('screens')->where('key', $key)->first();
        
        if ($screen) {
            DB::table('screen_buttons')->where('screen_id', $screen->id)->delete();
            DB::table('screens')->where('id', $screen->id)->delete();
        }
    }

    /**
     * Helper: Delete a button.
     */
    protected function deleteButton(string $screenKey, string $buttonText): int
    {
        $screen = DB::table('screens')->where('key', $screenKey)->first();
        
        if (!$screen) {
            return 0;
        }

        return DB::table('screen_buttons')
            ->where('screen_id', $screen->id)
            ->where('text', $buttonText)
            ->delete();
    }
}
