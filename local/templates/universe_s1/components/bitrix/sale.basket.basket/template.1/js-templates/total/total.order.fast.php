<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var string $sTemplateId
 */
global $USER;
if ($arParams['ORDER_FAST_USE'] !== 'Y' && empty($arParams['ORDER_FAST_TEMPLATE']) && !$USER->IsAuthorized())
    return;

$sPrefix = 'ORDER_FAST_';
$iLength = StringHelper::length($sPrefix);

$arParameters = [
    'TEMPLATE' => $arParams['ORDER_FAST_TEMPLATE'],
    'PARAMETERS' => []
];

foreach ($arParams as $key => $sParameter) {
    if (!StringHelper::startsWith($key, $sPrefix))
        continue;

    $key = StringHelper::cut($key, $iLength);
    $arParameters['PARAMETERS'][$key] = $sParameter;
}

unset($key, $sParameter);

?>
<?php if (!empty($arParameters['PARAMETERS']) && $arParameters['PARAMETERS']['USE'] === 'Y' && isAuthorized()) { ?>
    <?= Html::tag('button', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_ORDER_FAST'), [
        'class' => [
            'basket-order-button',
            'basket-create-reserve',
            'intec-ui' => [
                '',
                'control-button',
                'mod-round-2',
                'mod-transparent',
                'scheme-current',
                'mod-block',
                'size-2',
            ]
        ],
        /*'onclick' => 'template.api.components.show('.JavaScript::toObject([
            'component' => 'intec.universe:sale.order.fast',
            'template' => $arParameters['TEMPLATE'],
            'parameters' => $arParameters['PARAMETERS'],
            'settings' => [
                'parameters' => [
                    'width' => null
                ]
            ]
        ]).*/')'
    ]) ?>
<?php } ?>