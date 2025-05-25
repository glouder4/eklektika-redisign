<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\Json;
use intec\core\helpers\FileHelper;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

$bBase = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale'))
    $bBase = true;

if ($bBase)
    CJSCore::Init(array('currency'));

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

if (empty($arResult['ITEMS']) || empty($arResult['CATEGORIES']))
    return;

$arSvg = [
    'DELAY' => FileHelper::getFileData(__DIR__.'/svg/delay.svg'),
    'COMPARE' => FileHelper::getFileData(__DIR__.'/svg/compare.svg'),
    'ORDER_FAST' => FileHelper::getFileData(__DIR__.'/svg/order.svg'),
    'QUICK_VIEW' => FileHelper::getFileData(__DIR__.'/svg/view.svg'),
    'PRICE_DIFFERENCE' => FileHelper::getFileData(__DIR__.'/svg/price.difference.svg')
];

$dData = include(__DIR__.'/parts/data.php');
$vBigImage = include(__DIR__.'/parts/bigImage.php');
$vButtons = include(__DIR__.'/parts/buttons.php');
$vCounter = include(__DIR__.'/parts/counter.php');
$vImage = include(__DIR__.'/parts/image.php');
$vPrice = include(__DIR__.'/parts/price.php');
$vPurchase = include(__DIR__.'/parts/purchase.php');
$vQuantity = include(__DIR__.'/parts/quantity.php');
$vSku = include(__DIR__.'/parts/sku.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-widget',
        'c-widget-products-4'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS']['USE'] ? 'true' : 'false',
        'columns-desktop' => $arVisual['COLUMNS']['DESKTOP'],
        'columns-mobile' => $arVisual['COLUMNS']['MOBILE'],
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'button' => $arResult['ACTION'] !== 'none' ? 'true' : 'false',
        'tabs' => $arResult['MODE'] === 'all' || $arResult['MODE'] === 'categories' ? 'true' : 'false',
    ],
]) ?>
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <div class="widget-header-content-wrapper">
						<div class="intec-grid intec-grid-wrap intec-grid-a-v-center">
							<?php if ($arBlocks['HEADER']['SHOW']) { ?>
								<div class="widget-title-container intec-grid-item">
									<?= Html::tag('div', Html::encode($arBlocks['HEADER']['TEXT']), [
										'class' => [
											'widget-title',
											'align-'.$arBlocks['HEADER']['ALIGN'],
											$arBlocks['FOOTER']['BUTTON']['SHOW'] ? 'widget-title-margin' : null
										]
									]) ?>
								</div>
								<?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
									<?= Html::beginTag('div', [
										'class' => Html::cssClassFromArray([
											'widget-all-container' => true,
											'mobile' => $arBlocks['HEADER']['SHOW'],
											'intec-grid-item' => [
												'auto' => $arBlocks['HEADER']['SHOW'],
												'1' => !$arBlocks['HEADER']['SHOW']
											]
										], true)
									]) ?>
										<?= Html::beginTag('a', [
											'class' => [
												'widget-all-button',
												'intec-cl-text-light-hover',
											],
											'href' => $arBlocks['FOOTER']['BUTTON']['URL']
										])?>
											<i class="fal fa-angle-right"></i>
										<?= Html::endTag('a')?>
									<?= Html::endTag('div') ?>
								<?php } ?>
							<?php } ?>
							<?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
								<div class="intec-grid-item-1">
									<div class="widget-description align-<?= $arBlocks['DESCRIPTION']['ALIGN'] ?>">
										<?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
									</div>
								</div>
							<?php } ?>
							<div class="slider-navigation-wrapper desktop intec-grid intec-grid-i-h-5">
								<div class="intec-grid-item-auto">
									<a class="carousel-prev">
										<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0.292893 7.29289C-0.0976315 7.68342 -0.0976315 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928932C7.68054 0.538408 7.04738 0.538408 6.65685 0.928932L0.292893 7.29289ZM15 7L1 7V9L15 9V7Z" fill="#DB0032"/>
										</svg>
									</a>
								</div>
								<div class="intec-grid-item-auto">
									<a class="carousel-next">
										<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L8.34315 15.0711C7.95262 15.4616 7.31946 15.4616 6.92893 15.0711C6.53841 14.6805 6.53841 14.0474 6.92893 13.6569L12.5858 8L6.92893 2.34315C6.53841 1.95262 6.53841 1.31946 6.92893 0.928932C7.31946 0.538408 7.95262 0.538408 8.34315 0.928932L14.7071 7.29289ZM0 7L14 7V9L0 9L0 7Z" fill="#DB0032"/>
										</svg>
									</a>
								</div>
							</div>
						</div>
					</div>
                </div>
            <?php } ?>
            
			<?php if ($arResult['MODE'] === 'all' || $arResult['MODE'] === 'categories') { ?>
				<?php if ($arVisual['VIEW'] === 'tabs') { ?>
					<?= Html::beginTag('ul', [
						'class' => Html::cssClassFromArray([
							'widget-tabs' => true,
							'widget-tabs-margin-' . $arVisual['TABS']['ALIGN'] => $arBlocks['FOOTER']['SHOW'] && $arBlocks['FOOTER']['BUTTON']['SHOW'],
							'intec-ui' => [
								'' => true,
								'control-tabs' => true,
								'mod-block' => true,
								'mod-position-'.$arVisual['TABS']['ALIGN'] => true,
								'scheme-current' => true,
								'view-1' => true
							]
						], true),
						'data' => [
							'ui-control' => 'tabs'
						]
					]) ?>
						<?php $iCounter = 0 ?>
						<?php foreach ($arResult['CATEGORIES'] as $arCategory) { ?>
							<?= Html::beginTag('li', [
								'class' => 'intec-ui-part-tab',
								'data' => [
									'active' => $iCounter === 0 ? 'true' : 'false'
								]
							]) ?>
								<a href="<?= '#'.$sTemplateId.'-tab-'.$iCounter ?>" data-type="tab">
									<?= $arCategory['NAME'] ?>
								</a>
							<?= Html::endTag('li') ?>
							<?php $iCounter++ ?>
						<?php } ?>
					<?= Html::endTag('ul') ?>
					<div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
						<?php $iCounter = 0 ?>
						<?php foreach ($arResult['CATEGORIES'] as $arCategory) { ?>
							<?= Html::beginTag('div', [
								'id' => $sTemplateId.'-tab-'.$iCounter,
								'class' => 'intec-ui-part-tab',
								'data' => [
									'active' => $iCounter === 0 ? 'true' : 'false'
								]
							]) ?>
								<?php $arBanner = &$arCategory['BANNER'] ?>
								<?php $arItems = &$arCategory['ITEMS'] ?>
								<?php include(__DIR__.'/parts/items.php') ?>
							<?= Html::endTag('div') ?>
							<?php $iCounter++ ?>
						<?php } ?>
					</div>
					<?php if ($arBlocks['FOOTER']['SHOW'] && $arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
						<?= Html::beginTag('div', [
							'class' => Html::cssClassFromArray([
								'widget-footer' => true,
								'mobile' => $arBlocks['HEADER']['SHOW'] && $arBlocks['FOOTER']['BUTTON']['SHOW']
							], true),
							'data' => [
								'type' => 'tabs'
							]
						]) ?>
							<?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
								'class' => [
									'widget-footer-button',
									'intec-cl-text-hover'
								],
								'href' => $arBlocks['FOOTER']['BUTTON']['URL']
							]) ?>
						<?= Html::endTag('div') ?>
					<?php } ?>
				<?php } else { ?>
					<div class="widget-sections">
						<div class="widget-section">
							<div class="widget-section-content">
								<?= Html::beginTag('div', [
									'class' => Html::cssClassFromArray([
										'widget-items' => true,
									], true)
								]) ?>
									<div id="owl-wrap-for-element">
										<div class="owl-carousel" id="owl-carousel-for-element">
											<?php foreach ($arResult['CATEGORIES'] as $arCategory) { ?>
												
												<?php $arItems = &$arCategory['ITEMS'] ?>
												<?php include(__DIR__.'/parts/items.php') ?>
												
											<?php } ?>
										</div>
									</div>
								<?= Html::endTag('div') ?>
							</div>
						</div>
					</div>
					<div class="slider-navigation-wrapper mobile intec-grid intec-grid-i-h-5">
						<div class="intec-grid-item-auto">
							<a class="carousel-prev">
								<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.292893 7.29289C-0.0976315 7.68342 -0.0976315 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928932C7.68054 0.538408 7.04738 0.538408 6.65685 0.928932L0.292893 7.29289ZM15 7L1 7V9L15 9V7Z" fill="#DB0032"/>
								</svg>
							</a>
						</div>
						<div class="intec-grid-item-auto">
							<a class="carousel-next">
								<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L8.34315 15.0711C7.95262 15.4616 7.31946 15.4616 6.92893 15.0711C6.53841 14.6805 6.53841 14.0474 6.92893 13.6569L12.5858 8L6.92893 2.34315C6.53841 1.95262 6.53841 1.31946 6.92893 0.928932C7.31946 0.538408 7.95262 0.538408 8.34315 0.928932L14.7071 7.29289ZM0 7L14 7V9L0 9L0 7Z" fill="#DB0032"/>
								</svg>
							</a>
						</div>
					</div>
				<?php } ?>
			<?php } else if ($arResult['MODE'] === 'category') { ?>
				<?php $arCategory = null; ?>
				<?php $arBanner = &$arResult['BANNER'] ?>
				<?php $arItems = &$arResult['ITEMS'] ?>
				<?php include(__DIR__.'/parts/items.php') ?>
			<?php } ?>
			<?php if (!defined('EDITOR'))
				include(__DIR__.'/parts/script.php');
			?>
            
            <?php if ($arBlocks['FOOTER']['SHOW'] && $arBlocks['FOOTER']['BUTTON']['SHOW'] && $arVisual['VIEW'] !== 'tabs') { ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-footer' => true,
                        'align-' . $arBlocks['FOOTER']['ALIGN'] => true,
                        'mobile' => $arBlocks['HEADER']['SHOW'] && $arBlocks['FOOTER']['BUTTON']['SHOW']
                    ], true),
                    'data' => [
                        'type' => 'default'
                    ]
                ]) ?>
                    <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                        'class' => [
                            'widget-footer-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'size-5',
                                'scheme-current',
                                'mod-transparent',
                                'mod-round-half'
                            ]
                        ],
                        'href' => $arBlocks['FOOTER']['BUTTON']['URL']
                    ]) ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<script>
template.load(function (data) {
	var template = $('#<?= $sTemplateId ?>');
	var owlForElement = $(template).find('.owl-carousel#owl-carousel-for-element');
	handler = function () {
		var items = $(owlForElement).find('.owl-stage:first');

		
		items.children('.owl-item.active').removeClass('collapsed');
		
		
		var elem = $(items).children('.owl-item.active')[0];
		console.log($(elem).nextAll());
		$(elem).prevAll().addClass('collapsed');
		$(elem).nextAll().removeClass('collapsed');
		
		$(template).find('.widget-section').removeClass('no-box-shadows');
	};
	
	owlForElement.owlCarousel({
		nav: false,
		margin:0,
		stageClass: 'owl-stage owl-stage-flex',
		responsive:{
			0:{
			  items:2,
			},
			550:{
			  items:2, 
			},
			1000:{
			  items:3,
			},
			1200:{
			  items:3,
			}
		},
		dots: false,
		onDrag: function () {
			$(template).find('.widget-section').addClass('no-box-shadows');
		},
		onDragged: function () {
			$(template).find('.widget-section').removeClass('no-box-shadows');
		},
		onTranslate: function () {
			$(template).find('.widget-section').addClass('no-box-shadows');
		},
		onTranslated: handler
	});
	
	$(template).find('.carousel-next').click(
		function() {
			owlForElement.trigger('next.owl.carousel');
		}
	);
	$(template).find('.carousel-prev').click(
		function() {
			owlForElement.trigger('prev.owl.carousel');
		}
	);
}, {
	'name': '[Component] bitrix:catalog.section (.default)',
	'nodes': <?= JavaScript::toObject('#'.$sTemplateId) ?>,
	'loader': {
		'name': 'lazy'
	}
});
</script>