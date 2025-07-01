<?php
use Bitrix\Main\Context;

$customPages = [
    '/', // главная
    '/help/brands/', // каталог
    '/shares/', // о компании
    '/company/news/', // Новости
    '/help/how-to-request/', // Как заказать
    '/company/contacts/', // Контакты
    '/catalog/', //Купить
    '/dostavka/', // Способы доставки
    '/design-studiya/', // Дизайн студия
    '/our-production/', // Наше производство, служба качества
    '/loyalty-program/', //Программа лояльности
    '/services/', // Услуги
    '/oplata/', // Способы оплаты
    '/company/', //О компании
    '/vidy-naneseniy/', //Виды нанесений
    '/services/s_dtf_pechat/',
    '/services/s_tampopechat/',
    '/services/s_lazernaya-gravirovka/',
    '/services/s_polnocvetnaya-uf-pechat/',
    '/services/s_tisnenie/',
    '/services/s_sublimacionnaya_pechat/',
    '/services/s_shelkografiya/',
    '/services/e_nanesenie-logotipov-na-ezhednevniki/'
];

if (in_array($APPLICATION->GetCurPage(), $customPages)) {

        // Перехватываем отображение страницы
        echo "<div id='panel'>".$APPLICATION->ShowPanel()."</div>";
        $APPLICATION->IncludeComponent(
            'intec.universe:system',
            'basket.manager',
            array(
                'BASKET' => 'Y',
                'COMPARE' => 'Y',
                'COMPARE_NAME' => 'compare',
                'CACHE_TYPE' => 'N'
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        );

        if (
            $properties->get('base-settings-show') == 'all' ||
            $properties->get('base-settings-show') == 'admin' && $USER->IsAdmin()
        ) {
            $APPLICATION->IncludeComponent(
                'intec.universe:system.settings',
                '.default',
                array(
                    'MODE' => 'render',
                    'MENU_ROOT_TYPE' => 'top',
                    'MENU_CHILD_TYPE' => 'left'
                ),
                false,
                array(
                    'HIDE_ICONS' => 'N'
                )
            );
        }
        include($directory.'/onlineservice_addons/header.php');
        $APPLICATION->IncludeComponent(
            "intec.universe:main.header",
            "template.1.custom",
            array(
                "SETTINGS_USE" => "N",
                "REGIONALITY_USE" => "N",
                "CONTACTS_REGIONALITY_USE" => "Y",
                "CONTACTS_REGIONALITY_STRICT" => "N",
                "CONTACTS_IBLOCK_TYPE" => "content",
                "CONTACTS_IBLOCK_ID" => "41",
                "CONTACTS_PROPERTY_PHONE" => "PHONE",
                "CONTACTS_PROPERTY_CITY" => "CITY",
                "CONTACTS_PROPERTY_ADDRESS" => "ADDRESS",
                "CONTACTS_PROPERTY_SCHEDULE" => "WORK_TIME",
                "CONTACTS_PROPERTY_EMAIL" => "EMAIL",
                "CONTACTS_PROPERTY_REGION" => "REGIONS",
                "SEARCH_NUM_CATEGORIES" => "1",
                "SEARCH_TOP_COUNT" => "5",
                "SEARCH_ORDER" => "date",
                "SEARCH_USE_LANGUAGE_GUESS" => "Y",
                "SEARCH_CHECK_DATES" => "N",
                "SEARCH_SHOW_OTHERS" => "N",
                "SEARCH_TIPS_USE" => "Y",
                "SEARCH_MODE" => "catalog",
                "COMPARE_IBLOCK_TYPE" => "1c_catalog",
                "COMPARE_IBLOCK_ID" => "43",
                "COMPARE_CODE" => "compare",
                "MENU_MAIN_ROOT" => "top",
                "MENU_MAIN_CHILD" => "left",
                "MENU_MAIN_LEVEL" => "4",
                "MENU_MAIN_IBLOCK_TYPE" => "1c_catalog",
                "MENU_MAIN_IBLOCK_ID" => "43",
                "MENU_INFO_ROOT" => "info",
                "MENU_INFO_CHILD" => "left",
                "MENU_INFO_LEVEL" => "1",
                "LOGOTYPE" => "/include/logotype.php",
                "PHONES" => array(
                    0 => "+7 (000) 000 00 00",
                    1 => "",
                ),
                "ADDRESS" => "г. Челябинск",
                "EMAIL" => "shop@example.com",
                "TAGLINE" => "Минимальный заказ от<br><span style=\"font-weight:bold;font-size:17px\">15 000 ₽</span>",
                "MENU_MAIN_PROPERTY_IMAGE" => "UF_IMAGE_MENU",
                "MENU_MAIN_PROPERTY_IMAGE_ELEMENTS" => "SVG_FILE",
                "FORMS_CALL_ID" => "1",
                "FORMS_CALL_TEMPLATE" => ".default",
                "MENU_POPUP_FORMS_FEEDBACK_ID" => "2",
                "MENU_POPUP_FORMS_FEEDBACK_TEMPLATE" => ".default",
                "SOCIAL_VK" => "",
                "SOCIAL_INSTAGRAM" => "",
                "SOCIAL_FACEBOOK" => "",
                "SOCIAL_TWITTER" => "",
                "BANNER" => "template.1.custom",
                "BANNER_DISPLAY" => "main",
                "BANNER_IBLOCK_TYPE" => "content",
                "BANNER_IBLOCK_ID" => "1",
                "BANNER_ELEMENTS_COUNT" => "",
                "BANNER_SECTIONS_MODE" => "code",
                "BANNER_LAZYLOAD_USE" => "N",
                "BANNER_BLOCKS_USE" => "Y",
                "BANNER_BLOCKS_IBLOCK_TYPE" => "content",
                "BANNER_BLOCKS_IBLOCK_ID" => "2",
                "BANNER_BLOCKS_MODE" => "N",
                "BANNER_BLOCKS_ELEMENTS_COUNT" => "4",
                "BANNER_BLOCKS_POSITION" => "right",
                "BANNER_BLOCKS_EFFECT_FADE" => "Y",
                "BANNER_BLOCKS_EFFECT_SCALE" => "Y",
                "BANNER_BLOCKS_INDENT" => "N",
                "BANNER_HEIGHT" => "550",
                "BANNER_WIDE" => "N",
                "BANNER_ROUNDED" => "N",
                "BANNER_HEADER_SHOW" => "Y",
                "BANNER_HEADER_VIEW" => "4",
                "BANNER_DESCRIPTION_SHOW" => "Y",
                "BANNER_DESCRIPTION_VIEW" => "1",
                "BANNER_HEADER_OVER_SHOW" => "Y",
                "BANNER_HEADER_OVER_VIEW" => "1",
                "BANNER_BUTTON_VIEW" => "1",
                "BANNER_ORDER_SHOW" => "N",
                "BANNER_ORDER_FORM_ID" => "9",
                "BANNER_ORDER_FORM_TEMPLATE" => ".default",
                "BANNER_ORDER_FORM_TITLE" => "Узнать стоимость",
                "BANNER_ORDER_FORM_CONSENT" => "/company/consent/",
                "BANNER_ORDER_BUTTON" => "Узнать стоимость",
                "BANNER_PICTURE_SHOW" => "Y",
                "BANNER_VIDEO_SHOW" => "Y",
                "BANNER_ADDITIONAL_SHOW" => "Y",
                "BANNER_ADDITIONAL_VIEW" => "3",
                "BANNER_SLIDER_NAV_SHOW" => "N",
                "BANNER_SLIDER_NAV_VIEW" => "1",
                "BANNER_SLIDER_DOTS_SHOW" => "N",
                "BANNER_SLIDER_DOTS_VIEW" => "1",
                "BANNER_SLIDER_DOTS" => "Y",
                "BANNER_SLIDER_LOOP" => "Y",
                "BANNER_SLIDER_SPEED" => "500",
                "BANNER_SLIDER_AUTO_USE" => "Y",
                "BANNER_SORT_BY" => "SORT",
                "BANNER_ORDER_BY" => "ASC",
                "BANNER_PROPERTY_HEADER" => "TITLE",
                "BANNER_PROPERTY_DESCRIPTION" => "DESCRIPTION",
                "BANNER_PROPERTY_HEADER_OVER" => "HEADER_OVER",
                "BANNER_PROPERTY_LINK" => "LINK",
                "BANNER_PROPERTY_LINK_BLANK" => "LINK_BLANK",
                "BANNER_PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
                "BANNER_PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
                "BANNER_PROPERTY_TEXT_POSITION" => "TEXT_POSITION",
                "BANNER_PROPERTY_TEXT_ALIGN" => "TEXT_ALIGN",
                "BANNER_PROPERTY_TEXT_HALF" => "TEXT_HALF",
                "BANNER_PROPERTY_PICTURE" => "PICTURE",
                "BANNER_PROPERTY_PICTURE_ALIGN_VERTICAL" => "PICTURE_ALIGN_VERTICAL",
                "BANNER_PROPERTY_ADDITIONAL" => "ADDITIONAL",
                "BANNER_PROPERTY_SCHEME" => "TEXT_DARK",
                "BANNER_PROPERTY_FADE" => "BACKGROUND_FADE",
                "BANNER_PROPERTY_VIDEO" => "BACKGROUND_VIDEO",
                "BANNER_PROPERTY_VIDEO_FILE_MP4" => "BACKGROUND_VIDEO_FILE_MP4",
                "BANNER_PROPERTY_VIDEO_FILE_WEBM" => "BACKGROUND_VIDEO_FILE_WEBM",
                "BANNER_PROPERTY_VIDEO_FILE_OGV" => "BACKGROUND_VIDEO_FILE_OGV",
                "BANNER_BLOCKS_PROPERTY_LINK" => "LINK",
                "BANNER_BLOCKS_PROPERTY_LINK_BLANK" => "LINK_BLANK",
                "BANNER_PRODUCT_IBLOCK_TYPE" => "catalogs",
                "BANNER_PRODUCT_IBLOCK_ID" => "13",
                "BANNER_PRODUCT_DELAY_USE" => "Y",
                "BANNER_PRODUCT_FORM_ID" => "6",
                "BANNER_PRODUCT_FORM_PROPERTY_PRODUCT" => "form_text_21",
                "BANNER_PRODUCT_FORM_TEMPLATE" => ".default",
                "BANNER_PRODUCT_USE" => "Y",
                "BANNER_PROPERTY_PRODUCT" => "PRODUCT",
                "BANNER_PRODUCT_PROPERTY_ORDER_USE" => "ORDER_USE",
                "BANNER_PRODUCT_PROPERTY_MARKS_HIT" => "RECOMMEND",
                "BANNER_PRODUCT_PROPERTY_MARKS_NEW" => "NEW",
                "BANNER_PRODUCT_PROPERTY_MARKS_RECOMMEND" => "HIT",
                "BANNER_PRODUCT_MARKS_TEMPLATE" => "template.1",
                "BANNER_PRODUCT_ACTION" => "buy",
                "BANNER_PRODUCT_QUANTITY_SHOW" => "Y",
                "BANNER_PRODUCT_QUANTITY_MODE" => "number",
                "BANNER_PRODUCT_MARKS_SHOW" => "Y",
                "BANNER_PRODUCT_PRICE_DIFFERENCE" => "Y",
                "BANNER_PRODUCT_PRICE_CODE" => array(
                ),
                "BANNER_PRODUCT_TIMER_SHOW" => "Y",
                "BANNER_PRODUCT_SECTION_URL" => "",
                "BANNER_PRODUCT_DETAIL_URL" => "",
                "BANNER_PRODUCT_SECTION_ID_VARIABLE" => "SECTION_ID",
                "BANNER_PRODUCT_CHECK_SECTION_ID_VARIABLE" => "N",
                "BANNER_PRODUCT_SHOW_PRICE_COUNT" => "1",
                "BANNER_PRODUCT_BASKET_URL" => "/personal/basket.php",
                "BANNER_PRODUCT_ACTION_VARIABLE" => "action",
                "BANNER_PRODUCT_DISPLAY_COMPARE" => "Y",
                "BANNER_PRODUCT_CONVERT_CURRENCY" => "N",
                "BANNER_PRODUCT_COMPARE_PATH" => "",
                "BANNER_PRODUCT_COMPARE_NAME" => "compare",
                "BANNER_PRODUCT_TIMER_TIME_ZERO_HIDE" => "Y",
                "BANNER_PRODUCT_TIMER_MODE" => "discount",
                "BANNER_PRODUCT_TIMER_ELEMENT_ID_INTRODUCE" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_SECONDS_SHOW" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_QUANTITY_SHOW" => "Y",
                "BANNER_PRODUCT_TIMER_TIMER_QUANTITY_ENTER_VALUE" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_PRODUCT_UNITS_USE" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_QUANTITY_HEADER_SHOW" => "Y",
                "BANNER_PRODUCT_TIMER_TIMER_QUANTITY_HEADER" => "Остаток",
                "BANNER_PRODUCT_TIMER_TIMER_HEADER_SHOW" => "N",
                "BANNER_PRODUCT_TIMER_SETTINGS_USE" => "N",
                "BANNER_PRODUCT_TIMER_LAZYLOAD_USE" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_QUANTITY_OVER" => "Y",
                "BANNER_PRODUCT_TIMER_UNITS_USE" => "N",
                "BANNER_PRODUCT_TIMER_TIMER_HEADER" => "До конца акции",
                "BANNER_PRODUCT_VOTE_SHOW" => "Y",
                "BANNER_PRODUCT_VOTE_MODE" => "rating",
                "LOGOTYPE_SHOW" => "Y",
                "PHONES_SHOW" => "Y",
                "PHONES_SHOW_MOBILE" => "Y",
                "PHONES_ADVANCED_MODE" => "Y",
                "CONTACTS_ADDRESS_SHOW" => "Y",
                "CONTACTS_SCHEDULE_SHOW" => "Y",
                "CONTACTS_EMAIL_SHOW" => "Y",
                "TRANSPARENCY" => "N",
                "LOGOTYPE_SHOW_FIXED" => "Y",
                "LOGOTYPE_SHOW_MOBILE" => "Y",
                "ADDRESS_SHOW" => "Y",
                "ADDRESS_SHOW_MOBILE" => "Y",
                "EMAIL_SHOW" => "Y",
                "EMAIL_SHOW_MOBILE" => "Y",
                "AUTHORIZATION_SHOW" => "Y",
                "AUTHORIZATION_SHOW_FIXED" => "Y",
                "AUTHORIZATION_SHOW_MOBILE" => "N",
                "TAGLINE_SHOW" => "Y",
                "SEARCH_SHOW" => "Y",
                "SEARCH_SHOW_FIXED" => "Y",
                "SEARCH_SHOW_MOBILE" => "Y",
                "BASKET_SHOW" => "Y",
                "BASKET_SHOW_FIXED" => "Y",
                "BASKET_SHOW_MOBILE" => "N",
                "BASKET_POPUP" => "Y",
                "DELAY_SHOW" => "Y",
                "DELAY_SHOW_FIXED" => "Y",
                "DELAY_SHOW_MOBILE" => "N",
                "COMPARE_SHOW" => "N",
                "COMPARE_SHOW_FIXED" => "N",
                "COMPARE_SHOW_MOBILE" => "N",
                "MENU_MAIN_SHOW" => "Y",
                "MENU_MAIN_SHOW_FIXED" => "Y",
                "MENU_MAIN_SHOW_MOBILE" => "Y",
                "MENU_MAIN_DELIMITERS" => "Y",
                "MENU_MAIN_SECTION_VIEW" => "banner",
                "MENU_MAIN_SUBMENU_VIEW" => "simple.1",
                "MENU_MAIN_SECTION_COLUMNS_COUNT" => "3",
                "MENU_MAIN_SECTION_ITEMS_COUNT" => "15",
                "MENU_MAIN_CATALOG_LINKS" => array(
                    0 => "/catalog/",
                    1 => "",
                ),
                "FORMS_CALL_SHOW" => "Y",
                "FORMS_CALL_TITLE" => "Заказать звонок",
                "MENU_POPUP_TEMPLATE" => "1",
                "MENU_POPUP_MODE" => "simple",
                "MENU_POPUP_THEME" => "light",
                "MENU_POPUP_BACKGROUND" => "none",
                "MENU_POPUP_CONTACTS_SHOW" => "Y",
                "MENU_POPUP_FORMS_FEEDBACK_SHOW" => "Y",
                "MENU_POPUP_FORMS_FEEDBACK_TITLE" => "Задать вопрос",
                "MENU_POPUP_SOCIAL_SHOW" => "Y",
                "MENU_POPUP_BASKET_SHOW" => "Y",
                "MENU_POPUP_DELAY_SHOW" => "Y",
                "MENU_POPUP_COMPARE_SHOW" => "Y",
                "MENU_POPUP_AUTHORIZATION_SHOW" => "Y",
                "DESKTOP" => "template.1",
                "PHONES_POSITION" => "bottom",
                "MENU_MAIN_POSITION" => "bottom",
                "MENU_MAIN_TRANSPARENT" => "N",
                "MENU_INFO_SHOW" => "Y",
                "SOCIAL_SHOW" => "Y",
                "SOCIAL_SHOW_MOBILE" => "N",
                "SOCIAL_POSITION" => "left",
                "MOBILE" => "template.1",
                "MOBILE_FIXED" => "N",
                "MOBILE_FILLED" => "N",
                "FIXED" => "",
                "FIXED_MENU_POPUP_SHOW" => "Y",
                "CATALOG_URL" => "/catalog/",
                "LOGIN_URL" => "/personal/profile/",
                "PROFILE_URL" => "/personal/profile/",
                "PASSWORD_URL" => "/personal/profile/?forgot_password=yes",
                "REGISTER_URL" => "/personal/profile/registration.php",
                "SEARCH_URL" => "/search/",
                "BASKET_URL" => "/personal/basket/",
                "COMPARE_URL" => "/catalog/compare.php",
                "CONSENT_URL" => "/company/consent/",
                "ORDER_URL" => "/personal/basket/order.php",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "SEARCH_CATEGORY_0" => array(
                    0 => "no",
                ),
                "SEARCH_PRICE_CODE" => array(
                ),
                "SEARCH_PRICE_VAT_INCLUDE" => "Y",
                "SEARCH_CURRENCY_CONVERT" => "Y",
                "SEARCH_CURRENCY_ID" => "RUB",
                "COMPONENT_TEMPLATE" => "template.1.custom",
                "CONTACTS_ELEMENTS" => array(
                    0 => "",
                    1 => "",
                ),
                "CONTACTS_ELEMENT" => "",
                "CONTACTS_MOBILE_FORM_USE" => "N",
                "CONTACTS_PROPERTY_ICON" => "",
                "SEARCH_CATEGORY_0_TITLE" => "",
                "SEARCH_INPUT_ID" => "",
                "SEARCH_PRODUCTS_SHOW" => "N",
                "SEARCH_TIPS_VIEW" => "list.1",
                "MENU_MAIN_LAZYLOAD_USE" => "N",
                "COMPANY_NAME" => "",
                "BANNER_SECTIONS" => array(
                    0 => "",
                    1 => "",
                ),
                "SOCIAL_YOUTUBE" => "",
                "SOCIAL_ODNOKLASSNIKI" => "",
                "SOCIAL_VIBER" => "",
                "SOCIAL_WHATSAPP" => "",
                "SOCIAL_YANDEX_DZEN" => "",
                "SOCIAL_MAIL_RU" => "",
                "SOCIAL_TELEGRAM" => "",
                "SOCIAL_PINTEREST" => "",
                "SOCIAL_TIKTOK" => "",
                "SOCIAL_SNAPCHAT" => "",
                "SOCIAL_LINKEDIN" => "",
                "MENU_PERSONAL_SECTION" => "personal",
                "SECOND_PHONES_SHOW" => "N",
                "SOCIAL_SQUARE" => "N",
                "SOCIAL_GREY" => "N",
                "AUTHORIZATION_FORM_USE_REGISTRATION" => "N",
                "MENU_MAIN_UPPERCASE" => "N",
                "MENU_MAIN_OVERLAY_USE" => "N",
                "MENU_POPUP_BUTTONS_CLOSE_POSITION" => "left",
                "LOGOTYPE_WIDTH" => "130",
                "MOBILE_SEARCH_TYPE" => "page",
                "MOBILE_HIDDEN" => "N",
                "BANNER_MOBILE_PICTURE_USE" => "Y",
                "BANNER_MOBILE_BLOCK_SEPARATED" => "Y",
                "BANNER_HEADER_H1" => "N",
                "BANNER_BUTTON_SHOW" => "Y",
                "BANNER_BUTTONS_BACK_SHOW" => "N",
                "FORMS_CALCULATE_ID" => "2",
                "FORMS_CALCULATE_TEMPLATE" => ".default",
                "FORMS_CALCULATE_SHOW" => "Y",
                "FORMS_CALCULATE_TITLE" => "Запросить расчет",
                "MENU_MAIN_SECTION_BANNER_SHOW_ICONS_ROOT_ITEMS" => "N",
                "MENU_MAIN_SECTION_BANNER_SHOW" => "Y",
                "MENU_MAIN_SECTION_BANNER_MENU_SYNCHRONIZE" => "Y",
                "MENU_MAIN_SECTION_BANNER_IBLOCK_TYPE" => "",
                "MENU_MAIN_SECTION_BANNER_IBLOCK_ID" => "",
                "BANNER_PROPERTY_MOBILE_PICTURE" => "PICTURE_MOBILE",
                "BANNER_SLIDER_AUTO_TIME" => "5000",
                "BANNER_SLIDER_AUTO_HOVER" => "N"
            ),
            false
        );

        // Кастомные страницы
        switch($APPLICATION->GetCurPage()) {
            case '/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/mainpage.php');
                break;
            case '/help/brands/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/brands.php');
                break;
            case '/shares/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/promotions-and-discounts.php');
                break;
            case '/company/news/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/news-and-articles.php');
                break;
            case '/help/how-to-request/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/how-to-request.php');
                break;
            case '/company/contacts/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/contacts.php');
                break;
            case '/catalog/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/catalog.php');
                break;
            case '/dostavka/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/dostavka.php');
                break;
            case '/design-studiya/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/design-studiya.php');
                break;
            case '/our-production/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/our-production.php');
                break;
            case '/loyalty-program/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/loyalty-program.php');
                break;
            case '/services/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/services.php');
                break;
            case '/oplata/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/oplata.php');
                break;
            case '/company/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/company.php');
                break;
            case '/vidy-naneseniy/':
                include($_SERVER['DOCUMENT_ROOT'].'/local/templates/onlineservice-custom-template/vidy-naneseniy.php');
                break;
        }

        /* Услуги */
        switch($APPLICATION->GetCurPage()) {
            case '/services/s_dtf_pechat/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_dtf_pechat.php');
                break;
            case '/services/s_tampopechat/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_tampopechat.php');
                break;
            case '/services/s_lazernaya-gravirovka/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_lazernaya-gravirovka.php');
                break;
            case '/services/s_polnocvetnaya-uf-pechat/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_polnocvetnaya-uf-pechat.php');
                break;
            case '/services/s_tisnenie/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_tisnenie.php');
                break;
            case '/services/s_sublimacionnaya_pechat/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_sublimacionnaya_pechat.php');
                break;
            case '/services/s_shelkografiya/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/s_shelkografiya.php');
                break;
            case '/services/e_nanesenie-logotipov-na-ezhednevniki/':
                include($_SERVER['DOCUMENT_ROOT'] . '/local/templates/onlineservice-custom-template/services/e_nanesenie-logotipov-na-ezhednevniki.php');
                break;
        }


        $footerPath = $_SERVER['DOCUMENT_ROOT'].'/local/templates/universe_s1/footer.php';
        require_once($footerPath);
        die();

}
?>

