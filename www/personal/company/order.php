<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use intec\eklectika\advertising_agent\Client;
use intec\eklectika\advertising_agent\Company;

CModule::IncludeModule("intec.eklectika");
if (!$_GET["order_id"] && !$_GET["company_id"]) {
	echo "Доступ запрещен";
	die();
	
} else {
	$idOrder = $_GET["order_id"];
	$idCompany = $_GET["company_id"];
	if (Client::isBossCompany($idCompany) && Company::hasOrder($idCompany, $idOrder)) {
		
	} else {
		echo "Доступ запрещен";
		die();
	}
}

?>
<?$APPLICATION->IncludeComponent(
	"intec:sale.personal.order.detail",
	"template.1",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ALLOW_INNER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CUSTOM_SELECT_PROPS" => array(""),
		"DISALLOW_CANCEL" => "N",
		"ID" => $idOrder,
		"ONLY_INNER_FULL" => "N",
		"PATH_TO_CANCEL" => "",
		"PATH_TO_COPY" => "",
		"PATH_TO_LIST" => "",
		"PATH_TO_PAYMENT" => "payment.php",
		"PICTURE_HEIGHT" => "110",
		"PICTURE_RESAMPLE_TYPE" => "1",
		"PICTURE_WIDTH" => "110",
		"PROP_1" => array(""),
		"PROP_2" => array(""),
		"REFRESH_PRICES" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
		"SET_TITLE" => "Y",
		"GUEST_MODE" => "Y"
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>