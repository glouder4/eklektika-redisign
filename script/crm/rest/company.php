<?php
use intec\eklectika\advertising_agent\Company;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("intec.eklectika");

if ($_REQUEST['event'] == 'ONCRMCOMPANYADD' || $_REQUEST['event'] == 'ONCRMCOMPANYUPDATE') {

	$companyB24Id = $_REQUEST['data']['FIELDS']['ID'];
	$companySite = Company::findByIdB24($companyB24Id);
	
	if (!empty($companyB24Id)) {

		$file = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/company.txt';
		file_put_contents($file, date('Y.m.d').PHP_EOL, FILE_APPEND);
		file_put_contents($file, 'Запрос из Б24 на работу компании ID - '.$companyB24Id.PHP_EOL, FILE_APPEND);
		
		// получить данные о компании с Б24
		$companyInfo = sendRequestB24("crm.company.get", ["id" => $companyB24Id]);
		$dataCompany = [
			"NAME_COMPANY" => $companyInfo['TITLE'],
			"WEBSITE" => $companyInfo['WEB'][0]['VALUE'],
			"SPHERE" => $companyInfo['UF_CRM_1669208000616'],
			"ADDRESS" => $companyInfo['UF_CRM_1669208295583'],
			"ID_B24" => $companyInfo['ID'],
			"PHONE" => $companyInfo['PHONE'][0]['VALUE'],
			"EMAIL" => $companyInfo['EMAIL'][0]['VALUE'],
		];
		if (!$companySite && $_REQUEST['event'] == 'ONCRMCOMPANYADD') {
			$companyAdd = Company::add($dataCompany);
			file_put_contents($file, 'Добавлена компания из Б24 '.$companyInfo['ID'].' на сайт с ID - '.$companyAdd['ID'].PHP_EOL, FILE_APPEND);
		}

		if ($companySite && $_REQUEST['event'] == 'ONCRMCOMPANYUPDATE') {		
			$companyUpdate = Company::update($companySite['ID'], $dataCompany);			
			file_put_contents($file, 'Обновлена компания из Б24 '.$companyInfo['ID'].' на сайте с ID - '.$companyUpdate['ID'].PHP_EOL, FILE_APPEND);
		}
				
		// получить сотрудников компании
		$arContacts = sendRequestB24("crm.company.contact.items.get", ['id' => $companyB24Id]);
		$arStaffs = [];
		foreach ($arContacts as $arContact) {	
			$contactInfo = sendRequestB24("crm.contact.get", ["id" =>  $arContact["CONTACT_ID"]]);	
			$idStaff = intec\eklectika\advertising_agent\Client::findByEmail($contactInfo["EMAIL"][0]["VALUE"]);
			if ($idStaff) {
				$arStaffs[] = $idStaff;
			}
		}
		
		// привязать этих сотрудников в инфоблоке
		if ($companySite["ID"]) {
			intec\eklectika\advertising_agent\Company::update($companySite["ID"], ["STAFFS" => $arStaffs]);
		}
	}
}