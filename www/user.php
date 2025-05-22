<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Universe");
CModule::IncludeModule("intec.eklectika");
intec\eklectika\advertising_agent\Client::addInWork(81);
 // $qrList = [
        // 'fields' => [],
        // 'params' => [],
        // 'select' => [],
        // 'filter' => ["EMAIL" => "asdfasdfasdf@mail.ru"]
    // ];    
    // $arResult = sendRequestB24("crm.contact.list", $qrList);	
// print_r($arResult);
die();


?>

<?php
    /**Добавляем контакт*/
  /*   $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.add.json';
    $qr = array(
        'fields' => array(),
        'params' => array()
    );
    $qr['fields']['NAME'] = 'Test 1';
    $qr['fields']['SECOND_NAME'] = 'Test 1';
    $qr['fields']['LAST_NAME'] = 'Test 1';
    $qr['fields']['OPENED'] = 'Y'; //открыто для других пользователей
    $qr['fields']['ASSIGNED_BY_ID'] = '1'; //id ответственного менеджера
    $qr['fields']['PHONE']['n1'] = array("VALUE"=>"31231231200", "VALUE_TYPE"=>"WORK");
    $qr['fields']['EMAIL']['n1'] = array("VALUE"=>"test@bail.com", "VALUE_TYPE"=>"WORK");

    $queryData = http_build_query($qr);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    if(!$result = curl_exec($curl))
    {
        $result = curl_error($curl);
    }
    curl_close($curl);

    $result = json_decode($result, true);
    $contactId = $result["result"];

	echo "<pre>";
	print_r($contactId);
	echo "</pre>"; */
?>

<?php
    $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.get.json';
    $qr['id'] = 34731;
    $queryData = http_build_query($qr);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    if(!$result = curl_exec($curl))
    {
        $result = curl_error($curl);
    }
    curl_close($curl);

    $result = json_decode($result, true);
    $contactId = $result["result"];

	echo "<pre>";
	print_r($contactId);
	echo "</pre>";
	
?>

<?php

$filter = [
		"ACTIVE"              => "Y",
		"EMAIL"               => "ai@eklektika.ru",
	];
$rsUsers = CUser::GetList(
	[ $by="personal_country" ], 
	[ $order="desc" ], 
	$filter
);
$arUserList = [];

while($arUser = $rsUsers->Fetch()){
	$arUserList[] = $arUser['ID'].' '.$arUser['NAME'].' '.$arUser['LAST_NAME'];
};



?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
