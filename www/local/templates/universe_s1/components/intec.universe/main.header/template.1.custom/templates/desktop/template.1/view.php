<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sTemplateType = $arData['type'];
$bPanelShow = false;
$bContainerShow = false;

$arMenuMain = $arResult['MENU']['MAIN'];
$arMenuInfo = $arResult['MENU']['INFO'];

foreach (['AUTHORIZATION', 'ADDRESS', 'EMAIL'] as $sBlock)
    $bPanelShow = $bPanelShow || $arResult[$sBlock]['SHOW']['DESKTOP'];

if ($arMenuInfo['SHOW'])
    $bPanelShow = true;

$bContactsShow =
    $arResult['ADDRESS']['SHOW']['DESKTOP'] ||
    $arResult['REGIONALITY']['USE'] ||
    $arResult['EMAIL']['SHOW']['DESKTOP'];

if ($bContactsShow)
    $bPanelShow = true;

foreach (['LOGOTYPE', 'TAGLINE', 'CONTACTS', 'BASKET', 'DELAY', 'COMPARE'] as $sBlock)
    $bContainerShow = $bContainerShow || $arResult[$sBlock]['SHOW']['DESKTOP'];

$bBasketShow =
    $arResult['BASKET']['SHOW']['DESKTOP'] ||
    $arResult['DELAY']['SHOW']['DESKTOP'] ||
    $arResult['COMPARE']['SHOW']['DESKTOP'];

$sMenuPosition = false;
$sSearchPosition = false;
$sPhonesPosition = false;
$sSocialPosition = false;

if ($arMenuMain['SHOW']['DESKTOP'])
    $sMenuPosition = $arMenuMain['POSITION'];

if ($sMenuPosition === 'top')
    $bContainerShow = true;

if ($arResult['SEARCH']['SHOW']['DESKTOP']) {
    $sSearchPosition = 'bottom';

    if ($sMenuPosition == 'top')
        $sSearchPosition = 'top';
}

if ($sSearchPosition === 'top')
    $bPanelShow = true;

if ($sSearchPosition === 'bottom')
    $bContainerShow = true;

if ($arResult['CONTACTS']['SHOW']['DESKTOP'])
    $sPhonesPosition = $arResult['CONTACTS']['POSITION'];

if ($sPhonesPosition === 'top')
    $bPanelShow = true;

if ($arResult['SOCIAL']['SHOW']['DESKTOP'])
    $sSocialPosition = $arResult['SOCIAL']['POSITION'];

if ($sSocialPosition !== false)
    $bPanelShow = true;

$arContacts = [];
$arContact = null;

if ($arResult['CONTACTS']['SHOW']) {
    $arContacts = $arResult['CONTACTS']['VALUES'];
    $arContact = $arResult['CONTACTS']['SELECTED'];
}

?>
<div class="widget-view-desktop-1<?= $sMenuPosition !== 'bottom' ? ' widget-view-desktop-1-bordered' : null ?>">
    <?php //$APPLICATION->ShowViewContent('template-header-desktop-before') ?>
    <?php if ($bPanelShow) { ?>
        <div class="widget-panel">
            <div class="intec-content intec-content-visible intec-content-primary">
                <div class="intec-content-wrapper">
                    <div class="widget-panel-wrapper">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'intec-grid' => [
                                    '',
                                    'wrap',
                                    'a-h-center',
                                    'a-v-center',
                                ]
                            ]
                        ])?>
                            <?php if ($sSocialPosition === 'left') { ?>
                                <?php include(__DIR__.'/../../../parts/social.php') ?>
                            <?php } ?>
                            <?php if (($bContactsShow && $sSocialPosition !== 'left') || $arMenuInfo['SHOW']) { ?>
                                <div class="widget-panel-items-wrap intec-grid-item-auto">
                                    <div class="widget-panel-items widget-panel-items-visible" >
                                        <div class="widget-panel-items-wrapper">
                                            <?php if ($arMenuInfo['SHOW']) { ?>
                                                <div class="widget-panel-item">
                                                    <div class="widget-panel-item-wrapper">
                                                        <?php include(__DIR__.'/parts/menu/info.php') ?>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <?php /*include(__DIR__.'/parts/region.php') ?>
                                                <?php include(__DIR__.'/parts/address.php') ?>
                                                <?php include(__DIR__.'/parts/email.php')*/ ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($sSocialPosition === 'center') { ?>
                                <div class="intec-grid-item"></div>
                                <?php include(__DIR__.'/../../../parts/social.php') ?>
                            <?php } ?>
                            <div class="intec-grid-item"></div>
                            <?php if ($sPhonesPosition === 'top') { ?>
                                <div class="widget-panel-phone-wrap intec-grid-item-auto">
                                    <div class="widget-panel-phone intec-ui-align intec-grid intec-grid-a-v-center" data-block="phone" data-multiple="<?= !empty($arContacts) ? 'true' : 'false' ?>" data-expanded="false">
                                        <div class="widget-panel-phone-icon intec-ui-icon intec-ui-icon-phone-1 intec-cl-text"></div>
                                        <div class="widget-panel-phone-content intec-grid intec-grid-o-vertical">
                                            <div class="widget-panel-phone-wrapper intec-grid intec-grid-o-vertical">
                                                <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                                                    <?php foreach ($arContact as $arContactItem) { ?>
                                                        <a href="tel:<?= $arContactItem['PHONE']['VALUE'] ?>" class="tel widget-panel-phone-text intec-cl-text-hover" data-block-action="popup.open">
                                                            <span class="value"><?= $arContactItem['PHONE']['DISPLAY'] ?></span>
                                                        </a>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php foreach ($arContact as $arContactItem) { ?>
                                                        <a href="tel:<?= $arContactItem['VALUE'] ?>" class="tel widget-panel-phone-text intec-cl-text-hover" data-block-action="popup.open">
                                                            <span class="value"><?= $arContactItem['DISPLAY'] ?></span>
                                                        </a>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php if (!empty($arContacts)) { ?>
                                                <div class="widget-panel-phone-popup" data-block-element="popup">
                                                    <div class="widget-panel-phone-popup-wrapper scrollbar-inner">
                                                        <?php if ($arResult['CONTACTS']['ADVANCED']) {
                                                            $sScheduleString = '';
                                                        ?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <div class="widget-panel-phone-popup-contacts">
                                                                    <?php if (!empty($arContact['PHONE'])) { ?>
                                                                        <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="tel widget-panel-phone-popup-contact phone intec-cl-text-hover">
                                                                            <span class="value"><?= $arContact['PHONE']['DISPLAY'] ?></span>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['ADDRESS'])) { ?>
                                                                        <div class="adr widget-panel-phone-popup-contact address">
                                                                            <span class="locality"><?= $arContact['ADDRESS'] ?></span>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <?php if (!empty($arContact['SCHEDULE'])) { ?>
                                                                        <div class="widget-panel-phone-popup-contact schedule">
                                                                            <?php if (is_array($arContact['SCHEDULE'])) { ?>
                                                                                <?php foreach ($arContact['SCHEDULE'] as $sValue) { ?>
                                                                                    <?= $sValue ?>
                                                                                    <?php $sScheduleString .= $sValue.', '; ?>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <?= $arContact['SCHEDULE'] ?>
                                                                                <?php $sScheduleString .= $arContact['SCHEDULE'].', '; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['EMAIL'])) { ?>
                                                                        <a href="mailto:<?= $arContact['EMAIL'] ?>" class="email widget-panel-phone-popup-contact email intec-cl-text-hover">
                                                                            <?= $arContact['EMAIL'] ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php }
                                                                $sScheduleString = substr($sScheduleString, 0, (strlen($sScheduleString) - 2));
                                                            ?>
                                                            <span class="workhours">
                                                                <span class="value-title" title="<?=$sScheduleString?>"></span>
                                                            </span>
                                                            <?php unset($sScheduleString); ?>
                                                        <?php } else { ?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <a href="tel:<?= $arContact['VALUE'] ?>" class="tel widget-panel-phone-popup-item intec-cl-text-hover">
                                                                    <span class="value"><?= $arContact['DISPLAY'] ?></span>
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($arContacts)) { ?>
                                            <div class="widget-panel-phone-arrow" data-block-action="popup.open">
                                                <i class="far fa-chevron-down"></i>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (($bContactsShow && $arMenuInfo['SHOW']) || ($bContactsShow && $sSocialPosition === 'left')) { ?>
                                <div class="widget-panel-items-wrap intec-grid-item-auto">
                                    <div class="widget-panel-items widget-panel-items-visible">
                                        <div class="widget-panel-items-wrapper">
                                            <?php /*include(__DIR__.'/parts/region.php') ?>
                                            <?php include(__DIR__.'/parts/address.php') ?>
                                            <?php include(__DIR__.'/parts/email.php')*/ ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
							<?php if ($sPhonesPosition === 'bottom') { ?>
								<div class="widget-container-contacts-wrap intec-grid-item-auto">
									<div class="widget-container-item widget-container-contacts intec-grid intec-grid-i-h-7" data-block="phone" data-multiple="<?= !empty($arContacts) ? 'true' : 'false' ?>" data-expanded="false">
										<div class="intec-grid-item-auto">
											<div class="widget-container-phone">
												<div class="widget-container-phone-content">
													<?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
														<?php foreach ($arContact as $arContactItem) { ?>
															<a href="tel:<?= $arContactItem['PHONE']['VALUE'] ?>" class="tel widget-container-phone-text intec-cl-text-hover" data-block-action="popup.open">
																<span class="value"><?= $arContactItem['PHONE']['DISPLAY'] ?></span>
															</a>
														<?php } ?>
													<?php } else { ?>
														<?php foreach ($arContact as $arContactItem) { ?>
															<a href="tel:<?= $arContactItem['VALUE'] ?>" class="tel widget-container-phone-text intec-cl-text-hover" data-block-action="popup.open">
																<span class="value"><?= $arContactItem['DISPLAY'] ?></span>
															</a>
														<?php } ?>
													<?php } ?>
													<?php if (!empty($arContacts)) { ?>
														<div class="widget-container-phone-popup" data-block-element="popup">
															<div class="widget-container-phone-popup-wrapper scrollbar-inner">
																<?php if ($arResult['CONTACTS']['ADVANCED']) {
																	$sScheduleString = '';
																	?>
																	<?php foreach ($arContacts as $arContact) { ?>
																		<div class="widget-container-phone-popup-contacts">
																			<?php if (!empty($arContact['PHONE'])) { ?>
																				<a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="tel widget-container-phone-popup-contact phone intec-cl-text-hover">
																					<span class="value"><?= $arContact['PHONE']['DISPLAY'] ?></span>
																				</a>
																			<?php } ?>
																			<?php if (!empty($arContact['ADDRESS'])) { ?>
																				<div class="widget-container-phone-popup-contact address adr">
																					<?php if (Type::isArray($arContact['ADDRESS'])) { ?>
																						<?php foreach ($arContact['ADDRESS'] as $sValue) { ?>
																							<div class="locality"><?= $sValue ?></div>
																						<?php } ?>
																					<?php } else { ?>
																						<span class="locality"><?= $arContact['ADDRESS'] ?></span>
																					<?php } ?>
																				</div>
																			<?php } ?>
																			<?php if (!empty($arContact['SCHEDULE'])) { ?>
																				<div  class="widget-container-phone-popup-contact schedule">
																					<?php if (Type::isArray($arContact['SCHEDULE'])) { ?>
																						<?php foreach ($arContact['SCHEDULE'] as $sValue) { ?>
																							<?= $sValue ?>
																							<?php $sScheduleString .= $sValue.', '; ?>
																						<?php } ?>
																					<?php } else { ?>
																						<?= $arContact['SCHEDULE'] ?>
																						<?php $sScheduleString .= $arContact['SCHEDULE'].', '; ?>
																					<?php } ?>
																				</div>
																			<?php } ?>
																			<?php if (!empty($arContact['EMAIL'])) { ?>
																				<a href="mailto:<?= $arContact['EMAIL'] ?>" class="widget-container-phone-popup-contact email intec-cl-text-hover">
																					<span class="value"><?= $arContact['EMAIL'] ?></span>
																				</a>
																			<?php } ?>
																		</div>
																	<?php }
																	$sScheduleString = substr($sScheduleString, 0, (strlen($sScheduleString) - 2));
																	?>
																	<span class="workhours">
																		<span class="value-title" title="<?=$sScheduleString?>"></span>
																	</span>
																<?php
																	unset($sScheduleString);
																} else { ?>
																	<?php foreach ($arContacts as $arContact) { ?>
																		<a href="tel:<?= $arContact['VALUE'] ?>" class="tel widget-container-phone-popup-item intec-cl-text-hover">
																			<?= $arContact['DISPLAY'] ?>
																		</a>
																	<?php } ?>
																<?php } ?>
															</div>
														</div>
													<?php } ?>
												</div>
												<?php if (!empty($arContacts)) { ?>
													<div class="widget-container-phone-arrow far fa-chevron-down" data-block-action="popup.open"></div>
												<?php } ?>
											</div>
										</div>
										<div class="intec-grid-item-auto">
											<?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
												<div class="widget-container-button-wrap">
													<div class="widget-container-button intec-cl-text-hover intec-cl-border-hover" data-action="forms.call.open">
														<?= Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_BUTTON') ?>
													</div>
													<?php include(__DIR__.'/../../../parts/forms/call.php') ?>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
                        <?= Html::endTag('div') ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($bContainerShow) { ?>
        <div class="widget-container">
            <div class="intec-content intec-content-visible intec-content-primary">
                <div class="intec-content-wrapper">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'widget-container-wrapper',
                            'intec-grid' => [
                                '',
                                'nowrap',
                                'a-h-start',
                                'a-v-top'
                            ]
                        ]
                    ]) ?>
                        <?php if ($arResult['LOGOTYPE']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-container-logotype-wrap intec-grid-item-auto">
                                <?= Html::beginTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div', [
                                    'href' => $arResult['LOGOTYPE']['LINK']['USE'] ? $arResult['LOGOTYPE']['LINK']['VALUE'] : null,
                                    'class' => Html::cssClassFromArray([
                                        'widget-container-item' => true,
                                        'widget-container-logotype' => true,
                                        'intec-ui-picture' => true
                                    ], true)
                                ]) ?>
                                    <?php include(__DIR__.'/../../../parts/logotype.php') ?>
                                <?= Html::endTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div') ?>
								<?php if ($arResult['TAGLINE']['SHOW']['DESKTOP']) { ?>
									<div class="widget-container-tagline-wrap intec-grid-item-auto">
										<div class="widget-container-item widget-container-tagline">
											<div class="widget-container-tagline-text">
												<?= htmlspecialchars_decode($arResult['TAGLINE']['VALUE']) ?>
											</div>
										</div>
									</div>
								<?php } ?>
                            </div>
                        <?php } ?>
						<div class="intec-grid-item intec-grid intec-grid-wrap intec-grid-a-v-top" style="position: unset;">
							<?php if ($sMenuPosition === 'top') { ?>
								<div class="widget-container-menu-wrap intec-grid-item intec-grid-item-shrink-1">
									<div class="widget-container-item widget-container-menu">
										<?php $arMenuParams = [
											'TRANSPARENT' => 'Y'
										] ?>
										<?php include(__DIR__.'/../../../parts/menu/main.horizontal.1.php') ?>
									</div>
								</div>
							<?php } else if ($sSearchPosition === 'bottom') { ?>
								<div class="widget-container-search-wrap intec-grid-item" style="position: unset;">
									<div class="widget-container-item widget-container-search">
										<?php include(__DIR__.'/../../../parts/search/input.1.php') ?>
									</div>
								</div>
							<?php } else { ?>
								<div class="intec-grid-item"></div>
							<?php } ?>
							<div class="intec-grid-item-auto intec-grid intec-grid-wrap intec-grid-a-v-top">
								<?php if ($arResult['FORMS']['CALCULATE']['SHOW']) { ?>
									<div class="widget-panel-buttons-wrap intec-grid-item-auto">
										<div class="widget-panel-button-wrapper widget-panel-button-calculate" data-action="forms.calculate.open">
											<div class="widget-panel-button-icon-svg">
												<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<g clip-path="url(#clip0_242_5672)">
														<path d="M2.5 20.1818V3.81818C2.5 3.33597 2.69156 2.87351 3.03253 2.53253C3.37351 2.19156 3.83597 2 4.31818 2H20.6818C21.164 2 21.6265 2.19156 21.9675 2.53253C22.3084 2.87351 22.5 3.33597 22.5 3.81818V20.1818C22.5 20.664 22.3084 21.1265 21.9675 21.4675C21.6265 21.8084 21.164 22 20.6818 22H4.31818C3.83597 22 3.37351 21.8084 3.03253 21.4675C2.69156 21.1265 2.5 20.664 2.5 20.1818Z" fill="none"/>
														<path d="M15.2276 7.4549H18.864M15.2276 15.1822H18.864M15.2276 17.9094H18.864M6.13672 7.4549H7.9549M9.77308 7.4549H7.9549M7.9549 7.4549V5.63672M7.9549 7.4549V9.27308M6.66945 17.8313L7.9549 16.5458M9.24126 15.2604L7.9549 16.5458M7.9549 16.5458L6.66945 15.2604M7.9549 16.5458L9.24126 17.8313" fill="none" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
														<rect x="0.8" y="0.3" width="23.4" height="23.4" fill="none" stroke="none" stroke-width="0.6"/>
													</g>
													<defs>
														<clipPath id="clip0_242_5672">
															<rect width="24" height="24" stroke="none" transform="translate(0.5)"/>
														</clipPath>
													</defs>
												</svg>
											</div>
											<div class="widget-panel-button-text">
												<?= Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP1_BUTTON2') ?>
											</div>
											<?php include(__DIR__.'/../../../parts/forms/calculate.php') ?>
										</div>
									</div>
								<?php } ?>
								<?php if ($bBasketShow) { ?>
									<div class="widget-panel-buttons-wrap intec-grid-item-auto">
											<?php include(__DIR__.'/../../../parts/basket.php') ?>
									</div>
								<?php } ?>
								<?php if (
									$arResult['AUTHORIZATION']['SHOW']['DESKTOP'] ||
									$sSearchPosition === 'top'
								) { ?>
									<div class="widget-panel-buttons-wrap intec-grid-item-auto">
										<?php if ($arResult['AUTHORIZATION']['SHOW']['DESKTOP']) { ?>
											<?php include(__DIR__.'/../../../parts/auth/panel.1.php') ?>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php /*if ($sMenuPosition === 'bottom') { ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'widget-menu' => [
                    '' => true,
                    'transparent' => $arResult['MENU']['MAIN']['TRANSPARENT']
                ]
            ], true)
        ]) ?>
            <?php if ($arResult['MENU']['MAIN']['TRANSPARENT']) $arMenuParams = [
                'TRANSPARENT' => $arResult['MENU']['MAIN']['TRANSPARENT'] ? 'Y' : 'N'
            ] ?>
            <?php include(__DIR__.'/../../../parts/menu/main.horizontal.1.php') ?>
        <?= Html::endTag('div') ?>
    <?php }*/ ?>
    <?php if ($sPhonesPosition !== false && !empty($arContacts) && !defined('EDITOR')) { ?>
        <script type="text/javascript">
            template.load(function (data) {
                var $ = this.getLibrary('$');
                var root = data.nodes;
                var block = $('[data-block="phone"]', root);
                var popup = $('[data-block-element="popup"]', block);
                var scrollContacts = $('.scrollbar-inner', popup);

                popup.open = $('[data-block-action="popup.open"]', block);
                popup.open.on('mouseenter', function () {
                    block.attr('data-expanded', 'true');
                });

                block.on('mouseleave', function () {
                    block.attr('data-expanded', 'false');
                });

                scrollContacts.scrollbar();
            }, {
                'name': '[Component] intec.universe:main.header (template.1) > desktop (template.1) > phone.expand',
                'nodes': <?= JavaScript::toObject('#'.$sTemplateId) ?>,
                'loader': {
                    'name': 'lazy'
                }
            });
        </script>
    <?php } ?>
    <?php //$APPLICATION->ShowViewContent('template-header-desktop-after') ?>
</div>
