<?php
namespace intec\eklectika;

class Parser {
	
	public static function parseByLink($url) {
		$parse = parse_url($url);
		$host = $parse["host"];
		
		if ($host == str_replace(":443","", $_SERVER["HTTP_HOST"])) {
			return self::getDataFromEklectika($url);
		}
		
		if ($host == "gifts.ru") {
			return self::getDataFromGifts($url);
		}
		
		if ($host == "happygifts.ru") {
			return self::getDataFromHappyGifts($url);
		}
		
		if ($host == "www.oasiscatalog.com") {
			return self::getDataFromOasisCatalog($url);
		}
		
		if ($host == "portobello.ru") {
			return self::getDataFromHappyGifts($url);
		}
		
		if ($host == "www.oceangifts.ru") {
			return self::getDataFromOceanGifts($url);
		}
	}
	
	public static function getDataFromEklectika($url) {
		return file_get_contents($url."&parse=Y");
	}
	
	public static function getDataFromGifts($url) {
		$content = file_get_contents($url);
		
		$arProduct["edition"] = 1;
		
		preg_match("/<div>Артикул (.*?)<\/div>/", $content, $matches);
		$arProduct["article"] = $matches[1];
		
		preg_match("/h1 itemprop=\"name\">(.*?)<\/h1>/", $content, $matches);
		$arProduct["name"] = $matches[1];
		
		preg_match("/data-price=\"(.*?)\"/", $content, $matches);
		$arProduct["price"] = $matches[1];
		
		preg_match("/data-hd=\"(.*?)\"/", $content, $matches);
		$arProduct["image"] = str_replace("//","https://",$matches[1]);
		
		preg_match("/id=\"marketDescr\">(.*?)<\/div/", $content, $matches);
		if ($matches[1]) {
			$arProduct["description"] = strip_tags($matches[1]);
		} else {
			$arProduct["description"] = '';
		}
		
		preg_match("/<div class=\"itm-opts-label\">Размеры<\/div>(.*?)<\/li>/", $content, $matches);
		if (isset($matches[1])) {			
			$arProduct["size"] = $matches[1];
		} else {
			$arProduct["size"] = "";
		}
		preg_match("/<div class=\"itm-opts-label\">Материал<\/div>(.*?)<\/li>/", $content, $matches);
		if (isset($matches[1])) {			
			$arProduct["material"] = $matches[1];
		} else {
			$arProduct["material"] = "";
		}
		$arProduct["drawing"] = [[
			"name"=>"", 
			"price" => ""
		]];
		return json_encode($arProduct);
	}
	
	public static function getDataFromHappyGifts($url) {
		$content = file_get_contents($url);
		
		$arProduct["edition"] = 1;
		
		preg_match("/<span class=\"articul\" data-offer=\".*?\">(.*?)<\/span>/ms", $content, $matches);
		$arProduct["article"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/<h1>(.*?)<\/h1>/", $content, $matches);
		$arProduct["name"] = $matches[1];
		
		$matches = null;
		preg_match("/<div class=\"detail-text\">(.*?)<\/div>/ms", $content, $matches);
		$arProduct["description"] = trim(strip_tags($matches[1]));
		
		$matches = null;
		preg_match("/data-medium=\"(.*?)\"/ms", $content, $matches);
		$arProduct["image"] = "https://happygifts.ru".$matches[1];
		
		$matches = null;
		preg_match("/<p><b class=\"d-block font-weight-bold\">Цвет<\/b>(.*?)<\/p>/", $content, $matches);
		$arProduct["color"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/<p><b class=\"d-block font-weight-bold\">Размер<\/b>(.*?)<\/p>/", $content, $matches);
		$arProduct["size"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/<p><b class=\"d-block font-weight-bold\">Материал<\/b>(.*?)<\/p>/", $content, $matches);
		$arProduct["material"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/<span class=\"vu-price\".*?>(.*?)<\/span>/ms", $content, $matches);
		$arProduct["price"] = trim($matches[1]);
		
		return json_encode($arProduct);
	}
	
	public static function getDataFromOasisCatalog($url) {
		$content = file_get_contents($url);
		
		$arProduct['edition'] = 1;
		
		preg_match("/<h1 class=\"product-heading__title\">(.*?)<\/h1>/ms", $content, $matches);
		$arProduct["name"] = trim($matches[1]);
		
		preg_match("/<div class=\"product-heading__article\">(.*?)<\/div>/ms", $content, $matches);
		$article = trim($matches[1]);
		$article = str_replace(["(","арт.",")"], "", $article);
		$arProduct["article"] = trim($article);
		
		preg_match("/<div class=\"product__description\">(.*?)<\/div>/ms", $content, $matches);
		$arProduct["description"] = trim(strip_tags($matches[1]));
		
		preg_match("/<meta itemprop=\"price\" content=\"(.*?)\">/ms", $content, $matches);
		$arProduct["price"] = $matches[1];
		
		preg_match("/itemprop=\"image\".*?content=\"(.*?)\">/ms", $content, $matches);
		$arProduct["image"] = $matches[1];
		
		preg_match("/<div class=\"product-params__item-title\">.*?Цвет товара.*?<\/div>.*?<div class=\"product-params__item-data\">(.*?)<\/div>/ms", $content, $matches);
		$arProduct["color"] = trim($matches[1]);
		
		preg_match("/<div class=\"product-params__item-title\">.*?Материал товара.*?<\/div>.*?<div class=\"product-params__item-data\">(.*?)<\/div>/ms", $content, $matches);
		$arProduct["material"] = trim($matches[1]);
		
		preg_match("/<div class=\"product-params__item-title\">.*?Размер товара \(см\).*?<\/div>.*?<div class=\"product-params__item-data\">(.*?)<\/div>/ms", $content, $matches);
		$arProduct["size"] = trim($matches[1]);
		
		return json_encode($arProduct);
	}
	
	public static function getDataFromPortobello($url) {
	
	}
	
	public static function getDataFromOceanGifts($url) {
		$content = file_get_contents($url);
		
		$arProduct['edition'] = 1;
		
		preg_match("/<h1 class=\"iproduct__title\">(.*?)<\/h1>/ms", $content, $matches);
		$arProduct["name"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/<div class=\"iproduct__artikul\">Артикул (.*?)<\/div>/ms", $content, $matches);
		$arProduct["article"] = trim($matches[1]);
		
		$matches = null;
		preg_match("/itemprop=\"description\">(.*?)<\/div>/ms", $content, $matches);
		if ($matches) {
			$arProduct["description"] = trim(strip_tags($matches[1]));
		}
		
		$matches = null;
		preg_match("/<b>Материал<\/b>: <span.*?class=\"iproduct__param-value\">(.*?)<\/span>/ms",$content, $matches);
		if ($matches) {
			$arProduct["material"] = trim(strip_tags($matches[1]));
		}
		
		$matches = null;
		preg_match("/<b>Размеры изделия<\/b>: <span.*?class=\"iproduct__param-value\">(.*?)<\/span>/ms",$content, $matches);
		if ($matches) {
			$arProduct["size"] = trim(strip_tags($matches[1]));
		}
		
		$matches = null;
		preg_match("/data-price=\"(.*?)\"/", $content, $matches);
		if ($matches) {
			$arProduct["price"] = trim(strip_tags($matches[1]));
		}
		
		$matches = null;
		preg_match("/data-thumb=\"(.*?)\"/", $content, $matches);
		if ($matches) {
			$arProduct["image"] = "https://www.oceangifts.ru".$matches[1];
		}
		
		return json_encode($arProduct);
		
	}
}