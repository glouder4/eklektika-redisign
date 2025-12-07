<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arCodes
 * @var array $arVisual
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$component->applyTemplateModifications();

// Сортируем товары и офферы согласно порядку из поисковика
if (!empty($GLOBALS['OS_SEARCH_RESULT']) && !empty($arResult['ITEMS'])) {
    // Создаем массив с порядком: ключ - ITEM_ID (ID оффера), значение - позиция в поиске
    $searchOrder = [];
    foreach ($GLOBALS['OS_SEARCH_RESULT'] as $index => $searchItem) {
        if (!empty($searchItem['ITEM_ID'])) {
            $searchOrder[$searchItem['ITEM_ID']] = $index;
        }
    }

    // Сначала сортируем товары по минимальному порядку их офферов из поиска
    uasort($arResult['ITEMS'], function ($item1, $item2) use ($searchOrder) {
        $minOrder1 = 999999;
        $minOrder2 = 999999;

        // Находим минимальный порядок среди офферов первого товара
        if (!empty($item1['OFFERS'])) {
            foreach ($item1['OFFERS'] as $offer) {
                $offerId = isset($offer['ID']) ? $offer['ID'] : 0;
                if (isset($searchOrder[$offerId]) && $searchOrder[$offerId] < $minOrder1) {
                    $minOrder1 = $searchOrder[$offerId];
                }
            }
        }

        // Находим минимальный порядок среди офферов второго товара
        if (!empty($item2['OFFERS'])) {
            foreach ($item2['OFFERS'] as $offer) {
                $offerId = isset($offer['ID']) ? $offer['ID'] : 0;
                if (isset($searchOrder[$offerId]) && $searchOrder[$offerId] < $minOrder2) {
                    $minOrder2 = $searchOrder[$offerId];
                }
            }
        }

        return $minOrder1 - $minOrder2;
    });

    // Затем сортируем офферы внутри каждого товара по порядку из поиска
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!empty($arItem['OFFERS'])) {
            uasort($arItem['OFFERS'], function ($offer1, $offer2) use ($searchOrder) {
                $id1 = isset($offer1['ID']) ? $offer1['ID'] : 0;
                $id2 = isset($offer2['ID']) ? $offer2['ID'] : 0;

                $order1 = isset($searchOrder[$id1]) ? $searchOrder[$id1] : 999999;
                $order2 = isset($searchOrder[$id2]) ? $searchOrder[$id2] : 999999;

                return $order1 - $order2;
            });
        }
    }
    unset($arItem);
}
else {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!empty($arItem['OFFERS']))
            uasort($arItem['OFFERS'], function ($arOffer1, $arOffer2) {
                return Type::toInteger($arOffer1['SORT']) - Type::toInteger($arOffer2['SORT']);
            });
    }
}

unset($arItem);

$arResult['SKU_PROPS'] = ArrayHelper::getValue($arResult, ['SKU_PROPS', $arResult['IBLOCK_ID']], []);
$arSKUProps = [];

foreach ($arResult['SKU_PROPS'] as $arSKUProperty) {
    $arOffersProperty = [
        'id' => $arSKUProperty['ID'],
        'code' => 'P_'.$arSKUProperty['CODE'],
        'name' => $arSKUProperty['NAME'],
        'type' => $arSKUProperty['SHOW_MODE'] === 'TEXT' ? 'text' : 'picture',
        'values' => []
    ];

    foreach ($arSKUProperty['VALUES'] as $arValue) {
        $arOffersProperty['values'][] = [
            'id' => !empty($arValue['XML_ID']) ? $arValue['XML_ID'] : $arValue['ID'],
            'name' => $arValue['NAME'],
            'stub' => $arValue['NA'] == 1,
            'picture' => !empty($arValue['PICT']) ? $arValue['PICT']['SRC'] : null
        ];
    }

    $arSKUProps[] = $arOffersProperty;
}

$arResult['SKU_PROPS'] = $arSKUProps;

unset($arSKUProps);