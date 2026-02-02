<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// ID свойств, которые нужно пропустить
$excludedIds = [354, 617, 675, 312, 318, 319, 321, 322, 323, 325, 326, 327, 328];

// Порядок свойств для левого столбца
$leftColumnIds = [348, 679, 464, 275, 278, 359];

// Порядок свойств для правого столбца  
$rightColumnIds = [277, 280, 281, 282, 466];

// Подготавливаем массивы для свойств
$leftColumnProperties = [];
$rightColumnProperties = [];

// Собираем основные свойства
$displayPropertiesById = [];

foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) {
    // Пропускаем исключенные ID
    if (in_array($arProperty['ID'], $excludedIds)) {
        continue;
    }
    $displayPropertiesById[$arProperty['ID']] = [
        'type' => 'property',
        'data' => $arProperty
    ];
}

// Собираем свойства офферов (если они есть)
$offerPropertiesById = [];

if ($arVisual['OFFERS']['PROPERTIES']['SHOW'] && !empty($arResult['FIELDS']['OFFERS'])) {
    foreach ($arResult['FIELDS']['OFFERS'] as $sKey => $arOffer) {
        foreach ($arOffer as $arProperty) {
            // Пропускаем исключенные ID
            if (in_array($arProperty['ID'], $excludedIds)) {
                continue;
            }
            
            // Форматируем дату для ID 616 (если нужно)
            if ($arProperty['ID'] == 616 && !empty($arProperty['VALUE'])) {
                try {
                    $date = new DateTime($arProperty['VALUE']);
                    $arProperty['VALUE'] = $date->format('d.m.Y');
                    if (isset($arProperty['DISPLAY_VALUE'])) {
                        $arProperty['DISPLAY_VALUE'] = $date->format('d.m.Y');
                    }
                } catch (Exception $e) {
                    // Оставляем значение как есть в случае ошибки
                }
            }
            
            // Сохраняем свойство оффера
            if (!isset($offerPropertiesById[$arProperty['ID']])) {
                $offerPropertiesById[$arProperty['ID']] = [];
            }
            
            $offerPropertiesById[$arProperty['ID']][$sKey] = [
                'type' => 'offer',
                'offer_key' => $sKey,
                'data' => $arProperty
            ];
        }
    }
}

// РАСПРЕДЕЛЕНИЕ СВОЙСТВ ПО ЛЕВОМУ СТОЛБЦУ
foreach ($leftColumnIds as $propertyId) {
    // 1. Проверяем основные свойства
    if (isset($displayPropertiesById[$propertyId])) {
        $leftColumnProperties[] = $displayPropertiesById[$propertyId];
        unset($displayPropertiesById[$propertyId]);
    }
    
    // 2. Проверяем свойства офферов
    if (isset($offerPropertiesById[$propertyId])) {
        foreach ($offerPropertiesById[$propertyId] as $offerProperty) {
            $leftColumnProperties[] = $offerProperty;
        }
        unset($offerPropertiesById[$propertyId]);
    }
}

// РАСПРЕДЕЛЕНИЕ СВОЙСТВ ПО ПРАВОМУ СТОЛБЦУ
foreach ($rightColumnIds as $propertyId) {
    // 1. Проверяем основные свойства
    if (isset($displayPropertiesById[$propertyId])) {
        $rightColumnProperties[] = $displayPropertiesById[$propertyId];
        unset($displayPropertiesById[$propertyId]);
    }
    
    // 2. Проверяем свойства офферов
    if (isset($offerPropertiesById[$propertyId])) {
        foreach ($offerPropertiesById[$propertyId] as $offerProperty) {
            $rightColumnProperties[] = $offerProperty;
        }
        unset($offerPropertiesById[$propertyId]);
    }
}

// ОСТАВШИЕСЯ СВОЙСТВА - распределяем равномерно
// Основные свойства
$remainingProperties = array_values($displayPropertiesById);
foreach ($remainingProperties as $index => $property) {
    if ($index % 2 === 0) {
        $leftColumnProperties[] = $property;
    } else {
        $rightColumnProperties[] = $property;
    }
}

// Свойства офферов (которые не вошли в списки)
foreach ($offerPropertiesById as $propertyId => $offerProperties) {
    foreach ($offerProperties as $offerProperty) {
        if (count($leftColumnProperties) <= count($rightColumnProperties)) {
            $leftColumnProperties[] = $offerProperty;
        } else {
            $rightColumnProperties[] = $offerProperty;
        }
    }
}

// ДОБАВЛЯЕМ ДОПОЛНИТЕЛЬНЫЕ СВОЙСТВА (Вес, габариты и т.д.)
if ($arVisual['OFFERS']['PROPERTIES']['SHOW'] && !empty($arResult['FIELDS']['OFFERS'])) {
    foreach ($arResult['FIELDS']['OFFERS'] as $sKey => $arOffer) {
        // Вес
        if (isset($arResult['PRODUCT']['WEIGHT']) && $arResult['PRODUCT']['WEIGHT'] > 0) {
            $additionalProperty = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Вес нетто',
                    'VALUE' => $arResult['PRODUCT']['WEIGHT'] . ' гр.',
                    'DISPLAY_VALUE' => $arResult['PRODUCT']['WEIGHT'] . ' гр.'
                ]
            ];

            $insertPosition = null;
            
            foreach ($leftColumnProperties as $index => $property) {
                if (isset($property['data']['ID']) && $property['data']['ID'] == 464) {
                    $insertPosition = $index + 1; // Вставляем ПОСЛЕ 464
                    break;
                }
            }
            
            // Если не нашли ID 464, вставляем на 4-ю позицию по умолчанию
            if ($insertPosition === null) {
                $insertPosition = 3; // 4-я позиция (индекс 3)
            }
            
            // Вставляем свойство
            array_splice($leftColumnProperties, $insertPosition, 0, [$additionalProperty]);
            
            break; // Добавляем только один раз
            // Распределяем в столбец с меньшим количеством свойств
            /*if (count($leftColumnProperties) <= count($rightColumnProperties)) {
                $leftColumnProperties[] = $additionalProperty;
            } else {
                $rightColumnProperties[] = $additionalProperty;
            }*/
        }
        /*
        // Ширина
        if (isset($arResult['PRODUCT']['WIDTH']) && $arResult['PRODUCT']['WIDTH'] > 0) {
            $additionalProperty = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Ширина',
                    'VALUE' => $arResult['PRODUCT']['WIDTH'],
                    'DISPLAY_VALUE' => $arResult['PRODUCT']['WIDTH']
                ]
            ];
            
            if (count($leftColumnProperties) <= count($rightColumnProperties)) {
                $leftColumnProperties[] = $additionalProperty;
            } else {
                $rightColumnProperties[] = $additionalProperty;
            }
        }
        
        // Длина
        if (isset($arResult['PRODUCT']['LENGTH']) && $arResult['PRODUCT']['LENGTH'] > 0) {
            $additionalProperty = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Длина',
                    'VALUE' => $arResult['PRODUCT']['LENGTH'],
                    'DISPLAY_VALUE' => $arResult['PRODUCT']['LENGTH']
                ]
            ];
            
            if (count($leftColumnProperties) <= count($rightColumnProperties)) {
                $leftColumnProperties[] = $additionalProperty;
            } else {
                $rightColumnProperties[] = $additionalProperty;
            }
        }
        
        // Высота
        if (isset($arResult['PRODUCT']['HEIGHT']) && $arResult['PRODUCT']['HEIGHT'] > 0) {
            $additionalProperty = [
                'type' => 'additional',
                'offer_key' => $sKey,
                'data' => [
                    'NAME' => 'Высота',
                    'VALUE' => $arResult['PRODUCT']['HEIGHT'],
                    'DISPLAY_VALUE' => $arResult['PRODUCT']['HEIGHT']
                ]
            ];
            
            if (count($leftColumnProperties) <= count($rightColumnProperties)) {
                $leftColumnProperties[] = $additionalProperty;
            } else {
                $rightColumnProperties[] = $additionalProperty;
            }
        }
        */
    }
}
?>

<div class="catalog-element-properties-detail">
    <div class="catalog-element-properties-container">
        <!-- ЛЕВЫЙ СТОЛБЕЦ -->
        <div class="catalog-element-properties-column catalog-element-properties-column-left">
            <?php foreach ($leftColumnProperties as $item): ?>
                <? 
                if ($item['data']['VALUE'] === 0) {
                    continue;
                } 
                ?>
                <?php 
                $arProperty = $item['data'];
                // Получаем значение для отображения
                $value = isset($arProperty['DISPLAY_VALUE']) ? $arProperty['DISPLAY_VALUE'] : 
                        (isset($arProperty['VALUE']) ? $arProperty['VALUE'] : '');

                // Пропускаем пустые значения, 0, null и false
                if (empty($value) && $value !== '0' && $value !== 0) {
                    continue;
                }
                
                // Также пропускаем, если это строка "0" без других символов
                if ($value === '0' || $value === 0) {
                    continue;
                }

                $displayValue = '';
                
                if (is_array($value)) {
                    $displayValue = implode(', ', $value);
                } else {
                    $displayValue = $value;
                }
                ?>
                <div class="catalog-element-properties-detail-item" 
                     data-type="<?= htmlspecialcharsbx($item['type']) ?>" 
                     <?php if (isset($item['offer_key'])): ?>
                        data-offer="<?= htmlspecialcharsbx($item['offer_key']) ?>"
                     <?php endif; ?>>
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-name">
                                <?= htmlspecialcharsbx($arProperty['NAME']) ?>
                            </div>
                        </div>
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-value">
                                <?= htmlspecialcharsbx($displayValue) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- ПРАВЫЙ СТОЛБЕЦ -->
        <div class="catalog-element-properties-column catalog-element-properties-column-right">
            <?php foreach ($rightColumnProperties as $item): ?>
                <?php 
                $arProperty = $item['data'];
                // Получаем значение для отображения
                $value = isset($arProperty['DISPLAY_VALUE']) ? $arProperty['DISPLAY_VALUE'] : 
                        (isset($arProperty['VALUE']) ? $arProperty['VALUE'] : '');
              
                // Пропускаем пустые значения, 0, null и false
                if (empty($value) && $value !== '0' && $value !== 0) {
                    continue;
                }
                
                // Также пропускаем, если это строка "0" без других символов
                if ($value === '0' || $value === 0) {
                    continue;
                }

                $displayValue = '';
                
                if (is_array($value)) {
                    $displayValue = implode(', ', $value);
                } else {
                    $displayValue = $value;
                }
                ?>
                <div class="catalog-element-properties-detail-item" 
                     data-type="<?= htmlspecialcharsbx($item['type']) ?>" 
                     <?php if (isset($item['offer_key'])): ?>
                        data-offer="<?= htmlspecialcharsbx($item['offer_key']) ?>"
                     <?php endif; ?>>
                    <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-name">
                                <?= htmlspecialcharsbx($arProperty['NAME']) ?>
                            </div>
                        </div>
                        <div class="intec-grid-item-2 intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-item-value">
                                <?= htmlspecialcharsbx($displayValue) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php unset(
    $arProperty, 
    $sKey, 
    $arOffer, 
    $item, 
    $value, 
    $displayValue,
    $displayPropertiesById,
    $offerPropertiesById,
    $remainingProperties,
    $leftColumnProperties,
    $rightColumnProperties,
    $additionalProperty
); ?>
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
<?php unset($arProperty, $sKey, $arOffer) */?>
