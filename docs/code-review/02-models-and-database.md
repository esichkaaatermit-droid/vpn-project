# Модели и работа с БД

> Как устроены модели, какие связи между ними, как выглядят запросы к базе

---

## Обзор моделей

В проекте **5 моделей**. Все находятся в `app/Models/`.

```
User ──────┐
           │ hasMany
           ▼
        Payment

Screen ────┐
           │ hasMany (ordered by `order`)
           ▼
     ScreenButton ───→ Screen (через next_screen_key)

UserState ─────────→ Screen (через current_screen_key)
```

---

## Модель User

Файл: `app/Models/User.php`

Расширяет стандартный `Authenticatable` от Laravel. Добавлены поля для Telegram.

### Поля (fillable)
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'telegram_id',
    'telegram_username',
];
```

### Связи
```php
public function payments(): HasMany
{
    return $this->hasMany(Payment::class);
}
```

### Запросы к БД

**Найти или создать пользователя по Telegram ID:**
```php
public static function findOrCreateByTelegramId(
    int $telegramId, ?string $username = null, ?string $name = null
): self {
    return self::firstOrCreate(
        ['telegram_id' => $telegramId],                    // WHERE
        ['telegram_username' => $username, 'name' => $name] // INSERT если не нашли
    );
}
```
Генерирует SQL:
```sql
SELECT * FROM users WHERE telegram_id = ? LIMIT 1;
-- если не нашли:
INSERT INTO users (telegram_id, telegram_username, name) VALUES (?, ?, ?);
```

**Найти по Telegram ID:**
```php
public static function findByTelegramId(int $telegramId): ?self
{
    return self::where('telegram_id', $telegramId)->first();
}
```

### Паттерн

Модель использует **статические фабричные методы** для частых операций поиска. Это удобно — вызывающий код не строит запросы, а вызывает понятный метод: `User::findByTelegramId(123)`.

---

## Модель Screen

Файл: `app/Models/Screen.php`

Экран бота — центральная сущность навигации. Каждый экран имеет уникальный `key` (например, `main.menu`, `faq.start`).

### Поля (fillable)
```php
protected $fillable = [
    'key',         // уникальный ключ, например "profile.my_profile"
    'title',       // название для админки
    'text',        // текст сообщения (HTML)
    'handler_id',  // ID обработчика или null
];
```

### Связи
```php
public function buttons(): HasMany
{
    return $this->hasMany(ScreenButton::class)->orderBy('order');
}
```
Кнопки автоматически сортируются по полю `order`.

### Запросы к БД

**Найти экран по ключу:**
```php
public static function findByKey(string $key): ?self
{
    return static::where('key', $key)->first();
}
```
Это самый частый запрос в системе — вызывается при каждом показе экрана.

### Вспомогательные методы
```php
// Есть ли у экрана обработчик?
public function hasHandler(): bool
{
    return !empty($this->handler_id);
}

// Получить секцию из ключа: "faq.start" → "faq"
public function getSection(): ?string
{
    $parts = explode('.', $this->key);
    return $parts[0] ?? null;
}
```

---

## Модель ScreenButton

Файл: `app/Models/ScreenButton.php`

Кнопка экрана. Связывает экраны между собой через `next_screen_key`.

### Поля (fillable)
```php
protected $fillable = [
    'screen_id',        // к какому экрану принадлежит
    'text',             // текст кнопки
    'next_screen_key',  // ключ экрана, на который ведёт
    'order',            // порядок сортировки
    'row',              // номер ряда (для группировки в строку)
];
```

### Связи
```php
// Экран, к которому принадлежит кнопка
public function screen(): BelongsTo
{
    return $this->belongsTo(Screen::class);
}
```

### Запросы к БД

**Получить следующий экран:**
```php
public function nextScreen(): ?Screen
{
    if (!$this->next_screen_key) {
        return null;
    }
    return Screen::findByKey($this->next_screen_key);
}
```

### Особенность

Связь между экранами — **через строковый ключ**, а не через foreign key. Это гибче: можно менять экраны в сидерах, не заботясь о числовых ID. Но это значит, что связь `ScreenButton → Screen` не защищена на уровне БД.

---

## Модель UserState

Файл: `app/Models/UserState.php`

Хранит текущее состояние пользователя в боте: на каком экране находится и дополнительные данные сессии.

### Поля (fillable)
```php
protected $fillable = [
    'chat_id',             // Telegram chat ID
    'current_screen_key',  // ключ текущего экрана
    'data',                // JSON-данные сессии
];

protected $casts = [
    'data' => 'array',     // автоматическое JSON ↔ array
];
```

### Связи
```php
public function currentScreen(): BelongsTo
{
    // Связь по строковому ключу, а не по ID
    return $this->belongsTo(Screen::class, 'current_screen_key', 'key');
}
```

### Запросы к БД

**Найти или создать состояние:**
```php
public static function findOrCreateByChatId(int $chatId): self
{
    return static::firstOrCreate(
        ['chat_id' => $chatId],
        ['current_screen_key' => null, 'data' => []]
    );
}
```

### Управление данными сессии
```php
// Получить значение
$userState->getData('selected_tariff');

// Установить значение (автоматически сохраняет)
$userState->setData('selected_tariff', 'premium');

// Очистить всё
$userState->clearData();
```

Каждый вызов `setData()` и `setCurrentScreen()` сразу делает `$this->save()` — данные пишутся в БД немедленно.

---

## Модель Payment

Файл: `app/Models/Payment.php`

Платёж пользователя. Имеет жизненный цикл: `pending → paid / expired / cancelled`.

### Поля (fillable)
```php
protected $fillable = [
    'user_id',       // FK на users
    'tariff_key',    // ключ тарифа
    'tariff_name',   // название тарифа
    'amount',        // сумма
    'status',        // pending | paid | expired | cancelled
    'expires_at',    // дедлайн оплаты
    'paid_at',       // когда оплачен
    'external_id',   // ID в платёжной системе
];
```

### Константы статусов
```php
public const STATUS_PENDING   = 'pending';
public const STATUS_PAID      = 'paid';
public const STATUS_EXPIRED   = 'expired';
public const STATUS_CANCELLED = 'cancelled';
public const EXPIRATION_MINUTES = 15;
```

### Связи
```php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### Запросы к БД

**Создать платёж для пользователя:**
```php
public static function createForUser(
    User $user, string $tariffKey, string $tariffName, float $amount
): self {
    return self::create([
        'user_id'     => $user->id,
        'tariff_key'  => $tariffKey,
        'tariff_name' => $tariffName,
        'amount'      => $amount,
        'status'      => self::STATUS_PENDING,
        'expires_at'  => now()->addMinutes(self::EXPIRATION_MINUTES),
    ]);
}
```

**Получить просроченные платежи:**
```php
public static function getExpiredPending()
{
    return self::where('status', self::STATUS_PENDING)
        ->where('expires_at', '<', now())
        ->get();
}
```
Генерирует SQL:
```sql
SELECT * FROM payments
WHERE status = 'pending' AND expires_at < NOW();
```

### Методы смены статуса
```php
$payment->markAsPaid($externalId);   // → paid
$payment->markAsExpired();           // → expired
$payment->cancel();                  // → cancelled
```
Каждый метод — обёртка над `$this->update([...])`.

### Проверки состояния
```php
$payment->isPending();  // ожидает оплаты?
$payment->isPaid();     // оплачен?
$payment->isExpired();  // просрочен? (status = pending И expires_at в прошлом)
```

---

## Общие паттерны работы с БД в проекте

### 1. Статические фабричные методы
Все модели используют статические методы для типичных запросов:
```php
User::findByTelegramId($id)
User::findOrCreateByTelegramId($id, $username, $name)
Screen::findByKey($key)
UserState::findOrCreateByChatId($chatId)
Payment::createForUser($user, ...)
Payment::getExpiredPending()
```
Это инкапсулирует логику запроса внутри модели.

### 2. firstOrCreate
Паттерн «найди или создай» используется для `User` и `UserState`:
```php
self::firstOrCreate(
    ['telegram_id' => $telegramId],  // условие поиска
    ['name' => $name]                 // данные для вставки
);
```
Один вызов вместо `find() + if null + create()`.

### 3. Кастинг типов
```php
// Payment: decimal хранится как строка в PHP, каст → float
'amount' => 'decimal:2'

// UserState: JSON-поле → PHP-массив
'data' => 'array'

// User: пароль автоматически хешируется
'password' => 'hashed'
```

### 4. Связи через строковые ключи
Вместо классических foreign key по ID, связь `ScreenButton → Screen` и `UserState → Screen` идёт через строковый `key`. Это design decision для удобства сидинга.

### 5. Нет Query Scopes
Проект не использует Eloquent Scopes. Фильтрация реализована через статические методы.

### 6. Нет Repository Pattern
Запросы живут прямо в моделях — никаких промежуточных репозиториев. Для текущего размера проекта это оправдано.

---

## Миграции (хронология)

| Файл | Что создаёт |
|------|-------------|
| `create_users_table` | users, password_reset_tokens, sessions |
| `create_cache_table` | cache, cache_locks |
| `create_jobs_table` | jobs, job_batches, failed_jobs |
| `create_screens_table` | screens (key, title, text, handler_id) |
| `create_screen_buttons_table` | screen_buttons (screen_id, text, next_screen_key, order) |
| `create_payments_table` | payments (user_id, tariff_key, amount, status, ...) |
| `add_telegram_id_to_users` | + telegram_id, telegram_username в users |
| `create_user_states_table` | user_states (chat_id, current_screen_key, data) |
| `create_data_migrations_table` | data_migrations (трекинг data-миграций) |
| `add_row_to_screen_buttons` | + row в screen_buttons |
