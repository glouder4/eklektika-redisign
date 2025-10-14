<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "USER_ID" => [
            "PARENT" => "BASE",
            "NAME" => "ID пользователя",
            "TYPE" => "STRING",
            "DEFAULT" => "={$_REQUEST['id']}",
        ],
        "TYPE" => [
            "PARENT" => "BASE",
            "NAME" => "Тип редактирования",
            "TYPE" => "LIST",
            "VALUES" => [
                "profile" => "Профиль",
                "companies" => "Компании",
            ],
            "DEFAULT" => "profile",
        ],
        "CACHE_TIME" => ["DEFAULT" => 3600],
    ],
];

