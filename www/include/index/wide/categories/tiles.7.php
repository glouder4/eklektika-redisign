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
<?= Html::beginTag('div', ['style' => array (
  'margin-top' => '30px',
  'margin-bottom' => '40px',
)]) ?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.categories", 
	"template.15.custom", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "9",
		"SECTIONS_MODE" => "id",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"SORT_ORDER" => "ASC",
		"COLUMNS" => "4",
		"ELEMENTS_COUNT" => "4",
		"LINK_USE" => "Y",
		"LINK_BLANK" => "Y",
		"LINK_MODE" => "property",
		"PROPERTY_LINK" => "LINK",
		"HEADER_SHOW" => "N",
		"DESCRIPTION_SHOW" => "N",
		"NAME_SHOW" => "Y",
		"PREVIEW_SHOW" => "Y",
		"COMPONENT_TEMPLATE" => "template.15.custom",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"ORDER_BY" => "ASC",
		"BG_COLOR" => "BG_COLOR",
		"DETAIL_BANNER" => "DETAIL_BANNER",
		"BORDER_USE" => "BORDER_USE",
		"COLOR_WHITE" => "COLOR_WHITE"
	),
	false
);?>
<?= Html::endTag('div') ?>
