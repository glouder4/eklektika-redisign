<?php
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule("intec.eklectika");
$arProducts = intec\eklectika\SavedBasket::getProducts();
$idKp = intec\eklectika\advertising_agent\Offer::loadFromBascet($arProducts);
header("Location: https://".$_SERVER["SERVER_NAME"]."/personal/profile/kp/?id=".$idKp);
exit( );
?>