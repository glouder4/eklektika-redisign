<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("") ?>
<?php $APPLICATION->SetTitle("Контакты"); ?>

<?php $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"contacts",
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "41",
		"NEWS_COUNT" => "20",
		"SETTINGS_USE" => "Y",
		"REGIONALITY_USE" => "N",
		"REGIONALITY_PROPERTY" => "REGIONS",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "NAME",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "MAP",
			1 => "CITY",
			2 => "ADDRESS",
			3 => "PHONE",
			4 => "EMAIL",
			5 => "WORK_TIME",
			6 => "",
		),
		"CHECK_DATES" => "Y",
		"MAP_VENDOR" => "google",
		"CONTACT_ID" => "",
		"PROPERTY_MAP" => "MAP",
		"API_KEY_MAP" => "",
		"PROPERTY_STORE_ID" => "STORE_ID",
		"PROPERTY_CITY" => "CITY",
		"PROPERTY_ADDRESS" => "ADDRESS",
		"PROPERTY_PHONE" => "PHONE",
		"PROPERTY_EMAIL" => "EMAIL",
		"PROPERTY_WORK_TIME" => "WORK_TIME",
		"PROPERTY_OPENING_HOURS" => "OPENING_HOURS",
		"WEB_FORM_ID" => "2",
		"WEB_FORM_CONSENT_URL" => "/company/consent/",
		"SHOW_MAP" => "Y",
		"SHOW_FORM" => "Y",
		"SHOW_LIST" => "NONE",
		"TITLE_SHOW" => "Y",
		"TITLE_TEXT" => "",
		"DESCRIPTION_SHOW" => "Y",
		"DESCRIPTION_TEXT" => "",
		"DETAIL_URL" => "/contacts/stores/#ID#/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Контакты",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"FILE_404" => "/404.php",
		"COMPONENT_TEMPLATE" => "contacts",
		"TAB_SCROLL" => "Y"
	),
	false
); ?>

<div class="intec-content">
<div class="intec-content-wrapper">


<h2>Менеджеры по работе с корпоративными клиентами</h2>
<p>Для работы с каждым заказчиком выделяется персональный менеджер, который поможет Вам с выбором, проконсультирует по интересующему вопросу, объяснит систему скидок, рассчитает заказ, согласует с вами стоимость и сроки. </p>
					<table class="intec-ui-markup-table">
						<tbody><tr>
							<td>Максим Рак</td>
							<td><a href="mailto:team@eklektika.ru"><a href="mailto:team@eklektika.ru">team@eklektika.ru</a></a></td>
							<td>доб. 114</td>
						</tr>
						<tr>
							<td>Оксана Капышова</td>
							<td><a href="mailto:team@eklektika.ru"><a href="mailto:team@eklektika.ru">team@eklektika.ru</a></a></td>
							<td>доб. 111</td>
						</tr>
						<tr>
							<td>Юлия Сухорукова</td>
							<td><a href="mailto:team@eklektika.ru"><a href="mailto:team@eklektika.ru">team@eklektika.ru</a></a></td>
							<td>доб. 135</td>
						</tr>
						<tr>
							<td>Татьяна Вавилова</td>
							<td><a href="mailto:team@eklektika.ru"><a href="mailto:team@eklektika.ru">team@eklektika.ru</a></a></td>
							<td>доб. 134</td>
						</tr>
						</tbody>
					</table>


<h2>Офис и склад находятся в шаговой доступности друг от друга (1 автобусная остановка). Проход и проезд на склад осуществляется через проходную предприятия по адресу: Москва, 2-й Вязовский проезд, д.2а</h2>


<div class="accordion">



            <div class="accordion-item">

                <div class="accordion-title">От станции метро «Рязанский проспект»<span></span></div>

                <div class="accordion-content">



                    <div class="con-block">

                        <!--

                        <div class="con-map">

                            <div id="map10" class="map-small"></div>

                        </div>

                        -->

                        <div class="con-txt">

                            <p><strong>От станции метро «Рязанский проспект» (в первом вагоне из центра):</strong></p>

                            <p><strong>Офис: </strong>В сторону центра проехать до остановки «НИЦ Строительство»(3 остановки).От остановки перейти на противоположную сторону Рязанского проспекта по подземному переходу.Офис находится в Бизнесс-центре "Юнион", вход через центральные двери со стороны Рязанского проспекта (под вывеской ПЕНЕТРОН)</p>

							<p></p><p><strong>Склад: </strong>В сторону центра проехать до остановки «Комбинат ЖБК № 2» (4 остановки) на автобусах 143, 143к, 169, 279, 29к и т63 или на маршрутном такси 311к, либо на автобусе-экспресс М7 (2 остановки). От остановки перейти на противоположную сторону Рязанского проспекта по подземному переходу. Склад находятся в стоящем справа 7-ми этажном кирпичном здании за бетонным забором.</p>

                        </div>

                    </div>



                </div>

            </div>

            <!-- end item -->



            <!-- //////////////////////////////////// -->



            <div class="accordion-item">

                <div class="accordion-title">От станции МЦК «Нижегородская»<span></span></div>

                <div class="accordion-content" >



                    <div class="con-block">

                        <!--

                        <div class="con-map">

                            <div id="map2" class="map-small"></div>

                        </div>

                        -->

                        <div class="con-txt">

                            <p><strong>От станции МЦК «Нижегородская»:</strong></p>

                            <p>Перейти на светофоре по пешеходному переходу на противоположную сторону Рязанского проспекта к остановке общественного транспорта «Станция Нижегородская» (перед ТЦ Ашан), оттуда в сторону области проехать до остановки «НИЦ Строительство» (в офис) или «Комбинат ЖБК № 2» (на склад и производство) на автобусах 143, 143к, 279 и т63 или на маршрутном такси 311к, либо на автобусе-экспресс М7 (доезджает только до остановки «Комбинат ЖБК № 2»).</p>

                        </div>

                    </div>



                </div>

            </div>

            <!-- end item -->



            <!-- //////////////////////////////////// -->



            <div class="accordion-item">

                <div class="accordion-title active">Как нас найти<span></span></div>

                <div class="accordion-content" style="display: block;">



                    <div class="con-block">

                          <div class="con-map">

                          <img src="https://eklektika.ru/img/dom.jpeg" alt="офис">

                        </div><div class="con-txt">  <h3>Офис: </h3><p>
 Офис находится в Бизнесс-центре "Юнион", вход через центральные двери со стороны Рязанского проспекта (под вывеской ПЕНЕТРОН). На проходной обратиться в бюро пропусков и выписать пропуск в компанию «Эклектика» (обязательно наличие паспорта). Подняться на 12 этаж, после стеклянных дверей повернуть налево.</p>

 </div>
                        <div class="con-map">

                        <img src="https://eklektika.ru/content/map.png" alt="склад">

                        </div>



                        <div class="con-txt">       <h3>Склад: </h3>     <p>На проходной обратиться в бюро пропусков и выписать пропуск в компанию «Эклектика» (обязательно наличие паспорта или водительского удостоверения). На территории пройти к зданию, стоящему напротив, подняться по пандусу, внутри здания пройти вдоль грузовых лифтов налево к пассажирскому лифту и подняться на 7-ой этаж. В здании ориентируйтесь по указателям с надписью «Эклектика».</p>
             </div>

                    </div>

                </div>



            </div>

            <!-- end item -->



        </div>




            <h2>Наши реквизиты</h2>
            <table class="intec-ui-markup-table">
                <tbody>
                <tr>
                    <td>Наименование</td>
                    <td>ООО «Эклектика»</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">Регистрационная информация</td>
                </tr>
                <tr>
                    <td>Почтовые адреса</td>
                    <td>109428, г. Москва, Рязанский проспект, дом 16, строение 3, помещение I, комната 39, этаж 7</td>
                </tr>
                <tr>
                    <td>Фактический адрес</td>
                    <td>109428, г. Москва, Рязанский проспект, дом 24, корпус 2, этаж 12</td>
                </tr>
                <tr>
                    <td>ИНН участника</td>
                    <td>7704404228</td>
                </tr>
                <tr>
                    <td>КПП участника</td>
                    <td>772101001</td>
                </tr>
                <tr>
                    <td>Банк получателя</td>
                    <td>АО "Альфа-Банк"</td>
                </tr>
                <tr>
                    <td>БИК</td>
                    <td>044525593</td>
                </tr>
                <tr>
                    <td>Корреспондентский счет</td>
                    <td>30101810200000000593</td>
                </tr>
                <tr>
                    <td>Расчетный счет</td>
                    <td>40702810202880003884</td>
                </tr>
                </tbody>
            </table>

</div>
</div><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>