<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 */

// Массив ID свойств для вывода
$additionalPropertyIds = [312, 318, 319, 321, 322, 323, 325, 326, 327, 328];

// Собираем свойства для отображения
$additionalProperties = [];

foreach ($additionalPropertyIds as $propertyId) {
    $propertyId = (string)$propertyId; // Приводим к строке для сравнения
    
    // Ищем свойство в массиве свойств
    foreach ($arResult['PROPERTIES'] as $propertyCode => $property) {
        if (isset($property['ID']) && $property['ID'] == $propertyId) {
            // Проверяем, есть ли значение у свойства
            if (!empty($property['VALUE']) || (isset($property['VALUE']) && $property['VALUE'] !== false)) {
                $additionalProperties[$propertyCode] = $property;
            }
            break;
        }
    }
}

// Альтернативный способ поиска по ID
if (empty($additionalProperties)) {
    foreach ($arResult['PROPERTIES'] as $propertyCode => $property) {
        if (isset($property['ID']) && in_array($property['ID'], $additionalPropertyIds)) {
            if (!empty($property['VALUE']) || (isset($property['VALUE']) && $property['VALUE'] !== false)) {
                $additionalProperties[$propertyCode] = $property;
            }
        }
    }
}

// Если свойства найдены, выводим их
if (!empty($additionalProperties)):
?>
<div class="service-description-list_items">
    <?php 
    $svgColor = "#000000"; // Укажите нужный цвет SVG
    
    foreach ($additionalProperties as $propertyCode => $arProperty):
        // Определяем значение для отображения
        $displayValue = '';
        
        if (is_array($arProperty['VALUE'])) {
            // Если значение - массив (например, список или файлы)
            $displayValue = implode(', ', $arProperty['VALUE']);
        } else {
            // Одиночное значение
            $displayValue = $arProperty['VALUE'];
            
            // Обработка специальных типов свойств
            if ($arProperty['PROPERTY_TYPE'] == 'L' && isset($arProperty['VALUE_ENUM'])) {
                $displayValue = $arProperty['VALUE_ENUM'];
            } elseif ($arProperty['PROPERTY_TYPE'] == 'F' && !empty($arProperty['FILE_VALUE'])) {
                // Для файлов
                if (is_array($arProperty['FILE_VALUE'])) {
                    $displayValue = '<a href="' . $arProperty['FILE_VALUE']['SRC'] . '" target="_blank">' . $arProperty['FILE_VALUE']['ORIGINAL_NAME'] . '</a>';
                } else {
                    $displayValue = 'Файл';
                }
            } elseif ($arProperty['PROPERTY_TYPE'] == 'E' && !empty($arProperty['LINK_ELEMENT_VALUE'])) {
                // Для привязок к элементам
                if (is_array($arProperty['LINK_ELEMENT_VALUE'])) {
                    $names = [];
                    foreach ($arProperty['LINK_ELEMENT_VALUE'] as $element) {
                        $names[] = $element['NAME'];
                    }
                    $displayValue = implode(', ', $names);
                } else {
                    $displayValue = $arProperty['LINK_ELEMENT_VALUE']['NAME'];
                }
            }
        }
        
        // Пропускаем пустые значения
        if (empty($displayValue) && $displayValue !== '0' && $displayValue !== 0) {
            continue;
        }
    ?>
    <div class="service-description-list--item">
        <div class="service-description-list--item_icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#80E0A7" stroke-width="2" stroke-linecap="round"/>
                <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#80E0A7" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="service-description-list--item--data">
            <?php if (!empty($arProperty['NAME'])): ?>
                <div class="service-description-list--item_title">
                    <span><?=htmlspecialchars($arProperty['NAME'])?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($displayValue)): ?>
                <div class="service-description-list--item_description">
                    <p><?=is_string($displayValue) ? htmlspecialchars($displayValue) : $displayValue?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="service-description-list_empty">
    Нет дополнительных характеристик для отображения
</div>
<?php endif; ?>

<?
/*
// Массив ID свойств для вывода
$additionalPropertyIds = [312, 318, 319, 321, 322, 323, 325, 326, 327, 328];

// Собираем свойства для отображения
$additionalProperties = [];

foreach ($additionalPropertyIds as $propertyId) {
    $propertyId = (string)$propertyId; // Приводим к строке для сравнения
    
    // Ищем свойство в массиве свойств
    foreach ($arResult['PROPERTIES'] as $propertyCode => $property) {
        if (isset($property['ID']) && $property['ID'] == $propertyId) {
            // Проверяем, есть ли значение у свойства
            if (!empty($property['VALUE']) || (isset($property['VALUE']) && $property['VALUE'] !== false)) {
                $additionalProperties[$propertyCode] = $property;
            }
            break;
        }
    }
}

// Альтернативный способ поиска по ID
if (empty($additionalProperties)) {
    foreach ($arResult['PROPERTIES'] as $propertyCode => $property) {
        if (isset($property['ID']) && in_array($property['ID'], $additionalPropertyIds)) {
            if (!empty($property['VALUE']) || (isset($property['VALUE']) && $property['VALUE'] !== false)) {
                $additionalProperties[$propertyCode] = $property;
            }
        }
    }
}

// Если свойства найдены, выводим их
if (!empty($additionalProperties)):
    
    // Разделяем свойства по принципу: 1-е в левый, 2-е в правый, 3-е в левый, 4-е в правый и т.д.
    $leftColumnProperties = [];
    $rightColumnProperties = [];
    $index = 0;
    
    foreach ($additionalProperties as $propertyCode => $arProperty) {
        // Определяем значение для отображения
        $displayValue = '';
        
        if (is_array($arProperty['VALUE'])) {
            // Если значение - массив (например, список или файлы)
            $displayValue = implode(', ', $arProperty['VALUE']);
        } else {
            // Одиночное значение
            $displayValue = $arProperty['VALUE'];
            
            // Обработка специальных типов свойств
            if ($arProperty['PROPERTY_TYPE'] == 'L' && isset($arProperty['VALUE_ENUM'])) {
                $displayValue = $arProperty['VALUE_ENUM'];
            } elseif ($arProperty['PROPERTY_TYPE'] == 'F' && !empty($arProperty['FILE_VALUE'])) {
                // Для файлов
                if (is_array($arProperty['FILE_VALUE'])) {
                    $displayValue = '<a href="' . $arProperty['FILE_VALUE']['SRC'] . '" target="_blank">' . $arProperty['FILE_VALUE']['ORIGINAL_NAME'] . '</a>';
                } else {
                    $displayValue = 'Файл';
                }
            } elseif ($arProperty['PROPERTY_TYPE'] == 'E' && !empty($arProperty['LINK_ELEMENT_VALUE'])) {
                // Для привязок к элементам
                if (is_array($arProperty['LINK_ELEMENT_VALUE'])) {
                    $names = [];
                    foreach ($arProperty['LINK_ELEMENT_VALUE'] as $element) {
                        $names[] = $element['NAME'];
                    }
                    $displayValue = implode(', ', $names);
                } else {
                    $displayValue = $arProperty['LINK_ELEMENT_VALUE']['NAME'];
                }
            }
        }
        
        // Пропускаем пустые значения
        if (empty($displayValue) && $displayValue !== '0' && $displayValue !== 0) {
            continue;
        }
        
        // Формируем массив для отображения
        $propertyItem = [
            'data' => $arProperty,
            'display_value' => $displayValue,
            'type' => 'property'
        ];
        
        // Чередуем между столбцами: четные в левый, нечетные в правый
        if ($index % 2 == 0) {
            $leftColumnProperties[] = $propertyItem;
        } else {
            $rightColumnProperties[] = $propertyItem;
        }
        
        $index++;
    }
    
?>
<div class="catalog-element-properties catalog-element-additional-properties">
    <div class="catalog-element-properties-detail">
        <div class="catalog-element-properties-container">
            <!-- ЛЕВЫЙ СТОЛБЕЦ -->
            <?php if (!empty($leftColumnProperties)): ?>
            <div class="catalog-element-properties-column catalog-element-properties-column-left">
                <?php foreach ($leftColumnProperties as $item): ?>
                    <?php 
                    $arProperty = $item['data'];
                    $displayValue = $item['display_value'];
                    ?>
                    <div class="catalog-element-properties-detail-item" 
                         data-type="<?= htmlspecialcharsbx($item['type']) ?>">
                        <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div class="catalog-element-properties-detail-item-name">
                                    <?= htmlspecialcharsbx($arProperty['NAME']) ?>
                                </div>
                            </div>
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div class="catalog-element-properties-detail-item-value">
                                    <?= $displayValue ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- ПРАВЫЙ СТОЛБЕЦ -->
            <?php if (!empty($rightColumnProperties)): ?>
            <div class="catalog-element-properties-column catalog-element-properties-column-right">
                <?php foreach ($rightColumnProperties as $item): ?>
                    <?php 
                    $arProperty = $item['data'];
                    $displayValue = $item['display_value']; // Исправлено: было displayValue, стало display_value
                    ?>
                    <div class="catalog-element-properties-detail-item" 
                         data-type="<?= htmlspecialcharsbx($item['type']) ?>">
                        <div class="intec-grid intec-grid-a-v-center intec-grid-i-4 intec-grid-500-wrap flex-justify-content-between">
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div class="catalog-element-properties-detail-item-name">
                                    <?= htmlspecialcharsbx($arProperty['NAME']) ?>
                                </div>
                            </div>
                            <div class="intec-grid-item-2 intec-grid-item-500-1">
                                <div class="catalog-element-properties-detail-item-value">
                                    <?= $displayValue ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="catalog-element-properties-empty">
    Нет дополнительных характеристик для отображения
</div>
<?php endif; */?>