<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 * @var bool $bOffers
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php $vPanelMobileButton = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$sTemplateId, &$APPLICATION, &$component) { ?>
    <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?php if ($arResult['ACTION'] === 'buy') { ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-buy-container',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
        <?php if ($arItem['CAN_BUY']) { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-panel-mobile-buy-button',
                    'catalog-element-panel-mobile-buy-add',
                    'intec-ui',
                    'intec-ui-control-basket-button',
                    'intec-cl-background',
                    'intec-cl-background-light-hover'
                ],
                'data' => [
                    'basket-id' => $arItem['ID'],
                    'basket-action' => 'add',
                    'basket-state' => 'none',
                    'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                    'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null,
                    'basket-data' => Json::htmlEncode([
                        'additional' => true
                    ])
                ]
            ]) ?>
                <span class="catalog-element-panel-mobile-button-content intec-ui-part-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="19" viewBox="0 0 16 19" fill="none">
                        <path d="M11.9774 5.76916V4.0587C11.9774 3.52571 11.8742 2.99793 11.6735 2.5055C11.4729 2.01308 11.1788 1.56565 10.8081 1.18877C10.4373 0.811881 9.99719 0.512919 9.5128 0.30895C9.02841 0.104981 8.50925 0 7.98495 0C7.46065 0 6.94148 0.104981 6.45709 0.30895C5.9727 0.512919 5.53258 0.811881 5.16184 1.18877C4.79111 1.56565 4.49702 2.01308 4.29638 2.5055C4.09574 2.99793 3.99247 3.52571 3.99247 4.0587V8.11741C4.43608 8.11741 4.73182 8.11741 5.13318 8.11741V6.92879H9.69601V5.76916H5.13318V4.0587C5.13318 3.28982 5.43363 2.55243 5.96844 2.00875C6.50325 1.46507 7.22861 1.15963 7.98495 1.15963C8.74128 1.15963 9.46664 1.46507 10.0015 2.00875C10.5363 2.55243 10.8367 3.28982 10.8367 4.0587V8.08842C11.2381 8.08842 11.5338 8.08842 11.9774 8.08842V6.92879H14.8292V17.3655H1.14071V6.92879H2.85177V5.76916H0V17.4176C0 17.7113 0.114773 17.993 0.31907 18.2007C0.523368 18.4084 0.800455 18.5251 1.08937 18.5251H14.8805C15.1694 18.5251 15.4465 18.4084 15.6508 18.2007C15.8551 17.993 15.9699 17.7113 15.9699 17.4176V5.76916H11.9774Z" fill="white"/>
                    </svg>
                </span>
                <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                    <span class="intec-ui-part-effect-wrapper">
                        <i></i><i></i><i></i>
                    </span>
                </span>
            <?= Html::endTag('div') ?>
            <?= Html::beginTag('a', [
                'class' => [
                    'catalog-element-panel-mobile-buy-button',
                    'catalog-element-panel-mobile-buy-added',
                    'intec-cl-background',
                    'intec-cl-background-light-hover'
                ],
                'href' => $arResult['URL']['BASKET'],
                'data' => [
                    'basket-id' => $arItem['ID'],
                    'basket-state' => 'none'
                ]
            ]) ?>
                <span class="catalog-element-panel-mobile-button-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="19" viewBox="0 0 16 19" fill="none">
                        <path d="M11.9774 5.76916V4.0587C11.9774 3.52571 11.8742 2.99793 11.6735 2.5055C11.4729 2.01308 11.1788 1.56565 10.8081 1.18877C10.4373 0.811881 9.99719 0.512919 9.5128 0.30895C9.02841 0.104981 8.50925 0 7.98495 0C7.46065 0 6.94148 0.104981 6.45709 0.30895C5.9727 0.512919 5.53258 0.811881 5.16184 1.18877C4.79111 1.56565 4.49702 2.01308 4.29638 2.5055C4.09574 2.99793 3.99247 3.52571 3.99247 4.0587V8.11741C4.43608 8.11741 4.73182 8.11741 5.13318 8.11741V6.92879H9.69601V5.76916H5.13318V4.0587C5.13318 3.28982 5.43363 2.55243 5.96844 2.00875C6.50325 1.46507 7.22861 1.15963 7.98495 1.15963C8.74128 1.15963 9.46664 1.46507 10.0015 2.00875C10.5363 2.55243 10.8367 3.28982 10.8367 4.0587V8.08842C11.2381 8.08842 11.5338 8.08842 11.9774 8.08842V6.92879H14.8292V17.3655H1.14071V6.92879H2.85177V5.76916H0V17.4176C0 17.7113 0.114773 17.993 0.31907 18.2007C0.523368 18.4084 0.800455 18.5251 1.08937 18.5251H14.8805C15.1694 18.5251 15.4465 18.4084 15.6508 18.2007C15.8551 17.993 15.9699 17.7113 15.9699 17.4176V5.76916H11.9774Z" fill="white"/>
                    </svg>
                </span>
            <?= Html::endTag('a') ?>
        <?php } else { ?>
            <?php if ($arItem['CATALOG_SUBSCRIBE'] === 'Y') { ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.product.subscribe',
                    '.default', [
                    'BUTTON_CLASS' => Html::cssClassFromArray([
                        'catalog-element-panel-mobile-buy-subscribe',
                        'catalog-element-panel-mobile-buy-button',
                        'intec-cl-background',
                        'intec-cl-background-light-hover'
                    ]),
                    'BUTTON_ID' => $sTemplateId.'panel_mobile_subscribe_'.$arItem['ID'],
                    'PRODUCT_ID' => $arItem['ID']
                ],
                    $component
                ) ?>
            <?php } else { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-panel-mobile-buy-button',
                        'catalog-element-panel-mobile-buy-unavailable'
                    ],
                    'data-counter' => $arVisual['COUNTER']['SHOW'] ? 'true' : 'false'
                ]) ?>
                    <span class="catalog-element-panel-mobile-button-content intec-ui-part-content">
                        <span>
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_BUY_BUTTON_UNAVAILABLE') ?>
                        </span>
                    </span>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } else if ($arResult['ACTION'] === 'order') { ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-panel-mobile-buy-container',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-panel-mobile-buy-button',
                    'intec-cl-background',
                    'intec-cl-background-light-hover'
                ],
                'data-role' => 'order'
            ]) ?>
                <span class="catalog-element-panel-mobile-button-content">
                    <?= $arVisual['BUTTONS']['ORDER']['TEXT'] ?>
                </span>
            <?= Html::endTag('div') ?>
        <?= Html::endTag('div') ?>
    <?php } else if ($arResult['ACTION'] === 'request') { ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-panel-mobile-buy-container',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-panel-mobile-buy-button',
                    'intec-cl-background',
                    'intec-cl-background-light-hover'
                ],
                'data-role' => 'request'
            ]) ?>
                <span class="catalog-element-panel-mobile-button-content">
                    <?= $arVisual['BUTTONS']['REQUEST']['TEXT'] ?>
                </span>
            <?= Html::endTag('div') ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>
<?php $vPanelMobileButton($arResult);

if ($bOffers) {
    foreach ($arResult['OFFERS'] as &$arOffer)
        $vPanelMobileButton($arOffer, true);

    unset($arOffer);
}

unset($vPanelMobileButton); ?>