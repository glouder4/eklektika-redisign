<?
use Bitrix\Catalog\StoreProductTable;
/*
global $USER;
if ($USER->IsAuthorized() && $USER->IsAdmin()):
*/
// Объявляем функцию только если она еще не существует
if (!function_exists('universalReservedSearch')) {
    function universalReservedSearch($productId) {
        $reserved = 0;
        
        if (CModule::IncludeModule("catalog")) {
            $rsStore = CCatalogStoreProduct::GetList(
                array(),
                array('PRODUCT_ID' => $productId),
                false,
                false,
                array('RESERVED')
            );
            
            while ($arStore = $rsStore->Fetch()) {
                $reserved += $arStore['RESERVED'];
            }
            
            if ($reserved == 0) {
                $productInfo = CCatalogProduct::GetByID($productId);
                if ($productInfo && isset($productInfo['QUANTITY_RESERVED'])) {
                    $reserved = $productInfo['QUANTITY_RESERVED'];
                }
            }
        }
        
        return $reserved;
    }
}

if (!function_exists('calculateOfferStock')) {
    function calculateOfferStock($offer) {
        // Получаем зарезервированное количество
        $reserved = universalReservedSearch($offer['ID']);
        
        // Получаем количество на складе Москва (ID = 7)
        $skladMoscow = 0;
        if (CModule::IncludeModule("catalog")) {
            $result = StoreProductTable::getList([
                'select' => ['AMOUNT'],
                'filter' => [
                    '=PRODUCT_ID' => $offer['ID'],
                    '=STORE_ID' => 7
                ]
            ]);
            if ($row = $result->fetch()) {
                $skladMoscow = (float)$row['AMOUNT'];
            }
        }
        
        // Остаток в пути
        $v_puti = $offer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE'] ?? 0;
        
        // Всего с учетом резерва
        $vsego_s_rezervom = $skladMoscow + $reserved;
        
        $result = [
            'sklad_moscow' => $skladMoscow,  // теперь тут реальное значение со склада
            'v_puti' => $v_puti,
            'ostatok_bez_rezerva' => $skladMoscow, // тоже заменили на склад
            'vsego_s_rezervom' => $vsego_s_rezervom
        ];
        
        return $result;
    }
}
?>
<?php if($arItem['OFFERS'] && is_array($arItem['OFFERS'])) { ?>
    <!-- Контейнер для динамической информации о складе и доставке -->
    <div id="offer-dynamic-info-<?=$arItem['ID']?>" class="offer-dynamic-info">
        <!-- По умолчанию показываем информацию для первого оффера -->
        <?php 
        $firstOffer = $arItem['OFFERS'][0] ?? null;
        if ($firstOffer) {
            $stockInfo = calculateOfferStock($firstOffer);
            
            if ($stockInfo['sklad_moscow'] > 0) { ?>
                <div class="catalog-section-item-prop">
                    <div class="catalog-section-item-prop-name">
                        Склад Москва:
                    </div>
                    <div class="catalog-section-item-prop-value">
                        <?=$stockInfo['sklad_moscow']?> шт.
                    </div>
                </div>
            <?php } ?>
            
            <?php if ($stockInfo['v_puti'] > 0) { ?>
                <div class="catalog-section-item-prop">
                    <div class="catalog-section-item-prop-name">
                        В пути:
                    </div>
                    <div class="catalog-section-item-prop-value">
                        <?=$stockInfo['v_puti']?> шт.
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- Скрытый блок со всеми данными офферов -->
    <div id="all-offers-data-<?=$arItem['ID']?>" style="display:none;">
        <?php foreach($arItem['OFFERS'] as $index => $offer) {
            $colorId = $offer['PROPERTIES']['TSVET']['VALUE_ENUM_ID'] ?? '';
            $stockInfo = calculateOfferStock($offer);
            
            if ($colorId) {
                echo '<div class="offer-data" 
                        data-color-id="'.$colorId.'" 
                        data-sklad="'.htmlspecialchars($stockInfo['sklad_moscow']).'" 
                        data-time-puti="'.htmlspecialchars($stockInfo['v_puti']).'"></div>';
            }
        } ?>
    </div>

    <!-- JavaScript для динамического обновления информации -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var itemId = '<?=$arItem['ID']?>';
        
        // Функция для обновления информации о складе и доставке
        function updateOfferInfo(colorId) {
            console.log('Обновляем информацию для товара', itemId, 'цвет', colorId);
            
            var container = document.getElementById('offer-dynamic-info-' + itemId);
            var allOffersData = document.getElementById('all-offers-data-' + itemId);
            
            if (!container || !allOffersData) {
                console.log('Не найден контейнер для товара:', itemId);
                return;
            }
            
            // Ищем данные для выбранного цвета
            var offerData = allOffersData.querySelector('.offer-data[data-color-id="' + colorId + '"]');
            
            var html = '';
            
            if (offerData) {
                var sklad = offerData.getAttribute('data-sklad');
                var timePut = offerData.getAttribute('data-time-puti');
                
                console.log('Найдены данные:', {sklad: sklad, timePut: timePut});
                
                // Проверяем, что есть остаток на складе
                if (sklad && sklad !== '' && parseInt(sklad) > 0) {
                    html += '<div class="catalog-section-item-prop">';
                    html += '<div class="catalog-section-item-prop-name">Склад Москва:</div>';
                    html += '<div class="catalog-section-item-prop-value">' + sklad + ' шт.</div>';
                    html += '</div>';
                }
                
                // Проверяем, что есть товар в пути
                if (timePut && timePut !== '' && parseInt(timePut) > 0) {
                    html += '<div class="catalog-section-item-prop">';
                    html += '<div class="catalog-section-item-prop-name">В пути:</div>';
                    html += '<div class="catalog-section-item-prop-value">' + timePut + ' шт.</div>';
                    html += '</div>';
                }
            }
            // Обновление артикула
            var articulDisplay = document.getElementById('articul-display-' + itemId);
            var allArtikulsData = document.getElementById('all-offers-artikuls-' + itemId);

            if (articulDisplay && allArtikulsData) {
                var artikulElement = allArtikulsData.querySelector('.offer-artikul[data-color-id="' + colorId + '"]');
                var newArtikul = artikulElement ? artikulElement.getAttribute('data-artikul') : '';
                
                if (newArtikul) {
                    articulDisplay.innerHTML = 'Артикул: ' + newArtikul;
                    articulDisplay.style.display = 'block';
                } else {
                    articulDisplay.style.display = 'none';
                }
            }
            
            container.innerHTML = html;
        }
        
        // Ждем полной загрузки DOM и затем инициализируем
        function initializeColorSwitcher() {
            var itemElement = document.querySelector('[data-item-id="' + itemId + '"]');
            if (!itemElement) {
                // Пробуем найти по классу
                var containers = document.querySelectorAll('.catalog-section-item');
                containers.forEach(function(container) {
                    if (container.querySelector('#offer-dynamic-info-' + itemId)) {
                        itemElement = container;
                    }
                });
            }
            
            if (itemElement) {
                console.log('Найден элемент товара:', itemId);
                
                // Используем делегирование событий для надежности
                itemElement.addEventListener('click', function(e) {
                    var colorElement = e.target.closest('[data-role="item.property.value"]');
                    if (colorElement) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var colorId = colorElement.getAttribute('data-value');
                        console.log('Клик по цвету:', colorId);
                        
                        if (colorId && colorId !== '0') {
                            updateOfferInfo(colorId);
                        }
                    }
                });
                
                // Инициализация при загрузке - показываем информацию для выбранного цвета
                setTimeout(function() {
                    var selectedColor = itemElement.querySelector('[data-role="item.property.value"][data-state="selected"]');
                    if (selectedColor) {
                        var colorId = selectedColor.getAttribute('data-value');
                        console.log('Выбранный цвет при загрузке:', colorId);
                        if (colorId && colorId !== '0') {
                            updateOfferInfo(colorId);
                        }
                    } else {
                        // Если нет выбранного цвета, используем первый доступный
                        var firstColor = itemElement.querySelector('[data-role="item.property.value"]');
                        if (firstColor) {
                            var colorId = firstColor.getAttribute('data-value');
                            console.log('Первый доступный цвет:', colorId);
                            if (colorId && colorId !== '0') {
                                updateOfferInfo(colorId);
                            }
                        }
                    }
                }, 500);
            } else {
                console.log('Не найден элемент товара с ID:', itemId);
            }
        }
        
        // Запускаем инициализацию
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeColorSwitcher);
        } else {
            initializeColorSwitcher();
        }
    });
    </script>
<?php } ?>
<?/*
endif;
*/?>
<?/*
global $USER;
if ($USER->IsAuthorized() && $USER->IsAdmin()):

    
echo '<pre>';
print_r($arResult['MARKS']['VALUES']);
echo'</pre>';
endif;
?>
<?php if($arItem['OFFERS'] && is_array($arItem['OFFERS'])) { ?>
    <!-- Контейнер для динамической информации о складе и доставке -->
    <div id="offer-dynamic-info-<?=$arItem['ID']?>" class="offer-dynamic-info">
        <!-- По умолчанию показываем информацию для первого оффера -->
        <?php 
        $firstOffer = $arItem['OFFERS'][0] ?? null;
        if ($firstOffer && ($firstOffer['PROPERTIES']['POSTAVSHCHIK']['VALUE'] || $firstOffer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE'])) { ?>
            <?php if ($firstOffer['PROPERTIES']['POSTAVSHCHIK']['VALUE']) { ?>
                <?php $sklad = $firstOffer['PROPERTIES']['POSTAVSHCHIK']['VALUE']; ?>
                <div class="catalog-section-item-prop">
                    <div class="catalog-section-item-prop-name">
                        Склад Москва:
                    </div>
                    <div class="catalog-section-item-prop-value">
                        <?=$sklad?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($firstOffer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE']) { ?>
                <?php $time_puti = $firstOffer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE']; ?>
                <div class="catalog-section-item-prop">
                    <div class="catalog-section-item-prop-name">
                        В пути:
                    </div>
                    <div class="catalog-section-item-prop-value">
                        <?=$time_puti?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- Скрытый блок со всеми данными офферов -->
    <div id="all-offers-data-<?=$arItem['ID']?>" style="display:none;">
        <?php foreach($arItem['OFFERS'] as $index => $offer) {
            $colorId = $offer['PROPERTIES']['TSVET']['VALUE_ENUM_ID'] ?? '';
            $sklad = $offer['PROPERTIES']['POSTAVSHCHIK']['VALUE'] ?? '';
            $time_puti = $offer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE'] ?? '';
            
            if ($colorId) {
                echo '<div class="offer-data" 
                        data-color-id="'.$colorId.'" 
                        data-sklad="'.htmlspecialchars($sklad).'" 
                        data-time-puti="'.htmlspecialchars($time_puti).'"></div>';
            }
        } ?>
    </div>

    <!-- JavaScript для динамического обновления информации -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var itemId = '<?=$arItem['ID']?>';
        
        // Функция для обновления информации о складе и доставке
        function updateOfferInfo(colorId) {
            console.log('Обновляем информацию для товара', itemId, 'цвет', colorId);
            
            var container = document.getElementById('offer-dynamic-info-' + itemId);
            var allOffersData = document.getElementById('all-offers-data-' + itemId);
            
            if (!container || !allOffersData) {
                console.log('Не найден контейнер для товара:', itemId);
                return;
            }
            
            // Ищем данные для выбранного цвета
            var offerData = allOffersData.querySelector('.offer-data[data-color-id="' + colorId + '"]');
            
            var html = '';
            
            if (offerData) {
                var sklad = offerData.getAttribute('data-sklad');
                var timePut = offerData.getAttribute('data-time-puti');
                
                console.log('Найдены данные:', {sklad: sklad, timePut: timePut});
                
                if (sklad && sklad !== '') {
                    html += '<div class="catalog-section-item-prop">';
                    html += '<div class="catalog-section-item-prop-name">Склад:</div>';
                    html += '<div class="catalog-section-item-prop-value">' + sklad + '</div>';
                    html += '</div>';
                }
                
                if (timePut && timePut !== '') {
                    html += '<div class="catalog-section-item-prop">';
                    html += '<div class="catalog-section-item-prop-name">В пути:</div>';
                    html += '<div class="catalog-section-item-prop-value">' + timePut + '</div>';
                    html += '</div>';
                }
                
                // Если оба поля пустые - оставляем html пустым (ничего не выводим)
            }
            // Если не нашли данных для цвета - тоже ничего не выводим
            
            container.innerHTML = html;
        }
        
        // Ждем полной загрузки DOM и затем инициализируем
        function initializeColorSwitcher() {
            var itemElement = document.querySelector('[data-item-id="' + itemId + '"]');
            if (!itemElement) {
                // Пробуем найти по классу
                var containers = document.querySelectorAll('.catalog-section-item');
                containers.forEach(function(container) {
                    if (container.querySelector('#offer-dynamic-info-' + itemId)) {
                        itemElement = container;
                    }
                });
            }
            
            if (itemElement) {
                console.log('Найден элемент товара:', itemId);
                
                // Используем делегирование событий для надежности
                itemElement.addEventListener('click', function(e) {
                    var colorElement = e.target.closest('[data-role="item.property.value"]');
                    if (colorElement) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var colorId = colorElement.getAttribute('data-value');
                        console.log('Клик по цвету:', colorId);
                        
                        if (colorId && colorId !== '0') {
                            updateOfferInfo(colorId);
                        }
                    }
                });
                
                // Инициализация при загрузке - показываем информацию для выбранного цвета
                setTimeout(function() {
                    var selectedColor = itemElement.querySelector('[data-role="item.property.value"][data-state="selected"]');
                    if (selectedColor) {
                        var colorId = selectedColor.getAttribute('data-value');
                        console.log('Выбранный цвет при загрузке:', colorId);
                        if (colorId && colorId !== '0') {
                            updateOfferInfo(colorId);
                        }
                    } else {
                        // Если нет выбранного цвета, используем первый доступный
                        var firstColor = itemElement.querySelector('[data-role="item.property.value"]');
                        if (firstColor) {
                            var colorId = firstColor.getAttribute('data-value');
                            console.log('Первый доступный цвет:', colorId);
                            if (colorId && colorId !== '0') {
                                updateOfferInfo(colorId);
                            }
                        }
                    }
                }, 500);
            } else {
                console.log('Не найден элемент товара с ID:', itemId);
            }
        }
        
        // Запускаем инициализацию
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeColorSwitcher);
        } else {
            initializeColorSwitcher();
        }
    });
    </script>
<?php } */?>