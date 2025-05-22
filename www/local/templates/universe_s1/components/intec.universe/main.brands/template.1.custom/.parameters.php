<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LINK_USE'] === 'Y') {
    $arTemplateParameters['LINK_BLANK'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_LINK_BLANK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        3 => 3,
        4 => 4,
        5 => 5
    ],
    'DEFAULT' => 4
];

$arTemplateParameters['EFFECT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'none' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_NONE'),
        'blur' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_BLUR'),
        'brightness' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_BRIGHTNESS'),
        'grayscale' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_GRAYSCALE'),
        'sepia' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_SEPIA'),
        'zoom' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_EFFECT_ZOOM')
    ],
    'DEFAULT' => 'none'
];

$arTemplateParameters['TRANSPARENCY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_TRANSPARENCY'),
    'TYPE' => 'STRING',
    'DEFAULT' => 0
];

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] === 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_BRANDS_TEMPLATE_1_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}