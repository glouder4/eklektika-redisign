<?php
namespace intec\eklectika;

use intec\eklectika\advertising_agent\Offer;
use Bitrix\Sale\Fuser;
use \Bitrix\Main\Loader;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xls;
use \PhpOffice\PhpSpreadsheet\Worksheet\Drawing; 
use \PhpOffice\PhpSpreadsheet\IOFactory;

class SavedBasket {
	
    // сохранить корзину для пользователя
    public static function save() {
		Loader::includeModule("sale");
		Loader::includeModule("catalog");
		global $USER;
		$userId = Fuser::getId();	
		if ($USER->GetID()) {
			$params = [];		
			$params["user_id"] = $USER->GetID();
			// получить товары в корзине
			$basketStorage = \Bitrix\Sale\Basket\Storage::getInstance($userId, SITE_ID);
			$basket = $basketStorage->getBasket();
			$products = [];
			foreach ($basket as $basket_item) {
				$product = $basket_item->getFieldValues();		    
				if ($product["DELAY"] == "N") {
					$products[$product["PRODUCT_ID"]] = [
						"PRODUCT_ID" => $product["PRODUCT_ID"],
						"QUANTITY" => $product["QUANTITY"],
						"PRICE" => $product["PRICE"],
						"PRICE_TYPE_ID" => $product["PRICE_TYPE_ID"],
						"CURRENCY" => $product["CURRENCY"],
						"PRODUCT_XML_ID" => $product["PRODUCT_XML_ID"],
						"PRODUCT_PRICE_ID" => $product["PRODUCT_PRICE_ID"],
						"NAME" => $product["NAME"]
					];
				}           
			}    
			if (count($products)) { 
					$params["products"] = $products; 
					BasketRepository::add($params);
			}
			return true;
		}
		return false;
    }
	
	public static function saveExcel() {
		$arProducts = self::getProducts();
		if ($arProducts) {		
			// @url https://gist.github.com/dzhuryn/43b3e932d57735a2569284678eef2039 - шпаргалка по excel
			require_once __DIR__."/excel/vendor/autoload.php";			
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getColumnDimension('A')->setWidth('108', 'px');
			$sheet->getColumnDimension('B')->setWidth('104', 'px');
			$sheet->getColumnDimension('C')->setWidth('278', 'px');
			$sheet->getColumnDimension('D')->setWidth('106', 'px');
			$sheet->getColumnDimension('E')->setWidth('104', 'px');
			$sheet->getColumnDimension('F')->setWidth('104', 'px');
			$sheet->getColumnDimension('G')->setWidth('104', 'px');
			$sheet->getColumnDimension('H')->setWidth('104', 'px');
			$sheet->getColumnDimension('I')->setWidth('104', 'px');
			$sheet->getColumnDimension('J')->setWidth('104', 'px');
			$sheet->getColumnDimension('K')->setWidth('104', 'px');
			$sheet->getColumnDimension('L')->setWidth('104', 'px');
			$sheet->getColumnDimension('M')->setWidth('104', 'px');
			$sheet->getPageMargins()
				->setLeft(0.2)
				->setRight(0.2)
				->setTop(0.2)
				->setBottom(0.2);
			$sheet->setCellValue('A2', 'Коммерческое предложение');
			//Сделать жырным
			$sheet->getStyle('A2')->getFont()->setBold(true);
			
			//задание шрифта
			$sheet->getCell('A2')->getStyle()
				->getFont()
				->setName('Calibri')
				->setSize(16);
			
			$sheet->setCellValue('A5', 'Фото');
			$sheet->setCellValue('B5', 'Фото, ссылка');
			$sheet->setCellValue('C5', 'Артикул');
			$sheet->setCellValue('D5', 'Название');
			$sheet->setCellValue('E5', 'Описание');
			$sheet->setCellValue('F5', 'Цвет');
			$sheet->setCellValue('G5', 'Материал');
			$sheet->setCellValue('H5', 'Размер');
			$sheet->setCellValue('I5', 'Метод нанесения');
			$sheet->setCellValue('J5', 'Тираж');
			$sheet->setCellValue('K5', 'Цена за шт., руб.');
			$sheet->setCellValue('L5', 'Стоимость тиража, руб.');
			$sheet->setCellValue('M5', 'ИТОГ, руб.');
			$cellNum = 6; 
			$sheet->getRowDimension(5)->setRowHeight(50);
			$sheet->getStyle('A5:M5')
				->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('b0c4de');
			$sheet->getCell('I5')->getStyle()->getAlignment()->setWrapText(true);
			$sheet->getCell('K5')->getStyle()->getAlignment()->setWrapText(true);
			$sheet->getCell('L5')->getStyle()->getAlignment()->setWrapText(true);
			foreach ($arProducts as $arProduct) {
				$drawing = new Drawing();
				$drawing->setName($arProduct["NAME"]);
				$drawing->setDescription($arProduct["NAME"]);
				$drawing->setDescription($arProduct["NAME"]);
				$drawing->setPath($_SERVER['DOCUMENT_ROOT'].$arProduct["PICTURE"]);
				$drawing->setCoordinates('A'. $cellNum);
				$drawing->setWorksheet($sheet);
				$drawing->setWidth(108);
				$sheet->setCellValue('B'.$cellNum, "https://".$_SERVER['SERVER_NAME'].$arProduct["PICTURE"]);
				$sheet->setCellValue('C'.$cellNum, $arProduct["ARTICLE"]);
				$sheet->setCellValue('D'.$cellNum, $arProduct["NAME"]);
				$sheet->setCellValue('E'.$cellNum, $arProduct["DESCRIPTION"]);
				$sheet->setCellValue('F'.$cellNum, $arProduct["COLOR"]);
				$sheet->setCellValue('G'.$cellNum, $arProduct["MATERIAL"]);
				$sheet->setCellValue('H'.$cellNum, $arProduct["SIZE"]);
				$sheet->setCellValue('I'.$cellNum, $arProduct["DRAWING"]);
				$sheet->setCellValue('J'.$cellNum, $arProduct["QUANTITY"]);
				$sheet->setCellValue('K'.$cellNum, $arProduct["PRICE"]);
				$sheet->setCellValue('L'.$cellNum, '=K'.$cellNum.'*J'.$cellNum);
				$sheet->setCellValue('M'.$cellNum, '');
				$sheet->getRowDimension($cellNum)->setRowHeight(132);
				$sheet->getCell('B'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('C'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('D'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('E'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('F'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('G'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('H'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('I'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('I'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('J'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('K'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('L'.$cellNum)->getStyle()->getAlignment()->setWrapText(true);
				$cellNum++;				
			}
			$sheet->getStyle('A5:M5')->getFont()->setBold(true);
			$sheet->getStyle('A6:D'.($cellNum-1))->getFont()->setBold(true);
			$sheet->getStyle('A:M')->getAlignment()->setHorizontal('center');
			$sheet->getStyle('A:M')->getAlignment()->setVertical('center');
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => ['argb' => '000000'],
					],
				],
			];
			$sheet->getCell('A2')->getStyle()
			 ->getAlignment()
			 ->setHorizontal('center');

			$sheet->getStyle('A5:M'.($cellNum - 1))->applyFromArray($styleArray);
			$sheet->setCellValue('K'.$cellNum, 'Итого');
			$sheet->setCellValue('L'.$cellNum, "=SUM(L6:L".($cellNum - 1).")");
			$sheet->getStyle('K'.$cellNum.':M'.$cellNum)->applyFromArray($styleArray);
			// foreach(range('C','M') as $columnID) {
				// $sheet->getColumnDimension($columnID)
					// ->setAutoSize(true);
			// }
			//Отдать файл на скачивание в браузер
			$oWriter = IOFactory::createWriter($spreadsheet, 'Xls');
			header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			header("Content-Disposition: attachment;filename=\"kommercheskoe_predlozhenie.xls\"");
			header("Cache-Control: max-age=0");
			$oWriter->save('php://output');
		}
	}
	
	// получить товары в корзине
	public static function getProducts() {
		Loader::includeModule("sale");
		Loader::includeModule("catalog");
		global $USER;
		$userId = Fuser::getId();	
		if ($USER->GetID()) {
			$params = [];
			$params["user_id"] = $USER->GetID();
			$basketStorage = \Bitrix\Sale\Basket\Storage::getInstance($userId, SITE_ID);
			$basket = $basketStorage->getBasket();
			$products = [];
			foreach ($basket as $basket_item) {
				$product = $basket_item->getFieldValues();
				if ($product["DELAY"] == "N") {					
					$arProduct =  [
						"PRODUCT_ID" => $product["PRODUCT_ID"],
						"QUANTITY" => $product["QUANTITY"],
						"PRICE" => $product["PRICE"],
						"PRICE_TYPE_ID" => $product["PRICE_TYPE_ID"],
						"CURRENCY" => $product["CURRENCY"],
						"PRODUCT_XML_ID" => $product["PRODUCT_XML_ID"],
						"PRODUCT_PRICE_ID" => $product["PRODUCT_PRICE_ID"],
						"NAME" => $product["NAME"]
					];
					
					// получить информацию о товаре
					$rsIblockElement = \CIblockElement::GetById($product["PRODUCT_ID"]);
					$oIblockElement = $rsIblockElement->GetNextElement();
					$arFields = $oIblockElement->GetFields();
					$arProperties = $oIblockElement->GetProperties();
					
					// изображение
					$arProduct["PICTURE"] = \CFile::GetPath($arFields["PREVIEW_PICTURE"]);
					
					// артикул
					$arProduct["ARTICLE"] = $arProperties["CML2_ARTICLE"]["VALUE"];
					
					// цвет
					$arProduct["COLOR"] = $arProperties["TSVET"]["VALUE"];
					
					// размер
					$arProduct["SIZE"] = $arProperties["RAZMER"]["VALUE"] != "&lt;&gt;" ? $arProperties["RAZMER"]["VALUE"] : "";
					if (!$arProduct["SIZE"]) {
						$arProduct["SIZE"] = $arProperties["RAZMER_ODEZHDY"]["VALUE"] != "&lt;&gt;" ? $arProperties["RAZMER_ODEZHDY"]["VALUE"] : "";
					}
					
					// id основного товара
					$idMainProduct = $arProperties["CML2_LINK"]["VALUE"];
					
					// получить основной товар
					$rsMainProduct = \CIblockElement::GetById($idMainProduct);
					$oMainElement = $rsMainProduct->GetNextElement();
					$arFieldsMainProduct = $oMainElement->GetFields();
					$arPropertiesMainProduct = $oMainElement->GetProperties();
					
					// материал
					$arProduct["MATERIAL"] = $arPropertiesMainProduct["MATERIAL"]["VALUE"];
					
					// описание
					$arProduct["DESCRIPTION"] = strip_tags($arFieldsMainProduct["~DETAIL_TEXT"]);
					
					// виды нанесения
					$arProduct["DRAWING"] = is_array($arPropertiesMainProduct["APPLICATION_TYPES"]["VALUE"]) ? implode(", ", $arPropertiesMainProduct["APPLICATION_TYPES"]["VALUE"]) : "" ;					
					
					$products[$product["PRODUCT_ID"]] = $arProduct;
				}
				
			}   
			return $products;
		}
		return false;
	}
	
    // восстановить корзину
    public static function restore($idElement, $saveOld) {
		Loader::includeModule("sale");
		Loader::includeModule("catalog");
		if ($saveOld) {
			self::save();
		}
		global $USER;
		$userId = $USER->GetID();
		$arFilter = ["PROPERTY_USER" => $userId, "ID" => $idElement];
		
		$arBasket = BasketRepository::get($arFilter);
		$arBasket = $arBasket[$idElement];
		$arBasketData = json_decode($arBasket["PROPERTIES"]["DATA"]["~VALUE"]["TEXT"],true);	
		$arData = [];
		foreach ($arBasketData as $elem) {
			$arData[] = ["id" => $elem["PRODUCT_ID"], "quantity" => $elem["QUANTITY"], "price" => "PRICE_TYPE_ID"];
		}
		BasketActions::actionClear();
		BasketActions::actionAddMultiple($arData);
	
    }

    public static function get() {
		global $USER;
		$userId = $USER->GetID();
		$arFilter = ["PROPERTY_USER" => $userId];
		
		return BasketRepository::getId($arFilter);
	}

	public static function delete($id) {
		global $USER;
		$userId = $USER->GetID();
		$arFilter = ["PROPERTY_USER" => $userId,"ID" => $id];
		
		$arBasket = BasketRepository::getId($arFilter);
		if ($arBasket) {
			BasketRepository::delete($id);
		}
    }
	
	public static function createKp($idElement) {
		Loader::includeModule("catalog");
		global $USER;
		$userId = $USER->GetID();
		if ($USER->GetID()) {
			$arFilter = ["PROPERTY_USER" => $userId, "ID" => $idElement];
			$arBasket = BasketRepository::get($arFilter);
			foreach($arBasket as $arBasket) {
				$dataProducts = json_decode($arBasket["PROPERTIES"]["DATA"]["~VALUE"]["TEXT"],true);
				$products = [];
				foreach ($dataProducts as $dataProduct) {
					$arProduct =  [
						"PRODUCT_ID" => $dataProduct["PRODUCT_ID"],
						"QUANTITY" => $dataProduct["QUANTITY"],
						"PRICE" => $dataProduct["PRICE"],
						"PRICE_TYPE_ID" => $dataProduct["PRODUCT_PRICE_ID"],
						"CURRENCY" => $dataProduct["CURRENCY"],
						"PRODUCT_XML_ID" => $dataProduct["PRODUCT_XML_ID"],
						"PRODUCT_PRICE_ID" => $dataProduct["PRODUCT_PRICE_ID"],
						"NAME" => $dataProduct["NAME"]
					];
					
					// получить информацию о товаре
					$rsIblockElement = \CIblockElement::GetById($dataProduct["PRODUCT_ID"]);
					$oIblockElement = $rsIblockElement->GetNextElement();
					$arFields = $oIblockElement->GetFields();
					$arProperties = $oIblockElement->GetProperties();
					
					// изображение
					$arProduct["PICTURE"] = \CFile::GetPath($arFields["PREVIEW_PICTURE"]);
					
					// артикул
					$arProduct["ARTICLE"] = $arProperties["CML2_ARTICLE"]["VALUE"];
					
					// цвет
					$arProduct["COLOR"] = $arProperties["TSVET"]["VALUE"];
					
					// размер
					$arProduct["SIZE"] = $arProperties["RAZMER"]["VALUE"] != "&lt;&gt;" ? $arProperties["RAZMER"]["VALUE"] : "";
					if (!$arProduct["SIZE"]) {
						$arProduct["SIZE"] = $arProperties["RAZMER_ODEZHDY"]["VALUE"] != "&lt;&gt;" ? $arProperties["RAZMER_ODEZHDY"]["VALUE"] : "";
					}
					
					// id основного товара
					$idMainProduct = $arProperties["CML2_LINK"]["VALUE"];
					
					// получить основной товар
					$rsMainProduct = \CIblockElement::GetById($idMainProduct);
					$oMainElement = $rsMainProduct->GetNextElement();
					$arFieldsMainProduct = $oMainElement->GetFields();
					$arPropertiesMainProduct = $oMainElement->GetProperties();
					
					// материал
					$arProduct["MATERIAL"] = $arPropertiesMainProduct["MATERIAL"]["VALUE"];
					
					// описание
					$arProduct["DESCRIPTION"] = strip_tags($arFieldsMainProduct["~DETAIL_TEXT"]);
					
					// виды нанесения
					$arProduct["DRAWING"] = implode(", ", $arPropertiesMainProduct["APPLICATION_TYPES"]["VALUE"]);					
					
					$products[$arProduct["PRODUCT_ID"]] = $arProduct;
				}
				
				$idKp = Offer::loadFromBascet($products);
				return $idKp;
			}
			
		}
		
	}
}
