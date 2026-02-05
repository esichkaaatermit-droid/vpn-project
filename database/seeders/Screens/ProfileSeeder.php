<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Сидер ветки "Profile".
     */
    public function run(): void
    {
        $profileMain = Screen::create([
            'key' => 'profile.main',
            'title' => 'Профиль',
            'text' => 'Выберите интересующий вас раздел:',
            'handler_id' => 'profile.main',
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Мой профиль',
            'next_screen_key' => 'profile.my',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Как подключить VPN на другом доп устройстве',
            'next_screen_key' => 'profile.connect_device',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Перенос тарифа на другую почту',
            'next_screen_key' => 'profile.transfer',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Удаление аккаунта',
            'next_screen_key' => 'profile.delete',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Доступное количество устройств на аккаунт',
            'next_screen_key' => 'profile.devices_limit',
            'order' => 5,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Реферальная программа',
            'next_screen_key' => 'profile.referral',
            'order' => 6,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Назад',
            'next_screen_key' => 'main.menu',
            'order' => 7,
        ]);

        // Мой профиль
        $profileMy = Screen::create([
            'key' => 'profile.my',
            'title' => 'Мой профиль',
            'text' => 'Вся основная инфа по привязанной почте',
            'handler_id' => 'profile.my',
        ]);

        ScreenButton::create([
            'screen_id' => $profileMy->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Как подключить VPN на другом устройстве
        $profileConnectDevice = Screen::create([
            'key' => 'profile.connect_device',
            'title' => 'Как подключить VPN на другом устройстве',
            'text' => 'Вся основная инфа по тому, как подключить VPN на другом устройстве',
            'handler_id' => 'profile.connect_device',
        ]);

        ScreenButton::create([
            'screen_id' => $profileConnectDevice->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Перенос тарифа на другую почту
        $profileTransfer = Screen::create([
            'key' => 'profile.transfer',
            'title' => 'Перенос тарифа на другую почту',
            'text' => 'Вся основная инфа по тому, как перенести тариф на другую почту + Контакты поддержки',
            'handler_id' => 'profile.transfer',
        ]);

        ScreenButton::create([
            'screen_id' => $profileTransfer->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileTransfer->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Удаление аккаунта
        $profileDelete = Screen::create([
            'key' => 'profile.delete',
            'title' => 'Удаление аккаунта',
            'text' => 'Вся основная инфа по тому, как удалить аккаунт + Контакты поддержки',
            'handler_id' => 'profile.delete',
        ]);

        ScreenButton::create([
            'screen_id' => $profileDelete->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileDelete->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Доступное количество устройств
        $profileDevicesLimit = Screen::create([
            'key' => 'profile.devices_limit',
            'title' => 'Доступное количество устройств',
            'text' => 'Вся основная инфа по этому вопросу',
            'handler_id' => 'profile.devices_limit',
        ]);

        ScreenButton::create([
            'screen_id' => $profileDevicesLimit->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Реферальная программа
        $profileReferral = Screen::create([
            'key' => 'profile.referral',
            'title' => 'Реферальная программа',
            'text' => 'Вся основная инфа по реферальной программе + как она работает в приложении на выходе и через конфиги/ключи (пока нет, в будущем м/б)',
            'handler_id' => 'profile.referral',
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferral->id,
            'text' => 'Не начислились дни по реферальной программе',
            'next_screen_key' => 'profile.referral.problem',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferral->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Проблема с реферальной программой
        $profileReferralProblem = Screen::create([
            'key' => 'profile.referral.problem',
            'title' => 'Не начислились дни',
            'text' => 'Описание, что надо сделать в таком случае + Контакты поддержки',
            'handler_id' => 'profile.referral.problem',
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferralProblem->id,
            'text' => 'Написать в тех поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferralProblem->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.referral',
            'order' => 2,
        ]);
    }
}
