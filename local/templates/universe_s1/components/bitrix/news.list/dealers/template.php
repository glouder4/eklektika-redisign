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
<?php
// Получаем ID инфоблока
$iblockId = $arParams['IBLOCK_ID'];

// Получаем разделы первого уровня
$sections = [];
$sectionFilter = [
    'IBLOCK_ID' => $iblockId,
    'ACTIVE' => 'Y',
    'DEPTH_LEVEL' => 1, // Только первого уровня
];

$res = CIBlockSection::GetList(
    ['SORT' => 'ASC'],
    $sectionFilter,
    false,
    ['ID', 'NAME', 'CODE', 'DEPTH_LEVEL']
);

while ($section = $res->GetNext()) {
    $sectionId = $section['ID'];
    
    // Получаем подразделы (второго уровня)
    $subSections = [];
    $subSectionRes = CIBlockSection::GetList(
        ['SORT' => 'ASC'],
        [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'SECTION_ID' => $sectionId,
            'DEPTH_LEVEL' => 2
        ],
        false,
        ['ID', 'NAME', 'CODE']
    );
    
    while ($subSection = $subSectionRes->GetNext()) {
        $subSectionId = $subSection['ID'];
        
        // Получаем элементы в подразделе
        $elements = [];
        $elementRes = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
                'SECTION_ID' => $subSectionId,
                'INCLUDE_SUBSECTIONS' => 'N'
            ],
            false,
            false,
            ['ID', 'NAME', 'CODE', 'PREVIEW_TEXT','PROPERTY_DEALER_LINK']
        );
        
        while ($element = $elementRes->GetNext()) {
            $elements[] = $element;
        }
        
        $subSections[] = [
            'SECTION' => $subSection,
            'ELEMENTS' => $elements
        ];
    }
    
    $sections[] = [
        'SECTION' => $section,
        'SUBSECTIONS' => $subSections
    ];
}
?>

<div class="three-columns-container">
    <?php
    // Разделяем массив на три примерно равные части
    $chunks = array_chunk($sections, ceil(count($sections) / 3));
    
    foreach ($chunks as $columnSections): ?>
        <div class="column">
            <?php foreach ($columnSections as $sectionData): 
                $mainSection = $sectionData['SECTION'];
                $subSections = $sectionData['SUBSECTIONS'];
            ?>
                <div class="main-section">
                    <?= htmlspecialcharsbx($mainSection['NAME']) ?>
                </div>
                
                <?php if (!empty($subSections)): ?>
                    <?php foreach ($subSections as $subSectionData): 
                        $subSection = $subSectionData['SECTION'];
                        $elements = $subSectionData['ELEMENTS'];
                    ?>
                        <div class="sub-section">
                            <div class="sub-section-title">
                                <?= htmlspecialcharsbx($subSection['NAME']) ?>
                            </div>
                            
                            <?php if (!empty($elements)): ?>
                                <ul class="elements-list">
                                    <?php foreach ($elements as $element): ?>
                                        <?
                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                                        $link = false;
                                        if( isset($element['PROPERTY_DEALER_LINK_VALUE']) && !empty(($element['PROPERTY_DEALER_LINK_VALUE'])) && ($element['PROPERTY_DEALER_LINK_VALUE']) != "" && ($element['PROPERTY_DEALER_LINK_VALUE']) != " " ){
                                            $link = $element['PROPERTY_DEALER_LINK_VALUE'];
                                        }
                                        ?>
                                        <li class="element-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                            <?php
                                                if($link){ ?>
                                                    <a href="<?=$link ? $link : "javascript::void(0);"?>" <?=($link) ? "target='_blank'" : "";?> rel="noindex, nofollow" class="element-name <?=$link ? "hoverable" : null;?>">
                                                        <?= htmlspecialcharsbx($element['NAME']) ?>
                                                    </a>
                                                <?php }
                                                else{?>
                                                    <?= htmlspecialcharsbx($element['NAME']) ?>
                                                    <?php }
                                            ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="no-items">Нет элементов</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-items">Нет подразделов</div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
