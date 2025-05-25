<?php
namespace intec\eklectika\advertising_agent;
use Bitrix\Main\Loader;

class CompanyRepository {
    private static $typeIblock = 'personal';
    private static $iblockId = 52;
	
	// коды свойств компании
	private static $codeProps = [		
		"INN",
		"BOSS",
		"WEBSITE",
		"SPHERE",
		"ADDRESS",
		"STAFFS",
		"KPP",
		"NAME_COMPANY",
		"PHONE",
		"EMAIL",
		"ID_B24"
	];
	
    /**
    * ["INN", "BOSS", "WEBSITE", "SPHERE", "ADDRESS", "STAFFS"," KPP", "NAME_COMPANY", "ID_B24"] 
    */
    public static function add($params) {
        $el = new \CIBlockElement;
        $PROP = [];
      	
		foreach (self::$codeProps as $code) {
			$PROP[$code] =  $params[$code];
		}
		
		$arLoadProductArray = [
			"IBLOCK_SECTION_ID" => false,
			"IBLOCK_TYPE" => self::$typeIblock,
			"IBLOCK_ID" => self::$iblockId,
			"PROPERTY_VALUES" => $PROP,
			"NAME" => $params["NAME_COMPANY"], 
			"ACTIVE" => "Y",
			"CODE" => $params["ID_B24"]
		];
		if ($companyId = $el->Add($arLoadProductArray)) {		
			return $companyId;
		} else {
			echo "Error: ".$el->LAST_ERROR;
			return false;
		}
    }
	
	public static function activate($idCompany) {
		$el = new \CIBlockElement;
		$res = $el->Update($idCompany, ["ACTIVE"=> "Y"]);
	}
	
	public static function update($id, $arData) {
		foreach ($arData as $code => $data) {
			\CIBlockElement::SetPropertyValuesEx(
				$id,  
				self::$iblockId,  
				[$code => $data]			
			);			
		}
	}

	/**
	* добавить пользователя к компании
	*/
	public static function addUser($idCompany, $idUser) {	
		$arUsers = self::getUsers($idCompany);
		$arUsers[] = $idUser;
		$arUsers = array_unique($arUsers);
		\CIBlockElement::SetPropertyValuesEx(
			$idCompany,  
			self::$iblockId,  
			["STAFFS" => $arUsers]			
		);
	}
	
	/**
	* список пользователей компании
	*/
	public static function getUsers($idCompany) {
		$arCompany = self::getById($idCompany);
		return $arCompany["STAFFS"];
	}
	
	/**
	* найти компании для пользователя
	*/
	public static function getForUser($idUser) {
		$arFilter = [
			"IBLOCK_ID" => self::$iblockId,
			"IBLOCK_TYPE" => self::$typeIblock,
			"PROPERTY_STAFFS" => $idUser
		];
		$arSelect = [];	
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while ($ob = $res->GetNextElement()) {
			$arCompany = [];
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();	
			
			$arCompany["ID"] = $arFields["ID"];  			
			foreach (self::$codeProps as $code) {
				$arCompany[$code] = $arProps[$code]["VALUE"];  
			}			
			$arResult[] = $arCompany;
		}
		if (count($arResult)) {
			return $arResult;
		} else {
			return false;
		}
	}
	
	/**
	*
	*/
	public static function getById($id) {		
		$rsCompany = \CIBlockElement::GetById($id);
		$ob = $rsCompany->GetNextElement();
		$arProps = $ob->GetProperties();
		$arFields = $ob->GetFields();
		$arCompany["ID"] = $arFields["ID"];        
		foreach (self::$codeProps as $code) {
			$arCompany[$code] = $arProps[$code]["VALUE"];  
		}
		return $arCompany;
	}
	
	public static function findByIdB24($id) {
		$arFilter = [
			"IBLOCK_ID" => self::$iblockId,
			"IBLOCK_TYPE" => self::$typeIblock,
			"PROPERTY_ID_B24" => $id
		];
		$arSelect = [];	
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();	
			$arResult["ID"] = $arFields["ID"];  	
			foreach (self::$codeProps as $code) {
				$arResult[$code] = $arProps[$code]["VALUE"];  
			}		
		}
		if (count($arResult)) {
			return $arResult;
		} else {
			return false;
		}
	}
	/**
	* поиск компании по инн
	*/
	public static function findByInn($inn) {		
		$arFilter = [
			"IBLOCK_ID" => self::$iblockId,
			"IBLOCK_TYPE" => self::$typeIblock,
			"PROPERTY_INN" => $inn
		];
		$arSelect = [];	
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();	
			$arResult["ID"] = $arFields["ID"];  			
			foreach (self::$codeProps as $code) {
				$arResult[$code] = $arProps[$code]["VALUE"];  
			}		
		}
		if (count($arResult)) {
			return $arResult;
		} else {
			return false;
		}
	}	
		
	public static function addProfile($userId, $companyId) {
		Loader::includeModule('sale');
		
		// данные о пользователе
		$rsUser = \CUser::GetByID($userId);
		$arUser = $rsUser->Fetch();
		
		
		// данные компании
		$arCompany = self::getById($companyId);
		
		$profileId = null;
		
		$dbProfile = \CSaleOrderUserProps::GetList(
			[], [
				"USER_ID" => $userId,
				"NAME" => $arCompany["NAME_COMPANY"]
			]
		);
		
		$arProfile = $dbProfile->Fetch();
		
		if ($arProfile) {
			$profileId = $arProfile["ID"];
		}
		
		if (!$profileId) {
			$profileId = \CSaleOrderUserProps::Add([
				"NAME" => $arCompany["NAME_COMPANY"],
				"USER_ID" => $userId,
				"PERSON_TYPE_ID" => 2
			]);
		}
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 8,
			"NAME" => "Название компании",
			"VALUE" => $arCompany["NAME_COMPANY"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 9,
			"NAME" => "Юридический адрес",
			"VALUE" => $arCompany["ADDRESS"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 10,
			"NAME" => "ИНН",
			"VALUE" => $arCompany["INN"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 11,
			"NAME" => "КПП",
			"VALUE" => $arCompany["KPP"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 13,
			"NAME" => "E-Mail",
			"VALUE" => $arUser["EMAIL"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 14,
			"NAME" => "Телефон",
			"VALUE" => $arUser["PERSONAL_PHONE"]
		]);
		
		\CSaleOrderUserPropsValue::Add([
			"USER_PROPS_ID" => $profileId,
			"ORDER_PROPS_ID" => 12,
			"NAME" => "Контактное лицо",
			"VALUE" => $arUser["LAST_NAME"]." ".$arUser["NAME"]
		]);
		
	}
	
	// добавление профилей к пользователю, все его компании
	public static function addProfilesToUser($userId) {
		// найти компании к которым привязан
		$arFilter["IBLOCK_ID"] = self::$iblockId;
		$arFilter["IBLOCK_TYPE"] = self::$typeIblock;
		$arFilter["PROPERTY_STAFFS"] = $userId;
		$arSelect = [];	
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();		
			$arCompany = [];
			$arCompany["ID"] = $arFields["ID"];  
			$arResult[] = $arCompany;
		}
		if (count($arResult)) {
			foreach ($arResult as $arCompany) {
				self::addProfile($userId, $arCompany["ID"]);
			}
		} else {
			return false;
		}
	}
	
	/**
	* уволить сотрудника
	*/
	public static function removeStaff($idCompany, $userId) {
		$arStaffs = self::getUsers($idCompany);
		$newStaff = [];
		foreach ($arStaffs as $user) {
			if ($user == $userId) {
				continue;
			}
			$newStaff[] = $user;
		}
		\CIBlockElement::SetPropertyValuesEx(
			$idCompany,  
			self::$iblockId,  
			["STAFFS" => $newStaff]			
		);

	}
}
?>