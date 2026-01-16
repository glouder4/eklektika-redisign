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
<div class="vacancies">
    <?foreach($arResult["ITEMS"] as $arItem):?>
       
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        
        <div class="vacancy" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <h2><?=$arItem['NAME']?></h2>
             <?php if (!empty($arItem['PROPERTIES']['SUBTITLE1']['VALUE']['TEXT'])): ?>
                <p class="subtitle">
                    <?=$arItem['PROPERTIES']['SUBTITLE1']['VALUE']['TEXT']?>
                </p>
            <?php endif; ?>
            <?php if (!empty($arItem['PROPERTIES']['SUBTITLE2']['VALUE']['TEXT'])): ?>
                <p class="subtitle">
                    <?=$arItem['PROPERTIES']['SUBTITLE2']['VALUE']['TEXT']?>
                </p>
            <?php endif; ?>
            <?php if (!empty($arItem['PROPERTIES']['CHTO_VHODIT']['VALUE'])): ?>
                <div class="duties">
                    <h3>Что будет входить в твою зону ответственности:</h3>
                    <ul>
                        <?php foreach ($arItem['PROPERTIES']['CHTO_VHODIT']['VALUE'] as $item): ?>
                            <li><?= $item ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($arItem['PROPERTIES']['PREDLAGAEM']['VALUE'])): ?>
                <div class="offers">
                    <h3>Мы же предлагаем:</h3>
                    <ul>
                        <?php foreach ($arItem['PROPERTIES']['PREDLAGAEM']['VALUE'] as $item): ?>
                            <li><?= $item ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <p class="send-cv">
                <?=$arItem['PREVIEW_TEXT']?>
            </p>
        </div>
    <?php endforeach; ?>   
</div>
