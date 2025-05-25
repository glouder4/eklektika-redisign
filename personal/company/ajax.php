<?php
use intec\eklectika\advertising_agent\Company;
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule("intec.eklectika");
CModule::IncludeModule("iblock");

if (isset($_REQUEST["action"])) {
	if ($_REQUEST["action"] == "remove_staff") {
		if (!empty($_REQUEST["company_id"]) && !empty($_REQUEST["user_id"])) {
			if (Company::removeStaff($_REQUEST["company_id"], $_REQUEST["user_id"])) {
				echo "ok";
				die();
			}
		}
	}
}
?>