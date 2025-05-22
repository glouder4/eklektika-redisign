<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php return function (&$arItem, $bMobile = false) use (&$arResult, &$arVisual, &$sTemplateId, &$APPLICATION, &$component) {

    $arParent = [
        'URL' => $arItem['DETAIL_PAGE_URL']
    ];

?>
    <?php $fRender = function (&$arItem, $bOffer = false) use (&$arResult, $bMobile, &$arVisual, &$APPLICATION, &$component, &$sTemplateId, &$arParent) { ?>
        <?php if ($bOffer || $arItem['VISUAL']['ACTION'] === 'buy') {

            if ($arItem['VISUAL']['OFFER'] && !$bOffer)
                return;

        ?>
            <?php if ($arItem['CAN_BUY']) {

                $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]);

            ?>
                <?= Html::beginTag('div', [
                    'class' => 'widget-item-purchase-buttons',
                    'data-offer' => $bOffer ? $arItem['ID'] : 'false' 
                ]) ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'intec-ui',
                            'intec-ui-control-basket-button',
                            'widget-item-purchase-button',
                            'widget-item-purchase-button-add',
                            $bMobile ? 'intec-cl-border' : '',
                            $bMobile ? 'intec-cl-text' : 'intec-cl-background-hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'add',
                            'basket-state' => 'none',
                            'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <span class="intec-ui-part-content">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8.45501 9.38379L7.51009 5.13379H4.77734" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M10.9515 21.0151L8.45532 9.38428H26.3875C27.2899 9.38428 27.9614 10.2159 27.773 11.0984L25.6452 21.0151C25.5049 21.6682 24.9283 22.1343 24.2597 22.1343H12.3356C11.6683 22.1343 11.0917 21.6682 10.9515 21.0151Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12.5446 27.27C12.2513 27.27 12.0133 27.508 12.0161 27.8013C12.0147 28.0945 12.2527 28.3325 12.546 28.3325C12.8392 28.3325 13.0772 28.0945 13.0772 27.8013C13.0772 27.508 12.8392 27.27 12.5446 27.27" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M24.7382 27.27C24.4449 27.27 24.2069 27.508 24.2097 27.8013C24.2083 28.0945 24.4463 28.3325 24.7396 28.3325C25.0328 28.3325 25.2708 28.0945 25.2708 27.8013C25.2708 27.508 25.0328 27.27 24.7382 27.27" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
                        </span>
                        <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                            <?= Html::beginTag('span', [
                                'class' => Html::cssClassFromArray([
                                    'intec-ui-part-effect-wrapper' => true,
                                    'intec-cl-background' => $bMobile
                                ], true)
                            ]) ?>
                                <i></i><i></i><i></i>
                            <?= Html::endTag('span') ?>
                        </span>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('a', [
                        'class' => [
                            'widget-item-purchase-button',
                            'widget-item-purchase-button-added',
                            $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                            $bMobile ? 'intec-cl-text' : null
                        ],
                        'href' => $arResult['URL']['BASKET'],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-state' => 'none'
                        ]
                    ]) ?>
                        <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.45501 9.38379L7.51009 5.13379H4.77734" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M10.9515 21.0151L8.45532 9.38428H26.3875C27.2899 9.38428 27.9614 10.2159 27.773 11.0984L25.6452 21.0151C25.5049 21.6682 24.9283 22.1343 24.2597 22.1343H12.3356C11.6683 22.1343 11.0917 21.6682 10.9515 21.0151Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M12.5446 27.27C12.2513 27.27 12.0133 27.508 12.0161 27.8013C12.0147 28.0945 12.2527 28.3325 12.546 28.3325C12.8392 28.3325 13.0772 28.0945 13.0772 27.8013C13.0772 27.508 12.8392 27.27 12.5446 27.27" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M24.7382 27.27C24.4449 27.27 24.2069 27.508 24.2097 27.8013C24.2083 28.0945 24.4463 28.3325 24.7396 28.3325C25.0328 28.3325 25.2708 28.0945 25.2708 27.8013C25.2708 27.508 25.0328 27.27 24.7382 27.27" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
                    <?= Html::endTag('a') ?>
                <?= Html::endTag('div') ?>
            <?php } else { ?>
                <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                    <?= Html::beginTag('div', [
                        'class' => 'widget-item-purchase-buttons',
                        'data-offer' => $bOffer ? $arItem['ID'] : 'false'
                    ]) ?>
                        <?php $sMobile = $bMobile ? '_mobile' : '' ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.product.subscribe',
                            '.default', [
                                'BUTTON_CLASS' => Html::cssClassFromArray([
                                    'widget-item-purchase-button',
                                    $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                                    $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                                ]),
                                'BUTTON_ID' => $sTemplateId.'_subscribe_'.$arItem['ID'].$sMobile,
                                'PRODUCT_ID' => $arItem['ID']
                            ],
                            $component
                        ) ?>
                    <?= Html::endTag('div') ?>
                <?php } else { ?>
                    
                <?php } ?>
            <?php } ?>
        <?php } else if ($arItem['VISUAL']['ACTION'] === 'detail') { ?>
            <div class="widget-item-purchase-detail">
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'class' => [
                        'widget-item-purchase-button',
                        $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                        $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                    ],
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arParent['URL'] : null,
                    'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                ]) ?>
                    <?= Loc::getMessage('C_WIDGET_PRODUCTS_4_MORE_INFO') ?>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            </div>
        <?php } else if ($arItem['VISUAL']['ACTION'] === 'order') { ?>
            <div class="widget-item-purchase-order">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-purchase-button',
                        $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                        $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                    ],
                    'data-role' => 'item.order'
                ]) ?>
                    <span>
                        <?= $arVisual['BUTTONS']['ORDER']['TEXT'] ?>
                    </span>
                <?= Html::endTag('div') ?>
            </div>
        <?php } else if ($arItem['VISUAL']['ACTION'] === 'request') { ?>
            <?php if ($arItem['VISUAL']['OFFER']) { ?>
                <div class="widget-item-purchase-detail">
                    <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                        'class' => [
                            'widget-item-purchase-button',
                            $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                            $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                        ],
                        'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arParent['URL'] : null,
                        'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                    ]) ?>
                        <?= Loc::getMessage('C_WIDGET_PRODUCTS_4_MORE_INFO') ?>
                    <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
                </div>
            <?php } else { ?>
                <div class="widget-item-purchase-order">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'widget-item-purchase-button',
                            $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                            $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                        ],
                        'data-role' => 'item.request'
                    ]) ?>
                        <span>
                            <?= $arVisual['BUTTONS']['REQUEST']['TEXT'] ?>
                        </span>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php

        $fRender($arItem, false);

        if ($arVisual['OFFERS']['USE'] && $arItem['VISUAL']['OFFER'] && $arItem['VISUAL']['ACTION'] === 'buy') {
            foreach ($arItem['OFFERS'] as &$arOffer)
                $fRender($arOffer, true);

            unset($arOffer);
        }

    ?>
    <?php if ($arVisual['COLUMNS']['MOBILE'] == 2) { ?>
        <div class="widget-item-purchase-detail mobile">
            <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                'class' => [
                    'widget-item-purchase-button',
                    $bMobile ? 'intec-cl-border' : 'intec-cl-background',
                    $bMobile ? 'intec-cl-text' : 'intec-cl-background-light-hover'
                ],
                'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arParent['URL'] : null,
                'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
            ]) ?>
                <?= Loc::getMessage('C_WIDGET_PRODUCTS_4_MORE_INFO') ?>
            <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
        </div>
    <?php } ?>
<?php } ?>