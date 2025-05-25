<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('iblock'))
    return;

$arTemplateParameters['DATE_FORMAT'] = CIBlockParameters::GetDateFormat(
    Loc::getMessage('C_ARTICLES_CUSTOM_DATE_FORMAT'),
    'DATE'
);