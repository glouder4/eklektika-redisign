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
                        ],
                        'header__icon header__icon--heart' => true
                    ], true),
                    'data-role' => "tab.icon",
                    'rel' => $sTag === 'a' ? 'nofollow' : null,
                    'href' => !empty($arResult['URL']['DELAYED']) ? $arResult['URL']['DELAYED'] : null
                ]) ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="16" viewBox="0 0 19 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.8329 7.41211C16.5135 6.72079 16.8916 5.78743 16.884 4.81733C16.8764 3.84724 16.4838 2.9199 15.7925 2.2393C15.4502 1.90231 15.0448 1.63603 14.5996 1.45568C14.1544 1.27533 13.678 1.18444 13.1977 1.18819C12.2276 1.19577 11.3003 1.58841 10.6197 2.27973C10.4349 2.46454 10.2 2.69139 9.91507 2.96026L9.12288 3.70625L8.33069 2.96026C8.04513 2.69074 7.80994 2.4639 7.62513 2.27973C6.93917 1.59377 6.00881 1.20841 5.03872 1.20841C4.06863 1.20841 3.13828 1.59377 2.45232 2.27973C1.03928 3.69373 1.02291 5.97982 2.40034 7.40056L9.12288 14.1231L15.8329 7.41211ZM1.6351 1.46348C2.08203 1.01643 2.61265 0.661809 3.19665 0.419863C3.78065 0.177918 4.40659 0.0533882 5.03872 0.0533882C5.67086 0.0533882 6.2968 0.177918 6.8808 0.419863C7.4648 0.661809 7.99542 1.01643 8.44235 1.46348C8.61753 1.63931 8.84438 1.85813 9.12288 2.11994C9.4001 1.85813 9.62694 1.63898 9.80341 1.46251C10.6991 0.553053 11.9194 0.0366466 13.1958 0.0268987C14.4722 0.0171509 15.7002 0.51486 16.6097 1.41054C17.5191 2.30621 18.0356 3.52649 18.0453 4.80291C18.0551 6.07934 17.5573 7.30735 16.6617 8.21681L9.80341 15.076C9.6229 15.2565 9.37811 15.3579 9.12288 15.3579C8.86764 15.3579 8.62285 15.2565 8.44235 15.076L1.58216 8.21585C0.702419 7.30854 0.214852 6.0916 0.224757 4.82785C0.234663 3.56411 0.741246 2.35496 1.6351 1.46155V1.46348Z" fill="white"/>
                </svg>
                <span class="header__icon-title">Избранное</span>
                <?php if ($bActive) { ?>
                    <span class="header__badge">
                        <?= Html::encode($arResult['DELAYED']['COUNT']) ?>
                    </span>
                <?php } ?>
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
                        ],
                        'header__icon header__icon--bag' => true
                    ], true),
                    'data-role' => 'tab.icon',
                    'href' => !empty($arResult['URL']['BASKET']) ? $arResult['URL']['BASKET'] : null
                ]) ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="19" viewBox="0 0 17 19" fill="none">
                        <path d="M12.0228 5.87953V4.16907C12.0228 3.63608 11.9196 3.1083 11.7189 2.61587C11.5183 2.12345 11.2242 1.67602 10.8535 1.29914C10.4827 0.92225 10.0426 0.623288 9.55821 0.419319C9.07382 0.21535 8.55466 0.110369 8.03036 0.110369C7.50606 0.110369 6.98689 0.21535 6.5025 0.419319C6.01812 0.623288 5.57799 0.92225 5.20725 1.29914C4.83652 1.67602 4.54243 2.12345 4.34179 2.61587C4.14115 3.1083 4.03788 3.63608 4.03788 4.16907V8.22777C4.48149 8.22777 4.77723 8.22777 5.17859 8.22777V7.03915H9.74142V5.87953H5.17859V4.16907C5.17859 3.40019 5.47904 2.6628 6.01385 2.11912C6.54866 1.57544 7.27402 1.27 8.03036 1.27C8.78669 1.27 9.51205 1.57544 10.0469 2.11912C10.5817 2.6628 10.8821 3.40019 10.8821 4.16907V8.19878C11.2835 8.19878 11.5792 8.19878 12.0228 8.19878V7.03915H14.8746V17.4758H1.18612V7.03915H2.89718V5.87953H0.0454102V17.528C0.0454102 17.8217 0.160183 18.1034 0.364481 18.3111C0.568778 18.5188 0.845865 18.6354 1.13479 18.6354H14.9259C15.2149 18.6354 15.4919 18.5188 15.6962 18.3111C15.9005 18.1034 16.0153 17.8217 16.0153 17.528V5.87953H12.0228Z" fill="white"/>
                    </svg>
                    <span class="header__icon-title">Корзина</span>
                    <?php if ($bActive) { ?>
                        <span class="sale-basket-small-tab-counter intec-cl-background-dark">
                            <?= Html::encode($arResult['BASKET']['COUNT']) ?>
                        </span>
                    <?php } ?>
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

