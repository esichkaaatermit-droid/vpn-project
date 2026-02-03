<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use Illuminate\View\View;

class BotMapController extends Controller
{
    /**
     * Display the bot map.
     */
    public function index(): View
    {
        $screens = Screen::with('buttons')->get();
        
        // Подготавливаем данные для визуализации
        $nodes = [];
        $edges = [];
        
        foreach ($screens as $screen) {
            // Определяем цвет узла по секции
            $section = $screen->getSection();
            $color = $this->getSectionColor($section);
            
            $nodes[] = [
                'id' => $screen->key,
                'label' => $screen->title ?? $screen->key,
                'title' => $this->truncateText($screen->text, 100),
                'color' => $color,
                'hasHandler' => $screen->hasHandler(),
                'handlerId' => $screen->handler_id,
                'section' => $section,
            ];
            
            foreach ($screen->buttons as $button) {
                if ($button->next_screen_key) {
                    $edges[] = [
                        'from' => $screen->key,
                        'to' => $button->next_screen_key,
                        'label' => $button->text,
                    ];
                }
            }
        }
        
        // Группируем узлы по секциям
        $sections = $screens->groupBy(function ($screen) {
            return $screen->getSection() ?? 'other';
        })->map->count();

        return view('admin.bot-map', [
            'nodes' => $nodes,
            'edges' => $edges,
            'screens' => $screens,
            'sections' => $sections,
        ]);
    }

    /**
     * Get color for section.
     */
    protected function getSectionColor(?string $section): string
    {
        return match ($section) {
            'start' => '#4CAF50',      // Зеленый
            'faq' => '#2196F3',        // Синий
            'tariffs' => '#FF9800',    // Оранжевый
            'troubleshoot' => '#F44336', // Красный
            'account' => '#9C27B0',    // Фиолетовый
            'support' => '#00BCD4',    // Голубой
            'settings' => '#795548',   // Коричневый
            default => '#607D8B',      // Серый
        };
    }

    /**
     * Truncate text.
     */
    protected function truncateText(string $text, int $length): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '...';
    }
}
