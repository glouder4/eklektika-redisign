<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

Loader::includeModule('intec.core');



if ($arParams['SET_TITLE'] == 'Y') {
	$APPLICATION->SetTitle('Коммерческие предложения');
}

if (strlen($arParams['MAIN_CHAIN_NAME']) > 0) {
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams['MAIN_CHAIN_NAME']), $arResult['SEF_FOLDER']);
}


$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
?>

<div class="intec-content intec-content-visible">
    <div class="intec-content-wrapper">
        <?= Html::beginTag('div', [
            'id' => $sTemplateId,
            'class' => Html::cssClassFromArray([
                'ns-bitrix' => true,
                'c-sale-personal-section' => true,
                'c-sale-personal-section-template-1' => true,
            ], true),
            'data' => [
                'role' => 'personal'
            ]
        ]) ?>
            <div class="sale-personal-section-links-desktop">
                <?php include(__DIR__.'/parts/menu_desktop.php') ?>
            </div>
            <div class="sale-personal-section-links-mobile">
                <?php include(__DIR__.'/parts/menu_mobile.php') ?>
            </div>
        <?= Html::endTag('div') ?>
    </div>
</div>
<?php
Loader::includeModule('intec.eklectika');
global $USER;
$userId = $USER->GetID();
$GLOBALS["filterKP"] = ["PROPERTY_USER" => $userId];
$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"kp", 
	array(
		"COMPONENT_TEMPLATE" => "baskets",
		"IBLOCK_TYPE" => "cabinet",
		"IBLOCK_ID" => "51",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "filterKP",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "DATA",
			1 => "QUANTITY",
			2 => "CONTACTS",
			3 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);
?>

<?php include(__DIR__.'/parts/script.php') ?>