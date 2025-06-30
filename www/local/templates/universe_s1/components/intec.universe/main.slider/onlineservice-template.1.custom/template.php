<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;


/**
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arVisual = $arResult['VISUAL'];
$bSliderUse = count($arResult['ITEMS']) > 1;
$bDesktop = Core::$app->browser->isDesktop;
$bItemsFirst = true;
$arProductParameters = $arResult['PRODUCT']['PARAMETERS'];

/**
 * @var Closure $hText($arData, $bHeaderH1, $arForm)
 * @var Closure $vImage($arData)
 * @var Closure $vAdditional()
 */

$vText = include(__DIR__.'/parts/text.php');
$vPicture = include(__DIR__.'/parts/picture.php');
$vVideo = include(__DIR__.'/parts/video.php');
$vAdditional = include(__DIR__.'/parts/additional.php');
$vProduct = include(__DIR__.'/parts/product.php');
/*pre($arParams);
die();*/
?>
<div class="fullscreen-slider owl-carousel owl-theme" id="fullscreenSlider">
    <?php foreach ($arResult['ITEMS'] as $arItem) {
        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $arData = $arItem['DATA'];
        $sItemName = $arItem['NAME'];
        $sTag = !empty($arData['LINK']['VALUE']) && !$arData['BUTTON']['SHOW'] && !$arData['PRODUCT']['USE'] && !$arResult['FORM']['SHOW'] ? 'a' : 'div';
        $sPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);

        if (empty($sPicture))
            $sPicture = ArrayHelper::getValue($arItem, ['DETAIL_PICTURE', 'SRC']);

        if (empty($sPicture))
            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

        ?>
        <div class="fullscreen-slider--slide">
            <?php
            $sPictureMobile = $arData['MOBILE']['PICTURE']['USE'] ? $arData['MOBILE']['PICTURE']['VALUE']['SRC'] : $sPicture;
            //$mainPicture = $arData['PICTURE']['VALUE']['SRC'];
            ?>
            <div class="fullscreen-slider--slide_mobile-slide">
                <img src="<?=$sPictureMobile;?>" alt="Название слайда 1">
            </div>
            <div class="fullscreen-slider--slide_desktop-slide">
                <img src="<?=$sPicture;?>" alt="Название слайда 1">
            </div>

            <div class="fullscreen-slider--slide-data">
                <div class="fullscreen-slider--slide-data--title">
                    <span><?=$sItemName;?></span>
                </div>
                <div class="fullscreen-slider--slide-data--description">
                    <?php

                    $vText($arData, $bItemsFirst && $arVisual['HEADER']['H1'], $arResult['FORM']);
                    ?>
                    <?php //$vAdditional($arData) ?>
                </div>
                <?php if ($arData['BUTTON']['SHOW'] || $arResult['FORM']['SHOW']) {
                    $vTextButton = include(__DIR__.'/parts/buttons/onlineserice-view.1.php');
                    ?>
                    <?php if ($arData['BUTTON']['SHOW']) {

                        if (empty($arData['BUTTON']['TEXT']))
                            $arData['BUTTON']['TEXT'] = Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_BUTTON_TEXT_DEFAULT');

                        ?>
                        <?php $vTextButton(
                            $arData['LINK']['VALUE'],
                            $arData['LINK']['BLANK'],
                            $arData['BUTTON']['TEXT']
                        ) ?>
                    <?php } ?>
                    <?php if ($arResult['FORM']['SHOW']) { ?>
                        <?= Html::tag('div', $arResult['FORM']['BUTTON'], [
                            'class' => [
                                'widget-item-button',
                                'intec-cl-background' => [
                                    '',
                                    'light-hover'
                                ]
                            ],
                            'data' => [
                                'role' => 'form',
                                'name' => $arData['NAME']
                            ]
                        ]) ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php $bItemsFirst = false ?>
    <?php } ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#fullscreenSlider').owlCarousel({
            loop: <?=$arParams['SLIDER_LOOP'] == "Y";?>,
            margin: 0,
            nav: <?=$arParams['SLIDER_NAV_SHOW'] == "Y";?>,
            items: 1,
            dots: <?=$arParams['SLIDER_DOTS_SHOW'] == "Y";?>,
            lazyLoad: <?=$arParams['SLIDER_DOTS_SHOW'] == "Y";?>,
            autoplay: <?=$arParams['SLIDER_AUTO_USE'] == "Y";?>,
            autoplayTimeout: <?=$arParams['SLIDER_AUTO_TIME'];?>,
            autoplayHoverPause: <?=$arParams['SLIDER_AUTO_HOVER'] == "Y";?>,
            animateOut: 'fadeOut'
        });
    })
</script>