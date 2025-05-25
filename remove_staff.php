<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?
    // use intec\eklectika\advertising_agent\Company;
    // CModule::IncludeModule("intec.eklectika");

    $companyCRM = [2561];
	$staffId = 84;
    $iblockId == 52;
?>

<?
function getCompsID($arSelect, $arFilter, $propName, $filterVar){
    $propName = "PROPERTY_" . $propName;
    $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", $propName);
    $arFilter = Array("IBLOCK_ID"=>$iblockId, $propName => $filterVar);

	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$arResult = [];
	
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		array_push($arResult, $arFields['ID']);
	}
	
	return $arResult;
}

function findDiff($array1, $array2){
    $arDifference = array_merge(array_diff($array1, $array2), array_diff($array2, $array1));
    return $arDifference;
}

function updateStaffs($IBlock, $compID, $staffId, $flag){
	$code = 'STAFFS'; //код 
	$db_props = CIBlockElement::GetProperty(52, $compID, array("sort" => "asc"), Array("CODE" => $code));
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
        array_push($VALUES, $staffId);
	}
  
    $newProps = empty($VALUES) ? [$code => false] : [$code => $VALUES];
	CIBlockElement::SetPropertyValuesEx($compID, $IBlock, $newProps);

	return $VALUES;
}


$staffInCompanies = getCompsID($arSelect, $arFilter, "STAFFS", $staffId);
if  (!empty($companyCRM)){
    $companiesInB24 = getCompsID($arSelect, $arFilter, "ID_B24", $companyCRM);
} else {
    $companiesInB24 = [];
}

$diff = findDiff($staffInCompanies, $companiesInB24);

foreach ($diff as $company){
    $flag = in_array($company, $staffInCompanies) ? 0 : 1;
    updateStaffs($iblockId, $company, $staffId, $flag);
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>