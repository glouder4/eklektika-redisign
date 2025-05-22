<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !==true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

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
<div class="widget c-categories c-categories-template-15" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                        <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'i-h-15',
							'i-v-5'
                        ]
                    ],
                    'data' => [
                        'columns' => $arVisual['COLUMNS']
                    ]
                ]) ?>
					<?php $counter = 0; ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {
						$counter = $counter + 1;
                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet(
                                $sPicture, [
                                'width' => 600,
                                'height' => 600
                            ],
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH . '/images/picture.missing.png';

                        if ($arVisual['LINK']['USE'] && !empty($arItem['DETAIL_PAGE_URL']))
                            $sTag = 'a';
                        else
                            $sTag = 'div';

                        ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
								'big' => ($counter == 2),
                            ], true)
                        ]) ?>
                            <?= Html::beginTag('div', [
                                'id' => $sAreaId,
								'class' => Html::cssClassFromArray([
									'widget-item-wrapper' => true,
									'color-white' => !empty($arItem['PROPERTIES'][$arParams['COLOR_WHITE']]['VALUE']),
								], true)
                            ]) ?>
                                <?= Html::beginTag('div', [
									'class' => Html::cssClassFromArray([
										'widget-item-picture' => true,
										'border-use' => !empty($arItem['PROPERTIES'][$arParams['BORDER_USE']]['VALUE']),
										'border-active' => (!empty($arItem['PROPERTIES'][$arParams['BORDER_USE']]['VALUE']) && ($counter != 2)),
									], true),
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null,
										'background-color' => (!empty($arItem['PROPERTIES'][$arParams['BG_COLOR']]['VALUE'])) ? $arItem['PROPERTIES'][$arParams['BG_COLOR']]['VALUE'] : '#fff'
                                    ]
                                ]) ?>
									<div class="widget-item-content-wrapper">
										<div class="intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-h-16">
											<?= Html::beginTag('div', [
												'class' => Html::cssClassFromArray([
													'widget-item-count' => true,
													'intec-grid-item-1' => true,
													'intec-grid-item-768-auto' => true,
													'hidden' => ($counter == 2),
												], true)
											]) ?>
												<?php
													$counterValue = strval($counter);
													if ($counter < 10)
														$counterValue = '0'.strval($counter);
												?>
												<?= $counterValue ?>
											<?= Html::endTag('div') ?>
											<?php if ($arVisual['NAME']['SHOW']) { ?>
												<?= Html::beginTag('div', [
													'class' => Html::cssClassFromArray([
														'widget-item-name' => true,
														'intec-grid-item-1' => true,
														'intec-grid-item-768' => true,
														'big-font' => ($counter == 2),
													], true)
												]) ?>
													<?= $arItem['NAME'] ?>
												<?= Html::endTag('div') ?>
											<?php } ?>
											
										</div>
										<?= Html::beginTag('a', [
											'href' =>  $arItem['DETAIL_PAGE_URL'],
											'target' => $arVisual['LINK']['BLANK'] ? '_blank' : false,
											'class' => Html::cssClassFromArray([
												'widget-item-detail-button' => true,
												'intec-cl-background' => true,
												'intec-cl-background-light-hover' => true,
												'visible' => ($counter == 2),
											], true)
										]) ?>
											<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L8.34315 15.0711C7.95262 15.4616 7.31946 15.4616 6.92893 15.0711C6.53841 14.6805 6.53841 14.0474 6.92893 13.6569L12.5858 8L6.92893 2.34315C6.53841 1.95262 6.53841 1.31946 6.92893 0.928932C7.31946 0.538408 7.95262 0.538408 8.34315 0.928932L14.7071 7.29289ZM0 7L14 7V9L0 9L0 7Z" fill="white"/>
											</svg>
										<?= Html::endTag('a') ?>
									</div>
									
									<?
										$arPictureDetail = $arItem['PROPERTIES'][$arParams['DETAIL_BANNER']]['VALUE'];
										
										if (!empty($arPictureDetail)) {
											$arPictureDetail = CFile::ResizeImageGet($arPictureDetail, [
												'width' => 700,
												'height' => 700
											], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

											if (!empty($arPictureDetail))
												$arPictureDetail = $arPictureDetail['src'];
										}
										
									?>
									<?= Html::beginTag('div', [
										'class' => [
											'widget-item-picture-detail',
										],
										'data' => [
											'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
											'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureDetail : null
										],
										'style' => [
											'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arPictureDetail.'\')' : null,
										]
									]) ?>
									
									<?= Html::endTag('div') ?>
								<?= Html::endTag('div') ?>


                                
                            <?= Html::endTag('div') ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>

<script>
$('#<?= $sTemplateId ?> .widget-item-wrapper').each(
	function (index) {
		$(this).click (
			function() {
				var parent = $(this).closest('.widget-item');
				if (!$(parent).hasClass('big')) {
					var allParents = $(parent).closest('.widget-items').find('.widget-item');
					$(allParents).each(
						function() {
							var elem = $(this);
							//if ($(elem).hasClass('big')) {
								$(elem).find('.widget-item-content-wrapper').fadeOut(200);
								setTimeout(function () {
									$(elem).removeClass('big');
								}, 200);
								setTimeout(function () {
									$(elem).find('.widget-item-content-wrapper').fadeIn();
									$(elem).find('.widget-item-detail-button').removeClass('visible');
									$(elem).find('.widget-item-count').removeClass('hidden');
									$(elem).find('.widget-item-name').removeClass('big-font');
									if (!$(elem).hasClass('big') && $(elem).find('.widget-item-picture').hasClass('border-use')) {
										$(elem).find('.border-use').addClass('border-active');
									}
								}, 600);
							//}
						}
					)
					$(parent).find('.widget-item-content-wrapper').fadeOut(200);
					setTimeout(function () {
						$(parent).addClass('big');
					}, 200);
					setTimeout(function () {
						$(parent).find('.widget-item-content-wrapper').fadeIn();
						$(parent).find('.widget-item-detail-button').addClass('visible');
						$(parent).find('.widget-item-count').addClass('hidden');
						$(parent).find('.widget-item-name').addClass('big-font');
						$(parent).find('.border-use').removeClass('border-active');
					}, 600);
				}
			}
		)
	}
);
</script>