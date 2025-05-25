<?php
use intec\eklectika\advertising_agent\Company;
use intec\eklectika\advertising_agent\Client;

define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule("intec.eklectika");
CModule::IncludeModule("iblock");

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "find_company" && $_REQUEST["inn"]) {
	$inn = $_REQUEST["inn"];
	$company = Company::findByInn($inn);
	if ($company) {
		unset($company["ID"]);
		unset($company["BOSS"]);
		unset($company["STAFFS"]);
		echo json_encode($company);
	} else {
		echo "{}";
	}
	die();
}

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add_in_work" && isset($_REQUEST["order_id"])) {
	if (Client::addInWork($_REQUEST["order_id"])) {
		echo "ok";
	} else {
		echo "error";
	}
	
}	
