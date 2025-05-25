<?php

/* 	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.company.add.json';

	$qrCompanyInfo = array(
		'fields' => array()
	);
	
	$qrCompanyInfo['fields']['TITLE'] = 'TEST API с реквизитами';
	$qrCompanyInfo['fields']['PHONE']['n1'] = array("VALUE"=>'12345678', "VALUE_TYPE"=>"WORK");
	$qrCompanyInfo['fields']['EMAIL']['n1'] = array("VALUE"=>'test@mail.ru', "VALUE_TYPE"=>"WORK");
	$qrCompanyInfo['fields']['WEB']['n1'] = array("VALUE"=>'testmail.ru', "VALUE_TYPE"=>"WORK");
	
	$queryData = http_build_query($qrCompanyInfo);

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
		
	} */
	
	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.contact.company.add.json';

			$qrCompanyAddContact = array(
				'fields' => array()
			);
			
			$qrCompanyAddContact['id'] = '34722';
			$qrCompanyAddContact['fields']['COMPANY_ID'] = '2498';
			
			$queryData = http_build_query($qrCompanyAddContact);

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