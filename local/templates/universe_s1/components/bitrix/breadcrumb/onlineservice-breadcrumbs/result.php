<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use intec\core\helpers\ArrayHelper;
pre("here2");
/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('iblock'))
    return;
// Мутация наименования раздела
if (!empty($arResult)) {
    // Создаем массив соответствий ссылок и кастомных названий
    $customTitles = [];
    
    if (!empty($arParams['ITEM_0']) && !empty($arParams['ITEM_0_LINK'])) {
        $customTitles[$arParams['ITEM_0_LINK']] = $arParams['ITEM_0'];
    }
    
    if (!empty($arParams['ITEM_1']) && !empty($arParams['ITEM_1_LINK'])) {
        $customTitles[$arParams['ITEM_1_LINK']] = $arParams['ITEM_1'];
    }
    
    if (!empty($arParams['ITEM_2']) && !empty($arParams['ITEM_2_LINK'])) {
        $customTitles[$arParams['ITEM_2_LINK']] = $arParams['ITEM_2'];
    }
    
    if (!empty($arParams['ITEM_3']) && !empty($arParams['ITEM_3_LINK'])) {
        $customTitles[$arParams['ITEM_3_LINK']] = $arParams['ITEM_3'];
    }
    
    if (!empty($arParams['ITEM_4']) && !empty($arParams['ITEM_4_LINK'])) {
        $customTitles[$arParams['ITEM_4_LINK']] = $arParams['ITEM_4'];
    }
    
    if (!empty($arParams['ITEM_5']) && !empty($arParams['ITEM_5_LINK'])) {
        $customTitles[$arParams['ITEM_5_LINK']] = $arParams['ITEM_5'];
    }
    
    // Применяем кастомные названия
    foreach ($arResult as &$arItem) {
        if (isset($customTitles[$arItem['LINK']])) {
            $arItem['TITLE'] = $customTitles[$arItem['LINK']];
        }
    }
    unset($arItem);
}

// Дополнительная логика для динамического определения названий разделов
if (!empty($arParams['DYNAMIC_TITLES']) && $arParams['DYNAMIC_TITLES'] === 'Y') {
    // Получаем текущий URL через Application
    $context = Application::getInstance()->getContext();
    $currentUrl = $context->getRequest()->getRequestedPage();
    
    // Получаем название страницы из базы данных по URL
    if (Loader::includeModule('iblock')) {
        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ACTIVE' => 'Y',
            '=DETAIL_PAGE_URL' => $currentUrl
        ];
        
        $arSelect = ['ID', 'NAME', 'DETAIL_PAGE_URL'];
        $rsElement = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        
        if ($arElement = $rsElement->GetNext()) {
            // Находим соответствующий элемент в хлебных крошках и обновляем его название
            foreach ($arResult as &$arItem) {
                if ($arItem['LINK'] === $currentUrl) {
                    $arItem['TITLE'] = $arElement['NAME'];
                    break;
                }
            }
            unset($arItem);
        }
    }
}

// Специальная обработка для страниц услуг
$context = Application::getInstance()->getContext();
$currentPage = $context->getRequest()->getRequestedPage();

if (strpos($currentPage, '/services/') !== false) {
    // Получаем название услуги из заголовка страницы через глобальную переменную
    global $APPLICATION;
    $pageTitle = $APPLICATION->GetTitle();
    
    if (!empty($pageTitle)) {
        // Находим последний элемент в хлебных крошках (текущая страница)
        $lastIndex = count($arResult) - 1;
        if ($lastIndex >= 0) {
            $arResult[$lastIndex]['TITLE'] = $pageTitle;
        }
    }
} 