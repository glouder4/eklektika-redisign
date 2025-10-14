<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/**
 * @global $APPLICATION
 */
// Получаем ID пользователя из URL
$userId = $_REQUEST['id'] ?? $_REQUEST['USER_ID'] ?? '';
$userId = intval($userId);

// Получаем тип редактирования (profile или companies)
$type = $_REQUEST['type'] ?? 'profile';


// Проверяем доступ
if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}

?>
<div class="container personal-profile-wrapper">
    <?$APPLICATION->IncludeComponent(
        "online-service:user.profile.edit",
        ".default",
        Array(
            "USER_ID" => $userId,
            "TYPE" => $type,
            "CACHE_TIME" => 0, // Отключаем кеш для формы редактирования
        )
    );?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>

