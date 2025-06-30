<?php
$APPLICATION->SetTitle("Купить");
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
            <p>Чтобы приобрести нашу продукцию на максимально выгодных для вас условиях, пройдите регистрацию ниже:</p>
        </div>
    </div>

    <div class="container">
        <div class="catalog-section-content">
            <div class="cards-list">
                <div class="cards-list-item">
                    <div class="cards-list-item--title">
                        <h4 class="title">Я рекламное агентство</h4>
                    </div>
                    <div class="cards-list-item--description">
                        <p>Хотите приобрести продукцию<br/>
                            Для своих клиентов? Вам достаточно<br/>
                            стать нашим дилером.</p>
                    </div>
                    <div class="cards-list-item--action">
                        <a href="#" class="cards-list-item--action_btn">Стать дилером</a>
                    </div>
                </div>
                <div class="cards-list-item">
                    <div class="cards-list-item--title">
                        <h4 class="title">Я корпоративный заказчик</h4>
                    </div>
                    <div class="cards-list-item--description">
                        <p>Приобретаете продукцию<br/>для&nbsp;своей компании? Обратитесь<br/>к&nbsp;одному из&nbsp;наших менеджеров</p>
                    </div>
                    <div class="cards-list-item--action">
                        <a href="#" class="cards-list-item--action_btn">Зарегистрироваться</a>
                    </div>
                </div>
                <div class="cards-list-item">
                    <div class="cards-list-item--title">
                        <h4 class="title">Хочу купить для себя</h4>
                    </div>
                    <div class="cards-list-item--description">
                        <p>Хотите приобрести продукцию Yoliba<br/>для&nbsp;личного пользования? Нет&nbsp;проблем!</p>
                    </div>
                    <div class="cards-list-item--action">
                        <a href="#" class="cards-list-item--action_btn">Перейти к покупкам</a>
                    </div>
                </div>
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