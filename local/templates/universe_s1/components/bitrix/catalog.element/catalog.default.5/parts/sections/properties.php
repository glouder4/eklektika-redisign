<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

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