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
        'LOGIN'
    ],
    [
        'NAME',
        'LAST_NAME',
        'SECOND_NAME',
        'PERSONAL_BIRTHDAY',
        'PERSONAL_PHONE',
        'EMAIL',
        'WORK_POSITION'
    ],
    [
        'UF_NAME_COMPANY',
        'UF_INN',
        'UF_REQ',
        'PERSONAL_NOTES',
        'PASSWORD',
        'CONFIRM_PASSWORD'
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

