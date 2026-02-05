<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class InstallSeeder extends Seeder
{
    /**
     * Сидер ветки "Установить Easy Light".
     */
    public function run(): void
    {
        $installMain = Screen::create([
            'key' => 'install.main',
            'title' => 'Установить Easy Light',
            'text' => 'Текст о целевом действии + Краткое описание, опционально сюда кнопку на актуальную версию\n\nВыберите ваше устройство:',
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
            'next_screen_key' => 'install.iphone',
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
            'text' => 'Назад',
            'next_screen_key' => 'main.menu',
            'order' => 7,
        ]);

        // --- Android ---
        $installAndroid = Screen::create([
            'key' => 'install.android',
            'title' => 'Android',
            'text' => 'Инструкция по установке',
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
            'text' => 'У меня хуавей/реалми/апк приложения',
            'next_screen_key' => 'install.android.huawei',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.problem',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 4,
        ]);

        // Android - Я использую конфиги
        $installAndroidConfig = Screen::create([
            'key' => 'install.android.config',
            'title' => 'Я использую конфиги на Android',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.android.config',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfig->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.config.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfig->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Android - Config - Problem
        $installAndroidConfigProblem = Screen::create([
            'key' => 'install.android.config.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.android.config.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfigProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        // Android - Huawei/Realme/APK
        $installAndroidHuawei = Screen::create([
            'key' => 'install.android.huawei',
            'title' => 'У меня хуавей/реалми/апк приложения',
            'text' => 'Инструкция по установке для хуавей/реалми/апк',
            'handler_id' => 'install.android.huawei',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidHuawei->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidHuawei->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Android - Problem (общий)
        $installAndroidProblem = Screen::create([
            'key' => 'install.android.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.android.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        // --- iPhone/iPad ---
        $installIphone = Screen::create([
            'key' => 'install.iphone',
            'title' => 'iPhone/iPad',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.iphone',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphone->id,
            'text' => 'У меня другие программы',
            'next_screen_key' => 'install.iphone.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIphone->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.iphone.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installIphone->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // iPhone - У меня другие программы
        $installIphoneOther = Screen::create([
            'key' => 'install.iphone.other',
            'title' => 'У меня другие программы',
            'text' => 'Инструкция по установке',
            'handler_id' => 'install.iphone.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.iphone.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // iPhone - Other - Problem
        $installIphoneOtherProblem = Screen::create([
            'key' => 'install.iphone.other.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.iphone.other.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOtherProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        // iPhone - Problem
        $installIphoneProblem = Screen::create([
            'key' => 'install.iphone.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.iphone.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        // --- AndroidTV ---
        $installAndroidtv = Screen::create([
            'key' => 'install.androidtv',
            'title' => 'AndroidTV',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.androidtv',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtv->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.androidtv.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtv->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // AndroidTV - Problem
        $installAndroidtvProblem = Screen::create([
            'key' => 'install.androidtv.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.androidtv.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtvProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtvProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // --- AppleTV ---
        $installAppletv = Screen::create([
            'key' => 'install.appletv',
            'title' => 'AppleTV',
            'text' => 'Выдача конфига + Инструкция по установке / форматированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.appletv',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.appletv.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // AppleTV - Problem
        $installAppletvProblem = Screen::create([
            'key' => 'install.appletv.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.appletv.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // --- Windows ---
        $installWindows = Screen::create([
            'key' => 'install.windows',
            'title' => 'Windows',
            'text' => 'Выдача конфига + Инструкция по установке / форматированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.windows',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'У меня другие программы',
            'next_screen_key' => 'install.windows.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.windows.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Windows - Other programs
        $installWindowsOther = Screen::create([
            'key' => 'install.windows.other',
            'title' => 'У меня другие программы',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.windows.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.windows.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Windows - Other - Problem
        $installWindowsOtherProblem = Screen::create([
            'key' => 'install.windows.other.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.windows.other.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOtherProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOtherProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Windows - Problem
        $installWindowsProblem = Screen::create([
            'key' => 'install.windows.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.windows.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // --- Mac ---
        $installMac = Screen::create([
            'key' => 'install.mac',
            'title' => 'Mac',
            'text' => 'Выдача конфига + Инструкция по установке / форматированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.mac',
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'У меня другие программы',
            'next_screen_key' => 'install.mac.other',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.mac.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Mac - Other programs
        $installMacOther = Screen::create([
            'key' => 'install.mac.other',
            'title' => 'У меня другие программы',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.mac.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOther->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.mac.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOther->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Mac - Other - Problem
        $installMacOtherProblem = Screen::create([
            'key' => 'install.mac.other.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.mac.other.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOtherProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOtherProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // Mac - Problem
        $installMacProblem = Screen::create([
            'key' => 'install.mac.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь может найти ссылки на раздел устранения проблем + Выберите ваше устройство',
            'handler_id' => 'install.mac.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacProblem->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacProblem->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);
    }
}
