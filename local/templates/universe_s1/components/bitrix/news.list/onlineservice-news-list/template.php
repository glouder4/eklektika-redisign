<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

/**
 * @var Closure $tagsRender($arTags)
 */

?>

<div class="news-and-articles--list">
    <?php foreach ($arResult['ITEMS'] as $arItem) {

        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $sPicture = $arItem['PREVIEW_PICTURE'];

        if (empty($sPicture)){
            $sPicture = $arItem['DETAIL_PICTURE'];
        }
        $detailPicture = $arItem['DETAIL_PICTURE'];
        if (empty($detailPicture)){
            $detailPicture = $sPicture;
        }

        if (!empty($sPicture)) {
            $sPicture = CFile::ResizeImageGet($sPicture, [
                'width' => 350,
                'height' => 350
            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

            if (!empty($sPicture['src']))
                $sPicture = $sPicture['src'];
        }
        if (!empty($detailPicture)) {
            $detailPicture = CFile::ResizeImageGet($detailPicture, [
                'width' => 350,
                'height' => 350
            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

            if (!empty($detailPicture['src']))
                $detailPicture = $detailPicture['src'];
        }

        if (empty($sPicture))
            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

        ?>
        <article class="news-and-articles--item">
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news-and-articles--item_link">
                <div class="news-and-articles--item_image">
                    <img src="<?=$sPicture;?>" alt="<?echo $arItem["NAME"]?>">
                </div>
                <div class="news-and-articles--item_data">
                    <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                        <div class="news-and-articles--item_data-date">
                            <span><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
                        </div>
                    <?endif?>
                    <div class="news-and-articles--item_data-title_wrapper">
                        <div class="news-and-articles--item_data-title">
                            <?echo $arItem["NAME"]?>
                        </div>
                    </div>
                    <div class="news-and-articles--item_data--preview_text-wrapper">
                        <p class="news-and-articles--item_data--preview_text">
                            <?echo $arItem["PREVIEW_TEXT"];?>
                        </p>
                    </div>
                </div>
            </a>
        </article>
    <?php } ?>

    <div class="news-and-articles--action">
        <a data-fancybox href="#news-action-popup" rel="nofollow" class="news-and-articles--action_open-popup">Показать еще</a>
    </div>
</div>
