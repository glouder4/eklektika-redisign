<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Universe");
?>

<?php
/* 
	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.requisite.list.json';
	
	$qr = array(
        'fields' => array(),
        'params' => array(),
        'select' => array(),
        'filter' => array()
    );
	
	$qr['filter']['ENTITY_ID'] = '2503';
	$qr['select'][] = 'ID';
	$qr['select'][] = 'RQ_INN';
	$qr['select'][] = 'ENTITY_ID';
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
    $companyId = $result["result"];
	echo "<pre>";
    print_r($companyId);
	echo "</pre>";
	
	if (!empty($companyId)) {
		echo $companyId[0]['ENTITY_ID'];
		$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.company.get.json';
	
		$qrCompany['id'] = $companyId[0]['ENTITY_ID'];

		$queryDataCompany = http_build_query($qrCompany);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryDataCompany,
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
	}  */
?>

<?
/* $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.company.update.json';
	
	$qr = array(
        'fields' => array(),
        'params' => array(),
        'select' => array(),
        'filter' => array()
    );
	
	$qr['id'] = '2497';
	$qr['fields']['RQ_INN'] = '7722537534';
	$qr['fields']['RQ_KPP'] = '7722537534';
	$qr['fields']['UF_CRM_1669208000616'] = 'Test';

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
    $companyId = $result["result"];
	echo "<pre>";
    print_r($companyId);
	echo "</pre>"; */
?>

<?
/* 	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.requisite.add.json';
	
	$qr = array(
        'fields' => array()
    );
	

	$qr['fields']['ENTITY_ID'] = '2506';
	$qr['fields']['ENTITY_TYPE_ID'] = '4';
	$qr['fields']['NAME'] = 'Реквизит REST';
	$qr['fields']['PRESET_ID'] = '1';
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
    $companyId = $result["result"];
	echo "<pre>";
    print_r($companyId);
	echo "</pre>";  */
?>

<?
	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.requisite.update.json';
	
	$qr = array(
        'fields' => array()
    );
	
	$qr['id'] = '2787';
	$qr['fields']['ENTITY_ID'] = '2507';
	$qr['fields']['ENTITY_TYPE_ID'] = '4';
	$qr['fields']['RQ_INN'] = '1234567890';
	$qr['fields']['RQ_KPP'] = '123456789';

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
    $companyId = $result["result"];
	echo "<pre>";
    print_r($companyId);
	echo "</pre>"; 
?>

<?
  /*   $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.requisite.list.json';
	
	$qr = array(
        'fields' => array(),
        'params' => array(),
        'select' => array(),
        'filter' => array()
    );
	
	//$qr['filter']['ID'] = '2505';
	$qr['filter']['ENTITY_ID'] = '2506';
	
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
    $companyId = $result["result"];
	echo "<pre>";
    print_r($companyId);
	echo "</pre>";   */
?>

<?
	 	/* $queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.company.get.json';
	
		$qrCompany['id'] = '2505';

		$queryDataCompany = http_build_query($qrCompany);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryDataCompany,
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
		echo "</pre>";  */
?>


<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>