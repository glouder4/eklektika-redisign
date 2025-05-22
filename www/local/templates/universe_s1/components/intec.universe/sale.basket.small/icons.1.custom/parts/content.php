<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var IntecBasketLiteComponent $component
 * @var CBitrixComponentTemplate $this
 * @var boolean $bStub
 */

$arTitlesCount = array(
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_1'),
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_2'),
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_3')
);
$fDeclOfNum = function ($fNumber, $arTitles)
{
    $arCasesTitle = array (2, 0, 1, 1, 1, 2);
    return $fNumber." ".$arTitles[
        ($fNumber%100 > 4 && $fNumber %100 < 20) ? 2 : $arCasesTitle[ min($fNumber%10, 5) ]
    ];
};
$sCountBasket = $fDeclOfNum($arResult['BASKET']['COUNT'], $arTitlesCount);
$sCountDelayed = $fDeclOfNum($arResult['DELAYED']['COUNT'], $arTitlesCount);

?>
<div class="sale-basket-small-content">
    <?php if (!$bStub) { ?>
        <div class="sale-basket-small-tabs" data-role="tabs">
            <?= Html::beginTag('div', [
                'class' => [
                    'sale-basket-small-items',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                    ]
                ]
            ]) ?>
                <?php if ($arResult['DELAYED']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['DELAYED']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['DELAYED']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="delay">
                        <?= Html::beginTag($sTag, [
                            'class' => Html::cssClassFromArray([
                                'sale-basket-small-tab' => [
                                    '' => true,
                                    'active' => $bActive
                                ],
                                'intec-cl-text' => [
                                    '' => $bActive,
                                    'hover' => true
                                ]
                            ], true),
                            'data-role' => "tab.icon",
                            'rel' => $sTag === 'a' ? 'nofollow' : null,
                            'href' => !empty($arResult['URL']['DELAYED']) ? $arResult['URL']['DELAYED'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <div class="widget-panel-button-icon-svg">
									<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.3858 5.18725L12.5 5.32136L12.6142 5.18725C13.6767 3.93959 15.3054 3.15 17 3.15C19.9972 3.15 22.35 5.50284 22.35 8.5C22.35 10.3407 21.5235 12.0227 20.0401 13.8363C18.5559 15.6509 16.4297 17.5789 13.8537 19.9148L13.8492 19.9189L13.849 19.9191L12.4996 21.1475L11.1509 19.929L11.1506 19.9287L11.1192 19.9002C8.55541 17.5704 6.43894 15.6471 4.95989 13.8376C3.47649 12.0227 2.65 10.3407 2.65 8.5C2.65 5.50284 5.00284 3.15 8 3.15C9.69458 3.15 11.3233 3.93959 12.3858 5.18725ZM6.55104 13.09C7.93811 14.7108 9.92042 16.5072 12.2966 18.6587L12.3939 18.7561L12.5 18.8621L12.6061 18.7561L12.7034 18.6587C15.0796 16.5072 17.0619 14.7108 18.449 13.09C19.8351 11.4703 20.65 10.0004 20.65 8.5C20.65 6.41716 19.0828 4.85 17 4.85C15.4386 4.85 13.9142 5.82831 13.3383 7.21H11.6711C11.086 5.8288 9.5619 4.85 8 4.85C5.91716 4.85 4.35 6.41716 4.35 8.5C4.35 10.0004 5.16489 11.4703 6.55104 13.09Z" stroke="none" stroke-width="0.3"/>
									</svg>
									<?php if ($bActive) { ?>
										<span class="sale-basket-small-tab-counter intec-cl-background-dark">
											<?= Html::encode($arResult['DELAYED']['COUNT']) ?>
										</span>
									<?php } ?>
								</div>
								<div class="widget-panel-button-text">
									<?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_IZBRANNOE')?>
								</div>
                            </span>
                        <?= Html::endTag($sTag) ?>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-small-popup sale-basket-small-popup-delayed" data-role="tab.popup">
                                <div class="sale-basket-small-popup-wrapper">
                                    <div class="sale-basket-small-header">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                            <div class="sale-basket-small-header-text intec-grid-item">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DELAYED_TITLE')?>
                                                <span class="sale-basket-small-header-count">
                                                    <?= $sCountDelayed ?>
                                                </span>
                                            </div>
                                            <div class="sale-basket-small-header-clear-wrap intec-grid-item-auto">
                                                <div data-role="button"
                                                     data-action="delayed.clear"
                                                     class="sale-basket-small-header-clear intec-ui intec-ui-control-button intec-ui-size-2 intec-ui-state-hover intec-cl-background-hover intec-cl-border-hover">
                                                    <div class="intec-ui-part-icon">
                                                        <i class="fal fa-times"></i>
                                                    </div>
                                                    <div class="intec-ui-part-content">
                                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CLEAR')?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sale-basket-small-body">
                                    <?php
                                        $arItems = $arResult['DELAYED']['ITEMS'];
                                        include(__DIR__.'/content/products.php');
                                    ?>
                                    </div>
                                    <div class="sale-basket-small-footer-wrap">
                                        <div class="sale-basket-small-footer">
                                            <?php if (!empty($arResult['URL']['DELAYED'])) { ?>
                                                <div class="sale-basket-small-footer-buttons intec-grid intec-grid-nowrap intec-grid-a-v-start">
                                                    <div class="intec-grid-item">
                                                        <a rel="nofollow" href="<?= $arResult['URL']['DELAYED'] ?>"
                                                           class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-scheme-current intec-ui-size-2">
                                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_TO_BASKET') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['BASKET']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['BASKET']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['BASKET']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="basket">
                        <?= Html::beginTag($sTag, [
                            'class' => Html::cssClassFromArray([
                                'sale-basket-small-tab' => [
                                    '' => true,
                                    'active' => $bActive
                                ],
                                'intec-cl-text' => [
                                    '' => $bActive,
                                    'hover' => true
                                ]
                            ], true),
                            'data-role' => 'tab.icon',
                            'href' => !empty($arResult['URL']['BASKET']) ? $arResult['URL']['BASKET'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <div class="widget-panel-button-icon-svg">
									<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.46899 6.625L5.80199 3.625H3.87299" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M8.23099 14.835L6.46899 6.625H19.127C19.764 6.625 20.238 7.212 20.105 7.835L18.603 14.835C18.504 15.296 18.097 15.625 17.625 15.625H9.20799C8.73699 15.625 8.32999 15.296 8.23099 14.835Z" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M9.35599 19.25C9.14899 19.25 8.98099 19.418 8.98299 19.625C8.98199 19.832 9.14999 20 9.35699 20C9.56399 20 9.73199 19.832 9.73199 19.625C9.73199 19.418 9.56399 19.25 9.35599 19.25" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M17.963 19.25C17.756 19.25 17.588 19.418 17.59 19.625C17.589 19.832 17.757 20 17.964 20C18.171 20 18.339 19.832 18.339 19.625C18.339 19.418 18.171 19.25 17.963 19.25" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<?php if ($bActive) { ?>
										<span class="sale-basket-small-tab-counter intec-cl-background-dark">
											<?= Html::encode($arResult['BASKET']['COUNT']) ?>
										</span>
									<?php } ?>
								</div>
                                <div class="widget-panel-button-text">
									<?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_KORZINA')?>
								</div>
                            </span>
                        <?= Html::endTag($sTag) ?>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-small-popup sale-basket-small-popup-basket" data-role="tab.popup">
                                <div class="sale-basket-small-popup-wrapper">
                                    <div class="sale-basket-small-header">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                            <div class="sale-basket-small-header-text intec-grid-item">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_TITLE')?>
                                                <span class="sale-basket-small-header-count">
                                                    <?= $sCountBasket ?>
                                                </span>
                                            </div>
                                            <div class="sale-basket-small-header-clear-wrap intec-grid-item-auto">
                                                <div data-role="button"
                                                     data-action="basket.clear"
                                                     class="sale-basket-small-header-clear intec-ui intec-ui-control-button intec-ui-size-2 intec-ui-state-hover intec-cl-background-hover intec-cl-border-hover">
                                                    <div class="intec-ui-part-icon">
                                                        <i class="fal fa-times"></i>
                                                    </div>
                                                    <div class="intec-ui-part-content">
                                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CLEAR')?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sale-basket-small-body">
                                    <?php
                                        $arItems = $arResult['BASKET']['ITEMS'];
                                        include(__DIR__.'/content/products.php');
                                    ?>
                                    </div>
                                    <div class="sale-basket-small-footer-wrap">
                                        <div class="sale-basket-small-footer">
                                            <div class="sale-basket-small-footer-sum-wrap">
                                                <div class="intec-grid intec-grid-nowrap intec-grid-a-v-end">
                                                    <div class="sale-basket-small-footer-sum intec-grid-item">
                                                        <div class="sale-basket-small-footer-sum-title">
                                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_SUM_TITLE')?>
                                                        </div>
                                                    </div>
                                                    <div class="sale-basket-small-footer-sum intec-grid-item-auto">
                                                        <span class="sale-basket-small-footer-new-sum">
                                                            <?= $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] ?>
                                                        </span>
                                                    </div>
                                                    <div class="sale-basket-small-footer-sum intec-grid-item">
                                                        <?php if ($arResult['BASKET']['SUM']['DISCOUNT']['VALUE'] != $arResult['BASKET']['SUM']['BASE']['VALUE']) { ?>
                                                            <span class="sale-basket-small-footer-old-sum">
                                                                <?= $arResult['BASKET']['SUM']['BASE']['DISPLAY'] ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($arResult['URL']['BASKET']) || !empty($arResult['URL']['ORDER'])) { ?>
                                                <div class="sale-basket-small-footer-buttons intec-grid intec-grid-nowrap intec-grid-a-v-start">
                                                    <?php if (!empty($arResult['URL']['BASKET'])) { ?>
                                                        <div class="intec-grid-item">
                                                            <a href="<?= $arResult['URL']['BASKET'] ?>"
                                                               class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-scheme-current intec-ui-size-2">
                                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_TO_BASKET') ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (!empty($arResult['URL']['ORDER'])) { ?>
                                                        <div class="intec-grid-item">
                                                            <a href="<?= $arResult['URL']['ORDER'] ?>"
                                                               class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-size-2">
                                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CREATE_ORDER') ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    <?php } else { ?>
        <div class="sale-basket-small-tabs" data-role="tabs">
            <?= Html::beginTag('div', [
                'class' => [
                    'sale-basket-small-items',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-10'
                    ]
                ]
            ]) ?>
                <?php if ($arResult['COMPARE']['SHOW']) { ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ]
                        ]) ?>
                            <a rel="nofollow" href="<?= $arResult['URL']['COMPARE'] ?>" class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-compare"></i>
                            </a>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DELAYED']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['DELAYED']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['DELAYED']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="delay">
                        <?= Html::beginTag($sTag, [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ],
                            'data-role' => "tab.icon",
                            'rel' => $sTag === 'a' ? 'nofollow' : null,
                            'href' => !empty($arResult['URL']['DELAYED']) ? $arResult['URL']['DELAYED'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-heart"></i>
                            </span>
                        <?= Html::endTag($sTag) ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['BASKET']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['BASKET']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['BASKET']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="basket">
                        <?= Html::beginTag($sTag, [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ],
                            'data-role' => 'tab.icon',
                            'href' => !empty($arResult['URL']['BASKET']) ? $arResult['URL']['BASKET'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-cart"></i>
                            </span>
                        <?= Html::endTag($sTag) ?>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    <?php } ?>
</div>