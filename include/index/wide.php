<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;
use intec\core\collections\Arrays;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var string $page
 * @var Closure $render($block, $data = [])
 * @var Path $path
 * @global CMain $APPLICATION
 */
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/marquee-main.php"
	)
);?>
<?php
$render($blocks->get('categories'));
$render($blocks->get('icons'));
$render($blocks->get('advantages'));
?>
<div id="sections-main-block">
	<?php
		$render($blocks->get('sections'));
	?>
</div>
<div id="products-main-block">
	<?php
		$render($blocks->get('products'));
	?>
</div>
<?php
$render($blocks->get('product-day'));
$render($blocks->get('shares'));
$render($blocks->get('services'));
$render($blocks->get('gallery'));
$render($blocks->get('projects'));
$render($blocks->get('stages'));
$render($blocks->get('video'));
$render($blocks->get('collections'));
$render($blocks->get('rates'));
$render($blocks->get('staff'));
$render($blocks->get('certificates'));
$render($blocks->get('faq'));
$render($blocks->get('videos'));
$render($blocks->get('products-reviews'));
$render($blocks->get('images'));
$render($blocks->get('articles'));
?>
<div id="news-main-block">
	<?php
		$render($blocks->get('news'));
	?>
</div>
<?php
$render($blocks->get('reviews'));
$render($blocks->get('about'));

$APPLICATION->IncludeComponent(
	"intec.universe:main.categories", 
	"template.7.custom", 
	array(
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"DESCRIPTION_SHOW" => "N",
		"HEADER_SHOW" => "N",
		"IBLOCK_ID" => "48",
		"IBLOCK_TYPE" => "content",
		"ORDER_BY" => "ASC",
		"SORT_BY" => "SORT",
		"COMPONENT_TEMPLATE" => "template.7.custom",
		"SETTINGS_USE" => "N",
		"LAZYLOAD_USE" => "Y",
		"COLUMNS" => "3",
		"NAME_SHOW" => "Y",
		"PREVIEW_SHOW" => "N",
		"PICTURE_SHOW" => "N",
		"SECTIONS_MODE" => "id",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENTS_COUNT" => "",
		"LINK_MODE" => "property",
		"PROPERTY_LINK" => "LINK",
		"LINK_USE" => "N",
		"PROPERTY_STICKER" => "",
		"NAME_HORIZONTAL" => "left",
		"NAME_VERTICAL" => "bottom",
		"PROPERTY_MOBILE_IMAGE" => "FILE"
	),
	false
);

$render($blocks->get('brands'));
$render($blocks->get('stories'));
$render($blocks->get('instagram'));
$render($blocks->get('contacts'));
?>
<div id="form-main-block">
    <?php $APPLICATION->IncludeComponent(
	"intec.universe:main.form", 
	"template.1.custom", 
	array(
		"ID" => "2",
		"NAME" => "Обратная связь",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"CONSENT" => "/company/consent/",
		"TEMPLATE" => ".default",
		"TITLE" => "",
		"DESCRIPTION_SHOW" => "Y",
		"DESCRIPTION_TEXT" => "Оставьте заявку и с вами свяжутся в течение пары минут",
		"BUTTON_TEXT" => "Обратная связь",
		"THEME" => "dark",
		"VIEW" => "left",
		"BACKGROUND_COLOR" => "#FED16D",
		"BACKGROUND_IMAGE_USE" => "N",
		"BACKGROUND_IMAGE_PATH" => "/images/forms/form.1/background.jpg",
		"BACKGROUND_IMAGE_HORIZONTAL" => "center",
		"BACKGROUND_IMAGE_VERTICAL" => "center",
		"BACKGROUND_IMAGE_SIZE" => "cover",
		"BACKGROUND_IMAGE_PARALLAX_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"COMPONENT_TEMPLATE" => "template.1.custom"
	),
	false
); ?>
</div>
