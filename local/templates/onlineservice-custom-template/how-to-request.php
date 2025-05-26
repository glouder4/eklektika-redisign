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
        <style>
            .how_to_request--item:nth-child(1)::before{
                content: "01";
            }
            .how_to_request--item:nth-child(2)::before{
                content: "02";
            }
            .how_to_request--item:nth-child(3)::before{
                content: "03";
            }
            .how_to_request--item:nth-child(4)::before{
                content: "04";
            }
            .how_to_request--item:nth-child(5)::before{
                content: "05";
            }
            .how_to_request--item:nth-child(6)::before{
                content: "06";
            }
        </style>
        <div class="how_to_request">
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-1.png" alt="Выбор товара">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Выбор товара</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Выберите товар, укажите нужный тираж и&nbsp;сделайте заказ через&nbsp;корзину</p>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-2.png" alt="Звонок менеджера">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Звонок менеджера</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Менеджер свяжется с&nbsp;вами для&nbsp;уточнения необходимых деталей заказа</p>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-2.png" alt="Звонок менеджера">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Согласование итогового макета</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Согласование итогового заказа с&nbsp;нанесением, подписание документов</p>
                </div>
            </div>
            <div class="how_to_request--item additional">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-2.png" alt="Звонок менеджера">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Оплата</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>После&nbsp;согласования заказа менеджер выставляет счёт на&nbsp;оплату</p>

                    <a href="/oplata/" class="how_to_request--item_description--link">Подробнее о способах оплаты</a>
                </div>
            </div>
            <div class="how_to_request--item additional">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-2.png" alt="Звонок менеджера">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Получение заказа</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Изготавливаем ваш&nbsp;заказ и&nbsp;доставляем удобным для&nbsp;вас&nbsp;способом</p>

                    <a href="/dostavka/" class="how_to_request--item_description--link">Подробнее о способах доставки</a>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <img src="/local/templates/onlineservice-custom-template/components/how-to-request/assets/item-2.png" alt="Звонок менеджера">
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Подписание акта приёма заказа</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Проверяете ваш&nbsp;заказ и&nbsp;подписываете акт&nbsp;приёма заказа</p>
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