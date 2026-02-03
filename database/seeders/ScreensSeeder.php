<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class ScreensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Стартовый экран
        $start = Screen::create([
            'key' => 'start',
            'title' => 'Главное меню',
            'text' => '👋 Добро пожаловать в VPN бот!\n\nВыберите нужный раздел:',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $start->id,
            'text' => '💰 Тарифы',
            'next_screen_key' => 'tariffs.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $start->id,
            'text' => '❓ FAQ',
            'next_screen_key' => 'faq.main',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $start->id,
            'text' => '👤 Личный кабинет',
            'next_screen_key' => 'account.main',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $start->id,
            'text' => '🔧 Устранение неполадок',
            'next_screen_key' => 'troubleshoot.main',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $start->id,
            'text' => '📞 Поддержка',
            'next_screen_key' => 'support.main',
            'order' => 5,
        ]);

        // Тарифы
        $tariffs = Screen::create([
            'key' => 'tariffs.main',
            'title' => 'Тарифы',
            'text' => '💰 <b>Наши тарифы</b>\n\nВыберите подходящий план:',
            'handler_id' => 'show_tariffs',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffs->id,
            'text' => '📅 1 месяц - 199₽',
            'next_screen_key' => 'tariffs.month',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffs->id,
            'text' => '📆 3 месяца - 499₽',
            'next_screen_key' => 'tariffs.quarter',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffs->id,
            'text' => '📆 12 месяцев - 1499₽',
            'next_screen_key' => 'tariffs.year',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffs->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'start',
            'order' => 4,
        ]);

        // Тариф на месяц
        $tariffMonth = Screen::create([
            'key' => 'tariffs.month',
            'title' => 'Тариф на 1 месяц',
            'text' => '📅 <b>Тариф на 1 месяц</b>\n\n✅ Безлимитный трафик\n✅ До 3 устройств\n✅ Все серверы\n\n💰 Стоимость: <b>199₽</b>',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffMonth->id,
            'text' => '💳 Оплатить',
            'next_screen_key' => 'tariffs.payment',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffMonth->id,
            'text' => '⬅️ Назад к тарифам',
            'next_screen_key' => 'tariffs.main',
            'order' => 2,
        ]);

        // FAQ
        $faq = Screen::create([
            'key' => 'faq.main',
            'title' => 'FAQ',
            'text' => '❓ <b>Часто задаваемые вопросы</b>\n\nВыберите интересующий вопрос:',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $faq->id,
            'text' => '📱 Как подключить?',
            'next_screen_key' => 'faq.howto',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faq->id,
            'text' => '🌍 Какие страны доступны?',
            'next_screen_key' => 'faq.countries',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faq->id,
            'text' => '🔒 Это безопасно?',
            'next_screen_key' => 'faq.security',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faq->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'start',
            'order' => 4,
        ]);

        // FAQ - Как подключить
        $faqHowto = Screen::create([
            'key' => 'faq.howto',
            'title' => 'Как подключить VPN',
            'text' => '📱 <b>Как подключить VPN</b>\n\n1️⃣ Оплатите подписку\n2️⃣ Получите конфиг в личном кабинете\n3️⃣ Скачайте приложение:\n   • iOS: App Store → WireGuard\n   • Android: Play Market → WireGuard\n   • Windows/Mac: wireguard.com\n4️⃣ Импортируйте конфиг\n5️⃣ Подключайтесь!',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $faqHowto->id,
            'text' => '⬅️ Назад к FAQ',
            'next_screen_key' => 'faq.main',
            'order' => 1,
        ]);

        // Личный кабинет
        $account = Screen::create([
            'key' => 'account.main',
            'title' => 'Личный кабинет',
            'text' => '👤 <b>Личный кабинет</b>\n\nЗдесь вы можете управлять подпиской и получить конфигурацию.',
            'handler_id' => 'show_user_info',
        ]);

        ScreenButton::create([
            'screen_id' => $account->id,
            'text' => '📊 Статус подписки',
            'next_screen_key' => 'account.status',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $account->id,
            'text' => '⚙️ Получить конфиг',
            'next_screen_key' => 'account.config',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $account->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'start',
            'order' => 3,
        ]);

        // Статус подписки
        $accountStatus = Screen::create([
            'key' => 'account.status',
            'title' => 'Статус подписки',
            'text' => '📊 <b>Статус вашей подписки</b>',
            'handler_id' => 'show_connection_status',
        ]);

        ScreenButton::create([
            'screen_id' => $accountStatus->id,
            'text' => '🔄 Обновить',
            'next_screen_key' => 'account.status',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $accountStatus->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'account.main',
            'order' => 2,
        ]);

        // Получить конфиг
        $accountConfig = Screen::create([
            'key' => 'account.config',
            'title' => 'Конфигурация VPN',
            'text' => '⚙️ <b>Конфигурация VPN</b>\n\nНажмите кнопку ниже для генерации конфигурационного файла.',
            'handler_id' => 'generate_config',
        ]);

        ScreenButton::create([
            'screen_id' => $accountConfig->id,
            'text' => '📥 Скачать конфиг',
            'next_screen_key' => 'account.download',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $accountConfig->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'account.main',
            'order' => 2,
        ]);

        // Устранение неполадок
        $troubleshoot = Screen::create([
            'key' => 'troubleshoot.main',
            'title' => 'Устранение неполадок',
            'text' => '🔧 <b>Устранение неполадок</b>\n\nВыберите вашу операционную систему:',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $troubleshoot->id,
            'text' => '🪟 Windows',
            'next_screen_key' => 'troubleshoot.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $troubleshoot->id,
            'text' => '🍎 macOS',
            'next_screen_key' => 'troubleshoot.macos',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $troubleshoot->id,
            'text' => '📱 iOS/Android',
            'next_screen_key' => 'troubleshoot.mobile',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $troubleshoot->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'start',
            'order' => 4,
        ]);

        // Troubleshoot Windows
        $troubleshootWindows = Screen::create([
            'key' => 'troubleshoot.windows',
            'title' => 'Проблемы на Windows',
            'text' => '🪟 <b>Проблемы на Windows</b>\n\nЧастые проблемы:\n\n1️⃣ <b>Не подключается</b>\n→ Перезапустите WireGuard от имени администратора\n\n2️⃣ <b>Медленная скорость</b>\n→ Попробуйте другой сервер\n\n3️⃣ <b>Ошибка импорта</b>\n→ Проверьте, что файл .conf не повреждён',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $troubleshootWindows->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'troubleshoot.main',
            'order' => 1,
        ]);

        // Поддержка
        $support = Screen::create([
            'key' => 'support.main',
            'title' => 'Поддержка',
            'text' => '📞 <b>Служба поддержки</b>\n\nМы всегда готовы помочь!\n\n📧 Email: support@vpn-bot.ru\n💬 Telegram: @vpn_support\n\n⏰ Время работы: 9:00 - 21:00 МСК',
            'handler_id' => null,
        ]);

        ScreenButton::create([
            'screen_id' => $support->id,
            'text' => '✍️ Написать в поддержку',
            'next_screen_key' => 'support.write',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $support->id,
            'text' => '⬅️ Назад',
            'next_screen_key' => 'start',
            'order' => 2,
        ]);

        $this->command->info('Screens seeded successfully!');
    }
}
