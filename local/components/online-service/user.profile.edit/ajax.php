<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

header('Content-Type: application/json');

if (!$USER->IsAuthorized()) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    die();
}

$request = Application::getInstance()->getContext()->getRequest();

// Определяем тип запроса (JSON или FormData)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isFormData = strpos($contentType, 'multipart/form-data') !== false;

if ($isFormData) {
    // FormData запрос (с файлом)
    $action = $_POST['action'] ?? '';
    $sessid = $_POST['sessid'] ?? '';
    $userId = intval($_POST['userId'] ?? 0);
    $fields = json_decode($_POST['fields'] ?? '[]', true);
    
    // Обрабатываем файл фото
    if (!empty($_FILES['PERSONAL_PHOTO']) && $_FILES['PERSONAL_PHOTO']['error'] === UPLOAD_ERR_OK) {
        $fields['PERSONAL_PHOTO'] = $_FILES['PERSONAL_PHOTO'];
    }
    
    $data = [
        'userId' => $userId,
        'fields' => $fields
    ];
} else {
    // JSON запрос
    $postData = json_decode($request->getInput(), true);
    $sessid = $postData['sessid'] ?? '';
    $action = $postData['action'] ?? '';
    $data = $postData['data'] ?? [];
}

// Проверяем sessid
if (empty($sessid) || $sessid !== bitrix_sessid()) {
    echo json_encode(['success' => false, 'error' => 'Неверный sessid']);
    die();
}

// Подключаем модули
if (!Loader::includeModule('iblock')) {
    echo json_encode(['success' => false, 'error' => 'Модуль iblock не подключен']);
    die();
}

// Создаем экземпляр компонента
require_once(__DIR__ . '/class.php');
$component = new OnlineServiceUserProfileEditComponent();

$result = ['success' => false, 'error' => 'Неизвестное действие'];

try {
    switch ($action) {
        case 'saveProfile':
            $result = $component->saveProfileAction($data['userId'] ?? 0, $data['fields'] ?? []);
            break;
            
        case 'attachToCompany':
            $result = $component->attachToCompanyAction(
                $data['userId'] ?? 0,
                $data['companyId'] ?? 0,
                $data['role'] ?? 'employee'
            );
            break;
            
        case 'detachFromCompany':
            $result = $component->detachFromCompanyAction(
                $data['userId'] ?? 0,
                $data['companyId'] ?? 0
            );
            break;
            
        case 'changeRole':
            $result = $component->changeRoleAction(
                $data['userId'] ?? 0,
                $data['companyId'] ?? 0,
                $data['newRole'] ?? 'employee'
            );
            break;
    }
} catch (Exception $e) {
    $result = ['success' => false, 'error' => $e->getMessage()];
}

echo json_encode(['data' => $result]);

