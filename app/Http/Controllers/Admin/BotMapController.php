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

        // Генерируем деревья для каждой секции
        $screensByKey = $screens->keyBy('key');
        $trees = $this->generateTreesPerSection($screensByKey);

        // Проверяем битые ссылки
        $brokenLinks = $this->findBrokenLinks($screens);

        return view('admin.bot-map', [
            'screens' => $screens,
            'sections' => $sections,
            'stats' => $stats,
            'trees' => $trees,
            'brokenLinks' => $brokenLinks,
        ]);
    }

    /**
     * Генерировать деревья для каждой секции бота.
     */
    protected function generateTreesPerSection($screensByKey): array
    {
        $trees = [];
        
        // Главное меню + первый уровень кнопок
        $trees['main'] = $this->generateSectionTree('main.menu', $screensByKey, 1);
        
        // Основные ветки
        $mainBranches = [
            'install' => 'install.main',
            'faq' => 'faq.main',
            'tariffs' => 'tariffs.main',
            'profile' => 'profile.main',
            'docs' => 'docs.main',
        ];
        
        foreach ($mainBranches as $name => $startKey) {
            $trees[$name] = $this->generateSectionTree($startKey, $screensByKey);
        }
        
        return $trees;
    }
    
    /**
     * Генерировать дерево для конкретной секции.
     */
    protected function generateSectionTree(string $startKey, $screensByKey, int $maxDepth = 15): string
    {
        $visited = [];
        
        $startScreen = $screensByKey->get($startKey);
        if ($startScreen) {
            return $this->buildTreeBranch($startScreen, $screensByKey, $visited, 0, $maxDepth);
        }
        
        return '';
    }

    /**
     * Рекурсивно строить ветку дерева (HTML с поддержкой сворачивания).
     */
    protected function buildTreeBranch($screen, $screensByKey, &$visited, $depth, $maxDepth = 15): string
    {
        // Ограничиваем глубину чтобы избежать бесконечной рекурсии
        if ($depth > $maxDepth) {
            return '';
        }

        $key = $screen->key;
        
        // Проверяем, посещали ли мы этот экран на текущем пути
        if (isset($visited[$key]) && $visited[$key] > 2) {
            return "<div class=\"tree-item\"><span class=\"text-gray-400\">↩️ {$key} (цикл)</span></div>";
        }
        $visited[$key] = ($visited[$key] ?? 0) + 1;

        // Иконка секции
        $icon = $this->getSectionIcon($screen->getSection());
        
        // Название экрана
        $title = e($screen->title ?: $key);
        
        // Текст экрана (укороченный)
        $textHtml = '';
        if ($screen->text) {
            $shortText = mb_substr($screen->text, 0, 60);
            if (mb_strlen($screen->text) > 60) $shortText .= '...';
            $shortText = e(str_replace("\n", " ", $shortText));
            $textHtml = "<div class=\"text-gray-500 text-sm ml-6\">\"{$shortText}\"</div>";
        }

        // Собираем кнопки
        $buttons = $screen->buttons;
        $buttonsHtml = '';
        
        foreach ($buttons as $button) {
            $buttonText = e($button->text);
            $nextKey = $button->next_screen_key;
            
            if ($nextKey) {
                $nextScreen = $screensByKey->get($nextKey);
                
                if ($nextScreen) {
                    // Если это "Назад" или ведёт на уже посещённый экран - просто показываем ссылку
                    $isBack = stripos($button->text, 'назад') !== false || 
                              stripos($button->text, 'главное меню') !== false ||
                              stripos($button->text, 'другие устройства') !== false;
                    
                    // Проверяем межсекционный переход (из одной ветки в другую)
                    $currentSection = $screen->getSection();
                    $nextSection = $nextScreen->getSection();
                    $isCrossSection = $currentSection !== $nextSection;
                    
                    if ($isBack || $isCrossSection || (isset($visited[$nextKey]) && $visited[$nextKey] > 0)) {
                        $buttonsHtml .= "<div class=\"tree-item tree-button\">🔘 {$buttonText} → <span class=\"text-blue-500\">{$nextKey}</span></div>";
                    } else {
                        // Рекурсивно показываем вложенный экран
                        $childHtml = $this->buildTreeBranch($nextScreen, $screensByKey, $visited, $depth + 1, $maxDepth);
                        $buttonsHtml .= "<details class=\"tree-details\" open><summary class=\"tree-button cursor-pointer hover:bg-gray-100 rounded\">🔘 {$buttonText} ↓</summary><div class=\"tree-children\">{$childHtml}</div></details>";
                    }
                } else {
                    $buttonsHtml .= "<div class=\"tree-item tree-button\">🔘 {$buttonText} → <span class=\"text-red-500\">❌ {$nextKey}</span></div>";
                }
            } else {
                $buttonsHtml .= "<div class=\"tree-item tree-button\">🔘 {$buttonText}</div>";
            }
        }

        $visited[$key]--;
        
        $html = "<div class=\"tree-screen\" data-key=\"{$key}\">";
        $html .= "<div class=\"tree-header\">{$icon} <b>{$title}</b> <span class=\"text-gray-400\">({$key})</span></div>";
        $html .= $textHtml;
        $html .= "<div class=\"tree-buttons\">{$buttonsHtml}</div>";
        $html .= "</div>";
        
        return $html;
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
