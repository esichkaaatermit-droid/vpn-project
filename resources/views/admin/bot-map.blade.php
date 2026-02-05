<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карта сценария бота</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .screen-card { transition: all 0.2s; }
        .screen-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .tree-view { 
            font-family: 'Courier New', monospace; 
            font-size: 14px; 
            line-height: 1.6;
            white-space: pre;
        }
        .tree-view b { font-weight: 600; color: #1f2937; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">🤖 Карта сценария бота</h1>
            <p class="text-gray-600 mt-2">Визуализация всех экранов и переходов</p>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_screens'] }}</div>
                <div class="text-gray-600">Экранов</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['total_buttons'] }}</div>
                <div class="text-gray-600">Кнопок</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['screens_with_handlers'] }}</div>
                <div class="text-gray-600">С обработчиками</div>
            </div>
        </div>

        <!-- Валидация связей -->
        @if(count($brokenLinks) > 0)
            <div class="bg-red-50 border border-red-400 text-red-700 p-4 mb-8 rounded-lg">
                <div class="font-bold mb-2">⚠️ Найдены битые ссылки ({{ count($brokenLinks) }}):</div>
                @foreach($brokenLinks as $link)
                    <div class="text-sm ml-4">
                        <span class="font-mono">{{ $link['screen'] }}</span> → 
                        "{{ $link['button'] }}" → 
                        <span class="font-mono text-red-600">❌ {{ $link['missing'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-green-50 border border-green-400 text-green-700 p-4 mb-8 rounded-lg">
                ✓ Все связи в порядке — битых ссылок нет
            </div>
        @endif

        <!-- Дерево структуры бота -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">🌳 Дерево структуры бота</h2>
            <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
                <div class="tree-view">{!! $tree !!}</div>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                💡 Иконки: 
                🏠 Главное меню •
                📲 Установка •
                ❓ FAQ •
                💰 Тарифы •
                👤 Профиль •
                📄 Документация •
                🔘 Кнопка
            </p>
        </div>

        <!-- Экраны по секциям -->
        @foreach($sections as $sectionName => $sectionScreens)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full mr-2 
                        @switch($sectionName)
                            @case('main') bg-green-500 @break
                            @case('faq') bg-blue-500 @break
                            @case('tariffs') bg-orange-500 @break
                            @case('account') bg-purple-500 @break
                            @case('support') bg-cyan-500 @break
                            @default bg-gray-500
                        @endswitch
                    "></span>
                    {{ ucfirst($sectionName) }}
                    <span class="ml-2 text-sm text-gray-500">({{ $sectionScreens->count() }} экранов)</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($sectionScreens as $screen)
                        <div class="screen-card bg-white rounded-lg shadow p-4">
                            <!-- Заголовок экрана -->
                            <div class="border-b pb-2 mb-3">
                                <div class="font-mono text-sm text-blue-600">{{ $screen->key }}</div>
                                @if($screen->title)
                                    <div class="font-semibold text-gray-800">{{ $screen->title }}</div>
                                @endif
                            </div>

                            <!-- Текст экрана -->
                            <div class="text-gray-600 text-sm mb-3 max-h-20 overflow-hidden">
                                {{ Str::limit($screen->text, 150) }}
                            </div>

                            <!-- Handler -->
                            @if($screen->handler_id)
                                <div class="mb-3">
                                    <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                                        ⚙️ {{ $screen->handler_id }}
                                    </span>
                                </div>
                            @endif

                            <!-- Кнопки -->
                            @if($screen->buttons->count() > 0)
                                <div class="border-t pt-3">
                                    <div class="text-xs text-gray-500 mb-2">Кнопки:</div>
                                    <div class="space-y-1">
                                        @foreach($screen->buttons as $button)
                                            <div class="flex items-center text-sm">
                                                <span class="text-gray-700">{{ $button->text }}</span>
                                                @if($button->next_screen_key)
                                                    <span class="mx-1 text-gray-400">→</span>
                                                    <span class="font-mono text-xs text-green-600">{{ $button->next_screen_key }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Таблица всех экранов -->
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">📋 Полная таблица экранов</h2>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Handler</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Кнопки</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($screens as $screen)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-sm text-blue-600">{{ $screen->key }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $screen->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($screen->handler_id)
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                                            {{ $screen->handler_id }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @foreach($screen->buttons as $button)
                                        <div class="text-xs">
                                            {{ $button->text }} → 
                                            <span class="font-mono text-green-600">{{ $button->next_screen_key ?? 'null' }}</span>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
