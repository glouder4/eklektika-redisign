<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Type;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\net\Url;
use intec\core\helpers\StringHelper;
use intec\eklectika\advertising_agent\Client;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('intec.eklectika'))
    return;

$typeClient = Client::getStatus();
$typePersons = [];
$arResult["TYPE_CLIENT"] = $typeClient;
$arResult["USER_INFO"] = Client::getInfo();
foreach ($arResult['PERSON_TYPES'] as $arPersonType) {
	if ($arPersonType["ID"] == 1 && $typeClient == "fiz") {
		$typePersons[] = $arPersonType;
	} else {
		if ($arPersonType["ID"] == 2 && ($typeClient == "agent" || $typeClient == "jur")) {
			$typePersons[] = $arPersonType;
		} else {
			continue;
		}
	} 
}
$arResult['PERSON_TYPES'] = $typePersons;
if (!empty($arResult['ORDER_PROPS'])) {
    foreach ($arResult['ORDER_PROPS'] as &$arGroupProps) {
        if (!empty($arGroupProps['PROPS'])) {
            foreach ($arGroupProps['PROPS'] as &$arProp) {
                $arResult['ORDER_PROPS_VALUES']['ORDER_PROP_'.$arProp['ID']] = $arProp['MULTIPLE'] === 'Y' ? unserialize($arProp['~DEFAULT_VALUE']) : $arProp['~DEFAULT_VALUE'];
            }

            unset($arProp);
        }
    }

    unset($arGroupProps);
}
// если компания или рекламный агент
if ($typeClient == "jur" || $typeClient == "agent") {
	foreach ($arResult["ORDER_PROPS"] as &$arGroupProps) {
		if (!empty($arGroupProps['PROPS'])) {
			foreach ($arGroupProps['PROPS'] as &$arProp) {
				switch ($arProp["NAME"]) {
					case "Название компании":
						$arProp["HIDDEN"] = "Y";
						$arProp["VALUE"] = $arResult["USER_INFO"]["UF_NAME_COMPANY"];
						$arGroupProps["HIDDEN"] = "Y";
					break;
					case "ИНН":
						$arProp["HIDDEN"] = "Y";
						$arProp["VALUE"] = $arResult["USER_INFO"]["UF_INN"];
					break;
					case "КПП":
						$arProp["HIDDEN"] = "Y";
						$arProp["VALUE"] = $arResult["USER_INFO"]["UF_KPP"];
					break;
					case "E-Mail":
						$arProp["HIDDEN"] = "Y";
						$arProp["VALUE"] = $arResult["USER_INFO"]["EMAIL"];
					break;
					case "Юридический адрес":
						$arProp["HIDDEN"] = "Y";
						$arProp["VALUE"] = $arResult["USER_INFO"]["UF_JUR_ADDRESS"];
					break;
				}
			}
		}
	}
} else {
	
}

