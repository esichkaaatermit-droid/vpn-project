<?php

namespace App\Bot\Handlers\Concerns;

use App\Models\Screen;

/**
 * Трейт для построения массива inline-кнопок из экрана.
 *
 * Используется обработчиками, которые берут кнопки из БД (Screen → ScreenButton).
 */
trait BuildsButtons
{
    /**
     * Построить массив кнопок из модели экрана.
     *
     * @param Screen $screen Экран с загруженными кнопками
     * @return array<int, array{text: string, callback_data: string, row?: int}>
     */
    protected function buildButtons(Screen $screen): array
    {
        $buttons = [];

        foreach ($screen->buttons as $button) {
            $buttons[] = [
                'text' => $button->text,
                'callback_data' => $button->next_screen_key ?? 'noop',
                'row' => $button->row ?? 0,
            ];
        }

        return $buttons;
    }
}
