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
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'widget-item-text' => true,
            'intec-grid-item' => [
                '' => !$arData['TEXT']['HALF'],
                'auto' => $arData['TEXT']['HALF'],
                '768-1' => $arData['TEXT']['HALF'],
                'a-center' => true
            ]
        ], true),
        'data' => [
            'align' => $arData['TEXT']['ALIGN']
        ]
    ]) ?>
	<div class="widget-item-text-content">
        <?php if ($arVisual['HEADER']['OVER']['SHOW'] && !empty($arData['OVER'])) { ?>
            <?= Html::tag('div', $arData['OVER'], [
                'class' => 'widget-item-header-over',
                'data' => [
                    'view' => $arVisual['HEADER']['OVER']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arVisual['HEADER']['SHOW'] && !empty($arData['HEADER'])) { ?>
            <?= Html::tag($bHeaderH1 ? 'h1' : 'div', $arData['HEADER'], [
                'class' => 'widget-item-header',
                'data' => [
                    'view' => $arVisual['HEADER']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($arData['DESCRIPTION'])) { ?>
            <?= Html::tag('div', $arData['DESCRIPTION'], [
                'class' => 'widget-item-description',
                'data' => [
                    'view' => $arVisual['DESCRIPTION']['VIEW']
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arData['BUTTON']['SHOW'] || $arForm['SHOW']) { ?>
            <?= Html::beginTag('div', [
                'class' => 'widget-item-buttons',
                'data' => [
                    'view' => $arVisual['BUTTON']['VIEW']
                ]
            ]) ?>
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
                <?php if ($arForm['SHOW']) { ?>
                    <?= Html::tag('div', $arForm['BUTTON'], [
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
            <?= Html::endTag('div') ?>
        <?php } ?>
	</div>
	<div class="slider-navigation-wrapper intec-grid-a-h-768-start intec-grid intec-grid-a-h-end intec-grid-i-h-5">
		<div class="intec-grid-item-auto">
			<a class="carousel-prev">
				<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.292893 7.29289C-0.0976315 7.68342 -0.0976315 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928932C7.68054 0.538408 7.04738 0.538408 6.65685 0.928932L0.292893 7.29289ZM15 7L1 7V9L15 9V7Z" fill="#DB0032"/>
				</svg>
			</a>
		</div>
		<div class="intec-grid-item-auto">
			<a class="carousel-next">
				<svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L8.34315 15.0711C7.95262 15.4616 7.31946 15.4616 6.92893 15.0711C6.53841 14.6805 6.53841 14.0474 6.92893 13.6569L12.5858 8L6.92893 2.34315C6.53841 1.95262 6.53841 1.31946 6.92893 0.928932C7.31946 0.538408 7.95262 0.538408 8.34315 0.928932L14.7071 7.29289ZM0 7L14 7V9L0 9L0 7Z" fill="#DB0032"/>
				</svg>
			</a>
		</div>
	</div>
    <?= Html::endTag('div') ?>
<?php } ?>