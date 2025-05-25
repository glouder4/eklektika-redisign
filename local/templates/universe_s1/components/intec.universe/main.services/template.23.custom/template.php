<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$arForm = $arResult['FORM'];

$arForm['BUTTON'] = Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_23_ORDER_BUTTON_DEFAULT');

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

$iCounter = 0;
?>
<div class="widget c-services c-services-template-23" id="<?= $sTemplateId ?>">
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
                <div class="widget-items-wrap scrollbar-inner" data-role="scrollbar">
                    <div class="widget-items">
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $arData = $arItem['DATA'];
                            $arForm['PARAMETERS']['fields'][$arForm['FIELD']] = $arItem['NAME'];

                            $iCounter++;
							
							$sPicture = $arItem['PREVIEW_PICTURE'];

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
                        ?>
							<?php if ($arVisual['LINK']['USE']) { ?>
								<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="widget-item-a">
							<?php } ?>
								<div class="widget-item intec-grid intec-grid-a-v-center" id="<?= $sAreaId ?>" data-role="item">
									<div class="intec-grid-item">
										<div class="widget-item-property">
											<?= Html::tag('div', $arItem['NAME'], [
												'href' => $arVisual['LINK']['USE'] ? $arItem['DETAIL_PAGE_URL'] : null,
												'class' => Html::cssClassFromArray([
													'widget-item-name' => true
												], true),
												'data-role' => 'item.name'
											]) ?>
										</div>
									</div>
									<div class="intec-grid-item-auto">
										<div class="widget-item-svg">
											<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 25L25 1M25 1V25M25 1H1.54545" fill="none" stroke-width="2"/>
											</svg>
										</div>
									</div>
									
									<div class="widget-item-popup">
										<?= Html::beginTag('div', [
											'class' => 'widget-item-popup-image',
											'data' => [
												'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
												'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
											],
											'style' => [
												'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
											]
										]) ?>
										<?= Html::endTag('div') ?>
										<div class="widget-item-popup-content">
											<?php if ($arVisual['PRICE']['SHOW']) { ?>
												<div class="widget-item-price-wrap">
													<?php if (!empty($arData['PRICE']['VALUE'])) { ?>
														<div class="widget-item-price">
															<?= Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_23_PRICE_FORM').'<span class="bold">'.$arData['PRICE']['VALUE'].'</span>' ?>
														</div>
													<?php } ?>
												</div>
											<?php } ?>
											<div class="widget-item-description-wrap">
												<?= $arItem['PREVIEW_TEXT'] ?>
											</div>
										</div>
									</div>
								</div>
							<?php if ($arVisual['LINK']['USE']) { ?>
								</a>
							<?php } ?>
                        <?php } ?>
                    </div>
                </div>

                <?php if ($arForm['USE']) { ?>
                    <div class="widget-item-button-wrap">
                        <div class="intec-grid intec-grid-wrap intec-grid-i-4 intec-grid-a-h-end">
                            <div class="intec-grid-item-auto intec-grid-item-600-1">
                                <?= Html::tag('div', Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_23_BUTTON_CLEAR'), [
                                    'class' => [
                                        'widget-item-button',
                                        'widget-item-button-clear',
                                        'intec-cl-background-hover',
                                        'intec-cl-border-hover',
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'mod-round-2',
                                            'size-5',
                                            'mod-transparent'
                                        ]
                                    ],
                                    'data-role' => 'button.clear'
                                ]) ?>
                            </div>
                            <div class="intec-grid-item-auto intec-grid-item-600-1">
                                <?= Html::tag('div', Html::stripTags($arForm['BUTTON']), [
                                    'class' => [
                                        'widget-item-button',
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'mod-round-2',
                                            'size-5',
                                            'scheme-current'
                                        ]
                                    ],
                                    'data-role' => 'button.order'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {

	const followCursor = () => {
		const el = document.querySelectorAll('#<?= $sTemplateId ?> .widget-item-popup');

		$('#<?= $sTemplateId ?>').on('mousemove', e => {
			const target = e.target;
			if (!target.closest('#<?= $sTemplateId ?>')) return;

			$('#<?= $sTemplateId ?> .widget-item').hover(
				function () {
					$(this).find('.widget-item-popup').fadeIn(200);
				}, function () {
					$(this).find('.widget-item-popup').fadeOut(100);
				}
			);
			
			if (e.pageX + $(el).width() > $(window).width()) {
				$(el).css( 'left', e.pageX - $(el).width() + 30 + 'px' );
			} else {
				$(el).css( 'left', e.pageX + 'px' );
			}
			
			$(el).css( 'top', e.pageY + 'px' );
		})
	}

	followCursor();

})
</script>