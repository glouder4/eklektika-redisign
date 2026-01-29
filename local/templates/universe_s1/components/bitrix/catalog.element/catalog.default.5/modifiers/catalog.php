<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


if (!empty($arResult['OFFERS'])) {
    $arResult['CAN_BUY'] = false;

    $arPrices = null;
    $arPrice = null;

    foreach ($arResult['OFFERS'] as &$arOffer) {
        if (!empty($arOffer['ITEM_PRICES']))
            if ($arPrices === null || $arPrices[0]['PRICE'] > $arOffer['ITEM_PRICES'][0]['PRICE'])
                $arPrices = $arOffer['ITEM_PRICES'];

        if (!empty($arOffer['MIN_PRICE']))
            if ($arPrice === null || $arPrice['DISCOUNT_VALUE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE'])
                $arPrice = $arOffer['MIN_PRICE'];


        if( isset($_GET['offer']) && !empty($_GET['offer']) ){
            if( $arOffer['ID'] == $_GET['offer'] ){
                //$selectedOffer = $arOffer;
                $arResult['PRODUCT']['WEIGHT'] = $arOffer['PRODUCT']['WEIGHT'];
            }
        }

        unset($arOffer);
    }
    if( !isset($_GET['offer']) ){
        $arResult['PRODUCT']['WEIGHT'] = $arResult['OFFERS'][0]['PRODUCT']['WEIGHT'];
    }

    $arResult['MIN_PRICE'] = $arPrice;
    $arResult['ITEM_PRICES'] = $arPrices;

    unset($arPrice);
    unset($arPrices);
}