<?php
namespace intec\eklectika\advertising_agent;

use \Bitrix\Main\Loader;

class DismissEmployees {
	public static function UpdateEmployees($companyId, $userId){
		// Получаем Email сотрудника для дальнейшего поиска в Б24
		if(\CModule::IncludeModule("main")){ 
			$rsUser = \CUser::GetByID($userId);
			$arUser = $rsUser->Fetch();	
			$userB24Email = $arUser['EMAIL'];
		}
		
		$companyB24ID = self::findCompanyId($companyId); //Получаем ID компании в Б24
		$userB24ID = self::findUserId($userB24Email); //Получаем ID сотрудника в Б24
		
		self::dismiss($userB24ID, $companyB24ID);	//Отвязываем компанию от сотрудника в Б24
	}
	
	public static function findCompanyId($companyId){
		$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_ID_B24");
		$arFilter = Array("IBLOCK_ID"=>52, "ID" => $companyId, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement()){
			$arFields = $ob->GetFields();
			$result = $arFields['PROPERTY_ID_B24_VALUE'];
		}
		return $result;
	}
	
	public static function findUserId($email){
		$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.list';
		$qrList = array(
			'fields' => array(),
			'params' => array(),
			'select' => array(),
			'filter' => array("EMAIL" => $email)
		);

		$result = json_decode(self::newRequest($queryUrl, $qrList), true);
		$arResult = $result["result"][0]['ID'];
		
		return $arResult;
	}
	
	public static function dismiss($uid, $cid){
		$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.company.delete';
		$qrList = array(
			'id' => $uid,
			'fields' => array('COMPANY_ID' => $cid),
		);

		self::newRequest($queryUrl, $qrList);
	}

	public static function newRequest($queryUrl, $qrList){
		$queryData = http_build_query($qrList);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));

		if(!$result = curl_exec($curl)) {
			$result = curl_error($curl);
		}
		curl_close($curl);
		
		return $result;
	}
}

?>