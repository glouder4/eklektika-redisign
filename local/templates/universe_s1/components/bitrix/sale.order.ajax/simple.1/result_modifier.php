<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

// В checkout данные корзины приходят в JS_DATA.GRID.ROWS/TOTAL.
// Применяем тот же post-processing цен, что и в мини-корзине/корзине.
if (class_exists(\OnlineService\Site\CatalogPriceFloor::class)
    && \OnlineService\Site\CatalogPriceFloor::isPricingOverrideActive()
    && !empty($arResult['JS_DATA']['GRID']['ROWS'])
    && is_array($arResult['JS_DATA']['GRID']['ROWS'])) {
    $basketProductMap = [];
    $needBasketResolve = false;
    foreach ($arResult['JS_DATA']['GRID']['ROWS'] as $rowProbe) {
        if (!is_array($rowProbe)) {
            continue;
        }
        $rowDataProbe = (isset($rowProbe['data']) && is_array($rowProbe['data'])) ? $rowProbe['data'] : [];
        $basketIdProbe = (int)($rowDataProbe['ID'] ?? 0);
        $productIdProbe = (int)($rowDataProbe['PRODUCT_ID'] ?? 0);

        if ($productIdProbe <= 0 && $basketIdProbe > 0) {
            $needBasketResolve = true;
            break;
        }
    }

    if ($needBasketResolve && Loader::includeModule('sale') && class_exists(\Bitrix\Sale\Internals\BasketTable::class)) {
        $basketIds = [];

        foreach ($arResult['JS_DATA']['GRID']['ROWS'] as $rowProbe) {
            if (!is_array($rowProbe)) {
                continue;
            }
            $rowDataProbe = (isset($rowProbe['data']) && is_array($rowProbe['data'])) ? $rowProbe['data'] : [];
            $basketId = (int)($rowDataProbe['ID'] ?? 0);
            if ($basketId > 0) {
                $basketIds[] = $basketId;
            }
        }

        $basketIds = array_values(array_unique($basketIds));

        if (!empty($basketIds)) {
            $rsBasket = \Bitrix\Sale\Internals\BasketTable::getList([
                'filter' => ['@ID' => $basketIds],
                'select' => ['ID', 'PRODUCT_ID'],
            ]);

            while ($basketRow = $rsBasket->fetch()) {
                $basketProductMap[(int)$basketRow['ID']] = (int)$basketRow['PRODUCT_ID'];
            }
        }
    }

    $sumDiscount = 0.0;
    $sumBase = 0.0;

    foreach ($arResult['JS_DATA']['GRID']['ROWS'] as &$row) {
        if (!is_array($row) || !isset($row['data']) || !is_array($row['data'])) {
            continue;
        }
        $rowData =& $row['data'];

        if ((int)($rowData['PRODUCT_ID'] ?? 0) <= 0) {
            $basketId = (int)($rowData['ID'] ?? 0);
            if ($basketId > 0 && isset($basketProductMap[$basketId]) && $basketProductMap[$basketId] > 0) {
                $rowData['PRODUCT_ID'] = $basketProductMap[$basketId];
            }
        }

        // Нормализуем структуру строки под applyFloorToBasketRowRenderData.
        if (!isset($rowData['CURRENCY']) || $rowData['CURRENCY'] === '') {
            $rowData['CURRENCY'] = (string)($arResult['JS_DATA']['CURRENCY'] ?? '');
        }
        if (!isset($rowData['FULL_PRICE']) && isset($rowData['BASE_PRICE'])) {
            $rowData['FULL_PRICE'] = (float)$rowData['BASE_PRICE'];
        }

        \OnlineService\Site\CatalogPriceFloor::applyFloorToBasketRowRenderData($rowData);

        // Синхронизация полей, которые читает JS checkout.
        if (isset($rowData['SUM_PRICE_FORMATED'])) {
            $rowData['SUM'] = $rowData['SUM_PRICE_FORMATED'];
            if (isset($row['columns']) && is_array($row['columns'])) {
                $row['columns']['SUM'] = $rowData['SUM_PRICE_FORMATED'];
            }
        }
        if (isset($rowData['PRICE_FORMATED']) && isset($row['columns']) && is_array($row['columns'])) {
            $row['columns']['PRICE_FORMATED'] = $rowData['PRICE_FORMATED'];
        }
        if (isset($rowData['DISCOUNT_PRICE_PERCENT_FORMATED']) && isset($row['columns']) && is_array($row['columns'])) {
            $row['columns']['DISCOUNT_PRICE_PERCENT_FORMATED'] = $rowData['DISCOUNT_PRICE_PERCENT_FORMATED'];
        }
        if (isset($rowData['SUM_FULL_PRICE_FORMATED']) && isset($row['columns']) && is_array($row['columns'])) {
            $rowData['SUM_BASE_FORMATED'] = $rowData['SUM_FULL_PRICE_FORMATED'];
        }

        $sumDiscount += (float)($rowData['SUM_PRICE'] ?? $rowData['SUM_NUM'] ?? 0);
        $sumBase += (float)($rowData['SUM_FULL_PRICE'] ?? $rowData['SUM_BASE'] ?? 0);
    }
    unset($row);

    if (!empty($arResult['JS_DATA']['TOTAL']) && is_array($arResult['JS_DATA']['TOTAL'])) {
        $total =& $arResult['JS_DATA']['TOTAL'];
        $currency = (string)($arResult['JS_DATA']['CURRENCY'] ?? '');

        if ($currency === '' && !empty($arResult['JS_DATA']['GRID']['ROWS'][0]['data']['CURRENCY'])) {
            $currency = (string)$arResult['JS_DATA']['GRID']['ROWS'][0]['data']['CURRENCY'];
        }
        if ($currency === '') {
            $currency = (string)($arResult['BASE_LANG_CURRENCY'] ?? '');
        }
        if ($currency === '' && defined('DEFAULT_CURRENCY')) {
            $currency = (string)DEFAULT_CURRENCY;
        }
        if ($currency === '') {
            $currency = 'RUB';
        }

        $formatMoney = static function (float $value) use ($currency): string {
            if (function_exists('SaleFormatCurrency')) {
                return SaleFormatCurrency($value, $currency);
            }
            if (class_exists(\CCurrencyLang::class)) {
                return \CCurrencyLang::CurrencyFormat($value, $currency, true);
            }

            return (string)$value;
        };

        $round = static function (float $value): float {
            if (class_exists(\Bitrix\Sale\PriceMaths::class)) {
                return \Bitrix\Sale\PriceMaths::roundPrecision($value);
            }

            return round($value, 2);
        };

        $newOrderPrice = $round($sumDiscount);
        $newBasePrice = $round($sumBase);
        $oldOrderPrice = (float)($total['ORDER_PRICE'] ?? $newOrderPrice);
        $oldBasePrice = (float)($total['PRICE_WITHOUT_DISCOUNT_VALUE'] ?? $newBasePrice);
        $oldBasketDiff = max(0.0, $round($oldBasePrice - $oldOrderPrice));
        $oldTotalDiscount = (float)($total['DISCOUNT_PRICE'] ?? 0);
        $coreOrderDiscount = max(0.0, $round($oldTotalDiscount - $oldBasketDiff));
        $newBasketDiff = max(0.0, $round($newBasePrice - $newOrderPrice));
        $newDiscount = $round($coreOrderDiscount + $newBasketDiff);
        $delta = $newOrderPrice - $oldOrderPrice;

        $total['ORDER_PRICE'] = $newOrderPrice;
        $total['ORDER_PRICE_FORMATED'] = $formatMoney($newOrderPrice);

        $total['PRICE_WITHOUT_DISCOUNT_VALUE'] = $newBasePrice;
        $total['PRICE_WITHOUT_DISCOUNT'] = $formatMoney($newBasePrice);
        $total['BASKET_PRICE_DISCOUNT_DIFF_VALUE'] = $newBasketDiff;
        $total['BASKET_PRICE_DISCOUNT_DIFF'] = $formatMoney($newBasketDiff);

        $total['DISCOUNT_PRICE'] = $newDiscount;
        $total['DISCOUNT_PRICE_FORMATED'] = $formatMoney($newDiscount);

        $pctDiscount = $newBasePrice > 0 ? 100 * $newDiscount / $newBasePrice : 0.0;
        $total['DISCOUNT_PERCENT'] = round($pctDiscount, 2);
        $total['DISCOUNT_PERCENT_FORMATED'] = (string)(int)round($pctDiscount);

        if (isset($total['ORDER_TOTAL_PRICE'])) {
            $total['ORDER_TOTAL_PRICE'] = $round((float)$total['ORDER_TOTAL_PRICE'] + $delta);
            $total['ORDER_TOTAL_PRICE_FORMATED'] = $formatMoney((float)$total['ORDER_TOTAL_PRICE']);
        }

        if (array_key_exists('ORDER_TOTAL_LEFT_TO_PAY', $total) && $total['ORDER_TOTAL_LEFT_TO_PAY'] !== null) {
            $total['ORDER_TOTAL_LEFT_TO_PAY'] = $round((float)$total['ORDER_TOTAL_LEFT_TO_PAY'] + $delta);
            $total['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'] = $formatMoney((float)$total['ORDER_TOTAL_LEFT_TO_PAY']);
        }
    }
}

$arParams['ALLOW_USER_PROFILES'] = $arParams['ALLOW_USER_PROFILES'] === 'Y' ? 'Y' : 'N';
$arParams['SKIP_USELESS_BLOCK'] = $arParams['SKIP_USELESS_BLOCK'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['SHOW_ORDER_BUTTON']))
{
    $arParams['SHOW_ORDER_BUTTON'] = 'final_step';
}

$arParams['HIDE_ORDER_DESCRIPTION'] = isset($arParams['HIDE_ORDER_DESCRIPTION']) && $arParams['HIDE_ORDER_DESCRIPTION'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_TOTAL_ORDER_BUTTON'] = $arParams['SHOW_TOTAL_ORDER_BUTTON'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] = $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_PAY_SYSTEM_INFO_NAME'] = $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_LIST_NAMES'] = $arParams['SHOW_DELIVERY_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_INFO_NAME'] = $arParams['SHOW_DELIVERY_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_PARENT_NAMES'] = $arParams['SHOW_DELIVERY_PARENT_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_STORES_IMAGES'] = $arParams['SHOW_STORES_IMAGES'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['BASKET_POSITION']) || !in_array($arParams['BASKET_POSITION'], array('before', 'after')))
{
    $arParams['BASKET_POSITION'] = 'after';
}

$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['SHOW_BASKET_HEADERS'] = $arParams['SHOW_BASKET_HEADERS'] === 'Y' ? 'Y' : 'N';
$arParams['HIDE_DETAIL_PAGE_URL'] = isset($arParams['HIDE_DETAIL_PAGE_URL']) && $arParams['HIDE_DETAIL_PAGE_URL'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERY_FADE_EXTRA_SERVICES'] = $arParams['DELIVERY_FADE_EXTRA_SERVICES'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_COUPONS_BASKET'] = $arParams['SHOW_COUPONS_BASKET'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_COUPONS_DELIVERY'] = $arParams['SHOW_COUPONS_DELIVERY'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_COUPONS_PAY_SYSTEM'] = $arParams['SHOW_COUPONS_PAY_SYSTEM'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_NEAREST_PICKUP'] = $arParams['SHOW_NEAREST_PICKUP'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERIES_PER_PAGE'] = isset($arParams['DELIVERIES_PER_PAGE']) ? intval($arParams['DELIVERIES_PER_PAGE']) : 9;
$arParams['PAY_SYSTEMS_PER_PAGE'] = isset($arParams['PAY_SYSTEMS_PER_PAGE']) ? intval($arParams['PAY_SYSTEMS_PER_PAGE']) : 9;
$arParams['PICKUPS_PER_PAGE'] = isset($arParams['PICKUPS_PER_PAGE']) ? intval($arParams['PICKUPS_PER_PAGE']) : 5;
$arParams['SHOW_PICKUP_MAP'] = $arParams['SHOW_PICKUP_MAP'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_MAP_IN_PROPS'] = $arParams['SHOW_MAP_IN_PROPS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_YM_GOALS'] = $arParams['USE_YM_GOALS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$useDefaultMessages = !isset($arParams['USE_CUSTOM_MAIN_MESSAGES']) || $arParams['USE_CUSTOM_MAIN_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_BLOCK_NAME']))
{
    $arParams['MESS_AUTH_BLOCK_NAME'] = Loc::getMessage('AUTH_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REG_BLOCK_NAME']))
{
    $arParams['MESS_REG_BLOCK_NAME'] = Loc::getMessage('REG_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BASKET_BLOCK_NAME']))
{
    $arParams['MESS_BASKET_BLOCK_NAME'] = Loc::getMessage('BASKET_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_BLOCK_NAME']))
{
    $arParams['MESS_REGION_BLOCK_NAME'] = Loc::getMessage('REGION_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAYMENT_BLOCK_NAME']))
{
    $arParams['MESS_PAYMENT_BLOCK_NAME'] = Loc::getMessage('PAYMENT_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_BLOCK_NAME']))
{
    $arParams['MESS_DELIVERY_BLOCK_NAME'] = Loc::getMessage('DELIVERY_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BUYER_BLOCK_NAME']))
{
    $arParams['MESS_BUYER_BLOCK_NAME'] = Loc::getMessage('BUYER_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BACK']))
{
    $arParams['MESS_BACK'] = Loc::getMessage('BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FURTHER']))
{
    $arParams['MESS_FURTHER'] = Loc::getMessage('FURTHER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_EDIT']))
{
    $arParams['MESS_EDIT'] = Loc::getMessage('EDIT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_EXTEND']))
{
    $arParams['MESS_EXTEND'] = Loc::getMessage('EXTEND_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER']))
{
    $arParams['MESS_ORDER'] = $arParams['~MESS_ORDER'] = Loc::getMessage('ORDER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PRICE']))
{
    $arParams['MESS_PRICE'] = Loc::getMessage('PRICE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERIOD']))
{
    $arParams['MESS_PERIOD'] = Loc::getMessage('PERIOD_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_BACK']))
{
    $arParams['MESS_NAV_BACK'] = Loc::getMessage('NAV_BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_FORWARD']))
{
    $arParams['MESS_NAV_FORWARD'] = Loc::getMessage('NAV_FORWARD_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ADDITIONAL_MESSAGES']) || $arParams['USE_CUSTOM_ADDITIONAL_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRICE_FREE']))
{
    $arParams['MESS_PRICE_FREE'] = Loc::getMessage('PRICE_FREE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ECONOMY']))
{
    $arParams['MESS_ECONOMY'] = Loc::getMessage('ECONOMY_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGISTRATION_REFERENCE']))
{
    $arParams['MESS_REGISTRATION_REFERENCE'] = Loc::getMessage('REGISTRATION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_1']))
{
    $arParams['MESS_AUTH_REFERENCE_1'] = Loc::getMessage('AUTH_REFERENCE_1_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_2']))
{
    $arParams['MESS_AUTH_REFERENCE_2'] = Loc::getMessage('AUTH_REFERENCE_2_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_3']))
{
    $arParams['MESS_AUTH_REFERENCE_3'] = Loc::getMessage('AUTH_REFERENCE_3_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ADDITIONAL_PROPS']))
{
    $arParams['MESS_ADDITIONAL_PROPS'] = Loc::getMessage('ADDITIONAL_PROPS_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_USE_COUPON']))
{
    $arParams['MESS_USE_COUPON'] = Loc::getMessage('USE_COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_COUPON']))
{
    $arParams['MESS_COUPON'] = Loc::getMessage('COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERSON_TYPE']))
{
    $arParams['MESS_PERSON_TYPE'] = Loc::getMessage('PERSON_TYPE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PROFILE']))
{
    $arParams['MESS_SELECT_PROFILE'] = Loc::getMessage('SELECT_PROFILE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_REFERENCE']))
{
    $arParams['MESS_REGION_REFERENCE'] = Loc::getMessage('REGION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PICKUP_LIST']))
{
    $arParams['MESS_PICKUP_LIST'] = Loc::getMessage('PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NEAREST_PICKUP_LIST']))
{
    $arParams['MESS_NEAREST_PICKUP_LIST'] = Loc::getMessage('NEAREST_PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PICKUP']))
{
    $arParams['MESS_SELECT_PICKUP'] = Loc::getMessage('SELECT_PICKUP_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECTED_PICKUP']))
{
    $arParams['MESS_SELECTED_PICKUP'] = Loc::getMessage('SELECTED_PICKUP_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_INNER_PS_BALANCE']))
{
    $arParams['MESS_INNER_PS_BALANCE'] = Loc::getMessage('INNER_PS_BALANCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER_DESC']))
{
    $arParams['MESS_ORDER_DESC'] = Loc::getMessage('ORDER_DESC_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ERROR_MESSAGES']) || $arParams['USE_CUSTOM_ERROR_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRELOAD_ORDER_TITLE']))
{
    $arParams['MESS_PRELOAD_ORDER_TITLE'] = Loc::getMessage('PRELOAD_ORDER_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SUCCESS_PRELOAD_TEXT']))
{
    $arParams['MESS_SUCCESS_PRELOAD_TEXT'] = Loc::getMessage('SUCCESS_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FAIL_PRELOAD_TEXT']))
{
    $arParams['MESS_FAIL_PRELOAD_TEXT'] = Loc::getMessage('FAIL_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TITLE']))
{
    $arParams['MESS_DELIVERY_CALC_ERROR_TITLE'] = Loc::getMessage('DELIVERY_CALC_ERROR_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TEXT']))
{
    $arParams['MESS_DELIVERY_CALC_ERROR_TEXT'] = Loc::getMessage('DELIVERY_CALC_ERROR_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']))
{
    $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] = Loc::getMessage('PAY_SYSTEM_PAYABLE_ERROR_DEFAULT');
}