<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-brands',
        'c-brands-template-1'
    ],
    'data' => [
        'effect' => $arVisual['EFFECT'],
        'columns' => $arVisual['COLUMNS']
    ],
	'style' => [
		'overflow' => 'hidden',
		'position' => 'relative'
	]
]) ?>
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
					<div class="widget-header-content-wrapper">
						<div class="intec-grid intec-grid-wrap intec-grid-a-v-center">
							<?php if ($arBlocks['HEADER']['SHOW']) { ?>
								<div class="intec-grid-item">
									<?= Html::tag('div', Html::encode($arBlocks['HEADER']['TEXT']), [
										'class' => [
											'widget-title',
											'align-'.$arBlocks['HEADER']['POSITION'],
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
											'href' => $arBlocks['FOOTER']['BUTTON']['LINK']
										])?>
											<i class="fal fa-angle-right"></i>
										<?= Html::endTag('a')?>
									<?= Html::endTag('div') ?>
								<?php } ?>
							<?php } ?>
							<?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
								<div class="intec-grid-item-1">
									<div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
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
            <div class="widget-sections">
				<div id="owl-wrap">
					<div class="owl-carousel">
						<?php
							$i = 0;
							
							while ($i < count($arResult['ITEMS'])) {
								$slice = array_slice($arResult['ITEMS'], $i, 2);
						?>
						<div class="item">
						<?php foreach ($slice as $arItem) {

							$sId = $sTemplateId.'_'.$arItem['ID'];
							$sAreaId = $this->GetEditAreaId($sId);
							$this->AddEditAction($sId, $arItem['EDIT_LINK']);
							$this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

							$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';
							$sPicture = $arItem['PREVIEW_PICTURE'];

							if (empty($sPicture))
								$sPicture = $arItem['DETAIL_PICTURE'];

							if (!empty($sPicture)) {
								$sPicture = CFile::ResizeImageGet($sPicture, [
									'width' => 400,
									'height' => 400
								], BX_RESIZE_IMAGE_PROPORTIONAL);

								if (!empty($sPicture))
									$sPicture = $sPicture['src'];
							}

							if (empty($sPicture))
								$sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

						?>
							<?= Html::beginTag('div', [
								'class' => Html::cssClassFromArray([
									'widget-item' => true,
								], true)
							]) ?>
								<?= Html::beginTag($sTag, [
									'id' => $sAreaId,
									'class' => 'widget-item-wrapper',
									'href' => $arVisual['LINK']['USE'] ? $arItem['DETAIL_PAGE_URL'] : null,
									'target' => $arVisual['LINK']['USE'] && $arVisual['LINK']['BLANK'] ? '_blank' : null
								]) ?>
									<?= Html::tag('div', null, [
										'class' => 'widget-item-picture',
										'data' => [
											'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
											'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
										],
										'style' => [
											'opacity' => $arVisual['TRANSPARENCY'],
											'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
										]
									]) ?>
								<?= Html::endTag($sTag) ?>
							<?= Html::endTag('div') ?>
						<?php } ?>
						</div>
						<?php
							$i = $i + 3;
							}
						?>
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
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-footer' => true,
                        'align-' . $arBlocks['FOOTER']['POSITION'] => true,
                        'mobile' => $arBlocks['HEADER']['SHOW'],
                        'intec-grid-item' => [
                            'auto' => $arBlocks['HEADER']['SHOW'],
                            '1' => !$arBlocks['HEADER']['SHOW']
                        ]
                    ], true)
                ]) ?>
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <a href="<?= $arBlocks['FOOTER']['BUTTON']['LINK'] ?>" class="<?= Html::cssClassFromArray([
                            'widget-footer-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'mod-transparent',
                                'mod-round-half',
                                'scheme-current',
                                'size-5'
                            ]
                        ]) ?>">
                            <?= Html::encode($arBlocks['FOOTER']['BUTTON']['TEXT']) ?>
                        </a>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<script>
template.load(function (data) {
	var template = $('#<?= $sTemplateId ?>');
	handler = function () {
		var items = $(template).find('.owl-stage:first');

		
		items.children('.owl-item.active').removeClass('collapsed');
		
		
		var elem = $(items).children('.owl-item.active')[0];
		$(elem).prevAll().addClass('collapsed');
		$(elem).nextAll().removeClass('collapsed');
	};

	var owl = $(template).find('#owl-wrap .owl-carousel');
	owl.owlCarousel({
		nav: false,
		margin:0,
		//mouseDrag: false,
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
		//onResized: handler,
		//onRefreshed: handler,
		//onInitialized: handler,
		onTranslated: handler
	});

	$(template).find('.carousel-next').click(
		function() {
			owl.trigger('next.owl.carousel');
		}
	);
	$(template).find('.carousel-prev').click(
		function() {
			owl.trigger('prev.owl.carousel');
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