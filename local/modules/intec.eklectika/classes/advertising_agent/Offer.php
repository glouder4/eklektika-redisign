<?php
namespace intec\eklectika\advertising_agent;

class Offer {
	
	public static function add($arData) {
		global $USER;
		$userId = $USER->GetID();
		
		$arData["user_id"] = $userId;
		return OfferRepository::add($arData);
	}
	
	public static function update($id, $arData) {		
		if (self::checkAccess($id)) {
			OfferRepository::update($id, $arData);
		}
	}
	
	public static function delete($id) {		
		if (self::checkAccess($id)) {			
			OfferRepository::delete($id);
		}
	}
	
	static function copyImage($url) {		
		global $USER;
		$userId = $USER->GetID();
		
		$info = new \SplFileInfo($url);
		
		// тип файла
		$typeImage = $info->getExtension();
		
		// содержание		
		if (stripos($url, "http://") !== false || stripos($url, "https://") !== false)  {
			$imgSrc = file_get_contents($url);		
		} else {
			$imgSrc = file_get_contents($_SERVER["DOCUMENT_ROOT"].$url);
		}
		// уникальное имя
		$uniqName = uniqid();
		
		// папка для копирования
		$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/".$userId."/";
		
		if (!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		
		// название файла
		$nameFile = $uniqName.".".$typeImage;
		
		// путь
		$pathImage = $dir.$nameFile;
		
		// скопированит файл
		file_put_contents($pathImage, $imgSrc);
		
		return "/upload/kp/".$userId."/".$nameFile;
	}
	
	// загрузить товары с excel файла 
	public static function loadFromFile($filePath) {
		global $USER;
		$userId = $USER->GetID();
		$arData = \intec\eklectika\excel\Controller::getArray($filePath);

		$iCount = 0; 
		$arProducts = [];
		foreach ($arData as $arCols) {
			$iCount++;
			if ($iCount < 6) {
				continue;
			}
			if (isset($arCols["C"])) {
				$arProduct = [];
				$arProduct["article"] = $arCols["C"];
				$arProduct["name"] = $arCols["D"];
				$arProduct["description"] = $arCols["E"];
				$imageUrl = $arCols["B"];
				if ($imageUrl) {
					// скопировать изображение в папку
					$arProduct["image"] = self::copyImage($imageUrl);	
				}
				$arProduct["color"] = $arCols["F"];
				$arProduct["material"] = $arCols["G"];
				$arProduct["size"] = $arCols["H"];
				
				if ($arCols["I"]) {
					$arDrawings = explode(",", $arCols["I"]);
					$arDrawingsResult = [];
					foreach ($arDrawings as $arDraw) {
						$arDrawingsResult[] = ["name" => $arDraw, "price" => null];
					}
					$arProduct["drawing"] = $arDrawingsResult;
				}
				$arProduct["edition"] = $arCols["J"];
				$arProduct["price"] = $arCols["K"];
				$arProduct["price_drawing"] = $arCols["L"];
				$arProducts[] = $arProduct;		
			}			
		}
		
		if ($arProducts) {			
			$idOffer = Offer::add(["products" => json_encode($arProducts), "contacts" => '{}']);
			return json_encode(["id" => $idOffer, "products" => $arProducts]);
		}
		
		return false;
	}
	
	// загрузить товары с корзины
	public static function loadFromBascet($aProducts) {
		global $USER;
		$userId = $USER->GetID();
		$iCount = 0; 
		$arProducts = [];
		foreach ($aProducts as $arCols) {			
			$arProduct = [];
			$arProduct["article"] = $arCols["ARTICLE"];
			$arProduct["name"] = $arCols["NAME"];
			$arProduct["description"] = $arCols["DESCRIPTION"];
			if ($arCols["PICTURE"]) {
				$arProduct["image"] =  self::copyImage($arCols["PICTURE"]);
			}
			$arProduct["color"] = $arCols["COLOR"];
			$arProduct["material"] = $arCols["MATERIAL"];
			$arProduct["size"] = $arCols["SIZE"];
			if ($arCols["DRAWING"]) {
				$arDrawings = explode(",", $arCols["DRAWING"]);
				$arDrawingsResult = [];
				foreach ($arDrawings as $arDraw) {
					$arDrawingsResult[] = ["name" => $arDraw, "price" => null];
				}
				$arProduct["drawing"] = $arDrawingsResult;
			}
			$arProduct["edition"] = (int)$arCols["QUANTITY"];
			$arProduct["price"] = $arCols["PRICE"];
			$arProduct["price_drawing"] = $arCols["PRICE"]*$arCols["QUANTITY"];
			$arProducts[] = $arProduct;
		}		
		return Offer::add(["products" => json_encode($arProducts), "contacts" => '{}']);
	}
	
	private static function checkAccess($id) {
		global $USER;
		$userId = $USER->GetID();
		
		// проверить, что предложение пренадлежит текущему пользователю
		$arFilter = [
			"PROPERTY_USER_VALUE" => $userId,
			"ID" => $id
		];
		return OfferRepository::get($arFilter);
	}
	
	public static function uploadImage($idElement, $src, $index = false) {
		if (self::checkAccess($idElement)) {
			OfferRepository::uploadImage($idElement, $src, $index);
		}
	}
	
	public static function replaceImage($idElement, $src, $index = false) {
		if (self::checkAccess($idElement)) {
			OfferRepository::replaceImage($idElement, $src, $index);
		}
	}
	
	public static function uploadLogo($idElement, $src) {
		if (self::checkAccess($idElement)) {
			OfferRepository::uploadLogo($idElement, $src);
		}
	}
	
	public static function deleteProduct($idOffer, $indexProduct) {
		if (self::checkAccess($idOffer)) {
			OfferRepository::deleteProduct($idOffer, $indexProduct);
		}
	}
	
	public static function getById($id) {
		if (self::checkAccess($id)) {
			return OfferRepository::getById($id);
		}
	}
}
?>