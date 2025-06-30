<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('iblock'))
    return;

// Мутация наименования раздела
if (!empty($arResult)) {
    foreach ($arResult as &$arItem) {
        // Проверяем, есть ли параметры для переопределения названий
        if (!empty($arParams['ITEM_0']) && $arItem['LINK'] === '/') {
            $arItem['TITLE'] = $arParams['ITEM_0'];
        }
        
        if (!empty($arParams['ITEM_1']) && $arItem['LINK'] === '/services/') {
            $arItem['TITLE'] = $arParams['ITEM_1'];
        }
        
        // Можно добавить дополнительные проверки для других разделов
        // Например, для конкретных страниц услуг
        if (!empty($arParams['ITEM_2']) && strpos($arItem['LINK'], '/nanesenie-logotipov-na-ezhednevniki/') !== false) {
            $arItem['TITLE'] = $arParams['ITEM_2'];
        }
        
        // Универсальная проверка по URL
        if (!empty($arParams['CUSTOM_TITLES']) && is_array($arParams['CUSTOM_TITLES'])) {
            foreach ($arParams['CUSTOM_TITLES'] as $url => $title) {
                if ($arItem['LINK'] === $url) {
                    $arItem['TITLE'] = $title;
                    break;
                }
            }
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