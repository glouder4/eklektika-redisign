<?php
if (!function_exists('renderPropertyItem')) {
    /**
     * Рендерит элемент свойства
     * 
     * @param array $property Массив свойства из $arResult['PROPERTIES']
     * @param string $svgColor Цвет SVG (по умолчанию '#97e5b7')
     * @return void
     */
    function renderPropertyItem($property, $svgColor = '#97e5b7')
    {
        if (empty($property['VALUE'])) {
            return;
        }
        ?>
        <div class="service-description-list--item">
            <div class="service-description-list--item_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                    <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="<?=htmlspecialchars($svgColor)?>" stroke-width="2" stroke-linecap="round"/>
                    <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="<?=htmlspecialchars($svgColor)?>" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="service-description-list--item--data">
                <?php if (!empty($property['NAME'])): ?>
                    <div class="service-description-list--item_title">
                        <span><?=htmlspecialchars($property['NAME'])?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($property['VALUE'])): ?>
                    <div class="service-description-list--item_description">
                        <p><?=htmlspecialchars($property['VALUE'])?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}