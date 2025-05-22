<?php
namespace intec\eklectika\advertising_agent;

use \Bitrix\Main\Loader;
use \Bitrix\Sale\Order;

define('URL_B24', 'https://testb24.yoliba.ru/');

class Company {
	
	/**
	* добавление компании
	* ["NAME", "INN", "BOSS", "WEBSITE", "SPHERE", "ADDRESS", "STAFFS", "KPP", "NAME_COMPANY"]
	*/ 
	public static function add($arData) {
		$inn = $arData["INN"];
		$arCompany = null;
		// найти компанию по INN
		if (!empty($arData["INN"])) {
			$arCompany = CompanyRepository::findByInn($arData["INN"]);
		}
		
		if ($arCompany) {
			
		} 
		// иначе создать новую компанию и привязать туда пользователя
		else {
			$companyId = CompanyRepository::add($arData);
			return $companyId;	
		}
	}
	
	/**
	* активировать компанию
	*/
	public static function activate($idCompany) {
		return CompanyRepository::activate($idCompany);
	}
	
	/**
	* обновление компании
	* ["INN", "BOSS", "WEBSITE", "SPHERE", "ADDRESS", "STAFFS", "KPP", "NAME_COMPANY", "ID_B24", "PHONE", "EMAIL"]
	*/
	public static function update($id, $arData) {
		CompanyRepository::update($id, $arData);
	}
	
	/** 
	* найти компанию по ИНН
	*/
	public static function findByInn($inn) {
		if ($inn) {
			return CompanyRepository::findByInn($inn);
		} else {
			return false;
		}
	}
	
	/** 
	* найти компанию по Битрикс24
	*/
	public static function findByIdB24($idB24) {
		if ($idB24) {
			return CompanyRepository::findByIdB24($idB24);
		} else {
			return false;
		}
	}
	
	// добавить профиль пользователя из реквизитов компании
	public static function addProfile($idUser, $idCompany) {
		CompanyRepository::addProfile($idUser, $idCompany);
	}
	
	// получить данные компании по id
	public static function getById($idCompany) {
		return CompanyRepository::getById($idCompany);
	}
	
	// найти компании у пользователя
	public static function getForUser($userId) {
		return CompanyRepository::getForUser($userId);
	}
	
	/** 
	* уволить сотрудника
	*/ 
	public static function removeStaff($companyId, $userId) {
		if (Client::isBossCompany($companyId) || Client::isAdmin()) {
			CompanyRepository::removeStaff($companyId, $userId);
			// установить статус клиент - физическое лицо
			Client::setStatus($userId, Client::FIZ);		
			DismissEmployees::UpdateEmployees($companyId, $userId);
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* получить заказы компании
	*/
	public static function getOrders($idCompany) {	
		global $DB;	
		$company = self::getById($idCompany);
	
		Loader::includeModule('sale');
		Loader::includeModule('iblock');
		$arFilter = [
			'=PROPERTY_VAL.CODE' => 'INN',
			'=PROPERTY_VAL.VALUE' => $company['INN'],
		];			
		
		$parameters = [
			'filter' => $arFilter,
			'order' => ["DATE_INSERT" => "DESC"],
			'runtime' => [
				new \Bitrix\Main\Entity\ReferenceField(
					'PROPERTY_VAL',
					'\Bitrix\sale\Internals\OrderPropsValueTable',
					["=this.ID" => "ref.ORDER_ID"],
					["join_type"=>"left"]
				)
			]
		];

		$dbRes = Order::getList($parameters);
		$arOrders = [];
		while ($order = $dbRes->fetch()) {
			$arOrders[] = $order;
		}
		foreach ($arOrders as $key => $arOrder) {
			$arOrders[$key]["PRODUCTS"] = Client::getProducts($arOrder["ID"]);
		}
		return $arOrders;
	}
	
	/**
	* проверить принадлежность заказа к компании
	*/
	public static function hasOrder($idCompany, $idOrder) {
		global $DB;	
		$company = self::getById($idCompany);
		
		$company = self::getById($idCompany);
	
		Loader::includeModule('sale');
		Loader::includeModule('iblock');
		$arFilter = [
			'=ID' => $idOrder,
			'=PROPERTY_VAL.CODE' => 'INN',
			'=PROPERTY_VAL.VALUE' => $company['INN'],
		];			
		
		$parameters = [
			'filter' => $arFilter,
			'order' => ["DATE_INSERT" => "DESC"],
			'runtime' => [
				new \Bitrix\Main\Entity\ReferenceField(
					'PROPERTY_VAL',
					'\Bitrix\sale\Internals\OrderPropsValueTable',
					["=this.ID" => "ref.ORDER_ID"],
					["join_type"=>"left"]
				)
			]
		];

		$dbRes = Order::getList($parameters);
		$order = $dbRes->fetch();
		
		if ($order) {
			return true;
		} 
		return false;
	}

	// добавить босса к компании
	public static function addBoss($idCompany, $idBoss) {
		self::update($idCompany, ["BOSS" => $idBoss]);
	}
	
	// добавить сотрудника компании
	public static function addStaff($idCompany, $idUser) {
		CompanyRepository::addUser();
	}
}