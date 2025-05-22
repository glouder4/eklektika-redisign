<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?
$APPLICATION->IncludeComponent(
	"bitrix:main.profile", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(
			0 => "UF_JUR_ADDRESS",
			1 => "UF_SPERE",
			2 => "UF_NAME_COMPANY",
			3 => "UF_INN",
			4 => "UF_SITE",
			5 => "UF_REQ",
			6 => "UF_ADVERSTERING_AGENT",
		),
		"SEND_INFO" => "N",
		"CHECK_RIGHTS" => "N",
		"USER_PROPERTY_NAME" => ""
	),
	false
);
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>