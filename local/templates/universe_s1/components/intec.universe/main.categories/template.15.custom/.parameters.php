<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 4
];

if (!empty($arCurrentValues['PROPERTY_LINK']) || $arCurrentValues['LINK_MODE'] === 'component') {
    $arTemplateParameters['LINK_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_LINK_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['LINK_USE'] === 'Y') {
        $arTemplateParameters['LINK_BLANK'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_LINK_BLANK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['NAME_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_NAME_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
    'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
    'ACTIVE' => 'Y'
]));

$hPropertyTextSingle = function ($key, $arValue) {
    if ($arValue['PROPERTY_TYPE'] === 'S' && $arValue['LIST_TYPE'] === 'L' && $arValue['MULTIPLE'] === 'N' && empty($arValue['USER_TYPE']))
        return [
            'key' => $arValue['CODE'],
            'value' => '['.$arValue['CODE'].'] '.$arValue['NAME']
        ];

    return ['skip' => true];
};
$arPropertyTextSingle = $arProperties->asArray($hPropertyTextSingle);

$arTemplateParameters['BG_COLOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_BG_COLOR'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyTextSingle
];

$hPropertyFileSingle = function ($key, $arValue) {
    if ($arValue['PROPERTY_TYPE'] === 'F' && $arValue['MULTIPLE'] === 'N')
        return [
            'key' => $arValue['CODE'],
            'value' => '['.$arValue['CODE'].'] '.$arValue['NAME']
        ];

    return ['skip' => true];
};
$arPropertyFileSingle = $arProperties->asArray($hPropertyFileSingle);

$arTemplateParameters['DETAIL_BANNER'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_DETAIL_BANNER'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyFileSingle
];

$hPropertyCheckbox = function ($key, $arValue) {
    if ($arValue['PROPERTY_TYPE'] === 'L' && $arValue['LIST_TYPE'] === 'C' && $arValue['MULTIPLE'] === 'N')
        return [
            'key' => $arValue['CODE'],
            'value' => '['.$arValue['CODE'].'] '.$arValue['NAME']
        ];

    return ['skip' => true];
};
$arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);

$arTemplateParameters['BORDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_BORDER_USE'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyCheckbox
];

$arTemplateParameters['COLOR_WHITE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_15_COLOR_WHITE'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyCheckbox
];