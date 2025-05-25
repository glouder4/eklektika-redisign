<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arSvg
 * @var bool $bDesktop
 * @var string $sTemplateId
 * @var string $sTag
 * @var CBitrixComponentTemplate $this
 */

?>
<?php return function (&$arItem) use (&$arResult, &$arVisual, $sTemplateId, $bDesktop, &$arSvg, &$sTag) {

    $sId = $sTemplateId.'_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    $arData = $arItem['DATA'];

    $bAdditional = $arData['PHONE']['SHOW'] || $arData['EMAIL']['SHOW'] || $arData['SOCIAL']['SHOW'] || $arResult['FORM']['ASK']['USE'];

    $sPicture = $arItem['PREVIEW_PICTURE'];
?>
        <div class="intec-grid-item-3 intec-grid-item-1000-2 intec-grid-item-768-1">
            <div class="news-list-item-text" data-role="item">
                <div class="news-list-item-text-content intec-grid intec-grid-wrap">
                    <div class="news-list-item-text-base intec-grid-item-1">
                        <?php if ($arData['POSITION']['SHOW']) { ?>
                            <?= Html::tag('div', $arData['POSITION']['VALUE'], [
                                'class' => 'news-list-item-position'
                            ]) ?>
                        <?php } ?>
                        <?= Html::tag($sTag, $arItem['NAME'], [
                            'class' => Html::cssClassFromArray([
                                'news-list-item-name' => true,
                                'intec-cl-text-hover' => $sTag === 'a'
                            ], true),
                            'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                            'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                            'data-role' => 'item.name'
                        ]) ?>
                    </div>
                    
                        <div class="intec-grid-item-1">
                            
                                <div class="news-list-item-text-additional-container">
                                    <?php if ($arData['PHONE']['SHOW']) { ?>
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'news-list-item-contact',
                                                'intec-grid' => [
                                                    '',
                                                    'nowrap',
                                                    'a-v-center',
                                                    'i-h-4'
                                                ]
                                            ]
                                        ]) ?>
                                            <div class="news-list-item-contact-icon intec-grid-item-auto">
                                                <?= $arSvg['CONTACTS']['PHONE'] ?>
                                            </div>
                                            <div class="news-list-item-contact-value intec-grid-item">
                                                <?= Html::tag('a', $arData['PHONE']['VALUE'], [
                                                    'class' => 'intec-cl-text-hover',
                                                    'href' => 'tel:'.$arData['PHONE']['HTML'],
                                                    'title' => $arData['PHONE']['VALUE']
                                                ]) ?>
                                            </div>
                                        <?= Html::endTag('div') ?>
                                    <?php } ?>
                                    <?php if ($arData['EMAIL']['SHOW']) { ?>
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'news-list-item-contact',
                                                'intec-grid' => [
                                                    '',
                                                    'nowrap',
                                                    'a-v-center',
                                                    'i-h-4'
                                                ]
                                            ]
                                        ]) ?>
                                            <div class="news-list-item-contact-icon intec-grid-item-auto">
                                                <?= $arSvg['CONTACTS']['EMAIL'] ?>
                                            </div>
                                            <div class="news-list-item-contact-value intec-grid-item">
                                                <?= Html::tag('a', $arData['EMAIL']['VALUE'], [
                                                    'class' => 'intec-cl-text-hover',
                                                    'href' => 'mailto:'.$arData['EMAIL']['VALUE'],
                                                    'title' => $arData['EMAIL']['VALUE']
                                                ]) ?>
                                            </div>
                                        <?= Html::endTag('div') ?>
                                    <?php } ?>
                                </div>
                            
                            <?php if ($arData['SOCIAL']['SHOW']) { ?>
                                <div class="news-list-item-text-additional-container">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid' => [
                                                '',
                                                'wrap',
                                                'a-v-center',
                                                'i-6'
                                            ]
                                        ]
                                    ]) ?>
                                        <?php foreach ($arData['SOCIAL']['VALUES'] as $key => $arSocial) { ?>
                                            <?php if (empty($arSocial)) continue ?>
                                            <div class="intec-grid-item-auto">
                                                <?php if ($key !== 'SKYPE') { ?>
                                                    <?= Html::tag('a', $arSvg['SOCIAL'][$key], [
                                                        'class' => [
                                                            'news-list-item-social',
                                                            'intec-cl-svg-path-fill-hover'
                                                        ],
                                                        'href' => $arSocial,
                                                        'target' => '_blank'
                                                    ]) ?>
                                                <?php } else { ?>
                                                    <?= Html::tag('a', $arSvg['SOCIAL']['SKYPE'], [
                                                        'class' => [
                                                            'news-list-item-social',
                                                            'intec-cl-svg-path-fill-hover'
                                                        ],
                                                        'href' => 'skype:'.$arSocial.'?'.$arVisual['SOCIAL']['SKYPE']['ACTION']
                                                    ]) ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['FORM']['ASK']['USE']) { ?>
                                <?php if (empty($arResult['FORM']['ASK']['BUTTON']['TEXT']))
                                    $arResult['FORM']['ASK']['BUTTON']['TEXT'] = Loc::getMessage('C_NEWS_LIST_STAFF_BLOCKS_1_TEMPLATE_FORM_ASK_BUTTON_TEXT_DEFAULT')
                                ?>
                                <div class="news-list-item-text-additional-button-container">
                                    <?= Html::tag('div', $arResult['FORM']['ASK']['BUTTON']['TEXT'], [
                                        'class' => [
                                            'news-list-item-text-additional-button',
                                            'intec-cl-background',
                                            'intec-cl-background-light-hover'
                                        ],
                                        'data-role' => 'item.button'
                                    ]) ?>
                                </div>
                            <?php } ?>
                        </div>
                    
                </div>
            </div>
        </div>
<?php } ?>