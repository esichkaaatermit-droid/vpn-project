# 🤖 Карта сценария бота

**Сгенерировано:** 03.02.2026 16:22:20

| Метрика | Значение |
|---------|----------|
| Экранов | 95 |
| Кнопок | 243 |
| С обработчиками | 91 |
| Битых ссылок | 4 |

## ⚠️ Битые ссылки

- `account.config` → [📥 Скачать конфиг] → ❌ `account.download`
- `troubleshoot.main` → [🍎 macOS] → ❌ `troubleshoot.macos`
- `troubleshoot.main` → [📱 iOS/Android] → ❌ `troubleshoot.mobile`
- `support.main` → [✍️ Написать в поддержку] → ❌ `support.write`

## 📊 Диаграмма связей

```mermaid
flowchart TD

    subgraph Start
        start["Главное меню"]
    end

    subgraph Tariffs
        tariffs_main["Тарифы"]
        tariffs_pricing["Тарифы и их стоимость"]
        tariffs_pricing_1month["Тариф на 1 месяц"]
        tariffs_pricing_3months["Тариф на 3 месяца"]
        tariffs_pricing_6months["Тариф на 6 месяцев"]
        tariffs_start_plan["Что такое тариф Start"]
        tariffs_gold["Тарифы Голд"]
        tariffs_gold_1month["Тариф Голд на 1 месяц"]
        tariffs_gold_3months["Тариф Голд на 3 месяца"]
        tariffs_gold_6months["Тариф Голд на 6 месяцев"]
        tariffs_payment["Оплата тарифа"]
        tariffs_pay_process["Оплата тарифа"]
        tariffs_pay_failed["Оплата не прошла"]
        tariffs_cancel_refund["Отмена подписки и возврат сред"]
        tariffs_unsubscribe["Отменить подписку"]
        tariffs_refund["Возврат средств"]
    end

    subgraph Faq
        faq_main["Вопросы и ответы"]
        faq_tariffs["Вопросы о тарифах"]
        faq_tariffs_q1["Вопрос 1 о тарифах"]
        faq_tariffs_q2["Вопрос 2 о тарифах"]
        faq_tariffs_q3["Вопрос 3 о тарифах"]
        faq_tariffs_q4["Вопрос 4 о тарифах"]
        faq_cancel["Отменить подписку"]
        faq_support["Техподдержка"]
        faq_about["О сервисе"]
        faq_broken["Что-то сломалось"]
        faq_broken_android["Android"]
        faq_broken_android_p1["Проблема 1"]
        faq_broken_android_p2["Проблема 2"]
        faq_broken_android_p3["Проблема 3"]
        faq_broken_android_p4["Проблема 4"]
        faq_broken_iphone["iPhone/iPad"]
        faq_broken_iphone_p1["Проблема 1"]
        faq_broken_iphone_p2["Проблема 2"]
        faq_broken_iphone_p3["Проблема 3"]
        faq_broken_iphone_p4["Проблема 4"]
        faq_broken_androidtv["AndroidTV"]
        faq_broken_androidtv_p1["Проблема 1"]
        faq_broken_androidtv_p2["Проблема 2"]
        faq_broken_androidtv_p3["Проблема 3"]
        faq_broken_androidtv_p4["Проблема 4"]
        faq_broken_appletv["AppleTV"]
        faq_broken_appletv_p1["Проблема 1"]
        faq_broken_appletv_p2["Проблема 2"]
        faq_broken_appletv_p3["Проблема 3"]
        faq_broken_appletv_p4["Проблема 4"]
        faq_broken_windows["Windows"]
        faq_broken_windows_p1["Проблема 1"]
        faq_broken_windows_p2["Проблема 2"]
        faq_broken_windows_p3["Проблема 3"]
        faq_broken_windows_p4["Проблема 4"]
        faq_broken_mac["Mac"]
        faq_broken_mac_p1["Проблема 1"]
        faq_broken_mac_p2["Проблема 2"]
        faq_broken_mac_p3["Проблема 3"]
        faq_broken_mac_p4["Проблема 4"]
    end

    subgraph Account
        account_main["Личный кабинет"]
        account_status["Статус подписки"]
        account_config["Конфигурация VPN"]
    end

    subgraph Troubleshoot
        troubleshoot_main["Устранение неполадок"]
        troubleshoot_windows["Проблемы на Windows"]
    end

    subgraph Support
        support_main["Поддержка"]
    end

    subgraph Main
        main_menu["Главное меню"]
    end

    subgraph Profile
        profile_main["Профиль"]
        profile_my_profile["Мой профиль"]
        profile_add_device["Как подключить VPN на другом у"]
        profile_transfer_plan["Перенос тарифа на другую почту"]
        profile_delete_account["Удаление аккаунта"]
        profile_device_limit["Доступное количество устройств"]
        profile_referral["Реферальная программа"]
        profile_referral_issue["Не начислились дни по рефераль"]
    end

    subgraph Docs
        docs_main["Документация"]
        docs_privacy["Политика конфиденциальности"]
        docs_terms["Пользовательское соглашение"]
        docs_personal_data["Политика обработки персональны"]
        docs_refund["Условия возврата"]
    end

    subgraph Install
        install_main["Установить Easy Light"]
        install_android["Android"]
        install_android_config["Конфиги на Android"]
        install_android_app["Приложение на Android"]
        install_android_issues["Что-то не работает"]
        install_ios["iPhone/iPad"]
        install_ios_other["Другая программа"]
        install_ios_issues["Что-то не работает"]
        install_androidtv["AndroidTV"]
        install_appletv["AppleTV"]
        install_appletv_other["Другая программа"]
        install_appletv_issues["Что-то не работает"]
        install_windows["Windows"]
        install_windows_other["Другая программа"]
        install_windows_issues["Что-то не работает"]
        install_mac["Mac"]
        install_mac_other["Другая программа"]
        install_mac_issues["Что-то не работает"]
    end

    %% Связи между экранами
    start -->|💰 Тарифы| tariffs_main
    start -->|❓ FAQ| faq_main
    start -->|👤 Личный кабинет| account_main
    start -->|🔧 Устранение неполад| troubleshoot_main
    start -->|📞 Поддержка| support_main
    tariffs_main -->|Тарифы и их стоимост| tariffs_pricing
    tariffs_main -->|Что такое тариф Star| tariffs_start_plan
    tariffs_main -->|Оплата тарифа| tariffs_payment
    tariffs_main -->|Отмена подписки и во| tariffs_cancel_refund
    tariffs_main -->|В главное меню| main_menu
    tariffs_pricing -->|Тариф на 1 месяц| tariffs_pricing_1month
    tariffs_pricing -->|Тариф на 3 месяца| tariffs_pricing_3months
    tariffs_pricing -->|Тариф на 6 месяцев| tariffs_pricing_6months
    tariffs_pricing -->|Назад| tariffs_main
    tariffs_pricing_1month -->|Оплатить тариф| tariffs_pay_process
    tariffs_pricing_1month -->|Назад| tariffs_pricing
    tariffs_pricing_3months -->|Оплатить тариф| tariffs_pay_process
    tariffs_pricing_3months -->|Назад| tariffs_pricing
    tariffs_pricing_6months -->|Оплатить тариф| tariffs_pay_process
    tariffs_pricing_6months -->|Назад| tariffs_pricing
    tariffs_start_plan -->|Перейти на Голд| tariffs_gold
    tariffs_start_plan -->|Назад| tariffs_main
    tariffs_gold -->|Тариф на 1 месяц| tariffs_gold_1month
    tariffs_gold -->|Тариф на 3 месяца| tariffs_gold_3months
    tariffs_gold -->|Тариф на 6 месяцев| tariffs_gold_6months
    tariffs_gold -->|Назад| tariffs_start_plan
    tariffs_gold_1month -->|Оплатить тариф| tariffs_pay_process
    tariffs_gold_1month -->|Назад| tariffs_gold
    tariffs_gold_3months -->|Оплатить тариф| tariffs_pay_process
    tariffs_gold_3months -->|Назад| tariffs_gold
    tariffs_gold_6months -->|Оплатить тариф| tariffs_pay_process
    tariffs_gold_6months -->|Назад| tariffs_gold
    tariffs_payment -->|Оплатить тариф| tariffs_pay_process
    tariffs_payment -->|Назад| tariffs_main
    tariffs_pay_failed -->|Оплатить тариф ещё р| tariffs_pay_process
    tariffs_pay_failed -->|Тарифы| tariffs_main
    tariffs_cancel_refund -->|Отменить подписку| tariffs_unsubscribe
    tariffs_cancel_refund -->|Возврат средств| tariffs_refund
    tariffs_cancel_refund -->|Назад| tariffs_main
    tariffs_unsubscribe -->|Назад| tariffs_cancel_refund
    tariffs_refund -->|Назад| tariffs_cancel_refund
    faq_main -->|Что-то сломалось| faq_broken
    faq_main -->|Вопросы о тарифах| faq_tariffs
    faq_main -->|Отменить подписку| faq_cancel
    faq_main -->|Техподдержка| faq_support
    faq_main -->|О сервисе| faq_about
    faq_main -->|В главное меню| main_menu
    faq_tariffs -->|Вопрос 1| faq_tariffs_q1
    faq_tariffs -->|Вопрос 2| faq_tariffs_q2
    faq_tariffs -->|Вопрос 3| faq_tariffs_q3
    faq_tariffs -->|Вопрос 4| faq_tariffs_q4
    faq_tariffs -->|Назад| faq_main
    faq_tariffs_q1 -->|Назад к вопросам| faq_tariffs
    faq_tariffs_q1 -->|В главное меню| main_menu
    faq_tariffs_q2 -->|Назад к вопросам| faq_tariffs
    faq_tariffs_q2 -->|В главное меню| main_menu
    faq_tariffs_q3 -->|Назад к вопросам| faq_tariffs
    faq_tariffs_q3 -->|В главное меню| main_menu
    faq_tariffs_q4 -->|Назад к вопросам| faq_tariffs
    faq_tariffs_q4 -->|В главное меню| main_menu
    faq_cancel -->|Назад| faq_main
    faq_cancel -->|В главное меню| main_menu
    faq_support -->|Назад| faq_main
    faq_support -->|В главное меню| main_menu
    faq_about -->|Назад| faq_main
    faq_about -->|В главное меню| main_menu
    faq_broken -->|Android| faq_broken_android
    faq_broken -->|iPhone/iPad| faq_broken_iphone
    faq_broken -->|AndroidTV| faq_broken_androidtv
    faq_broken -->|AppleTV| faq_broken_appletv
    faq_broken -->|Windows| faq_broken_windows
    faq_broken -->|Mac| faq_broken_mac
    faq_broken -->|Назад| faq_main
    faq_broken_android -->|Проблема 1| faq_broken_android_p1
    faq_broken_android -->|Проблема 2| faq_broken_android_p2
    faq_broken_android -->|Проблема 3| faq_broken_android_p3
    faq_broken_android -->|Проблема 4| faq_broken_android_p4
    faq_broken_android -->|Назад| faq_broken
    faq_broken_android_p1 -->|Назад| faq_broken_android
    faq_broken_android_p1 -->|Написать в поддержку| faq_support
    faq_broken_android_p2 -->|Назад| faq_broken_android
    faq_broken_android_p2 -->|Написать в поддержку| faq_support
    faq_broken_android_p3 -->|Назад| faq_broken_android
    faq_broken_android_p3 -->|Написать в поддержку| faq_support
    faq_broken_android_p4 -->|Назад| faq_broken_android
    faq_broken_android_p4 -->|Написать в поддержку| faq_support
    faq_broken_iphone -->|Проблема 1| faq_broken_iphone_p1
    faq_broken_iphone -->|Проблема 2| faq_broken_iphone_p2
    faq_broken_iphone -->|Проблема 3| faq_broken_iphone_p3
    faq_broken_iphone -->|Проблема 4| faq_broken_iphone_p4
    faq_broken_iphone -->|Назад| faq_broken
    faq_broken_iphone_p1 -->|Назад| faq_broken_iphone
    faq_broken_iphone_p1 -->|Написать в поддержку| faq_support
    faq_broken_iphone_p2 -->|Назад| faq_broken_iphone
    faq_broken_iphone_p2 -->|Написать в поддержку| faq_support
    faq_broken_iphone_p3 -->|Назад| faq_broken_iphone
    faq_broken_iphone_p3 -->|Написать в поддержку| faq_support
    faq_broken_iphone_p4 -->|Назад| faq_broken_iphone
    faq_broken_iphone_p4 -->|Написать в поддержку| faq_support
    faq_broken_androidtv -->|Проблема 1| faq_broken_androidtv_p1
    faq_broken_androidtv -->|Проблема 2| faq_broken_androidtv_p2
    faq_broken_androidtv -->|Проблема 3| faq_broken_androidtv_p3
    faq_broken_androidtv -->|Проблема 4| faq_broken_androidtv_p4
    faq_broken_androidtv -->|Назад| faq_broken
    faq_broken_androidtv_p1 -->|Назад| faq_broken_androidtv
    faq_broken_androidtv_p1 -->|Написать в поддержку| faq_support
    faq_broken_androidtv_p2 -->|Назад| faq_broken_androidtv
    faq_broken_androidtv_p2 -->|Написать в поддержку| faq_support
    faq_broken_androidtv_p3 -->|Назад| faq_broken_androidtv
    faq_broken_androidtv_p3 -->|Написать в поддержку| faq_support
    faq_broken_androidtv_p4 -->|Назад| faq_broken_androidtv
    faq_broken_androidtv_p4 -->|Написать в поддержку| faq_support
    faq_broken_appletv -->|Проблема 1| faq_broken_appletv_p1
    faq_broken_appletv -->|Проблема 2| faq_broken_appletv_p2
    faq_broken_appletv -->|Проблема 3| faq_broken_appletv_p3
    faq_broken_appletv -->|Проблема 4| faq_broken_appletv_p4
    faq_broken_appletv -->|Назад| faq_broken
    faq_broken_appletv_p1 -->|Назад| faq_broken_appletv
    faq_broken_appletv_p1 -->|Написать в поддержку| faq_support
    faq_broken_appletv_p2 -->|Назад| faq_broken_appletv
    faq_broken_appletv_p2 -->|Написать в поддержку| faq_support
    faq_broken_appletv_p3 -->|Назад| faq_broken_appletv
    faq_broken_appletv_p3 -->|Написать в поддержку| faq_support
    faq_broken_appletv_p4 -->|Назад| faq_broken_appletv
    faq_broken_appletv_p4 -->|Написать в поддержку| faq_support
    faq_broken_windows -->|Проблема 1| faq_broken_windows_p1
    faq_broken_windows -->|Проблема 2| faq_broken_windows_p2
    faq_broken_windows -->|Проблема 3| faq_broken_windows_p3
    faq_broken_windows -->|Проблема 4| faq_broken_windows_p4
    faq_broken_windows -->|Назад| faq_broken
    faq_broken_windows_p1 -->|Назад| faq_broken_windows
    faq_broken_windows_p1 -->|Написать в поддержку| faq_support
    faq_broken_windows_p2 -->|Назад| faq_broken_windows
    faq_broken_windows_p2 -->|Написать в поддержку| faq_support
    faq_broken_windows_p3 -->|Назад| faq_broken_windows
    faq_broken_windows_p3 -->|Написать в поддержку| faq_support
    faq_broken_windows_p4 -->|Назад| faq_broken_windows
    faq_broken_windows_p4 -->|Написать в поддержку| faq_support
    faq_broken_mac -->|Проблема 1| faq_broken_mac_p1
    faq_broken_mac -->|Проблема 2| faq_broken_mac_p2
    faq_broken_mac -->|Проблема 3| faq_broken_mac_p3
    faq_broken_mac -->|Проблема 4| faq_broken_mac_p4
    faq_broken_mac -->|Назад| faq_broken
    faq_broken_mac_p1 -->|Назад| faq_broken_mac
    faq_broken_mac_p1 -->|Написать в поддержку| faq_support
    faq_broken_mac_p2 -->|Назад| faq_broken_mac
    faq_broken_mac_p2 -->|Написать в поддержку| faq_support
    faq_broken_mac_p3 -->|Назад| faq_broken_mac
    faq_broken_mac_p3 -->|Написать в поддержку| faq_support
    faq_broken_mac_p4 -->|Назад| faq_broken_mac
    faq_broken_mac_p4 -->|Написать в поддержку| faq_support
    account_main -->|📊 Статус подписки| account_status
    account_main -->|⚙️ Получить конфиг| account_config
    account_main -->|⬅️ Назад| start
    account_status -->|🔄 Обновить| account_status
    account_status -->|⬅️ Назад| account_main
    account_config -.->|📥 Скачать конфиг| account_download[❌ account.download]
    account_config -->|⬅️ Назад| account_main
    troubleshoot_main -->|🪟 Windows| troubleshoot_windows
    troubleshoot_main -.->|🍎 macOS| troubleshoot_macos[❌ troubleshoot.macos]
    troubleshoot_main -.->|📱 iOS/Android| troubleshoot_mobile[❌ troubleshoot.mobile]
    troubleshoot_main -->|⬅️ Назад| start
    troubleshoot_windows -->|⬅️ Назад| troubleshoot_main
    support_main -.->|✍️ Написать в поддер| support_write[❌ support.write]
    support_main -->|⬅️ Назад| start
    main_menu -->|Установить Easy Ligh| install_main
    main_menu -->|Вопросы и ответы| faq_main
    main_menu -->|Тарифы| tariffs_main
    main_menu -->|Профиль| profile_main
    main_menu -->|Документация| docs_main
    profile_main -->|Мой профиль| profile_my_profile
    profile_main -->|Как подключить VPN н| profile_add_device
    profile_main -->|Перенос тарифа на др| profile_transfer_plan
    profile_main -->|Удаление аккаунта| profile_delete_account
    profile_main -->|Доступное количество| profile_device_limit
    profile_main -->|Реферальная программ| profile_referral
    profile_main -->|В главное меню| main_menu
    profile_my_profile -->|Назад| profile_main
    profile_add_device -->|Назад| profile_main
    profile_transfer_plan -->|Написать в поддержку| faq_support
    profile_transfer_plan -->|Назад| profile_main
    profile_delete_account -->|Написать в поддержку| faq_support
    profile_delete_account -->|Назад| profile_main
    profile_device_limit -->|Назад| profile_main
    profile_referral -->|Не начислились дни п| profile_referral_issue
    profile_referral -->|Назад| profile_main
    profile_referral_issue -->|Написать в тех подде| faq_support
    profile_referral_issue -->|Назад| profile_referral
    docs_main -->|Политика конфиденциа| docs_privacy
    docs_main -->|Пользовательское сог| docs_terms
    docs_main -->|Политика обработки п| docs_personal_data
    docs_main -->|Условия возврата| docs_refund
    docs_main -->|В главное меню| main_menu
    docs_privacy -->|Назад| docs_main
    docs_terms -->|Назад| docs_main
    docs_personal_data -->|Назад| docs_main
    docs_refund -->|Назад| docs_main
    install_main -->|Android| install_android
    install_main -->|iPhone/iPad| install_ios
    install_main -->|AndroidTV| install_androidtv
    install_main -->|AppleTV| install_appletv
    install_main -->|Windows| install_windows
    install_main -->|Mac| install_mac
    install_main -->|В главное меню| main_menu
    install_android -->|Я использую конфиги | install_android_config
    install_android -->|У меня уже/недавно/н| install_android_app
    install_android -->|Что-то не работает| install_android_issues
    install_android -->|Другие устройства| install_main
    install_android_config -->|Что-то не работает| install_android_issues
    install_android_config -->|Другие устройства| install_main
    install_android_app -->|Что-то не работает| install_android_issues
    install_android_app -->|Другие устройства| install_main
    install_android_issues -->|Перейти к разделу "Ч| faq_broken
    install_android_issues -->|Другие устройства| install_main
    install_ios -->|У меня другая програ| install_ios_other
    install_ios -->|Что-то не работает| install_ios_issues
    install_ios -->|Другие устройства| install_main
    install_ios_other -->|Что-то не работает| install_ios_issues
    install_ios_other -->|Другие устройства| install_main
    install_ios_issues -->|Перейти к разделу "Ч| faq_broken
    install_ios_issues -->|Другие устройства| install_main
    install_androidtv -->|Другие устройства| install_main
    install_appletv -->|У меня другая програ| install_appletv_other
    install_appletv -->|Что-то не работает| install_appletv_issues
    install_appletv -->|Другие устройства| install_main
    install_appletv_other -->|Что-то не работает| install_appletv_issues
    install_appletv_other -->|Другие устройства| install_main
    install_appletv_issues -->|Перейти к разделу "Ч| faq_broken
    install_appletv_issues -->|Другие устройства| install_main
    install_windows -->|У меня другая програ| install_windows_other
    install_windows -->|Что-то не работает| install_windows_issues
    install_windows -->|Другие устройства| install_main
    install_windows_other -->|Что-то не работает| install_windows_issues
    install_windows_other -->|Другие устройства| install_main
    install_windows_issues -->|Перейти к разделу "Ч| faq_broken
    install_windows_issues -->|Другие устройства| install_main
    install_mac -->|У меня другая програ| install_mac_other
    install_mac -->|Что-то не работает| install_mac_issues
    install_mac -->|Другие устройства| install_main
    install_mac_other -->|Что-то не работает| install_mac_issues
    install_mac_other -->|Другие устройства| install_main
    install_mac_issues -->|Перейти к разделу "Ч| faq_broken
    install_mac_issues -->|Другие устройства| install_main

    %% Стили
    style tariffs_main fill:#ffedd5,stroke:#f97316
    style tariffs_pricing fill:#ffedd5,stroke:#f97316
    style tariffs_pricing_1month fill:#ffedd5,stroke:#f97316
    style tariffs_pricing_3months fill:#ffedd5,stroke:#f97316
    style tariffs_pricing_6months fill:#ffedd5,stroke:#f97316
    style tariffs_start_plan fill:#ffedd5,stroke:#f97316
    style tariffs_gold fill:#ffedd5,stroke:#f97316
    style tariffs_gold_1month fill:#ffedd5,stroke:#f97316
    style tariffs_gold_3months fill:#ffedd5,stroke:#f97316
    style tariffs_gold_6months fill:#ffedd5,stroke:#f97316
    style tariffs_payment fill:#ffedd5,stroke:#f97316
    style tariffs_pay_process fill:#ffedd5,stroke:#f97316
    style tariffs_pay_failed fill:#ffedd5,stroke:#f97316
    style tariffs_cancel_refund fill:#ffedd5,stroke:#f97316
    style tariffs_unsubscribe fill:#ffedd5,stroke:#f97316
    style tariffs_refund fill:#ffedd5,stroke:#f97316
    style faq_main fill:#dbeafe,stroke:#3b82f6
    style faq_tariffs fill:#dbeafe,stroke:#3b82f6
    style faq_tariffs_q1 fill:#dbeafe,stroke:#3b82f6
    style faq_tariffs_q2 fill:#dbeafe,stroke:#3b82f6
    style faq_tariffs_q3 fill:#dbeafe,stroke:#3b82f6
    style faq_tariffs_q4 fill:#dbeafe,stroke:#3b82f6
    style faq_cancel fill:#dbeafe,stroke:#3b82f6
    style faq_support fill:#dbeafe,stroke:#3b82f6
    style faq_about fill:#dbeafe,stroke:#3b82f6
    style faq_broken fill:#dbeafe,stroke:#3b82f6
    style faq_broken_android fill:#dbeafe,stroke:#3b82f6
    style faq_broken_android_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_android_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_android_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_android_p4 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_iphone fill:#dbeafe,stroke:#3b82f6
    style faq_broken_iphone_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_iphone_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_iphone_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_iphone_p4 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_androidtv fill:#dbeafe,stroke:#3b82f6
    style faq_broken_androidtv_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_androidtv_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_androidtv_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_androidtv_p4 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_appletv fill:#dbeafe,stroke:#3b82f6
    style faq_broken_appletv_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_appletv_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_appletv_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_appletv_p4 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_windows fill:#dbeafe,stroke:#3b82f6
    style faq_broken_windows_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_windows_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_windows_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_windows_p4 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_mac fill:#dbeafe,stroke:#3b82f6
    style faq_broken_mac_p1 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_mac_p2 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_mac_p3 fill:#dbeafe,stroke:#3b82f6
    style faq_broken_mac_p4 fill:#dbeafe,stroke:#3b82f6
    style account_main fill:#f3e8ff,stroke:#a855f7
    style account_status fill:#f3e8ff,stroke:#a855f7
    style account_config fill:#f3e8ff,stroke:#a855f7
    style troubleshoot_main fill:#fee2e2,stroke:#ef4444
    style troubleshoot_windows fill:#fee2e2,stroke:#ef4444
    style support_main fill:#cffafe,stroke:#06b6d4
    style main_menu fill:#dcfce7,stroke:#22c55e
    style docs_main fill:#fef3c7,stroke:#f59e0b
    style docs_privacy fill:#fef3c7,stroke:#f59e0b
    style docs_terms fill:#fef3c7,stroke:#f59e0b
    style docs_personal_data fill:#fef3c7,stroke:#f59e0b
    style docs_refund fill:#fef3c7,stroke:#f59e0b
```

---

📌 **Как открыть:** Скопируйте код диаграммы на [mermaid.live](https://mermaid.live) или откройте в редакторе с поддержкой Mermaid (VS Code, Obsidian, Notion, GitHub)