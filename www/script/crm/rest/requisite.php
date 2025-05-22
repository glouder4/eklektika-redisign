<?php
use intec\eklectika\advertising_agent\Company;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("intec.eklectika");

if ($_REQUEST['event'] == 'ONCRMREQUISITEUPDATE') {
	$file = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/requisite.txt';
	file_put_contents($file, date('Y.m.d').PHP_EOL, FILE_APPEND);
	file_put_contents($file, 'Запрос из Б24 на работу реквизитов ID - '.$_REQUEST['data']['FIELDS']['ID'].PHP_EOL, FILE_APPEND);

	$companyB24 = sendRequestB24("crm.requisite.get", [
		'id' =>  $_REQUEST['data']['FIELDS']['ID']
	]);	
	$companySite = Company::findByIdB24($companyB24['ENTITY_ID']);
	if ($companySite) {
		$companyUpdate = Company::update($companySite['ID'], [
			"INN" => $companyB24['RQ_INN'],
			"KPP" => $companyB24['RQ_KPP'],
		]);
		file_put_contents($file, 'Обновлена компания из Б24 '.$companyB24['ENTITY_ID'].' на сайте с ID - '.$companyUpdate['ID'].PHP_EOL, FILE_APPEND);
	}
}