<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>

<?
use intec\eklectika\advertising_agent\Manager;
//CModule::IncludeModule("intec.eklectika");

$B24CompanyId = 2561;

$companyInfo = sendRequestB24("crm.company.get", ["id" => $B24CompanyId]);
$managerInfoNew = sendRequestB24("user.get", ["ID" => $companyInfo['ASSIGNED_BY_ID']]);

$managerInfo = $managerInfoNew[0];

$arData = [
	'B24_ID' => $managerInfo['ID'],
	'NAME' => $managerInfo['NAME'],
	'LAST_NAME' => $managerInfo['LAST_NAME'],
	'PHONE' => $managerInfo['PERSONAL_PHONE'],
	'EMAIL'=> $managerInfo['PERSONAL_MAILBOX'],
];

$manager = Manager::findByIdB24($companyInfo['ASSIGNED_BY_ID']);

if(!empty($manager)){
	Manager::update($manager['ID'], $arData);
} else {
	Manager::add($arData);
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>