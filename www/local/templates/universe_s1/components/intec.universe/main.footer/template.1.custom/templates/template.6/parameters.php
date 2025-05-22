<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arReturn = [];
$arReturn['BUTTON_SHAPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_6_BUTTON_SHAPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'round' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_6_BUTTON_SHAPE_ROUND'),
        'square' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_6_BUTTON_SHAPE_SQUARE')
    ],
    'DEFAULT' => 'square'
];
$arReturn['ICONS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ALFABANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_ALFABANK'),
        'SBERBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_SBERBANK'),
        'QIWI' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_QIWI'),
        'YANDEXMONEY' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_YANDEXMONEY'),
        'VISA' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_VISA'),
        'MASTERCARD' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_MASTERCARD'),
        'MIR' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_MIR'),
        'JCB' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_JCB'),
        'PayPal' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_1_ICONS_PayPal')
    ],
    'MULTIPLE' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

return $arReturn;