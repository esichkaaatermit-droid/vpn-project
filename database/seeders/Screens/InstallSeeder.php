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
        // Корневой экран
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

        // ============================================
        // ANDROID
        // ============================================
        $installAndroid = Screen::create([
            'key' => 'install.android',
            'title' => 'Android',
            'text' => 'Выберите способ установки:',
            'handler_id' => 'install.android',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroid->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.android.instructions',
            'order' => 1,
        ]);

        // Android - Инструкции по установке
        $installAndroidInstructions = Screen::create([
            'key' => 'install.android.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Выберите вариант установки:',
            'handler_id' => 'install.android.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidInstructions->id,
            'text' => 'Я использую конфиги на Android',
            'next_screen_key' => 'install.android.config',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidInstructions->id,
            'text' => 'У меня хуавей/реалми/апк приложения',
            'next_screen_key' => 'install.android.huawei',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidInstructions->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.android.problem',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidInstructions->id,
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
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidConfigProblem->id,
            'text' => 'Назад',
            'next_screen_key' => 'install.android.config',
            'order' => 2,
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
            'text' => 'Назад',
            'next_screen_key' => 'install.android.instructions',
            'order' => 1,
        ]);

        // Android - Problem
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

        // ============================================
        // IPHONE/IPAD
        // ============================================
        $installIphone = Screen::create([
            'key' => 'install.iphone',
            'title' => 'iPhone/iPad',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
            'handler_id' => 'install.iphone',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphone->id,
            'text' => 'У меня другая программа',
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

        // iPhone - У меня другая программа
        $installIphoneOther = Screen::create([
            'key' => 'install.iphone.other',
            'title' => 'У меня другая программа',
            'text' => 'Выберите вариант:',
            'handler_id' => 'install.iphone.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOther->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.iphone.other.instructions',
            'order' => 1,
        ]);

        // iPhone - Other - Instructions
        $installIphoneOtherInstructions = Screen::create([
            'key' => 'install.iphone.other.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Инструкция по установке',
            'handler_id' => 'install.iphone.other.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOtherInstructions->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.iphone.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installIphoneOtherInstructions->id,
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

        // ============================================
        // ANDROIDTV
        // ============================================
        $installAndroidtv = Screen::create([
            'key' => 'install.androidtv',
            'title' => 'AndroidTV',
            'text' => 'Выберите действие:',
            'handler_id' => 'install.androidtv',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtv->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.androidtv.instructions',
            'order' => 1,
        ]);

        // AndroidTV - Instructions (конечная точка)
        $installAndroidtvInstructions = Screen::create([
            'key' => 'install.androidtv.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.androidtv.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installAndroidtvInstructions->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 1,
        ]);

        // ============================================
        // APPLETV
        // ============================================
        $installAppletv = Screen::create([
            'key' => 'install.appletv',
            'title' => 'AppleTV',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
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
            'next_screen_key' => 'install.appletv.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletv->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // AppleTV - У меня другая программа
        $installAppletvOther = Screen::create([
            'key' => 'install.appletv.other',
            'title' => 'У меня другая программа',
            'text' => 'Выберите вариант:',
            'handler_id' => 'install.appletv.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOther->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.appletv.other.instructions',
            'order' => 1,
        ]);

        // AppleTV - Other - Instructions
        $installAppletvOtherInstructions = Screen::create([
            'key' => 'install.appletv.other.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Инструкция по установке',
            'handler_id' => 'install.appletv.other.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOtherInstructions->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.appletv.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOtherInstructions->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 2,
        ]);

        // AppleTV - Other - Problem
        $installAppletvOtherProblem = Screen::create([
            'key' => 'install.appletv.other.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.appletv.other.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvOtherProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        // AppleTV - Problem
        $installAppletvProblem = Screen::create([
            'key' => 'install.appletv.problem',
            'title' => 'Что-то не работает',
            'text' => 'Текст о том, что здесь пользователь сможет найти ответы на распространенные проблемы + Выберите ваше устройство:',
            'handler_id' => 'install.appletv.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $installAppletvProblem->id,
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        // ============================================
        // WINDOWS
        // ============================================
        $installWindows = Screen::create([
            'key' => 'install.windows',
            'title' => 'Windows',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
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
            'next_screen_key' => 'install.windows.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindows->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Windows - У меня другая программа
        $installWindowsOther = Screen::create([
            'key' => 'install.windows.other',
            'title' => 'У меня другая программа',
            'text' => 'Выберите вариант:',
            'handler_id' => 'install.windows.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOther->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.windows.other.instructions',
            'order' => 1,
        ]);

        // Windows - Other - Instructions
        $installWindowsOtherInstructions = Screen::create([
            'key' => 'install.windows.other.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.windows.other.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOtherInstructions->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.windows.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installWindowsOtherInstructions->id,
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
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
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
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        // ============================================
        // MAC
        // ============================================
        $installMac = Screen::create([
            'key' => 'install.mac',
            'title' => 'Mac',
            'text' => 'Выдача конфига + инструкция по установке / сформированная ссылка на автоматическое добавление конфига в программу',
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
            'next_screen_key' => 'install.mac.problem',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $installMac->id,
            'text' => 'Другие устройства',
            'next_screen_key' => 'install.main',
            'order' => 3,
        ]);

        // Mac - У меня другая программа
        $installMacOther = Screen::create([
            'key' => 'install.mac.other',
            'title' => 'У меня другая программа',
            'text' => 'Выберите вариант:',
            'handler_id' => 'install.mac.other',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOther->id,
            'text' => 'Инструкции по установке',
            'next_screen_key' => 'install.mac.other.instructions',
            'order' => 1,
        ]);

        // Mac - Other - Instructions
        $installMacOtherInstructions = Screen::create([
            'key' => 'install.mac.other.instructions',
            'title' => 'Инструкции по установке',
            'text' => 'Инструкция на установку',
            'handler_id' => 'install.mac.other.instructions',
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOtherInstructions->id,
            'text' => 'Что-то не работает',
            'next_screen_key' => 'install.mac.other.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $installMacOtherInstructions->id,
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
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
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
            'text' => 'Выбрать проблему',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);
    }
}
