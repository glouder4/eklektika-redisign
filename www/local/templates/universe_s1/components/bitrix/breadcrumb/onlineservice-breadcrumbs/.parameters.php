<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [
    'HIDE_ICONS' => [
        'PARENT' => 'VISUAL',
        'NAME' => 'Скрыть иконки',
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'BREADCRUMB_MOBILE_COMPACT' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('BREADCRUMB_MOBILE_COMPACT'),
        'TYPE' => 'CHECKBOX'
    ],
    'BREADCRUMB_DROPDOWN_USE' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('BREADCRUMB_DROPDOWN_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ],
    'ITEM_0' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 0 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_0_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 0 - Ссылка',
        'TYPE' => 'STRING'
    ],
    'ITEM_1' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 1 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_1_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 1 - Ссылка',
        'TYPE' => 'STRING'
    ],
    'ITEM_2' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 2 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_2_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 2 - Ссылка',
        'TYPE' => 'STRING'
    ],
    'ITEM_3' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 3 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_3_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 3 - Ссылка',
        'TYPE' => 'STRING'
    ],
    'ITEM_4' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 4 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_4_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 4 - Ссылка',
        'TYPE' => 'STRING'
    ],
    'ITEM_5' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 5 - Название',
        'TYPE' => 'STRING'
    ],
    'ITEM_5_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Элемент 5 - Ссылка',
        'TYPE' => 'STRING'
    ]
];