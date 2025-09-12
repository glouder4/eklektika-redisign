<?php
use Bitrix\Main\Loader;
use Bitrix\Sale;
use intec\eklectika\advertising_agent\Client;

define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;
$userId = $USER->GetID();

CModule::IncludeModule("intec.eklectika");
Loader::includeModule('sale');

$statusClient = Client::getStatus();
$arUserInfo = Client::getInfo($userId);

$order = Sale\Order::create(SITE_ID, $userId);
if( isset($_GET['STATUS_ID']) && ( $_GET['STATUS_ID'] == "R" || $_GET['STATUS_ID'] == "OB" ) ){
    // Устанавливаем статус заказа "К"
    $order->setField('STATUS_ID', $_GET['STATUS_ID']);
}

/*if ($statusClient == "fiz") {
	$typePerson = 1;
	$order->setPersonTypeId($typePerson);  
	
} else {
	$typePerson = 2;
	$order->setPersonTypeId($typePerson);  
}*/
$typePerson = 2;
$order->setPersonTypeId($typePerson);

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$order->setBasket($basket);
$basketSum = $order->getPrice();
/*if ($basketSum < 15000) {
	die();
}*/
$arProps = [];
if ($typePerson == 1) {
    if( isset($_GET['STATUS_ID']) && ( $_GET['STATUS_ID'] == "R" ) ) {
        $arProps["REQUEST_TO_RESERVE"] = [
            "ID" => 25,
            "VALUE" => "Y"
        ];
    }
    if( isset($_GET['STATUS_ID']) && ( $_GET['STATUS_ID'] == "OB" ) ){
        $arProps["REQUEST_TO_SAMPLE"] = [
            "ID" => 35,
            "VALUE" => "Y"
        ];
    }
	$arProps["FIO"] = [
		"ID" => 1,
		"VALUE" => $arUserInfo["LAST_NAME"]." ".$arUserInfo["NAME"]
	];
	$arProps["EMAIL"] = [
		"ID" => 2,
		"VALUE" => $arUserInfo["EMAIL"]
	];
	$arProps["PHONE"] = [
		"ID" => 3,
		"VALUE" => $arUserInfo["PERSONAL_PHONE"]
	];
} else {
    if( isset($_GET['STATUS_ID']) && ( $_GET['STATUS_ID'] == "R" ) ){
        $arProps["REQUEST_TO_RESERVE"] = [
            "ID" => 26,
            "VALUE" => "Y"
        ];
    }
    if( isset($_GET['STATUS_ID']) && ( $_GET['STATUS_ID'] == "OB" ) ){
        $arProps["REQUEST_TO_SAMPLE"] = [
            "ID" => 35,
            "VALUE" => "Y"
        ];
    }
	$arProps["ADVERSTERING_AGENT"] = [
		"ID" => 22,
		"VALUE" => "Y"
	];
	$arProps["CONTACT_PERSON"] = [
		"ID" => 12,
		"VALUE" => $arUserInfo["LAST_NAME"]." ".$arUserInfo["NAME"]
	];
	$arProps["EMAIL"] = [
		"ID" => 13,
		"VALUE" => $arUserInfo["EMAIL"]
	];
	$arProps["COMPANY"] = [
		"ID" => 8,
		"VALUE" => $arUserInfo["UF_NAME_COMPANY"]
	];
	$arProps["INN"] = [
		"ID" => 10,
		"VALUE" => $arUserInfo["UF_INN"]
	];
}



$oProperties = $order->getPropertyCollection();

foreach ($arProps as $arProperty) {
	$oProperty = null;

	foreach ($oProperties as $oProperty) {
		if ($oProperty->getField('ORDER_PROPS_ID') == $arProperty['ID'])
			break;

		$oProperty = null;
	}

	if (!empty($oProperty))
		$oProperty->setValue($arProperty['VALUE']);
}

/*foreach ($oProperties as $oProperty) {
	print_r($oProperties);
	continue;
	$propertyValue = $propertyCollection->getItemByOrderPropertyId($property['ID']);
		
		// switch($property["CODE"]) {
			// case "ADVERSTERING_AGENT":
				// if ($statusClient == "agent") {					
					// $propertyValue->setValue("Y");
				// }
			// break;
			// case "REQUEST_TO_RESERVE":
				// if ($typePerson == $property["PERSON_TYPE_ID"]) {
					// $propertyValue->setValue("Y");
				// }
			// break;
			// case "CONTACT_PERSON":
				// if ($typePerson == $property["PERSON_TYPE_ID"]) {					
					// $propertyValue->setValue($arUserInfo["LAST_NAME"]." ".$arUserInfo["NAME"]);
				// }
			// break;
			// case "EMAIL":
				// if ($typePerson == $property["PERSON_TYPE_ID"]) {					
					// $propertyValue->setValue($arUserInfo["EMAIL"]);
				// }
			// break;
			// case "COMPANY":
				// if ($typePerson == $property["PERSON_TYPE_ID"]) {					
					// $propertyValue->setValue($arUserInfo["UF_NAME_COMPANY"]);
				// }
			// break;
			// case "INN":
				// if ($typePerson == $property["PERSON_TYPE_ID"]) {					
					// $propertyValue->setValue($arUserInfo["UF_INN"]);
				// }
			// break;
			// default:
				// echo $property["CODE"];
				// $propertyValue->delete();
			// break;
		// }
	// }
}

die();*/


// Сохраняем заказ
$order->doFinalAction(true);
$result = $order->save();
if ($result->isSuccess()) {
    // $orderId = $order->getId();
    echo "ok";
} else {
    $errors = $result->getErrorMessages();
    echo "Ошибка создания: " . implode(', ', $errors);
}

// Далее можете добавить любую логику, связанную с созданием заказа

// В конце скрипта подключаем футер
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>

?>