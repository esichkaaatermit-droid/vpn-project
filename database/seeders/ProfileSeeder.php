<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =====================================================
        // Ветка "Профиль"
        // =====================================================

        // Экран 1 — главный экран "Профиль"
        $profileMain = Screen::create([
            'key' => 'profile.main',
            'title' => 'Профиль',
            'text' => 'Текст о наполнении этого блока + Выберите вопрос, который вас интересует:',
            'handler_id' => 'profile.main',
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Мой профиль',
            'next_screen_key' => 'profile.my_profile',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Как подключить VPN на другом доп устройстве',
            'next_screen_key' => 'profile.add_device',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Перенос тарифа на другую почту',
            'next_screen_key' => 'profile.transfer_plan',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Удаление аккаунта',
            'next_screen_key' => 'profile.delete_account',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $profileMain->id,
            'text' => 'Доступное количество устройств на аккаунте',
            'next_screen_key' => 'profile.device_limit',
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
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 7,
        ]);

        // Экран 2 — "Мой профиль"
        $profileMyProfile = Screen::create([
            'key' => 'profile.my_profile',
            'title' => 'Мой профиль',
            'text' => 'Вся основная инфа по привязанной почте',
            'handler_id' => 'profile.my_profile',
        ]);

        ScreenButton::create([
            'screen_id' => $profileMyProfile->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Экран 3 — "Как подключить VPN на другом доп устройстве"
        $profileAddDevice = Screen::create([
            'key' => 'profile.add_device',
            'title' => 'Как подключить VPN на другом доп устройстве',
            'text' => 'Вся основная инфа по тому, как подключить VPN на другом устройстве',
            'handler_id' => 'profile.add_device',
        ]);

        ScreenButton::create([
            'screen_id' => $profileAddDevice->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Экран 4 — "Перенос тарифа на другую почту"
        $profileTransferPlan = Screen::create([
            'key' => 'profile.transfer_plan',
            'title' => 'Перенос тарифа на другую почту',
            'text' => 'Вся основная инфа по тому, как перенести тариф на другую почту + Контакты поддержки:',
            'handler_id' => 'profile.transfer_plan',
        ]);

        ScreenButton::create([
            'screen_id' => $profileTransferPlan->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileTransferPlan->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Экран 5 — "Удаление аккаунта"
        $profileDeleteAccount = Screen::create([
            'key' => 'profile.delete_account',
            'title' => 'Удаление аккаунта',
            'text' => 'Вся основная инфа по тому, как удалить аккаунт + Контакты поддержки:',
            'handler_id' => 'profile.delete_account',
        ]);

        ScreenButton::create([
            'screen_id' => $profileDeleteAccount->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileDeleteAccount->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Экран 6 — "Доступное количество устройств на аккаунте"
        $profileDeviceLimit = Screen::create([
            'key' => 'profile.device_limit',
            'title' => 'Доступное количество устройств на аккаунте',
            'text' => 'Вся основная инфа по этому вопросу',
            'handler_id' => 'profile.device_limit',
        ]);

        ScreenButton::create([
            'screen_id' => $profileDeviceLimit->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 1,
        ]);

        // Экран 7 — "Реферальная программа"
        $profileReferral = Screen::create([
            'key' => 'profile.referral',
            'title' => 'Реферальная программа',
            'text' => 'Вся основная инфа по реферальной программе + как она работает в приложении на андроид и через конфиги/ключи (пока нет, в будущем мб)',
            'handler_id' => 'profile.referral',
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferral->id,
            'text' => 'Не начислились дни по реферальной программе',
            'next_screen_key' => 'profile.referral.issue',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferral->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.main',
            'order' => 2,
        ]);

        // Экран 8 — "Не начислились дни по реферальной программе"
        $profileReferralIssue = Screen::create([
            'key' => 'profile.referral.issue',
            'title' => 'Не начислились дни по реферальной программе',
            'text' => 'Описываем, что надо сделать в таком случае + Контакты поддержки',
            'handler_id' => 'profile.referral.issue',
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferralIssue->id,
            'text' => 'Написать в тех поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $profileReferralIssue->id,
            'text' => 'Назад',
            'next_screen_key' => 'profile.referral',
            'order' => 2,
        ]);

        $this->command->info('Profile screens seeded successfully!');
    }
}
