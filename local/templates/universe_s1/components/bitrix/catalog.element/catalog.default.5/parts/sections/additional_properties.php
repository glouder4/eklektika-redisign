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
<?php endif; ?>