<?php

namespace App\Console\Commands;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Команда для генерации карты сценария бота.
 * 
 * Юзкейс: изменил что-то в БД → прогнал команду → получил файл/картинку
 * для проверки, что всё собрано корректно.
 */
class BotMapCommand extends Command
{
    protected $signature = 'bot:map 
                            {--format=md : Формат вывода (md, html, json)}
                            {--output= : Путь к файлу для сохранения}
                            {--no-file : Только вывод в консоль, без сохранения файла}';

    protected $description = 'Генерация карты сценария бота с проверкой на ошибки';

    protected Collection $screens;
    protected Collection $allKeys;
    protected array $brokenLinks = [];

    public function handle(): int
    {
        $this->info('🤖 Генерация карты сценария бота...');
        $this->newLine();

        // Загружаем данные
        $this->screens = Screen::with(['buttons' => fn($q) => $q->orderBy('order')])->get();
        $this->allKeys = $this->screens->pluck('key');

        // Проверяем битые ссылки
        $this->checkBrokenLinks();

        // Статистика
        $this->outputStats();

        // Битые ссылки
        if (!empty($this->brokenLinks)) {
            $this->outputBrokenLinks();
        }

        // Генерируем карту
        $format = $this->option('format');
        $content = match ($format) {
            'html' => $this->generateHtml(),
            'json' => $this->generateJson(),
            default => $this->generateMarkdown(),
        };

        // Сохраняем или выводим
        if (!$this->option('no-file')) {
            $this->saveToFile($content, $format);
        }

        // Итог
        $this->newLine();
        if (empty($this->brokenLinks)) {
            $this->info('✅ Карта сгенерирована без ошибок');
            return Command::SUCCESS;
        } else {
            $this->warn('⚠️  Карта сгенерирована, но есть битые ссылки!');
            return Command::FAILURE;
        }
    }

    /**
     * Проверка битых ссылок.
     */
    protected function checkBrokenLinks(): void
    {
        $buttons = ScreenButton::whereNotNull('next_screen_key')
            ->whereNotIn('next_screen_key', $this->allKeys)
            ->with('screen')
            ->get();

        foreach ($buttons as $button) {
            $this->brokenLinks[] = [
                'from' => $button->screen->key,
                'button' => $button->text,
                'to' => $button->next_screen_key,
            ];
        }
    }

    /**
     * Вывод статистики.
     */
    protected function outputStats(): void
    {
        $totalScreens = $this->screens->count();
        $totalButtons = $this->screens->sum(fn($s) => $s->buttons->count());
        $withHandlers = $this->screens->filter(fn($s) => $s->handler_id)->count();

        $this->table(
            ['Метрика', 'Значение'],
            [
                ['Экранов', $totalScreens],
                ['Кнопок', $totalButtons],
                ['С обработчиками', $withHandlers],
                ['Битых ссылок', count($this->brokenLinks)],
            ]
        );
    }

    /**
     * Вывод битых ссылок.
     */
    protected function outputBrokenLinks(): void
    {
        $this->newLine();
        $this->error('⚠️  Битые ссылки:');
        
        $rows = array_map(fn($link) => [
            $link['from'],
            $link['button'],
            $link['to'],
        ], $this->brokenLinks);

        $this->table(['Экран', 'Кнопка', 'Ссылается на'], $rows);
    }

    /**
     * Генерация Markdown с читаемым деревом.
     */
    protected function generateMarkdown(): string
    {
        $date = now()->format('d.m.Y H:i:s');
        $totalScreens = $this->screens->count();
        $totalButtons = $this->screens->sum(fn($s) => $s->buttons->count());
        $withHandlers = $this->screens->filter(fn($s) => $s->handler_id)->count();
        $brokenCount = count($this->brokenLinks);
        $status = $brokenCount === 0 ? '✅' : '⚠️';

        $md = "# Дерево экранов бота\n\n";
        $md .= "**Сгенерировано:** {$date}\n\n";
        $md .= "**Экранов:** {$totalScreens} | **Кнопок:** {$totalButtons} | **Битых ссылок:** {$brokenCount} {$status}\n\n";
        $md .= "---\n\n";

        // Битые ссылки
        if (!empty($this->brokenLinks)) {
            $md .= "## ⚠️ Битые ссылки\n\n";
            foreach ($this->brokenLinks as $link) {
                $md .= "- `{$link['from']}` → [{$link['button']}] → ❌ `{$link['to']}`\n";
            }
            $md .= "\n---\n\n";
        }

        // Дерево экранов
        $md .= "## 🏠 Главное меню (main.menu)\n\n";
        $md .= "```\n";
        $md .= $this->generateTree();
        $md .= "```\n";

        return $md;
    }

    /**
     * Генерация читаемого дерева экранов.
     */
    protected function generateTree(): string
    {
        $tree = '';
        $visited = [];
        
        // Иконки для секций
        $icons = [
            'install' => '📂',
            'faq' => '❓',
            'tariffs' => '💰',
            'profile' => '👤',
            'docs' => '📄',
        ];

        // Находим главное меню
        $mainMenu = $this->screens->firstWhere('key', 'main.menu');
        if (!$mainMenu) {
            return "Главное меню (main.menu) не найдено\n";
        }

        // Получаем кнопки главного меню (это основные ветки)
        $mainButtons = $mainMenu->buttons->sortBy('order');
        $totalButtons = $mainButtons->count();
        $index = 0;

        $mainButtons = $mainButtons->filter(fn($b) => $b->next_screen_key);
        $totalButtons = $mainButtons->count();
        $index = 0;

        foreach ($mainButtons as $button) {
            $index++;
            $isLast = ($index === $totalButtons);
            $prefix = $isLast ? '└── ' : '├── ';
            $childPrefix = $isLast ? '    ' : '│   ';

            $screen = $this->screens->firstWhere('key', $button->next_screen_key);
            if (!$screen) continue;

            $section = $this->getSection($screen->key);
            $icon = $icons[$section] ?? '📁';
            
            $tree .= "{$prefix}{$icon} {$button->text} ({$screen->key})\n";
            $visited[$screen->key] = true;
            
            $tree .= $this->generateSubTree($screen, $childPrefix, $visited);
        }

        return $tree;
    }

    /**
     * Рекурсивная генерация поддерева.
     */
    protected function generateSubTree($screen, string $prefix, array &$visited): string
    {
        $tree = '';
        $buttons = $screen->buttons->sortBy('order')->filter(fn($b) => $b->next_screen_key);

        $totalButtons = $buttons->count();
        $index = 0;
        $currentSection = $this->getSection($screen->key);

        foreach ($buttons as $button) {
            $index++;
            $isLast = ($index === $totalButtons);
            $connector = $isLast ? '└── ' : '├── ';
            $childPrefix = $prefix . ($isLast ? '    ' : '│   ');

            $nextScreen = $this->screens->firstWhere('key', $button->next_screen_key);
            
            if (!$nextScreen) {
                // Битая ссылка
                $tree .= "{$prefix}{$connector}❌ {$button->text} ({$button->next_screen_key})\n";
                continue;
            }

            $nextSection = $this->getSection($nextScreen->key);

            // Проверяем циклы
            if (isset($visited[$nextScreen->key])) {
                $tree .= "{$prefix}{$connector}{$button->text} → {$nextScreen->key}\n";
                continue;
            }

            // Проверяем межсекционный переход (переход в другую ветку)
            if ($this->isCrossSectionLink($currentSection, $nextSection, $button->text)) {
                $tree .= "{$prefix}{$connector}{$button->text} → {$nextScreen->key}\n";
                continue;
            }

            $tree .= "{$prefix}{$connector}{$button->text} ({$nextScreen->key})\n";
            $visited[$nextScreen->key] = true;

            // Рекурсивно обрабатываем детей (но ограничиваем глубину)
            $depth = substr_count($prefix, '│') + substr_count($prefix, '    ');
            if ($depth < 8) {
                $tree .= $this->generateSubTree($nextScreen, $childPrefix, $visited);
            }
        }

        return $tree;
    }

    /**
     * Проверяет, является ли переход межсекционным (из одной ветки в другую).
     */
    protected function isCrossSectionLink(string $fromSection, string $toSection, string $buttonText): bool
    {
        // Если переход в другую секцию - это межсекционная ссылка
        return $fromSection !== $toSection;
    }

    /**
     * Генерация HTML с vis-network (интерактивная диаграмма с zoom/drag).
     */
    protected function generateHtml(): string
    {
        $date = now()->format('d.m.Y H:i:s');
        $brokenHtml = '';

        if (!empty($this->brokenLinks)) {
            $brokenHtml = '<div class="broken"><h2>⚠️ Битые ссылки</h2><ul>';
            foreach ($this->brokenLinks as $link) {
                $brokenHtml .= "<li><code>{$link['from']}</code> → [{$link['button']}] → ❌ <code>{$link['to']}</code></li>";
            }
            $brokenHtml .= '</ul></div>';
        }

        $totalScreens = $this->screens->count();
        $totalButtons = $this->screens->sum(fn($s) => $s->buttons->count());
        $withHandlers = $this->screens->filter(fn($s) => $s->handler_id)->count();

        // Генерируем данные для vis-network
        $visData = $this->generateVisNetworkData();

        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Карта сценария бота</title>
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        h1 { color: #1f2937; margin: 0 0 5px 0; }
        .header { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stats { display: flex; gap: 15px; margin: 15px 0; flex-wrap: wrap; }
        .stat { background: #f8fafc; padding: 12px 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .stat-value { font-size: 28px; font-weight: 700; color: #3b82f6; }
        .stat-label { font-size: 13px; color: #64748b; }
        .broken { background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .broken h2 { color: #dc2626; margin: 0 0 10px 0; font-size: 16px; }
        .broken ul { margin: 0; padding-left: 20px; }
        .broken li { margin: 5px 0; font-size: 14px; }
        code { background: #fee2e2; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
        .meta { color: #6b7280; font-size: 13px; }
        .graph-container { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        .graph-header { padding: 15px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .graph-header h2 { margin: 0; font-size: 18px; }
        .controls { display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer; font-size: 13px; }
        .btn:hover { background: #f8fafc; }
        #network { width: 100%; height: 700px; }
        .legend { display: flex; gap: 15px; flex-wrap: wrap; padding: 15px 20px; border-top: 1px solid #e5e7eb; background: #f8fafc; }
        .legend-item { display: flex; align-items: center; gap: 6px; font-size: 13px; }
        .legend-dot { width: 12px; height: 12px; border-radius: 3px; }
        .tip { font-size: 12px; color: #6b7280; padding: 10px 20px; background: #fffbeb; border-top: 1px solid #fef3c7; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🤖 Карта сценария бота</h1>
        <p class="meta">Сгенерировано: {$date}</p>
        
        <div class="stats">
            <div class="stat"><div class="stat-value">{$totalScreens}</div><div class="stat-label">Экранов</div></div>
            <div class="stat"><div class="stat-value">{$totalButtons}</div><div class="stat-label">Кнопок</div></div>
            <div class="stat"><div class="stat-value">{$withHandlers}</div><div class="stat-label">С обработчиками</div></div>
        </div>

        {$brokenHtml}
    </div>

    <div class="graph-container">
        <div class="graph-header">
            <h2>📊 Диаграмма связей</h2>
            <div class="controls">
                <button class="btn" onclick="network.fit()">🔍 Вписать в экран</button>
                <button class="btn" onclick="network.moveTo({scale: 1})">1:1</button>
                <button class="btn" onclick="network.moveTo({scale: network.getScale() * 1.3})">➕</button>
                <button class="btn" onclick="network.moveTo({scale: network.getScale() / 1.3})">➖</button>
            </div>
        </div>
        <div id="network"></div>
        <div class="legend">
            <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div> Main/Start</div>
            <div class="legend-item"><div class="legend-dot" style="background:#3b82f6"></div> FAQ</div>
            <div class="legend-item"><div class="legend-dot" style="background:#f97316"></div> Тарифы</div>
            <div class="legend-item"><div class="legend-dot" style="background:#a855f7"></div> Аккаунт</div>
            <div class="legend-item"><div class="legend-dot" style="background:#06b6d4"></div> Поддержка</div>
            <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div> Troubleshoot</div>
            <div class="legend-item"><div class="legend-dot" style="background:#eab308"></div> Документы</div>
            <div class="legend-item"><div class="legend-dot" style="background:#84cc16"></div> Установка</div>
            <div class="legend-item"><div class="legend-dot" style="background:#ec4899"></div> Профиль</div>
            <div class="legend-item"><div class="legend-dot" style="background:#dc2626; border: 2px dashed #dc2626"></div> Битая ссылка</div>
        </div>
        <div class="tip">💡 Используйте колёсико мыши для масштабирования, перетаскивайте для навигации. Кликните на узел для выделения связей.</div>
    </div>

    <script>
{$visData}

        var container = document.getElementById('network');
        var data = { nodes: nodes, edges: edges };
        var options = {
            nodes: {
                shape: 'box',
                margin: { top: 10, bottom: 10, left: 15, right: 15 },
                font: { size: 12, face: 'system-ui', color: '#1f2937' },
                borderWidth: 2,
                shadow: { enabled: true, size: 5, x: 2, y: 2 }
            },
            edges: {
                arrows: { to: { enabled: true, scaleFactor: 0.5 } },
                smooth: { type: 'cubicBezier', forceDirection: 'horizontal', roundness: 0.4 },
                font: { size: 9, align: 'horizontal', background: 'white', color: '#64748b' },
                color: { color: '#cbd5e1', highlight: '#3b82f6' },
                width: 1.5
            },
            physics: {
                enabled: false
            },
            interaction: {
                hover: true,
                navigationButtons: true,
                keyboard: true,
                zoomView: true,
                dragView: true
            },
            layout: {
                hierarchical: {
                    enabled: true,
                    direction: 'LR',
                    sortMethod: 'directed',
                    levelSeparation: 200,
                    nodeSpacing: 80,
                    treeSpacing: 100,
                    blockShifting: true,
                    edgeMinimization: true,
                    parentCentralization: true
                }
            }
        };
        
        var network = new vis.Network(container, data, options);
        
        // Подогнать под экран после отрисовки
        network.once("afterDrawing", function () {
            network.fit({ animation: { duration: 500 } });
        });
    </script>
</body>
</html>
HTML;
    }
    
    /**
     * Генерация данных для vis-network с иерархическим layout.
     */
    protected function generateVisNetworkData(): string
    {
        $colors = [
            'main' => '#f59e0b',
            'faq' => '#3b82f6',
            'tariffs' => '#f97316',
            'docs' => '#eab308',
            'install' => '#84cc16',
            'profile' => '#ec4899',
        ];
        
        // Рассчитываем уровни (глубину) для каждого узла
        $levels = $this->calculateLevels();
        
        $nodes = [];
        $edges = [];
        $addedBrokenNodes = [];
        
        foreach ($this->screens as $screen) {
            $section = $this->getSection($screen->key);
            $color = $colors[$section] ?? '#94a3b8';
            $title = $this->sanitizeForMermaid($screen->title ?: $screen->key);
            $level = $levels[$screen->key] ?? 0;
            
            // Главные узлы (уровень 0-1) делаем жёлтыми как в Figma
            $isMainNode = in_array($screen->key, ['main.menu', 'faq.main', 'tariffs.main', 'docs.main', 'install.main', 'profile.main']);
            
            if ($isMainNode) {
                $nodeColor = [
                    'background' => '#fef3c7',
                    'border' => '#f59e0b',
                    'highlight' => ['background' => '#fde68a', 'border' => '#d97706']
                ];
            } else {
                $nodeColor = [
                    'background' => '#ffffff',
                    'border' => '#e5e7eb',
                    'highlight' => ['background' => $color . '20', 'border' => $color]
                ];
            }
            
            $nodes[] = [
                'id' => $screen->key,
                'label' => mb_substr($title, 0, 22),
                'title' => "{$screen->key}\n{$screen->title}",
                'level' => $level,
                'color' => $nodeColor,
            ];
            
            foreach ($screen->buttons as $button) {
                if (!$button->next_screen_key) continue;
                
                $isBroken = !$this->allKeys->contains($button->next_screen_key);
                $buttonText = $this->sanitizeForMermaid($button->text);
                
                if ($isBroken) {
                    if (!isset($addedBrokenNodes[$button->next_screen_key])) {
                        $brokenLevel = ($levels[$screen->key] ?? 0) + 1;
                        $nodes[] = [
                            'id' => $button->next_screen_key,
                            'label' => '❌ ' . $button->next_screen_key,
                            'level' => $brokenLevel,
                            'color' => [
                                'background' => '#fee2e2',
                                'border' => '#dc2626',
                            ],
                            'borderWidth' => 2,
                            'shapeProperties' => ['borderDashes' => [5, 5]]
                        ];
                        $addedBrokenNodes[$button->next_screen_key] = true;
                    }
                    
                    $edges[] = [
                        'from' => $screen->key,
                        'to' => $button->next_screen_key,
                        'label' => mb_substr($buttonText, 0, 12),
                        'dashes' => true,
                        'color' => ['color' => '#dc2626']
                    ];
                } else {
                    $edges[] = [
                        'from' => $screen->key,
                        'to' => $button->next_screen_key,
                        'label' => mb_substr($buttonText, 0, 12),
                    ];
                }
            }
        }
        
        $nodesJson = json_encode($nodes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $edgesJson = json_encode($edges, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return "var nodes = new vis.DataSet({$nodesJson});\nvar edges = new vis.DataSet({$edgesJson});";
    }
    
    /**
     * Рассчитать уровни (глубину) для каждого узла методом BFS.
     */
    protected function calculateLevels(): array
    {
        $levels = [];
        $visited = [];
        $queue = [];
        
        // Начинаем с корневых узлов
        $rootKeys = ['main.menu'];
        foreach ($rootKeys as $root) {
            if ($this->allKeys->contains($root)) {
                $queue[] = [$root, 0];
                $levels[$root] = 0;
                $visited[$root] = true;
            }
        }
        
        // BFS для расчёта уровней
        while (!empty($queue)) {
            [$currentKey, $currentLevel] = array_shift($queue);
            
            $screen = $this->screens->firstWhere('key', $currentKey);
            if (!$screen) continue;
            
            foreach ($screen->buttons as $button) {
                $nextKey = $button->next_screen_key;
                if (!$nextKey) continue;
                
                // Пропускаем обратные ссылки (назад, в главное меню)
                $buttonTextLower = mb_strtolower($button->text);
                if (str_contains($buttonTextLower, 'назад') || str_contains($buttonTextLower, 'главное меню')) {
                    continue;
                }
                
                if (!isset($visited[$nextKey]) && $this->allKeys->contains($nextKey)) {
                    $visited[$nextKey] = true;
                    $levels[$nextKey] = $currentLevel + 1;
                    $queue[] = [$nextKey, $currentLevel + 1];
                }
            }
        }
        
        // Для узлов без уровня назначаем на основе секции
        foreach ($this->screens as $screen) {
            if (!isset($levels[$screen->key])) {
                $levels[$screen->key] = 1;
            }
        }
        
        return $levels;
    }

    /**
     * Генерация JSON.
     */
    protected function generateJson(): string
    {
        $data = [
            'generated_at' => now()->toIso8601String(),
            'stats' => [
                'screens' => $this->screens->count(),
                'buttons' => $this->screens->sum(fn($s) => $s->buttons->count()),
                'with_handlers' => $this->screens->filter(fn($s) => $s->handler_id)->count(),
                'broken_links' => count($this->brokenLinks),
            ],
            'broken_links' => $this->brokenLinks,
            'screens' => $this->screens->map(fn($s) => [
                'key' => $s->key,
                'title' => $s->title,
                'text' => $s->text,
                'handler_id' => $s->handler_id,
                'buttons' => $s->buttons->map(fn($b) => [
                    'text' => $b->text,
                    'next_screen_key' => $b->next_screen_key,
                    'order' => $b->order,
                ])->toArray(),
            ])->toArray(),
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Сохранение в файл.
     */
    protected function saveToFile(string $content, string $format): void
    {
        $extensions = ['md' => 'md', 'html' => 'html', 'json' => 'json'];
        $ext = $extensions[$format] ?? 'md';
        
        $path = $this->option('output') ?: base_path("bot-map.{$ext}");
        
        file_put_contents($path, $content);
        $this->info("📄 Сохранено: {$path}");
    }

    /**
     * Получить секцию из ключа экрана.
     */
    protected function getSection(string $key): string
    {
        return explode('.', $key)[0] ?? 'other';
    }

    /**
     * Преобразовать ключ в ID для Mermaid.
     */
    protected function toNodeId(string $key): string
    {
        return str_replace('.', '_', $key);
    }

    /**
     * Очистить текст для Mermaid (убрать эмодзи и спецсимволы).
     */
    protected function sanitizeForMermaid(string $text): string
    {
        // Убираем эмодзи
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text); // Emoticons
        $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text); // Misc Symbols
        $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text); // Transport
        $text = preg_replace('/[\x{1F700}-\x{1F77F}]/u', '', $text); // Alchemical
        $text = preg_replace('/[\x{1F780}-\x{1F7FF}]/u', '', $text); // Geometric
        $text = preg_replace('/[\x{1F800}-\x{1F8FF}]/u', '', $text); // Supplemental Arrows
        $text = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $text); // Supplemental Symbols
        $text = preg_replace('/[\x{1FA00}-\x{1FA6F}]/u', '', $text); // Chess Symbols
        $text = preg_replace('/[\x{1FA70}-\x{1FAFF}]/u', '', $text); // Symbols Extended-A
        $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);   // Misc symbols
        $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);   // Dingbats
        $text = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $text);   // Variation Selectors
        $text = preg_replace('/[\x{E0100}-\x{E01EF}]/u', '', $text); // Variation Selectors Supplement
        
        // Убираем опасные для Mermaid символы
        $text = str_replace(['"', "'", '`', '[', ']', '{', '}', '|', '<', '>', '#', '&'], '', $text);
        
        // Убираем множественные пробелы
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
}
