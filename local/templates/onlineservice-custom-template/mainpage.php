<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Page\Asset;

// Подключаем стили и скрипты кастомного шаблона
$asset = Asset::getInstance();
$asset->addCss("/local/templates/onlineservice-custom-template/styles/template.css");


$asset->addCss("/local/templates/onlineservice-custom-template/styles/header_old.css");
$asset->addCss("/local/templates/onlineservice-custom-template/styles/header_custom.css");


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

$APPLICATION->SetTitle('ЙО!каталог');
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

    <?/*$APPLICATION->IncludeComponent(
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
                0 => "MOBILE_BANNER",
                1 => "",
            ),
            "SET_BROWSER_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "SORT_BY1" => "SORT",
            "SORT_BY2" => "ID",
            "SORT_ORDER1" => "ASC",
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
    );*/?>

    <div class="container">
        <div class="categories-slider--container-title">
            <span class="title">Категории товаров</span>
        </div>
        <div class="categories-slider owl-carousel owl-theme" id="categoriesSlider">
            <?php
                $arFilter = array(
                    'IBLOCK_ID' => 43,
                    'ACTIVE' => 'Y',
                    'GLOBAL_ACTIVE' => 'Y',
                    'SECTION_ID' => 0
                );
                $arSelect = array(
                    'ID',
                    'NAME',
                    'PICTURE',
                    'DETAIL_PICTURE',
                    'SECTION_PAGE_URL',
                    'UF_SVG',
                    'UF_IMAGE',
                    'UF_HOVER_TEMPLATE'
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
                    $sectionSvg = ( !empty($arSection['UF_SVG']) ) ? CFile::GetPath($arSection['UF_SVG']) : null;
                    $hoverImage = ( !empty($arSection['UF_IMAGE']) ) ? CFile::GetPath($arSection['UF_IMAGE']) : null;

                    // Получаем URL изображения раздела
                    $sectionImage = CFile::GetPath($arSection['PICTURE']);
                    if (empty($sectionImage)) {
                        $sectionImage = CFile::GetPath($arSection['DETAIL_PICTURE']);
                    }
                    
                    if ($counter % 3 == 0) {
                        if ($counter > 0) echo '</div>';
                        echo '<div class="categories-slider--item">';
                        $columnCounter++;
                    }

                    $isEmptyPicture = false;
                    if (empty($sectionImage)){
                        $isEmptyPicture = true;
                        $sectionImage = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                    }
                    if( empty($sectionSvg) ){
                        $isEmptyPicture = true;
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
                        <?php
                            if( strlen($arSection['NAME'])/2 > 18 ){ ?>
                                #categoriesSlider .owl-item:nth-child(<?=$columnCounter;?>) .categories-slider--item_category:nth-of-type(<?=$keyCounter;?>) .categories-slider--item_category-title{
                                    top: 109px!important;
                                }
                        <?php }
                        ?>
                        </style>
                    <?php
                        }

                        $additional_class = null;
                        if($isEmptyPicture || $arSection == 7 || empty($sectionSvg)){
                            $additional_class = "transition-style-alternative-1";
                        }
                        if(!$isEmptyPicture && $arSection == 8){
                            $additional_class = "transition-style-alternative-2";
                        }

                        if(empty($sectionSvg))
                            $sectionSvg = $sectionImage;

                        if(empty($hoverImage))
                            $hoverImage = $sectionImage;
                    ?>
                    <a href="<?= $arSection['SECTION_PAGE_URL'] ?>"
                       class="categories-slider--item_category <?=$additional_class;?>"
                       style="background-image: url('<?= $hoverImage ?>')"
                    >
                        <div class="categories-slider--item_category-image">
                            <img src="<?= $sectionSvg ?>" alt="<?= $arSection['NAME'] ?>">
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

    <!--<div class="container">
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
    </div>-->
    <div class="container">
        <div class="our-services">
            <span class="our-services--title">Наши услуги</span>
        </div>
        <div class="our-services--list">
            <div class="our-services--list-column">
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="51" height="59" viewBox="0 0 51 59" fill="none">
                            <path d="M6.31695 31.5837H44.2183V29.4782H6.31695V31.5837ZM45.2704 33.6891H5.26395C4.68198 33.6891 4.21057 33.2187 4.21057 32.637V28.4249C4.21057 27.8432 4.68198 27.3728 5.26395 27.3728H45.2704C45.8521 27.3728 46.3235 27.8432 46.3235 28.4249V32.637C46.3235 33.2187 45.8521 33.6891 45.2704 33.6891Z" fill="#85248F"/>
                            <path d="M8.42235 56.851H42.1128V33.6889H8.42235V56.851ZM43.1649 58.9564H7.36931C6.78735 58.9564 6.31689 58.486 6.31689 57.9043V32.6368C6.31689 32.0549 6.78735 31.5835 7.36931 31.5835H43.1649C43.7466 31.5835 44.2183 32.0549 44.2183 32.6368V57.9043C44.2183 58.486 43.7466 58.9564 43.1649 58.9564Z" fill="#85248F"/>
                            <path d="M16.8442 44.2174H33.689V40.0065H16.8442V44.2174ZM34.742 46.3228H15.7921C15.2105 46.3228 14.7388 45.8527 14.7388 45.2707V38.9534C14.7388 38.3715 15.2105 37.9014 15.7921 37.9014H34.742C35.324 37.9014 35.7953 38.3715 35.7953 38.9534V45.2707C35.7953 45.8527 35.324 46.3228 34.742 46.3228Z" fill="#85248F"/>
                            <path d="M8.42235 27.3729H42.1128V2.10544H8.42235V27.3729ZM43.1649 29.4783H7.36931C6.78735 29.4783 6.31689 29.007 6.31689 28.425V1.0524C6.31689 0.47044 6.78735 0 7.36931 0H43.1649C43.7466 0 44.2183 0.47044 44.2183 1.0524V28.425C44.2183 29.007 43.7466 29.4783 43.1649 29.4783Z" fill="#85248F"/>
                            <path d="M13.6873 16.8447C13.3777 16.8447 13.0722 16.7085 12.8636 16.4501C12.5016 15.9954 12.5752 15.3332 13.0286 14.9686L23.557 6.54718C24.0107 6.18384 24.6739 6.25936 25.0372 6.71084C25.3993 7.1658 25.3257 7.8277 24.872 8.1923L14.3438 16.6137C14.1499 16.7698 13.9179 16.8447 13.6873 16.8447Z" fill="#85248F"/>
                            <path d="M24.2155 16.8448C23.9134 16.8448 23.6136 16.7165 23.4051 16.467C23.0329 16.0189 23.0942 15.3545 23.54 14.9823L29.8563 9.71938C30.3034 9.34815 30.9674 9.40281 31.3396 9.85303C31.7121 10.301 31.6518 10.9654 31.206 11.3364L24.8887 16.6006C24.6913 16.7646 24.4527 16.8448 24.2155 16.8448Z" fill="#85248F"/>
                            <path d="M49.4816 58.9572H1.05239C0.470424 58.9572 0 58.4868 0 57.9039C0 57.3222 0.470424 56.8518 1.05239 56.8518H49.4816C50.0636 56.8518 50.5349 57.3222 50.5349 57.9039C50.5349 58.4868 50.0636 58.9572 49.4816 58.9572Z" fill="#85248F"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Шоурум и пункт выдачи образцов </span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="41" viewBox="0 0 42 41" fill="none">
                            <path d="M39.7367 0C40.8636 0 41.7874 0.907088 41.7874 2.02521V28.3536C41.7874 29.4714 40.8636 30.3775 39.7367 30.3775L22.1498 30.3766V37.484L29.8894 37.4853C30.7408 37.4853 31.4299 38.1652 31.4299 39.0043C31.4299 39.8425 30.7408 40.5224 29.8894 40.5224H11.8412C10.9973 40.5224 10.3086 39.8425 10.3086 39.0043C10.3086 38.1652 10.9973 37.4853 11.8412 37.4853L19.0857 37.484V30.3766L2.05078 30.3775C0.916237 30.3775 0 29.4714 0 28.3536V2.02521C0 0.907088 0.916237 0 2.05078 0H39.7367ZM3.07158 27.3404H38.7159V3.0381H3.07158V27.3404Z" fill="#CD4F4F"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Подготовка макетов к печати</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46" fill="none">
                            <path d="M22.5121 2.83904C11.6652 2.83904 2.83873 11.6643 2.83873 22.5124C2.83873 33.3616 11.6652 42.1868 22.5121 42.1868C33.3591 42.1868 42.1855 33.3616 42.1855 22.5124C42.1855 11.6643 33.3591 2.83904 22.5121 2.83904ZM22.5121 45.0255C10.0991 45.0255 0 34.9264 0 22.5124C0 10.0994 10.0991 0 22.5121 0C34.9252 0 45.0243 10.0994 45.0243 22.5124C45.0243 34.9264 34.9252 45.0255 22.5121 45.0255Z" fill="#F2B144"/>
                            <path d="M30.9486 43.2699C30.1651 43.2699 29.5298 42.6333 29.5298 41.851V27.1082L22.5101 14.8266L15.4937 27.1082V41.851C15.4937 42.6333 14.8571 43.2699 14.0735 43.2699C13.2913 43.2699 12.6547 42.6333 12.6547 41.851V26.7303C12.6547 26.4829 12.7185 26.2409 12.8411 26.0248L21.2783 11.2599C21.7832 10.375 23.24 10.375 23.7439 11.2599L32.1824 26.0248C32.305 26.2409 32.3675 26.4829 32.3675 26.7303V41.851C32.3675 42.6333 31.7322 43.2699 30.9486 43.2699Z" fill="#F2B144"/>
                            <path d="M25.7603 17.6458H19.2699L22.5146 12.2378L25.7603 17.6458Z" fill="#F2B144"/>
                            <path d="M26.7299 30.2608C25.1704 30.2608 23.5319 29.2855 22.5111 28.5453C21.489 29.2855 19.8527 30.2608 18.2913 30.2608C16.0264 30.2608 13.5968 28.2053 13.132 27.7943C12.5456 27.2736 12.4932 26.3785 13.0104 25.7903C13.5311 25.2061 14.428 25.1505 15.0144 25.6677C15.8403 26.3953 17.4029 27.4218 18.2913 27.4218C19.1807 27.4218 20.7712 26.3763 21.5692 25.6699C22.1054 25.195 22.9123 25.1938 23.4516 25.6677C24.2775 26.3953 25.8402 27.4218 26.7299 27.4218C27.6202 27.4218 29.1819 26.3953 30.0078 25.6677C30.5938 25.1505 31.4924 25.2061 32.0105 25.7903C32.5299 26.3785 32.4765 27.2736 31.8901 27.7943C31.6049 28.0448 29.0435 30.2608 26.7299 30.2608Z" fill="#F2B144"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Собственное производство</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46" fill="none">
                            <path d="M9.85517 2.83907C7.98163 2.83907 6.22059 3.56794 4.89426 4.89426C3.56889 6.22059 2.8378 7.98258 2.8378 9.85644V35.1694C2.8378 37.0429 3.56794 38.8062 4.89426 40.1316C6.21838 41.4579 7.98163 42.1868 9.85517 42.1868C11.73 42.1868 13.492 41.4579 14.8183 40.1316C16.1424 38.8062 16.8713 37.0429 16.8713 35.1694V9.85644C16.8713 7.98258 16.1424 6.22059 14.8183 4.89426C13.492 3.56794 11.7287 2.83907 9.85517 2.83907ZM9.85517 45.0255C7.22243 45.0255 4.74829 44.0003 2.88454 42.1378C1.02428 40.2763 0 37.8021 0 35.1694V9.85644C0 7.22369 1.02428 4.74924 2.88454 2.88677C4.74829 1.02555 7.22464 0 9.85517 0C12.4876 0 14.9633 1.02554 16.8258 2.88581C18.687 4.74924 19.7123 7.22369 19.7123 9.85644V35.1694C19.7123 37.8021 18.687 40.2763 16.8258 42.1378C14.9633 44.0003 12.4876 45.0255 9.85517 45.0255Z" fill="#67B97E"/>
                            <path d="M17.8342 40.919L15.827 38.9118L36.2113 18.5272C37.5354 17.2 38.2665 15.4377 38.2665 13.5641C38.2665 11.6918 37.5354 9.92731 36.2113 8.60099C34.885 7.27687 33.1217 6.54579 31.2481 6.54579C29.3746 6.54579 27.6113 7.27685 26.285 8.60318L19.2976 15.5928L17.2892 13.5853L24.2775 6.59475C26.1422 4.73228 28.6176 3.70703 31.2481 3.70703C33.8809 3.70703 36.355 4.73228 38.2188 6.59475C40.0812 8.45818 41.1078 10.9326 41.1078 13.5641C41.1078 16.1969 40.0812 18.671 38.2188 20.5347L17.8342 40.919Z" fill="#67B97E"/>
                            <path d="M9.84945 34.4791C9.66905 34.4791 9.49053 34.5515 9.36258 34.6798C9.23209 34.8122 9.1607 34.985 9.1607 35.1689C9.1607 35.3527 9.23209 35.5269 9.36258 35.657C9.61881 35.9136 10.0801 35.9136 10.3389 35.657C10.4671 35.5269 10.5395 35.3527 10.5395 35.1689C10.5395 34.985 10.4671 34.8122 10.3389 34.6842C10.2062 34.5515 10.0333 34.4791 9.84945 34.4791ZM9.84945 38.6977C8.92121 38.6977 8.01161 38.322 7.35508 37.6633C6.68749 36.9979 6.32068 36.1119 6.32068 35.1689C6.32068 34.2261 6.68844 33.3389 7.35508 32.6723C8.68488 31.3403 11.0099 31.3393 12.346 32.6745C13.0114 33.3367 13.3782 34.2239 13.3782 35.1689C13.3782 36.1119 13.0114 37.0001 12.3438 37.6658C11.6784 38.3321 10.7903 38.6977 9.84945 38.6977ZM35.1615 45.0262H9.84945V42.1863H35.1615C37.0372 42.1863 38.7983 41.4561 40.1246 40.1298C41.4487 38.8057 42.1798 37.0449 42.1798 35.1689C42.1798 33.2944 41.4487 31.532 40.1246 30.2057C38.7983 28.8816 37.0372 28.1506 35.1615 28.1506H29.8896V25.3118H35.1615C37.7942 25.3118 40.2696 26.337 42.1321 28.1986C43.9946 30.0607 45.0189 32.5352 45.0189 35.1689C45.0189 37.8038 43.9946 40.277 42.1321 42.1395C40.2718 44.002 37.7942 45.0262 35.1615 45.0262Z" fill="#67B97E"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Все виды нанесения</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46" fill="none">
                            <path d="M22.5156 45.0414C20.3211 45.0414 18.1231 44.716 15.9785 44.0649C11.4491 42.6896 7.5311 39.9778 4.65129 36.2238C1.77211 32.4701 0.167118 27.9872 0.0123061 23.256C-0.142505 18.5279 1.16583 13.948 3.79636 10.0114C6.42658 6.07473 10.1582 3.11566 14.5855 1.45033C19.0147 -0.21816 23.7727 -0.452308 28.3416 0.775127C29.0992 0.977962 29.5485 1.75804 29.3447 2.51472C29.1441 3.27172 28.3627 3.72543 27.607 3.51691C23.6135 2.44903 19.4551 2.65089 15.5841 4.1077C11.7154 5.56324 8.45618 8.1489 6.15707 11.5908C3.85986 15.0304 2.7152 19.0318 2.84884 23.1656C2.98501 27.2962 4.38716 31.2162 6.9049 34.4976C9.4217 37.7755 12.8443 40.145 16.8021 41.3465C20.7612 42.5493 24.9209 42.4801 28.8363 41.1494C32.754 39.8196 36.0967 37.3397 38.5042 33.9803C40.9126 30.6187 42.1877 26.6587 42.1877 22.5214C42.1877 21.7366 42.824 21.1003 43.6066 21.1003C44.3914 21.1003 45.0265 21.7366 45.0265 22.5214C45.0265 27.2539 43.57 31.7889 40.8124 35.6343C38.0584 39.4796 34.2307 42.3162 29.7504 43.8399C27.3906 44.6402 24.9544 45.0414 22.5156 45.0414Z" fill="#E6086E"/>
                            <path d="M24.5258 25.4488C23.711 25.4488 22.9218 25.1881 22.2674 24.6956L14.6327 18.9675C14.004 18.4996 13.8783 17.6077 14.3484 16.9815C14.8188 16.3541 15.7082 16.228 16.3369 16.6984L23.9717 22.4239C24.159 22.5655 24.3931 22.6346 24.6237 22.6043C24.8544 22.58 25.0686 22.4685 25.2234 22.2934L40.1978 5.17825C40.715 4.58617 41.6097 4.52708 42.2018 5.04428C42.7914 5.56148 42.8504 6.45782 42.3333 7.04736L27.3589 24.1616C26.7336 24.8782 25.8689 25.3284 24.9201 25.4288C24.7899 25.4412 24.656 25.4488 24.5258 25.4488Z" fill="#E6086E"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Контроль качества</span>
                    </div>
                </div>
            </div>
            <div class="our-services--list-column">
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="41" viewBox="0 0 42 41" fill="none">
                            <path d="M21.0016 32.0668C21.4932 32.0668 21.9826 32.1802 22.4148 32.3954L31.9736 37.4444L33.0557 38.579L32.2276 37.4255C32.3234 37.2906 32.3268 37.2217 32.3167 37.149L30.4776 26.5583C30.3952 26.049 30.4331 25.5432 30.5869 25.0794C30.7553 24.5891 31.0362 24.1565 31.4005 23.8223L38.984 16.2978C39.0554 16.2308 39.0757 16.1784 39.0867 16.1395L39.0166 15.9645C39.0065 15.9645 38.876 15.9086 38.8672 15.9064L28.2897 14.3405C27.8395 14.2881 27.3637 14.1042 26.9466 13.8123C26.5132 13.4891 26.19 13.09 25.9761 12.6307L21.2066 2.98747C21.199 2.9742 21.1911 2.95967 21.1845 2.94514C21.1744 2.92271 21.1488 2.88701 21.1042 2.85921C20.9772 2.83488 20.9314 2.84024 20.8657 2.87942L16.005 12.6632C15.8268 13.0755 15.4957 13.4869 15.0745 13.7933C14.6597 14.0985 14.1703 14.2881 13.6578 14.3462L3.12841 15.9064C3.12714 15.9086 2.92211 15.961 2.9199 15.961C2.9199 15.9623 2.84628 16.1762 2.84754 16.1784L10.6273 23.7253C10.9717 24.045 11.2481 24.4763 11.4108 24.9546C11.5701 25.437 11.607 25.9498 11.5198 26.4449L9.68201 37.1914L8.94522 38.579L9.77679 37.4277H9.77899C9.79573 37.4277 10.0131 37.4444 10.0299 37.4444H10.0321L19.5583 32.4121C20.0196 32.1827 20.509 32.0668 21.0016 32.0668ZM32.0826 40.3135C31.5711 40.3135 31.0729 40.1865 30.6305 39.9467L21.121 34.9223C21.0964 34.9112 20.9381 34.8945 20.8569 34.939L11.3584 39.9546C10.8545 40.2241 10.2851 40.3445 9.70222 40.3056C9.13131 40.269 8.58096 40.0671 8.11147 39.7293C7.64356 39.3884 7.2802 38.9268 7.06599 38.3897C6.84894 37.8593 6.78511 37.2773 6.88432 36.7099L8.72249 25.9555C8.71681 25.9498 8.66214 25.7748 8.65551 25.7694L0.940507 18.3274C0.518093 17.9293 0.219215 17.4077 0.08336 16.8314C-0.052495 16.2599 -0.0224608 15.6612 0.174687 15.1051C0.372151 14.5513 0.725374 14.0653 1.1936 13.7064C1.66056 13.3475 2.22229 13.1311 2.81626 13.0866L13.2888 11.5318C13.2967 11.5116 13.4493 11.4215 13.4581 11.4013L18.2519 1.72813C18.4737 1.24884 18.8551 0.803995 19.341 0.484261C20.4063 -0.171318 21.6414 -0.144461 22.629 0.463096C23.1405 0.79831 23.5218 1.23526 23.7578 1.74709L28.5349 11.4035C28.5485 11.4035 28.6932 11.5296 28.7068 11.5318L39.2839 13.0976C39.8257 13.1668 40.365 13.3997 40.8086 13.7655C41.2478 14.1232 41.5789 14.6047 41.7694 15.1607C41.9479 15.6947 41.97 16.2788 41.8341 16.8516C41.6613 17.4681 41.3605 17.9641 40.9492 18.3451L33.359 25.8762L31.8766 26.3144L33.2753 26.0724L35.121 36.7076C35.2079 37.3162 35.1434 37.8779 34.9438 38.3818C34.7321 38.918 34.3653 39.3872 33.8838 39.7328C33.4115 40.0693 32.8662 40.2668 32.3022 40.3056C32.2298 40.3113 32.1553 40.3135 32.0826 40.3135Z" fill="#5EAFE2"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Скидки и программа лояльности</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="42" viewBox="0 0 44 42" fill="none">
                            <path d="M8.163 13.5789H41.2459C41.3584 12.1635 41.4162 10.7433 41.4162 9.32692V6.1435H35.9526V9.86057H33.5262V6.1435H16.2303V9.86057H13.8051V6.1435H8.34118V9.12727C8.34118 10.6119 8.28211 12.0977 8.163 13.5789ZM2.90222 33.3013H35.8101L36.1766 32.53C38.6467 27.3407 40.2772 21.7242 40.996 16.0044H7.91561C7.17536 21.9874 5.47905 27.8645 2.90222 33.3013ZM43.8417 41.6427H5.91601V35.7267H0V33.7439L0.598377 32.4978C4.0769 25.2495 5.91601 17.168 5.91601 9.12727V3.71834H13.8051V0H16.2303V3.71834H33.5262V0H35.9526V3.71834H43.8417V9.32692C43.8417 17.6627 41.9492 26.0475 38.3667 33.5733L37.8508 34.6434L37.2031 35.7267H8.34118V39.2176H41.4162V31.5547H43.8417V41.6427Z" fill="#67B97E"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Возможность постоплаты</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="53" viewBox="0 0 42 53" fill="none">
                            <path d="M30.6746 28.1178H27.6074V14.314H10.7361V11.2468H30.6746V28.1178Z" fill="#85248F"/>
                            <path d="M41.9222 46.5227H18.4044V43.4555H32.2086V30.1632H11.248V27.0957H35.2757V43.4555H41.9222V46.5227Z" fill="#85248F"/>
                            <path d="M12.7808 37.3203H9.71364V6.64617C9.71364 4.67248 8.10739 3.06748 6.13464 3.06748H0V0H6.13464C9.79957 0 12.7808 2.98156 12.7808 6.64617V37.3203Z" fill="#85248F"/>
                            <path d="M11.2476 40.3875C8.71088 40.3875 6.64682 42.4518 6.64682 44.9885C6.64682 47.5265 8.71088 49.5896 11.2476 49.5896C13.7843 49.5896 15.8486 47.5265 15.8486 44.9885C15.8486 42.4518 13.7843 40.3875 11.2476 40.3875ZM11.2476 52.658C7.01901 52.658 3.57935 49.2171 3.57935 44.9885C3.57935 40.76 7.01901 37.3203 11.2476 37.3203C15.4765 37.3203 18.9158 40.76 18.9158 44.9885C18.9158 49.2171 15.4765 52.658 11.2476 52.658Z" fill="#85248F"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Бесплатный копакинг и хранение заказа</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="32" viewBox="0 0 44 32" fill="none">
                            <path d="M15.8603 2.50575C13.056 2.50575 11.6482 2.50574 10.6798 2.93826C9.82928 3.31708 9.10482 3.92906 8.58542 4.7047C8.00029 5.58175 7.7684 6.96275 7.30934 9.7146L6.86668 12.3518C7.17757 12.3496 7.51091 12.3496 7.8676 12.3496H16.538V2.50575H15.8603ZM19.0447 12.3496H32.677C33.2175 12.3496 33.6668 12.3495 34.0535 12.354L30.0331 6.34982C29.0256 4.8453 28.5207 4.09179 27.9545 3.62704C27.4475 3.21347 26.8734 2.90793 26.2481 2.71963C25.5436 2.50574 24.6331 2.50575 22.8173 2.50575H19.0447V12.3496ZM39.5695 24.7001H40.119C40.4043 24.7001 40.5436 24.707 40.6617 24.6903C41.0096 24.6356 41.2826 24.3658 41.3372 24.018C41.3562 23.8998 41.347 23.7604 41.347 23.483C41.347 22.0208 41.347 21.2873 41.2614 20.7457C40.7843 17.7487 38.4404 15.4158 35.4288 14.9422C34.8825 14.8563 34.1448 14.8562 32.677 14.8562H7.8676C5.82251 14.8562 4.69553 14.8563 4.07692 15.1694C3.53192 15.4458 3.09718 15.8783 2.81978 16.4211C2.50637 17.0331 2.50637 18.1543 2.50637 20.1893V22.6606C2.50637 23.4262 2.50637 24.0871 2.59452 24.2587C2.67161 24.4094 2.79642 24.5342 2.94554 24.61C3.10162 24.689 3.63461 24.6991 4.28324 24.7001C4.85035 22.3419 6.98485 20.5839 9.52155 20.5839C12.0595 20.5839 14.194 22.3419 14.7611 24.7001H29.0904C29.6587 22.3419 31.792 20.5839 34.3299 20.5839C36.8676 20.5839 39.0021 22.3419 39.5695 24.7001ZM6.64046 25.9531C6.64046 27.5322 7.93236 28.8174 9.52155 28.8174C11.112 28.8174 12.4039 27.5322 12.4039 25.9531C12.4039 24.3737 11.112 23.0897 9.52155 23.0897C7.93236 23.0897 6.64046 24.3737 6.64046 25.9531ZM31.4486 25.9531C31.4486 27.5322 32.7404 28.8174 34.3299 28.8174C35.9191 28.8174 37.211 27.5322 37.211 25.9531C37.211 24.3737 35.9191 23.0897 34.3299 23.0897C32.7404 23.0897 31.4486 24.3737 31.4486 25.9531ZM34.3299 31.3228C31.792 31.3228 29.6587 29.5652 29.0904 27.2058H14.7611C14.194 29.5652 12.0595 31.3228 9.52155 31.3228C6.98485 31.3228 4.85035 29.5652 4.28324 27.2058C3.143 27.2035 2.47318 27.1802 1.81445 26.8456C1.18793 26.5291 0.685277 26.0299 0.362068 25.4002C-1.23535e-06 24.6912 0 23.9702 0 22.6606V20.1893C0 17.6861 -4.94141e-06 16.4299 0.587331 15.2809C1.10895 14.2623 1.92346 13.451 2.94459 12.9338C3.36352 12.7208 3.79825 12.5859 4.30218 12.5L4.83709 9.30135C5.35776 6.19595 5.61747 4.63773 6.50115 3.31267C7.28595 2.14242 8.37688 1.22176 9.65771 0.649903C11.1155 9.88283e-06 12.7015 0 15.8603 0H22.8173C24.8804 0 25.9158 2.71477e-05 26.9736 0.320076C27.9132 0.603161 28.7769 1.06347 29.5393 1.68745C30.3965 2.38758 30.9705 3.24473 32.113 4.95335L37.406 12.8566C40.712 13.9747 43.175 16.8236 43.7358 20.3523C43.8527 21.0888 43.8528 21.8891 43.8528 23.483C43.8528 23.9702 43.8449 24.2132 43.8114 24.416C43.5852 25.8359 42.474 26.9426 41.0497 27.1656C40.8469 27.1969 40.6049 27.2058 40.119 27.2058H39.5695C39.0021 29.5652 36.8676 31.3228 34.3299 31.3228Z" fill="#CD4F4F"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Удобный самовывоз в пределах МКАД</span>
                    </div>
                </div>
                <div class="our-services--list-item">
                    <div class="our-services--list-item__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="37" viewBox="0 0 46 37" fill="none">
                            <path d="M11.9678 26.0406C10.939 26.0406 9.96847 26.4432 9.23485 27.172C8.53062 27.8842 8.12051 28.8797 8.12051 29.8948C8.12051 30.9103 8.53062 31.9045 9.24275 32.6246C10.6838 34.0602 13.2451 34.0656 14.701 32.6167C15.4074 31.9045 15.8153 30.9103 15.8153 29.8948C15.8153 28.8797 15.4074 27.8842 14.6931 27.1641C13.9762 26.4542 12.981 26.0406 11.9678 26.0406ZM11.9678 36.588C10.1833 36.588 8.5028 35.8923 7.23303 34.6295C5.97021 33.3569 5.28052 31.6793 5.28052 29.8948C5.28052 28.1116 5.97021 26.4321 7.2264 25.1667C8.50281 23.8963 10.1833 23.2019 11.9678 23.2019C13.7257 23.2019 15.452 23.914 16.7015 25.1591C17.9653 26.4321 18.6553 28.1116 18.6553 29.8948C18.6553 31.6793 17.9653 33.3569 16.7094 34.6241C15.452 35.8734 13.7279 36.588 11.9678 36.588ZM33.0667 26.0406C32.038 26.0406 31.0671 26.4432 30.3338 27.172C29.6049 27.8978 29.2024 28.8652 29.2024 29.8948C29.2024 30.9261 29.6049 31.8913 30.3338 32.6167C31.7862 34.0656 34.3519 34.0634 35.7784 32.6246C36.513 31.8935 36.9133 30.9261 36.9133 29.8948C36.9133 28.8652 36.513 27.8978 35.7819 27.172C35.0698 26.4498 34.0799 26.0406 33.0667 26.0406ZM33.0667 36.588C31.2791 36.588 29.597 35.8923 28.3298 34.6295C27.0616 33.3679 26.3636 31.6859 26.3636 29.8948C26.3636 28.1037 27.0616 26.422 28.3298 25.1591C29.5992 23.8963 31.2813 23.2019 33.0667 23.2019C34.8321 23.2019 36.5566 23.9184 37.7938 25.1667C39.0566 26.422 39.752 28.1037 39.752 29.8948C39.752 31.6859 39.0566 33.3679 37.7881 34.6295C36.5566 35.8724 34.8334 36.588 33.0667 36.588Z" fill="#F2B144"/>
                            <path d="M14.0837 9.16738H1.41856C0.634077 9.16738 0 8.53075 0 7.74848C0 6.96273 0.634077 6.32834 1.41856 6.32834H14.0837C14.8682 6.32834 15.5023 6.96273 15.5023 7.74848C15.5023 8.53075 14.8682 9.16738 14.0837 9.16738ZM9.86591 15.4957H1.41856C0.634077 15.4957 0 14.8594 0 14.0746C0 13.2911 0.634077 12.6548 1.41856 12.6548H9.86591C10.652 12.6548 11.2861 13.2911 11.2861 14.0746C11.2861 14.8594 10.652 15.4957 9.86591 15.4957ZM6.70081 31.3149C4.55177 31.3149 3.36919 31.3149 2.30036 30.7007C1.61477 30.3074 1.03407 29.7302 0.623029 29.0367C-8.61735e-06 27.9612 0 26.7764 0 24.622V20.4033C0 19.6197 0.634077 18.9834 1.41856 18.9834C2.20431 18.9834 2.83873 19.6197 2.83873 20.4033V24.622C2.83873 26.2292 2.83873 27.1988 3.07284 27.6001C3.23113 27.8686 3.4507 28.0872 3.71577 28.2398C4.12587 28.4762 5.09456 28.4762 6.70081 28.4762C7.48529 28.4762 8.1194 29.1125 8.1194 29.896C8.1194 30.6786 7.48529 31.3149 6.70081 31.3149ZM27.7985 31.3149H17.2501C16.4644 31.3149 15.828 30.6786 15.828 29.896C15.828 29.1125 16.4644 28.4762 17.2501 28.4762H27.7985C28.5842 28.4762 29.2183 29.1125 29.2183 29.896C29.2183 30.6786 28.5842 31.3149 27.7985 31.3149ZM38.3332 31.3149C37.5488 31.3149 36.9121 30.6786 36.9121 29.896C36.9121 29.1125 37.5488 28.4762 38.3332 28.4762C39.887 28.4762 40.9157 28.4762 41.3059 28.2455C41.5789 28.0894 41.8042 27.8642 41.9669 27.5912C42.1931 27.1988 42.1931 26.2292 42.1931 24.622V18.9932C42.1931 18.2188 42.1922 17.8675 42.1296 17.6457C42.0727 17.4318 41.8946 17.1332 41.492 16.471L37.9099 10.4959C37.1352 9.19521 36.745 8.56081 36.3561 8.33902C35.9539 8.1128 35.1545 8.1128 33.7034 8.1128H30.2672V25.6763C30.2672 26.4599 29.6331 27.0962 28.8483 27.0962C28.0626 27.0962 27.4285 26.4599 27.4285 25.6763V7.74848C27.4285 5.32204 27.4285 3.85863 26.9211 3.35028C26.4052 2.83877 24.9417 2.83873 22.5165 2.83873H1.41856C0.634077 2.83873 0 2.20248 0 1.41989C0 0.63414 0.634077 0 1.41856 0H22.5165C25.7764 0 27.5722 1.22933e-05 28.9264 1.33961C29.8827 2.29691 30.1579 3.48646 30.236 5.27185H33.7034C35.6395 5.27185 36.7062 5.2718 37.7573 5.87146C38.817 6.46986 39.3923 7.43505 40.3486 9.03909L43.9229 15.0044C44.4088 15.8047 44.7033 16.2906 44.8682 16.8947C45.034 17.4953 45.034 18.027 45.034 18.9932V24.622C45.034 26.7764 45.034 27.9612 44.4167 29.0256C44.0009 29.7289 43.4193 30.3052 42.7327 30.7007C41.686 31.3149 40.4955 31.3149 38.3332 31.3149Z" fill="#F2B144"/>
                        </svg>
                    </div>
                    <div class="our-services--list-item__title">
                        <span>Склад в Москве и своя служба доставки</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?/*$APPLICATION->IncludeComponent(
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
            "FIELD_CODE" => array("DETAIL_PICTURE"),
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
            "SORT_BY1" => "SORT",
            "SORT_BY2" => "SORT",
            "SORT_ORDER1" => "ASC",
            "SORT_ORDER2" => "ASC",
            "STRICT_SECTION_CHECK" => "N"
        )
    );*/?>

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