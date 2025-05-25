<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arForm
 * @var array $arItem
 * @var array $arVisual
 */

$vTextButton = include(__DIR__.'/buttons/view.'.$arVisual['BUTTON']['VIEW'].'.php');

?>
<?php return function (&$arData, $bHeaderH1 = false, $arForm = []) use (&$arVisual, &$sTemplateId, &$vTextButton) { ?>

    <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arData['DESCRIPTION'])) { ?>
        <?= Html::tag('p', $arData['DESCRIPTION'], [
            'class' => 'widget-item-description',
            'data' => [
                'view' => $arVisual['DESCRIPTION']['VIEW']
            ]
        ]) ?>
    <?php } ?>
<?php } ?>