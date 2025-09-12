<?php
if (headers_sent() === false) {
    header_remove('X-Frame-Options');
}


define('BX_CRONTAB_SUPPORT', true);
//header("X-Frame-Options: bitrix.yomerch.ru");
//header('Content-Security-Policy: frame-ancestors https://bitrix.yomerch.ru', true);
use Bitrix\Sale;

use intec\eklectika\advertising_agent\Company;
CModule::IncludeModule("intec.eklectika");

define('IBLOCK_ID_1C', 45);
define('URL_B24', 'https://bitrix.yomerch.ru/');
define("EXLUDED_ORDER_KEYS",["KO","UD","KP",'SD']);
define("EXLUDED_RESERVE_KEYS",["RO", "RC", "R"]);
define("EXLUDED_SAMPLE_KEYS",["OB","SS", "SO", "SC","OG"]);

require_once __DIR__.'/../classes/requires.php'; // Подключение кастомных обработчиков

function pre($o) {

    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/", "\\", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    $dRoot = str_replace("\\", "/", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    ?>
    <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;text-align: left!important;'>
        <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]</div>
        <pre style='padding:5px;'><? print_r($o) ?></pre>
    </div>
    <?
}
/*
use Bitrix\Main\EventManager;

// Получаем все обработчики события
$eventManager = EventManager::getInstance();
$handlers = $eventManager->findEventHandlers("main", "OnAfterUserUpdate");

echo "<pre>";
foreach ($handlers as $handler) {
    echo "Module: " . $handler['MODULE_ID'] . "\n";
    echo "Class: " . $handler['TO_CLASS'] . "\n";
    echo "Method: " . $handler['TO_METHOD'] . "\n";
    echo "File: " . $handler['TO_PATH'] . "\n";
    echo "Sort: " . $handler['SORT'] . "\n";
    echo "------------------------\n";
}
echo "</pre>";
die(); */
/**
* отправить запрос к Б24
*/
function sendRequestB24($method, $params,$debug = false) {
	$queryUrl = URL_B24.'rest/1/oak1tjz71elzz2xt/'.$method.'.json';

    $curl = curl_init();
    $queryData  = http_build_query($params);

    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ));

    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    $curlErrno = curl_errno($curl);

    curl_close($curl);

    if( $debug ){
        // Логируем детали запроса
        pre("=== CURL Request Details ===");
        pre("URL: " . $queryUrl);
        pre("Params: " . print_r($params, true));
        pre("HTTP Code: " . $httpCode);
        pre("CURL Error: " . $curlError);
        pre("CURL Errno: " . $curlErrno);
        pre("Raw Response: " . $result);
    }

    // Обработка ошибок CURL
    if ($curlErrno) {
        pre("CURL Error occurred: " . $curlError);
        return [
            'success' => 0,
            'error' => 'CURL Error: ' . $curlError,
            'errno' => $curlErrno
        ];
    }

    // Обработка HTTP ошибок
    if ($httpCode !== 200) {
        if( $debug )
            pre("HTTP Error: " . $httpCode);

        return [
            'success' => 0,
            'error' => 'HTTP Error: ' . $httpCode,
            'response' => $result
        ];
    }

    // Парсим JSON ответ
    $decodedResult = json_decode($result, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        if( $debug ) {
            pre("JSON Parse Error: " . json_last_error_msg());
            pre("Raw response that failed to parse: " . $result);
        }
        return [
            'success' => 0,
            'error' => 'JSON Parse Error: ' . json_last_error_msg(),
            'raw_response' => $result
        ];
    }
    if( $debug ) {
        pre("=== Parsed Response ===");
        pre($decodedResult);
        die();
    }

    return $decodedResult['result'];
}

function sendRequest($params, $debug = false){
    $queryUrl = URL_B24.'/local/classes/ajax.php';
    $curl = curl_init();
    $queryData  = http_build_query($params);

    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ));

    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    $curlErrno = curl_errno($curl);

    curl_close($curl);

    if( $debug ){
        // Логируем детали запроса
        pre("=== CURL Request Details ===");
        pre("URL: " . $queryUrl);
        pre("Params: " . print_r($params, true));
        pre("HTTP Code: " . $httpCode);
        pre("CURL Error: " . $curlError);
        pre("CURL Errno: " . $curlErrno);
        pre("Raw Response: " . $result);
    }

    // Обработка ошибок CURL
    if ($curlErrno) {
        pre("CURL Error occurred: " . $curlError);
        return [
            'success' => 0,
            'error' => 'CURL Error: ' . $curlError,
            'errno' => $curlErrno
        ];
    }

    // Обработка HTTP ошибок
    if ($httpCode !== 200) {
        if( $debug )
            pre("HTTP Error: " . $httpCode);

        return [
            'success' => 0,
            'error' => 'HTTP Error: ' . $httpCode,
            'response' => $result
        ];
    }

    // Парсим JSON ответ
    $decodedResult = json_decode($result, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        if( $debug ) {
            pre("JSON Parse Error: " . json_last_error_msg());
            pre("Raw response that failed to parse: " . $result);
        }
        return [
            'success' => 0,
            'error' => 'JSON Parse Error: ' . json_last_error_msg(),
            'raw_response' => $result
        ];
    }
    if( $debug ) {
        pre("=== Parsed Response ===");
        pre($decodedResult);
        die();
    }

    return $decodedResult;
}

AddEventHandler("catalog", "OnSuccessCatalogImport1C", "updateProperties");
function updateProperties() {
	if (CModule::IncludeModule('iblock') && CModule::IncludeModule('catalog')) {
		$dbFields = \CIBlockElement::GetList (
			["ID" => "ASC"],
			["IBLOCK_ID" => IBLOCK_ID_1C],
			false,
			false, [
				'ID', 
				'IBLOCK_ID', 
				'PROPERTY_TAMPOPECHAT',
				'PROPERTY_SHELKOGRAFIYA',
				'PROPERTY_FLEKSOGRAFIYA',
				'PROPERTY_LAZERNAYA_GRAVIROVKA',
				'PROPERTY_UF_PECHAT', 
				'PROPERTY_POLIMERNAYA_NAKLEYKA', 
				'PROPERTY_VYSHIVKA', 
				'PROPERTY_SHEVRON', 
				'PROPERTY_PRYAMAYA_TSIFROVAYA_PECHAT', 
				'PROPERTY_SUBLIMATSIONNAYA_PECHAT', 
				'PROPERTY_DEKOLIROVANIE', 
				'PROPERTY_SHILDY_I_NAKLEYKI', 
				'PROPERTY_TISNENIE', 
				'PROPERTY_TERMOTRANSFER', 
				'PROPERTY_ZALIVKA_POLIMERNOY_SMOLOY', 
				'PROPERTY_POLIGRAFICHESKAYA_VSTAVKA', 
				'PROPERTY_TSIFROVAYA_PECHAT', 
			]
		);
		$arUpdateProp = [];
		while($arFields = $dbFields->Fetch()) {
			if(!empty($arFields['PROPERTY_TAMPOPECHAT_VALUE'])) {
				$arUpdateProp[] = 'Тампопечать';
			}
			if(!empty($arFields['PROPERTY_SHELKOGRAFIYA_VALUE'])) {
				$arUpdateProp[] = 'Шелкография';
			}
			if(!empty($arFields['PROPERTY_FLEKSOGRAFIYA_VALUE'])) {
				$arUpdateProp[] = 'Флексография';
			}
			if(!empty($arFields['PROPERTY_LAZERNAYA_GRAVIROVKA_VALUE'])) {
				$arUpdateProp[] = 'Лазерная гравировка';
			}
			if(!empty($arFields['PROPERTY_UF_PECHAT_VALUE'])) {
				$arUpdateProp[] = 'УФ-печать';
			}
			if(!empty($arFields['PROPERTY_POLIMERNAYA_NAKLEYKA_VALUE'])) {
				$arUpdateProp[] = 'Полимерная наклейка';
			}
			if(!empty($arFields['PROPERTY_VYSHIVKA_VALUE'])) {
				$arUpdateProp[] = 'Вышивка';
			}
			if(!empty($arFields['PROPERTY_SHEVRON_VALUE'])) {
				$arUpdateProp[] = 'Шеврон';
			}
			if(!empty($arFields['PROPERTY_PRYAMAYA_TSIFROVAYA_PECHAT_VALUE'])) {
				$arUpdateProp[] = 'Прямая цифровая печать';
			}
			if(!empty($arFields['PROPERTY_SUBLIMATSIONNAYA_PECHAT_VALUE'])) {
				$arUpdateProp[] = 'Сублимационная печать';
			}
			if(!empty($arFields['PROPERTY_DEKOLIROVANIE_VALUE'])) {
				$arUpdateProp[] = 'Деколирование';
			}
			if(!empty($arFields['PROPERTY_SHILDY_I_NAKLEYKI_VALUE'])) {
				$arUpdateProp[] = 'Шильды и наклейки';
			}
			if(!empty($arFields['PROPERTY_TISNENIE_VALUE'])) {
				$arUpdateProp[] = 'Тиснение';
			}
			if(!empty($arFields['PROPERTY_TERMOTRANSFER_VALUE'])) {
				$arUpdateProp[] = 'Термотрансфер';
			}
			if(!empty($arFields['PROPERTY_ZALIVKA_POLIMERNOY_SMOLOY_VALUE'])) {
				$arUpdateProp[] = 'Заливка полимерной смолой';
			}
			if(!empty($arFields['PROPERTY_POLIGRAFICHESKAYA_VSTAVKA_VALUE'])) {
				$arUpdateProp[] = 'Полиграфическая вставка';
			}
			if(!empty($arFields['PROPERTY_TSIFROVAYA_PECHAT_VALUE'])) {
				$arUpdateProp[] = 'Цифровая печать';
			}
			\CIBlockElement::SetPropertyValuesEx($arFields['ID'], IBLOCK_ID_1C, array('APPLICATION_TYPES' => $arUpdateProp));
			$arUpdateProp = [];
		}
		unset($arUpdateProp);
	}
}

function actionSection ($name=false, $section=false) {
	$rsSections = \CIBlockSection::GetList (
		[],
		[
			'IBLOCK_ID' => IBLOCK_ID_1C, 
			'NAME' => trim($name)
		]
	);

	if ($arSection = $rsSections->Fetch()) {
		$sectionId = $arSection['ID'];
	}

	if (empty($sectionId)) {

		$bs = new \CIBlockSection;
		$arFields = Array(
			"ACTIVE" => 'Y',
			"IBLOCK_SECTION_ID" => $section,
			"IBLOCK_ID" => IBLOCK_ID,
			"NAME" => $name,
			"CODE" => \Cutil::translit($name, "ru", ["replace_space"=>"_","replace_other"=>"_"])
		  );
		$sectionId = $bs->Add($arFields);
	}

	return $sectionId;
}

/* AddEventHandler("catalog", "OnSuccessCatalogImport1C", "updateSections");
function updateSections()
{
	if (CModule::IncludeModule('iblock') && CModule::IncludeModule('catalog')) {
		$dbFields = \CIBlockElement::GetList (
			[],
			[
				'IBLOCK_ID' => IBLOCK_ID_1C
			],
			false,
			false,
			[
				'ID', 
				'IBLOCK_ID',
				'IBLOCK_SECTION_ID'
			]
		);
		
		while($ob = $dbFields->GetNextElement()) {
		
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();

			$res = \CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
			if ($ar_res = $res->GetNext()) {
				$idSectionElement = $ar_res['IBLOCK_SECTION_ID'];
			}
		
			$dopSection = $dopSectionId = [];
			$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_1']['VALUE'];
			$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_2']['VALUE'];
			$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_3']['VALUE'];

			foreach ($dopSection as $item) {
				if (!empty($item)) {
					$dopSectionId[] = actionSection($item, $idSectionElement);
				}
			}
			$dopSectionId[] = $arFields['IBLOCK_SECTION_ID'];
		
			$el = new \CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION" => $dopSectionId 
			);
			$res = $el->Update($arFields['ID'], $arLoadProductArray);

		}
	}
} */


/* AddEventHandler("sproduction.integration", "OnAfterOrderUpdate", "updateOnBeforeOrder1");
function updateOnBeforeOrder1($order_id)
{
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-update-order-1.txt', print_r($order_id, true));
	return $order_id;
} */

/* AddEventHandler("sproduction.integration", "OnBeforeDealUpdate", "checkBeforeDealUpdate");
function checkBeforeDealUpdate($deal_new_fields,$order_data,$deal_info){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order1.txt', print_r($deal_new_fields, true));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order2.txt', print_r($order_data, true));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order3.txt', print_r($deal_info, true));
}*/

function getApplication($dl, $ord)
{
	if((CModule::IncludeModule('iblock'))&&(CModule::IncludeModule('sale'))) {
		
		$webhook = URL_B24."/rest/1/w8i2ce68y3wwps17/";
	
		$method = "kit.productapplications.deal.productrows.get/?ID=";  

		$dealId = $dl;  // ID сделки

		$newOrderId = $ord;  // ID заказа 

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $webhook . $method . $dealId);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
		$response = json_decode(curl_exec($ch));
		$responseArray = json_decode(json_encode($response), true);

		curl_close($ch);
	}

	$log = date('Y-m-d H:i:s') . print_r($responseArray['result'], true);
	file_put_contents($_SERVER['DOCUMENT_ROOT'] .  '/get-items-log.txt', $log);
}


function addApplication($dl, $ord) {
	if((CModule::IncludeModule('iblock'))&&(CModule::IncludeModule('sale'))) {		
		$webhook = URL_B24."/rest/1/w8i2ce68y3wwps17/";	
		$method = "kit.productapplications.deal.productrows.get/?ID=";  
		$iblockID = 50;  // ID инфоблока товаров 
		$dealId = $dl;  // ID сделки
		$newOrderId = $ord;  // ID заказа 

		function findItem($iblock, $products){
			global $USER;

			foreach ($products as $product) {
				$arSelect = Array(
					"ID", 
					"NAME", 
					"PRICE_1",
					"PROPERTY_CML2_ARTICLE"
				);

				$arFilter = Array(
					"IBLOCK_ID" => IntVal($iblock), 
					"NAME" => $product['NAME'], 
					"PROPERTY_CML2_ARTICLE" => $product['ARTICLE'], 
				);

				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
				
				while($ob = $res->GetNextElement()){
					$arFields = $ob->GetFields();
				};

				if (empty($arFields)){            
					$el = new CIBlockElement;
					$PROP = array();
					$PROP[272] = $product['ARTICLE'];  
					$arLoadProductArray = Array(
						"MODIFIED_BY" => $USER->GetID(), 
						"IBLOCK_SECTION_ID" => false,          
						"IBLOCK_ID" => $iblock,
						"PROPERTY_VALUES" => $PROP,
						"NAME" => $product['NAME'],
						"ACTIVE" => "Y",
						"QUANTITY" => 0,
					);
					
					if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
						setPrice($PRODUCT_ID, $product['PRICE']); 
					} else {
						echo "Error: ".$el->LAST_ERROR;
					}
				};
			};
		};



		function setPrice($productID, $price){ //Задаем цену созданному товару
			$PRICE_TYPE_ID = 1;              
			$arFields = Array(
				"PRODUCT_ID" => $productID,
				"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
				"PRICE" => $price,
				"CURRENCY" => "RUB",
			);
			$res = CPrice::GetList(
						array(),
						array(
							"PRODUCT_ID" => $PRODUCT_ID,
							"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
						),
					);
			if ($arr = $res->Fetch()) {
				CPrice::Update($arr["ID"], $arFields);
			} else {
				CPrice::Add($arFields);
			};
		};

		function updateOrder($iblock, $products, $orderId){	    //Дополняем заказ отсутствующими товарами;
			
			global $USER;
			$order = Sale\Order::load($orderId);
			$basket = Sale\Order::load($orderId)->getBasket();
			$arBasketInfo = $basket->getListOfFormatText();
			$basketBasePrice = $basket->getBasePrice();
			$basketFinalPrice = $basket->getPrice();
			$basket->refreshData(['PRICE']);
			$quantity = 1;


			foreach ($products as $product) {
				$name = $product['NAME'] . " (" . $product['APPLICATION_PARENT_PRODUCT_NAME'] . ")";
				$arSelect = Array(
					"ID", 
					"NAME", 
					"PRICE_1",
					"PROPERTY_CML2_ARTICLE",
				);

				$arFilter = Array(
					"IBLOCK_ID" => IntVal($iblock), 
					"NAME" => $product['NAME'], 
					"PROPERTY_CML2_ARTICLE" => $product['ARTICLE'],
					"PROPERTY_PARENT_NAME" =>  $product['APPLICATION_PARENT_PRODUCT_NAME']
				);

				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
				
				while($ob = $res->GetNextElement()){
					$arFields = $ob->GetFields();
				};

				$basketInfo = Array();

				foreach ($basket as $basketItem) {
					$findItemInBasket = $basketItem->getField('NAME');
					array_push($basketInfo, $findItemInBasket);
				}

				$findItemInOrder = array_search($name, $basketInfo);

				if (empty($findItemInOrder)){
					$item = $basket->createItem('catalog', $arFields['ID']); 
					$item->setFields(array(
						'NAME' => $name,
						'QUANTITY' => $quantity,
						'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
						'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
						'PRICE' => $arFields['PRICE_1'],
						'CUSTOM_PRICE' => 'Y', 
						'IGNORE_CALLBACK_FUNC' => 'Y',    
						'PRODUCT_PROVIDER_CLASS' => '',
					));
				}

				$basket->refresh();
				$basket->save();
				$res = $basket->refreshData(array('PRICE', 'COUPONS'));
				if (!$res->isSuccess())
					$result->addErrors($res->getErrors());
			}
				
			$discount = $order->getDiscount();

			$registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);

			$discountCouponsClass = $registry->getDiscountCouponClassName();

			$discountCouponsClass::clearApply(true);
			$discountCouponsClass::useSavedCouponsForApply(true);

			$res = $discount->calculate();
			$order->applyDiscount($res->getData());
			$order->save(); 
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $webhook . $method . $dealId);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
		$response = json_decode(curl_exec($ch));
		$responseArray = json_decode(json_encode($response), true);

		curl_close($ch);

		$itemForOrder = Array();

		foreach ($responseArray['result'] as $orderItem) { 	
			if ((!empty($orderItem['UF_APPLICATION_PARENT_PRODUCT_ROW_ID'])) && ($orderItem['UF_APPLICATION_PARENT_PRODUCT_ROW_ID'] != 0)){
				
				$key = array_search($orderItem['UF_APPLICATION_PARENT_PRODUCT_ROW_ID'], array_column($responseArray['result'], 'ID'));
				$app_parent_name = $responseArray['result'][$key]['PRODUCT_NAME'];

				$item_param = Array(
					'ARTICLE' => $orderItem['PROPERTY_ARTIKUL_BITRIKS'],
					'NAME' => $orderItem['PRODUCT_NAME'],
					'PRICE' => $orderItem['PRICE'],
					'APPLICATION_PARENT_PRODUCT_NAME' => $app_parent_name,
				);
				array_push($itemForOrder, $item_param);
			}
		}

		if (!empty($itemForOrder)){
			findItem($iblockID, $itemForOrder);		
			updateOrder($iblockID, $itemForOrder, $newOrderId);
		};
		
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log-update-order-check-script.txt',  print_r($responseArray, true));
	}
}

function isAgent() {
	global $USER;
	$arGroups = CUser::GetUserGroup($USER->GetID());
	foreach ($arGroups as $groupId) {
		if ($groupId == 12) {
			return true;
		}
	}
	return false;
}

function isAuthorized() {
	global $USER;
	return $USER->IsAuthorized();
}


function findContact($param, $arFields, $select) {
    $qrList = [
        'fields' => [],
        'params' => [
			$param => $arFields[$select]
		],
        'select' => [
			$param
		],
        'filter' => []
    ];    
	return sendRequestB24("crm.contact.list", $qrList);
}
function newRest($param, $arFields, $select) {
    $qrList = array(
        'fields' => array(),
        'params' => array(),
        'select' => array(),
        'filter' => array()
    );
	
	$qrList['filter'][$param] = $arFields[$select];
	$qrList['select'][] = $param;

    return sendRequestB24("crm.contact.list", $qrList);//$result["result"];
}


