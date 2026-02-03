<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use Illuminate\View\View;

/**
 * Контроллер для отображения карты сценария бота.
 */
class BotMapController extends Controller
{
    /**
     * Показать HTML-карту сценария бота.
     */
    public function index(): View
    {
        // Загружаем все экраны с кнопками (eager loading)
        $screens = Screen::with(['buttons' => function ($query) {
            $query->orderBy('order');
        }])->orderBy('key')->get();

        // Группируем по секциям для удобства
        $sections = $screens->groupBy(function ($screen) {
            return $screen->getSection() ?? 'other';
        });

        // Статистика
        $stats = [
            'total_screens' => $screens->count(),
            'total_buttons' => $screens->sum(fn($s) => $s->buttons->count()),
            'screens_with_handlers' => $screens->filter(fn($s) => $s->hasHandler())->count(),
        ];

        return view('admin.bot-map', [
            'screens' => $screens,
            'sections' => $sections,
            'stats' => $stats,
        ]);
    }
}
