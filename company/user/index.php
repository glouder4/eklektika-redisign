<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/**
 * @global $APPLICATION
 */

// Получаем ID пользователя из URL
$userId = $_REQUEST['USER_ID'] ?? '';
$userId = intval($userId);

// Проверяем, что ID пользователя указан
if (empty($userId)) {
    LocalRedirect('/company/');
}

?>
<div class="container personal-profile-wrapper">
    <?$APPLICATION->IncludeComponent(
        "online-service:user.profile",
        ".default",
        Array(
            "USER_ID" => $userId,
            "CACHE_TIME" => 3600,
        )
    );?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
