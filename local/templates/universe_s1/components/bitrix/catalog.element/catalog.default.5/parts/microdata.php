<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$sPicture = null;
$fRatingValue = 3;
$iReviewCount = 1;
$iOffersCount = null;
$arMinPrice = [];
$arMaxPrice = [];
$sAvailability = 'OutOfStock';

if (!empty($arResult['GALLERY']['VALUES'])){
    $sPicture = $arResult['GALLERY']['VALUES'][0]['SRC'];
}
elseif (!empty($arResult['OFFERS'])){
    $sPicture = $arResult['OFFERS'][0]['DETAIL_PICTURE']['SRC'];
}
else
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

if (!empty($arResult['PROPERTIES']['rating']['VALUE']))
    $fRatingValue = $arResult['PROPERTIES']['rating']['VALUE'];

if (!empty($arResult['PROPERTIES']['vote_count']['VALUE']))
    $iReviewCount = $arResult['PROPERTIES']['vote_count']['VALUE'];

if (!empty($arResult['OFFERS'])) {
    $iOffersCount = count($arResult['OFFERS']);

    foreach ($arResult['OFFERS'] as &$arOffer) {
        $arCurrentPrice = $arOffer['ITEM_PRICES'][0];

        if (empty($arMinPrice) || $arMinPrice['PRICE'] > $arCurrentPrice['PRICE'])
            $arMinPrice = $arCurrentPrice;

        if (empty($arMaxPrice) || $arMaxPrice['PRICE'] < $arCurrentPrice['PRICE'])
            $arMaxPrice = $arCurrentPrice;
    }

    unset($arOffer);
}

if ($arResult['CAN_BUY'])
    $sAvailability = 'InStock';

    $sDescription = '';

    $sDescription = strip_tags($arResult['DETAIL_TEXT']);
    
    if (empty($sDescription))
        $sDescription = strip_tags($arResult['PREVIEW_TEXT']);

    $sDescription = strip_tags($arResult['DETAIL_TEXT']);

    if (empty($sDescription))
        $sDescription = strip_tags($arResult['PREVIEW_TEXT']);
    
    // Убираем переносы строк, табы и лишние пробелы для JSON-LD
    if (!empty($sDescription)) {
        $sDescription = preg_replace('/[\r\n\t]+/', ' ', $sDescription); // заменяем переносы на пробелы
        $sDescription = preg_replace('/\s+/', ' ', $sDescription); // схлопываем множественные пробелы
        $sDescription = trim($sDescription); // убираем пробелы по краям
    }
    
    // Очистка и экранирование для JSON-LD
    if (!empty($sDescription)) {
        // Убираем лишние пробелы и переносы строк
        $sDescription = preg_replace('/\s+/', ' ', $sDescription);
        $sDescription = trim($sDescription);
        // Обрезаем до разумной длины (Google рекомендует до 5000 символов)
        if (mb_strlen($sDescription) > 1000) {
            $sDescription = mb_substr($sDescription, 0, 997) . '...';
        }
    }
    
    // Функция для безопасного экранирования в JSON
    function jsonSafe($string) {
        $string = str_replace(['\\', '"', "\n", "\r", "\t"], ['\\\\', '\\"', '\\n', '\\r', '\\t'], $string);
        return $string;
    }

    $selectedOffer = false;
    if( isset($_GET['offer']) && !empty($_GET['offer']) ){
        if (!empty($arResult['OFFERS'])){
            foreach ($arResult['OFFERS'] as $key => $offer){
                if( $offer['ID'] == $_GET['offer'] ){
                    $selectedOffer = $offer;
                }
            }
        }
    }
    elseif (!empty($arResult['OFFERS'])){
        $selectedOffer = $arResult['OFFERS'][0]['ID'];
    }
?>
<div itemscope itemtype="http://schema.org/Product" style="display: none">
    <meta itemprop="name" content="<?= $arResult['NAME'] ?>" />
    <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>" />
    <?php // Для материала ?>
    <?php if (!empty($arResult['PROPERTIES']['MATERIAL']['VALUE'])) { ?>
        <meta itemprop="material" content="<?= Html::encode($arResult['PROPERTIES']['MATERIAL']['VALUE']) ?>" />
        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
            <span itemprop="name">Материал</span>
            <span itemprop="value"><?= Html::encode($arResult['PROPERTIES']['MATERIAL']['VALUE']) ?></span>
        </div>
    <?php } ?>
    <?php if ($selectedOffer) { ?>
        <?php if (!empty($selectedOffer['GALLERY']['VALUES'])) { ?>

            <?php foreach ($selectedOffer['GALLERY']['VALUES'] as $image) { ?>
                <img loading="lazy" itemprop="image" src="<?= $image['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
            <?php } ?>
        <?php }
        ?>
    <?php } else { ?>
        <img loading="lazy" itemprop="image" src="<?= SITE_TEMPLATE_PATH ?>/images/picture.missing.png" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
    <?php } ?>
    <?php if (!empty($sDescription)) { ?>
        <meta itemprop="description" content="<?= Html::encode($sDescription) ?>" />
    <?php } ?>
    <?php if (!empty($arFields['BRAND'])) { ?>
        <meta itemprop="brand" content="<?= $arFields['BRAND']['VALUE']['NAME'] ?>" />
    <?php } ?>
    <div itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?= $fRatingValue ?>" />
        <meta itemprop="reviewCount" content="<?= $iReviewCount ?>" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="worstRating" content="0" />
    </div>

    <?php if ($selectedOffer) {
        ?>
        <?php // Наличие ?>
        <?php if ($selectedOffer['CAN_BUY']) { ?>
            <link itemprop="availability" href="http://schema.org/InStock" />
        <?php } else { ?>
            <link itemprop="availability" href="http://schema.org/OutOfStock" />
        <?php } ?>

        <?php // Цвет (важно для вариаций!) ?>
        <?php
            $colorValue = is_array($selectedOffer['PROPERTIES']['TSVET']['VALUE'])
                ? implode(', ', $selectedOffer['PROPERTIES']['TSVET']['VALUE'])
                : $selectedOffer['PROPERTIES']['TSVET']['VALUE'];
        ?>
        <meta itemprop="color" content="<?= Html::encode($colorValue) ?>" />

        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
            <span itemprop="name">Цвет</span>
            <span itemprop="value"><?= Html::encode($colorValue) ?></span>
        </div>
        <?php // Размер (если есть) ?>
        <?php if (!empty($selectedOffer['PROPERTIES']['RAZMER']['VALUE'])) {
            $sizeValue = is_array($selectedOffer['PROPERTIES']['RAZMER']['VALUE'])
                ? implode(', ', $selectedOffer['PROPERTIES']['RAZMER']['VALUE'])
                : $selectedOffer['PROPERTIES']['RAZMER']['VALUE'];
            ?>
            <meta itemprop="size" content="<?= Html::encode($sizeValue) ?>" />
        <?php } ?>

        <?php // Все остальные свойства предложения как additionalProperty ?>
        <?php if (!empty($arResult['DISPLAY_PROPERTIES'])) { ?>
            <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {

                $propertyValue = '';
                if (!empty($arProperty['DISPLAY_VALUE'])) {
                    $propertyValue = is_array($arProperty['DISPLAY_VALUE'])
                        ? implode(', ', $arProperty['DISPLAY_VALUE'])
                        : $arProperty['DISPLAY_VALUE'];
                } elseif (!empty($arProperty['VALUE'])) {
                    $propertyValue = is_array($arProperty['VALUE'])
                        ? implode(', ', $arProperty['VALUE'])
                        : $arProperty['VALUE'];
                }

                $propertyValue = strip_tags($propertyValue);

                if (!empty($propertyValue)) {
                    ?>
                    <?php
                    if( $arProperty['ID'] == 354 ){
                        ?>
                        <meta itemprop="sku" content="<?= $selectedOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'] ?>" />
                        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                            <span itemprop="name">Артикул</span>
                            <span itemprop="value"><?= Html::encode($propertyValue) ?></span>
                        </div>
                        <?php
                    }
                    else{
                        ?>
                        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                            <span itemprop="name"><?= Html::encode($arProperty['NAME']) ?></span>
                            <span itemprop="value"><?= Html::encode($propertyValue) ?></span>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }
            } ?>
        <?php } ?>

        <?php if (!empty($selectedOffer['DISPLAY_PROPERTIES'])) { ?>
        <?php foreach ($selectedOffer['DISPLAY_PROPERTIES'] as $arProperty) {

            $propertyValue = '';
            if (!empty($arProperty['DISPLAY_VALUE'])) {
                $propertyValue = is_array($arProperty['DISPLAY_VALUE'])
                    ? implode(', ', $arProperty['DISPLAY_VALUE'])
                    : $arProperty['DISPLAY_VALUE'];
            } elseif (!empty($arProperty['VALUE'])) {
                $propertyValue = is_array($arProperty['VALUE'])
                    ? implode(', ', $arProperty['VALUE'])
                    : $arProperty['VALUE'];
            }

            $propertyValue = strip_tags($propertyValue);

            if (!empty($propertyValue)) {
                ?>
                <?php
                if( $arProperty['ID'] == 354 ){
                    ?>
                    <meta itemprop="sku" content="<?= $selectedOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'] ?>" />
                    <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                        <span itemprop="name">Артикул</span>
                        <span itemprop="value"><?= Html::encode($propertyValue) ?></span>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                        <span itemprop="name"><?= Html::encode($arProperty['NAME']) ?></span>
                        <span itemprop="value"><?= Html::encode($propertyValue) ?></span>
                    </div>
                    <?php
                }
                ?>
                <?php
            }
        } ?>
    <?php } ?>

    <?php
    }
    ?>


    <div itemscope itemprop="offers" itemtype="http://schema.org/AggregateOffer">
        <meta itemprop="lowPrice" content="<?= $arMinPrice['PRICE'] ?>" />
        <meta itemprop="highPrice" content="<?= $arMaxPrice['PRICE'] ?>" />
        <meta itemprop="offerCount" content="<?= $iOffersCount ?>" />
        <meta itemprop="priceCurrency" content="<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>" />
        <?php foreach ($arResult['OFFERS'] as &$arOffer) {  ?>
            <div itemscope itemprop="offers" itemtype="http://schema.org/Offer">
                <?php // Название варианта ?>
                <?php if (!empty($arOffer['NAME'])) { ?>
                    <meta itemprop="name" content="<?= Html::encode($arOffer['NAME']) ?>" />
                <?php } ?>

                <?php // Базовые свойства ?>
                <meta itemprop="price" content="<?= $arOffer['ITEM_PRICES'][0]['PRICE'] ?>" />
                <meta itemprop="priceCurrency" content="<?= $arOffer['ITEM_PRICES'][0]['CURRENCY'] ?>" />


                <?php // Единица измерения ?>
                <?php if (!empty($arOffer['CATALOG_MEASURE_NAME'])) { ?>
                    <meta itemprop="hasMeasurement" content="<?= Html::encode($arOffer['CATALOG_MEASURE_NAME']) ?>" />
                <?php } ?>

                <?php // Наличие ?>
                <?php if ($arOffer['CAN_BUY']) { ?>
                    <link itemprop="availability" href="http://schema.org/InStock" />
                <?php } else { ?>
                    <link itemprop="availability" href="http://schema.org/OutOfStock" />
                <?php } ?>
            </div>
        <?php } ?>
        <?php unset($arOffer); ?>
    </div>
</div>
