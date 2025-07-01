<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

/**
 * @var Closure $tagsRender($arTags)
 */
$tagsRender = include(__DIR__.'/parts/tags.php');

?>

<div class="container">
    <div class="brands-scrollable-block">
        <span class="brands-scrollable-block--title">Бренды</span>

        <div class="brands-scrollable-block--list">
            <?php foreach ($arResult['ITEMS'] as $arItem) {
                //pre($arItem);die();

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $sPicture = $arItem['PREVIEW_PICTURE'];

                if (empty($sPicture)){
                    $sPicture = $arItem['DETAIL_PICTURE'];
                }
                $detailPicture = $arItem['DETAIL_PICTURE'];
                if (empty($detailPicture)){
                    $detailPicture = $sPicture;
                }

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 350,
                        'height' => 350
                    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    if (!empty($sPicture['src']))
                        $sPicture = $sPicture['src'];
                }
                if (!empty($detailPicture)) {
                    $detailPicture = CFile::ResizeImageGet($detailPicture, [
                        'width' => 350,
                        'height' => 350
                    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    if (!empty($detailPicture['src']))
                        $detailPicture = $detailPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                <div class="brands-scrollable-block--item" id="<?= $sAreaId ?>">
                    <img src="<?=$sPicture;?>" draggable="false" alt="<?=$arItem['NAME'];?>">
                    <img src="<?=$detailPicture;?>" class="hover" draggable="false" alt="<?=$arItem['NAME'];?>">
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scrollableBlock = document.querySelector('.brands-scrollable-block--list');
        let isDragging = false;
        let startX, scrollLeft;
        let targetScrollLeft = scrollableBlock.scrollLeft;
        let isAnimating = false;

        // --- Отключение перетаскивания изображений ---
        document.querySelectorAll('.brands-scrollable-block--item img').forEach(img => {
            img.setAttribute('draggable', 'false');
        });

        // --- Скролл колесиком ---
        scrollableBlock.addEventListener('wheel', (e) => {
            e.preventDefault();
            targetScrollLeft += e.deltaY * 0.5;
            startSmoothScroll();
        });

        // --- Перетаскивание мышью ---
        scrollableBlock.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - scrollableBlock.offsetLeft;
            scrollLeft = scrollableBlock.scrollLeft;
            scrollableBlock.style.cursor = 'grabbing';
            scrollableBlock.style.userSelect = 'none';
        });

        scrollableBlock.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - scrollableBlock.offsetLeft;
            const walk = (x - startX) * 1.5;
            targetScrollLeft = scrollLeft - walk;
            startSmoothScroll();
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            scrollableBlock.style.cursor = 'grab';
            scrollableBlock.style.removeProperty('user-select');
        });

        scrollableBlock.addEventListener('mouseleave', () => {
            isDragging = false;
            scrollableBlock.style.cursor = 'grab';
        });

        // --- Тач-скролл ---
        scrollableBlock.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].pageX - scrollableBlock.offsetLeft;
            scrollLeft = scrollableBlock.scrollLeft;
        }, { passive: true });

        scrollableBlock.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            e.preventDefault(); // обязательно для отключения нативного скролла
            const x = e.touches[0].pageX - scrollableBlock.offsetLeft;
            const walk = (x - startX) * 1.5;
            targetScrollLeft = scrollLeft - walk;
            startSmoothScroll();
        }, { passive: false });

        scrollableBlock.addEventListener('touchend', () => {
            isDragging = false;
        });

        // --- Плавный скролл ---
        function startSmoothScroll() {
            if (isAnimating) return;
            isAnimating = true;
            smoothScroll();
        }

        function smoothScroll() {
            const diff = targetScrollLeft - scrollableBlock.scrollLeft;
            if (Math.abs(diff) > 0.5) {
                scrollableBlock.scrollLeft += diff * 0.1;
                requestAnimationFrame(smoothScroll);
            } else {
                isAnimating = false;
            }
        }
    })
</script>
