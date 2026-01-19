<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
use intec\core\helpers\Html;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var bool $bSkuDynamic
 */

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
        $arVideos = $arResult['GALLERY_VIDEO']['OFFERS'][$arItem['ID']] ?? [];
        if (empty($arVideos) && empty($arItem['GALLERY']['VALUES']))
            return;
    } else {
        $arVideos = $arResult['GALLERY_VIDEO']['PRODUCT'] ?? [];
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

    // Размеры основной картинки
    if ($arVisual['MAIN_VIEW'] == 1) {
        $arPictureSizes = ['width' => 600, 'height' => 600];
    } else if ($arVisual['MAIN_VIEW'] == 2) {
        $arPictureSizes = ['width' => 1000, 'height' => 1000];
    } else {
        $arPictureSizes = ['width' => 1200, 'height' => 1200];
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
            <!-- Вертикальный слайдер миниатюр (только на десктопе) -->
            <div class="catalog-element-gallery-preview-vertical" data-role="gallery.preview">
                <div class="catalog-element-gallery-preview-vertical-slider owl-carousel" data-role="gallery.preview.slider">
                    <?php $bPictureFirst = true; ?>
                    <?php foreach ($arItem['GALLERY']['VALUES'] as $arPicture): 
                        $sPicture = CFile::ResizeImageGet($arPicture, [
                            'width' => 80,
                            'height' => 80
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
                    ?>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-element-gallery-preview-vertical-slider-item',
                            'data' => [
                                'role' => 'gallery.preview.slider.item',
                                'index' => $bPictureFirst ? '0' : null,
                                'active' => $bPictureFirst ? 'true' : 'false'
                            ]
                        ]) ?>
                            <div class="catalog-element-gallery-preview-vertical-slider-item-picture intec-ui-picture">
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture['src'], [
                                    'alt' => $arItem['GALLERY']['PROPERTIES']['ALT'] ?? '',
                                    'title' => $arItem['GALLERY']['PROPERTIES']['TITLE'] ?? '',
                                    'loading' => 'lazy',
                                    'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'data-original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture['src'] : null
                                ]) ?>
                                <?php if ($arPicture['CONTENT_TYPE'] === 'image/gif') { ?>
                                    <?= $arSvg['GIF'] ?? '' ?>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                        <?php $bPictureFirst = false; ?>
                    <?php endforeach; ?>

                    <?php foreach ($arVideos as $sKeyVideo => $arVideo): ?>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-element-gallery-preview-vertical-slider-item',
                            'data' => [
                                'role' => 'gallery.preview.slider.item',
                                'index' => $bPictureFirst ? '0' : null,
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
                                        'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'data-original' => $arVisual['LAZYLOAD']['USE'] ? $sVideoPreview : null
                                    ]) ?>
                                    <?= $arSvg['PLAY'] ?? '' ?>
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
                                    <?= $arSvg['PLAY'] ?? '' ?>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        <?= Html::endTag('div') ?>
                        <?php $bPictureFirst = false; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php } ?>

        <!-- Основной большой слайдер с НАВИГАЦИЕЙ (стрелками) -->
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-gallery-pictures',
            'data' => [
                'role' => 'gallery.pictures',
                'action' => $arVisual['GALLERY']['ACTION'],
                'zoom' => $arVisual['GALLERY']['ZOOM'] ? 'true' : 'false'
            ]
        ]) ?>

            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-element-gallery-pictures-slider' => true,
                    'owl-carousel' => true,
                    'owl-theme' => true
                ], true),
                'data-role' => 'gallery.pictures.slider'
            ]) ?>

                <?php $bFirstSlide = true; ?>
                <?php foreach ($arItem['GALLERY']['VALUES'] as $arPicture): 
                    $bImageIsGif = $arPicture['CONTENT_TYPE'] == 'image/gif';
                    $arPictureResize = CFile::ResizeImageGet(
                        $arPicture,
                        $arPictureSizes,
                        BX_RESIZE_IMAGE_PROPORTIONAL
                    );
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
                                'alt' => $arItem['GALLERY']['PROPERTIES']['ALT'] ?? '',
                                'title' => $arItem['GALLERY']['PROPERTIES']['TITLE'] ?? '',
                                'loading' => 'lazy',
                                'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'data-original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                            ]) ?>

                            <?php if ($bFirstSlide && !$bOffer && !empty($sMarkersHtml)): ?>
                                <?= $sMarkersHtml ?>
                            <?php endif; ?>

                        <?= Html::endTag($arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div') ?>
                    </div>
                    <?php $bFirstSlide = false; ?>
                <?php endforeach; ?>

                <!-- Видео-слайды (если есть) -->
                <?php foreach ($arVideos as $sKeyVideo => $arVideo): ?>
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
                                <?= Html::tag('div', $arSvg['PLAY'] ?? '', [
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

                                <?php if ($bFirstSlide && !$bOffer && !empty($sMarkersHtml)): ?>
                                    <?= $sMarkersHtml ?>
                                <?php endif; ?>

                            <?= Html::endTag('div') ?>
                        </div>
                    <?php } ?>
                    <?php $bFirstSlide = false; ?>
                <?php endforeach; ?>

                <?php if (empty($arItem['GALLERY']['VALUES']) && empty($arVideos)): ?>
                    <div class="catalog-element-gallery-pictures-slider-item">
                        <div class="catalog-element-gallery-pictures-slider-item-picture intec-ui-picture">
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                                'alt' => '',
                                'title' => '',
                                'loading' => 'lazy',
                                'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'data-original' => $arVisual['LAZYLOAD']['USE'] ? SITE_TEMPLATE_PATH.'/images/picture.missing.png' : null
                            ]) ?>

                            <?php if (!$bOffer && !empty($sMarkersHtml)): ?>
                                <?= $sMarkersHtml ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?= Html::endTag('div') ?>
        <?= Html::endTag('div') ?>
    </div>
<?= Html::endTag('div') ?>
<?php } ?>

<div class="catalog-element-gallery-container catalog-element-main-block">
    <?php 
    $vGallery($arResult);
    if ($bSkuDynamic) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vGallery($arOffer, true);
        unset($arOffer);
    } 
    ?>
</div>

<!-- ================================================================================= -->
<!--                               JavaScript — ОСНОВНОЕ                              -->
<!-- ================================================================================= -->
<script>
$(document).ready(function() {

    // =============================================================
    // 1. Инициализация основного большого слайдера
    // =============================================================
    var $mainSlider = $('.catalog-element-gallery-pictures-slider');

    $mainSlider.owlCarousel({
        items: 1,
        margin: 0,
        nav: true,                  // ← ВКЛЮЧАЕМ СТРЕЛКИ
        dots: true,                 // точки внизу
        loop: false,                // или true — если хочешь зациклить
        mouseDrag: true,
        touchDrag: true,
        pullDrag: true,
        autoplay: false,
        navText: [
            '<div class="owl-prev-custom"><svg width="24" height="44" viewBox="0 0 24 44" fill="none"><path d="M22 2L2 22L22 42" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></div>',
            '<div class="owl-next-custom"><svg width="24" height="44" viewBox="0 0 24 44" fill="none"><path d="M2 42L22 22L2 2" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></div>'
        ],
        responsive: {
            0:    { nav: false },   // на мобилках стрелки можно скрыть
            768:  { nav: true }
        }
    });

    // =============================================================
    // 2. Вертикальный слайдер миниатюр (только десктоп)
    // =============================================================
    function initPreviewSlider() {
        var $preview = $('.catalog-element-gallery-preview-vertical-slider');
        var isMobile = $(window).width() < 768;

        if (isMobile) {
            if ($preview.data('owl.carousel')) {
                $preview.trigger('destroy.owl.carousel');
                $preview.removeClass('owl-loaded owl-carousel owl-drag');
            }
        } else {
            if (!$preview.data('owl.carousel')) {
                $preview.owlCarousel({
                    items: 5,           // сколько видно миниатюр
                    margin: 12,
                    nav: true,
                    dots: false,
                    vertical: true,     // ← вертикальное направление
                    mouseDrag: true,
                    touchDrag: true,
                    navText: [
                        '<svg width="20" height="12" viewBox="0 0 20 12" fill="none"><path d="M2 10L10 2L18 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                        '<svg width="20" height="12" viewBox="0 0 20 12" fill="none"><path d="M2 2L10 10L18 2" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                    ]
                });
            }
        }
    }

    initPreviewSlider();
    $(window).on('resize', initPreviewSlider);

    // =============================================================
    // 3. Синхронизация: клик по миниатюре → меняем основной слайд
    // =============================================================
    $('.catalog-element-gallery-preview-vertical-slider-item').on('click', function() {
        var index = $(this).index(); // номер миниатюры
        $mainSlider.trigger('to.owl.carousel', [index, 300]); // переключаем основной слайдер
    });

    // =============================================================
    // 4. При смене слайда в основном → выделяем активную миниатюру
    // =============================================================
    $mainSlider.on('changed.owl.carousel', function(event) {
        var currentIndex = event.item.index;

        // Убираем active со всех миниатюр
        $('.catalog-element-gallery-preview-vertical-slider-item')
            .removeClass('active')
            .attr('data-active', 'false');

        // Добавляем active нужной миниатюре
        $('.catalog-element-gallery-preview-vertical-slider-item')
            .eq(currentIndex)
            .addClass('active')
            .attr('data-active', 'true');
    });

    // =============================================================
    // 5. Клик по основной картинке → следующий слайд (удобно на мобилках)
    // =============================================================
    $('.catalog-element-gallery-pictures-slider-item-picture').on('click', function(e) {
        // Если кликнули на ссылку или видео — не переключаем
        if ($(e.target).closest('a, .catalog-element-gallery-pictures-slider-item-video').length) {
            return;
        }
        $mainSlider.trigger('next.owl.carousel');
    });

});
</script>

<!-- ================================================================================= -->
<!--                                   Стили                                           -->
<!-- ================================================================================= -->
<style>
/* Основные стрелки — делаем их красивыми и заметными */
.catalog-element-gallery-preview-vertical-slider .owl-nav{
    display: none !important;
}
.owl-nav {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    z-index: 10;
}

.owl-prev-custom,
.owl-next-custom {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: white;
    display: flex !important;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    pointer-events: auto;
}
.owl-theme .owl-nav [class*='owl-'] {
    color: #a1a8ac !important;
    background: transparent !important;
}
.owl-prev-custom {
    margin-left: 15px;
}

.owl-next-custom {
    margin-right: 15px;
}

.owl-prev-custom:hover,
.owl-next-custom:hover {
    background: rgba(0,0,0,0.7);
    transform: scale(1.1);
}

/* Активная миниатюра */
.catalog-element-gallery-preview-vertical-slider-item.active {
    border: 3px solid #744A9E !important;
    border-radius: 8px;
    opacity: 1 !important;
}

/* Убираем стандартные точки, если не нужны */
.owl-dots {
    margin-top: 15px;
}
.owl-carousel .owl-nav.disabled, .owl-carousel .owl-dots.disabled {
    display: block;
}
/* Скрываем зум-картинку (если используете zoom) */
.zoomImg {
    display: none !important;
}
.ns-bitrix.c-catalog-element.c-catalog-element-catalog-default-5 .catalog-element-gallery-container {
    max-width: 100%;
    /* margin-left: auto; */
    /* margin-right: auto; */
    /* margin-bottom: 24px; */
}
</style>