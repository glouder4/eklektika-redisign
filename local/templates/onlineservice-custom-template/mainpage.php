<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Page\Asset;

// Подключаем стили и скрипты кастомного шаблона
$asset = Asset::getInstance();
$asset->addCss("/local/templates/onlineservice-custom-template/styles/template.css");
$asset->addCss("/local/templates/onlineservice-custom-template/styles/header.css");
$asset->addCss("/local/templates/onlineservice-custom-template/styles/footer.css");

$asset->addJs("/local/templates/onlineservice-custom-template/scripts/jquery.min.js", false);
$asset->addJs("/local/templates/onlineservice-custom-template/scripts/template.js", true);
$asset->addJs("/local/templates/onlineservice-custom-template/scripts/header.js", true);

$asset->addCss("/local/templates/onlineservice-custom-template/components/mainpage/slider/styles/owl.carousel.min.css");

$asset->addCss("/local/templates/onlineservice-custom-template/components/mainpage/categories-slider/styles/styles.css");
$asset->addCss("/local/templates/onlineservice-custom-template/components/mainpage/free-services/styles/styles.css");
$asset->addCss("/local/templates/onlineservice-custom-template/components/mainpage/our-services/styles/styles.css");
//$asset->addCss("/local/templates/onlineservice-custom-template/components/mainpage/slider/styles/styles.css");
$asset->addJs("/local/templates/onlineservice-custom-template/components/mainpage/slider/scripts/owl.carousel.min.js", true);
$asset->addJs("/local/templates/onlineservice-custom-template/components/mainpage/categories-slider/scripts/scripts.js", true);
?>

<main class="main-content">
    <?$APPLICATION->IncludeComponent(
        "intec.universe:main.slider",
        "onlineservice-template.1.custom",
        Array(
            "ADDITIONAL_SHOW" => "N",
            "BUTTONS_BACK_SHOW" => "N",
            "BUTTON_SHOW" => "Y",
            "BUTTON_VIEW" => "1",
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "DESCRIPTION_SHOW" => "Y",
            "DESCRIPTION_VIEW" => "1",
            "ELEMENTS_COUNT" => "5",
            "HEADER_H1" => "N",
            "HEADER_OVER_SHOW" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_VIEW" => "1",
            "HEIGHT" => "600",
            "IBLOCK_ID" => "1",
            "IBLOCK_TYPE" => "content",
            "LAZYLOAD_USE" => "N",
            "MOBILE_BLOCK_SEPARATED" => "N",
            "MOBILE_PICTURE_USE" => "Y",
            "ORDER_BY" => "ASC",
            "ORDER_SHOW" => "N",
            "PICTURE_SHOW" => "N",
            "PRODUCT_USE" => "N",
            "PROPERTY_ADDITIONAL" => "ADDITIONAL",
            "PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
            "PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
            "PROPERTY_DESCRIPTION" => "DESCRIPTION",
            "PROPERTY_FADE" => "BACKGROUND_FADE",
            "PROPERTY_HEADER" => "TITLE",
            "PROPERTY_HEADER_OVER" => "HEADER_OVER",
            "PROPERTY_LINK" => "LINK",
            "PROPERTY_LINK_BLANK" => "LINK_BLANK",
            "PROPERTY_MOBILE_PICTURE" => "PICTURE_MOBILE",
            "PROPERTY_PICTURE" => "PICTURE",
            "PROPERTY_PICTURE_ALIGN_VERTICAL" => "PICTURE_ALIGN_VERTICAL",
            "PROPERTY_SCHEME" => "TEXT_DARK",
            "PROPERTY_TEXT_ALIGN" => "TEXT_ALIGN",
            "PROPERTY_TEXT_HALF" => "TEXT_HALF",
            "PROPERTY_TEXT_POSITION" => "TEXT_POSITION",
            "PROPERTY_VIDEO" => "BACKGROUND_VIDEO",
            "PROPERTY_VIDEO_FILE_MP4" => "BACKGROUND_VIDEO_FILE_MP4",
            "PROPERTY_VIDEO_FILE_OGV" => "BACKGROUND_VIDEO_FILE_OGV",
            "PROPERTY_VIDEO_FILE_WEBM" => "BACKGROUND_VIDEO_FILE_WEBM",
            "SECTIONS" => array("", ""),
            "SECTIONS_MODE" => "id",
            "SLIDER_AUTO_HOVER" => "Y",
            "SLIDER_AUTO_TIME" => "3000",
            "SLIDER_AUTO_USE" => "Y",
            "SLIDER_DOTS_SHOW" => "Y",
            "SLIDER_DOTS_VIEW" => "1",
            "SLIDER_LOOP" => "Y",
            "SLIDER_NAV_SHOW" => "Y",
            "SLIDER_NAV_VIEW" => "1",
            "SORT_BY" => "SORT",
            "VIDEO_SHOW" => "N"
        )
    );?>

    <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"onlineservice-undersliderpromo.news.list",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "22",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "4",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ID",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "onlineservice-undersliderpromo.news.list",
		"LAZYLOAD_USE" => "N",
		"PROPERTY_TAGS" => "",
		"LINK_BLANK" => "N",
		"DELIMITER_SHOW" => "N",
		"IMAGE_SHOW" => "N",
		"PREVIEW_SHOW" => "N",
		"DATE_SHOW" => "N"
	),
	false
);?>

    <div class="container">
        <div class="categories-slider--container-title">
            <span class="title">Категории товаров</span>
        </div>
        <div class="categories-slider owl-carousel owl-theme" id="categoriesSlider">
            <?php
                $arFilter = array(
                    'IBLOCK_ID' => 43,
                    'ACTIVE' => 'Y',
                    'GLOBAL_ACTIVE' => 'Y'
                );
                $arSelect = array(
                    'ID',
                    'NAME',
                    'PICTURE',
                    'DETAIL_PICTURE',
                    'SECTION_PAGE_URL'
                );
                $rsSections = CIBlockSection::GetList(
                    array('SORT' => 'ASC'),
                    $arFilter,
                    false,
                    $arSelect
                );
                $counter = 0;
                $keyCounter = 1;
                $columnCounter = 0;
                while ($arSection = $rsSections->GetNext()) {
                    if ($counter % 3 == 0) {
                        if ($counter > 0) echo '</div>';
                        echo '<div class="categories-slider--item">';
                        $columnCounter++;
                    }
                    $sPicture = $arSection['PICTURE'];
                    if (empty($sPicture))
                        $sPicture = $arSection['DETAIL_PICTURE'];
                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 350,
                            'height' => 350
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
                        if (!empty($sPicture['src']))
                            $sPicture = $sPicture['src'];
                    }
                    $isEmptyPicture = false;
                    if (empty($sPicture)){
                        $isEmptyPicture = true;
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                    }
                    ?>
                    <?php
                        if ($isEmptyPicture){
                    ?>
                        <style>
                            #categoriesSlider .owl-item:nth-child(<?=$columnCounter;?>) .categories-slider--item_category:nth-of-type(<?=$keyCounter;?>)::before{
                                content: "<?= str_replace('"', '\\"', html_entity_decode($arSection['NAME'])) ?>";
                            }
                            <?php
                                if( strlen($arSection['NAME'])/2 > 11 ){ ?>
                                    #categoriesSlider .owl-item:nth-child(<?=$columnCounter;?>) .categories-slider--item_category:nth-of-type(<?=$keyCounter;?>)::before{
                                        font-size: 16px;
                                        text-align: center;
                                    }
                                    @media(min-width: 1520px){
                                        #categoriesSlider .owl-item:nth-child(<?=$columnCounter;?>) .categories-slider--item_category:nth-of-type(<?=$keyCounter;?>)::before{
                                            font-size: 26px;
                                        }
                                    }
                                <?php }
                            ?>
                        </style>
                    <?php
                        }
                    ?>
                    <a href="<?= $arSection['SECTION_PAGE_URL'] ?>"
                       class="categories-slider--item_category <?=($isEmptyPicture) ? "transition-style-alternative-1" : null;?>"
                       style="background-image: url('<?= $sPicture ?>')"
                    >
                        <div class="categories-slider--item_category-image">
                            <img src="<?= $sPicture ?>" alt="<?= $arSection['NAME'] ?>">
                        </div>
                        <div class="categories-slider--item_category-title">
                            <span><?= $arSection['NAME'] ?></span>
                        </div>
                    </a>
                    <?php
                    $counter++;
                    $keyCounter++;
                    if($keyCounter == 4)
                        $keyCounter = 1;
                }
                if ($counter > 0) {
                    echo '</div>';
                }
            ?>
        </div>
    </div>

    <div class="container">
        <span class="free-services--title">Бесплатные сервисы</span>

        <div class="free-services--list">
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="41" viewBox="0 0 43 41" fill="none">
                        <path d="M14.6585 9.7561V0M34.1707 9.7561V0M41.9756 31.2195V40H6.85366V34.1463M41.7083 14.6341H6.56488M1 33.6585V34.1463H35.9268L36.2195 33.6585L36.6761 32.7005C40.1652 25.3672 41.9756 17.3483 41.9756 9.22732V4.87805H6.85366V9.03024C6.85374 17.214 5.0153 25.2933 1.47415 32.6712L1 33.6585Z" stroke="#57B0EA" stroke-width="2"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Возможна постоплата<br/>до 30 рабочих дней</p>
                </div>
            </div>
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37" height="40" viewBox="0 0 37 40" fill="none">
                        <path d="M34.1389 21.0851H30.5764V18.9077C30.5764 16.2248 28.4648 14.0321 25.8327 13.9336V9.09523C25.8327 7.68357 24.6962 6.53506 23.299 6.53506H18.7557V5.89972C18.7557 2.64659 16.1362 0 12.9164 0C9.69655 0 7.07709 2.64659 7.07709 5.89972V6.53514H2.53376C1.13665 6.53514 0 7.68357 0 9.09523V37.44C0 38.8516 1.13665 40 2.53376 40H15.7209C15.924 40 16.1102 39.9271 16.2552 39.8058C16.5314 39.9302 16.8368 40 17.1583 40H34.1389C35.3656 40 36.3636 38.9916 36.3636 37.7521V23.3331C36.3636 22.0935 35.3656 21.0851 34.1389 21.0851ZM28.8991 18.9077V21.0851L22.3981 21.0852V18.9077C22.3981 17.0967 23.8562 15.6236 25.6486 15.6236C27.4409 15.6236 28.8991 17.0967 28.8991 18.9077ZM8.75439 5.89972C8.75439 3.58109 10.6215 1.69467 12.9164 1.69467C15.2113 1.69467 17.0784 3.58109 17.0784 5.89972V6.53506L8.75439 6.53514V5.89972ZM2.53376 8.22981H7.07717V9.1221C5.63014 9.49937 4.55802 10.8293 4.55802 12.4075C4.55802 14.2782 6.06432 15.8 7.91582 15.8C9.76732 15.8 11.2737 14.2782 11.2737 12.4075C11.2737 10.8293 10.2016 9.49937 8.75447 9.1221V8.22981L17.0784 8.22973V9.12234C15.6319 9.50001 14.5602 10.8296 14.5602 12.4075C14.5602 14.2782 16.0665 15.8 17.918 15.8C19.7695 15.8 21.2758 14.2782 21.2758 12.4075C21.2758 10.829 20.2032 9.49888 18.7557 9.12186V8.22973H23.2991C23.7714 8.22973 24.1556 8.61798 24.1556 9.09523V14.1632C22.1662 14.8038 20.7209 16.6881 20.7209 18.9077V21.0852H17.1584C15.9316 21.0852 14.9336 22.0935 14.9336 23.333V31.9767H1.6773V9.09523C1.6773 8.61806 2.06148 8.22981 2.53376 8.22981ZM7.91582 12.8903C8.37899 12.8903 8.75447 12.511 8.75447 12.043V10.9384C9.25686 11.2323 9.5964 11.78 9.5964 12.4074C9.5964 13.3436 8.84249 14.1052 7.91582 14.1052C6.98915 14.1052 6.23533 13.3436 6.23533 12.4074C6.23533 11.78 6.57486 11.2322 7.07717 10.9384V12.043C7.07717 12.511 7.45265 12.8903 7.91582 12.8903ZM17.917 12.8902C18.3802 12.8902 18.7557 12.5109 18.7557 12.0429V10.9379C19.2584 11.2317 19.5984 11.7798 19.5984 12.4075C19.5984 13.3437 18.8445 14.1053 17.9179 14.1053C16.9912 14.1053 16.2374 13.3437 16.2374 12.4075C16.2374 11.7804 16.5765 11.2329 17.0784 10.939V12.043C17.0784 12.5109 17.4538 12.8902 17.917 12.8902ZM1.6773 37.44V33.6714H14.9335V37.7521C14.9335 37.9429 14.9573 38.1282 15.0019 38.3053H2.53376C2.06148 38.3053 1.6773 37.9171 1.6773 37.44ZM34.6863 37.7521C34.6863 38.0571 34.4407 38.3053 34.1389 38.3053H17.1583C16.8564 38.3053 16.6108 38.0571 16.6108 37.7521V23.3331C16.6108 23.0281 16.8564 22.78 17.1583 22.78H20.7208V24.65C20.7208 25.118 21.0963 25.4973 21.5595 25.4973C22.0226 25.4973 22.3981 25.118 22.3981 24.65V22.78L28.8991 22.7799V24.65C28.8991 25.118 29.2746 25.4973 29.7377 25.4973C30.2009 25.4973 30.5764 25.118 30.5764 24.65V22.7799H34.1389C34.4408 22.7799 34.6863 23.028 34.6863 23.3331V37.7521Z" fill="#FF9900"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Бесплатное предоставление образцов сроком до&nbsp;1&nbsp;месяца</p>
                </div>
            </div>
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="40" viewBox="0 0 41 40" fill="none">
                        <path d="M3.0303 2.99859V26.9873H37.4142V2.99859H3.0303ZM38.4243 0C39.54 0 40.4445 0.89501 40.4445 1.99906V27.9869C40.4445 29.0909 39.54 29.9859 38.4243 29.9859L21.4667 29.9844V37L29.1053 37.0014C29.9421 37.0014 30.6204 37.6727 30.6204 38.5007C30.6204 39.3287 29.9421 40 29.1053 40H11.2916C10.4548 40 9.77643 39.3287 9.77643 38.5007C9.77643 37.6727 10.4548 37.0014 11.2916 37.0014L18.4378 37V29.9844L2.0202 29.9859C0.904475 29.9859 0 29.0909 0 27.9869V1.99906C0 0.89501 0.904475 0 2.0202 0H38.4243Z" fill="#4BD783"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Бесплатная подготовка технического макета<br/>
                        <small>(при наличии логотипа в векторе)</small></p>
                </div>
            </div>
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="40" viewBox="0 0 41 40" fill="none">
                        <path d="M39.3451 8.091L20.2288 0L0 8.56137V18.1375L1.61419 18.8209V32.2723L19.8725 40L38.4705 32.1285V18.663L40.0847 17.9802V8.40401L39.3451 8.091ZM18.8293 36.9267L4.03783 30.6664V19.2281L18.8293 25.4884V36.9267ZM18.8293 23.5145L4.03783 17.2536V17.2151L2.42364 16.5317V11.2679L18.8294 18.2122V23.5145H18.8293ZM19.7382 16.6229L3.45558 9.73064L20.2288 2.63189L36.5107 9.52349L19.7382 16.6229ZM36.0469 30.5226L20.6471 37.0403V25.4884L36.0469 18.9695V30.5226ZM37.6611 16.3128L20.6471 23.5145V18.2122L37.6611 11.0111V16.3128Z" fill="#D74B4B"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Бесплатное хранение заказа на складе<br/>
                        до 5 дней</p>
                </div>
            </div>
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="33" height="41" viewBox="0 0 33 41" fill="none">
                        <path d="M8.3999 9.80005H22.7999V21.8" stroke="#744A9E" stroke-width="2"/>
                        <path d="M14.4 35H26.4M32.8 35H26.4M8.80005 22.2H26.4V35" stroke="#744A9E" stroke-width="2"/>
                        <path d="M0 1H3.8C6.56142 1 8.8 3.23858 8.8 6V29" stroke="#744A9E" stroke-width="2"/>
                        <circle cx="8.80005" cy="35" r="5" stroke="#744A9E" stroke-width="2"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Бесплатная комплектация заказа&nbsp;/&nbsp;переупаковка<br/>
                        в рамках задачи брендирования</p>
                </div>
            </div>
            <div class="free-services--list_item">
                <div class="free-services--list_item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="41" height="42" viewBox="0 0 41 42" fill="none">
                        <path d="M1.3231 38.7195L5.31769 10.5244C5.45743 9.53805 6.30173 8.80493 7.29791 8.80493H32.766C33.7389 8.80493 34.5707 9.50505 34.7367 10.4637L39.619 38.6588C39.8307 39.8816 38.8893 41.0001 37.6483 41.0001H3.30333C2.08716 41.0001 1.1525 39.9236 1.3231 38.7195Z" stroke="#EF4A85" stroke-width="2"/>
                        <path d="M15.1438 12.7073V5.87805C15.1438 3.18398 17.3278 1 20.0218 1V1C22.7159 1 24.8999 3.18398 24.8999 5.87805V12.7073" stroke="#EF4A85" stroke-width="2"/>
                        <circle cx="15.1453" cy="14.1705" r="1.43902" stroke="#EF4A85" stroke-width="2"/>
                        <circle cx="24.9014" cy="14.1705" r="1.43902" stroke="#EF4A85" stroke-width="2"/>
                        <path d="M16.4502 29.5093H23.6047" stroke="#EF4A85" stroke-width="2"/>
                        <path d="M14.6626 33.6829L18.9051 23.7839C19.3283 22.7962 20.7286 22.7962 21.1519 23.7839L25.3943 33.6829" stroke="#EF4A85" stroke-width="2"/>
                    </svg>
                </div>
                <div class="free-services--list_item--title">
                    <p>Бесплатная&nbsp;подготовка макета-привязки <small>(при&nbsp;заказе&nbsp;от&nbsp;100&nbsp;000&nbsp;Р)</small></p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="our-services">
            <span class="our-services--title">Наши услуги</span>
        </div>
        <div class="our-services--list">
            <div class="our-services--list-item">
                <div class="our-services--list-item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#FBB040"/>
                        <path d="M30.0013 18.8574C23.8471 18.8574 18.8584 23.8461 18.8584 30.0003C18.8584 36.1545 23.8471 41.1431 30.0013 41.1431C36.1555 41.1431 41.1441 36.1545 41.1441 30.0003C41.1441 23.8461 36.1555 18.8574 30.0013 18.8574Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.5439 40.2159V32.2287L30.0011 24.4287L34.4582 32.2287V40.2159" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M31.7176 27.4289H28.2891L30.0033 24.5718L31.7176 27.4289Z" fill="white"/>
                        <path d="M25.5439 32.2288C25.5439 32.2288 26.7997 33.343 27.7725 33.343C28.7453 33.343 30.0011 32.2288 30.0011 32.2288C30.0011 32.2288 31.2569 33.343 32.2297 33.343C33.2024 33.343 34.4582 32.2288 34.4582 32.2288" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="our-services--list-item--title"><span>Дизайн-студия</span></div>
                <div class="our-services--list-item--description">
                    <p>Мы&nbsp;предлагаем комплексные услуги по&nbsp;разработке дизайна подарочных коллекций и&nbsp;упаковки. Помогаем брендам выделиться и&nbsp;подчеркнуть их&nbsp;индивидуальность.</p>
                </div>
                <div class="our-services--list-item--action">
                    <a href="#" class="our-services--list-item--action_btn">Подробнее</a>
                </div>
            </div>
            <div class="our-services--list-item">
                <div class="our-services--list-item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#EF4A85"/>
                        <path d="M18.8584 23.3146C18.8584 22.1325 19.328 20.9988 20.1639 20.1629C20.9997 19.327 22.1334 18.8574 23.3155 18.8574C24.4976 18.8574 25.6313 19.327 26.4672 20.1629C27.3031 20.9988 27.7727 22.1325 27.7727 23.3146V36.686C27.7727 37.8681 27.3031 39.0018 26.4672 39.8377C25.6313 40.6735 24.4976 41.1431 23.3155 41.1431C22.1334 41.1431 20.9997 40.6735 20.1639 39.8377C19.328 39.0018 18.8584 37.8681 18.8584 36.686V23.3146Z" stroke="white" stroke-width="2"/>
                        <path d="M27.7733 25.8142L31.4649 22.1214C32.3008 21.2855 33.4346 20.8159 34.6167 20.8159C35.7988 20.8159 36.9326 21.2855 37.7685 22.1214C38.6044 22.9573 39.074 24.091 39.074 25.2732C39.074 26.4553 38.6044 27.589 37.7685 28.4249L27 39.1934" stroke="white" stroke-width="2"/>
                        <path d="M23.3125 41.143H36.684C37.8661 41.143 38.9998 40.6735 39.8356 39.8376C40.6715 39.0017 41.1411 37.868 41.1411 36.6859C41.1411 35.5038 40.6715 34.3701 39.8356 33.5342C38.9998 32.6984 37.8661 32.2288 36.684 32.2288H33.8982M24.4268 36.6859C24.4268 36.9814 24.3094 37.2649 24.1004 37.4738C23.8915 37.6828 23.6081 37.8002 23.3125 37.8002C23.017 37.8002 22.7336 37.6828 22.5246 37.4738C22.3156 37.2649 22.1982 36.9814 22.1982 36.6859C22.1982 36.3904 22.3156 36.107 22.5246 35.898C22.7336 35.689 23.017 35.5716 23.3125 35.5716C23.6081 35.5716 23.8915 35.689 24.1004 35.898C24.3094 36.107 24.4268 36.3904 24.4268 36.6859Z" stroke="white" stroke-width="2"/>
                    </svg>
                </div>
                <div class="our-services--list-item--title"><span>Все виды нанесения</span></div>
                <div class="our-services--list-item--description">
                    <p>Широкий выбор технологий нанесения изображений и&nbsp;логотипов на&nbsp;сувенирную продукцию. Мы&nbsp;подбираем оптимальный метод в&nbsp;зависимости от&nbsp;материала, тиража и&nbsp;желаемого эффекта.</p>
                </div>
                <div class="our-services--list-item--action">
                    <a href="#" class="our-services--list-item--action_btn">Подробнее</a>
                </div>
            </div>
            <div class="our-services--list-item">
                <div class="our-services--list-item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#744A9E"/>
                        <path d="M38.3559 36.1856C38.3559 36.9245 38.0624 37.633 37.54 38.1554C37.0175 38.6779 36.309 38.9714 35.5702 38.9714C34.8314 38.9714 34.1228 38.6779 33.6004 38.1554C33.078 37.633 32.7845 36.9245 32.7845 36.1856C32.7845 35.4468 33.078 34.7383 33.6004 34.2158C34.1228 33.6934 34.8314 33.3999 35.5702 33.3999C36.309 33.3999 37.0175 33.6934 37.54 34.2158C38.0624 34.7383 38.3559 35.4468 38.3559 36.1856ZM27.213 36.1856C27.213 36.9245 26.9195 37.633 26.3971 38.1554C25.8747 38.6779 25.1661 38.9714 24.4273 38.9714C23.6885 38.9714 22.9799 38.6779 22.4575 38.1554C21.9351 37.633 21.6416 36.9245 21.6416 36.1856C21.6416 35.4468 21.9351 34.7383 22.4575 34.2158C22.9799 33.6934 23.6885 33.3999 24.4273 33.3999C25.1661 33.3999 25.8747 33.6934 26.3971 34.2158C26.9195 34.7383 27.213 35.4468 27.213 36.1856Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M32.787 36.186H27.2155M18.8584 21.1431H30.0013C31.5769 21.1431 32.3647 21.1431 32.8538 21.6334C33.3441 22.1214 33.3441 22.9092 33.3441 24.4859V33.9574M33.9013 23.9288H35.9081C36.8329 23.9288 37.2954 23.9288 37.6787 24.1461C38.062 24.3623 38.2993 24.7589 38.7751 25.5523L40.6683 28.7058C40.9045 29.1002 41.0227 29.2986 41.0839 29.517C41.1441 29.7365 41.1441 29.966 41.1441 30.4262V33.4003C41.1441 34.4422 41.1441 34.9625 40.9201 35.3503C40.7734 35.6044 40.5625 35.8154 40.3084 35.9621C39.9206 36.186 39.4003 36.186 38.3584 36.186M18.8584 31.1717V33.4003C18.8584 34.4422 18.8584 34.9625 19.0824 35.3503C19.2291 35.6044 19.44 35.8154 19.6941 35.9621C20.0819 36.186 20.6023 36.186 21.6441 36.186M18.8584 24.4859H25.5441M18.8584 27.8288H23.3155" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="our-services--list-item--title"><span>Доставка</span></div>
                <div class="our-services--list-item--description">
                    <p>Обеспечиваем удобство и&nbsp;своевременность получения заказов. У&nbsp;нас&nbsp;возможен самовывоз, доставка нашим транспортом и&nbsp;транспортными компаниями партнёров на&nbsp;ваш&nbsp;выбор.</p>
                </div>
                <div class="our-services--list-item--action">
                    <a href="#" class="our-services--list-item--action_btn">Подробнее</a>
                </div>
            </div>
            <div class="our-services--list-item">
                <div class="our-services--list-item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#4BD783"/>
                        <path d="M26.2881 27.5235L30.3218 30.5494C30.5717 30.7368 30.8832 30.8228 31.1939 30.7902C31.5045 30.7576 31.7914 30.6088 31.9969 30.3736L39.9071 21.333" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M41.1441 30.0001C41.1442 32.3283 40.4149 34.5981 39.0587 36.4907C37.7026 38.3832 35.7877 39.8034 33.583 40.5518C31.3783 41.3002 28.9945 41.3392 26.7665 40.6633C24.5385 39.9874 22.5782 38.6306 21.1609 36.7834C19.7435 34.9362 18.9404 32.6915 18.8644 30.3644C18.7883 28.0374 19.443 25.745 20.7366 23.8092C22.0302 21.8734 23.8977 20.3914 26.0768 19.5714C28.2559 18.7514 30.6371 18.6346 32.886 19.2373" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="our-services--list-item--title"><span>Контроль качества</span></div>
                <div class="our-services--list-item--description">
                    <p>Используем современные технологии печати, стойкие материалы и&nbsp;тщательно контролируем каждую деталь</p>
                </div>
                <div class="our-services--list-item--action">
                    <a href="#" class="our-services--list-item--action_btn">Подробнее</a>
                </div>
            </div>
            <div class="our-services--list-item">
                <div class="our-services--list-item--image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="30" fill="#57B0EA"/>
                        <g clip-path="url(#clip0_903_8371)">
                            <path d="M30.7793 20.5921L33.3104 25.7019C33.3679 25.8316 33.4585 25.9439 33.573 26.0277C33.6875 26.1115 33.822 26.1638 33.963 26.1794L39.5504 27.0072C39.7121 27.028 39.8647 27.0944 39.9901 27.1986C40.1156 27.3029 40.2088 27.4406 40.2589 27.5959C40.3089 27.7511 40.3138 27.9174 40.2729 28.0753C40.232 28.2332 40.1471 28.3762 40.0279 28.4876L36.0006 32.4831C35.8978 32.5791 35.8206 32.6994 35.7762 32.8328C35.7317 32.9662 35.7213 33.1087 35.7459 33.2472L36.7169 38.8664C36.745 39.0278 36.7272 39.194 36.6656 39.3458C36.604 39.4977 36.501 39.6292 36.3683 39.7254C36.2356 39.8216 36.0785 39.8786 35.9151 39.89C35.7516 39.9013 35.5881 39.8666 35.4434 39.7896L30.4132 37.1313C30.2844 37.068 30.1428 37.0352 29.9994 37.0352C29.8559 37.0352 29.7143 37.068 29.5855 37.1313L24.5553 39.7896C24.4106 39.8666 24.2471 39.9013 24.0836 39.89C23.9202 39.8786 23.7631 39.8216 23.6304 39.7254C23.4977 39.6292 23.3947 39.4977 23.3331 39.3458C23.2715 39.194 23.2537 39.0278 23.2818 38.8664L24.2528 33.1835C24.2774 33.045 24.267 32.9026 24.2225 32.7691C24.1781 32.6357 24.1009 32.5155 23.9981 32.4194L19.923 28.4876C19.8025 28.3731 19.7177 28.2262 19.6789 28.0645C19.6402 27.9029 19.6492 27.7334 19.7048 27.5768C19.7604 27.4201 19.8602 27.2829 19.9922 27.1818C20.1241 27.0807 20.2826 27.0201 20.4483 27.0072L26.0357 26.1794C26.1767 26.1638 26.3112 26.1115 26.4257 26.0277C26.5402 25.9439 26.6308 25.8316 26.6883 25.7019L29.2194 20.5921C29.2883 20.4433 29.3983 20.3173 29.5365 20.229C29.6748 20.1407 29.8353 20.0938 29.9994 20.0938C30.1634 20.0938 30.3239 20.1407 30.4622 20.229C30.6004 20.3173 30.7104 20.4433 30.7793 20.5921V20.5921Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_903_8371">
                                <rect width="22.2857" height="22.2857" fill="white" transform="translate(18.8555 18.8569)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="our-services--list-item--title"><span>Скидки и программа лояльности</span></div>
                <div class="our-services--list-item--description">
                    <p>Скидки WELCOM и&nbsp;система скидок для&nbsp;постоянных клиентов. Программа привилегий и&nbsp;бонусов для&nbsp;дилеров.</p>
                </div>
                <div class="our-services--list-item--action">
                    <a href="#" class="our-services--list-item--action_btn">Подробнее</a>
                </div>
            </div>
        </div>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "onlineservice-brands-news.list",
        Array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "Y",
            "CACHE_NOTES" => "",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "FIELD_CODE" => array("", ""),
            "FILTER_NAME" => "",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "IBLOCK_ID" => "39",
            "IBLOCK_TYPE" => "content",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
            "INCLUDE_SUBSECTIONS" => "N",
            "MESSAGE_404" => "",
            "NEWS_COUNT" => "0",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => "onlineservice-brands-news.list",
            "PAGER_TITLE" => "Новости",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "",
            "PREVIEW_TRUNCATE_LEN" => "",
            "PROPERTY_CODE" => array("", ""),
            "SET_BROWSER_TITLE" => "Y",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "SORT_BY1" => "ID",
            "SORT_BY2" => "SORT",
            "SORT_ORDER1" => "DESC",
            "SORT_ORDER2" => "ASC",
            "STRICT_SECTION_CHECK" => "N"
        )
    );?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "onlineservice-feedback-form-type-1",
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
            "WEB_FORM_ID" => "1"
        )
    );?>
</main>