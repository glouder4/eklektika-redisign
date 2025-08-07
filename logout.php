<?php
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

    global $USER;

    $USER->Logout();
    LocalRedirect('/');