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

?>
<div itemscope itemtype="http://schema.org/Product" style="display: none">
    <meta itemprop="name" content="<?= $arResult['NAME'] ?>" />
    <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>" />
    <meta itemprop="lowPrice" content="<?= $arMinPrice['PRICE'] ?>" />
    <meta itemprop="highPrice" content="<?= $arMaxPrice['PRICE'] ?>" />
    <meta itemprop="offerCount" content="<?= $iOffersCount ?>" />
    <meta itemprop="priceCurrency" content="<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>" />
    <?php // Для материала ?>
    <?php if (!empty($arResult['PROPERTIES']['MATERIAL']['VALUE'])) { ?>
        <meta itemprop="material" content="<?= Html::encode($arResult['PROPERTIES']['MATERIAL']['VALUE']) ?>" />
    <?php } ?>
    <?php if (!empty($arResult['GALLERY']['VALUES'])) { ?>
        <?php foreach ($arResult['GALLERY']['VALUES'] as $image) { ?>
            <img loading="lazy" itemprop="image" src="<?= $image['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
        <?php } ?>
    <?php } elseif (!empty($arResult['OFFERS'])) { ?>

        <?php if (!empty($arResult['OFFERS'][0]['GALLERY']['VALUES'])) { ?>
            <?php foreach ($arResult['OFFERS'] as $offer) {  ?>
                <img loading="lazy" itemprop="image" src="<?= $offer['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
            <?php } ?>

            <?php foreach ($arResult['OFFERS'][0]['GALLERY']['VALUES'] as $image) { ?>
                <img loading="lazy" itemprop="image" src="<?= $image['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
            <?php } ?>
        <?php }
            else{
        ?>
            <img loading="lazy" itemprop="image" src="<?= $arResult['OFFERS'][0]['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
        <?php
            }
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
    <?php if (!empty($arResult['OFFERS'])) { ?>
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

                <?php // Изображения варианта ?>
                <?php if (!empty($arOffer['GALLERY']['VALUES'])) { ?>
                    <?php foreach ($arOffer['GALLERY']['VALUES'] as $image) { ?>
                        <img loading="lazy" itemprop="image" src="<?= $image['SRC'] ?>" alt="<?= $arOffer['NAME'] ?>" title="<?= $arOffer['NAME'] ?>" />
                    <?php } ?>
                <?php } elseif (!empty($arOffer['DETAIL_PICTURE']['SRC'])) { ?>
                    <img loading="lazy" itemprop="image" src="<?= $arOffer['DETAIL_PICTURE']['SRC'] ?>" alt="<?= $arOffer['NAME'] ?>" title="<?= $arOffer['NAME'] ?>" />
                <?php } ?>

                <?php // Базовые свойства ?>
                <meta itemprop="price" content="<?= $arOffer['ITEM_PRICES'][0]['PRICE'] ?>" />
                <meta itemprop="priceCurrency" content="<?= $arOffer['ITEM_PRICES'][0]['CURRENCY'] ?>" />

                <?php // Артикул ?>
                <?php if (!empty($arOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'])) { ?>
                    <meta itemprop="sku" content="<?= Html::encode($arOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE']) ?>" />
                <?php } ?>

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

                <?php // Цвет (важно для вариаций!) ?>
                <?php if (!empty($arOffer['PROPERTIES']['TSVET']['VALUE'])) {
                    $colorValue = is_array($arOffer['PROPERTIES']['TSVET']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['TSVET']['VALUE'])
                        : $arOffer['PROPERTIES']['TSVET']['VALUE'];
                ?>
                    <meta itemprop="color" content="<?= Html::encode($colorValue) ?>" />
                <?php } ?>

                <?php // Размер (если есть) ?>
                <?php if (!empty($arOffer['PROPERTIES']['RAZMER']['VALUE'])) {
                    $sizeValue = is_array($arOffer['PROPERTIES']['RAZMER']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['RAZMER']['VALUE'])
                        : $arOffer['PROPERTIES']['RAZMER']['VALUE'];
                ?>
                    <meta itemprop="size" content="<?= Html::encode($sizeValue) ?>" />
                <?php } ?>

                <?php // Материал (если есть) ?>
                <?php if (!empty($arOffer['PROPERTIES']['MATERIAL']['VALUE'])) {
                    $materialValue = is_array($arOffer['PROPERTIES']['MATERIAL']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['MATERIAL']['VALUE'])
                        : $arOffer['PROPERTIES']['MATERIAL']['VALUE'];
                ?>
                    <meta itemprop="material" content="<?= Html::encode($materialValue) ?>" />
                <?php } ?>

                <?php // Все остальные свойства предложения как additionalProperty ?>
                <?php if (!empty($arOffer['DISPLAY_PROPERTIES'])) { ?>
                    <?php foreach ($arOffer['DISPLAY_PROPERTIES'] as $arProperty) {
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

                        if (!empty($propertyValue)) {
                    ?>
                        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                            <meta itemprop="name" content="<?= Html::encode($arProperty['NAME']) ?>" />
                            <meta itemprop="value" content="<?= Html::encode($propertyValue) ?>" />
                        </div>
                    <?php
                        }
                    } ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php unset($arOffer); ?>
    </div>
<?php } else { ?>
        <div itemscope itemprop="offers" itemtype="http://schema.org/Offer">
            <?php if (!empty($arResult['GALLERY']['VALUES'])) { ?>
                <?php foreach ($arResult['GALLERY']['VALUES'] as $image) { ?>
                    <img loading="lazy" itemprop="image" src="<?= $image['SRC'] ?>" alt="<?= $arResult['NAME'] ?>" title="<?= $arResult['NAME'] ?>" />
                <?php } ?>
            <?php } ?>
            <meta itemprop="price" content="<?= $arResult['ITEM_PRICES'][0]['PRICE'] ?>" />
            <meta itemprop="priceCurrency" content="<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>" />
            <meta itemprop="hasMeasurement" content="<?= $arResult['CATALOG_MEASURE_NAME'] ?>" />
            <link itemprop="availability" href="<?= 'http://schema.org/'.$sAvailability ?>" />
            <link itemprop="url" href="<?= $arResult['DETAIL_PAGE_URL'] ?>" />
        </div>
    <?php } ?>
    <?php if (!empty($arFields['ARTICLE']['VALUE'])) { ?>
        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
            <meta itemprop="name" content="<?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ARTICLE_NAME') ?>" />
            <meta itemprop="value" content="<?= $arFields['ARTICLE']['VALUE'] ?>" />
        </div>
    <?php } ?>
    <?php if (!empty($arResult['DISPLAY_PROPERTIES'])) {   ?>
        <?php foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProperty) { ?>
            <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue">
                <meta itemprop="name" content="<?= $arProperty['NAME'] ?>" />
                <meta itemprop="value" content="<?= $arProperty['VALUE'] ?>" />
            </div>
        <?php }
            unset($arProperty)
        ?>
    <?php } ?>

    <script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "<?= jsonSafe($arResult['NAME']) ?>",
    "description": "<?= jsonSafe($sDescription) ?>",
    <?php if (!empty($arResult['GALLERY']['VALUES'])) { ?>
    "image": [
        <?php
            $images = [];
            foreach ($arResult['GALLERY']['VALUES'] as $image) {
                $images[] = '"' . addslashes($image['SRC']) . '"';
            }
            echo implode(',', $images);
            ?>
    ],
    <?php } ?>
    <?php if (!empty($arResult['OFFERS'])) { ?>
    "offers": {
        "@type": "AggregateOffer",
        "lowPrice": "<?= $arMinPrice['PRICE'] ?>",
        "highPrice": "<?= $arMaxPrice['PRICE'] ?>",
        "priceCurrency": "<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>",
        "offerCount": "<?= $iOffersCount ?>",
        "offers": [
            <?php
            $offerItems = [];
            foreach ($arResult['OFFERS'] as $arOffer) {
                $offerData = [
                    '"@type": "Offer"',
                    '"name": "' . addslashes($arOffer['NAME']) . '"',
                    '"price": "' . $arOffer['ITEM_PRICES'][0]['PRICE'] . '"',
                    '"priceCurrency": "' . $arOffer['ITEM_PRICES'][0]['CURRENCY'] . '"',
                ];

                // SKU
                if (!empty($arOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'])) {
                    $offerData[] = '"sku": "' . addslashes($arOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE']) . '"';
                }

                // Availability
                $offerData[] = '"availability": "' . ($arOffer['CAN_BUY'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') . '"';

                // Цвет
                if (!empty($arOffer['PROPERTIES']['TSVET']['VALUE'])) {
                    $colorValue = is_array($arOffer['PROPERTIES']['TSVET']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['TSVET']['VALUE'])
                        : $arOffer['PROPERTIES']['TSVET']['VALUE'];
                    $offerData[] = '"color": "' . addslashes($colorValue) . '"';
                }

                // Размер
                if (!empty($arOffer['PROPERTIES']['RAZMER']['VALUE'])) {
                    $sizeValue = is_array($arOffer['PROPERTIES']['RAZMER']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['RAZMER']['VALUE'])
                        : $arOffer['PROPERTIES']['RAZMER']['VALUE'];
                    $offerData[] = '"size": "' . addslashes($sizeValue) . '"';
                }

                // Материал
                if (!empty($arOffer['PROPERTIES']['MATERIAL']['VALUE'])) {
                    $materialValue = is_array($arOffer['PROPERTIES']['MATERIAL']['VALUE'])
                        ? implode(', ', $arOffer['PROPERTIES']['MATERIAL']['VALUE'])
                        : $arOffer['PROPERTIES']['MATERIAL']['VALUE'];
                    $offerData[] = '"material": "' . addslashes($materialValue) . '"';
                }

                // Все дополнительные свойства
                if (!empty($arOffer['DISPLAY_PROPERTIES'])) {
                    $additionalProps = [];
                    foreach ($arOffer['DISPLAY_PROPERTIES'] as $prop) {
                        $propValue = '';
                        if (!empty($prop['DISPLAY_VALUE'])) {
                            $propValue = is_array($prop['DISPLAY_VALUE'])
                                ? implode(', ', $prop['DISPLAY_VALUE'])
                                : $prop['DISPLAY_VALUE'];
                        } elseif (!empty($prop['VALUE'])) {
                            $propValue = is_array($prop['VALUE'])
                                ? implode(', ', $prop['VALUE'])
                                : $prop['VALUE'];
                        }
                        if (!empty($propValue)) {
                            $additionalProps[] = '{"@type": "PropertyValue", "name": "' . addslashes($prop['NAME']) . '", "value": "' . addslashes($propValue) . '"}';
                        }
                    }
                    if (!empty($additionalProps)) {
                        $offerData[] = '"additionalProperty": [' . implode(',', $additionalProps) . ']';
                    }
                }

                $offerItems[] = '{' . implode(',', $offerData) . '}';
            }
            echo implode(',', $offerItems);
            ?>
        ]
    },
    <?php } else { ?>
    "offers": {
        "@type": "Offer",
        "price": "<?= $arResult['ITEM_PRICES'][0]['PRICE'] ?>",
        "priceCurrency": "<?= $arResult['ITEM_PRICES'][0]['CURRENCY'] ?>",
        "availability": "<?= 'https://schema.org/' . $sAvailability ?>",
        "url": "<?= $arResult['DETAIL_PAGE_URL'] ?>"
    },
    <?php } ?>
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $fRatingValue ?>",
        "reviewCount": "<?= $iReviewCount ?>",
        "bestRating": "5",
        "worstRating": "0"
    }
    <?php
        // Дополнительные свойства товара
        if (!empty($arResult['DISPLAY_PROPERTIES'])) {
            $additionalProperties = [];
            foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
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
                if (!empty($propertyValue)) {
                    $additionalProperties[] = '{"@type": "PropertyValue", "name": "' . addslashes($arProperty['NAME']) . '", "value": "' . addslashes($propertyValue) . '"}';
                }
            }
            if (!empty($additionalProperties)) {
                echo ',"additionalProperty": [' . implode(',', $additionalProperties) . ']';
            }
        }
        ?>
}
</script>
</div>
