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

<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.sections", 
	"template.1.custom", 
	array(
		"IBLOCK_TYPE" => "1c_catalog",
		"IBLOCK_ID" => "43",
		"QUANTITY" => "N",
		"SECTIONS_MODE" => "id",
		"DEPTH" => "1",
		"ELEMENTS_COUNT" => "",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"HEADER_SHOW" => "Y",
		"HEADER_POSITION" => "left",
		"HEADER_TEXT" => "Категории товаров",
		"DESCRIPTION_SHOW" => "Y",
		"BUTTON_ALL_SHOW" => "N",
		"BUTTON_ALL_TEXT" => "Весь каталог",
		"LINE_COUNT" => "3",
		"SECTION_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"COMPONENT_TEMPLATE" => "template.1.custom",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"LIST_PAGE_URL" => "",
		"DESCRIPTION_POSITION" => "left",
		"DESCRIPTION_TEXT" => "Подходит для продукции из натуральной, эко-кожи, дерева, бумаги, картона.",
		"PICTURE_SIZE" => "big",
		"SUB_SECTIONS_SHOW" => "N"
	),
	false
);?>

