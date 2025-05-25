<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use intec\eklectika\advertising_agent\Client;
use intec\eklectika\advertising_agent\Company;

CModule::IncludeModule("intec.eklectika");
if (!$_GET["order_id"] && !$_GET["company_id"]) {
	echo "Доступ запрещен";
	die();
	
} else {
	$idCompany = $_GET["company_id"];
	if (Client::isBossCompany($idCompany) && $_REQUEST["company_inn"] ) {
		
	} else {
		echo "Доступ запрещен";
		die();
	}
}

?>

<?$APPLICATION->IncludeComponent(
	"intec:sale.personal.order.list",
	"template.1",
	Array(
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"PATH_TO_DETAIL" => "order.php?order_id=#ID#&company_id=".$_GET["company_id"],								
		"PATH_TO_PAYMENT" => "payment.php",
		"ORDERS_PER_PAGE" => 20,
		//"ID" => $ID,
		//"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"NAV_TEMPLATE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"HISTORIC_STATUSES" => "F",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		'USE_FILTER' => 'N',
		'USE_SEARCH' => 'Y',
		'SHOW_ONLY_CURRENT_ORDERS' => 'Y',
		'COMPANY_INN' => $_REQUEST["company_inn"],
		'USER_ID' => $_REQUEST["user_id"],
		'TITLE' => 'Заказы компании'
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>