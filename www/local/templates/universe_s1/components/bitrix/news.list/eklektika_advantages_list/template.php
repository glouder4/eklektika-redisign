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
$VIEW_TEMPLATE = $arParams["VIEW_TEMPLATE"] ?? "BLUE";

$SVG_COLOR = "#57B0EA";
switch($VIEW_TEMPLATE){
    case "BLUE":
        $SVG_COLOR = "#57B0EA";
        break;
    case "PINK":
        $SVG_COLOR = "#57B0EA";
        break;
}
?>
<div class="service-description-list_items">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
    <div class="service-description-list--item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="service-description-list--item_icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#57B0EA" stroke-width="2" stroke-linecap="round"/>
                <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#57B0EA" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="service-description-list--item--data">
            <div class="service-description-list--item_title">
                <span><?echo $arItem["NAME"]?></span>
            </div>
            <div class="service-description-list--item_description">
                <p><?echo $arItem["PREVIEW_TEXT"];?></p>
            </div>
        </div>
    </div>
<?endforeach;?>

    <?php
        if( count($arResult["ITEMS"]) == 0 ){
            ?>
            <style>
                .service-description-list{
                    display: none;
                }
            </style>
    <?php
        }
    ?>
</div>
