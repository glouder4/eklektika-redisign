<?php

namespace intec\eklectika\advertising_agent;

use \Bitrix\Main\Loader;
use \Bitrix\Sale\Order;

// класс для работы с клиентом
class Client {
	
	const FIZ = 4;
	const JUR = 5;
	const AGENT = 6;
	
	public static $statuses = [
		4 => "fiz",
		5 => "jur",
		6 => "agent"
	];
	
	/**
	* Найти пользователя по EMAIL
	*/
	public static function findByEmail($email) {	
		if (!$email) {
			return false;
		}
		$arFilter = [
			"EMAIL" => $email,
		];
		$rsUsers = \CUser::GetList([], [], $arFilter);
		while ($arUser = $rsUsers->Fetch()) {
			if ($arUser["EMAIL"] ==  $email) {
				return $arUser['ID'];
			}
		}
		return false;
	}

	
	// добавить пользователю статус рекламного агента
	public static function addStatusRA($idUser) {
		$user = new \CUser;
		$arGroups = [3, 4, 7, 12];
		\CUser::SetUserGroup($idUser, $arGroups);
		$user->Update($idUser, ['UF_ADVERSTERING_AGENT' => 1]);
		self::setStatus($idUser, 6);
	}
	
	// убрать статус рекламного агента и назначить физиком или обычной компанией
	public static function eraseStatusRA($idUser, $companyId) {
		$user = new \CUser;
		$arGroups = [3, 4, 7];
		\CUser::SetUserGroup($idUser, $arGroups);
		$user->Update($idUser, ['UF_ADVERSTERING_AGENT' => 0]);		
		// если привязан к компании, то это компания
		if ($companyId) {	
			self::setStatus($idUser, 5);
		} else {
			// иначе обычный физик
			self::setStatus($idUser, 4);
		}
	}
	
	
	// получить статус клиента
	public static function getStatus() {		
		$idUser = self::getId();		
		$rsUser = \CUser::GetByID($idUser);
		$arUser = $rsUser->Fetch();
		if ($arUser["UF_TYPE"]) {
			$status = self::$statuses[$arUser["UF_TYPE"]];
			if ($status == "agent") {
				if (self::isAgent($idUser)) {
					return "agent";
				} else {
					return "jur";
				}
			} else {
				return $status;
			}
		}
		return "fiz";
	}
	
	// установить статус пользователю
	public static function setStatus($idUser, $status) {
		$user = new \CUser;
		$user->Update($idUser, [
			"UF_TYPE" => $status
		]);
	}
	
	public static function getInfo($userId = null) {		
		if (!$userId) {
			$userId = self::getId();
		} 		
		$rsUser = \CUser::GetByID($userId);
		
		$arUser = $rsUser->Fetch();
		return $arUser;
	}
	
	// проверить пользователя на агента
	public static function isAgent($idUser = null) {
		if (!$idUser) {
			$idUser = self::getId();
		}
		$arGroups = \CUser::GetUserGroup($idUser);
		foreach ($arGroups as $groupId) {
			if ($groupId == 12) {
				return true;
			}
		}
		return false;
	}
	
	// получить компании пользователя
	public static function getCompanies() {
		$idUser = self::getId();
		return Company::getForUser($idUser);
	}
	
	// является ли пользователь руководителем компании
	public static function isBossCompany($companyId, $idUser = null) {
		if (!$idUser) {
			$idUser = self::getId();
		}
		$arCompany = Company::getById($companyId);
		if ($arCompany) {
			if ($arCompany["BOSS"] == $idUser) {
				return true;
			}
		}
		return false;
	}
	
	private static function getId() {
		global $USER;
		$idUser = $USER->GetID();	
		return $idUser;
	}
	
	public static function isAdmin() {
		global $USER;
		return $USER->IsAdmin();
	}	
	
	// получить количество заказов пользователя
	public static function getCountOrders($userId = null, $idCompany = null, $arFilter = []) {
		if (!$idUser) {
			$idUser = self::getId();
		}	
		Loader::includeModule('sale');
		Loader::includeModule('iblock');
		global $DB;
		$arFilter = array_merge([
			"USER_ID" => $userId,
		], $arFilter);			
		$parameters = [];
		if ($idCompany) {			
			$company = Company::getById($idCompany); 
			if ($company) {
				$arFilter['=PROPERTY_VAL.CODE'] = 'INN';
				$arFilter['=PROPERTY_VAL.VALUE'] = $company['INN'];
				$parameters['runtime'] = [
					new \Bitrix\Main\Entity\ReferenceField(
						'PROPERTY_VAL',
						'\Bitrix\sale\Internals\OrderPropsValueTable',
						["=this.ID" => "ref.ORDER_ID"],
						["join_type"=>"left"]
					)
				];
			}
		}
		$parameters['filter'] = $arFilter;
		$parameters['order'] =  ["DATE_INSERT" => "DESC"];
		$dbRes = Order::getList($parameters);
		$arOrders = [];
		while ($order = $dbRes->fetch()) {
			$arOrders[] = $order;
		}		
		return count($arOrders);
	}
	
	/** 
	* получить заказы пользователя
	*/
	public static function getOrders($userId = null, $idCompany = null, $arFilter = []) {	
		if (!$idUser) {
			$idUser = self::getId();
		}	
		Loader::includeModule('sale');
		Loader::includeModule('iblock');
		global $DB;
		$arFilter = array_merge([
			"USER_ID" => $userId,
		], $arFilter);			
				
		$parameters = [];
		if ($idCompany) {			
			$company = Company::getById($idCompany); 
			if ($company) {
				$arFilter['=PROPERTY_VAL.CODE'] = 'INN';
				$arFilter['=PROPERTY_VAL.VALUE'] = $company['INN'];
				$parameters['runtime'] = [
					new \Bitrix\Main\Entity\ReferenceField(
						'PROPERTY_VAL',
						'\Bitrix\sale\Internals\OrderPropsValueTable',
						["=this.ID" => "ref.ORDER_ID"],
						["join_type"=>"left"]
					)
				];
			}
		}
		$parameters['filter'] = $arFilter;
		$parameters['order'] =  ["DATE_INSERT" => "DESC"];

		$dbRes = Order::getList($parameters);
		$arOrders = [];
		while ($order = $dbRes->fetch()) {
			$arOrders[] = $order;
		}
		foreach ($arOrders as $key => $arOrder) {
			$arOrders[$key]["PRODUCTS"] = self::getProducts($arOrder["ID"]);
		}
		return $arOrders;
	}
	
	/** 
	* получить состав заказа
	*/
	static function getProducts($orderId) {
		Loader::includeModule('sale');
		Loader::includeModule('iblock');
		$basket = Order::load($orderId)->getBasket();
		$arItems = $basket->getOrderableItems();
		$arProducts = [];
		foreach ($arItems as $basketItem) {					
			$productId = $basketItem->getField("PRODUCT_ID");
			$id = $basketItem->getField("ID");			
			$arProduct = \CIBlockElement::GetByID($productId)->Fetch();		
			$arProducts[$id] = [
				"ID" => $id,
				"PRODUCT_ID" => $productId,
				"NAME" => $basketItem->getField("NAME"),
				"CODE" => $arProduct["CODE"],
				"QUANTITY" => $basketItem->getField("QUANTITY"),
				"PRICE" => $basketItem->getField("PRICE"),
			];
		}
		return $arProducts;
	}
	
	/**
	* добавить ответственного менеджера Клиенту
	*/
	public static function addManager($idUser, $idManager) {
		$user = new \CUser;
		$user->Update($idUser, [
			"UF_MANAGER" => $idManager
		]);
	}
	
	// добавить клиента
	public static function add() {
		$user = new \CUser;
		$arFields = [
			"NAME" => $arData['NAME'],
			"LAST_NAME" =>$arData['LAST_NAME'],
			"EMAIL" => $arData['EMAIL'][0]['VALUE'],
			"LOGIN" => $arData['EMAIL'][0]['VALUE'],
			"LID" => "ru",
			"ACTIVE" => "Y",
			"PASSWORD" => $arData['EMAIL'][0]['VALUE'],
			"CONFIRM_PASSWORD" => $arData['EMAIL'][0]['VALUE'],
			"PERSONAL_WWW" => $arData['WEB'][0]['VALUE'],
			"PERSONAL_PHONE" => $arData['PHONE'][0]['VALUE'],
			"WORK_POSITION" => $arData['POST'],
		];
		$idUser = $user->Add($arFields);
		return $idUser;
	}
	
	// обновить данные у пользователя
	public static function update($idUser, $arData) {
		$arFields = [];
		if ($arData["NAME"]) {
			$arFields["NAME"] = $arData["NAME"]; 
		}
		if ($arData["LAST_NAME"]) {
			$arFields["LAST_NAME"] = $arData["LAST_NAME"]; 
		}
		if ($arData['EMAIL'][0]['VALUE']) {
			$arFields["EMAIL"] = $arData['EMAIL'][0]['VALUE'];
		}
		if ($arData['WEB'][0]['VALUE']) {
			$arFields["PERSONAL_WWW"] = $arData['WEB'][0]['VALUE'];
		}
		if ($arData['PHONE'][0]['VALUE']) {
			$arFields["PERSONAL_PHONE"] = $arData['PHONE'][0]['VALUE'];
		}
		if ($arData['POST']) {
			$arFields["WORK_POSITION"] = $arData['POST'];
		}
		if ($arData['INN']) {
			$arFields["UF_INN"] = $arData['INN'];
		}
		if ($arData['KPP']) {
			$arFields["UF_KPP"] = $arData['KPP'];
		}
		if ($arData['NAME_COMPANY']) {
			$arFields["UF_NAME_COMPANY"] = $arData['NAME_COMPANY'];
		}
		if ($arData['ADDRESS']) {
			$arFields["UF_JUR_ADDRESS"] = $arData['ADDRESS'];
		}
		if ($arData['SPERE']) {
			$arFields["UF_SPERE"] = $arData['SPERE'];
		}
		$user = new \CUser;
		$user->Update($idUser, $arFields);		
	}
	
	
	// заблокировать пользователя
	public static function block($idUser) {
		$user = new \CUser;
		$user->Update($idUser, [
			"BLOCKED" => "Y", 
			"ACTIVE" => "N"
		]);	
	}
	
	// разблокировать пользователя
	public static function unblock($idUser) {
		$user = new \CUser;
		$user->Update($idUser, [
			"BLOCKED" => "N", 
			"ACTIVE" => "Y"
		]);	
	}
	
	// взять резерв в работу
	public static function addInWork($orderId) {
		\CModule::IncludeModule("sale");
		\CModule::IncludeModule("catalog");
		$order = Order::load($orderId);
		// проверить принадлежность пользователя заказу
		$userId = $order->getField("USER_ID");
		if ($userId != self::getId()) {
			return false;
		}
		if ($order) {
			$request = $success = $inWork = false;
			$propertyCollection = $order->getPropertyCollection();
			foreach ($propertyCollection as $property){				
				$propertyCode = $property->getField('CODE');				
				
				if ($propertyCode == "REQUEST_TO_RESERVE" ) {
					$propertyValue = $property->getValue();	
					if ($propertyValue == "Y") {
						$request = true;									
						continue;
					}	
				} 
				
				if ($propertyCode == "RESERVE_SUCCESS" ) {
					$propertyValue = $property->getValue();	
					if ($propertyValue == "Y") {
						$success = true;
						continue;
					}	
				}
				
				if ($propertyCode == "IN_WORK" ) {
					$propertyValue = $property->getValue();	
					if ($propertyValue == "Y") {
						$inWork = true;
						continue;
					} else {
						$idInWork = $property->getField('ORDER_PROPS_ID');
						
					}
				}
			}
			
			// если отправлен запрос на резерв
			// и запрос одобрен
			// и еще не был отправлен в работу
			if ($request && $success && !$inWork) {
				// отправть заказ в работу
				foreach ($propertyCollection as $oProperty) {				
					if ($oProperty->getField('ORDER_PROPS_ID') == $idInWork) {
						$oProperty->setValue('Y');
						$order->doFinalAction(true);
						$result = $order->save();
						return true;
					}
				}
			}
			
		}
		return false;
	}
	
	// оставить только нужного типа плательщика при заказе
	public static function onSaleComponentOrderOneStepPersonType(&$arResult, &$arModifiedResult , &$arParams) {
		$status = self::getStatus();
        if (is_array($arResult["PERSON_TYPE"])) {       
			if ($status == 'fiz') {				
				unset($arResult["PERSON_TYPE"][2]);
				$arResult["JS_DATA"]["LAST_ORDER_DATA"]["PERSON_TYPE"] = 1;
			}			
			if ($status == 'jur' || $status == 'agent') {	
				unset($arResult["PERSON_TYPE"][1]);
				$arResult["JS_DATA"]["LAST_ORDER_DATA"]["PERSON_TYPE"] = 2;
			}	
		}
    }
}