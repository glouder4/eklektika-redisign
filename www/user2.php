<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Universe");
?>

<?php
    /**Список контакт*/
    $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.list.json';
    $qr = array(
        'fields' => array(),
        'params' => array(),
        'select' => array(),
        'filter' => array()
    );
	
	/* $qr['filter']['NAME'] = 'Екатерина';
	$qr['filter']['LAST_NAME'] = 'Серафимова'; */
	
	$qr['filter']['ID'] = 34747;
	$qr['select'][] = 'NAME';
	$qr['select'][] = 'EMAIL';
	$qr['select'][] = 'PHONE';
	$qr['select'][] = 'UF_*';

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
    print_r($result);
	echo "</pre>";
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>