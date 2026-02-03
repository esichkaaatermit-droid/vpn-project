<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карта бота - VPN Telegram Bot</title>
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #1a1a2e;
            color: #eee;
            min-height: 100vh;
        }

        .header {
            background: #16213e;
            padding: 20px;
            border-bottom: 1px solid #0f3460;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #e94560;
        }

        .header-stats {
            display: flex;
            gap: 20px;
        }

        .stat {
            background: #0f3460;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #e94560;
        }

        .stat-label {
            font-size: 12px;
            color: #aaa;
            margin-top: 4px;
        }

        .main-container {
            display: flex;
            height: calc(100vh - 80px);
        }

        .sidebar {
            width: 320px;
            background: #16213e;
            border-right: 1px solid #0f3460;
            overflow-y: auto;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 16px;
            color: #e94560;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #0f3460;
        }

        .section-list {
            margin-bottom: 30px;
        }

        .section-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .section-item:hover {
            background: #0f3460;
        }

        .section-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .section-name {
            flex: 1;
            font-size: 14px;
        }

        .section-count {
            background: #0f3460;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            color: #aaa;
        }

        .screen-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .screen-item {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            background: #0f3460;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .screen-item:hover {
            background: #1a3a6e;
            transform: translateX(4px);
        }

        .screen-item.active {
            border-left-color: #e94560;
            background: #1a3a6e;
        }

        .screen-key {
            font-family: monospace;
            font-size: 13px;
            color: #e94560;
            margin-bottom: 4px;
        }

        .screen-title {
            font-size: 14px;
            color: #fff;
        }

        .screen-handler {
            font-size: 11px;
            color: #4CAF50;
            margin-top: 4px;
        }

        .graph-container {
            flex: 1;
            position: relative;
        }

        #bot-graph {
            width: 100%;
            height: 100%;
            background: #1a1a2e;
        }

        .controls {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .control-btn {
            background: #16213e;
            border: 1px solid #0f3460;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .control-btn:hover {
            background: #0f3460;
            border-color: #e94560;
        }

        .info-panel {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: #16213e;
            border: 1px solid #0f3460;
            border-radius: 8px;
            padding: 20px;
            display: none;
        }

        .info-panel.active {
            display: block;
        }

        .info-panel h3 {
            color: #e94560;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .info-panel-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-field {
            background: #0f3460;
            padding: 12px;
            border-radius: 6px;
        }

        .info-field-label {
            font-size: 11px;
            color: #aaa;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-field-value {
            font-size: 14px;
            color: #fff;
            word-break: break-word;
        }

        .info-field-value.code {
            font-family: monospace;
            color: #e94560;
        }

        .buttons-list {
            margin-top: 15px;
        }

        .button-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: #0f3460;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .button-text {
            flex: 1;
            font-size: 13px;
        }

        .button-arrow {
            color: #666;
            margin: 0 10px;
        }

        .button-target {
            font-family: monospace;
            font-size: 12px;
            color: #4CAF50;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #aaa;
            font-size: 20px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #e94560;
        }

        .legend {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #16213e;
            border: 1px solid #0f3460;
            border-radius: 8px;
            padding: 15px;
        }

        .legend h4 {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .legend-color {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #aaa;
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .empty-state code {
            background: #0f3460;
            padding: 10px 15px;
            border-radius: 6px;
            display: inline-block;
            font-size: 13px;
            color: #e94560;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a2e;
        }

        ::-webkit-scrollbar-thumb {
            background: #0f3460;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1a3a6e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🤖 Карта Telegram Бота</h1>
        <div class="header-stats">
            <div class="stat">
                <div class="stat-value">{{ count($nodes) }}</div>
                <div class="stat-label">Экранов</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ count($edges) }}</div>
                <div class="stat-label">Переходов</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $sections->count() }}</div>
                <div class="stat-label">Секций</div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="sidebar">
            <h2>📂 Секции</h2>
            <div class="section-list">
                @forelse($sections as $section => $count)
                    <div class="section-item" data-section="{{ $section }}">
                        <div class="section-color" style="background: {{ $nodes[0]['color'] ?? '#607D8B' }}"></div>
                        <span class="section-name">{{ ucfirst($section) }}</span>
                        <span class="section-count">{{ $count }}</span>
                    </div>
                @empty
                    <p style="color: #666; font-size: 13px;">Нет секций</p>
                @endforelse
            </div>

            <h2>📄 Экраны</h2>
            <div class="screen-list">
                @forelse($screens as $screen)
                    <div class="screen-item" data-key="{{ $screen->key }}">
                        <div class="screen-key">{{ $screen->key }}</div>
                        @if($screen->title)
                            <div class="screen-title">{{ $screen->title }}</div>
                        @endif
                        @if($screen->handler_id)
                            <div class="screen-handler">⚙️ {{ $screen->handler_id }}</div>
                        @endif
                    </div>
                @empty
                    <p style="color: #666; font-size: 13px;">Нет экранов</p>
                @endforelse
            </div>
        </div>

        <div class="graph-container">
            @if(count($nodes) > 0)
                <div id="bot-graph"></div>
                
                <div class="legend">
                    <h4>ЛЕГЕНДА</h4>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #4CAF50"></div>
                        <span>start</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #2196F3"></div>
                        <span>faq</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #FF9800"></div>
                        <span>tariffs</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #F44336"></div>
                        <span>troubleshoot</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #9C27B0"></div>
                        <span>account</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #00BCD4"></div>
                        <span>support</span>
                    </div>
                </div>

                <div class="controls">
                    <button class="control-btn" onclick="network.fit()">📐 Вписать</button>
                    <button class="control-btn" onclick="resetLayout()">🔄 Сбросить</button>
                    <button class="control-btn" onclick="togglePhysics()">⚡ Физика</button>
                </div>
            @else
                <div class="empty-state">
                    <h3>База данных пуста</h3>
                    <p>Добавьте экраны в таблицу screens для визуализации карты бота.</p>
                    <code>php artisan db:seed --class=ScreensSeeder</code>
                </div>
            @endif

            <div class="info-panel" id="info-panel">
                <button class="close-btn" onclick="closeInfoPanel()">&times;</button>
                <h3 id="info-title">Информация об экране</h3>
                <div class="info-panel-content">
                    <div class="info-field">
                        <div class="info-field-label">Ключ</div>
                        <div class="info-field-value code" id="info-key">-</div>
                    </div>
                    <div class="info-field">
                        <div class="info-field-label">Заголовок</div>
                        <div class="info-field-value" id="info-screen-title">-</div>
                    </div>
                    <div class="info-field">
                        <div class="info-field-label">Обработчик</div>
                        <div class="info-field-value code" id="info-handler">-</div>
                    </div>
                </div>
                <div class="info-field" style="margin-top: 15px;">
                    <div class="info-field-label">Текст сообщения</div>
                    <div class="info-field-value" id="info-text">-</div>
                </div>
                <div class="buttons-list" id="info-buttons"></div>
            </div>
        </div>
    </div>

    <script>
        // Данные из PHP
        const nodesData = @json($nodes);
        const edgesData = @json($edges);
        const screensData = @json($screens);

        let network = null;
        let physicsEnabled = true;

        // Инициализация графа
        if (nodesData.length > 0) {
            const nodes = new vis.DataSet(nodesData.map(node => ({
                id: node.id,
                label: node.label,
                title: node.title,
                color: {
                    background: node.color,
                    border: node.color,
                    highlight: {
                        background: node.color,
                        border: '#fff'
                    }
                },
                font: {
                    color: '#fff',
                    size: 14
                },
                borderWidth: node.hasHandler ? 3 : 1,
                borderWidthSelected: 4,
                shape: 'box',
                margin: 10,
                shadow: true
            })));

            const edges = new vis.DataSet(edgesData.map((edge, index) => ({
                id: index,
                from: edge.from,
                to: edge.to,
                label: edge.label,
                arrows: 'to',
                color: {
                    color: '#444',
                    highlight: '#e94560'
                },
                font: {
                    color: '#888',
                    size: 11,
                    strokeWidth: 0
                },
                smooth: {
                    type: 'curvedCW',
                    roundness: 0.2
                }
            })));

            const container = document.getElementById('bot-graph');
            const data = { nodes, edges };
            const options = {
                layout: {
                    hierarchical: {
                        enabled: true,
                        direction: 'UD',
                        sortMethod: 'directed',
                        levelSeparation: 150,
                        nodeSpacing: 200
                    }
                },
                physics: {
                    enabled: true,
                    hierarchicalRepulsion: {
                        centralGravity: 0.0,
                        springLength: 200,
                        springConstant: 0.01,
                        nodeDistance: 200
                    }
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200
                }
            };

            network = new vis.Network(container, data, options);

            // Клик по узлу
            network.on('click', function(params) {
                if (params.nodes.length > 0) {
                    showScreenInfo(params.nodes[0]);
                }
            });

            // Подсветка при наведении
            network.on('hoverNode', function(params) {
                document.body.style.cursor = 'pointer';
            });

            network.on('blurNode', function(params) {
                document.body.style.cursor = 'default';
            });
        }

        // Показать информацию об экране
        function showScreenInfo(key) {
            const screen = screensData.find(s => s.key === key);
            if (!screen) return;

            document.getElementById('info-key').textContent = screen.key;
            document.getElementById('info-screen-title').textContent = screen.title || '-';
            document.getElementById('info-handler').textContent = screen.handler_id || '-';
            document.getElementById('info-text').textContent = screen.text;

            // Кнопки
            const buttonsContainer = document.getElementById('info-buttons');
            buttonsContainer.innerHTML = '';
            
            if (screen.buttons && screen.buttons.length > 0) {
                const header = document.createElement('div');
                header.className = 'info-field-label';
                header.style.marginBottom = '10px';
                header.textContent = 'КНОПКИ';
                buttonsContainer.appendChild(header);

                screen.buttons.forEach(btn => {
                    const item = document.createElement('div');
                    item.className = 'button-item';
                    item.innerHTML = `
                        <span class="button-text">${btn.text}</span>
                        <span class="button-arrow">→</span>
                        <span class="button-target">${btn.next_screen_key || '-'}</span>
                    `;
                    buttonsContainer.appendChild(item);
                });
            }

            document.getElementById('info-panel').classList.add('active');

            // Подсветка в списке
            document.querySelectorAll('.screen-item').forEach(el => {
                el.classList.remove('active');
                if (el.dataset.key === key) {
                    el.classList.add('active');
                }
            });
        }

        function closeInfoPanel() {
            document.getElementById('info-panel').classList.remove('active');
            document.querySelectorAll('.screen-item').forEach(el => {
                el.classList.remove('active');
            });
        }

        function resetLayout() {
            if (network) {
                network.stabilize();
                network.fit();
            }
        }

        function togglePhysics() {
            if (network) {
                physicsEnabled = !physicsEnabled;
                network.setOptions({ physics: { enabled: physicsEnabled } });
            }
        }

        // Клик по экрану в списке
        document.querySelectorAll('.screen-item').forEach(item => {
            item.addEventListener('click', function() {
                const key = this.dataset.key;
                showScreenInfo(key);
                if (network) {
                    network.focus(key, {
                        scale: 1.5,
                        animation: true
                    });
                    network.selectNodes([key]);
                }
            });
        });

        // Фильтр по секции
        document.querySelectorAll('.section-item').forEach(item => {
            item.addEventListener('click', function() {
                const section = this.dataset.section;
                const nodesToSelect = nodesData
                    .filter(n => n.section === section)
                    .map(n => n.id);
                
                if (network && nodesToSelect.length > 0) {
                    network.selectNodes(nodesToSelect);
                    network.fit({
                        nodes: nodesToSelect,
                        animation: true
                    });
                }
            });
        });
    </script>
</body>
</html>
