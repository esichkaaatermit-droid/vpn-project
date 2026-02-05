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

        // Генерируем Mermaid-диаграмму
        $mermaid = $this->generateMermaidDiagram($screens);

        // Проверяем битые ссылки
        $brokenLinks = $this->findBrokenLinks($screens);

        return view('admin.bot-map', [
            'screens' => $screens,
            'sections' => $sections,
            'stats' => $stats,
            'mermaid' => $mermaid,
            'brokenLinks' => $brokenLinks,
        ]);
    }

    /**
     * Генерировать Mermaid-диаграмму связей.
     */
    protected function generateMermaidDiagram($screens): string
    {
        $lines = ['flowchart TD'];
        $allKeys = $screens->pluck('key')->toArray();

        // Стили для разных секций
        $lines[] = '    classDef main fill:#10b981,color:#fff';
        $lines[] = '    classDef install fill:#3b82f6,color:#fff';
        $lines[] = '    classDef faq fill:#8b5cf6,color:#fff';
        $lines[] = '    classDef tariffs fill:#f59e0b,color:#fff';
        $lines[] = '    classDef profile fill:#ec4899,color:#fff';
        $lines[] = '    classDef docs fill:#6b7280,color:#fff';
        $lines[] = '';

        foreach ($screens as $screen) {
            $fromId = $this->sanitizeNodeId($screen->key);
            $fromLabel = $screen->title ?: $screen->key;
            
            // Добавляем узел с меткой
            $lines[] = "    {$fromId}[\"{$fromLabel}\"]";
            
            foreach ($screen->buttons as $button) {
                if ($button->next_screen_key && in_array($button->next_screen_key, $allKeys)) {
                    $toId = $this->sanitizeNodeId($button->next_screen_key);
                    $lines[] = "    {$fromId} --> {$toId}";
                }
            }
        }

        // Применяем стили
        $lines[] = '';
        foreach ($screens as $screen) {
            $nodeId = $this->sanitizeNodeId($screen->key);
            $section = $screen->getSection() ?? 'other';
            if (in_array($section, ['main', 'install', 'faq', 'tariffs', 'profile', 'docs'])) {
                $lines[] = "    class {$nodeId} {$section}";
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Преобразовать ключ в валидный ID для Mermaid.
     */
    protected function sanitizeNodeId(string $key): string
    {
        return str_replace(['.', '-', ' '], '_', $key);
    }

    /**
     * Найти битые ссылки (кнопки ведущие на несуществующие экраны).
     */
    protected function findBrokenLinks($screens): array
    {
        $allKeys = $screens->pluck('key')->toArray();
        $brokenLinks = [];

        foreach ($screens as $screen) {
            foreach ($screen->buttons as $button) {
                if ($button->next_screen_key && !in_array($button->next_screen_key, $allKeys)) {
                    $brokenLinks[] = [
                        'screen' => $screen->key,
                        'button' => $button->text,
                        'missing' => $button->next_screen_key,
                    ];
                }
            }
        }

        return $brokenLinks;
    }
}
