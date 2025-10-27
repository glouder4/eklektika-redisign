<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$markerHit = ArrayHelper::getValue($arParams, 'HIT');
$markerSale = ArrayHelper::getValue($arParams, 'SALE');
$markerNew = ArrayHelper::getValue($arParams, 'NEW');
$markerRecommend = ArrayHelper::getValue($arParams, 'RECOMMEND');

$arResult = [
    'HIT' => $markerHit === true || $markerHit === 'Y',
    'SALE' => $markerSale === true || $markerSale === 'Y',
    'NEW' => $markerNew === true || $markerNew === 'Y',
    'RECOMMEND' => $markerRecommend === true || $markerRecommend === 'Y'
];

$this->includeComponentTemplate();