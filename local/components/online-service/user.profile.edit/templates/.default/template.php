<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

// Получаем данные пользователя
$user = $arResult['USER'];
$type = $arResult['TYPE'];

// Формируем полное имя
$fullName = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
if (empty($fullName)) {
    $fullName = $user['LOGIN'];
}

// Настраиваем хлебные крошки
$GLOBALS["OS_BREADCRUMBS"] = [
    [
        'ITEM' => $fullName,
        "LINK" => "/company/user/" . $user['ID'],
    ],
    [
        'ITEM' => ($type === 'companies') ? 'Управление компаниями' : 'Редактирование профиля',
        "LINK" => "#",
    ]
];

// Подключаем CSS и JS
$APPLICATION->AddHeadScript($templateFolder . '/script.js');
$APPLICATION->SetAdditionalCSS($templateFolder . '/style.css');
?>

<div class="user-profile-edit-wrapper">
    <?php
    // Роутер по типу редактирования
    switch ($type) {
        case 'companies':
            include(__DIR__ . '/companies.php');
            break;
        case 'profile':
        default:
            include(__DIR__ . '/profile.php');
            break;
    }
    ?>
</div>

<script>
    // Дожидаемся загрузки DOM и Bitrix API
    if (typeof BX !== 'undefined') {
        BX.ready(function() {
            console.log('Инициализация UserProfileEdit...');
            
            if (typeof UserProfileEdit === 'undefined') {
                console.error('Класс UserProfileEdit не найден! Проверьте загрузку script.js');
                return;
            }
            
            var profileEdit = new UserProfileEdit({
                componentPath: '<?= $this->GetFolder() ?>',
                ajaxPath: '<?= $this->GetFolder() ?>/../../ajax.php',
                userId: <?= intval($user['ID']) ?>,
                type: '<?= htmlspecialchars($type) ?>',
                sessid: '<?= bitrix_sessid() ?>'
            });
            
            console.log('UserProfileEdit инициализирован', profileEdit);
        });
    } else {
        // Если BX не загружен, используем обычный DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Инициализация UserProfileEdit (без BX)...');
            
            if (typeof UserProfileEdit === 'undefined') {
                console.error('Класс UserProfileEdit не найден! Проверьте загрузку script.js');
                return;
            }
            
            var profileEdit = new UserProfileEdit({
                componentPath: '<?= $this->GetFolder() ?>',
                ajaxPath: '<?= $this->GetFolder() ?>/../../ajax.php',
                userId: <?= intval($user['ID']) ?>,
                type: '<?= htmlspecialchars($type) ?>',
                sessid: '<?= bitrix_sessid() ?>'
            });
            
            console.log('UserProfileEdit инициализирован', profileEdit);
        });
    }
</script>

