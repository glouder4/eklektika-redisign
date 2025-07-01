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

?>

<div class="container">
    <div class="underslider-categories">
        <?php foreach ($arResult['ITEMS'] as $key => $arItem) {
            $mobileBanner = CFile::GetPath($arItem['PROPERTIES']['MOBILE_BANNER']['VALUE']);

            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);

            $sPicture = $arItem['PREVIEW_PICTURE'];

            if (empty($sPicture))
                $sPicture = $arItem['DETAIL_PICTURE'];

            if (!empty($sPicture)) {
                $sPicture = CFile::ResizeImageGet($sPicture, [
                    'width' => 350,
                    'height' => 900
                ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                if (!empty($sPicture['src']))
                    $sPicture = $sPicture['src'];
            }

            if (empty($sPicture))
                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

            if( empty($mobileBanner) )
                $mobileBanner = $sPicture;

            ?>

            <a href="<?=!$arItem['DATA']['HIDE_LINK'] ? $arItem['DETAIL_PAGE_URL'] : '#';?>" id="<?= $sAreaId ?>" class="underslider-categories--category-item <?=($key == 0) ? "category-item-full_size" : null;?>" data-mobile_background="<?=$mobileBanner;?>" data-desktop_background="<?=$sPicture;?>" style="background-image: url('<?=($key == 0) ? $sPicture : $sPicture;?>')">
                <?php
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);
                ?>
                <div class="category-item-full_size--data">
                    <div class="category-item-full_size--data_title"><span><?=html_entity_decode($arItem['NAME']);?></span></div>
                    <div class="category-item-full_size--data_description">
                        <p><?= html_entity_decode($arItem['PREVIEW_TEXT']); ?></p>
                    </div>
                    <div class="category-item-full_size--data_actions">
                        <div class="category-item-full_size--data_actions-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
                                <path d="M1.25464 9L4.6001 5L1.25464 1" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateBackgroundImages() {
            const categoryItems = document.querySelectorAll('.underslider-categories--category-item');
            const isMobile = window.innerWidth < 768;
            const firstItem = categoryItems[0];

            categoryItems.forEach(item => {
                if (item === firstItem) {
                    if( isMobile ) {
                        item.style.backgroundImage = `url('${item.getAttribute('data-mobile_background')}')`;
                    }
                    else{
                        item.style.backgroundImage = `url('${item.getAttribute('data-desktop_background')}')`;
                    }
                } else {
                    let backgroundImage = null;
                    if( isMobile ){
                        backgroundImage = item.getAttribute('data-desktop_background');
                    }
                    else{
                        backgroundImage = item.getAttribute('data-mobile_background');
                    }
                    item.style.backgroundImage = `url('${backgroundImage}')`;
                }
            });
        }

        // Инициализация при загрузке страницы
        updateBackgroundImages();

        // Обновление при изменении размера окна
        window.addEventListener('resize', updateBackgroundImages);

        // Обработка наведения мыши
        const categoryItems = document.querySelectorAll('.underslider-categories--category-item');
        const firstItem = categoryItems[0];

        categoryItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                // Убираем класс у всех элементов
                categoryItems.forEach(el => el.classList.remove('category-item-full_size'));
                // Добавляем класс наведенному элементу
                item.classList.add('category-item-full_size');
                
                // Меняем фон: наведенный элемент получает десктопную версию, остальные - мобильную
                categoryItems.forEach(el => {
                    if( window.innerWidth < 768 ){
                        if (el === item) {
                            el.style.backgroundImage = `url('${el.getAttribute('data-mobile_background')}')`;
                        } else {
                            el.style.backgroundImage = `url('${el.getAttribute('data-desktop_background')}')`;
                        }
                    }
                    else{
                        if (el === item) {
                            el.style.backgroundImage = `url('${el.getAttribute('data-desktop_background')}')`;
                        } else {
                            el.style.backgroundImage = `url('${el.getAttribute('data-mobile_background')}')`;
                        }
                    }
                });
            });

            item.addEventListener('mouseleave', () => {
                // Возвращаем класс первому элементу
                categoryItems.forEach(el => el.classList.remove('category-item-full_size'));
                firstItem.classList.add('category-item-full_size');
                
                // Возвращаем фоны: первый элемент получает десктопную версию, остальные - мобильную
                categoryItems.forEach(el => {
                    if( window.innerWidth < 768 ){
                        if (el === firstItem) {
                            el.style.backgroundImage = `url('${el.getAttribute('data-mobile_background')}')`;
                        } else {
                            el.style.backgroundImage = `url('${el.getAttribute('data-desktop_background')}')`;
                        }
                    }
                    else{
                        if (el === firstItem) {
                            el.style.backgroundImage = `url('${el.getAttribute('data-desktop_background')}')`;
                        } else {
                            el.style.backgroundImage = `url('${el.getAttribute('data-mobile_background')}')`;
                        }
                    }
                });
            });
        });
    });
</script>