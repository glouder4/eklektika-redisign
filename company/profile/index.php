<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/**
 * @global $APPLICATION
 */

$APPLICATION->SetTitle("Компания");

// Получаем код элемента из URL
$elementCode = $_REQUEST['ELEMENT_CODE'] ?? '';

// Убираем GET параметры если они попали в ELEMENT_CODE
if (strpos($elementCode, '?') !== false) {
    $elementCode = substr($elementCode, 0, strpos($elementCode, '?'));
}

?>
<div class="container personal-profile-wrapper">
    <?$APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "os-personal-profile",
        Array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_ELEMENT_CHAIN" => "Y",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BROWSER_TITLE" => "-",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "N",
            "CHECK_DATES" => "N",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_CODE" => $elementCode,
            "ELEMENT_ID" => "",
            "FIELD_CODE" => array("", ""),
            "IBLOCK_ID" => "57",
            "IBLOCK_TYPE" => "personal",
            "IBLOCK_URL" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Страница",
            "PROPERTY_CODE" => array("OS_COMPANY_EMAIL", "OS_COMPANY_IS_HEAD_OF_HOLDING", "OS_COMPANY_INN", "OS_COMPANY_BOSS", "OS_COMPANY_USERS", "OS_COMPANY_PHONE", "OS_IS_MARKETING_AGENT", ""),
            "SET_BROWSER_TITLE" => "Y",
            "SET_CANONICAL_URL" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "Y",
            "SHOW_404" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "USE_PERMISSIONS" => "N",
            "USE_SHARE" => "N"
        )
    );?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>