<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CModule::IncludeModule("intec.eklectika");
?>
<?php if ($arResult["ITEMS"]) {?>
	<div class="companies intec-grid intec-grid-wrap intec-grid-i-5">
		<?php foreach($arResult["ITEMS"] as $arItem) {?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="companies__item intec-grid-item-2" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<b><?echo $arItem["PROPERTIES"]["NAME_COMPANY"]["VALUE"]?></b>
				<?php if (intec\eklectika\advertising_agent\Client::isBossCompany($arItem['ID'])) {?>
					<a target="_blank" href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
						подробнее
					</a>
				<?php }?>
				<br />
				<table>
					<tr>
						<td>ИНН:</td>
						<td><?=$arItem["PROPERTIES"]["INN"]["VALUE"]?></td>
					</tr>
					<tr>
						<td>КПП:</td>
						<td><?=$arItem["PROPERTIES"]["KPP"]["VALUE"]?></td>
					</tr>
					<tr>
						<td>Юр. адрес:</td>
						<td><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></td>
					</tr>
					<tr>
						<td>Сайт:</td>
						<td><?=$arItem["PROPERTIES"]["WEBSITE"]["VALUE"]?></td>
					</tr>
					<tr>
						<td>Сотрудников:</td>
						<td><?=count($arItem["PROPERTIES"]["STAFFS"]["VALUE"])?></td>
					</tr>
				</table>				
			</div>
		<?php }?>
	</div>
<?php }?>