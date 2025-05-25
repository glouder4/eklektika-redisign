<?php

namespace intec\eklectika;

class BasketRepository {
    private static $typeIblock = '1c_catalog';
    private static $iblockId = 49;

    /**
     * ["user_id", "products"] 
     */
    public static function add($params) {
        $el = new \CIBlockElement;
        $PROP = [];
        $PROP["USER"] = $params["user_id"];  
        $arProductsId = [];
		$arProductsQuantity = [];
		
		$PROP["DATA"] =  Array("VALUE" => Array ("TEXT" => json_encode($params["products"]), "TYPE" => "text"));
		$arLoadProductArray = array(           
			"IBLOCK_SECTION_ID" => false,
			"IBLOCK_TYPE" => self::$typeIblock,
			"IBLOCK_ID" => self::$iblockId,
			"PROPERTY_VALUES" => $PROP,
			"NAME" => "Корзина для ".$params["user_id"],
			"ACTIVE" => "Y",   
		);
		return $el->Add($arLoadProductArray);
	}

    /**
    *
    */
    public static function delete($id) {
		if ($id) {
			\CIBlockElement::Delete($id);
		}
    }

    public static function getId($arFilter) {
		$arFilter["IBLOCK_ID"] = self::$iblockId;
		$arFilter["IBLOCK_TYPE"] = self::$typeIblock;
		$arSelect = Array("ID");	
		$res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		$arResult = [];
		while($ob = $res->GetNextElement()){
		   $arFields = $ob->GetFields();
		   $arResult[] = $arFields["ID"];
		}
		return $arResult;
    }

    public static function get($arFilter) {
    	$arFilter["IBLOCK_ID"] = self::$iblockId;
		$arFilter["IBLOCK_TYPE"] = self::$typeIblock;
		$arSelect = [];
		$res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		$arResult = [];
		while($ob = $res->GetNextElement()){
		   $arFields = $ob->GetFields();
		   $arProperties = $ob->GetProperties();
		   $arResult[$arFields["ID"]]["FIELDS"] = $arFields;
		   $arResult[$arFields["ID"]]["PROPERTIES"] = $arProperties;
		}
		return $arResult;
    }
}
