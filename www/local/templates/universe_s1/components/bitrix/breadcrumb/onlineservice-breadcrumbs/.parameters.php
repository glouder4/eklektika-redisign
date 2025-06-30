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
        'NAME' => 'Название главной страницы',
        'TYPE' => 'STRING',
        'DEFAULT' => 'Главная'
    ],
    'ITEM_0_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Ссылка главной страницы',
        'TYPE' => 'STRING',
        'DEFAULT' => '/'
    ],
    'ITEM_1' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Название первого раздела',
        'TYPE' => 'STRING'
    ],
    'ITEM_1_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Ссылка первого раздела',
        'TYPE' => 'STRING'
    ],
    'ITEM_2' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Название второго раздела',
        'TYPE' => 'STRING'
    ],
    'ITEM_2_LINK' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Ссылка второго раздела',
        'TYPE' => 'STRING'
    ],
    'DYNAMIC_TITLES' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => 'Использовать динамические названия',
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ]
];