<?php

namespace Database\Seeders\Screens;

use App\Models\Screen;
use App\Models\ScreenButton;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Сидер ветки "Faq".
     */
    public function run(): void
    {
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
            'text' => 'Назад',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        $faqTariffsQ2 = Screen::create([
            'key' => 'faq.tariffs.q2',
            'title' => 'Вопрос 2 о тарифах',
            'text' => 'Ответ на вопрос 2',
            'handler_id' => 'faq.tariffs.q2',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ2->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        $faqTariffsQ3 = Screen::create([
            'key' => 'faq.tariffs.q3',
            'title' => 'Вопрос 3 о тарифах',
            'text' => 'Ответ на вопрос 3',
            'handler_id' => 'faq.tariffs.q3',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ3->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        $faqTariffsQ4 = Screen::create([
            'key' => 'faq.tariffs.q4',
            'title' => 'Вопрос 4 о тарифах',
            'text' => 'Ответ на вопрос 4',
            'handler_id' => 'faq.tariffs.q4',
        ]);

        ScreenButton::create([
            'screen_id' => $faqTariffsQ4->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.tariffs',
            'order' => 1,
        ]);

        // Экран 3 — "Отменить подписку"
        $faqCancel = Screen::create([
            'key' => 'faq.cancel',
            'title' => 'Отменить подписку',
            'text' => 'Описание текущего процесса отмены и/или описание того, как это сделать по кнопке',
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
            'text' => 'Отменить подписку',
            'next_screen_key' => 'faq.cancel.process',
            'order' => 2,
        ]);

        // Экран процесса отмены подписки
        $faqCancelProcess = Screen::create([
            'key' => 'faq.cancel.process',
            'title' => 'Отмена подписки',
            'text' => 'Ваша подписка отменена. Вы можете продолжать пользоваться сервисом до окончания оплаченного периода.',
            'handler_id' => 'faq.cancel.process',
        ]);

        ScreenButton::create([
            'screen_id' => $faqCancelProcess->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.cancel',
            'order' => 1,
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
            'text' => 'Написать в поддержку',
            'next_screen_key' => 'faq.support.write',
            'order' => 1,
        ]);

        ScreenButton::create([
            'screen_id' => $faqSupport->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 2,
        ]);

        // Экран написания в поддержку
        $faqSupportWrite = Screen::create([
            'key' => 'faq.support.write',
            'title' => 'Написать в поддержку',
            'text' => 'Напишите ваш вопрос, и мы ответим в ближайшее время.\n\nТакже вы можете связаться с нами напрямую:\n📧 support@vpn-bot.ru\n💬 @vpn_support',
            'handler_id' => 'faq.support.write',
        ]);

        ScreenButton::create([
            'screen_id' => $faqSupportWrite->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.support',
            'order' => 1,
        ]);

        // Экран 5 — "О сервисе"
        $faqAbout = Screen::create([
            'key' => 'faq.about',
            'title' => 'О сервисе',
            'text' => 'Кто мы/на каких платформах доступны/ссылки на канал, сайт, почта, дни поддержки',
            'handler_id' => 'faq.about',
        ]);

        ScreenButton::create([
            'screen_id' => $faqAbout->id,
            'text' => 'Назад',
            'next_screen_key' => 'faq.main',
            'order' => 1,
        ]);

        // --- Подветка "Что-то сломалось" (faq.broken) ---

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
    }
}
