<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<?
	$requisiteId = [];
	$requisiteId['id'] = 2815;

	$queryUrl = 'https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/crm.requisite.get.json';

	$queryDataCompany = http_build_query($requisiteId);

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
	$companyId = $result["result"];
?>

<pre>
<?print_r($companyId);?>
</pre>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>