<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<?php

define('IBLOCK_ID', 43);

function actionSecrion ($name=false, $section=false) {
	$rsSections = CIBlockSection::GetList (
		[],
		[
			'IBLOCK_ID' => IBLOCK_ID, 
			'NAME' => $name
		]
	);

	if ($arSection = $rsSections->Fetch()) {
		$sectionId = $arSection['ID'];
	}

	if (empty($sectionId)) {

		$bs = new CIBlockSection;
		$arFields = Array(
			"ACTIVE" => 'Y',
			"IBLOCK_SECTION_ID" => $section,
			"IBLOCK_ID" => IBLOCK_ID,
			"NAME" => $name,
			"CODE" => Cutil::translit($name, "ru", ["replace_space"=>"_","replace_other"=>"_"])
		  );
		$sectionId = $bs->Add($arFields);
	}

	return $sectionId;
}

if (CModule::IncludeModule('iblock') && CModule::IncludeModule('catalog')) {

      $dbFields = CIBlockElement::GetList (
         [],
         [
			'ID' => 19500,
            'IBLOCK_ID' => IBLOCK_ID
         ],
         false,
         false,
         [
            'ID', 
            'IBLOCK_ID',
			'IBLOCK_SECTION_ID'
         ]
      );

      while($ob = $dbFields->GetNextElement()) {

		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();

		$res = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
		if ($ar_res = $res->GetNext()) {
			$idSectionElement = $ar_res['IBLOCK_SECTION_ID'];
		}

		$dopSection = $dopSectionId = [];
		$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_1']['VALUE'];
		$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_2']['VALUE'];
		$dopSection[] = $arProps['DLYA_SAYTA_DOP_GRUPPA_3']['VALUE'];

		foreach ($dopSection as $item) {
			if (!empty($item)) {
				$dopSectionId[] = actionSecrion($item, $idSectionElement);
			}
		}
		$dopSectionId[] = $arFields['IBLOCK_SECTION_ID'];

		$el = new CIBlockElement;
		$arLoadProductArray = Array(
			"IBLOCK_SECTION" => $dopSectionId 
		);
		$res = $el->Update($arFields['ID'], $arLoadProductArray);

      }

   }

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>