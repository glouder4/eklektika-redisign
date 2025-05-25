<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>


<?
function addApplication1($dl, $ord)
{
	if((CModule::IncludeModule('iblock'))&&(CModule::IncludeModule('sale'))) {
		
		$webhook = "https://testb24.yoliba.ru/rest/1/w8i2ce68y3wwps17/";
	
		$method = "kit.productapplications.deal.productrows.get/?ID=";  

		$dealId = $dl;  // ID сделки

		$newOrderId = $ord;  // ID заказа 

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $webhook . $method . $dealId);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);

		$response = json_decode(curl_exec($ch));
		$responseArray = json_decode(json_encode($response), true);

		curl_close($ch);


	?><pre><?
		print_r($responseArray['result'] );
	?></pre><?

	}

	$log = date('Y-m-d H:i:s') . print_r($responseArray['result'], true);
	file_put_contents($_SERVER['DOCUMENT_ROOT'] .  '/get-items-log.txt', $log);



} ?>

<? 
	addApplication1(11983, 37); 
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>