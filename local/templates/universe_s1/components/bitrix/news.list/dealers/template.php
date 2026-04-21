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

/**
 * Раскладывает города по N колонкам, стараясь уравнять «высоту» по числу дилеров.
 * Важно: входной $blocks уже отсортирован по SORT — обход идёт в этом порядке.
 *
 * @param array<int, array{SECTION: array, ELEMENTS: array}> $blocks
 * @return array<int, array<int, array{SECTION: array, ELEMENTS: array}>>
 */
function dealersDistributeCityBlocksToColumns(array $blocks, int $columnCount = 4): array
{
    $columns = [];
    for ($i = 0; $i < $columnCount; $i++) {
        $columns[$i] = [];
    }

    $loads = array_fill(0, $columnCount, 0);

    foreach ($blocks as $block) {
        $weight = 3 + count($block['ELEMENTS']);

        $bestCol = 0;
        $bestLoad = $loads[0];
        for ($c = 1; $c < $columnCount; $c++) {
            if ($loads[$c] < $bestLoad) {
                $bestLoad = $loads[$c];
                $bestCol = $c;
            }
        }

        $columns[$bestCol][] = $block;
        $loads[$bestCol] += $weight;
    }

    return $columns;
}

// Получаем разделы первого уровня
$sections = [];
$sectionFilter = [
    'IBLOCK_ID' => $iblockId,
    'ACTIVE' => 'Y',
    'DEPTH_LEVEL' => 1, // Только первого уровня
];

// Порядок стран, городов и дилеров — по полю SORT инфоблока (см. первый аргумент GetList).
$res = CIBlockSection::GetList(
    ['SORT' => 'ASC'],
    $sectionFilter,
    false,
    ['ID', 'NAME', 'CODE', 'DEPTH_LEVEL', 'SORT']
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
        ['ID', 'NAME', 'CODE', 'SORT']
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
            ['ID', 'NAME', 'CODE', 'SORT', 'PREVIEW_TEXT','PROPERTY_DEALER_LINK']
        );
        
        while ($element = $elementRes->GetNext()) {
            $elements[] = $element;
        }

        $subSectionName = trim((string)$subSection['NAME']);
        if ($subSectionName === '' || empty($elements)) {
            continue;
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

$countryGroups = [];
$singleCityBuffer = [];

foreach ($sections as $sectionData) {
    $subSectionsCount = count($sectionData['SUBSECTIONS']);

    if ($subSectionsCount <= 1) {
        $singleCityBuffer[] = $sectionData;
        continue;
    }

    while (!empty($singleCityBuffer)) {
        $countryGroups[] = [
            'TYPE' => 'single_row',
            'COUNTRIES' => array_splice($singleCityBuffer, 0, 2)
        ];
    }

    $countryGroups[] = [
        'TYPE' => 'regular',
        'COUNTRIES' => [$sectionData]
    ];
}

while (!empty($singleCityBuffer)) {
    $countryGroups[] = [
        'TYPE' => 'single_row',
        'COUNTRIES' => array_splice($singleCityBuffer, 0, 2)
    ];
}
?>

<div class="countries-container">
    <?php foreach ($countryGroups as $group):
        $isCompactRow = $group['TYPE'] === 'single_row';
    ?>
        <div class="countries-row <?= $isCompactRow ? 'compact-countries-row' : '' ?>">
            <?php foreach ($group['COUNTRIES'] as $sectionData):
                $mainSection = $sectionData['SECTION'];
                $subSections = $sectionData['SUBSECTIONS'];
            ?>
                <div class="country-block <?= $isCompactRow ? 'country-block-compact' : '' ?>">
                    <div class="main-section">
                        <?= htmlspecialcharsbx($mainSection['NAME']) ?>
                    </div>

                    <?php if (!empty($subSections)): ?>
                        <?php
                        $cityBlocks = [];
                        foreach ($subSections as $subSectionData) {
                            $subSection = $subSectionData['SECTION'];
                            $elements = $subSectionData['ELEMENTS'];
                            if (empty($subSection['NAME']) || empty($elements)) {
                                continue;
                            }
                            $cityBlocks[] = [
                                'SECTION' => $subSection,
                                'ELEMENTS' => $elements,
                            ];
                        }
                        $cityColumns = dealersDistributeCityBlocksToColumns($cityBlocks, 4);
                        ?>
                        <div class="cities-row">
                            <?php for ($col = 0; $col < 4; $col++): ?>
                                <div class="cities-column cities-column-<?= $col + 1 ?>">
                                    <?php foreach ($cityColumns[$col] as $subSectionData):
                                        $subSection = $subSectionData['SECTION'];
                                        $elements = $subSectionData['ELEMENTS'];
                                    ?>
                                        <div class="sub-section city-block">
                                            <div class="sub-section-title">
                                                <?= htmlspecialcharsbx($subSection['NAME']) ?>
                                            </div>

                                            <ul class="elements-list">
                                                <?php foreach ($elements as $element): ?>
                                                    <?php
                                                    $link = false;
                                                    if (isset($element['PROPERTY_DEALER_LINK_VALUE']) && !empty($element['PROPERTY_DEALER_LINK_VALUE']) && ($element['PROPERTY_DEALER_LINK_VALUE'] != " ")) {
                                                        $link = $element['PROPERTY_DEALER_LINK_VALUE'];
                                                    }
                                                    ?>
                                                    <li class="element-item">
                                                        <?php if ($link): ?>
                                                            <a href="<?= $link ?>" target="_blank" rel="noindex, nofollow" class="element-name hoverable">
                                                                <?= htmlspecialcharsbx($element['NAME']) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <?= htmlspecialcharsbx($element['NAME']) ?>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-items">Нет подразделов</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
