<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Type;

/**
 * @var array $arResult
 */


global $USER;

if (!$USER->IsAuthorized() || !$USER->IsAdmin()) {
    return;
}

$prop = $arResult['PROPERTIES'] ?? [];

// Собираем все торговые предложения по ID
$offersById = [];
foreach ($arResult['OFFERS'] ?? [] as $offer) {
    $offersById[$offer['ID']] = [
        'TSVET' => $offer['PROPERTIES']['TSVET']['VALUE'] ?? '',
        'RAZMER' => $offer['PROPERTIES']['RAZMER']['VALUE'] ?? '',
        'ARTIKUL' => $offer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'] ?? ''
    ];
}

// Определяем "текущее" предложение для начального отображения — используем первое
$currentOffer = null;
if (!empty($arResult['OFFERS'])) {
    $firstOffer = reset($arResult['OFFERS']);
    $currentOffer = $offersById[$firstOffer['ID']] ?? [];
}

// Значения по умолчанию (из текущего/первого предложения)
$TSVET = $currentOffer['TSVET'] ?? '';
$RAZMER = $currentOffer['RAZMER'] ?? '';
$current_artikul = $currentOffer['ARTIKUL'] ?? '';

// Статические свойства товара (не зависят от предложения)
$PANTONE = $prop['PANTONE']['VALUE'] ?? '';
$VES_KG = $prop['VES_KG']['VALUE'] ?? '';
$MATERIAL = $prop['MATERIAL']['VALUE'] ?? '';
$OBEM = $prop['OBEM_SM3']['VALUE'] ?? '';
$VID_NANESENIA = $prop['APPLICATION_TYPES']['VALUE'] ?? '';

// Упаковка
$UPAKOVKA = $prop['UPAKOVKA']['VALUE'] ?? '';
$VES_BRUTTO_KG_T = $prop['VES_BRUTTO_KG_T']['VALUE'] ?? '';
$OBEM_M3_T = $prop['OBEM_M3_T']['VALUE'] ?? '';
$KOLICHESTVO_V_UP_SHT_T = $prop['KOLICHESTVO_V_UP_SHT_T']['VALUE'] ?? '';
$RAZMERY_UPAKOVKI_SM_T = $prop['RAZMERY_UPAKOVKI_SM_T']['VALUE'] ?? '';

// Формируем левый столбец
$one_column = [];
if ($TSVET !== '') {
    $one_column[] = [
        'value' => $TSVET,
        'name' => 'Цвет',
        'data_offer_property' => 'TSVET'
    ];
}
if ($PANTONE !== '') {
    $one_column[] = [
        'value' => $PANTONE,
        'name' => $prop['PANTONE']['NAME'] ?? 'Pantone'
    ];
}
if ($VES_KG !== '') {
    $one_column[] = [
        'value' => $VES_KG,
        'name' => $prop['VES_KG']['NAME'] ?? 'Вес, кг'
    ];
}
if ($MATERIAL !== '') {
    $one_column[] = [
        'value' => $MATERIAL,
        'name' => $prop['MATERIAL']['NAME'] ?? 'Материал'
    ];
}
if ($OBEM !== '') {
    $one_column[] = [
        'value' => $OBEM,
        'name' => $prop['OBEM_SM3']['NAME'] ?? 'Объём, см³'
    ];
}
if ($VID_NANESENIA !== '') {
    $one_column[] = [
        'value' => $VID_NANESENIA,
        'name' => $prop['APPLICATION_TYPES']['NAME'] ?? 'Вид нанесения'
    ];
}

// Правый столбец
$two_column = [];
if ($UPAKOVKA !== '') {
    $two_column[] = [
        'value' => $UPAKOVKA,
        'name' => $prop['UPAKOVKA']['NAME'] ?? 'Упаковка'
    ];
}
if ($VES_BRUTTO_KG_T !== '') {
    $two_column[] = [
        'value' => $VES_BRUTTO_KG_T,
        'name' => $prop['VES_BRUTTO_KG_T']['NAME'] ?? 'Вес брутто, кг'
    ];
}
if ($OBEM_M3_T !== '') {
    $two_column[] = [
        'value' => $OBEM_M3_T,
        'name' => $prop['OBEM_M3_T']['NAME'] ?? 'Объём, м³'
    ];
}
if ($KOLICHESTVO_V_UP_SHT_T !== '') {
    $two_column[] = [
        'value' => $KOLICHESTVO_V_UP_SHT_T,
        'name' => $prop['KOLICHESTVO_V_UP_SHT_T']['NAME'] ?? 'Кол-во в упаковке, шт'
    ];
}
if ($RAZMERY_UPAKOVKI_SM_T !== '') {
    $two_column[] = [
        'value' => $RAZMERY_UPAKOVKI_SM_T,
        'name' => $prop['RAZMERY_UPAKOVKI_SM_T']['NAME'] ?? 'Размеры упаковки, см'
    ];
}

// Преобразуем в JSON для JavaScript
$all_offers_json = !empty($offersById) ? json_encode($offersById, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) : '{}';

if (empty($one_column) && empty($two_column)) {
    return;
}
?>

<script>
window.productOffersById = <?= $all_offers_json ?>;
</script>

<div class="catalog-element-properties-detail" id="catalog-properties-detail">
    <div class="catalog-element-properties-container">

        <?php if (!empty($one_column)): ?>
        <div class="catalog-element-properties-column catalog-element-properties-column-left">
            <?php foreach ($one_column as $item): ?>
                <?php if (!empty($item['name']) && !empty($item['value'])): ?>
                    <?php
                    $dataAttr = '';
                    if (!empty($item['data_offer_property'])) {
                        $dataAttr = 'data-offer-property="' . htmlspecialchars($item['data_offer_property']) . '"';
                    }
                    ?>
                    <div class="catalog-element-properties-detail-item" <?= $dataAttr ?>>
                        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue" class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div itemprop="name" class="catalog-element-properties-detail-item-name">
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </div>
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div  itemprop="value" class="catalog-element-properties-detail-item-value">
                                    <?= htmlspecialchars($item['value']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($two_column)): ?>
        <div class="catalog-element-properties-column catalog-element-properties-column-right">
            <?php foreach ($two_column as $item): ?>
                <?php if (!empty($item['name']) && !empty($item['value'])): ?>
                    <div class="catalog-element-properties-detail-item">
                        <div itemscope itemprop="additionalProperty" itemtype="http://schema.org/PropertyValue" class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                            <div itemprop="name" class="intec-grid-item-2 intec-grid-item-500-1">
                                <div class="catalog-element-properties-detail-item-name">
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </div>
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div itemprop="value" class="catalog-element-properties-detail-item-value">
                                    <?= htmlspecialchars($item['value']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateOfferProperties(offer) {
        if (!offer || typeof offer !== 'object') return;

        // Обновляем свойства, зависящие от предложения
        const dynamicProps = document.querySelectorAll('[data-offer-property]');
        dynamicProps.forEach(function(el) {
            const propKey = el.getAttribute('data-offer-property');
            const valueEl = el.querySelector('.catalog-element-properties-detail-item-value');
            if (valueEl) {
                valueEl.textContent = offer[propKey] || '—';
            }
        });

        // Обновляем артикул
        const articleValueEl = document.querySelector('[data-role="article.value"]');
        if (articleValueEl) {
            articleValueEl.textContent = offer.ARTIKUL || '—';
        }
    }

    // Слушаем событие смены торгового предложения от Bitrix
    if (typeof BX !== 'undefined' && typeof BX.addCustomEvent === 'function') {
        BX.addCustomEvent('onCatalogElementChangeOffer', function(offerId) {
            const offer = window.productOffersById && window.productOffersById[offerId];
            if (offer) {
                updateOfferProperties(offer);
            }
        });
    }
});
</script>



<?

/*
// ID свойств, которые нужно пропустить
$excludedIds = [354, 617, 675];

// Собираем все элементы для вывода в одном массиве
$allProperties = [];

// 1. Добавляем основные свойства
foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
    // Пропускаем исключенные ID
    if (in_array($arProperty['ID'], $excludedIds)) {
        continue;
    }
    $allProperties[] = [
        'type' => 'property',
        'data' => $arProperty
    ];
}

// 2. Добавляем офферы и дополнительные свойства
if ($arVisual['OFFERS']['PROPERTIES']['SHOW'] && !empty($arResult['FIELDS']['OFFERS'])) {
    foreach ($arResult['FIELDS']['OFFERS'] as $sKey => $arOffer) {
        foreach ($arOffer as $arProperty) {
            // Пропускаем исключенные ID
            if (in_array($arProperty['ID'], $excludedIds)) {
                continue;
            }
            
            if ($arProperty['ID'] == 616) {
                $date = new DateTime($arProperty['VALUE']);
                $arProperty['VALUE'] = $date->format('d.m.Y');
            }
            $allProperties[] = [
                'type' => 'offer',
                'offer_key' => $sKey,
                'data' => $arProperty
            ];
        }

        // Добавляем дополнительные свойства для каждого оффера
        if (isset($arResult['PRODUCT']['WEIGHT']) && $arResult['PRODUCT']['WEIGHT'] > 0) {
            $allProperties[] = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Вес',
                    'VALUE' => $arResult['PRODUCT']['WEIGHT'] . ' гр.'
                ]
            ];
        }
        if (isset($arResult['PRODUCT']['WIDTH']) && $arResult['PRODUCT']['WIDTH'] > 0) {
            $allProperties[] = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Ширина',
                    'VALUE' => $arResult['PRODUCT']['WIDTH']
                ]
            ];
        }
        if (isset($arResult['PRODUCT']['LENGTH']) && $arResult['PRODUCT']['LENGTH'] > 0) {
            $allProperties[] = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Длина',
                    'VALUE' => $arResult['PRODUCT']['LENGTH']
                ]
            ];
        }
        if (isset($arResult['PRODUCT']['HEIGHT']) && $arResult['PRODUCT']['HEIGHT'] > 0) {
            $allProperties[] = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Высота',
                    'VALUE' => $arResult['PRODUCT']['HEIGHT']
                ]
            ];
        }
    }
}

?>
<div class="catalog-element-properties-detail">
    <div class="catalog-element-properties-container">
        <div class="catalog-element-properties-column catalog-element-properties-column-left">
            <?php 
            // Левый столбец - нечетные индексы (0, 2, 4...)
            foreach ($allProperties as $index => $item) {
                if ($index % 2 === 0) {
                    $arProperty = $item['data'];
            ?>
                <div class="catalog-element-properties-detail-item" 
                     data-type="<?= $item['type'] ?>" 
                     <?= isset($item['offer_key']) ? 'data-offer="' . $item['offer_key'] . '"' : '' ?>>
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-name">
                                <?= $arProperty['NAME'] ?>
                            </div>
                        </div>
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-value">
                                <?php 
                                $value = $arProperty['DISPLAY_VALUE'] ?? $arProperty['VALUE'] ?? '';
                                if (Type::isArray($value)) { 
                                    echo implode(', ', $value);
                                } else { 
                                    echo $value;
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>
        </div>
        <div class="catalog-element-properties-column catalog-element-properties-column-right">
            <?php 
            // Правый столбец - четные индексы (1, 3, 5...)
            foreach ($allProperties as $index => $item) {
                if ($index % 2 === 1) {
                    $arProperty = $item['data'];
            ?>
                <div class="catalog-element-properties-detail-item" 
                     data-type="<?= $item['type'] ?>" 
                     <?= isset($item['offer_key']) ? 'data-offer="' . $item['offer_key'] . '"' : '' ?>>
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-name">
                                <?= $arProperty['NAME'] ?>
                            </div>
                        </div>
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-value">
                                <?php 
                                $value = $arProperty['DISPLAY_VALUE'] ?? $arProperty['VALUE'] ?? '';
                                if (Type::isArray($value)) { 
                                    echo implode(', ', $value);
                                } else { 
                                    echo $value;
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>
        </div>
    </div>
</div>
<?php unset($arProperty, $sKey, $arOffer) ?>
*/?>