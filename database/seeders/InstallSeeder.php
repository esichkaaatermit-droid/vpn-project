<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class InstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =====================================================
        // Ветка "Установить EasyLight"
        // =====================================================

        // Главный экран — выбор устройства
        $installMain = Screen::create([
            'key' => 'install.main',
            'title' => 'Установить Easy Light',
            'text' => '📲 Текст о наполнении этого блока + Выберите вопрос, который вас интересует:',
            'handler_id' => 'install.main',
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'Android',
            'next_screen_key' => 'install.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'iPhone/iPad',
            'next_screen_key' => 'install.ios',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'AndroidTV',
            'next_screen_key' => 'install.androidtv',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'AppleTV',
            'next_screen_key' => 'install.appletv',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'Windows',
            'next_screen_key' => 'install.windows',
            'order' => 5,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'Mac',
            'next_screen_key' => 'install.mac',
            'order' => 6,
        ]);

        ScreenButton::create([
            'screen_id' => $installMain->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 7,
        ]);

        // =====================================================
        // Android
        // =====================================================
        $installAndroid = Screen::create([
            'key' => 'install.android',
            'title' => 'Android',
            'text' => '📱 Инструкция по установке',
            'handler_id' => 'install.android',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Я использую конфиги на Android',
            'next_screen_key' => 'install.android.config',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'У меня уже/недавно/новое приложение',
            'next_screen_key' => 'install.android.app',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.issues',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 4,
        ]);

        // Android — конфиги
        $installAndroidConfig = Screen::create([
            'key' => 'install.android.config',
            'title' => 'Конфиги на Android',
            'text' => '⚙️ Выдача конфига + инструкция по установке / единоразовая ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.android.config',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfig->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfig->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Android — приложение
        $installAndroidApp = Screen::create([
            'key' => 'install.android.app',
            'title' => 'Приложение на Android',
            'text' => '📲 Инструкция по установке приложения',
            'handler_id' => 'install.android.app',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidApp->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidApp->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Android — проблемы
        $installAndroidIssues = Screen::create([
            'key' => 'install.android.issues',
            'title' => 'Что-то не работает',
            'text' => '⚠️ Текст о том, что здесь пользователь сможет найти ответы на распространённые проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.android.issues',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidIssues->id,
            'text' => 'Перейти к разделу "Что-то не работает"',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidIssues->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // =====================================================
        // iPhone/iPad
        // =====================================================
        $installIos = Screen::create([
            'key' => 'install.ios',
            'title' => 'iPhone/iPad',
            'text' => '🍎 Выдача конфига + инструкция по установке / единоразовая ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.ios',
        ]);

        ScreenButton::create([
            'screen_id' => $installIos->id,
            'text' => 'У меня другая программа',
            'next_screen_key' => 'install.ios.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIos->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.ios.issues',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installIos->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // iPhone — другая программа
        $installIosOther = Screen::create([
            'key' => 'install.ios.other',
            'title' => 'Другая программа',
            'text' => '📲 Инструкция по установке',
            'handler_id' => 'install.ios.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installIosOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.ios.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIosOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // iPhone — проблемы
        $installIosIssues = Screen::create([
            'key' => 'install.ios.issues',
            'title' => 'Что-то не работает',
            'text' => '⚠️ Текст о том, что здесь пользователь сможет найти ответы на распространённые проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.ios.issues',
        ]);

        ScreenButton::create([
            'screen_id' => $installIosIssues->id,
            'text' => 'Перейти к разделу "Что-то не работает"',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIosIssues->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // =====================================================
        // AndroidTV
        // =====================================================
        $installAndroidtv = Screen::create([
            'key' => 'install.androidtv',
            'title' => 'AndroidTV',
            'text' => '📺 Инструкция по установке',
            'handler_id' => 'install.androidtv',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtv->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 1,
        ]);

        // =====================================================
        // AppleTV
        // =====================================================
        $installAppletv = Screen::create([
            'key' => 'install.appletv',
            'title' => 'AppleTV',
            'text' => '📺 Выдача конфига + инструкция по установке / единоразовая ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.appletv',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'У меня другая программа',
            'next_screen_key' => 'install.appletv.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.appletv.issues',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // AppleTV — другая программа
        $installAppletvOther = Screen::create([
            'key' => 'install.appletv.other',
            'title' => 'Другая программа',
            'text' => '📲 Инструкция по установке',
            'handler_id' => 'install.appletv.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.appletv.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // AppleTV — проблемы
        $installAppletvIssues = Screen::create([
            'key' => 'install.appletv.issues',
            'title' => 'Что-то не работает',
            'text' => '⚠️ Текст о том, что здесь пользователь сможет найти ответы на распространённые проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.appletv.issues',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvIssues->id,
            'text' => 'Перейти к разделу "Что-то не работает"',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvIssues->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // =====================================================
        // Windows
        // =====================================================
        $installWindows = Screen::create([
            'key' => 'install.windows',
            'title' => 'Windows',
            'text' => '🪟 Выдача конфига + инструкция по установке / единоразовая ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.windows',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'У меня другая программа',
            'next_screen_key' => 'install.windows.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.windows.issues',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Windows — другая программа
        $installWindowsOther = Screen::create([
            'key' => 'install.windows.other',
            'title' => 'Другая программа',
            'text' => '📲 Инструкция по установке',
            'handler_id' => 'install.windows.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.windows.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Windows — проблемы
        $installWindowsIssues = Screen::create([
            'key' => 'install.windows.issues',
            'title' => 'Что-то не работает',
            'text' => '⚠️ Текст о том, что здесь пользователь сможет найти ответы на распространённые проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.windows.issues',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsIssues->id,
            'text' => 'Перейти к разделу "Что-то не работает"',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsIssues->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // =====================================================
        // Mac
        // =====================================================
        $installMac = Screen::create([
            'key' => 'install.mac',
            'title' => 'Mac',
            'text' => '🍎 Выдача конфига + инструкция по установке / единоразовая ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.mac',
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'У меня другая программа',
            'next_screen_key' => 'install.mac.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.mac.issues',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Mac — другая программа
        $installMacOther = Screen::create([
            'key' => 'install.mac.other',
            'title' => 'Другая программа',
            'text' => '📲 Инструкция по установке',
            'handler_id' => 'install.mac.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.mac.issues',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Mac — проблемы
        $installMacIssues = Screen::create([
            'key' => 'install.mac.issues',
            'title' => 'Что-то не работает',
            'text' => '⚠️ Текст о том, что здесь пользователь сможет найти ответы на распространённые проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.mac.issues',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacIssues->id,
            'text' => 'Перейти к разделу "Что-то не работает"',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacIssues->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        $this->command->info('Install screens seeded successfully!');
    }
}
