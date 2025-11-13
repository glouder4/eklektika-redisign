<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arFields
 * @var bool $bOffers
 * @var bool $bSkuDynamic
 * @var bool $bRecalculation
 */
?>

<? if ($arFields['ARTICLE']['SHOW']) { ?>
    <div class="intec-grid-item">
        <?php include(__DIR__.'/../article.php') ?>
    </div>
<? } ?>

<?php if ($arResult['SHARES']['SHOW']) {
    include(__DIR__ . '/../shares.php');
} ?>
<?php if ($arResult['SIZES']['SHOW'])
    include(__DIR__.'/../sizes.php');
?>
<? if (!empty($arResult['OFFERS']) && $arResult['SKU']['VIEW'] === 'dynamic')
    include(__DIR__.'/../offers.php');
?>
<?php if ($arVisual['PROPERTIES']['PREVIEW']['SHOW'])
    include(__DIR__.'/../properties.preview.php')
?>
<div class="catalog-element-purchase-container catalog-element-purchase-container-1" data-sticky="top" data-role="purchase">
    <div class="catalog-element-purchase">
        <div class="catalog-element-purchase-wrapper">
            <?php if ($arVisual['TIMER']['SHOW'] && !$bSkuList) { ?>
                <?php include(__DIR__.'/../purchase/timer.php') ?>
            <?php } ?>
            <?php if (!$bOffers || $bSkuDynamic) { ?>
                <?php if ($arVisual['PRICE']['SHOW'])
                    include(__DIR__.'/../purchase/price.php');
                ?>
                <?php if ($arVisual['PRICE']['SHOW'])
                    include(__DIR__.'/../purchase/table.offer.php');
                ?>
                <?php if ($arVisual['PRICE']['SHOW'])
                    include(__DIR__.'/../purchase/add.an.application.php');
                ?>
                <?php if ($arVisual['MEASURES']['USE'] && $arVisual['MEASURES']['POSITION'] === 'top')
                    include(__DIR__.'/../purchase/measures.php');
                ?>
                <?php if ($arVisual['PRICE']['RANGE'])
                    include(__DIR__.'/../purchase/price.range.php');
                ?>
                <?php if ($arFields['ADDITIONAL']['SHOW']) { ?>
                    <div class="catalog-element-purchase-block">
                        <?php include(__DIR__.'/../purchase/products.additional.php') ?>
                    </div>
                <?php } ?>
                <?php if ($arVisual['CREDIT']['SHOW'] && !$bSkuList) {
                    include(__DIR__.'/../purchase/credit.php');
                } ?>
                <?php
                /* НЕ НУЖНО
                if ($arVisual['QUANTITY']['SHOW'] || $arResult['FORM']['CHEAPER']['SHOW']) { ?>
                    <div class="catalog-element-purchase-block">
                        <div class="intec-grid intec-grid-wrap intec-grid-i-h-12 intec-grid-i-v-6">
                            <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                                <div class="catalog-element-quantity-container intec-grid-item-auto">
                                    <?php include(__DIR__.'/../purchase/quantity.php') ?>
                                    <?php if ($arVisual['STORES']['USE'] && $arVisual['STORES']['POSITION'] === 'popup')
                                        include(__DIR__.'/../purchase/quantity.store.php');
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
                                <div class="intec-grid-item-auto">
                                    <?php include(__DIR__.'/../purchase/cheaper.php') ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } 
                */
                ?>
                <?php if ($arResult['DELIVERY_CALCULATION']['USE']) { ?>
                    <div class="catalog-element-purchase-block">
                        <?php include(__DIR__.'/../purchase/delivery.calculation.php') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['ACTION'] !== 'none') { ?>
                    <?php if ($arVisual['MEASURES']['USE'] && $arVisual['MEASURES']['POSITION'] === 'bottom') { ?>
                        <div class="catalog-element-purchase-block">
                            <?php include(__DIR__.'/../purchase/measures.php') ?>
                        </div>
                    <?php } ?>
                    <div class="catalog-element-purchase-block catalog-element-purchase-action">
                        <div class="intec-grid intec-grid-wrap">
                            <div class="block-buy-summary-custom">
                                <?php if ($arVisual['COUNTER']['SHOW']) { ?>
                                    <div class="intec-grid-item-2">
                                        <?php include(__DIR__.'/../purchase/counter.php') ?>
                                    </div>
                                <?php } ?>
                                <div class="intec-grid-item">
                                    <?php include(__DIR__.'/../purchase/order.php') ?>
                                </div>
                                <?php if ($arResult['ORDER_FAST']['USE']) { ?>
                                    <div class="catalog-element-buy-fast-container intec-grid-item-1">
                                        <?= Html::tag('div', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_BUY_BUTTON_ORDER_FAST'), [
                                            'class' => [
                                                'catalog-element-buy-fast',
                                                'intec-cl-text'
                                            ],
                                            'data-role' => 'orderFast'
                                        ]) ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($bRecalculation) { ?>
                                <div class="catalog-element-purchase-summary intec-grid-item-1" data-role="item.summary" style="display: none">
                                    <div class="catalog-element-purchase-summary-wrapper">
                                        <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_TITLE_SUMMARY') ?>
                                        <span data-role="item.summary.price"></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="container-btn-order-sample">
                            <div class="btn-order-sample">
                                Заказать образец
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 20.5C16.9705 20.5 21 16.339 21 13.5C21 10.661 16.9705 6.5 12 6.5C7.0295 6.5 3 10.664 3 13.5C3 16.336 7.0295 20.5 12 20.5Z" stroke="#222222" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M12 16.5C12.7956 16.5 13.5587 16.1839 14.1213 15.6213C14.6839 15.0587 15 14.2956 15 13.5C15 12.7044 14.6839 11.9413 14.1213 11.3787C13.5587 10.8161 12.7956 10.5 12 10.5C11.2044 10.5 10.4413 10.8161 9.87868 11.3787C9.31607 11.9413 9 12.7044 9 13.5C9 14.2956 9.31607 15.0587 9.87868 15.6213C10.4413 16.1839 11.2044 16.5 12 16.5Z" stroke="#222222" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M6.63281 5.633L7.92981 7.443M17.8133 5.855L16.5158 7.665M12.0053 3.5V6.5" stroke="#222222" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if ($arVisual['PRICE']['SHOW'] && !empty($arResult['ITEM_PRICES']))
                    include(__DIR__.'/../purchase/price.static.php');
                ?>
                <?php if ($arFields['ADDITIONAL']['SHOW']) { ?>
                    <div class="catalog-element-purchase-block">
                        <?php include(__DIR__.'/../purchase/products.additional.php') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
                    <div class="catalog-element-purchase-block">
                        <?php include(__DIR__.'/../purchase/cheaper.php') ?>
                    </div>
                <?php } ?>
                <?php include(__DIR__.'/../purchase/order.static.php') ?>
            <?php } ?>
        </div>
    </div>
    <?php if ($arVisual['PRICE_INFO']['SHOW']) { ?>
        <?php if (empty($arVisual['PRICE_INFO']['TEXT']))
            $arVisual['PRICE_INFO']['TEXT'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_PRICE_INFO_TEXT_DEFAULT');
        ?>
        <div class="catalog-element-purchase-information">
            <?= $arVisual['PRICE_INFO']['TEXT'] ?>
        </div>
    <?php } ?>
</div>
<style>
    .intec-cl-background {
        background-color: #744A9E !important;
        fill: #744A9E !important;
    }
</style>