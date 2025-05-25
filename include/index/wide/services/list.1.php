<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<div id="services-main-block">
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.services", 
	"template.23.custom", 
	array(
		"IBLOCK_TYPE" => "catalogs",
		"IBLOCK_ID" => "16",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENTS_COUNT" => "",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "Y",
		"PROPERTY_MEASURE" => "",
		"PROPERTY_PRICE" => "PRICE",
		"HEADER_BLOCK_SHOW" => "Y",
		"HEADER_BLOCK_POSITION" => "left",
		"HEADER_BLOCK_TEXT" => "Наши услуги",
		"DESCRIPTION_BLOCK_SHOW" => "N",
		"LINK_USE" => "Y",
		"PREVIEW_SHOW" => "Y",
		"PRICE_SHOW" => "Y",
		"ORDER_USE" => "N",
		"ORDER_FORM_ID" => "10",
		"ORDER_FORM_FIELD" => "form_text_38",
		"ORDER_FORM_TEMPLATE" => ".default",
		"ORDER_FORM_TITLE" => "Заказать",
		"ORDER_FORM_CONSENT" => "/company/consent/",
		"LIST_PAGE_URL" => "",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"COMPONENT_TEMPLATE" => "template.23.custom",
		"PROPERTY_PRICE_FORMAT" => "",
		"PRICE_FORMAT" => "#VALUE# #CURRENCY#",
		"PROPERTY_CURRENCY" => "CURRENCY",
		"CURRENCY" => ""
	),
	false
);?>
</div>
