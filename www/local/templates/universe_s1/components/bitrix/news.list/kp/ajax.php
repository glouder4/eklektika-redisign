<?php
use intec\eklectika\advertising_agent\Offer;
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule("intec.eklectika");
CModule::IncludeModule("iblock");

if (isset($_REQUEST["action"])) {
	switch($_REQUEST["action"]) {
		
		// импортировать коммерческое предложение
		case "import_from_file":
			if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
				$file = $_FILES["file"];
				$uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/";				
				if (move_uploaded_file($file["tmp_name"], $uploadDirectory . $file["name"])) {
					$json = Offer::loadFromFile($uploadDirectory . $file["name"]);
					if ($json) {
						echo $json;
					} else {
						echo "error";
					}
					unlink($uploadDirectory . $file["name"]);
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		break;
		
		// сохранить коммерческое предложение
		case "save": 
			$arData = $_POST;
			if ($arData["id"] != 'null') {
				$id = $arData["id"];
				Offer::update($id, $arData);
			} else {				
				$id = Offer::add($arData);
			}
			echo $id;
		break;
		
		// создать pdf
		case "create_pdf":
			$arData = Offer::getById($_GET["id"]);
			ob_start();
			include "template_pdf.php";
			$html = ob_get_clean();
			//echo $html;
			intec\eklectika\pdf\Controller::getPdf($html);
			
		break;
		
		// удалить предложение
		case "delete_offer":
			$arData = $_POST;
			if ($arData["id"]) {
				Offer::delete($arData["id"]);
			}
		break;
		
		case "delete_product": 
			$arData = $_POST;
			if ($_POST["id_offer"] &&  $_POST["index_product"]) {
				Offer::deleteProduct($_POST["id_offer"], $_POST["index_product"]);
			}			
		break;
		
		// загрузить изображение
		case "upload_image":
			if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
				global $USER;
				$userId = $USER->GetID();
				$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/".$userId."/";
				$file = $_FILES["file"];
				$uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/";		
				$info = new \SplFileInfo($file["name"]);				
				$typeImage = $info->getExtension();	
				$uniqName = uniqid();
				$filePath = $dir.$uniqName.".".$typeImage;		
				$relPath = "/upload/kp/".$userId."/".$uniqName.".".$typeImage;
				if (move_uploaded_file($file["tmp_name"], $filePath)) {
					$filePath = $uploadDirectory . $file["name"];
					Offer::uploadImage($_POST["idOffer"], $relPath, $_POST["indexProduct"]);	
					echo $relPath;
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		break;
		
		//заменить изображение
		case "replace_image":
			if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
				global $USER;
				$userId = $USER->GetID();
				$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/".$userId."/";
				$file = $_FILES["file"];
				$uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/";		
				$info = new \SplFileInfo($file["name"]);				
				$typeImage = $info->getExtension();	
				$uniqName = uniqid();
				$filePath = $dir.$uniqName.".".$typeImage;		
				$relPath = "/upload/kp/".$userId."/".$uniqName.".".$typeImage;
				if (move_uploaded_file($file["tmp_name"], $filePath)) {
					$filePath = $uploadDirectory . $file["name"];
					Offer::replaceImage($_POST["idOffer"], $relPath, $_POST["indexProduct"]);	
					echo $relPath;
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		break;
		
		
		case "parse_url":
			$url = $_POST["url"];
			$data = intec\eklectika\Parser::parseByLink($url);
			if ($data) {
				$arData = json_decode($data, true);				
				$image = $arData["image"];
				$newImage = Offer::copyImage($image);
				$arData["image"] = $newImage;
				if (!isset($arData["drawing"])) {
					$arData["drawing"] = [["name" => null, "price" => null]];
				}
				$data = json_encode($arData);
				echo $data;
			} else {
				echo "error";
			}
		break;
		
		// загрузить логотип
		case "upload_logo":
			if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
				global $USER;
				$userId = $USER->GetID();
				$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/".$userId."/";
				$file = $_FILES["file"];
				$uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/";		
				$info = new \SplFileInfo($file["name"]);				
				$typeImage = $info->getExtension();	
				$uniqName = uniqid();
				$filePath = $dir.$uniqName.".".$typeImage;		
				$relPath = "/upload/kp/".$userId."/".$uniqName.".".$typeImage;
				if (move_uploaded_file($file["tmp_name"], $filePath)) {
					$filePath = $uploadDirectory . $file["name"];
					Offer::uploadLogo($_POST["idOffer"], $relPath);	
					echo $relPath;
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		break;
		
		// заменить логотип
		case "replace_logo":
			if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
				global $USER;
				$userId = $USER->GetID();
				$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/".$userId."/";
				$file = $_FILES["file"];
				$uploadDirectory = $_SERVER["DOCUMENT_ROOT"]."/upload/kp/";		
				$info = new \SplFileInfo($file["name"]);				
				$typeImage = $info->getExtension();	
				$uniqName = uniqid();
				$filePath = $dir.$uniqName.".".$typeImage;		
				$relPath = "/upload/kp/".$userId."/".$uniqName.".".$typeImage;
				if (move_uploaded_file($file["tmp_name"], $filePath)) {
					$filePath = $uploadDirectory . $file["name"];
					Offer::replaceLogo($_POST["idOffer"], $relPath, $_POST["idOffer"]);	
					echo $relPath;
				} else {
					echo "error";
				}
			} else {
				echo "error";
			}
		break;
	}
}
?>