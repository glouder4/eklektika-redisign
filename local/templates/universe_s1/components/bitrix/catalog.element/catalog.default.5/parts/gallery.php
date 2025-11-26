<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var bool $bSkuDynamic
 */


// global $USER;
// if ($USER->IsAuthorized() && $USER->IsAdmin()):
// echo '<pre>';
// print_r($arResult['MARKS']['VALUES']);
// echo'</pre>';
// endif;
if (!empty($arResult['MARKS']['VALUES'])) {
    $sMarkersHtml = '
        <div class="catalog-element-gallery-marks">
            <div class="catalog-element-marks">
                '.$APPLICATION->IncludeComponent(
                    'intec.universe:main.markers',
                    'template.2', [
                        'HIT' => $arResult['MARKS']['VALUES']['HIT'] ?? null,
                        'SALE' => $arResult['MARKS']['VALUES']['SALE'] ?? null,
                        'NEW' => $arResult['MARKS']['VALUES']['NEW'] ?? null,
                        'RECOMMEND' => $arResult['MARKS']['VALUES']['RECOMMEND'] ?? null,
                        'ORIENTATION' => 'horizontal'
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                ).'
            </div>
        </div>
    ';
}
?>

<?php $vGallery = function (&$arItem, $bOffer = false) use (&$arVisual, &$arResult, &$arSvg, &$sMarkersHtml) {
    if ($bOffer) {
        $arVideos = $arResult['GALLERY_VIDEO']['OFFERS'][$arItem['ID']];

        if (empty($arVideos) && empty($arItem['GALLERY']['VALUES']))
            return;
    } else {
        $arVideos = $arResult['GALLERY_VIDEO']['PRODUCT'];
    }

    $bCarousel = false;
    $iCountPictures = 0;

    if (!empty($arItem['GALLERY']['VALUES'])) {
        $iCountPictures += count($arItem['GALLERY']['VALUES']);
        $bCarousel = $iCountPictures > 1;
    }

    if (!empty($arVideos)) {
        $iCountPictures += count($arVideos);
        $bCarousel = $iCountPictures > 1;
    }

    if ($arVisual['MAIN_VIEW'] == 1) {
        $arPictureSizes = [
            'width' => 600,
            'height' => 600
        ];
    } else if ($arVisual['MAIN_VIEW'] == 2) {
        $arPictureSizes = [
            'width' => 1000,
            'height' => 1000
        ];
    } else if ($arVisual['MAIN_VIEW'] == 3) {
        $arPictureSizes = [
            'width' => 1200,
            'height' => 1200
        ];
    }
?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-gallery',
        'data' => [
            'role' => 'gallery',
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <div class="catalog-element-gallery-layout">
            <?php if ($arVisual['GALLERY']['PREVIEW'] && $iCountPictures > 1) { ?>
                <div class="catalog-element-gallery-preview-vertical" data-role="gallery.preview">
                    <div class="catalog-element-gallery-preview-vertical-slider owl-carousel" data-role="gallery.preview.slider">
                        <?php $bPictureFirst = true; ?>
                        <?php foreach ($arItem['GALLERY']['VALUES'] as $arPicture) {
                            $sPicture = CFile::ResizeImageGet($arPicture, [
                                'width' => 80,
                                'height' => 80
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
                            ?>
                                <?= Html::beginTag('div', [
                                    'class' => 'catalog-element-gallery-preview-vertical-slider-item',
                                    'data' => [
                                        'role' => 'gallery.preview.slider.item',
                                        'active' => $bPictureFirst ? 'true' : 'false'
                                    ]
                                ]) ?>
                                    <div class="catalog-element-gallery-preview-vertical-slider-item-picture intec-ui-picture">
                                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture['src'], [
                                            'alt' => $arItem['GALLERY']['PROPERTIES']['ALT'],
                                            'title' => $arItem['GALLERY']['PROPERTIES']['TITLE'],
                                            'loading' => 'lazy',
                                            'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'data-original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture['src'] : null
                                        ]) ?>
                                        <?php if ($arPicture['CONTENT_TYPE'] === 'image/gif') { ?>
                                            <?= $arSvg['GIF'] ?>
                                        <?php } ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                                <?php $bPictureFirst = false; ?>
                         <?php } ?>

                        <?php foreach ($arVideos as $sKeyVideo => $arVideo) { ?>
                            <?= Html::beginTag('div', [
                                'class' => 'catalog-element-gallery-preview-vertical-slider-item',
                                'data' => [
                                    'role' => 'gallery.preview.slider.item',
                                    'active' => $bPictureFirst ? 'true' : 'false'
                                ]
                            ]) ?>
                                <?php if (!empty($arVideo['LINK'])) {
                                    $arVideoInfo = youtube_video($arVideo['LINK']);
                                    $sVideoPreview = !empty($arVideoInfo['sddefault']) ? $arVideoInfo['sddefault'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                                ?>
                                    <div class="catalog-element-gallery-preview-vertical-slider-item-picture intec-ui-picture intec-cl-svg-path-stroke">
                                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sVideoPreview, [
                                            'alt' => Html::encode($arResult['NAME']),
                                            'title' => Html::encode($arResult['NAME']),
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sVideoPreview : null
                                            ]
                                        ]) ?>
                                        <?= $arSvg['PLAY'] ?>
                                    </div>
                                <?php } else { ?>
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'catalog-element-gallery-preview-vertical-slider-item-picture-stub',
                                            'intec-ui-picture',
                                            'intec-cl-svg-path-stroke'
                                        ],
                                        'data' => [
                                            'role' => 'video.stub',
                                            'id' => 'video-'.$arItem['ID'].$sKeyVideo
                                        ]
                                    ]) ?>
                                        <img src="<?= SITE_TEMPLATE_PATH.'/images/picture.missing.png' ?>" data-role="canvas.stub" />
                                        <?= $arSvg['PLAY'] ?>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                                <?php $bPictureFirst = false; ?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?= Html::beginTag('div', [
                'class' => 'catalog-element-gallery-pictures',
                'data' => [
                    'role' => 'gallery.pictures',
                    'action' => $arVisual['GALLERY']['ACTION'],
                    'zoom' => $arVisual['GALLERY']['ZOOM'] ? 'true' : 'false'
                ]
            ]) ?>
            <?
            $figmaTagsData = [
                '33386' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><path d="M20.058 13.6348C22.1533 16.5891 20.5504 19.9729 19.7752 21.2929C19.6615 21.4775 19.5088 21.6351 19.3278 21.7545C19.1469 21.874 18.942 21.9524 18.7275 21.9843C17.2085 22.2462 13.4266 22.5396 11.3942 19.5853C9.39325 16.7986 9.47706 11.8958 9.65516 9.55956C9.66218 9.38551 9.71044 9.21561 9.79596 9.06385C9.88148 8.9121 10.0018 8.78282 10.1471 8.68667C10.2923 8.59051 10.4583 8.53023 10.6315 8.51078C10.8046 8.49134 10.9798 8.51328 11.1428 8.5748C13.3952 9.22433 18.0885 10.8481 20.058 13.6348Z" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.9971 12.6602C15.3967 15.488 18.5529 20.0784 20.333 23.332" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'title' => 'ЭКО'
                ],
                '33387' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#744A9E"/><path d="M21.2468 19.4493C21.2468 19.9134 21.0625 20.3586 20.7343 20.6868C20.4061 21.015 19.961 21.1993 19.4968 21.1993C19.0327 21.1993 18.5876 21.015 18.2594 20.6868C17.9312 20.3586 17.7468 19.9134 17.7468 19.4493C17.7468 18.9851 17.9312 18.54 18.2594 18.2118C18.5876 17.8836 19.0327 17.6992 19.4968 17.6992C19.961 17.6992 20.4061 17.8836 20.7343 18.2118C21.0625 18.54 21.2468 18.9851 21.2468 19.4493ZM14.2468 19.4493C14.2468 19.9134 14.0625 20.3586 13.7343 20.6868C13.4061 21.015 12.961 21.1993 12.4968 21.1993C12.0327 21.1993 11.5876 21.015 11.2594 20.6868C10.9312 20.3586 10.7468 19.9134 10.7468 19.4493C10.7468 18.9851 10.9312 18.54 11.2594 18.2118C11.5876 17.8836 12.0327 17.6992 12.4968 17.6992C12.961 17.6992 13.4061 17.8836 13.7343 18.2118C14.0625 18.54 14.2468 18.9851 14.2468 19.4493Z" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.75 19.4503H14.25M9 10H16C16.9898 10 17.4847 10 17.792 10.308C18.1 10.6146 18.1 11.1095 18.1 12.1001V18.0503M18.45 11.7501H19.7107C20.2917 11.7501 20.5822 11.7501 20.823 11.8866C21.0638 12.0224 21.2129 12.2716 21.5118 12.77L22.7011 14.7511C22.8495 14.9989 22.9237 15.1235 22.9622 15.2607C23 15.3986 23 15.5428 23 15.8319V17.7003C23 18.3548 23 18.6817 22.8593 18.9253C22.7671 19.0849 22.6346 19.2175 22.475 19.3096C22.2314 19.4503 21.9045 19.4503 21.25 19.4503M9 16.3002V17.7003C9 18.3548 9 18.6817 9.1407 18.9253C9.23285 19.0849 9.36539 19.2175 9.525 19.3096C9.7686 19.4503 10.0955 19.4503 10.75 19.4503M9 12.1001H13.2M9 14.2001H11.8" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'title' => 'В пути'
                ],
                '33388' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#EF4A85"/><g clip-path="url(#clip0_1711_12132)"><path d="M21.28 17.3912L17.39 21.2812C17.2494 21.4217 17.0588 21.5006 16.86 21.5006C16.6613 21.5006 16.4706 21.4217 16.33 21.2812L8.61001 13.5612C8.57122 13.5242 8.54121 13.4789 8.52218 13.4287C8.50315 13.3785 8.49557 13.3247 8.50001 13.2712L9.09001 9.44123C9.09483 9.34997 9.13325 9.26371 9.19787 9.19909C9.26249 9.13447 9.34875 9.09605 9.44001 9.09123L13.27 8.50123C13.3235 8.49679 13.3773 8.50437 13.4275 8.5234C13.4776 8.54243 13.5229 8.57244 13.56 8.61123L21.28 16.3312C21.4205 16.4719 21.4994 16.6625 21.4994 16.8612C21.4994 17.06 21.4205 17.2506 21.28 17.3912V17.3912Z" stroke="#EF4A85" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.1101 12.6113C12.3863 12.6113 12.6101 12.3875 12.6101 12.1113C12.6101 11.8352 12.3863 11.6113 12.1101 11.6113C11.834 11.6113 11.6101 11.8352 11.6101 12.1113C11.6101 12.3875 11.834 12.6113 12.1101 12.6113Z" stroke="#EF4A85" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12132"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
                    'title' => 'Распродажа'
                ],
                '33389' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF9900"/><path d="M21.5 13.5C21.5 13.3674 21.4473 13.2402 21.3536 13.1464C21.2598 13.0527 21.1326 13 21 13H20V12C20 11.7348 19.8946 11.4804 19.7071 11.2929C19.5196 11.1054 19.2652 11 19 11H9.5C9.23478 11 8.98043 11.1054 8.79289 11.2929C8.60536 11.4804 8.5 11.7348 8.5 12V18C8.5 18.2652 8.60536 18.5196 8.79289 18.7071C8.98043 18.8946 9.23478 19 9.5 19H19C19.2652 19 19.5196 18.8946 19.7071 18.7071C19.8946 18.5196 20 18.2652 20 18V17H21C21.1326 17 21.2598 16.9473 21.3536 16.8536C21.4473 16.7598 21.5 16.6326 21.5 16.5V13.5Z" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'title' => 'Быстрая зарядка'
                ],
                '33390' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><path d="M14.9999 20.2489C15.7234 20.2489 16.3099 19.6624 16.3099 18.9389C16.3099 18.2154 15.7234 17.6289 14.9999 17.6289C14.2764 17.6289 13.6899 18.2154 13.6899 18.9389C13.6899 19.6624 14.2764 20.2489 14.9999 20.2489Z" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.53 16.0001C12.8552 15.6663 13.2439 15.401 13.6733 15.2198C14.1027 15.0386 14.564 14.9453 15.03 14.9453C15.4961 14.9453 15.9574 15.0386 16.3867 15.2198C16.8161 15.401 17.2049 15.6663 17.53 16.0001" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.3601 14.3089C10.9688 13.6966 11.6926 13.2107 12.4897 12.8792C13.2869 12.5476 14.1417 12.377 15.0051 12.377C15.8685 12.377 16.7233 12.5476 17.5205 12.8792C18.3176 13.2107 19.0414 13.6966 19.6501 14.3089" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 12.4492C9.35343 11.5954 10.3667 10.918 11.482 10.4559C12.5973 9.99372 13.7928 9.75586 15 9.75586C16.2072 9.75586 17.4027 9.99372 18.518 10.4559C19.6333 10.918 20.6466 11.5954 21.5 12.4492" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'title' => 'Беспроводная зарядка'
                ],
                '33391' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><g clip-path="url(#clip0_1711_12181)"><path d="M11 13H9.5C9.23478 13 8.98043 13.1054 8.79289 13.2929C8.60536 13.4804 8.5 13.7348 8.5 14V16C8.5 16.2652 8.60536 16.5196 8.79289 16.7071C8.98043 16.8946 9.23478 17 9.5 17H11V13Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 16.9994L14.91 19.8094C15.0549 19.9096 15.2236 19.9698 15.3992 19.9838C15.5748 19.9979 15.751 19.9653 15.91 19.8894C16.0824 19.8119 16.2293 19.6872 16.3337 19.5297C16.4382 19.3722 16.4958 19.1883 16.5 18.9994V10.9994C16.5043 10.8198 16.4601 10.6424 16.3721 10.4858C16.2841 10.3292 16.1556 10.1991 16 10.1094C15.841 10.0334 15.6648 10.0009 15.4892 10.0149C15.3136 10.029 15.1449 10.0891 15 10.1894L11 12.9994" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.5 12C21.1953 12.8406 21.5519 13.9104 21.5 15C21.485 16.235 21.1397 17.4435 20.5 18.5" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 13.5C18.8476 13.9203 19.0259 14.4552 19 15C19.0259 15.5448 18.8476 16.0797 18.5 16.5" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12181"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
                    'title' => 'Мощный звук'
                ],
                '33392' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF9900"/><g clip-path="url(#clip0_1711_12188)"><path d="M18.4995 15.9994C18.5018 15.3754 18.3373 14.762 18.0228 14.2229C17.7084 13.6838 17.2555 13.2386 16.7112 12.9334C16.1668 12.6282 15.5508 12.4741 14.9268 12.4871C14.3029 12.5 13.6938 12.6796 13.1625 13.0071C12.6313 13.3347 12.1974 13.7983 11.9056 14.35C11.6138 14.9017 11.4749 15.5213 11.5031 16.1447C11.5313 16.7682 11.7258 17.3727 12.0662 17.8958C12.4067 18.4188 12.8808 18.8413 13.4395 19.1194V20.8394C13.4421 20.9411 13.4843 21.0378 13.5572 21.1088C13.6301 21.1797 13.7278 21.2195 13.8295 21.2194H16.1695C16.2712 21.2195 16.3689 21.1797 16.4418 21.1088C16.5146 21.0378 16.5569 20.9411 16.5595 20.8394V19.0894C17.1357 18.8026 17.6217 18.3624 17.964 17.8172C18.3062 17.2721 18.4915 16.6431 18.4995 15.9994V15.9994Z" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.9995 8.80859V10.3086" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9995 10.2383L17.9395 11.3083" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M21.2798 13.3398H19.7798" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 10.2383L12.06 11.3083" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.71973 13.3398H10.2197" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12188"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
                    'title' => 'Подсветка'
                ],
                '33393' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#3636ED"/><g clip-path="url(#clip0_1711_12197)"><path d="M20 17C20 13.5 15 8.5 15 8.5C15 8.5 10 13.5 10 17C10.0698 18.2585 10.6348 19.4382 11.5717 20.2814C12.5086 21.1246 13.7411 21.5627 15 21.5C16.2589 21.5627 17.4914 21.1246 18.4283 20.2814C19.3652 19.4382 19.9302 18.2585 20 17V17Z" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 8.5V21.5" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 20.9014V11.4414" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12197"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
                    'title' => 'Влагостойкость'
                ],
                '33394' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#57B0EA"/><g clip-path="url(#clip0_1711_12137)"><path d="M19.25 17V10C19.25 9.60218 19.092 9.22064 18.8107 8.93934C18.5294 8.65804 18.1478 8.5 17.75 8.5V8.5C17.3522 8.5 16.9706 8.65804 16.6893 8.93934C16.408 9.22064 16.25 9.60218 16.25 10V17C15.8302 17.3148 15.5202 17.7537 15.3637 18.2546C15.2073 18.7554 15.2124 19.2928 15.3783 19.7906C15.5442 20.2883 15.8626 20.7213 16.2882 21.0281C16.7139 21.3349 17.2253 21.5 17.75 21.5C18.2747 21.5 18.7861 21.3349 19.2118 21.0281C19.6374 20.7213 19.9558 20.2883 20.1217 19.7906C20.2876 19.2928 20.2927 18.7554 20.1363 18.2546C19.9798 17.7537 19.6698 17.3148 19.25 17Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.1426 9.21289L10.9997 10.07L11.8569 9.21289" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.21387 12.8549L9.07101 11.9978L8.21387 11.1406" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.8569 14.7849L10.9997 13.9277L10.1426 14.7849" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.7849 11.1406L12.9277 11.9978L13.7849 12.8549" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.49951 10.5L10.1424 11.1429" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.1424 12.8555L9.49951 13.4983" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.4993 10.5L11.8564 11.1429" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.8564 12.8555L12.4993 13.4983" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.9995 10.0703V13.9275" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.0708 12H12.9279" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12137"><rect width="14" height="14" fill="white" transform="translate(7.75 8)"/></clipPath></defs></svg>',
                    'title' => 'Сохраняет холод 5 часов'
                ],
                '33395' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><path d="M20.25 17.5V10.5C20.25 10.1022 20.092 9.72064 19.8107 9.43934C19.5294 9.15804 19.1478 9 18.75 9V9C18.3522 9 17.9706 9.15804 17.6893 9.43934C17.408 9.72064 17.25 10.1022 17.25 10.5V17.5C16.8302 17.8148 16.5202 18.2537 16.3637 18.7546C16.2073 19.2554 16.2124 19.7928 16.3783 20.2906C16.5442 20.7883 16.8626 21.2213 17.2882 21.5281C17.7139 21.8349 18.2253 22 18.75 22C19.2747 22 19.7861 21.8349 20.2118 21.5281C20.6374 21.2213 20.9558 20.7883 21.1217 20.2906C21.2876 19.7928 21.2927 19.2554 21.1363 18.7546C20.9798 18.2537 20.6698 17.8148 20.25 17.5Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.499 13.75C12.1894 13.75 12.749 13.1904 12.749 12.5C12.749 11.8096 12.1894 11.25 11.499 11.25C10.8087 11.25 10.249 11.8096 10.249 12.5C10.249 13.1904 10.8087 13.75 11.499 13.75Z" stroke="#FF5B36" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.7529 12.5L13.5829 13.36L13.8029 14.8L12.3629 14.58L11.5029 15.75L10.6429 14.58L9.20293 14.8L9.42293 13.36L8.25293 12.5L9.42293 11.64L9.20293 10.2L10.6429 10.42L11.5029 9.25L12.3629 10.42L13.8029 10.2L13.5829 11.64L14.7529 12.5Z" stroke="#FF5B36" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'title' => 'Держит тепло 9 часов'
                ],
                '33396' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#222222"/><g clip-path="url(#clip0_1711_12216)"><path d="M18.1802 15.2491C18.1811 14.7205 18.3502 14.2059 18.6631 13.7799C18.9759 13.3538 19.4162 13.0383 19.9202 12.8791C19.7249 12.5194 19.456 12.2049 19.1309 11.9562C18.8058 11.7075 18.432 11.5302 18.0337 11.4358C17.6355 11.3415 17.2218 11.3322 16.8197 11.4085C16.4176 11.4849 16.0361 11.6453 15.7002 11.8791C15.5482 11.9669 15.3757 12.0131 15.2002 12.0131C15.0247 12.0131 14.8522 11.9669 14.7002 11.8791C14.3366 11.6485 13.9288 11.4964 13.503 11.4326C13.0772 11.3688 12.6428 11.3947 12.2275 11.5086C11.8123 11.6225 11.4255 11.822 11.0919 12.0942C10.7583 12.3663 10.4852 12.7052 10.2902 13.0891C9.73313 14.115 9.54264 15.3003 9.75019 16.4491C9.91872 18.0269 10.6002 19.5059 11.6902 20.6591C12.0495 20.9999 12.5182 21.2021 13.0127 21.2296C13.5072 21.2571 13.9953 21.1081 14.3902 20.8091C14.6174 20.641 14.8926 20.5503 15.1752 20.5503C15.4578 20.5503 15.733 20.641 15.9602 20.8091C16.3391 21.1152 16.8142 21.2771 17.3012 21.2661C17.7882 21.2552 18.2555 21.072 18.6202 20.7491C19.4435 19.918 20.0303 18.8824 20.3202 17.7491C19.7195 17.6661 19.1701 17.3658 18.7758 16.9052C18.3815 16.4445 18.1696 15.8554 18.1802 15.2491Z" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.1802 9.75L17.6802 8.25" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12216"><rect width="14" height="14" fill="white" transform="translate(8 7.75)"/></clipPath></defs></svg>',
                    'title' => 'Iphone 12-16'
                ],
                '33397' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#744A9E"/><g clip-path="url(#clip0_1711_12221)"><path d="M15 18.25C16.933 18.25 18.5 16.683 18.5 14.75C18.5 12.817 16.933 11.25 15 11.25C13.067 11.25 11.5 12.817 11.5 14.75C11.5 16.683 13.067 18.25 15 18.25Z" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 11.88V9.25C17 8.98478 16.8946 8.73043 16.7071 8.54289C16.5196 8.35536 16.2652 8.25 16 8.25H14C13.7348 8.25 13.4804 8.35536 13.2929 8.54289C13.1054 8.73043 13 8.98478 13 9.25V11.88" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 17.6191V20.2491C17 20.5144 16.8946 20.7687 16.7071 20.9562C16.5196 21.1438 16.2652 21.2491 16 21.2491H14C13.7348 21.2491 13.4804 21.1438 13.2929 20.9562C13.1054 20.7687 13 20.5144 13 20.2491V17.6191" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12221"><rect width="14" height="14" fill="white" transform="translate(8 7.75)"/></clipPath></defs></svg>',
                    'title' => 'Whatch'
                ],
                '33398' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#57B0EA"/><g clip-path="url(#clip0_1711_12227)"><path d="M10.3477 8.25C9.61024 8.24892 8.89826 8.51947 8.34766 9.01V13.49C8.89826 13.9805 9.61024 14.2511 10.3477 14.25C10.514 14.2652 10.6813 14.2652 10.8477 14.25V20.04C10.8477 20.3715 10.9794 20.6895 11.2138 20.9239C11.4482 21.1583 11.7661 21.29 12.0977 21.29C12.4292 21.29 12.7471 21.1583 12.9815 20.9239C13.216 20.6895 13.3477 20.3715 13.3477 20.04V11.25C13.3477 10.4544 13.0316 9.69129 12.469 9.12868C11.9064 8.56607 11.1433 8.25 10.3477 8.25V8.25Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.3477 8.25C20.0851 8.24892 20.7971 8.51947 21.3477 9.01V13.49C20.7971 13.9805 20.0851 14.2511 19.3477 14.25C19.1813 14.2652 19.014 14.2652 18.8477 14.25V20.04C18.8477 20.3715 18.716 20.6895 18.4815 20.9239C18.2471 21.1583 17.9292 21.29 17.5977 21.29C17.2661 21.29 16.9482 21.1583 16.7138 20.9239C16.4794 20.6895 16.3477 20.3715 16.3477 20.04V11.25C16.3477 10.4544 16.6637 9.69129 17.2263 9.12868C17.7889 8.56607 18.552 8.25 19.3477 8.25V8.25Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12227"><rect width="14" height="14" fill="white" transform="translate(7.84766 7.75)"/></clipPath></defs></svg>',
                    'title' => 'Earphones'
                ],
                '33399' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><g clip-path="url(#clip0_1711_12232)"><path d="M8.01953 8.5V21.5H21.0195" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.0195 21.5C15.0195 19.6435 14.282 17.863 12.9693 16.5503C11.6565 15.2375 9.87605 14.5 8.01953 14.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.01953 21.5L10.5195 19" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.0195 16.5L14.5195 15" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.5195 13L18.0195 11.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.0195 9.5L21.0195 8.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12232"><rect width="14" height="14" fill="white" transform="translate(7.51953 8)"/></clipPath></defs></svg>',
                    'title' => 'Регулируемый угол'
                ],
                '33400' => [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><g clip-path="url(#clip0_1711_12241)"><path d="M16.8098 20.07C16.358 20.5217 15.8217 20.8801 15.2315 21.1246C14.6413 21.3691 14.0086 21.4949 13.3698 21.4949C12.0795 21.4949 10.8421 20.9823 9.92978 20.07C9.01743 19.1577 8.50488 17.9203 8.50488 16.63C8.50488 15.3397 9.01743 14.1023 9.92978 13.19L14.6198 8.5L16.8098 10.69L12.5098 15C12.2334 15.3398 12.0928 15.7701 12.1153 16.2076C12.1377 16.6451 12.3216 17.0587 12.6314 17.3684C12.9411 17.6782 13.3547 17.8621 13.7922 17.8845C14.2297 17.907 14.6599 17.7664 14.9998 17.49L19.2998 13.19L21.4998 15.38L16.8098 20.07Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.1895 15.3086L19.3795 17.4986" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.5 10.6191L14.69 12.8091" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12241"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
                    'title' => 'Magnetic adsorption'
                ],
            ];
            ?>
            <? global $USER; ?>
            <? if ($USER->IsAuthorized() && $USER->IsAdmin()): ?>
                <?/*
                <pre>
                    <? print_r($arResult['PROPERTIES']['NEW']['VALUE_XML_ID']); ?>
                    <? print_r($arResult['PROPERTIES']['SALE']['VALUE_XML_ID']); ?>
                    <? print_r($arResult['PROPERTIES']['HIT']['VALUE_XML_ID']); ?>
                </pre>
                */?>
            <?endif;?>
                <div class="block-header-tags">
                    <div class="tags-marker">
                        <?php if ($arResult['PROPERTIES']['RECOMMEND']['VALUE_XML_ID']) { ?>
                            <div class="widget-markers-wrap">
                                <div class="widget-markers widget-markers-recommend">Рекомендуют</div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['PROPERTIES']['SALE']['VALUE_XML_ID']) { ?>
                            <div class="widget-markers-wrap">
                                <div class="widget-markers widget-markers-sale">Распродажа</div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['PROPERTIES']['NEW']['VALUE_XML_ID']) { ?>
                            <div class="widget-markers-wrap">
                                <div class="widget-markers widget-markers-new">Новинка</div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['PROPERTIES']['HIT']['VALUE_XML_ID']) { ?>
                            <div class="widget-markers-wrap">
                                <div class="widget-markers widget-markers-hit">Хит</div>
                            </div>
                        <?php } ?>
                        <?php foreach ($arResult['PROPERTIES']['TAGS_FIGMA']['VALUE_ENUM_ID'] as $enumId) { 
                            if (isset($figmaTagsData[$enumId])) {
                                $tagData = $figmaTagsData[$enumId];
                            ?>
                                <div class="figma-tag" title="<?= htmlspecialchars($tagData['title']) ?>">
                                    <?= $tagData['svg'] ?>
                                </div>
                            <?php 
                            }
                        } ?>
                    </div>
                    <div class="heart-marker">
                        <?/* ПУСТОЕ СЕРДЦЕ */?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="23" viewBox="0 0 27 23" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M23.6478 11.0796C24.679 10.0425 25.2518 8.64226 25.2403 7.18691C25.2288 5.73156 24.634 4.34034 23.5866 3.3193C23.0679 2.81373 22.4538 2.41426 21.7793 2.1437C21.1048 1.87313 20.383 1.73677 19.6553 1.7424C18.1855 1.75378 16.7805 2.34282 15.7493 3.37995C15.4693 3.65721 15.1135 3.99752 14.6818 4.4009L13.4816 5.52004L12.2814 4.4009C11.8487 3.99656 11.4924 3.65625 11.2124 3.37995C10.1731 2.35087 8.76354 1.77273 7.29377 1.77273C5.82401 1.77273 4.41444 2.35087 3.37516 3.37995C1.23429 5.50126 1.2095 8.93089 3.29641 11.0623L13.4816 21.1476L23.6478 11.0796ZM2.13701 2.15539C2.81414 1.48473 3.61807 0.952715 4.50288 0.589743C5.38768 0.226772 6.33604 0.0399508 7.29377 0.0399508C8.25151 0.0399508 9.19986 0.226772 10.0847 0.589743C10.9695 0.952715 11.7734 1.48473 12.4505 2.15539C12.716 2.41917 13.0596 2.74745 13.4816 3.14024C13.9016 2.74745 14.2453 2.41869 14.5127 2.15395C15.8697 0.789557 17.7185 0.0148347 19.6524 0.00021083C21.5863 -0.014413 23.4468 0.732259 24.8247 2.07597C26.2026 3.41968 26.985 5.25036 26.9998 7.16527C27.0146 9.08019 26.2605 10.9225 24.9035 12.2869L14.5127 22.5772C14.2392 22.8479 13.8683 23 13.4816 23C13.0949 23 12.724 22.8479 12.4505 22.5772L2.0568 12.2854C0.72392 10.9243 -0.0147832 9.09859 0.000224273 7.20269C0.0152318 5.30679 0.782746 3.49281 2.13701 2.1525V2.15539Z" fill="#222222"/>
                        </svg>

                        <div class="container-btn-create-kp">
                            <div class="btn-create-kp">
                                Создать КП
                            </div>
                        </div>
                    </div>

                    <?/* ЗАПОЛНЕНОЕ СЕРДЦЕ 
                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="23" viewBox="0 0 27 23" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.11605 2.15539C2.78654 1.48473 3.58258 0.952715 4.45871 0.589744C5.33483 0.226772 6.27388 0.0399508 7.22222 0.0399508C8.17056 0.0399508 9.10961 0.226772 9.98574 0.589744C10.8619 0.952715 11.6579 1.48473 12.3284 2.15539C12.5912 2.41917 12.9315 2.74745 13.3493 3.14024C13.7652 2.74745 14.1055 2.41869 14.3703 2.15395C15.714 0.789558 17.5447 0.0148347 19.4596 0.00021083C21.3745 -0.014413 23.2168 0.732259 24.5812 2.07597C25.9456 3.41968 26.7203 5.25036 26.7349 7.16527C26.7496 9.08019 26.0029 10.9225 24.6592 12.2869L14.3703 22.5772C14.0995 22.8479 13.7323 23 13.3493 23C12.9664 23 12.5992 22.8479 12.3284 22.5772L2.03663 12.2854C0.716819 10.9243 -0.0146382 9.09859 0.000222073 7.20269C0.0150823 5.30679 0.775067 3.49281 2.11605 2.1525V2.15539Z" fill="#EF4A85"/>
                    </svg>
                    */?>
                    
                    <?/*<pre>
                    <? print_r($figmaTagsData); ?>
                    </pre>*/?>
                </div>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'catalog-element-gallery-pictures-slider' => true,
                        'owl-carousel' => true
                    ], true),
                    'data-role' => 'gallery.pictures.slider'
                ]) ?>
                    <?php if (!empty($arItem['GALLERY']['VALUES']) || ($arVisual['GALLERY']['VIDEO']['USE'] && !empty($arVideos))) { ?>
                        <?php $bFirstSlide = true; ?>
                        <?php foreach ($arItem['GALLERY']['VALUES'] as $arPicture) {
                            $bImageIsGif = $arPicture['CONTENT_TYPE'] == 'image/gif';
                            $arPictureResize['src'] = $arPicture['SRC'];

                            if (!$bImageIsGif) {
                                $arPictureResize = CFile::ResizeImageGet(
                                    $arPicture,
                                    $arPictureSizes,
                                    BX_RESIZE_IMAGE_PROPORTIONAL
                                );
                            }
                        ?>
                            <div class="catalog-element-gallery-pictures-slider-item" data-role="gallery.pictures.item">                              
                                <?= Html::beginTag($arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div', [
                                    'class' => [
                                        'catalog-element-gallery-pictures-slider-item-picture',
                                        'intec-ui-picture'
                                    ],
                                    'href' => $arVisual['GALLERY']['ACTION'] === 'source' ? $arPicture['SRC'] : null,
                                    'target' => $arVisual['GALLERY']['ACTION'] === 'source' ? '_blank' : null,
                                    'data' => [
                                        'role' => 'gallery.pictures.item.picture',
                                        'src' => $arVisual['GALLERY']['ACTION'] === 'popup' || $arVisual['GALLERY']['ZOOM'] ? $arPicture['SRC'] : null,
                                        'type' => $arPicture['CONTENT_TYPE'],
                                        'lightGallery' => $arVisual['GALLERY']['ACTION'] === 'popup' ? 'true' : 'false'
                                    ]
                                ]) ?>
                                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $arPictureResize['src'], [
                                        'alt' => $arItem['GALLERY']['PROPERTIES']['ALT'],
                                        'title' => $arItem['GALLERY']['PROPERTIES']['TITLE'],
                                        'loading' => 'lazy',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                                        ]
                                    ]) ?>
                                    
                                    <!-- Вывод маркеров на первом слайде основного товара -->
                                    <?php if ($bFirstSlide && !$bOffer && !empty($sMarkersHtml)): ?>
                                        <?= $sMarkersHtml ?>
                                    <?php endif; ?>
                                    
                                <?= Html::endTag($arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div') ?>
                            </div>
                            <?php $bFirstSlide = false; ?>
                        <?php } ?>
                        
                        <?php foreach ($arVideos as $sKeyVideo => $arVideo) { ?>
                            <?php if (!empty($arVideo['LINK'])) {
                                $arVideoInfo = youtube_video($arVideo['LINK']);
                                $sVideoPreview = !empty($arVideoInfo['sddefault']) ? $arVideoInfo['sddefault'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                            ?>
                                <div class="catalog-element-gallery-pictures-slider-item" data-role="gallery.pictures.item">
                                    <?= Html::beginTag('div', [
                                        'class' => 'catalog-element-gallery-pictures-slider-item-video',
                                        'data' => [
                                            'src' => $arVideoInfo['iframe'],
                                            'lightGallery' => 'true'
                                        ]
                                    ]) ?>
                                        <?= Html::tag('div', $arSvg['PLAY'], [
                                            'class' => [
                                                'catalog-element-gallery-pictures-slider-item-video-stub',
                                                'intec-image-effect'
                                            ],
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sVideoPreview : null
                                            ],
                                            'style' => [
                                                'background-image' => 'url(\''.($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sVideoPreview).'\')'
                                            ]
                                        ]) ?>
                                        
                                        <!-- Вывод маркеров на первом видео-слайде, если нет изображений -->
                                        <?php if ($bFirstSlide && !$bOffer && !empty($sMarkersHtml)): ?>
                                            <?= $sMarkersHtml ?>
                                        <?php endif; ?>
                                        
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?php } else { ?>
                                <div class="catalog-element-gallery-pictures-slider-item" data-role="gallery.pictures.item">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'catalog-element-gallery-pictures-slider-item-video',
                                            'intec-image-effect'
                                        ],
                                        'data' => [
                                            'html' => '#video-'.$arItem['ID'].$sKeyVideo,
                                            'role' => 'gallery.video',
                                            'lightGallery' => 'true'
                                        ]
                                    ]) ?>
                                        <?= Html::beginTag('div', [
                                            'id' => 'video-'.$arItem['ID'].$sKeyVideo,
                                            'class' => 'catalog-element-gallery-pictures-slider-item-video-wrapper'
                                        ]) ?>
                                            <?= Html::beginTag('video', [
                                                'class' => [
                                                    'lg-video-object',
                                                    'lg-html5'
                                                ],
                                                'data' => [
                                                    'id' => 'video-'.$arItem['ID'].$sKeyVideo,
                                                    'role' => 'gallery.uploaded.video',
                                                    'src' => !empty($arVideo['FILE_MP4']) ? $arVideo['FILE_MP4']['SRC'].'#t=0.5' : (!empty($arVideo['FILE_WEBM']) ? $arVideo['FILE_WEBM']['SRC'].'#t=0.5' : (!empty($arVideo['FILE_OGV']) ? $arVideo['FILE_OGV']['SRC'].'#t=0.5' : null))
                                                ],
                                                'loop' => true,
                                                'controls' => $arVisual['GALLERY']['VIDEO']['CONTROLS'],
                                                'muted' => true
                                            ]) ?>
                                                <?php if (!empty($arVideo['FILE_MP4'])) { ?>
                                                    <source src="<?= $arVideo['FILE_MP4']['SRC'] ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                                                <?php } ?>
                                                <?php if (!empty($arVideo['FILE_WEBM'])) { ?>
                                                    <source src="<?= $arVideo['FILE_WEBM']['SRC'] ?>" type='video/webm; codecs="vp8, vorbis"'>
                                                <?php } ?>
                                                <?php if (!empty($arVideo['FILE_OGV'])) { ?>
                                                    <source src="<?= $arVideo['FILE_OGV']['SRC'] ?>" type='video/ogg; codecs="theora, vorbis"'>
                                                <?php } ?>
                                            <?= Html::endTag('video') ?>
                                            <?= $arSvg['PLAY'] ?>
                                        <?= Html::endTag('div') ?>
                                        
                                        <!-- Вывод маркеров на первом видео-слайде, если нет изображений -->
                                        <?php if ($bFirstSlide && !$bOffer && !empty($sMarkersHtml)): ?>
                                            <?= $sMarkersHtml ?>
                                        <?php endif; ?>
                                        
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?php } ?>
                            <?php $bFirstSlide = false; ?>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="catalog-element-gallery-pictures-slider-item">
                            <div class="catalog-element-gallery-pictures-slider-item-picture intec-ui-picture">
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                                    'alt' => $arItem['GALLERY']['PROPERTIES']['ALT'],
                                    'title' => $arItem['GALLERY']['PROPERTIES']['TITLE'],
                                    'loading' => 'lazy',
                                    'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'data-original' => $arVisual['LAZYLOAD']['USE'] ? SITE_TEMPLATE_PATH.'/images/picture.missing.png' : null
                                ]) ?>
                                
                                <!-- Вывод маркеров на заглушке для основного товара -->
                                <?php if (!$bOffer && !empty($sMarkersHtml)): ?>
                                    <?= $sMarkersHtml ?>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?= Html::endTag('div') ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>

<div class="catalog-element-gallery-container catalog-element-main-block">
    <?php $vGallery($arResult);

    if ($bSkuDynamic) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vGallery($arOffer, true);

        unset($arOffer);
    } ?>
</div>
<?php unset($vGallery, $iVideoCounter, $iVideoPreviewCounter) ?>

<script>
$(document).ready(function() {
    // Основной слайдер - всегда инициализируем
    $('.catalog-element-gallery-pictures-slider').owlCarousel({
        items: 1,
        margin: 0,
        nav: false,
        dots: true, // Всегда включаем точки
        mouseDrag: true,
        touchDrag: true,
        pullDrag: true,
        freeDrag: false,
        stagePadding: 0,
        loop: false
    });

    // Вертикальный слайдер миниатюр только на десктопе
    function initVerticalSlider() {
        var verticalSlider = $('.catalog-element-gallery-preview-vertical-slider');
        var isMobile = $(window).width() <= 767;
        
        if (isMobile) {
            // На мобильных уничтожаем вертикальный слайдер
            if (verticalSlider.data('owl.carousel')) {
                verticalSlider.trigger('destroy.owl.carousel');
                verticalSlider.removeClass('owl-loaded');
            }
        } else {
            // На десктопе инициализируем вертикальный слайдер
            if (!verticalSlider.data('owl.carousel')) {
                verticalSlider.owlCarousel({
                    items: 4,
                    margin: 10,
                    nav: false,
                    dots: false,
                    vertical: true,
                    mouseDrag: true,
                    touchDrag: true
                });
            }
        }
    }

    // Инициализация при загрузке
    initVerticalSlider();
    
    // Переинициализация при изменении размера окна
    $(window).on('resize', function() {
        initVerticalSlider();
    });
});
</script>