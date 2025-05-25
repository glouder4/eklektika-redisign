<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$arSearchParams = !empty($arSearchParams) ? $arSearchParams : [];

$sPrefix = 'SEARCH_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters = ArrayHelper::merge($arParameters, $arSearchParams);
$arParameters['PAGE'] = $arResult['SEARCH']['MODE'] === 'site' ? $arResult['URL']['SEARCH'] : $arResult['URL']['CATALOG'];
$arParameters['INPUT_ID'] = $arParameters['INPUT_ID'].'-input-1';

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


<div class="intec-grid intec-grid-wrap intec-grid-a-v-center menu-search-wrapper">
	<div class="intec-grid-item-auto" style="position: unset;">
		<?php /*$APPLICATION->IncludeComponent(
			'bitrix:menu',
			'popup.3.custom',
			$arParametersCatalog,
			$this->getComponent()
		); */?>
		<?php $APPLICATION->IncludeComponent(
			'bitrix:menu',
			'horizontal.1.custom',
			$arParametersCatalog,
			$this->getComponent()
		); ?>
		<?php unset($arMenu) ?>
	</div>

	<div class="intec-grid-item">
			<!--noindex-->
		<?php $APPLICATION->IncludeComponent(
			"bitrix:search.title",
			"input.1.custom",
			$arParameters,
			$this->getComponent()
		) ?>
			<!--/noindex-->
		<?php unset($arParameters) ?>
		<?php unset($arSearchParams) ?>
	</div>
</div>



