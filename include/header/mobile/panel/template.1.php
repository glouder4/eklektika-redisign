<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.panel", 
	"template.1", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "42",
		"ELEMENTS_COUNT" => "",
		"MODE" => "code",
		"ELEMENTS" => array(
			0 => "",
			1 => "",
		),
		"BASKET_USE" => "Y",
		"DELAY_USE" => "Y",
		"COMPARE_USE" => "Y",
		"COMPARE_IBLOCK_TYPE" => "1c_catalog",
		"COMPARE_IBLOCK_ID" => "43",
		"COMPARE_NAME" => "compare",
		"PROPERTY_URL" => "LINK",
		"PROPERTY_ICON" => "ICON",
		"BASKET_ELEMENT" => "basket",
		"DELAY_ELEMENT" => "favorite",
		"COMPARE_ELEMENT" => "compare",
		"NAME_SHOW" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"SVG_COLOR_MODE" => "fill",
		"COMPONENT_TEMPLATE" => "template.1"
	),
	false
); ?>
