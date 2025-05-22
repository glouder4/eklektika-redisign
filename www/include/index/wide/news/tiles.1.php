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
  'margin-top' => '50px',
  'margin-bottom' => '50px',
)]) ?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.news", 
	"template.3.custom", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "25",
		"ELEMENTS_COUNT" => "",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"HEADER_BLOCK_SHOW" => "Y",
		"HEADER_BLOCK_POSITION" => "left",
		"HEADER_BLOCK_TEXT" => "Последние новости и статьи",
		"DESCRIPTION_BLOCK_SHOW" => "Y",
		"DATE_SHOW" => "Y",
		"DATE_FORMAT" => "j F Y",
		"COLUMNS" => "4",
		"LINK_USE" => "Y",
		"FOOTER_SHOW" => "N",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"COMPONENT_TEMPLATE" => "template.3.custom",
		"LIST_PAGE_URL" => "",
		"NAVIGATION_USE" => "N",
		"DESCRIPTION_BLOCK_POSITION" => "left",
		"DESCRIPTION_BLOCK_TEXT" => "Подходит для продукции из натуральной, эко-кожи, дерева, бумаги, картона."
	),
	false
);?>
<?= Html::endTag('div') ?>
