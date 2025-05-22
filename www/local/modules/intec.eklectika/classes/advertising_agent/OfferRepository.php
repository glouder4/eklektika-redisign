<?php
namespace intec\eklectika\advertising_agent;

class OfferRepository {
    private static $typeIblock = 'personal';
    private static $iblockId = 51;

    /**
     * ["user_id", "products", "contacts"] 
     */
    public static function add($params) {
        $el = new \CIBlockElement;
        $PROP = [];
        $PROP["USER"] = $params["user_id"];  
        $arProductsId = [];
		$arProductsQuantity = [];		
		
		$PROP["DATA"] =  [
			"VALUE" => [
				"TEXT" => $params["products"], 
				"TYPE" => "text"
			]
		];
		
		$PROP["CONTACTS"] = [
			"VALUE" => [
				"TEXT" => $params["contacts"],
				"TYPE" => "text"
			]
		];
		
		$arLoadProductArray = [           
			"IBLOCK_SECTION_ID" => false,
			"IBLOCK_TYPE" => self::$typeIblock,
			"IBLOCK_ID" => self::$iblockId,
			"PROPERTY_VALUES" => $PROP,
			"NAME" => "Коммерческое предложение",
			"ACTIVE" => "Y" 
		];
		if($productId = $el->Add($arLoadProductArray)) {
			return $productId;
		} else {
			return false;
		}		
    }
	
	/**
	* 
	*/
	public static function update($id, $params) {		
		if ($params["products"]) {
			\CIBlockElement::SetPropertyValuesEx(
				$id,  
				self::$iblockId,  
				["DATA" => $params["products"]]			
			);
		}
		if ($params["contacts"]) {
			\CIBlockElement::SetPropertyValuesEx(
				$id,  
				self::$iblockId,  
				["CONTACTS" => $params["contacts"]]			
			);
		}
	}
	
	
	/**
    * удалить предложение
    */
    public static function delete($id) {
		if ($id) {			
			self::deleteImages($id);
			\CIBlockElement::Delete($id);
		}
    }
	
	/**
	*
	*/	
    public static function getId($arFilter) {
		$arFilter["IBLOCK_ID"] = self::$iblockId;
		$arFilter["IBLOCK_TYPE"] = self::$typeIblock;
		$arSelect = ["ID"];	
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while ($ob = $res->GetNextElement()) {
		   $arFields = $ob->GetFields();
		   $arResult[] = $arFields["ID"];
		}
		return $arResult;
    }

	
	/**
	*
	*/	
    public static function get($arFilter) {
    	$arFilter["IBLOCK_ID"] = self::$iblockId;
		$arFilter["IBLOCK_TYPE"] = self::$typeIblock;
		$arSelect = [];
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$arResult = [];
		while($ob = $res->GetNextElement()){		
		   $arFields = $ob->GetFields();
		   $arProperties = $ob->GetProperties();
		   $arResult[$arFields["ID"]]["FIELDS"] = $arFields;
		   $arResult[$arFields["ID"]]["PROPERTIES"] = $arProperties;
		}
		if (count($arResult)) {
			return $arResult;
		} else {
			return false;
		}
    }
	
	public static function getById($id) {		
		$rsOffer = \CIBlockElement::GetById($id);
		$ob = $rsOffer->GetNextElement();
	
		$arProps = $ob->GetProperties();
		$arProducts = json_decode($arProps["DATA"]["~VALUE"]["TEXT"], true);	
		$arContacts = json_decode($arProps["CONTACTS"]["~VALUE"]["TEXT"], true);
		return ["PRODUCTS" => $arProducts, "CONTACTS" => $arContacts];
	}
	
	// загрузить изображение товару
	public static function uploadImage($idOffer, $src, $indexProduct) {
		$arProducts = self::getProducts($idOffer);
		$arProducts[$indexProduct]["image"] = $src;
		self::saveProducts($idOffer, $arProducts);
	}
	
	// заменить изображение у товара
	public static function replaceImage($idOffer, $newSrc, $indexProduct) {
		$arProducts = self::getProducts($idOffer);
		
		// удалить старое изображение
		unlink($_SERVER["DOCUMENT_ROOT"].$arProducts[$indexProduct]["image"]);
		
		// добавить новое изображение
		$arProducts[$indexProduct]["image"] = $newSrc;
		
		// сохранить изменения
		self::saveProducts($idOffer, $arProducts);
	}
	
	// загрузить логотип компании
	public static function uploadLogo($idOffer, $src) {
		$arContacts = self::getContacts($idOffer);
		$arContacts["logo"] = $src;
		self::saveContacts($idOffer, $arContacts);
	}
	
	// заменить логотип компании
	public static function replaceLogo($idOffer, $src) {
		$arContacts = self::getContacts($idOffer);
		
		// удалить старое изображение
		unlink($_SERVER["DOCUMENT_ROOT"].$arContacts["logo"]);
		
		// добавить новое изображение
		$arContacts["logo"] = $newSrc;
		
		// сохранить изменения
		self::saveContacts($idOffer, $arContacts);
	}
	
	/**
	* удалить товар по индексу
	*/
	public static function deleteProduct($idOffer, $indexProduct) {
		$arProducts = self::getProducts($idOffer);
		
		// удалить старое изображение
		unlink($_SERVER["DOCUMENT_ROOT"].$arProducts[$indexProduct]["image"]);
		
		// удалить товар из массива
		unset($arProducts[$indexProduct]);
		
		// сохранить изменения
		self::saveProducts($idOffer, $arProducts);
	}
	
	/**
	* получить товары из предложения в виде массива
	*/
	private static function getProducts($idOffer) {
		$rs = \CIBlockElement::GetById($idOffer);
		$elem = $rs->GetNextElement();
		$props = $elem->GetProperties();
		$arProducts = json_decode($props["DATA"]["~VALUE"]["TEXT"], true);	
		return $arProducts;
	}
	
	/**
	* получить контакты	из предложения в виде массива
	*/
	private static function getContacts($idOffer) {
		$rs = \CIBlockElement::GetById($idOffer);
		$elem = $rs->GetNextElement();
		$props = $elem->GetProperties();
		$arProducts = json_decode($props["CONTACTS"]["~VALUE"]["TEXT"], true);	
		return $arProducts;
	}
	
	/**
	* сохранить товары в предложении
	*/
	private static function saveProducts($idOffer, $arProducts) {
		self::update($idOffer, ["products" => json_encode($arProducts)]);
	}
	
	/**
	* сохранить контакты в предложении
	*/
	private static function saveContacts($idOffer, $arContacts) {
		self::update($idOffer, ["contacts" => json_encode($arContacts)]);
	}
	
	/**
	* удалить все изображения в предложении
	*/
	private static function deleteImages($idOffer) {
		
		$arProducts = self::getProducts($idOffer);	
		foreach ($arProducts as $arProduct) {
			if ($arProduct["image"]) {
				unlink($_SERVER["DOCUMENT_ROOT"].$arProduct["image"]);
			}
		}
	}
    
}
?>