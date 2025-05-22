<?php
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule("intec.eklectika");
if (isset($_REQUEST["action"]) && isset($_REQUEST["id"])) {
	$id = $_REQUEST["id"];	
	switch ($_REQUEST["action"]) {
		case "restore":
			$saveOld = $_REQUEST["saveOld"];			
			intec\eklectika\SavedBasket::restore($id, $saveOld=='true'?true:false);
			echo "ok";
		break;
		case "delete":
			intec\eklectika\SavedBasket::delete($id, $saveOld);
		break;
		case "create_kp":
			$idKp = intec\eklectika\SavedBasket::createKp($id);
			echo "https://".$_SERVER["SERVER_NAME"]."/personal/profile/kp/?id=".$idKp;
		break;
	}
}
?>