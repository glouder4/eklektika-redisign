<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\bitrix\Component;

/**
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

$bFirstBig = $arVisual['ELEMENT']['FIRST_BIG'];

?>
<div class="widget c-articles c-articles-template-1" id="<?= $sTemplateId ?>" style="overflow: hidden; position: relative">
    <div class="widget-wrapper intec-content intec-content-visible widget-articles-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arVisual['HEADER']['SHOW'] || $arVisual['DESCRIPTION']['SHOW'] || $arVisual['SEE_ALL']['SHOW']) { ?>
                <div class="widget-header">
					<div class="widget-header-content-wrapper">
						<div class="intec-grid intec-grid-wrap">
							<?php if ($arVisual['HEADER']['SHOW']) { ?>
								<div class="widget-title-container intec-grid-item-1">
									<?= Html::tag('div', Html::encode($arVisual['HEADER']['VALUE']), [
										'class' => [
											'widget-title',
											'align-'.$arVisual['HEADER']['POSITION'],
											$arVisual['SEE_ALL']['SHOW'] ? 'widget-title-margin' : null
										]
									]) ?>
								</div>
							<?php } ?>
							<?php if ($arVisual['SEE_ALL']['SHOW']) { ?>
								<?= Html::beginTag('div', [
									'class' => Html::cssClassFromArray([
										'widget-all-container' => true,
										'align-'.$arVisual['SEE_ALL']['POSITION'] => true,
										'mobile' => $arVisual['HEADER']['SHOW'],
										'intec-grid-item-1' => true
									], true)
								]) ?>
									<?= Html::beginTag('a', [
										'class' => [
											'widget-all-button',
											'intec-cl-text-light-hover',
										],
										'href' => $arVisual['SEE_ALL']['URL']
									])?>
										<span><?= $arVisual['SEE_ALL']['TEXT'] ?></span>
										<i class="fal fa-angle-right"></i>
									<?= Html::endTag('a')?>
								<?= Html::endTag('div') ?>
							<?php } ?>
							<?php if ($arVisual['DESCRIPTION']['SHOW']) { ?>
								<div class="widget-description-container intec-grid-item-1">
									<div class="widget-description align-<?= $arVisual['DESCRIPTION']['POSITION'] ?>">
										<?= Html::encode($arVisual['DESCRIPTION']['VALUE']) ?>
									</div>
								</div>
							<?php } ?>
							<div class="slider-navigation-wrapper intec-grid intec-grid-i-h-5">
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
            <div class="widget-section">
                
				<div id="owl-wrap">
					<div class="owl-carousel">
				
						<?php foreach ($arResult['ITEMS'] as $arItem) {

							$header = ArrayHelper::getValue($arItem, 'NAME');
							$description = ArrayHelper::getValue($arItem, 'PREVIEW_TEXT');
							$bShowDescription = $bElementDescriptionShow && !empty($description);

							$sPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);
						?>
							
							<div class="widget-element">
								<?= Html::tag('a', '', [
										'class' => [
											'picture',
											'intec-image-effect'
										],
										'href' => $arItem['DETAIL_PAGE_URL'],
										'data' => [
											'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
											'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
										],
										'style' => [
											'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
										]
									]
								) ?>
								<?php if ($arVisual['ELEMENT']['HEADER']) { ?>
									<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="header intec-cl-text-hover">
										<span><?= $arItem['NAME'] ?></span>
									</a>
								<?php } ?>
								<?php if ($arVisual['ELEMENT']['DESCRIPTION']) { ?>
									<div class="description">
										<?= $arItem['PREVIEW_TEXT'] ?>
									</div>
								<?php } ?>
							</div>
							
						<?php } ?>
					</div>
				</div>
				
				
				
            </div>
        </div>
    </div>
</div>

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
	
	$(template).find('#owl-wrap .owl-carousel').owlCarousel({
		nav: false,
		margin:0,
		//mouseDrag: false,
		responsive:{
			0:{
			  items:1,
			},
			550:{
			  items:1, 
			},
			1000:{
			  items:2,
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
	var owl = $(template).find('.owl-carousel');
	owl.owlCarousel();
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