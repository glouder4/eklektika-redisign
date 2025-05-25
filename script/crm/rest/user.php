<?
use intec\eklectika\advertising_agent\Manager;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if($_REQUEST['event'] == 'ONUSERUPDATE'){
	$managerInfoNew = sendRequestB24("user.get", ["ID" => $_REQUEST['data']['FIELDS'][0]['ID']]);
	$managerInfo = $managerInfoNew[0];
	
	$arData = [
		'B24_ID' => $managerInfo['ID'],
		'NAME' => $managerInfo['NAME'],
		'LAST_NAME' => $managerInfo['LAST_NAME'],
		'PHONE' => $managerInfo['PERSONAL_PHONE'],
		'EMAIL'=> $managerInfo['PERSONAL_MAILBOX'],
	];
	
	$manager = Manager::findByIdB24($arData['B24_ID']);
	
	if(!empty($manager)){
		Manager::update($manager['ID'], $arData);
	} else {
		Manager::add($arData);
	}
}