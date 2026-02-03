# VPN Telegram Bot

Telegram-бот для VPN сервиса на Laravel 11.

## Настройка

### 1. Переменные окружения

Добавьте в файл `.env`:

```env
# Telegram Bot
TELEGRAM_BOT_TOKEN=123456789:ABCDefGhIJKlmnOPQrstUVwxyz
TELEGRAM_WEBHOOK_URL=https://yourdomain.com/api/telegram/webhook
TELEGRAM_WEBHOOK_SECRET=your_secret_token_here

# Настройки бота
TELEGRAM_START_SCREEN=main.menu
TELEGRAM_STATE_TTL=24
```

### 2. Миграции

```bash
php artisan migrate
```

### 3. Сидер (заполнение экранов)

```bash
php artisan db:seed --class=ScreensSeeder
```

### 4. Установка Webhook

```bash
# Через artisan команду
php artisan telegram:set-webhook

# Или через curl
curl -X POST "https://api.telegram.org/bot{TOKEN}/setWebhook" \
    -d "url={WEBHOOK_URL}" \
    -d "secret_token={SECRET}"
```

### 5. Запуск сервера

```bash
php artisan serve
```

---

## Структура проекта

### Таблицы БД

#### `screens` — экраны бота
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| key | string(255) | Уникальный ключ экрана (например, `faq.main`) |
| title | string(255) | Заголовок экрана |
| text | text | Текст сообщения |
| handler_id | string(255) | ID обработчика (НЕ имя класса!) |

#### `screen_buttons` — кнопки экранов
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| screen_id | bigint | FK → screens.id |
| text | string(255) | Текст кнопки |
| next_screen_key | string(255) | Key экрана, куда ведёт кнопка |
| order | integer | Порядок кнопки |

#### `user_states` — состояние пользователей
| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint | PK |
| chat_id | bigint | Telegram chat ID |
| current_screen_key | string(255) | Текущий экран пользователя |
| data | json | Дополнительные данные сессии |

### Как добавить новый экран

1. Добавьте экран в `database/seeders/ScreensSeeder.php`:

```php
$screen = Screen::create([
    'key' => 'my_section.my_screen',
    'title' => 'Мой экран',
    'text' => 'Текст экрана',
    'handler_id' => 'my_section.my_screen', // опционально
]);

ScreenButton::create([
    'screen_id' => $screen->id,
    'text' => 'Кнопка 1',
    'next_screen_key' => 'another.screen',
    'order' => 1,
]);
```

2. Запустите сидер:
```bash
php artisan db:seed --class=ScreensSeeder
```

### Как создать новый Handler

1. Создайте класс в `app/Bot/Handlers/`:

```php
<?php

namespace App\Bot\Handlers;

use App\Models\Screen;

class MyHandler implements HandlerInterface
{
    public function handle(Screen $screen, int $chatId, array $update): array
    {
        return [
            'text' => 'Динамический текст',
            'buttons' => [
                ['text' => 'Кнопка', 'callback_data' => 'next.screen']
            ],
        ];
    }
}
```

2. Зарегистрируйте в `app/Bot/HandlerRegistry.php`:

```php
protected static array $handlers = [
    'my_section.my_screen' => MyHandler::class,
];
```

---

## Интеграция

### Интеграционные сервисы

Сервисы в `App\Services\Integration` работают на **заглушках** и готовы к замене на реальные API-запросы.

| Сервис | Файл | Описание |
|--------|------|----------|
| `UserService` | `app/Services/Integration/UserService.php` | Профиль пользователя, подписки |
| `TariffService` | `app/Services/Integration/TariffService.php` | Список тарифов, цены |
| `ConfigService` | `app/Services/Integration/ConfigService.php` | VPN конфигурации, QR-коды |

### Как заменить на реальный API

1. Откройте нужный сервис
2. Найдите методы с комментарием `// TODO: Заменить на реальный API-запрос`
3. Замените заглушку на HTTP-запрос:

```php
// Было (заглушка):
return [
    'email' => $email,
    'tariff' => 'Start',
    // ...
];

// Стало (реальный API):
$response = Http::get(config('services.backend.url') . '/api/users/profile', [
    'email' => $email
]);
return $response->json();
```

---

## Маршруты

### API (`routes/api.php`)

| Метод | URL | Описание |
|-------|-----|----------|
| POST | `/api/telegram/webhook` | Webhook от Telegram |
| GET | `/api/telegram/webhook-info` | Информация о webhook |

### Web (`routes/web.php`)

| Метод | URL | Описание |
|-------|-----|----------|
| GET | `/admin/bot-map` | HTML-карта сценария |

---

## Структура файлов

```
app/
├── Bot/
│   ├── HandlerRegistry.php          # Реестр обработчиков
│   └── Handlers/
│       ├── HandlerInterface.php     # Интерфейс
│       ├── MainMenuHandler.php      # Главное меню
│       ├── ProfileHandler.php       # Профиль
│       └── TariffHandler.php        # Тарифы
├── Http/Controllers/
│   ├── Admin/
│   │   └── BotMapController.php     # HTML-карта
│   └── Telegram/
│       └── WebhookController.php    # Webhook
├── Models/
│   ├── Screen.php                   # Экраны
│   ├── ScreenButton.php             # Кнопки
│   ├── User.php                     # Пользователи
│   └── UserState.php                # Состояние
├── Services/
│   ├── Integration/
│   │   ├── UserService.php          # Заглушка API пользователей
│   │   ├── TariffService.php        # Заглушка API тарифов
│   │   └── ConfigService.php        # Заглушка API конфигов
│   └── Telegram/
│       └── BotService.php           # Основной сервис бота
config/
└── telegram.php                     # Конфигурация бота
database/
├── migrations/
│   ├── *_create_screens_table.php
│   ├── *_create_screen_buttons_table.php
│   └── *_create_user_states_table.php
└── seeders/
    └── ScreensSeeder.php            # Заполнение экранов
```

---

## Команды Artisan

```bash
# Установить webhook
php artisan telegram:set-webhook

# Удалить webhook
php artisan telegram:delete-webhook

# Запустить миграции
php artisan migrate

# Заполнить экраны
php artisan db:seed --class=ScreensSeeder
```

---

## Важные правила

1. **Строковые ID** — в `handler_id` хранятся ТОЛЬКО строки (`profile.my_profile`), НЕ имена классов
2. **Связи через key** — кнопки ссылаются на экраны через `next_screen_key` → `screens.key`
3. **Обработчики опциональны** — экран может работать без `handler_id`
4. **Заглушки** — сервисы в `Integration/` содержат тестовые данные, заменить на API позже
