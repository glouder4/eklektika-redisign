<?php
// @ma - веб хук на изменения контактов
use intec\eklectika\advertising_agent\Company;
use intec\eklectika\advertising_agent\Client;
use intec\eklectika\advertising_agent\Manager;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("intec.eklectika");
$contactId = $_REQUEST['data']['FIELDS']['ID'];
if (empty($contactId)) {
	return;
}

// информация о контакте
$contactInfo = sendRequestB24("crm.contact.get", ["id" => $contactId]);	
if (empty($contactInfo['EMAIL'][0]['VALUE']) && empty($contactInfo['NAME'])) {
	return;
}

// информация о компании
$arCompanies = sendRequestB24("crm.contact.company.items.get", ['id' => $contactInfo['ID']]);
$companyCRM = null;

if ($arCompanies) {
	$companyCRM = $arCompanies[0];
	$companySite = Company::findByIdB24($companyCRM["COMPANY_ID"]);	
}

// если добавлен новый контакт
if ($_REQUEST['event'] == 'ONCRMCONTACTADD') {	
	$logFile = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/user.add.txt';
	addLog($logFile, 'ID contact CRM - '.$contactId);	
		
	// Найти пользователя по EMAIL		
	$idUser = Client::findByEmail($contactInfo['EMAIL'][0]['VALUE']);	
	if ($idUser) {
		addLog($logFile, 'User for email exists - '.$idUser);
	} else {
		$idUser = Client::add($contactInfo);
		if (intval($idUser) > 0) {
			
		} else {
			return;
		}
	} 	
}
// если контакт обновился
if ($_REQUEST['event'] == 'ONCRMCONTACTUPDATE') {	
	$logFile = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/user.update.txt';	
	addLog($logFile, 'ID contact CRM - '.$contactId);
	
	// найти пользователя по email	
	addLog($logFile, 'Email - '.$contactInfo['EMAIL'][0]['VALUE']);
	$idUser = Client::findByEmail($contactInfo['EMAIL'][0]['VALUE']);	
	// если пользователя на сайте нет, то создать
	if (!$idUser) {
		$idUser = Client::add($contactInfo);
	} else {
		// обновить его контактые данные
		Client::update($idUser, $contactInfo);
	}
}

if ($idUser) {		

	// блокировка пользователя
	if ($contactInfo['UF_CRM_1681120601710'] == 1) {
		// это большая честь быть в чёрном списке
		Client::block($idUser);
	} else {
		Client::unblock($idUser);
	}
	
	// если это рекламный агент
	if ($contactInfo['UF_CRM_1698752707853'] == 1) {
		// промодерировать его статус
		Client::addStatusRA($idUser);
		addLog($logFile, 'Пользователь '.$idUser.' рекламный агент');
	} else {
		// установить нужный статус (физик или компания)
		Client::eraseStatusRA($idUser, $companyCRM);		
		addLog($logFile, 'Пользователь '.$idUser.' не рекламный агент');		
	}
	
	// добавить ответственного
	$arManager = Manager::findByIdB24($contactInfo["ASSIGNED_BY_ID"]);
	
	if ($arManager) {
		$idManager = $arManager["ID"];
	} else {
		// добавить менеджера
		$dataManager = sendRequestB24("user.get", ["ID" => $contactInfo["ASSIGNED_BY_ID"]]);
		$dataManager = $dataManager[0];
		$idManager = Manager::add([
			"NAME" => $dataManager["NAME"],
			"SECOND_NAME" => $dataManager["LAST_NAME"],
			"EMAIL" => $dataManager["PERSONAL_MAILBOX"],
			"PHONE" => $dataManager["PERSONAL_PHONE"],
			"B24_ID" => $contactInfo["ASSIGNED_BY_ID"]
		]);
	}
	
	if ($idManager) {
		Client::addManager($idUser, $idManager);		
	}
	
	// если клиент привязан к компании	
	if ($companyCRM) {				
		// привязать пользователя к сотрудникам, если он не там
		$arStaffs = $companySite["STAFFS"];	
		if (!$arStaffs) {
			$arStaffs[] = $idUser;
			// добро пожаловать новый сотрудник
			intec\eklectika\advertising_agent\Company::update($companySite["ID"], ["STAFFS" => $arStaffs]);
			
		} else {
			if (!in_array($idUser, $arStaffs)) {
				$arStaffs[] = $idUser;
				// добро пожаловать новый сотрудник
				intec\eklectika\advertising_agent\Company::update($companySite["ID"], ["STAFFS" => $arStaffs]);
			}	
		}
		// установить для пользователя данные из компании
		Client::update($idUser, [
			"INN" => $companySite["INN"],
			"KPP" => $companySite["KPP"],
			"NAME_COMPANY" => $companySite["NAME_COMPANY"],
			"SITE" =>  $companySite["SITE"],
			"ADDRESS" =>  $companySite["ADDRESS"],	
		]);
		
		// добавить или обновить профиль пользователя
		
			
		
		// назначить руководителя
		if ($contactInfo['UF_CRM_1712732096274'] == 1) {
			// если пользователь не руководитель, то назначить его
			if ($companySite["BOSS"] != $idUser) {
				// в компании новый босс
				intec\eklectika\advertising_agent\Company::addBoss($companySite["ID"], $idUser);
			}
			addLog($logFile, 'Пользователь '.$idUser.' назначен руководителем '.$companySite["ID"], FILE_APPEND);
		} else {
			// если пользователь руководитель, то убрать его оттуда
			if ($companySite["BOSS"] == $idUser) {
				// кажется ты больше не можешь быть руководителем
				intec\eklectika\advertising_agent\Company::addBoss($companySite["ID"], "");
				addLog($logFile, 'Пользователь '.$idUser.' больше не руководитель '.$companySite["ID"]);
			}
		}				
	} else {
		// найти компании к которой привязан пользователь
		$arCompanies = Company::getForUser($idUser);
		// если компания есть, то нужно уволить его оттуда
		if ($arCompanies) {
			foreach ($arCompanies as $arCompany) {
				// прощай чел, ты больше нам не нужен
				Company::removeStaff($arCompany["ID"], $idUser);
				addLog($logFile, 'Пользователь '.$idUser.' уволен с компании '.$arCompany["ID"]);
			}
		}
		// удалить реквизиты компании
		Client::update($idUser, [
			"INN" => "",
			"KPP" => "",
			"NAME_COMPANY" => "",
			"SITE" =>  "",
			"ADDRESS" =>  "",
		]);
		// 
		// удалить профиль с данным инн
		
	}
}

function addLog($file, $text) {
	file_put_contents($file, date("d.m.Y H:i:s")." - ".$text.PHP_EOL, FILE_APPEND);
}
