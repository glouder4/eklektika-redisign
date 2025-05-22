<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\FileHelper;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$bPartLeftShow = $arResult['SEARCH']['SHOW'] ||
    $arResult['EMAIL']['SHOW'] ||
    $arResult['PHONE']['SHOW'] ||
    $arResult['ADDRESS']['SHOW'] ||
    $arResult['CONTACTS']['USE'];

$bPartRightShow = $arResult['MENU']['MAIN']['SHOW'];
$bPartsShow =
    $bPartLeftShow ||
    $bPartRightShow;

$bParts2Show =
    $arResult['FORMS']['CALL']['SHOW'] ||
    $arResult['SEARCH']['SHOW'];

$bPanelShow =
    $arResult['COPYRIGHT']['SHOW'] ||
    $arResult['LOGOTYPE']['SHOW'] ||
    $arResult['SOCIAL']['SHOW'];

$bPhoneShow = false;
$bAddressShow = false;

if ($arResult['CONTACTS']['USE']) {
    foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) {
        if (!empty($arContact['DATA']['PHONE']))
            $bPhoneShow = true;

        if (!empty($arContact['DATA']['ADDRESS']))
            $bAddressShow = true;
    }

    unset($arContact);
} else {
    $bPhoneShow = $arResult['PHONE']['SHOW'];
    $bAddressShow = $arResult['ADDRESS']['SHOW'];
}

?>
<div class="widget-view-6 intec-content-wrap" style="overflow: hidden" id="custom-footer">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($bPartsShow) { ?>
                <div class="<?= Html::cssClassFromArray([
                    'widget-parts',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-h-start',
                        'a-v-start',
                        '768-wrap'
                    ]
                ]) ?>">
					<div class="widget-part widget-part-right intec-grid-item intec-grid-item-768-1">
                        <?php if ($bPartLeftShow) { ?>
                            <?php include(__DIR__.'/../../parts/menu/main.columns.1.php') ?>
                        <?php } ?>
                    </div>
                    <?php if ($bPartRightShow) { ?>
                        <div class="widget-part widget-part-left intec-grid-item-auto intec-grid-item-768-1">
                            <div class="widget-part-items">
								<?php if ($arResult['LOGOTYPE']['SHOW']) { ?>
									<div class="widget-panel-item widget-logotype-wrap intec-grid-item-auto intec-grid-item-768-1">
										<div class="widget-logotype">
											<?php if ($arResult['LOGOTYPE']['LINK']) { ?>
												<a target="_blank" href="<?= $arResult['LOGOTYPE']['LINK'] ?>" class="widget-logotype-wrapper">
											<?php } ?>
												<?php include(__DIR__.'/../../parts/logotype.php') ?>
											<?php if ($arResult['LOGOTYPE']['LINK']) { ?>
												</a>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
                                <?php if ($arResult['EMAIL']['SHOW']) { ?>
                                    <div class="widget-part-item widget-email">
                                        <span class="widget-part-item-icon widget-part-item-icon-email">
                                            <?=FileHelper::getFileData(__DIR__."/../../svg/icon-email.svg");?>
                                        </span>
                                        <a class="email widget-part-item-text" href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>">
                                            <?= $arResult['EMAIL']['VALUE'] ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!$arResult['CONTACTS']['USE'] && $bPhoneShow) { ?>
                                    <div class="widget-part-item widget-phone">
                                        <span class="widget-part-item-icon">
                                            <i class="fas fa-phone fa-flip-horizontal"></i>
                                        </span>
                                        <a class="tel widget-part-item-text intec-cl-text" href="tel:<?= $arResult['PHONE']['VALUE']['LINK'] ?>">
                                            <span class="value"><?= $arResult['PHONE']['VALUE']['DISPLAY'] ?></span>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!$arResult['CONTACTS']['USE'] && $bAddressShow) { ?>
                                    <div class="widget-part-item widget-address">
                                        <span class="widget-part-item-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <span class="adr widget-part-item-text intec-cl-text">
                                            <span class="locality"><?= $arResult['ADDRESS']['VALUE'] ?></span>
                                        </span>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['CONTACTS']['USE']) { ?>
                                    <div class="widget-part-item widget-contacts">
                                        <div class="widget-contacts-items">
											<?php foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) { ?>
												<?php if (empty($arContact['DATA']['PHONE'])) continue ?>
												<div class="widget-contacts-item">
													<div class="widget-contacts-item-phone intec-grid intec-grid-i-h-4 intec-grid-a-v-center">
														<div class="intec-grid-item-auto" style="font-size: 0; line-height: 0">
															<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M9.95133 12.0486C8.87883 10.9761 8.07034 9.77526 7.53317 8.55701C7.4195 8.29942 7.48642 7.99784 7.68533 7.79892L8.43609 7.04909C9.05117 6.43401 9.05117 5.56409 8.514 5.02692L7.43784 3.95076C6.72192 3.23484 5.56142 3.23484 4.8455 3.95076L4.24783 4.54842C3.56858 5.22767 3.28533 6.20759 3.46867 7.17926C3.9215 9.57451 5.313 12.1971 7.55792 14.442C9.80283 16.6869 12.4254 18.0784 14.8207 18.5313C15.7923 18.7146 16.7723 18.4313 17.4515 17.7521L18.0483 17.1553C18.7642 16.4394 18.7642 15.2789 18.0483 14.563L16.973 13.4878C16.4358 12.9506 15.565 12.9506 15.0288 13.4878L14.201 14.3164C14.0021 14.5153 13.7005 14.5823 13.4429 14.4686C12.2247 13.9305 11.0238 13.1211 9.95133 12.0486V12.0486Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
															</svg>
														</div>
														<div class="intec-grid-item-auto">
															<a href="tel:<?= $arContact['DATA']['PHONE']['LINK'] ?>" class="tel">
																<span class="value"><?= $arContact['DATA']['PHONE']['DISPLAY'] ?></span>
															</a>
														</div>
														<div class="intec-grid-item-auto" style="font-size: 0; line-height: 0; cursor: pointer">
															<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path fill-rule="evenodd" clip-rule="evenodd" d="M10.69 12.6866L8.33841 9.8024C8.12933 9.54666 8.31456 9.16659 8.64845 9.16659H13.3515C13.6854 9.16659 13.8707 9.54666 13.6616 9.8024L11.31 12.6866C11.151 12.8821 10.849 12.8821 10.69 12.6866Z" fill="white"/>
															</svg>
														</div>
													</div>
												</div>
												<?php break ?>
											<?php } ?>
											<div class="widget-contacts-items-popup">
												<?php $isFirst = true ?>
												<?php foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) { ?>
													<?php if (empty($arContact['DATA']['PHONE'])) continue  ?>
													<?php if ($isFirst) {
														$isFirst = false;
														continue;
													} ?>
													<div class="widget-contacts-items-popup-item widget-contacts-item-phone">
														<a href="tel:<?= $arContact['DATA']['PHONE']['LINK'] ?>" class="tel">
															<span class="value"><?= $arContact['DATA']['PHONE']['DISPLAY'] ?></span>
														</a>
													</div>
												<?php } ?>
											</div>
                                        </div>
										<div class="widget-contacts-items-time">
											<?= $arParams['COMPANY_TIME'] ?>
										</div>
                                    </div>
                                <?php } ?>
								<?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
									<div class="widget-form intec-grid-item intec-grid-item-650-1">
										<?= Html::tag('div', Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_6_FORMS_CALL_BUTTON'), [
											'class' => Html::cssClassFromArray([
												'widget-form-button' => true,
											], true),
											'data' => [
												'action' => 'forms.call.open'
											]
										]) ?>
										<?php include(__DIR__.'/../../parts/forms/call.php') ?>
									</div>
								<?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
			<div class="footer-border"></div>
            <?php if ($bParts2Show) { ?>
                <div class="widget-parts-2 intec-grid intec-grid-650-wrap intec-grid-a-h-start intec-grid-a-v-center">
                    <?php if ($arResult['SEARCH']['SHOW']) { ?>
                        <div class="widget-part-2 widget-search intec-grid-item-auto intec-grid-item-650-1">
                            <?php
                                $arSearch = [
                                    'TEMPLATE' => 'input.3'
                                ];

                                include(__DIR__.'/../../parts/search.php');
                            ?>
                        </div>
                    <?php } ?>
					<div class="footer-addresses-block">
						<?php if ($arResult['CONTACTS']['USE']) { ?>
							<div class="intec-grid intec-grid-i-h-18 widget-addresses">
								<?php foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) { ?>
									<?php if (empty($arContact['DATA']['ADDRESS'])) continue ?>
									<div class="intec-grid-item-5 intec-grid-item-768-auto widget-addresses-elem">
										<div class="widget-addresses-item-name">
											<div class="widget-contacts-item-address intec-grid intec-grid-i-h-4 intec-grid-a-v-center">
												<div class="intec-grid-item-auto" style="font-size: 0; line-height: 0">
													<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M11 11.9167C9.48108 11.9167 8.25 10.6857 8.25 9.16675C8.25 7.64783 9.48108 6.41675 11 6.41675C12.5189 6.41675 13.75 7.64783 13.75 9.16675C13.75 10.6857 12.5189 11.9167 11 11.9167Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
														<path d="M11 19.25C11 19.25 4.58331 13.9792 4.58331 9.16667C4.58331 5.62283 7.45615 2.75 11 2.75C14.5438 2.75 17.4166 5.62283 17.4166 9.16667C17.4166 13.9792 11 19.25 11 19.25Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
													</svg>
												</div>
												<div class="intec-grid-item-auto">
													<span class="value"><?= $arContact['NAME'] ?></span>
												</div>
												<div class="intec-grid-item-auto slidedown-button" style="font-size: 0; line-height: 0; cursor: pointer">
													<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M10.69 12.6866L8.33841 9.8024C8.12933 9.54666 8.31456 9.16659 8.64845 9.16659H13.3515C13.6854 9.16659 13.8707 9.54666 13.6616 9.8024L11.31 12.6866C11.151 12.8821 10.849 12.8821 10.69 12.6866Z" fill="white"></path>
													</svg>
												</div>
											</div>
										</div>
										<div class="widget-addresses-item-address">
											<span class="adr">
												<span class="locality"><?= $arContact['DATA']['ADDRESS'] ?></span>
											</span>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
                </div>
            <?php } ?>
			<div class="footer-border"></div>
			<?php if ($arResult['SOCIAL']['SHOW']) { ?>
				<div class="widget-panel-item widget-social-wrap intec-grid-item intec-grid-item-768-1">
					<!--noindex-->
					<div class="widget-social intec-grid intec-grid-a-h-center">
						<div class="widget-social-items intec-grid-item-auto intec-grid-a-h-end intec-grid-a-h-768-center intec-grid intec-grid-wrap intec-grid-i-7 intec-grid-a-v-center">
							<?php foreach ($arResult['SOCIAL']['ITEMS'] as $arItem) { ?>
								<?php if (!$arItem['SHOW']) continue ?>
								<a rel="nofollow" target="_blank" href="<?= $arItem['LINK'] ?>" class="widget-social-item intec-image-effect intec-grid-item-auto">
									<div class="widget-social-item-icon" data-grey="<?= $arResult['SOCIAL']['GREY'] ?>" data-social-icon="<?= $arItem['CODE'] ?>" data-social-icon-square="<?= $arResult['SOCIAL']['SQUARE'] ?>"></div>
								</a>
							<?php } ?>
						</div>
					</div>
					<!--/noindex-->
				</div>
			<?php } ?>
            <div id="bx-composite-banner"></div>
            <?php if ($bPanelShow) { ?>
                <div class="widget-panel">
                    <div class="<?= Html::cssClassFromArray([
                        'widget-panel-items',
                        'intec-grid' => [
                            '',
                            'nowrap',
                            'a-h-start',
                            'a-v-center',
                            '768-wrap'
                        ]
                    ]) ?>">
                        <?php if ($arResult['COPYRIGHT']['SHOW']) { ?>
                            <div class="widget-panel-item widget-copyright-wrap intec-grid-item intec-grid-item-768-1">
                                <!--noindex-->
                                <div class="widget-copyright">
                                    <?= $arResult['COPYRIGHT']['VALUE'] ?>
                                </div>
                                <!--/noindex-->
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-768-1"></div>
                        <?php } ?>
                        
						<?/*<div class="widget-panel-item intec-grid-item-auto intec-grid-item-768-1 footer-intec-logotype-wrapper" style="font-size:0;line-height:0">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "inc",
									"EDIT_TEMPLATE" => "",
									"PATH" => "/include/logotype-intec.php"
								)
							);?>
						</div>*/?>
						<div class="widget-panel-item intec-grid-item intec-grid-item-700-1">
							<div class="widget-icons intec-grid intec-grid-wrap intec-grid-a-h-end intec-grid-a-h-768-center intec-grid-a-v-center intec-grid-i-h-12">
								<?php foreach ($arResult['ICONS']['ITEMS'] as $arItem) { ?>
								<?php if (!$arItem['SHOW']) continue ?>
									<div class="widget-icon intec-grid-item-auto" data-icon="<?= StringHelper::toLowerCase($arItem['CODE']) ?>">
										<div class="widget-icon-image"></div>
									</div>
								<?php } ?>
							</div>
						</div>
                    </div>
                </div>
            <?php } ?>
			<div class="logo-decoration">
				<svg width="560" height="684" viewBox="0 0 560 684" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M225.859 631.511V399.066L13.1031 89.8322C4.66805 79.9274 0.032281 67.452 0 54.5604C0.0169611 46.8909 1.68052 39.3101 4.88258 32.3093C8.08463 25.3086 12.7556 19.0439 18.5899 13.9206C29.1085 4.73059 42.7842 -0.237851 56.885 0.00875946C64.343 0.0376617 71.6882 1.78896 78.3235 5.12008C84.9588 8.4512 90.6956 13.268 95.0681 19.1784L281.063 294.235L464.815 18.1929C469.767 12.4348 475.96 7.81838 482.949 4.6748C489.937 1.53122 497.549 -0.0621769 505.24 0.00875946C518.111 0.0558819 530.54 4.60178 540.287 12.8255C546.527 17.6771 551.549 23.8626 554.965 30.9073C558.381 37.952 560.101 45.6681 559.995 53.4646C559.804 66.347 555.146 78.784 546.783 88.7371L335.709 399.066V631.511C335.771 638.502 334.391 645.434 331.654 651.892C328.918 658.351 324.879 664.204 319.779 669.101C314.678 673.999 308.623 677.84 301.971 680.397C295.319 682.953 288.207 684.172 281.063 683.98C273.918 684.172 266.809 682.953 260.157 680.397C253.505 677.84 247.447 673.999 242.346 669.101C237.246 664.204 233.207 658.351 230.471 651.892C227.734 645.434 226.356 638.502 226.419 631.511H225.859Z" fill="#FED16D"/>
				</svg>
			</div>
        </div>
    </div>
</div>
<script>
$('#custom-footer.widget-view-6 .widget-contacts-items').hover(
	function () {
		$(this).find('.widget-contacts-items-popup').fadeIn();
	}, function () {
		$(this).find('.widget-contacts-items-popup').fadeOut();
	}
);
$('#custom-footer.widget-view-6 .slidedown-button').click(
	function () {
		var slide = $(this).closest('.widget-addresses-elem').find('.widget-addresses-item-address');
		if ($(slide).css('display') == 'none') {
			slide.slideDown();
		} else {
			slide.slideUp();
		}
	}
);
</script>
