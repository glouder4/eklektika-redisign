    <?php
$APPLICATION->SetTitle("Контакты");
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
        <div class="contacts">
            <div class="contacts--map-section">
                <div class="contacts--map-wrapper">
                    <div class="contacts--map">
                        <script type="text/javascript" charset="utf-8" async 
                                src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A75845e6e7a3f4aa073f4d5d82c13608ceeb491173d115ce59578b1f286e2a610&amp;width=100%25&amp;height=100%&amp;lang=ru_RU&amp;scroll=true"></script>
                    </div>
                </div>
                <div class="contacts--map_data-wrapper">
                    <div class="contacts--map_data--item">
                        <div class="contacts--map_data--item_title">
                            <span>Офис и выдача образцов</span>
                        </div>
                        <div class="contacts--map_data--item_description">
                            <p><strong>Адрес:</strong> 127254, Москва, Огородный проезд, д. 16/1, стр. 4, офис 1005</p>
                            <p><strong>Время работы</strong> 09:30–18:00 (понедельник–пятница)</p>
                            <p><strong>Телефон:</strong> <a href="tel:+74951614100">+7 (495) 161-41-00</a></p>
                            <p><strong>E-mail:</strong> <a href="mailto:office@yomerch.ru">office@yomerch.ru</a>
                        </div>
                    </div>
                    <div class="contacts--map_data--item">
                        <div class="contacts--map_data--item_title">
                            <span>Склад и Производство</span>
                        </div>
                        <div class="contacts--map_data--item_description">
                            <p><strong>Адрес:</strong> 109428, г. Москва, Рязанский проспект, дом 16, стр. 3</p>
                            <p><strong>Время работы</strong> 09:30–18:00 (понедельник–пятница)</p>
                        </div>
                    </div>
                    <!--<div class="contacts--map_data--item">
                        <div class="contacts--map_data--item_title">
                            <span>Телефон склада</span>
                        </div>
                        <div class="contacts--map_data--item_description">
                            <a href="#" class="contacts--map_data--item_description-phone">+7(925)667-33-83</a>
                        </div>
                    </div>-->
                    <!--<div class="contacts--map_data--item">
                        <div class="contacts--map_data--item_title">
                            <span>Оформление заказов</span>
                        </div>
                        <div class="contacts--map_data--item_description">
                            <a href="tel:+&nbsp;7(800)707-52-11" class="contacts--map_data--item_description-phone">+&nbsp;7(800)707-52-11 <small>(по России бесплатно)</small></a>
                            <a href="tel:+&nbsp;7 (495) 129-53-72" class="contacts--map_data--item_description-phone">+&nbsp;7 (495) 129-53-72</a>
                        </div>
                    </div>-->

                    <!-- <div class="contacts--map_data--item">
                        <div class="contacts--map_data--item--additional_info">
                            <a href="mailto:office@yomerch.ru" class="contacts--map_data--item_description-email">office@yomerch.ru</a>
                            <span>Время работы:</span>
                            <span>9:30 - 18:00 (кроме сб.,вс.)</span>
                        </div>
                    </div> -->

                    <?/*
                    <div class="contacts--map_data--item">
                        <div class="contacts--map_data--item--actions">
                            <button type="button" data-action="onlineservice-action.forms.call.open" class="contacts--map_data--item--action">Заказать звонок</button>
                            <a href="#feedback__form--form" class="contacts--map_data--item--action">Отправить сообщение</a>
                        </div>
                    </div>
                    */?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="contacts--accordion-section">
            <div class="contacts--accordion-section-description">
                <!--<p>Офис и&nbsp;склад находятся в&nbsp;шаговой доступности друг от&nbsp;друга (1&nbsp;автобусная остановка). Проход и&nbsp;проезд на&nbsp;склад осуществляется через&nbsp;проходную предприятия по&nbsp;адресу: Москва, 2-й Вязовский проезд,&nbsp;д..&nbsp;2а</p>-->
            </div>
            <!--<div class="contacts--accordion-section-items">

                <div class="contacts--accordion-section-item">
                    <div class="contacts--accordion-section-item-header">
                        Как нас найти
                        <div class="icon-container">
                            <svg class="plus-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="19" stroke="#744A9E" stroke-width="2"/>
                                <path d="M20.4004 15.2001V25.6001" stroke="#744A9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.2012 20.368H25.6012" stroke="#744A9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="19" stroke="#744A9E" stroke-width="2"/>
                                <path d="M15.2012 15.368L25.6012 25.768" stroke="#744A9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M25.6012 15.368L15.2012 25.768" stroke="#744A9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="contacts--accordion-section-item-content">
                        <style>
                            .how_to_find_us{
                                display: flex;
                                flex-direction: column;
                                gap: 30px;
                            }
                            .how_to_find_us--images{
                                display: flex;
                                flex-direction: row;
                                flex-wrap: wrap;

                                row-gap: 20px;
                                column-gap: 13px;
                            }
                            .how_to_find_us--images>img{
                                max-width: 100%;
                            }
                            .how_to_find_us--images>img:nth-child(1){
                                width: 100%;
                            }
                            .how_to_find_us--images>img:nth-child(2),
                            .how_to_find_us--images>img:nth-child(3){
                                width: calc(50% - 7px);
                            }
                            .how_to_find_us--description-item{
                                font-family: Manrope;
                                font-weight: 400;
                                font-size: 16px;
                                line-height: 150%;
                                letter-spacing: 0.29px;
                            }
                            .how_to_find_us--description-item p{
                                margin: 0;
                            }
                            .how_to_find_us--description{
                                display: flex;
                                flex-direction: column;
                                gap: 15px;
                            }
                            @media(min-width: 991px){
                                .how_to_find_us{
                                    flex-direction: row;
                                    justify-content: space-between;
                                }
                            }
                            @media(min-width: 1520px){
                                .how_to_find_us--images{
                                    width: 614px;
                                }
                                .how_to_find_us--description{
                                    width: 772px;
                                }
                                .how_to_find_us--description{
                                    gap: 45px;
                                }
                                .how_to_find_us--description-item{
                                    font-size: 20px;
                                }
                            }
                        </style>
                        <div class="how_to_find_us">
                            <div class="how_to_find_us--images">
                                <img src="/local/templates/onlineservice-custom-template/components/contacts/accordion/assets/question-3-1.png" alt="">
                                <img src="/local/templates/onlineservice-custom-template/components/contacts/accordion/assets/question-3-2.png" alt="">
                                <img src="/local/templates/onlineservice-custom-template/components/contacts/accordion/assets/question-3-3.png" alt="">
                            </div>
                            <div class="how_to_find_us--description">
                                <div class="how_to_find_us--description-item">
                                    <p style="font-weight: bold;">
                                        Офис
                                    </p>
                                    <p>
                                        Офис находится в Бизнесс-центре "Юнион", вход через центральные двери со стороны Рязанского проспекта (под вывеской ПЕНЕТРОН). На проходной обратиться в бюро пропусков и выписать пропуск в компанию «ЙО!каталог» (обязательно наличие паспорта). Подняться на 12 этаж, после стеклянных дверей повернуть налево.
                                    </p>
                                </div>
                                <div class="how_to_find_us--description-item">
                                    <p style="font-weight: bold;">
                                        Склад
                                    </p>
                                    <p>
                                        На проходной обратиться в бюро пропусков и выписать пропуск в компанию «ЙО!каталог» (обязательно наличие паспорта или водительского удостоверения). На территории пройти к зданию, стоящему напротив, подняться по пандусу, внутри здания пройти вдоль грузовых лифтов налево
                                        к пассажирскому лифту и подняться на 7-ой этаж. В здании ориентируйтесь по указателям с надписью «ЙО!каталог».
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
    <div class="container">
        <div class="requisites">
            <div class="requisites--title">
                <span>Наши реквизиты</span>
            </div>
            <div class="requisites--list">
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Наименование</span>
                    <span>Общество с ограниченной ответственностью «ЙOкаталог»</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Сокращенное наименование </span>
                    <span>ООО «ЙOкаталог» </span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Юридический, фактический, почтовый адрес</span>
                    <span>127254, г. Москва, вн. тер. г. муниципальный округ Бутырский, пр-д Огородный, д. 16/1, стр. 4, помещ. 1005</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">ОГРН</span>
                    <span>1257700330865</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">ИНН / КПП</span>
                    <span>9715514096 / 771501001</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Банк получателя</span>
                    <span>АО "Альфа-Банк"</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">БИК</span>
                    <span>044525593</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Корреспондентский счет</span>
                    <span>30101810200000000593</span>
                </div>
                <div class="requisites--list-item">
                    <span class="requisites--list-item_name">Расчетный счет</span>
                    <span>40702810702590008239</span>
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
        "onlineservice-feedback-form-type-3",
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