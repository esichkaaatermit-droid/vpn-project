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

        // Генерируем текстовое дерево
        $screensByKey = $screens->keyBy('key');
        $tree = $this->generateTextTree($screensByKey);

        // Проверяем битые ссылки
        $brokenLinks = $this->findBrokenLinks($screens);

        return view('admin.bot-map', [
            'screens' => $screens,
            'sections' => $sections,
            'stats' => $stats,
            'tree' => $tree,
            'brokenLinks' => $brokenLinks,
        ]);
    }

    /**
     * Генерировать текстовое дерево структуры бота.
     */
    protected function generateTextTree($screensByKey): string
    {
        $lines = [];
        $visited = [];

        // Начинаем с главного меню
        $mainMenu = $screensByKey->get('main.menu');
        if ($mainMenu) {
            $this->buildTreeBranch($mainMenu, $screensByKey, $lines, '', true, $visited, 0);
        }

        return implode("\n", $lines);
    }

    /**
     * Рекурсивно строить ветку дерева.
     */
    protected function buildTreeBranch($screen, $screensByKey, &$lines, $prefix, $isLast, &$visited, $depth): void
    {
        // Ограничиваем глубину чтобы избежать бесконечной рекурсии
        if ($depth > 10) {
            return;
        }

        $key = $screen->key;
        
        // Проверяем, посещали ли мы этот экран на текущем пути
        if (isset($visited[$key]) && $visited[$key] > 2) {
            $connector = $isLast ? '└── ' : '├── ';
            $lines[] = $prefix . $connector . "↩️ {$key} (цикл)";
            return;
        }
        $visited[$key] = ($visited[$key] ?? 0) + 1;

        // Определяем символы для дерева
        $connector = $isLast ? '└── ' : '├── ';
        $childPrefix = $prefix . ($isLast ? '    ' : '│   ');

        // Иконка секции
        $icon = $this->getSectionIcon($screen->getSection());
        
        // Название экрана
        $title = $screen->title ?: $key;
        $lines[] = $prefix . $connector . "{$icon} <b>{$title}</b> <span class=\"text-gray-400\">({$key})</span>";
        
        // Текст экрана (укороченный)
        if ($screen->text) {
            $shortText = mb_substr($screen->text, 0, 60);
            if (mb_strlen($screen->text) > 60) $shortText .= '...';
            $shortText = str_replace("\n", " ", $shortText);
            $lines[] = $childPrefix . "<span class=\"text-gray-500 text-sm\">\"{$shortText}\"</span>";
        }

        // Кнопки
        $buttons = $screen->buttons;
        $buttonCount = $buttons->count();
        
        foreach ($buttons as $index => $button) {
            $isLastButton = ($index === $buttonCount - 1);
            $buttonConnector = $isLastButton ? '└── ' : '├── ';
            
            $buttonText = $button->text;
            $nextKey = $button->next_screen_key;
            
            if ($nextKey) {
                $nextScreen = $screensByKey->get($nextKey);
                
                if ($nextScreen) {
                    // Если это "Назад" или ведёт на уже посещённый экран - просто показываем ссылку
                    $isBack = stripos($buttonText, 'назад') !== false || 
                              stripos($buttonText, 'главное меню') !== false ||
                              stripos($buttonText, 'другие устройства') !== false;
                    
                    if ($isBack || (isset($visited[$nextKey]) && $visited[$nextKey] > 0)) {
                        $lines[] = $childPrefix . $buttonConnector . "🔘 {$buttonText} → <span class=\"text-blue-500\">{$nextKey}</span>";
                    } else {
                        // Рекурсивно показываем вложенный экран
                        $lines[] = $childPrefix . $buttonConnector . "🔘 {$buttonText} ↓";
                        $this->buildTreeBranch($nextScreen, $screensByKey, $lines, $childPrefix . ($isLastButton ? '    ' : '│   '), true, $visited, $depth + 1);
                    }
                } else {
                    $lines[] = $childPrefix . $buttonConnector . "🔘 {$buttonText} → <span class=\"text-red-500\">❌ {$nextKey}</span>";
                }
            } else {
                $lines[] = $childPrefix . $buttonConnector . "🔘 {$buttonText}";
            }
        }

        $visited[$key]--;
    }

    /**
     * Получить иконку для секции.
     */
    protected function getSectionIcon(?string $section): string
    {
        return match($section) {
            'main' => '🏠',
            'install' => '📲',
            'faq' => '❓',
            'tariffs' => '💰',
            'profile' => '👤',
            'docs' => '📄',
            default => '📁',
        };
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
