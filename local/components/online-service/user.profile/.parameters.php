<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = [
    "GROUPS" => [
        "SETTINGS" => [
            "NAME" => Loc::getMessage("SETTINGS_GROUP_NAME"),
            "SORT" => 100,
        ],
    ],
    "PARAMETERS" => [
        "USER_ID" => [
            "PARENT" => "SETTINGS",
            "NAME" => Loc::getMessage("USER_ID_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "COLS" => 25,
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
];
