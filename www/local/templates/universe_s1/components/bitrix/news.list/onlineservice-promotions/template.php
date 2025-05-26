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
<div id="promotions" class="promotions">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

    $previewPicture = $arItem["PREVIEW_PICTURE"]["SRC"];
    $previewAlt = $arItem["PREVIEW_PICTURE"]["ALT"];
    $previewTitle = $arItem["PREVIEW_PICTURE"]["TITLE"];

    $detailPicture = $arItem["DETAIL_PICTURE"]["SRC"];
    $detailAlt = $arItem["DETAIL_PICTURE"]["ALT"];
    $detailTitle = $arItem["DETAIL_PICTURE"]["TITLE"];

    $picture = $previewPicture;
    $alt = $previewAlt;
    $title = $previewTitle;

    if( empty($previewPicture) ){
        $picture = $detailPicture;
        $alt = $detailAlt;
        $title = $detailTitle;
    }
    ?>
    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="promotion--item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="promotion--item--body">
            <div class="promotion--item_image">
                <img
                        class=""
                        border="0"
                        src="<?=$picture?>"
                        alt="<?=$alt?>"
                        title="<?=$title?>"
                />
            </div>
            <div class="promotion--item_title">
                <span><?=$arItem["NAME"];?></span>
            </div>
        </div>
        <div class="promotion--item--action">
            <div class="promotion--item--action_link">
                <span>13 марта - 5 апреля</span>
                <div class="promotion--item--action_link--arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
                        <path d="M1.25415 9L4.59961 5L1.25415 1" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </a>
<?endforeach;?>
    <div class="promotions-action">
        <a data-fancybox href="#promotions-action-popup" rel="nofollow" class="promotions-action_open-popup">Показать еще</a>
    </div>
</div>