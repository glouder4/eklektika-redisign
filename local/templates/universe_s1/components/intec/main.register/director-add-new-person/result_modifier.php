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
        'NAME', // NAME => required
        'LAST_NAME',
        'EMAIL',
        'LOGIN',
        'WORK_POSITION',
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

global $USER;
$arResult['HEAD_COMPANY_ID'] = false;
$arResult['SHOW_ERROR_NO_HEAD_COMPANY'] = false;

// Проверяем, передан ли ID головной компании в параметрах
if (!empty($_GET['head_company']) && intval($_GET['head_company']) > 0) {
    // Используем переданный ID головной компании
    $headCompanyId = intval($_GET['head_company']);
    
    // Получаем данные головной компании
    $company = new \OnlineService\Site\Company();
    $headCompanyData = $company->getCompany($headCompanyId);
    
    if ($headCompanyData) {
        // Проверяем, является ли текущий пользователь руководителем этой компании
        $currentUserId = $USER->GetID();
        $companyBosses = $headCompanyData['OS_COMPANY_BOSS'] ?? [];
        
        // Преобразуем в массив если пришло одно значение
        if (!is_array($companyBosses)) {
            $companyBosses = $companyBosses ? [$companyBosses] : [];
        }
        
        // Проверяем права доступа: администратор или руководитель компании
        if ($USER->IsAdmin() || in_array($currentUserId, $companyBosses)) {
            // Все ок, пользователь имеет право добавлять дочерние компании
            $arResult['HEAD_COMPANY_ID'] = $headCompanyId;
            $arResult['HEAD_COMPANY_B24_ID'] = $headCompanyData['OS_COMPANY_B24_ID'];
        } else {
            // Пользователь не является руководителем этой компании
            $arResult['SHOW_ERROR_NO_HEAD_COMPANY'] = true;
        }
    } else {
        // Компания не найдена
        $arResult['SHOW_ERROR_NO_HEAD_COMPANY'] = true;
    }
} else {
    // Если параметр не передан, показываем ошибку
    $arResult['SHOW_ERROR_NO_HEAD_COMPANY'] = true;
}



