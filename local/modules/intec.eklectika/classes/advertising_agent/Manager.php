<?php
namespace intec\eklectika\advertising_agent;

class Manager {
	private static $iblockId = "53";
	private static $typeIblock = 'personal';
	
	/**
	* ["B24_ID", "NAME", "SECOND_NAME", "EMAIL", "PHONE"]
	* Добавить менеджера
	*/
	public static function add($arData) {
		$el = new \CIBlockElement;
		$arProps = [];

		$arProps["PHONE"] = [
			"VALUE" => $arData["PHONE"]
		];
		$arProps["EMAIL"] = [
			"VALUE" => $arData["EMAIL"]
		];
		
		$arLoadProductArray = [           
			"IBLOCK_SECTION_ID" => false,
			"IBLOCK_TYPE" => self::$typeIblock,
			"IBLOCK_ID" => self::$iblockId,
			"PROPERTY_VALUES" => $arProps,
			"NAME" => $arData["LAST_NAME"]." ".$arData["NAME"],
			"ACTIVE" => "Y",
			"XML_ID" => $arData["B24_ID"]
		];
		if($managerId = $el->Add($arLoadProductArray)) {
			return $managerId;
		} else {
			return false;
		}		
	}

	/**
	* Обновить менеджера
	*/
	public static function update($managerID, $arData) {
		$arFields = [];
		if ($arData["NAME"]) {
			$arFields["NAME"] = $arData["NAME"] . " " . $arData["LAST_NAME"]; 
			$user = new \CIBlockElement;
			$user->Update($managerID, $arFields);
		}
		if ($arData["EMAIL"]) {
			self::updateProps($managerID, "EMAIL", $arData["EMAIL"]);
		}
		if ($arData["PHONE"]) {
			self::updateProps($managerID, "PHONE", $arData["PHONE"]);
		}
	}

	public static function updateProps($managerID, $PROPERTY_CODE, $PROPERTY_VALUE){
		if( \CModule::IncludeModule('iblock')) {
			\CIBlockElement::SetPropertyValues($managerID, self::$iblockId, $PROPERTY_VALUE, $PROPERTY_CODE);
		}
	}
	
	/**
	* Найти менеджера по $id
	*/
	public static function findById($id) {
		$res = \CIBlockElement::GetByID($id);
		$arData = [];
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();
			$arData["B24_ID"] = $arFields["XML_ID"];
			$arData["NAME"] = $arFields["NAME"];
			$arData["EMAIL"] = $arProps["EMAIL"]["VALUE"];
			$arData["PHONE"] = $arProps["PHONE"]["VALUE"];
			return $arData;		
		}
		return false;
		
	}
	
	/**
	* Найти менеджера по внешнему коду (XML_ID)
	*/
	public static function findByIdB24($id) {
		$arFilter = [
			"IBLOCK_ID" => self::$iblockId,
			"XML_ID" => $id,
			"IBLOCK_TYPE_ID" => self::$typeIblock
		];
		$res = \CIBlockElement::GetList([], $arFilter, false, false, false);
		$arData = [];
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();
			$arData["ID"] = $arFields["ID"];
			$arData["B24_ID"] = $arFields["XML_ID"];
			$arData["NAME"] = $arFields["NAME"];
			$arData["EMAIL"] = $arProps["EMAIL"]["VALUE"];
			$arData["PHONE"] = $arProps["PHONE"]["VALUE"];
			return $arData;
		}
		return false;
	}

		
}
?>