<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arSvg
 */
?>
<?/*
global $USER;
if ($USER->IsAuthorized() && $USER->IsAdmin()): */?>
<? $vopros = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none"><rect x="0.5" y="0.5" width="19" height="18" rx="9" fill="white"/><rect x="0.5" y="0.5" width="19" height="18" rx="9" stroke="#C8C8C8"/><path d="M9.03316 11.402C8.92916 10.958 8.88916 10.596 8.91316 10.316C8.94116 10.032 9.03516 9.792 9.19516 9.596C9.35516 9.4 9.58516 9.206 9.88516 9.014C10.2972 8.75 10.5712 8.526 10.7072 8.342C10.8432 8.158 10.9112 7.954 10.9112 7.73C10.9112 7.374 10.7852 7.108 10.5332 6.932C10.2812 6.752 9.92716 6.662 9.47116 6.662C9.14316 6.662 8.84716 6.708 8.58316 6.8C8.31916 6.892 8.07716 7.02 7.85716 7.184L7.50916 6.086C7.64116 5.97 7.81716 5.862 8.03716 5.762C8.25716 5.658 8.51116 5.576 8.79916 5.516C9.08716 5.452 9.39716 5.42 9.72916 5.42C10.2412 5.42 10.6992 5.504 11.1032 5.672C11.5072 5.836 11.8252 6.074 12.0572 6.386C12.2932 6.694 12.4112 7.062 12.4112 7.49C12.4112 7.734 12.3672 7.964 12.2792 8.18C12.1952 8.392 12.0492 8.604 11.8412 8.816C11.6332 9.028 11.3492 9.254 10.9892 9.494C10.7332 9.662 10.5452 9.824 10.4252 9.98C10.3052 10.132 10.2372 10.318 10.2212 10.538C10.2052 10.754 10.2252 11.042 10.2812 11.402H9.03316ZM9.71116 14.18C9.45516 14.18 9.23516 14.098 9.05116 13.934C8.86716 13.77 8.77516 13.556 8.77516 13.292C8.77516 13.112 8.81716 12.956 8.90116 12.824C8.98516 12.688 9.09716 12.584 9.23716 12.512C9.38116 12.436 9.53916 12.398 9.71116 12.398C9.97116 12.398 10.1932 12.48 10.3772 12.644C10.5612 12.804 10.6532 13.02 10.6532 13.292C10.6532 13.468 10.6092 13.624 10.5212 13.76C10.4372 13.892 10.3232 13.994 10.1792 14.066C10.0392 14.142 9.88316 14.18 9.71116 14.18Z" fill="#858585"/></svg>'; ?>
<?php
function universalReservedSearch($productId) {
    $reserved = 0;
    
    if (CModule::IncludeModule("catalog")) {
        $rsStore = CCatalogStoreProduct::GetList(
            array(),
            array('PRODUCT_ID' => $productId),
            false,
            false,
            array('RESERVED')
        );
        
        while ($arStore = $rsStore->Fetch()) {
            $reserved += $arStore['RESERVED'];
        }
        
        if ($reserved == 0) {
            $productInfo = CCatalogProduct::GetByID($productId);
            if ($productInfo && isset($productInfo['QUANTITY_RESERVED'])) {
                $reserved = $productInfo['QUANTITY_RESERVED'];
            }
        }
    }
    
    return $reserved;
}

function showReservedInfo($offer) {
    $reserved = universalReservedSearch($offer['ID']);
    $OSTATOK_V_PUTI = $offer['PROPERTIES']['OSTATOK_V_PUTI']['VALUE']; 
    $OSTATOK_BEZ_REZERVA = $offer['PRODUCT']['QUANTITY'];
    $OSTATOK_VMESTE_S_REZERVOM = $OSTATOK_BEZ_REZERVA + $reserved;
    $article = $offer['DISPLAY_PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['DISPLAY_VALUE'];
    $tooltip_1 = 'Всего на складе, без учета резервов';
    $SROK_POSTAVKI = $offer['PROPERTIES']['SROK_POSTAVKI']['VALUE'];
    // echo'<pre>';
    // print_r($offer['PROPERTIES']['SROK_POSTAVKI']);
    // echo'</pre>';
    $tooltip_2 = 'Дата поставки '.$SROK_POSTAVKI.' г';
    $tooltip_3 = 'Свободный остаток с учетом резервов';
?>
    <div class="catalog-element-purchase-container-ostatok offer-stock-info" data-article="<?=$article?>" style="display: none;">
        <div class="catalog-element-ostatok">
            <div class="catalog-element-ostatok-name">Склад Москва:</div>
            <div class="catalog-element-ostatok-number">
                <?=$OSTATOK_BEZ_REZERVA?> шт. 
                <div class="tooltip-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none"><rect x="0.5" y="0.5" width="19" height="18" rx="9" fill="white"/><rect x="0.5" y="0.5" width="19" height="18" rx="9" stroke="#C8C8C8"/><path d="M9.03316 11.402C8.92916 10.958 8.88916 10.596 8.91316 10.316C8.94116 10.032 9.03516 9.792 9.19516 9.596C9.35516 9.4 9.58516 9.206 9.88516 9.014C10.2972 8.75 10.5712 8.526 10.7072 8.342C10.8432 8.158 10.9112 7.954 10.9112 7.73C10.9112 7.374 10.7852 7.108 10.5332 6.932C10.2812 6.752 9.92716 6.662 9.47116 6.662C9.14316 6.662 8.84716 6.708 8.58316 6.8C8.31916 6.892 8.07716 7.02 7.85716 7.184L7.50916 6.086C7.64116 5.97 7.81716 5.862 8.03716 5.762C8.25716 5.658 8.51116 5.576 8.79916 5.516C9.08716 5.452 9.39716 5.42 9.72916 5.42C10.2412 5.42 10.6992 5.504 11.1032 5.672C11.5072 5.836 11.8252 6.074 12.0572 6.386C12.2932 6.694 12.4112 7.062 12.4112 7.49C12.4112 7.734 12.3672 7.964 12.2792 8.18C12.1952 8.392 12.0492 8.604 11.8412 8.816C11.6332 9.028 11.3492 9.254 10.9892 9.494C10.7332 9.662 10.5452 9.824 10.4252 9.98C10.3052 10.132 10.2372 10.318 10.2212 10.538C10.2052 10.754 10.2252 11.042 10.2812 11.402H9.03316ZM9.71116 14.18C9.45516 14.18 9.23516 14.098 9.05116 13.934C8.86716 13.77 8.77516 13.556 8.77516 13.292C8.77516 13.112 8.81716 12.956 8.90116 12.824C8.98516 12.688 9.09716 12.584 9.23716 12.512C9.38116 12.436 9.53916 12.398 9.71116 12.398C9.97116 12.398 10.1932 12.48 10.3772 12.644C10.5612 12.804 10.6532 13.02 10.6532 13.292C10.6532 13.468 10.6092 13.624 10.5212 13.76C10.4372 13.892 10.3232 13.994 10.1792 14.066C10.0392 14.142 9.88316 14.18 9.71116 14.18Z" fill="#858585"/></svg>
                    <div class="tooltip"><?=$tooltip_1?></div>
                </div>
            </div>
        </div>
         <div class="catalog-element-ostatok">
            <div class="catalog-element-ostatok-name">Свободно:</div>
            <div class="catalog-element-ostatok-number"><?=$OSTATOK_VMESTE_S_REZERVOM?> шт.
                <div class="tooltip-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none"><rect x="0.5" y="0.5" width="19" height="18" rx="9" fill="white"/><rect x="0.5" y="0.5" width="19" height="18" rx="9" stroke="#C8C8C8"/><path d="M9.03316 11.402C8.92916 10.958 8.88916 10.596 8.91316 10.316C8.94116 10.032 9.03516 9.792 9.19516 9.596C9.35516 9.4 9.58516 9.206 9.88516 9.014C10.2972 8.75 10.5712 8.526 10.7072 8.342C10.8432 8.158 10.9112 7.954 10.9112 7.73C10.9112 7.374 10.7852 7.108 10.5332 6.932C10.2812 6.752 9.92716 6.662 9.47116 6.662C9.14316 6.662 8.84716 6.708 8.58316 6.8C8.31916 6.892 8.07716 7.02 7.85716 7.184L7.50916 6.086C7.64116 5.97 7.81716 5.862 8.03716 5.762C8.25716 5.658 8.51116 5.576 8.79916 5.516C9.08716 5.452 9.39716 5.42 9.72916 5.42C10.2412 5.42 10.6992 5.504 11.1032 5.672C11.5072 5.836 11.8252 6.074 12.0572 6.386C12.2932 6.694 12.4112 7.062 12.4112 7.49C12.4112 7.734 12.3672 7.964 12.2792 8.18C12.1952 8.392 12.0492 8.604 11.8412 8.816C11.6332 9.028 11.3492 9.254 10.9892 9.494C10.7332 9.662 10.5452 9.824 10.4252 9.98C10.3052 10.132 10.2372 10.318 10.2212 10.538C10.2052 10.754 10.2252 11.042 10.2812 11.402H9.03316ZM9.71116 14.18C9.45516 14.18 9.23516 14.098 9.05116 13.934C8.86716 13.77 8.77516 13.556 8.77516 13.292C8.77516 13.112 8.81716 12.956 8.90116 12.824C8.98516 12.688 9.09716 12.584 9.23716 12.512C9.38116 12.436 9.53916 12.398 9.71116 12.398C9.97116 12.398 10.1932 12.48 10.3772 12.644C10.5612 12.804 10.6532 13.02 10.6532 13.292C10.6532 13.468 10.6092 13.624 10.5212 13.76C10.4372 13.892 10.3232 13.994 10.1792 14.066C10.0392 14.142 9.88316 14.18 9.71116 14.18Z" fill="#858585"/></svg>
                    <div class="tooltip"><?=$tooltip_3?></div>
                </div>
            </div>
        </div>
        <? if($OSTATOK_V_PUTI) {?>
        <div class="catalog-element-ostatok">
            <div class="catalog-element-ostatok-name">В пути:</div>
            <div class="catalog-element-ostatok-number">
                <?=$OSTATOK_V_PUTI?> шт.
                <div class="tooltip-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none"><rect x="0.5" y="0.5" width="19" height="18" rx="9" fill="white"/><rect x="0.5" y="0.5" width="19" height="18" rx="9" stroke="#C8C8C8"/><path d="M9.03316 11.402C8.92916 10.958 8.88916 10.596 8.91316 10.316C8.94116 10.032 9.03516 9.792 9.19516 9.596C9.35516 9.4 9.58516 9.206 9.88516 9.014C10.2972 8.75 10.5712 8.526 10.7072 8.342C10.8432 8.158 10.9112 7.954 10.9112 7.73C10.9112 7.374 10.7852 7.108 10.5332 6.932C10.2812 6.752 9.92716 6.662 9.47116 6.662C9.14316 6.662 8.84716 6.708 8.58316 6.8C8.31916 6.892 8.07716 7.02 7.85716 7.184L7.50916 6.086C7.64116 5.97 7.81716 5.862 8.03716 5.762C8.25716 5.658 8.51116 5.576 8.79916 5.516C9.08716 5.452 9.39716 5.42 9.72916 5.42C10.2412 5.42 10.6992 5.504 11.1032 5.672C11.5072 5.836 11.8252 6.074 12.0572 6.386C12.2932 6.694 12.4112 7.062 12.4112 7.49C12.4112 7.734 12.3672 7.964 12.2792 8.18C12.1952 8.392 12.0492 8.604 11.8412 8.816C11.6332 9.028 11.3492 9.254 10.9892 9.494C10.7332 9.662 10.5452 9.824 10.4252 9.98C10.3052 10.132 10.2372 10.318 10.2212 10.538C10.2052 10.754 10.2252 11.042 10.2812 11.402H9.03316ZM9.71116 14.18C9.45516 14.18 9.23516 14.098 9.05116 13.934C8.86716 13.77 8.77516 13.556 8.77516 13.292C8.77516 13.112 8.81716 12.956 8.90116 12.824C8.98516 12.688 9.09716 12.584 9.23716 12.512C9.38116 12.436 9.53916 12.398 9.71116 12.398C9.97116 12.398 10.1932 12.48 10.3772 12.644C10.5612 12.804 10.6532 13.02 10.6532 13.292C10.6532 13.468 10.6092 13.624 10.5212 13.76C10.4372 13.892 10.3232 13.994 10.1792 14.066C10.0392 14.142 9.88316 14.18 9.71116 14.18Z" fill="#858585"/></svg>
                    <div class="tooltip"><?=$tooltip_2?></div>
                </div>
            </div> 
        </div>
        <? } ?>
    </div>
<?
}

// Отрисовываем блоки для всех офферов
foreach ($arResult['OFFERS'] as $offer) {
    showReservedInfo($offer);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для показа информации по артикулу
    function showStockByArticle(article) {
        // Скрываем все блоки
        var allStockBlocks = document.querySelectorAll('.offer-stock-info');
        allStockBlocks.forEach(function(block) {
            block.style.display = 'none';
        });
        
        // Показываем блок для нужного артикула
        var selectedBlock = document.querySelector('.offer-stock-info[data-article="' + article + '"]');
        if (selectedBlock) {
            selectedBlock.style.display = 'block';
        }
    }
    
    // Функция для отслеживания изменений артикула
    function observeArticleChanges() {
        var articleElement = document.querySelector('[data-role="article.value"]');
        
        if (articleElement) {
            // Начальное значение
            var currentArticle = articleElement.textContent.trim();
            showStockByArticle(currentArticle);
            
            // MutationObserver для отслеживания изменений текста
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'characterData' || mutation.type === 'childList') {
                        var newArticle = articleElement.textContent.trim();
                        if (newArticle !== currentArticle) {
                            currentArticle = newArticle;
                            showStockByArticle(currentArticle);
                        }
                    }
                });
            });
            
            observer.observe(articleElement, {
                characterData: true,
                childList: true,
                subtree: true
            });
        }
    }
    
    // Запускаем отслеживание
    observeArticleChanges();
    
    // Дополнительно отслеживаем клики по офферам на случай, если артикул меняется не через span
    document.addEventListener('click', function(e) {
        var offerElement = e.target.closest('.catalog-element-offer, .offer-variant');
        if (offerElement) {
            // Даем время на обновление артикула
            setTimeout(function() {
                var articleElement = document.querySelector('[data-role="article.value"]');
                if (articleElement) {
                    var article = articleElement.textContent.trim();
                    showStockByArticle(article);
                }
            }, 100);
        }
    });
});
</script>

<?/* endif; */?>