<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><div class="intec-content">
	<div class="intec-content-wrapper">
		 <?php $APPLICATION->IncludeComponent(
			"intec:main.register", 
			"template.2", 
			array(
				"AUTH" => "Y",
				"REQUIRED_FIELDS" => array(
					1 => "PERSONAL_PHONE",	
					2 => "LAST_NAME",
					3 => "NAME",
				),
				"SET_TITLE" => "Y",
				"SHOW_FIELDS" => array(
					0 => "EMAIL",
					1 => "PERSONAL_PHONE",					
					2 => "LAST_NAME",
					3 => "NAME",
					4 => "SECOND_NAME",
					
					
				),
				"SUCCESS_PAGE" => "",
				"USER_PROPERTY" => array(
					0 => "UF_ADVERSTERING_AGENT",
					1 => "UF_JUR_ADDRESS",
					2 => "UF_SPERE",
					3 => "UF_NAME_COMPANY",
					4 => "UF_INN",
					5 => "UF_KPP",
					6 => "UF_SITE",
					7 => "UF_REQ",

				),
				"USER_PROPERTY_NAME" => "",
				"USE_BACKURL" => "Y",
				"COMPONENT_TEMPLATE" => "template.2",
				"CONSENT_URL" => ""
			),
			false
		);?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>