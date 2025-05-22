<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$arMenu = $arResult['MENU']['MAIN'];
$arMenuParams = !empty($arMenuParams) ? $arMenuParams : [];

$sPrefixCatalog = 'MENU_MAIN_';
$arParametersCatalog = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefixCatalog)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefixCatalog));
        $arParametersCatalog[$sKey] = $sValue;
    }

$arParametersCatalog['TRANSPARENT'] = $arResult['VISUAL']['TRANSPARENCY'] ? 'Y' : 'N';
$arParametersCatalog = ArrayHelper::merge($arParametersCatalog, $arMenuParams, [
    'ROOT_MENU_TYPE' => $arMenu['ROOT'],
    'CHILD_MENU_TYPE' => $arMenu['CHILD'],
    'MAX_LEVEL' => $arMenu['LEVEL'],
    'MENU_CACHE_TYPE' => 'N',
    'USE_EXT' => 'Y',
    'DELAY' => 'N',
    'ALLOW_MULTI_SELECT' => 'N'
]);

?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:menu',
    'horizontal.1',
    $arParametersCatalog,
    $this->getComponent()
); ?>
<?php unset($arMenu) ?>