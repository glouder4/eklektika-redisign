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
?>
<?php if ($arResult["ITEMS"]) {?>
	<div class="agent-news">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="agent-news__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a target="_blank" href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
				<?php if($arItem["PREVIEW_TEXT"]) {?>
					<?echo TruncateText(strip_tags($arItem["PREVIEW_TEXT"]), 150);?>
				<?}?>
				<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
					<div style="clear:both"></div>
				<?endif?>		
			</div>
		<?endforeach;?>
		<a target="_blank" 
			class="sale-personal-section-claims-button intec-ui intec-ui-control-button intec-ui-mod-transparent intec-ui-mod-round-2 intec-ui-scheme-current" 
			href="<?=str_replace("#SITE_DIR#", "", $arResult["LIST_PAGE_URL"]);?>">
		посмотреть все
		</a>
	</div>
<?php }?>