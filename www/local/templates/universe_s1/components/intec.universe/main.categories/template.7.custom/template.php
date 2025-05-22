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
<div class="widget c-categories c-categories-template-7" id="<?= $sTemplateId ?>">
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
                <div class="widget-items">
					<div class="widget-custom-tabs intec-grid">
						<?php foreach ($arResult['ITEMS'] as $key => $arItem) { ?>
							<div class="intec-grid-item-4 intec-grid-item-768-auto" data-block-type="tab">
								
									<a href="#block-scheme-default-tab-<?= $key ?>"><?= $arItem['NAME'] ?></a>
								
							</div>
						<?php } ?>
					</div>
					<div class="widget-custom-tabs-content">
						<?php foreach ($arResult['ITEMS'] as $key => $arItem) {

							$sId = $sTemplateId.'_'.$arItem['ID'];
							$sAreaId = $this->GetEditAreaId($sId);
							$this->AddEditAction($sId, $arItem['EDIT_LINK']);
							$this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

							$iCounter++;

							$arData = $arItem['DATA'];
							$sPicturePreview = $arItem['PREVIEW_PICTURE'];
							
							if (!empty($sPicturePreview)) {
								$sPicturePreview = CFile::ResizeImageGet(
									$sPicturePreview, [
										'width' => 600,
										'height' => 600
									],
									BX_RESIZE_IMAGE_PROPORTIONAL_ALT
								);

								if (!empty($sPicturePreview))
									$sPicturePreview = $sPicturePreview['src'];
							}

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
							
							$sPictureMobile = $arItem['PROPERTIES'][$arParams['PROPERTY_MOBILE_IMAGE']]['VALUE'];
							
							if (!empty($sPictureMobile)) {
								$sPictureMobile = CFile::ResizeImageGet(
									$sPictureMobile, [
										'width' => 800,
										'height' => 800
									],
									BX_RESIZE_IMAGE_PROPORTIONAL_ALT
								);

								if (!empty($sPictureMobile))
									$sPictureMobile = $sPictureMobile['src'];
							}

						?>
						
						
						
						
						
						
							<div id="block-scheme-default-tab-<?= $key ?>" data-block-type="content">
								<?= Html::beginTag('div', [
									'class' => Html::cssClassFromArray([
										'widget-item' => true,
									], true)
								]) ?>
									<?= Html::beginTag('div', [
										'id' => $sAreaId,
										'class' => 'widget-item-wrapper',
									]) ?>
										<div class="widget-item-picture-wrapper">
										
											
											<?= Html::beginTag('div', [
												'class' => 'widget-item-picture',
												'data' => [
													'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
													'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
												],
												'style' => [
													'background-image' => !empty($sPicture) ? (!$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null) : false,
												]
											]) ?>
											
												<?= Html::beginTag('div', [
													'class' => 'widget-item-picture-mobile',
													'data' => [
														'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
														'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureMobile : null
													],
													'style' => [
														'background-image' => !empty($sPictureMobile) ? (!$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPictureMobile.'\')' : null) : false,
													]
												]) ?>
												<?= Html::endTag('div') ?>
										
										
												<?php /*if ($arVisual['STICKER']['SHOW'] && !empty($arData['STICKER'])) { ?>
													<div class="widget-item-sticker-wrap" data-horizontal="<?= $arVisual['STICKER']['HORIZONTAL'] ?>">
														<div class="widget-item-aligner"></div>
														<div class="widget-item-sticker" data-vertical="<?= $arVisual['STICKER']['VERTICAL'] ?>">
															<?= $arData['STICKER'] ?>
														</div>
													</div>
												<?php }*/ ?>
												<div class="widget-item-name-wrap" data-horizontal="<?= $arVisual['NAME']['HORIZONTAL'] ?>">
													<div class="widget-item-aligner"></div>
													<div class="widget-item-name" data-vertical="middle">
														<div class="widget-item-name-previw-text">
															<?= $arItem['PREVIEW_TEXT'] ?>
														</div>
														<div class="widget-item-name-detail-text">
															<?= $arItem['DETAIL_TEXT'] ?>
														</div>
														<?php if (!empty($arItem['DETAIL_PAGE_URL'])) { ?>
															<a <?= 'href="'.$arItem['DETAIL_PAGE_URL'].'"' ?> <?= $arVisual['LINK']['BLANK'] ? 'target="_blank"' : ''  ?> class="widget-item-name-detail-button intec-cl-background intec-cl-background-light-hover">
																<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L8.34315 15.0711C7.95262 15.4616 7.31946 15.4616 6.92893 15.0711C6.53841 14.6805 6.53841 14.0474 6.92893 13.6569L12.5858 8L6.92893 2.34315C6.53841 1.95262 6.53841 1.31946 6.92893 0.928932C7.31946 0.538408 7.95262 0.538408 8.34315 0.928932L14.7071 7.29289ZM0 7L14 7V9L0 9L0 7Z" fill="white"/>
																</svg>
															</a>
														<?php } ?>
													</div>
													
												</div>
												<?php if (!empty($sPicturePreview)) { ?>
													<?= Html::beginTag('div', [
														'class' => 'widget-item-picture-preview',
														'data' => [
															'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
															'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicturePreview : null
														],
														'style' => [
															'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicturePreview.'\')' : null,
														]
													]) ?>
													<?= Html::endTag('div') ?>
												<?php } ?>
											
											
											
											
											<?= Html::endTag('div') ?>
											
										</div>
									<?= Html::endTag('div') ?>
								<?= Html::endTag('div') ?>
							</div>
							
							
							
							
							
							
							
							
						<?php } ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const company = $('#<?= $sTemplateId ?>');

$($(company).find('.widget-custom-tabs [data-block-type="tab"]')[0]).addClass('active');
$($(company).find('.widget-custom-tabs-content [data-block-type="content"]')[0]).addClass('active');
$($(company).find('.widget-custom-tabs-content [data-block-type="content"]')[0]).fadeIn(0);

$(company).find('.widget-custom-tabs [data-block-type="tab"]').click(function () {
	$(company).find('.widget-custom-tabs .active').removeClass('active');
	$(company).find('.widget-custom-tabs-content .active').fadeOut(0);
	$(company).find('.widget-custom-tabs-content .active').removeClass('active');

	
	$(this).addClass('active');
	var contentId = $(this).find('a').attr('href');
	
	var content = $(company).find('.widget-custom-tabs-content ' + contentId);
	content.addClass('active');
	content.fadeIn(200);
	
	
	return false;
});
</script>