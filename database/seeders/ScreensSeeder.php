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

        // =====================================================
        // Ветка "Тарифы"
        // =====================================================

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

        // =====================================================
        // Ветка "Вопросы и ответы" (FAQ)
        // =====================================================

        // Экран 1 — главный экран "Вопросы и ответы"
        $faqMain = Screen::create([
            'key' => 'faq.main',
            'title' => 'Вопросы и ответы',
            'text' => 'Обобщённое приветственное сообщение + Выберите вопрос, который вас интересует:',
            'handler_id' => 'faq.main',
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'Что-то сломалось',
            'next_screen_key' => 'faq.broken',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'Вопросы о тарифах',
            'next_screen_key' => 'faq.tariffs',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'Отменить подписку',
            'next_screen_key' => 'faq.cancel',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'Техподдержка',
            'next_screen_key' => 'faq.support',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'О сервисе',
            'next_screen_key' => 'faq.about',
            'order' => 5,
        ]);

        ScreenButton::create([
            'screen_id' => $faqMain->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 6,
        ]);

        // Экран 2 — "Вопросы о тарифах"
        $faqTariffs = Screen::create([
            'key' => 'faq.tariffs',
            'title' => 'Вопросы о тарифах',
            'text' => 'Перечисление распространённых вопросов о тарифах. Выберите вопрос:',
            'handler_id' => 'faq.tariffs',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffs->id,
            'text' => 'Вопрос 1',
            'next_screen_key' => 'faq.tariffs.q1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffs->id,
            'text' => 'Вопрос 2',
            'next_screen_key' => 'faq.tariffs.q2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffs->id,
            'text' => 'Вопрос 3',
            'next_screen_key' => 'faq.tariffs.q3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffs->id,
            'text' => 'Вопрос 4',
            'next_screen_key' => 'faq.tariffs.q4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffs->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 5,
        ]);

        // Экраны для вопросов о тарифах (q1, q2, q3, q4)
        $faqTariffsQ1 = Screen::create([
            'key' => 'faq.tariffs.q1',
            'title' => 'Вопрос 1 о тарифах',
            'text' => 'Ответ на вопрос 1',
            'handler_id' => 'faq.tariffs.q1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ1->id,
            'text' => 'Назад к вопросам',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ1->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        $faqTariffsQ2 = Screen::create([
            'key' => 'faq.tariffs.q2',
            'title' => 'Вопрос 2 о тарифах',
            'text' => 'Ответ на вопрос 2',
            'handler_id' => 'faq.tariffs.q2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ2->id,
            'text' => 'Назад к вопросам',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ2->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        $faqTariffsQ3 = Screen::create([
            'key' => 'faq.tariffs.q3',
            'title' => 'Вопрос 3 о тарифах',
            'text' => 'Ответ на вопрос 3',
            'handler_id' => 'faq.tariffs.q3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ3->id,
            'text' => 'Назад к вопросам',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ3->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        $faqTariffsQ4 = Screen::create([
            'key' => 'faq.tariffs.q4',
            'title' => 'Вопрос 4 о тарифах',
            'text' => 'Ответ на вопрос 4',
            'handler_id' => 'faq.tariffs.q4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ4->id,
            'text' => 'Назад к вопросам',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ4->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        // Экран 3 — "Отменить подписку"
        $faqCancel = Screen::create([
            'key' => 'faq.cancel',
            'title' => 'Отменить подписку',
            'text' => 'Описание текущего процесса отмены и/или описание того, как это сделать.',
            'handler_id' => 'faq.cancel',
        ]);

        ScreenButton::create([
            'screen_id' => $faqCancel->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqCancel->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        // Экран 4 — "Техподдержка"
        $faqSupport = Screen::create([
            'key' => 'faq.support',
            'title' => 'Техподдержка',
            'text' => 'Описание + контакты поддержки',
            'handler_id' => 'faq.support',
        ]);

        ScreenButton::create([
            'screen_id' => $faqSupport->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqSupport->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        // Экран 5 — "О сервисе"
        $faqAbout = Screen::create([
            'key' => 'faq.about',
            'title' => 'О сервисе',
            'text' => 'Кто мы, на каких платформах доступны, ссылки на канал/сайт',
            'handler_id' => 'faq.about',
        ]);

        ScreenButton::create([
            'screen_id' => $faqAbout->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqAbout->id,
            'text' => 'В главное меню',
            'next_screen_key' => 'main.menu',
            'order' => 2,
        ]);

        // =====================================================
        // Ветка "Что-то сломалось" (faq.broken)
        // =====================================================

        // Главный экран "Что-то сломалось"
        $faqBroken = Screen::create([
            'key' => 'faq.broken',
            'title' => 'Что-то сломалось',
            'text' => 'Выберите вашу платформу, чтобы мы могли помочь:',
            'handler_id' => 'faq.broken',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'Android',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'iPhone/iPad',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'AndroidTV',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'AppleTV',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'Windows',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 5,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'Mac',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 6,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBroken->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 7,
        ]);

        // Категория: Android
        $faqBrokenAndroid = Screen::create([
            'key' => 'faq.broken.android',
            'title' => 'Android',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.android',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroid->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.android.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroid->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.android.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroid->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.android.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroid->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.android.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroid->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы Android
        $faqBrokenAndroidP1 = Screen::create([
            'key' => 'faq.broken.android.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.android.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidP2 = Screen::create([
            'key' => 'faq.broken.android.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.android.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidP3 = Screen::create([
            'key' => 'faq.broken.android.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.android.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidP4 = Screen::create([
            'key' => 'faq.broken.android.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.android.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.android',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        // Категория: iPhone/iPad
        $faqBrokenIphone = Screen::create([
            'key' => 'faq.broken.iphone',
            'title' => 'iPhone/iPad',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.iphone',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphone->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.iphone.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphone->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.iphone.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphone->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.iphone.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphone->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.iphone.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphone->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы iPhone/iPad
        $faqBrokenIphoneP1 = Screen::create([
            'key' => 'faq.broken.iphone.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.iphone.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenIphoneP2 = Screen::create([
            'key' => 'faq.broken.iphone.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.iphone.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenIphoneP3 = Screen::create([
            'key' => 'faq.broken.iphone.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.iphone.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenIphoneP4 = Screen::create([
            'key' => 'faq.broken.iphone.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.iphone.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.iphone',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenIphoneP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        // Категория: AndroidTV
        $faqBrokenAndroidtv = Screen::create([
            'key' => 'faq.broken.androidtv',
            'title' => 'AndroidTV',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.androidtv',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtv->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.androidtv.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtv->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.androidtv.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtv->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.androidtv.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtv->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.androidtv.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtv->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы AndroidTV
        $faqBrokenAndroidtvP1 = Screen::create([
            'key' => 'faq.broken.androidtv.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.androidtv.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidtvP2 = Screen::create([
            'key' => 'faq.broken.androidtv.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.androidtv.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidtvP3 = Screen::create([
            'key' => 'faq.broken.androidtv.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.androidtv.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAndroidtvP4 = Screen::create([
            'key' => 'faq.broken.androidtv.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.androidtv.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.androidtv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAndroidtvP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        // Категория: AppleTV
        $faqBrokenAppletv = Screen::create([
            'key' => 'faq.broken.appletv',
            'title' => 'AppleTV',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.appletv',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletv->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.appletv.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletv->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.appletv.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletv->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.appletv.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletv->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.appletv.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletv->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы AppleTV
        $faqBrokenAppletvP1 = Screen::create([
            'key' => 'faq.broken.appletv.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.appletv.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAppletvP2 = Screen::create([
            'key' => 'faq.broken.appletv.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.appletv.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAppletvP3 = Screen::create([
            'key' => 'faq.broken.appletv.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.appletv.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenAppletvP4 = Screen::create([
            'key' => 'faq.broken.appletv.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.appletv.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.appletv',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenAppletvP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        // Категория: Windows
        $faqBrokenWindows = Screen::create([
            'key' => 'faq.broken.windows',
            'title' => 'Windows',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.windows',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindows->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.windows.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindows->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.windows.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindows->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.windows.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindows->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.windows.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindows->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы Windows
        $faqBrokenWindowsP1 = Screen::create([
            'key' => 'faq.broken.windows.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.windows.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenWindowsP2 = Screen::create([
            'key' => 'faq.broken.windows.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.windows.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenWindowsP3 = Screen::create([
            'key' => 'faq.broken.windows.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.windows.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenWindowsP4 = Screen::create([
            'key' => 'faq.broken.windows.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.windows.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.windows',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenWindowsP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        // Категория: Mac
        $faqBrokenMac = Screen::create([
            'key' => 'faq.broken.mac',
            'title' => 'Mac',
            'text' => 'Перечисление распространённых проблем: Проблема 1, Проблема 2, Проблема 3, Проблема 4, и тд...',
            'handler_id' => 'faq.broken.mac',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMac->id,
            'text' => 'Проблема 1',
            'next_screen_key' => 'faq.broken.mac.p1',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMac->id,
            'text' => 'Проблема 2',
            'next_screen_key' => 'faq.broken.mac.p2',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMac->id,
            'text' => 'Проблема 3',
            'next_screen_key' => 'faq.broken.mac.p3',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMac->id,
            'text' => 'Проблема 4',
            'next_screen_key' => 'faq.broken.mac.p4',
            'order' => 4,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMac->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken',
            'order' => 5,
        ]);

        // Проблемы Mac
        $faqBrokenMacP1 = Screen::create([
            'key' => 'faq.broken.mac.p1',
            'title' => 'Проблема 1',
            'text' => 'Решение проблемы 1',
            'handler_id' => 'faq.broken.mac.p1',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP1->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP1->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenMacP2 = Screen::create([
            'key' => 'faq.broken.mac.p2',
            'title' => 'Проблема 2',
            'text' => 'Решение проблемы 2',
            'handler_id' => 'faq.broken.mac.p2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP2->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenMacP3 = Screen::create([
            'key' => 'faq.broken.mac.p3',
            'title' => 'Проблема 3',
            'text' => 'Решение проблемы 3',
            'handler_id' => 'faq.broken.mac.p3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP3->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
        ]);

        $faqBrokenMacP4 = Screen::create([
            'key' => 'faq.broken.mac.p4',
            'title' => 'Проблема 4',
            'text' => 'Решение проблемы 4',
            'handler_id' => 'faq.broken.mac.p4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.broken.mac',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqBrokenMacP4->id,
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support',
            'order' => 2,
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

        // Главное меню (main.menu)
        $mainMenu = Screen::create([
            'key' => 'main.menu',
            'title' => 'Главное меню',
            'text' => 'Добро пожаловать! Выберите интересующий вас раздел:',
            'handler_id' => 'main.menu',
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Установить Easy Light',
            'next_screen_key' => 'install.main',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Вопросы и ответы',
            'next_screen_key' => 'faq.main',
            'order' => 2,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Тарифы',
            'next_screen_key' => 'tariffs.main',
            'order' => 3,
        ]);

        ScreenButton::create([
            'screen_id' => $mainMenu->id,
            'text' => 'Документация',
            'next_screen_key' => 'docs.main',
            'order' => 4,
        ]);

        $this->command->info('Screens seeded successfully!');
    }
}
