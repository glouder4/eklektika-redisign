<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$arVisual = $arResult['VISUAL'];

if (
    !$arResult['RECOMMEND'] &&
    !$arResult['NEW'] &&
    !$arResult['HIT'] &&
    !$arResult['SALE']
) return;
?>
<style>
.c-markers.c-markers-template-1 .widget-markers, .widget-markers {
    background: transparent !important;
    border-radius: 0px !important;
    font-size: 12px !important;
    letter-spacing: 0 !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
    line-height: 100% !important;
    padding: 9px 13px !important;
}
.c-markers.c-markers-template-1 .widget-markers-new, .widget-markers-new {
    color: #80E0A7 !important;
    border: 1px solid #80E0A7 !important;
}
.c-markers.c-markers-template-1 .widget-markers-sale, .widget-markers-sale {
    color: #EF4A85 !important;
    border: 1px solid #EF4A85 !important;
}
.c-markers.c-markers-template-1 .widget-markers-hit, .widget-markers-hit {
    color: #744A9E !important;
    border: 1px solid #744A9E !important;
}
@media (max-width:500px){

    .figma-tag svg{
        width: 26px;
        height: 26px;
    }
}
</style>

<div class="widget c-markers c-markers-template-1 tag-flex-markers" data-orientation="<?= $arVisual['ORIENTATION'] ?>" style="margin-bottom:20px; argin-left: -15px;flex-wrap: wrap;">
    <?php if ($arResult['RECOMMEND']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_RECOMMEND'), [
                'class' => 'widget-markers widget-markers-recommend'
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($arResult['SALE']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_SALE'), [
                'class' => 'widget-markers widget-markers-sale'
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($arResult['NEW']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_NEW'), [
                'class' => 'widget-markers widget-markers-new'
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($arResult['HIT']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_HIT'), [
                'class' => 'widget-markers widget-markers-hit'
            ]) ?>
        </div>
    <?php } ?>
</div>
