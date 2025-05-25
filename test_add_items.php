<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?
	use Bitrix\Sale;
?>

<?
function addApplication1($dl, $ord)
{
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
						"ACTIVE" => "N",
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



		function setPrice($productID, $price){   	//Задаем цену созданному товару
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

				?><pre><?
					print_r($name);
					print_r($findItemInOrder);
				?></pre><?

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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);	
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
?><pre><?
	print_r($responseArray['result'])
?></pre><?
	}
}
	 ?>

<? 
	addApplication1(12002, 28); 
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>