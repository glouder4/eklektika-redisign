<?php
$APPLICATION->SetTitle("Способы доставки товаров");
?>
<div class="container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "onlineservice-breadcrumbs",
        Array(),
        false
    );?>
</div>
<main class="main-content">
    <div class="container">
        <div class="page__title-wrapper">
            <h1 class="page__title-wrapper-title">
                <?$APPLICATION->ShowTitle(false);?>
            </h1>
        </div>
    </div>
    <div class="container">
        <div class="delivery-variants-list">
            <div class="delivery-variants--card">
                <div class="delivery-variants--card_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#FBB040"/>
                        <path d="M33.9 48.7143H47.1M33.9 48.7143C33.9 50.529 32.4225 52 30.6 52C28.7775 52 27.3 50.529 27.3 48.7143M33.9 48.7143C33.9 46.8996 32.4225 45.4286 30.6 45.4286C28.7775 45.4286 27.3 46.8996 27.3 48.7143M47.1 48.7143C47.1 50.529 48.5774 52 50.4 52C52.2226 52 53.7 50.529 53.7 48.7143M47.1 48.7143C47.1 46.8996 48.5774 45.4286 50.4 45.4286C52.2226 45.4286 53.7 46.8996 53.7 48.7143M27.3 48.7143H26.64C25.7159 48.7143 25.2539 48.7143 24.9009 48.5352C24.5905 48.3777 24.338 48.1265 24.1798 47.8173C24 47.4659 24 47.0059 24 46.0857V44.1143C24 42.2741 24 41.354 24.3597 40.6511C24.6761 40.0329 25.1809 39.5302 25.8018 39.2153C26.5077 38.8571 27.4318 38.8571 29.28 38.8571H49.08C50.3063 38.8571 50.9194 38.8571 51.4324 38.938C54.2565 39.3834 56.4715 41.5887 56.9188 44.4006C57 44.9114 57 45.5219 57 46.7429C57 47.0481 57 47.2007 56.9797 47.3284C56.8678 48.0314 56.3141 48.5827 55.6081 48.6941C55.4799 48.7143 55.3266 48.7143 55.02 48.7143H53.7M37.2 29V38.8571M27.3 38.8571L27.847 35.5893C28.2389 33.2483 28.4348 32.0778 29.0214 31.1995C29.5386 30.4252 30.2651 29.8123 31.1172 29.4318C32.0838 29 33.2756 29 35.6592 29H41.2113C42.761 29 43.5358 29 44.2392 29.2127C44.8619 29.401 45.4411 29.7096 45.9438 30.121C46.5118 30.5857 46.9416 31.2277 47.8012 32.5115L52.05 38.8571" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="delivery-variants--card_title">
                    <h3 class="title">Самовывоз собственным транспортом</h3>
                </div>
                <div class="delivery-variants--card_description">
                    <p>Доставка транспортом покупателя</p>
                </div>
                <div class="delivery-variants--card_action">
                    <a data-fancybox href="#dostavka-modal-1" rel="nofollow" class="delivery-variants--card_action--btn">Подробнее</a>
                </div>
            </div>
            <div class="delivery-variants--card">
                <div class="delivery-variants--card_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#EF4A85"/>
                        <path d="M51.1412 48.2475C51.1412 49.2326 50.7499 50.1774 50.0533 50.8739C49.3567 51.5705 48.412 51.9618 47.4269 51.9618C46.4418 51.9618 45.4971 51.5705 44.8005 50.8739C44.1039 50.1774 43.7126 49.2326 43.7126 48.2475C43.7126 47.2624 44.1039 46.3177 44.8005 45.6211C45.4971 44.9245 46.4418 44.5332 47.4269 44.5332C48.412 44.5332 49.3567 44.9245 50.0533 45.6211C50.7499 46.3177 51.1412 47.2624 51.1412 48.2475ZM36.284 48.2475C36.284 49.2326 35.8927 50.1774 35.1962 50.8739C34.4996 51.5705 33.5548 51.9618 32.5698 51.9618C31.5847 51.9618 30.6399 51.5705 29.9434 50.8739C29.2468 50.1774 28.8555 49.2326 28.8555 48.2475C28.8555 47.2624 29.2468 46.3177 29.9434 45.6211C30.6399 44.9245 31.5847 44.5332 32.5698 44.5332C33.5548 44.5332 34.4996 44.9245 35.1962 45.6211C35.8927 46.3177 36.284 47.2624 36.284 48.2475Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M43.716 48.2477H36.2874M25.1445 28.1904H40.0017C42.1025 28.1904 43.1529 28.1904 43.8051 28.8441C44.4588 29.4949 44.4588 30.5453 44.4588 32.6476V45.2763M45.2017 31.9047H47.8774C49.1106 31.9047 49.7272 31.9047 50.2382 32.1945C50.7493 32.4827 51.0658 33.0116 51.7002 34.0694L54.2244 38.274C54.5394 38.8 54.6969 39.0644 54.7786 39.3556C54.8588 39.6483 54.8588 39.9544 54.8588 40.568V44.5334C54.8588 45.9225 54.8588 46.6164 54.5602 47.1334C54.3646 47.4722 54.0833 47.7535 53.7445 47.9491C53.2275 48.2477 52.5337 48.2477 51.1445 48.2477M25.1445 41.5619V44.5334C25.1445 45.9225 25.1445 46.6164 25.4432 47.1334C25.6387 47.4722 25.9201 47.7535 26.2588 47.9491C26.7758 48.2477 27.4697 48.2477 28.8588 48.2477M25.1445 32.6476H34.0588M25.1445 37.1048H31.0874" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="delivery-variants--card_title">
                    <h3 class="title">Транспортом компании<br/>Эклектика</h3>
                </div>
                <div class="delivery-variants--card_description">
                    <p>По Москве и Московской области</p>
                </div>
                <div class="delivery-variants--card_action">
                    <a data-fancybox href="#dostavka-modal-2" rel="nofollow" class="delivery-variants--card_action--btn">Подробнее</a>
                </div>
            </div>
            <div class="delivery-variants--card">
                <div class="delivery-variants--card_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#744A9E"/>
                        <path d="M26.6915 31.0045L29.7476 30.023C29.9531 29.9589 30.1735 29.9611 30.3777 30.0291C30.5818 30.0972 30.7594 30.2277 30.8853 30.4022L32.9823 33.7261L44.493 27.993C45.4007 27.4607 46.4135 27.1331 47.461 27.0329C48.5084 26.9326 49.565 27.0623 50.5571 27.4129C51.5492 27.7634 52.4528 28.3263 53.2047 29.0622C53.9567 29.7982 54.5389 30.6895 54.9107 31.6738C55.0641 32.1708 55.014 32.7083 54.7714 33.1684C54.5289 33.6284 54.1136 33.9734 53.6169 34.1276L46.0546 36.5815L45.2961 36.8269L43.8684 41.7122C43.825 41.881 43.7384 42.0356 43.6172 42.1607C43.4959 42.2859 43.3442 42.3774 43.1769 42.4261L39.7415 43.5415C39.5612 43.6194 39.363 43.6462 39.1685 43.619C38.974 43.5918 38.7907 43.5116 38.6387 43.3873C38.4867 43.2629 38.3718 43.0991 38.3067 42.9139C38.2415 42.7286 38.2285 42.529 38.2692 42.3368L38.983 38.8569L38.4923 39.013L31.1084 41.3999C30.8698 41.4884 30.616 41.5286 30.3617 41.518C30.1075 41.5075 29.8579 41.4464 29.6274 41.3385C29.397 41.2305 29.1903 41.0778 29.0194 40.8892C28.8486 40.7006 28.717 40.4799 28.6323 40.2399L26.0446 32.2092C25.9746 31.9637 26.003 31.7006 26.1238 31.4757C26.2445 31.2508 26.4482 31.0818 26.6915 31.0045V31.0045Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M55 52.6875H26" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="delivery-variants--card_title">
                    <h3 class="title">Транспортом<br/>компаний-партнеров </h3>
                </div>
                <div class="delivery-variants--card_description">
                    <p>Для регионов России</p>
                </div>
                <div class="delivery-variants--card_action">
                    <a data-fancybox href="#dostavka-modal-3" rel="nofollow" class="delivery-variants--card_action--btn">Подробнее</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="alert-section">
            <div class="alert-card">
                <p>Уважаемые клиенты! Обращаем ваше внимание, что&nbsp;отгрузка товара осуществляется строго при&nbsp;предъявлении оригинала доверенности на&nbsp;получателя груза или&nbsp;при&nbsp;наличии печати при&nbsp;получении(для&nbsp;отметки на&nbsp;накладной). При&nbsp;заказе услуг Яндекс-доставки или&nbsp;иных подрядчиков, оригинал доверенности так&nbsp;же&nbsp;требуется на&nbsp;курьера, забирающего груз.</p>
            </div>
            <div class="alert-additional-list">
                <ul>
                    <li>
                        Услуги по сборке и погрузке товара при любом способе доставки осуществляет компания-поставщик (Компания Эклектика) бесплатно
                    </li>
                    <li>
                        Разгрузка заказов при любом способе доставки осуществляется силами покупателя
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "onlineservice-feedback-form-type-2",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "CONSENT_URL" => "",
            "EDIT_URL" => "result_edit.php",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "result_list.php",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "RESULT_ID" => "RESULT_ID",
                "WEB_FORM_ID" => "WEB_FORM_ID"
            ),
            "WEB_FORM_ID" => "2"
        )
    );?>
</main>