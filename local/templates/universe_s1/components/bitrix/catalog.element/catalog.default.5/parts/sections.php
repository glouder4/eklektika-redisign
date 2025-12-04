<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array arFields
 */
$arSections = [
    'DESCRIPTION' => [
        'ID' => 'description',
        'SHOW' => false,
        'TYPE' => 'print',
        'NAME' => $arVisual['DESCRIPTION']['DETAIL']['NAME'],
        'VALUE' => null
    ],
    'PROPERTIES' => [
        'ID' => 'properties',
        'SHOW' => $arVisual['PROPERTIES']['DETAIL']['SHOW'],
        'TYPE' => 'file',
        'NAME' => 'Описание'/*$arVisual['PROPERTIES']['DETAIL']['NAME']*/,
        'VALUE' => __DIR__.'/sections/properties.php'
    ],
    'ACCESSORIES' => [
        'ID' => 'accessories',
        'SHOW' => $arVisual['ACCESSORIES']['SHOW'] && $arResult['FIELDS']['ACCESSORIES']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['ACCESSORIES']['NAME'],
        'VALUE' => __DIR__.'/sections/accessories.php',
        'VIEW' => $arVisual['ACCESSORIES']['VIEW'],
        'LINK' => null
    ],
    'STORES' => [
        'ID' => 'stores',
        'SHOW' => $arVisual['STORES']['USE'] && $arVisual['STORES']['POSITION'] === 'content' && ($arResult['SKU']['VIEW'] === 'dynamic' || !$bOffers && $arResult['SKU']['VIEW'] === 'list'),
        'TYPE' => 'file',
        'NAME' => $arVisual['STORES']['NAME'],
        'VALUE' => __DIR__.'/sections/stores.php'
    ],
    'DOCUMENTS' => [
        'ID' => 'documents',
        'SHOW' => $arFields['DOCUMENTS']['SHOW'] && $arVisual['DOCUMENTS']['POSITION'] === 'content',
        'TYPE' => 'file',
        'NAME' => $arVisual['DOCUMENTS']['NAME'],
        'VALUE' => __DIR__.'/sections/documents.php'
    ],
	'DOC_DOCUMENTS' => [
        'ID' => 'doc_documents',
        'SHOW' => $arResult['PROPERTIES']['PROD_LIST_DOCS']['VALUE'],
        'TYPE' => 'file',
        'NAME' => 'Нанесение',
        'VALUE' => __DIR__.'/sections/prod_documents.php'
    ],
    'VIDEO' => [
        'ID' => 'video',
        'SHOW' => $arFields['VIDEO']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['VIDEO']['NAME'],
        'VALUE' => __DIR__.'/sections/video.php'
    ],
    'ARTICLES' => [
        'ID' => 'articles',
        'SHOW' => $arFields['ARTICLES']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['ARTICLES']['NAME'],
        'VALUE' => __DIR__.'/sections/articles.php'
    ],
    'REVIEWS' => [
        'ID' => 'reviews',
        'SHOW' => $arResult['REVIEWS']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arResult['REVIEWS']['NAME'],
        'VALUE' => __DIR__.'/sections/reviews.php'
    ],
    /*'ADDITIONAL_PROPERTIES' => [
        'ID' => 'additional_properties',
        'SHOW' => true, // Можно настроить условие показа
        'TYPE' => 'file',
        'NAME' => 'Дополнительные характеристики', // Название таба
        'VALUE' => __DIR__.'/sections/additional_properties.php'
    ],*/
    'BUY' => [
        'ID' => 'buy',
        'SHOW' => $arVisual['INFORMATION']['BUY']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['INFORMATION']['BUY']['NAME'],
        'VALUE' => __DIR__.'/sections/information.buy.php'
    ],
    'PAYMENT' => [
        'ID' => 'payment',
        'SHOW' => $arVisual['INFORMATION']['PAYMENT']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['INFORMATION']['PAYMENT']['NAME'],
        'VALUE' => __DIR__.'/sections/information.payment.php'
    ],

    /*'SHIPMENT' => [
        'ID' => 'shipment',
        'SHOW' => $arVisual['INFORMATION']['SHIPMENT']['SHOW'],
        'TYPE' => 'file',
        'NAME' => $arVisual['INFORMATION']['SHIPMENT']['NAME'],
        'VALUE' => __DIR__.'/sections/information.shipment.php'
    ]*/
];

if ($arVisual['DESCRIPTION']['DETAIL']['SHOW']) {
    if (empty($arSections['DESCRIPTION']['NAME']))
        $arSections['DESCRIPTION']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ADDITIONAL_DESCRIPTION');

    if (!empty($arResult['DETAIL_TEXT']))
        $arSections['DESCRIPTION']['VALUE'] = &$arResult['DETAIL_TEXT'];
    else if ($arVisual['DESCRIPTION']['FROM_PREVIEW'] && !empty($arResult['PREVIEW_TEXT']))
        $arSections['DESCRIPTION']['VALUE'] = &$arResult['PREVIEW_TEXT'];

    if (!empty($arSections['DESCRIPTION']['VALUE']))
        $arSections['DESCRIPTION']['SHOW'] = false;
}

if ($arSections['PROPERTIES']['SHOW']) {
    if (empty($arSections['PROPERTIES']['NAME']))
        $arSections['PROPERTIES']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_PROPERTIES_DETAIL_NAME_DEFAULT');
}

if ($arSections['ACCESSORIES']['SHOW']) {
    if (empty($arSections['ACCESSORIES']['NAME']))
        $arSections['ACCESSORIES']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_PRODUCTS_ACCESSORIES_NAME_DEFAULT');

    if ($arSections['ACCESSORIES']['VIEW'] === 'link') {
        if (!empty($arVisual['ACCESSORIES']['LINK']))
            $arSections['ACCESSORIES']['LINK'] = $arVisual['ACCESSORIES']['LINK'];
        else
            $arSections['ACCESSORIES']['SHOW'] = false;
    }
}

if ($arSections['STORES']['SHOW']) {
    if (empty($arSections['STORES']['NAME']))
        $arSections['STORES']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_STORES_NAME_DEFAULT');
}

if ($arSections['DOCUMENTS']['SHOW']) {
    if (empty($arSections['DOCUMENTS']['NAME']))
        $arSections['DOCUMENTS']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_DOCUMENTS_NAME_DEFAULT');
}

if ($arSections['VIDEO']['SHOW']) {
    if (empty($arSections['VIDEO']['NAME']))
        $arSections['VIDEO']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_VIDEO_NAME_DEFAULT');
}

if ($arSections['ARTICLES']['SHOW']) {
    if (empty($arSections['ARTICLES']['NAME']))
        $arSections['ARTICLES']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ARTICLES_NAME_DEFAULT');
}

if ($arSections['REVIEWS']['SHOW']) {
    if (empty($arSections['REVIEWS']['NAME']))
        $arSections['REVIEWS']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_REVIEWS_NAME_DEFAULT');
}

if ($arSections['BUY']['SHOW']) {
    if (empty($arSections['BUY']['NAME']))
        $arSections['BUY']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ADDITIONAL_BUY');
}

if ($arSections['PAYMENT']['SHOW']) {
    if (empty($arSections['PAYMENT']['NAME']))
        $arSections['PAYMENT']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ADDITIONAL_PAYMENT');
}

if ($arSections['SHIPMENT']['SHOW']) {
    if (empty($arSections['SHIPMENT']['NAME']))
        $arSections['SHIPMENT']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_ADDITIONAL_SHIPMENT');
}

?>
<div class="catalog-element-sections-container catalog-element-additional-block" data-role="section">
    <div class="catalog-element-sections">
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-element-sections-tabs '
            ],
            'data' => [
                'role' => 'section.tabs',
                'sticky' => 'nulled'
            ]
        ]) ?>
            <div class="owl-carousel" data-role="scroll" data-navigation="false">
                <?php $bFirst = true ?>
                <?php foreach ($arSections as $arSection) {

                    if (!$arSection['SHOW'] || $arSection['ID'] == "reviews")
                        continue;

                ?>
                    <?php if ($arSection['VIEW'] === 'link') { ?>
                        <?= Html::tag('a', $arSection['NAME'], [
                            'class' => Html::cssClassFromArray([
                                'catalog-element-sections-tab' => true,
                            ], true),
                            'data' => [
                                'id' => $arSection['ID'],
                                'active' => 'false'
                            ],
                            'href' => $arSection['LINK'],
                            'target' => '_blank'
                        ]) ?>

                    <?php } else { ?>
                        <?= Html::tag('div', $arSection['NAME'], [
                            'class' => Html::cssClassFromArray([
                                'catalog-element-sections-tab' => true,
                                'intec-cl' => [
                                    'background' => $bFirst,
                                    'background-light-hover' => $bFirst,
                                    'border' => $bFirst,
                                    'border-light-hover' => $bFirst
                                ],
                            ], true),
                            'data' => [
                                'role' => 'section.tabs.item',
                                'id' => $arSection['ID'],
                                'active' => $bFirst ? 'true' : 'false'
                            ]
                        ]) ?>
                        <?php if ($bFirst) $bFirst = false ?>
                    <?php } ?>
                <?php } ?>

            </div>
        <?= Html::endTag('div') ?>
        <div class="catalog-element-sections-content" data-role="section.content">
            <?php $bFirst = true ?>
            <?php foreach ($arSections as $arSection) {

                if (!$arSection['SHOW'] || $arSection['VIEW'] === 'link')
                    continue;

            ?>
                <?= Html::beginTag('div', [
                    'class' => 'catalog-element-sections-content-item',
                    'data' => [
                        'role' => 'section.content.item',
                        'id' => $arSection['ID'],
                        'active' => $bFirst ? 'true' : 'false'
                    ]
                ]) ?>

                    <?php if ($arSection['TYPE'] === 'print') { ?>
                        <div class="catalog-element-sections-content-text">
                            <?= $arSection['VALUE'] ?>
                        </div>
                    <?php } else if ($arSection['TYPE'] === 'file')
                        include($arSection['VALUE']);
                    ?>
                <?= Html::endTag('div') ?>
                <?php if ($bFirst) $bFirst = false ?>
            <?php } ?>

        </div>
    </div>
</div>

<div class="catalog-element-description catalog-element-additional-block">
    <div class="catalog-element-description-wrapper">
        <div class="catalog-element-additional-block-name desc-detail-name">
            <?= $arSections['DESCRIPTION']['NAME'] ?>
        </div>
        <div class="catalog-element-additional-block-content-text desc-detail-text">
            <?= $arSections['DESCRIPTION']['VALUE'] ?>
        </div>
    </div>
</div>
<?
if (!empty($arResult['PROPERTIES']['DESCRIPTION_PLUS']['VALUE'])) {
    $elementId = $arResult['PROPERTIES']['DESCRIPTION_PLUS']['VALUE'][0];
    
    $res = CIBlockElement::GetList(
        array(),
        array('ID' => $elementId),
        false,
        false,
        array('ID', 'NAME', 'IBLOCK_ID')
    );
    
    if ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProperties = $ob->GetProperties();
        
        // Получаем цвет SVG (предполагаем, что это одиночное свойство)
        $svgColor = $arProperties['COLOR_SVG']['VALUE'] ?? '#000000';
        
        // Получаем массивы названий и описаний
        $names = $arProperties['NAME_PUNKT']['VALUE'] ?? array();
        $descriptions = $arProperties['DESCRIPTION_PUNKT']['VALUE'] ?? array();
        
        // Определяем максимальное количество элементов
        $count = max(count($names), count($descriptions));
        
        if ($count > 0): ?>
            <div class="service-description-list_items">
                <?php for ($i = 0; $i < $count; $i++): 
                    // Берем значения из массивов или пустые строки
                    $name = $names[$i] ?? '';
                    $description = $descriptions[$i] ?? '';
                    
                    // Пропускаем пустые элементы
                    if (empty($name) && empty($description)) {
                        continue;
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
                            <?php if (!empty($name)): ?>
                                <div class="service-description-list--item_title">
                                    <span><?=htmlspecialchars($name)?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($description)): ?>
                                <div class="service-description-list--item_description">
                                    <p><?=htmlspecialchars($description)?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php
        endif;
    }
}
?>
<div class="warning-text-cust">
    Компания YO!merch оставляет за собой право без предварительных уведомлений менять технические параметры и потребительские характеристики представленных товаров и их упаковки
</div>
