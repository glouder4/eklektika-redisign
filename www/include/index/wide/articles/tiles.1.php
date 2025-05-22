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
  'margin-top' => '163px',
  'margin-bottom' => '163px',
)]) ?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:widget", 
	"articles", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "34",
		"ELEMENTS_COUNT" => "",
		"SETTINGS_USE" => "N",
		"LAZYLOAD_USE" => "Y",
		"HEADER_SHOW" => "Y",
		"HEADER_CENTER" => "N",
		"HEADER" => "Последние новости и статьи",
		"DESCRIPTION_SHOW" => "Y",
		"DESCRIPTION_CENTER" => "N",
		"DESCRIPTION" => "Подходит для продукции из натуральной, эко-кожи, дерева, бумаги, картона.",
		"BIG_FIRST_BLOCK" => "N",
		"HEADER_ELEMENT_SHOW" => "Y",
		"DESCRIPTION_ELEMENT_SHOW" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SEE_ALL_SHOW" => "N",
		"SEE_ALL_POSITION" => "N",
		"SEE_ALL_TEXT" => "Все статьи",
		"SEE_ALL_URL" => "/blog/",
		"COMPONENT_TEMPLATE" => "articles",
		"SECTIONS_ID" => array(
			0 => "",
			1 => "",
		),
		"ELEMENTS_ID" => array(
			0 => "",
			1 => "",
		)
	),
	false
);?>
<?= Html::endTag('div') ?>
