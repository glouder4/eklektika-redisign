<?php
$APPLICATION->SetTitle("Купить");

$asset->addCss("/local/templates/onlineservice-custom-template/components/catalog/styles/styles.css");

$GLOBALS["OS_BREADCRUMBS"] = [
    [
        'ITEM' => "Купить",
        "LINK" => "/catalog/",
    ]
];

\Bitrix\Main\Loader::includeModule('iblock');

$pageSettings = getPageEditorSettings(124484,60);
if( !$pageSettings )
    return;

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
        <div class="content-description">
            <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/local/templates/onlineservice-custom-template/include/index_catalog.php",
                    "AREA_FILE_SUFFIX" => "",
                    "EDIT_TEMPLATE" => ""
                )
            );?>
        </div>
    </div>

    <div class="container">
        <div class="catalog-section-content">
            <div class="cards-list">
                <div class="cards-list-item--wrapper">
                    <div class="cards-list-item">
                        <div class="cards-list-item--title">
                            <h4 class="title">Хочу стать дилером</h4>
                        </div>
                        <div class="cards-list-item--description">
                            <p>Хотите приобрести продукцию для своих клиентов?<br/>Станьте нашим дилером!</p>
                        </div>
                        <div class="cards-list-item--action">
                            <a href="/personal/profile/registration.php" class="cards-list-item--action_btn">Зарегистрироваться</a>
                            <a href="/personal/profile/" class="cards-list-item--action_btn" style="background-color: #80E0A7;">Войти</a>
                        </div>
                    </div>
                </div>
                <div class="cards-list-item--wrapper">
                    <div class="cards-list-item">
                        <div class="cards-list-item--title">
                            <h4 class="title">Я корпоративный заказчик</h4>
                        </div>
                        <div class="cards-list-item--description">
                            <p>Приобретаете продукцию для&nbsp;своей компании?<br/>Обратитесь к&nbsp;одному из&nbsp;наших дилеров!</p>
                        </div>
                        <div class="cards-list-item--action">
                            <a href="/buy/our-dealers/" class="cards-list-item--action_btn" style="background-color: #80E0A7;">Наши дилеры</a>
                        </div>
                    </div>
                </div>
                <div class="cards-list-item--wrapper">
                    <div class="cards-list-item">
                        <div class="cards-list-item--title">
                            <h4 class="title">  Хочу купить для себя</h4>
                        </div>
                        <div class="cards-list-item--description">
                            <p>Хотите приобрести продукцию Yoliba для&nbsp;личного пользования?<br/>Нет&nbsp;проблем!</p>
                        </div>
                        <div class="cards-list-item--action">
                            <a href="/to-purchase/" class="cards-list-item--action_btn" style="background-color: #80E0A7;">К покупкам</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/footer/banner.php"
        )
    );?>
    <?/*
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
    */?>
</main>