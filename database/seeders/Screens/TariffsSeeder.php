<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class TariffsSeeder extends Seeder
{
    /**
     * Сидер ветки "Tariffs".
     */
    public function run(): void
    {
        // Экран 1 — главный экран "Тарифы"
        $tariffsMain = Screen::create([
            'key' => 'tariffs.main',
            'title' => 'Тарифы',
            'text' => 'Текст о наполнении этого блока + Выберите вопрос, который вас интересует:',
            'handler_id' => 'tariffs.main',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsMain->id,
            'text' => 'Тарифы и их стоимость',
            'next_screen_key' => 'tariffs.pricing',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsMain->id,
            'text' => 'Что такое тариф Start',
            'next_screen_key' => 'tariffs.start_plan',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsMain->id,
            'text' => 'Оплата тарифа',
            'next_screen_key' => 'tariffs.payment',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsMain->id,
            'text' => 'Отмена подписки и возврат средств',
            'next_screen_key' => 'tariffs.cancel_refund',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsMain->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 5,
        ]);

        // Экран 2 — "Тарифы и их стоимость"
        $tariffsPricing = Screen::create([
            'key' => 'tariffs.pricing',
            'title' => 'Тарифы и их стоимость',
            'text' => 'Текст о тарифах + Выберите требуемый тариф',
            'handler_id' => 'tariffs.pricing',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing->id,
            'text' => 'Тариф на 1 месяц',
            'next_screen_key' => 'tariffs.pricing.1month',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing->id,
            'text' => 'Тариф на 3 месяца',
            'next_screen_key' => 'tariffs.pricing.3months',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing->id,
            'text' => 'Тариф на 6 месяцев',
            'next_screen_key' => 'tariffs.pricing.6months',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.main',
            'order' => 4,
        ]);

        // Подэкраны тарифов
        $tariffsPricing1month = Screen::create([
            'key' => 'tariffs.pricing.1month',
            'title' => 'Тариф на 1 месяц',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.pricing.1month',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing1month->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing1month->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.pricing',
            'order' => 2,
        ]);

        $tariffsPricing3months = Screen::create([
            'key' => 'tariffs.pricing.3months',
            'title' => 'Тариф на 3 месяца',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.pricing.3months',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing3months->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing3months->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.pricing',
            'order' => 2,
        ]);

        $tariffsPricing6months = Screen::create([
            'key' => 'tariffs.pricing.6months',
            'title' => 'Тариф на 6 месяцев',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.pricing.6months',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing6months->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPricing6months->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.pricing',
            'order' => 2,
        ]);

        // Экран 3 — "Что такое тариф Start"
        $tariffsStartPlan = Screen::create([
            'key' => 'tariffs.start_plan',
            'title' => 'Что такое тариф Start',
            'text' => 'Описание тарифа старт, преимущества на фоне тарифа Голд и базовый',
            'handler_id' => 'tariffs.start_plan',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsStartPlan->id,
            'text' => 'Перейти на Голд',
            'next_screen_key' => 'tariffs.gold',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsStartPlan->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.main',
            'order' => 2,
        ]);

        // Экран "Голд" с тарифами
        $tariffsGold = Screen::create([
            'key' => 'tariffs.gold',
            'title' => 'Тарифы Голд',
            'text' => 'Текст о тарифах + Выберите требуемый тариф',
            'handler_id' => 'tariffs.gold',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold->id,
            'text' => 'Тариф на 1 месяц',
            'next_screen_key' => 'tariffs.gold.1month',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold->id,
            'text' => 'Тариф на 3 месяца',
            'next_screen_key' => 'tariffs.gold.3months',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold->id,
            'text' => 'Тариф на 6 месяцев',
            'next_screen_key' => 'tariffs.gold.6months',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.start_plan',
            'order' => 4,
        ]);

        // Подэкраны тарифов Голд
        $tariffsGold1month = Screen::create([
            'key' => 'tariffs.gold.1month',
            'title' => 'Тариф Голд на 1 месяц',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.gold.1month',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold1month->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold1month->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.gold',
            'order' => 2,
        ]);

        $tariffsGold3months = Screen::create([
            'key' => 'tariffs.gold.3months',
            'title' => 'Тариф Голд на 3 месяца',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.gold.3months',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold3months->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold3months->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.gold',
            'order' => 2,
        ]);

        $tariffsGold6months = Screen::create([
            'key' => 'tariffs.gold.6months',
            'title' => 'Тариф Голд на 6 месяцев',
            'text' => 'Ссылка на оплату + Ссылка на оферту',
            'handler_id' => 'tariffs.gold.6months',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold6months->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsGold6months->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.gold',
            'order' => 2,
        ]);

        // Экран 4 — "Оплата тарифа"
        $tariffsPayment = Screen::create([
            'key' => 'tariffs.payment',
            'title' => 'Оплата тарифа',
            'text' => 'Объяснение как происходит оплата тарифа на разных устройствах + Короткое описание текущих тарифов',
            'handler_id' => 'tariffs.payment',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPayment->id,
            'text' => 'Оплатить тариф',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPayment->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.main',
            'order' => 2,
        ]);

        // Экран процесса оплаты (ожидание, через 15 минут если не оплачен → переход на tariffs.pay.failed)
        $tariffsPayProcess = Screen::create([
            'key' => 'tariffs.pay.process',
            'title' => 'Оплата тарифа',
            'text' => 'Ожидание оплаты... Если оплата не будет произведена в течение 15 минут, она будет отменена.',
            'handler_id' => 'tariffs.pay.process',
        ]);

        // Экран ошибки оплаты
        $tariffsPayFailed = Screen::create([
            'key' => 'tariffs.pay.failed',
            'title' => 'Оплата не прошла',
            'text' => 'Оплата тарифа на 1 месяц была отменена, попробуйте ещё раз или выберите другой тариф',
            'handler_id' => 'tariffs.pay.failed',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPayFailed->id,
            'text' => 'Оплатить тариф ещё раз',
            'next_screen_key' => 'tariffs.pay.process',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsPayFailed->id,
            'text' => 'Тарифы',
            'next_screen_key' => 'tariffs.main',
            'order' => 2,
        ]);

        // Экран 5 — "Отмена подписки и возврат средств"
        $tariffsCancelRefund = Screen::create([
            'key' => 'tariffs.cancel_refund',
            'title' => 'Отмена подписки и возврат средств',
            'text' => 'Описание текущего процесса отмены/возврата средств и/или описание того, как это сделать по кнопке',
            'handler_id' => 'tariffs.cancel_refund',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsCancelRefund->id,
            'text' => 'Отменить подписку',
            'next_screen_key' => 'tariffs.unsubscribe',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsCancelRefund->id,
            'text' => 'Возврат средств',
            'next_screen_key' => 'tariffs.refund',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsCancelRefund->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.main',
            'order' => 3,
        ]);

        // Экран отмены подписки
        $tariffsUnsubscribe = Screen::create([
            'key' => 'tariffs.unsubscribe',
            'title' => 'Отменить подписку',
            'text' => 'Инструкция по отмене подписки',
            'handler_id' => 'tariffs.unsubscribe',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsUnsubscribe->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.cancel_refund',
            'order' => 1,
        ]);

        // Экран возврата средств
        $tariffsRefund = Screen::create([
            'key' => 'tariffs.refund',
            'title' => 'Возврат средств',
            'text' => 'Информация о возврате средств',
            'handler_id' => 'tariffs.refund',
        ]);

        ScreenButton::create([
            'screen_id' => $tariffsRefund->id,
            'text' => 'Назад',
            'next_screen_key' => 'tariffs.cancel_refund',
            'order' => 1,
        ]);
    }
}
