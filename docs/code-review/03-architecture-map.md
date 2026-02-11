# Карта архитектуры

> Файловая структура, сервисный слой, DI-контейнер, взаимосвязи компонентов

---

## Файловая структура (только значимые файлы)

```
app/
├── Bot/                                    # Логика бота
│   ├── HandlerRegistry.php                 # Реестр: handler_id → класс
│   └── Handlers/
│       ├── HandlerInterface.php            # Контракт для хендлеров
│       ├── MainMenuHandler.php             # Главное меню (без логики)
│       ├── ProfileHandler.php              # Профиль (UserService)
│       └── TariffHandler.php               # Тарифы (TariffService)
│
├── Http/Controllers/
│   ├── Controller.php                      # Базовый контроллер
│   ├── Admin/
│   │   └── BotMapController.php            # Визуализация карты бота
│   └── Telegram/
│       └── WebhookController.php           # Webhook от Telegram
│
├── Models/
│   ├── User.php                            # Пользователь
│   ├── Screen.php                          # Экран бота
│   ├── ScreenButton.php                    # Кнопка экрана
│   ├── UserState.php                       # Состояние пользователя
│   └── Payment.php                         # Платёж
│
├── Services/
│   ├── Telegram/
│   │   └── BotService.php                  # Главный сервис бота (539 строк)
│   └── Integration/
│       ├── UserService.php                 # Работа с пользователями (заглушка)
│       ├── TariffService.php               # Тарифы (заглушка)
│       └── ConfigService.php               # VPN-конфиги (заглушка)
│
├── Jobs/
│   └── CheckPaymentJob.php                 # Проверка просроченных платежей
│
├── Providers/
│   ├── AppServiceProvider.php              # Стандартный (пустой)
│   └── BotServiceProvider.php              # Регистрация сервисов бота
│
├── Console/Commands/
│   ├── TelegramSetWebhook.php              # Установка webhook
│   ├── TelegramDeleteWebhook.php           # Удаление webhook
│   ├── BotMapCommand.php                   # Генерация карты бота
│   ├── DataMigrateCommand.php              # Запуск data-миграций
│   └── MakeDataMigrationCommand.php        # Генератор data-миграций
│
└── DataMigrations/
    └── DataMigration.php                   # Базовый класс data-миграций

routes/
├── web.php                                 # GET / и admin/bot-map
├── api.php                                 # POST /api/telegram/webhook
└── console.php                             # Artisan-команды

database/
├── migrations/                             # 10 миграций
└── seeders/
    ├── DatabaseSeeder.php
    ├── ScreensSeeder.php                   # Оркестратор сидеров экранов
    └── Screens/                            # По сидеру на каждую секцию
        ├── MainMenuSeeder.php
        ├── ProfileSeeder.php
        ├── TariffsSeeder.php
        ├── FaqSeeder.php
        ├── InstallSeeder.php
        └── DocsSeeder.php
```

---

## Схема взаимодействия компонентов

```
                         ┌─────────────────────────────────────────────┐
                         │              Telegram API                   │
                         └──────────┬──────────────────▲───────────────┘
                                    │ POST /webhook    │ HTTP (sendMessage,
                                    ▼                  │  editMessage, ...)
                         ┌──────────────────┐          │
                         │ WebhookController │          │
                         └────────┬─────────┘          │
                                  │                    │
                                  ▼                    │
                         ┌──────────────────┐          │
                         │    BotService     │──────────┘
                         │                  │
                         │  handleUpdate()  │
                         │  showScreen()    │
                         │  sendMessage()   │
                         └──┬───────────┬───┘
                            │           │
               ┌────────────┘           └────────────┐
               ▼                                     ▼
    ┌─────────────────┐                   ┌─────────────────────┐
    │ HandlerRegistry │                   │      Models         │
    │                 │                   │                     │
    │ resolve(id)     │                   │ Screen::findByKey() │
    │ execute(id,...) │                   │ UserState::find...  │
    └──┬──────────────┘                   │ User::findBy...     │
       │                                  └─────────────────────┘
       ▼                                            │
    ┌──────────────┐                                │
    │   Handlers   │                                ▼
    │              │                        ┌──────────────┐
    │ Profile ─────┼───→ UserService        │   Database   │
    │ Tariff  ─────┼───→ TariffService      │   (MySQL /   │
    │ MainMenu     │                        │   SQLite)    │
    └──────────────┘                        └──────────────┘
```

---

## DI-контейнер (Dependency Injection)

Файл: `app/Providers/BotServiceProvider.php`

Все ключевые сервисы зарегистрированы как **singleton** — один экземпляр на весь запрос:

```php
// Интеграционные сервисы
$this->app->singleton(UserService::class);
$this->app->singleton(TariffService::class);

$this->app->singleton(ConfigService::class, function ($app) {
    return new ConfigService($app->make(UserService::class));
});

// Главный сервис бота
$this->app->singleton(BotService::class, function ($app) {
    return new BotService($app->make(UserService::class));
});
```

### Граф зависимостей

```
BotService
  └── UserService

ConfigService
  └── UserService

ProfileHandler
  └── UserService

TariffHandler
  └── TariffService
```

`UserService` — центральная зависимость, используется в 3 местах.

---

## Интеграционные сервисы (заглушки)

Три сервиса в `app/Services/Integration/` подготовлены для подключения внешнего API, но сейчас возвращают тестовые данные:

| Сервис | Назначение | Текущий статус |
|--------|-----------|---------------|
| `UserService` | Профиль, подписки, управление пользователями | Заглушка с тестовыми данными |
| `TariffService` | Список тарифов, цены | Заглушка с тестовыми данными |
| `ConfigService` | VPN-конфиги, QR-коды | Заглушка с тестовыми данными |

Когда будет готово реальное API, нужно заменить реализацию внутри этих классов. Контракт (сигнатуры методов) уже определён.

---

## Система экранов (Screen System)

Навигация бота основана на **экранах, хранящихся в БД**. Это позволяет:
- Менять тексты и структуру без изменения кода
- Добавлять новые экраны через сидеры или data-миграции
- Визуализировать карту бота через админку

### Как связаны экраны

```
[main.menu] ─────→ [profile.my_profile]
     │                    │
     ├───→ [tariffs.pricing]
     │                    │
     ├───→ [faq.start] ──→ [faq.question_1] ──→ [faq.answer_1]
     │                    │
     ├───→ [install.start] ──→ [install.ios] / [install.android] / ...
     │
     └───→ [docs.start]
```

Связь идёт через `ScreenButton.next_screen_key → Screen.key`.

### Сидинг экранов

Экраны заполняются через сидеры: `database/seeders/Screens/`. Каждая секция (FAQ, тарифы, профиль, установка, документация) — отдельный сидер. Оркестрирует всё `ScreensSeeder`, который после сидинга **проверяет целостность ссылок** между экранами.

---

## Data Migrations

Проект имеет собственную систему data-миграций — для обновления данных в продакшене (в отличие от обычных миграций, которые меняют структуру таблиц).

Команды:
```bash
php artisan data:migrate              # запустить все новые data-миграции
php artisan make:data-migration Name  # создать новую data-миграцию
```

Трекинг выполненных миграций — в таблице `data_migrations`.

---

## Как расширять функционал

### Добавить новый экран
1. Создать data-миграцию: `php artisan make:data-migration AddNewScreen`
2. В миграции: `Screen::create(['key' => 'section.name', 'text' => '...'])`
3. Добавить кнопки: `ScreenButton::create([...])`
4. Привязать кнопку с существующего экрана

### Добавить новый хендлер
1. Создать класс в `app/Bot/Handlers/`, реализующий `HandlerInterface`
2. Зарегистрировать в `HandlerRegistry::$handlers`:
   ```php
   'section.handler_id' => NewHandler::class,
   ```
3. У нужного экрана в БД указать `handler_id`

### Подключить реальное API
1. Заменить тестовые данные в `UserService`, `TariffService`, `ConfigService`
2. Сигнатуры методов уже определены — менять вызывающий код не нужно

### Добавить новый тип медиа в ответе бота
1. В хендлере вернуть ключ `'photo'` или `'document'` в массиве результата
2. `BotService::showScreen()` автоматически обработает — отправит фото/документ

---

## Где что лежит (быстрый поиск)

| Нужно найти | Где искать |
|-------------|-----------|
| Маршруты | `routes/api.php`, `routes/web.php` |
| Точка входа запроса | `WebhookController.php` |
| Основная логика бота | `BotService.php` |
| Маппинг хендлеров | `HandlerRegistry.php` |
| Конкретный хендлер | `app/Bot/Handlers/` |
| Модели | `app/Models/` |
| Миграции БД | `database/migrations/` |
| Данные экранов | `database/seeders/Screens/` |
| Регистрация сервисов | `BotServiceProvider.php` |
| Настройки бота | `config/telegram.php` |
| Artisan-команды | `app/Console/Commands/` |
| Фоновые задачи | `app/Jobs/CheckPaymentJob.php` |
