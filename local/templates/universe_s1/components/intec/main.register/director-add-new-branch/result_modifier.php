<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'CONSENT_URL' => null
], $arParams);

$arResult['CONSENT'] = [
    'SHOW' => false,
    'URL' => null
];

if (!Loader::includeModule('intec.core'))
    return;

$arResult['CONSENT'] = [
    'SHOW' => !defined('EDITOR') ? Properties::get('base-consent') : null,
    'URL' => $arParams['CONSENT_URL']
];

if (!empty($arResult['CONSENT']['URL'])) {
    $arResult['CONSENT']['URL'] = StringHelper::replaceMacros($arResult['CONSENT']['URL'], [
        'SITE_DIR' => SITE_DIR
    ]);
} else {
    $arResult['CONSENT']['SHOW'] = false;
}

$correctSortOrder = [
    [
        'WORK_COMPANY', // NAME => required
        'UF_CITY',
        'UF_SITE',
        'LOGIN',
        'UF_NAME_COMPANY',
        'UF_INN',
        'UF_REQ'
    ]
];

$arResult['SORTED_FIELDS'] = [];

foreach ($correctSortOrder as $key => $correctFieldBlock){
    foreach ($correctFieldBlock as $correctFieldName){
        if( in_array($correctFieldName,$arResult['SHOW_FIELDS']) ){
            $arResult['SORTED_FIELDS'][$key][$correctFieldName] = $correctFieldName;
            continue;
        }

        if( isset($arResult["USER_PROPERTIES"]["DATA"][$correctFieldName]) ){
            $arResult['SORTED_FIELDS'][$key][$correctFieldName] = $arResult["USER_PROPERTIES"]["DATA"][$correctFieldName];
            continue;
        }
    }
}

global $USER;
$arResult['HEAD_COMPANY_ID'] = false;

// Получаем компанию пользователя
$rsCompany = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID' => 57,
        'PROPERTY_OS_COMPANY_BOSS' => $USER->GetID(),
        'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING' => 31520,
        'ACTIVE' => 'Y'
    ],
    false,
    false,
    ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF','PROPERTY_OS_COMPANY_B24_ID','PROPERTY_OS_HEAD_COMPANY_B24_ID']
);
if( $headCompany = $rsCompany->GetNext() ){
    $arResult['HEAD_COMPANY_B24_ID'] = $headCompany['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'];
    $arResult['HEAD_COMPANY_ID'] = $headCompany['ID'];
}



