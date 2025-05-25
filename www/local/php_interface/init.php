<?php
if (headers_sent() === false) {
    header_remove('X-Frame-Options');
}
define('BX_CRONTAB_SUPPORT', true);
//header("X-Frame-Options: testb24.yoliba.ru");
//header('Content-Security-Policy: frame-ancestors https://testb24.yoliba.ru', true);
use Bitrix\Sale;

use intec\eklectika\advertising_agent\Company;
CModule::IncludeModule("intec.eklectika");

define('IBLOCK_ID_1C', 45);
define('URL_B24', 'https://testb24.yoliba.ru/');

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

/**
* отправить запрос к Б24
*/
function sendRequestB24($method, $params) {
	$queryUrl = URL_B24.'rest/1/w8i2ce68y3wwps17/'.$method.'.json';
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
	));
	if (!$result = curl_exec($curl)) {
		$result = curl_error($curl);
	} 
	curl_close($curl);
	$result = json_decode($result, true);
	return $result["result"];
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
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-fields.txt', print_r($fields, true));
} */

function getApplication($dl, $ord)
{
	if((CModule::IncludeModule('iblock'))&&(CModule::IncludeModule('sale'))) {
		
		$webhook = "https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/";
	
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
		$webhook = "https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/";	
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

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
function OnBeforeUserRegisterHandler(&$arFields) {
	global $APPLICATION;	
	
	// найти пользователя в б24 по EMAIL
	$arResult = newRest("EMAIL", $arFields, "EMAIL");	
	// найти пользователя в б24 по Телефону
	if (empty($arResult)){
		$arResult = newRest("PHONE", $arFields, "PERSONAL_PHONE");
	}
	
	// если такой пользователь есть, то вывести предупреждение
	if (!empty($arResult) && count($arResult) > 0) {
		if (is_array($arResult[0]['PHONE']) && !empty($arResult[0]['PHONE'])) {
			$field = $arFields['PERSONAL_PHONE'];
		} else {
			$field = $arFields['EMAIL'];
		}
		$APPLICATION->ThrowException('Такой пользователь уже существует в системе. Вы можете авторизоваться или восстановить пароль для '.$field); 
		return false;
	} else {
		if ($arFields['PASSWORD'] == $arFields['CONFIRM_PASSWORD']) {
			// данные для контакта
			$dataContact = [
				'fields' => [
					'NAME' => $arFields['NAME'],
					'SECOND_NAME' => $arFields['SECOND_NAME'],
					'LAST_NAME' => $arFields['LAST_NAME'],
					'OPENED' => 'Y',
					'ASSIGNED_BY_ID' => 1,
					'PHONE' => [[
						"VALUE" => $arFields['PERSONAL_PHONE'], 
						"VALUE_TYPE" => "WORK"
					]],
					'EMAIL' => [ [
						"VALUE" => $arFields['EMAIL'], 
						"VALUE_TYPE" => "WORK"
					]]
				],
				'params' => []
			];
			
			// если это компания или рекламынй агент
			if ($arFields['UF_TYPE'] == '5' || $arFields['UF_TYPE'] == '6') {				
				// проверить заполненность ИНН и названия компании
				if (empty($arFields['UF_INN']) && empty($arFields['UF_NAME_COMPANY'])) {
					$APPLICATION->ThrowException('Вы регистрируйтесь как рекламный агент или юридическое лицо. Поля "Название компании", "ИНН организации" обязательно для заполнения!'); 
					return false;
				} else {					
					// если это рекламный агент
					if ($arFields['UF_ADVERSTERING_AGENT'] == 'on') {
						$dataContact['fields']['UF_CRM_1701839165901'] = "Пользователь зарегистрировался как рекламный агент";
					}
					$dataRequisite = [
						'fields' => [],
						'params' => [],
						'select' => [
							'ID', 
							'RQ_INN',
							'ENTITY_ID'
						],
						'filter' => [
							'RQ_INN' => $arFields['UF_INN']
						]
					];
					// найти реквизит по ИНН
					$dataRequisite = sendRequestB24("crm.requisite.list", $dataRequisite);
					
					if (!empty($dataRequisite)) {		
						$dataContact['fields']['COMPANY_ID'] = $dataRequisite[0]['ENTITY_ID'];
						$companyId = $dataRequisite[0]['ENTITY_ID'];
					} else {						
						/*Создание компании*/			
						$qrCompanyInfo = [
							'fields' => [
								'TITLE' => $arFields['UF_NAME_COMPANY'],
								'PHONE' => [[
									'VALUE' => $arFields['PERSONAL_PHONE'],
									'VALUE_TYPE' => "WORK"
								]],
								'EMAIL' => [[ 
									'VALUE' => $arFields['EMAIL'],
									'VALUE_TYPE' => "WORK"
								]],
								'WEB' => [[
									'VALUE' => $arFields['UF_SITE'],
									"VALUE_TYPE" => "WORK"
								]],
								'UF_CRM_1669208000616' => $arFields['UF_SPERE'],
								'UF_CRM_1669208295583' => $arFields['UF_JUR_ADDRESS'],
								'COMPANY_TYPE' => 'CUSTOMER'
							]
						];											
						$companyId = sendRequestB24("crm.company.add", $qrCompanyInfo);		
						if (!empty($companyId)) {
							$qrCompany['id'] = $companyId;							
							$dataCompany = sendRequestB24("crm.company.get", $qrCompany);
													
							/*Добавление реквизита к компании*/		
							$qrRequisite = [
								'fields' => [
									'ENTITY_ID' => $dataCompany['ID'],
									'ENTITY_TYPE_ID' => '4',
									'NAME' => 'Реквизит с формы сайта',
									'PRESET_ID' => 1
								]
							];							
							$requisiteId = sendRequestB24("crm.requisite.add", $qrRequisite);
							
							/*Обновление реквизитов у компании*/
							$qrRequisites = array(
								'id' => $requisiteId,
								'fields' => [
									'ENTITY_ID' => $dataCompany['ENTITY_ID'],
									'ENTITY_TYPE_ID' => '4',
									'RQ_INN' => $arFields['UF_INN'],
									'RQ_KPP' => $arFields['UF_KPP'],
									'RQ_COMPANY_FULL_NAME' => $arFields['UF_NAME_COMPANY']
								]
							);							
							sendRequestB24("crm.requisite.update", $qrRequisites);
						}
					}
					sleep(10);
					// добавить компанию в инфоблок
					$companySite = Company::findByIdB24($dataCompany['ID']);
					$dataCompanyCreate = [
						"NAME_COMPANY" => $arFields['UF_NAME_COMPANY'], // название компании
						"INN" => $arFields['UF_INN'], // ИНН
						"KPP" => $arFields['UF_KPP'], // КПП
						"WEBSITE" => $arFields['UF_SITE'], // сайт
						"SPHERE" => $arFields['UF_SPERE'], // сфера деятельности
						"ADDRESS" => $arFields['UF_JUR_ADDRESS'], // адрес
						"ID_B24" => $dataCompany['ID'],
						"PHONE" => $arFields['PERSONAL_PHONE'],
						"EMAIL" => $arFields['EMAIL'],
					];
					if ($companySite) {
						Company::update($companySite["ID"], $dataCompanyCreate);
					} else {
						Company::add($dataCompanyCreate);
					}
					
				} 
			}
			$contactId = sendRequestB24("crm.contact.add", $dataContact);
			
			if (!empty($companyId) && !empty($contactId)) {		
				// добавить контакт в компанию
				$qrCompanyAddContact = [
					'fields' => ['COMPANY_ID' => $companyId],
					'id' => $contactId
				];
				sendRequestB24("crm.contact.company.add", $qrCompanyAddContact);				
			}
		}
		return $arFields;
	}
}

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler2");
function OnBeforeUserRegisterHandler2(&$arFields) {
	$arFields['UF_ADVERSTERING_AGENT'] = "";
}

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "UpdateEmployees");
// увольнение сотрудника
function UpdateEmployees($arFields) {
    $IBLOCK_ID = 52;

    if ($arFields['IBLOCK_ID'] == $IBLOCK_ID) {  
		$arStaff = [];
		$arCurrent = [];
		$b24id = $arFields['PROPERTY_VALUES']['594'];
		
		foreach ($arFields['PROPERTY_VALUES']['583'] as $value) {  
			if (!empty($value['VALUE'])) {
				array_push($arStaff, $value['VALUE']);
			}
		}

		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_STAFFS"];
		$arFilter = ["IBLOCK_ID" => $IBLOCK_ID, "ID" => $arFields['ID']];
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			array_push($arCurrent, $arFields['PROPERTY_STAFFS_VALUE']);
		}

		if (count($arCurrent) >  count($arStaff))  {
			$arResult = array_diff($arCurrent, $arStaff);
			foreach($arResult as $result){
				$userId = $result;
			}
			
			$rsUser = CUser::GetByID($userId);
			$arUser = $rsUser->Fetch();

			deleteStaffB24($arUser, $b24id, $arFields['ID']);
		}
    }
}

function deleteStaffB24($arUser, $companyId, $idCompanySite) {
    $qrList = [
        'fields' => [],
        'params' => [],
        'select' => [],
        'filter' => ["EMAIL" => $arUser["EMAIL"]]
    ];    
    $arResult = sendRequestB24("crm.contact.list", $qrList);	
	
	if ($arResult['ID']) {	
		// убрать рекламную агентность		
		sendRequestB24("crm.contact.update", [ 
			"id" => $arResult['ID'],
			"fields" => [
				'UF_CRM_1698752707853' => ''
            ]
        ]);
		intec\eklectika\advertising_agent\Client::eraseStatusRA($arUser["ID"], $idCompanySite);
		
		// уволить его!		
		sendRequestB24("crm.contact.company.delete", [
			'id' => $arResult['ID'],
			'fields' => array('COMPANY_ID' => $companyId),
		]); 
		// прощай сотрудник, ты больше нам не нужен =(
	}
}

\Bitrix\Main\EventManager::getInstance()->addEventHandler( 
    'sale', 
    'OnSaleComponentOrderOneStepPersonType', 
    'intec\eklectika\advertising_agent\Client::onSaleComponentOrderOneStepPersonType'

); 


/* AddEventHandler("sale", "OnBeforeOrderUpdate", "checkBeforeUpdate");
function checkBeforeUpdate(&$arFields){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-update-order.txt', print_r($arFields, true));
}

AddEventHandler("sale", "OnOrderUpdate", "checkUpdate");
function checkUpdate(&$arFields){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-after-update-order.txt', print_r($arFields, true));
} */

$bxEventManager = \Bitrix\Main\EventManager::getInstance();
$bxEventManager->addEventHandler(
    "sale",
    "OnSaleOrderBeforeSaved",
    "changeStatusOnOrderCreate"
);

function changeStatusOnOrderCreate(Bitrix\Main\Event $event){
	$order = $event->getParameter("ENTITY");
    $value = $event->getParameter("VALUE");
    $oldValue = $event->getParameter("OLD_VALUE");

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-after-update-order.txt', print_r($order, true));
}


