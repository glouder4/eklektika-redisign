<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<? 
	$companyCRM = [2549, 2552];
	$staffId = 84;
?>

<? 

/*===========================ФУНКЦИИ===========================*/

//Поиск компаний по фильтру (по ID из Б24 или по ID сотрудника) 
function getItems($arSelect, $arFilter){
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$arResult = [];
	
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		array_push($arResult, $arFields);
	}
	
	return $arResult;
}

//Обновление сотрудников компании
function updateStaffs($compID, $staffId, $flag){
	$code = 'STAFFS'; //код 
	$db_props = CIBlockElement::GetProperty(52, $compID, array("sort" => "asc"), Array("CODE"=>$code));
	$VALUES = [];

	while ($ob = $db_props->GetNext()){
		if ($ob['VALUE']) {
			if (($ob['VALUE'] == $staffId) && !$flag ){
				continue;
			}
			$VALUES[] = $ob['VALUE'];
		}   
	}

	if ($flag){
		$VALUES[] = $staffId;
	}

	CIBlockElement::SetPropertyValuesEx($compID, 52,["STAFFS" => $VALUES]);
	return $VALUES;
}
/*=============================================================*/

//Найти и удалить сотрудника из компаний, к котрым нет привязки в Б24
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_ID_B24", "PROPERTY_STAFFS");
$arFilter = Array("IBLOCK_ID"=>52, "PROPERTY_STAFFS" => $staffId);

$staffInCompanies = getItems($arSelect, $arFilter);
$compsId = [];

foreach ($staffInCompanies as $company){
	if	(in_array($company['PROPERTY_ID_B24_VALUE'], $companyCRM)){
		continue;
	}
	array_push($compsId,  $company['ID']);
}

foreach ($compsId as $compId){
	updateStaffs($compId, $staffId, 0);
}

//Найти и удалить сотрудника в компаню, к котрым есть привязка в Б24
$newStaffInCompanies = getItems($arSelect, $arFilter);
$arrNewComp = [];

foreach ($staffInCompanies as $newComp) {
	array_push($arrNewComp, $newComp['PROPERTY_ID_B24_VALUE']);
}

$fullDiff = array_merge(array_diff($arrNewComp, $companyCRM), array_diff($companyCRM, $arrNewComp)); 

foreach ($fullDiff as $fullDiffId){
	updateStaffs($fullDiffId, $staffId, 1);
}

?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>