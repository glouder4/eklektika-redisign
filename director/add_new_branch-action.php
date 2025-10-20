<?php
/**
 * AJAX обработчик для создания дочерней компании (филиала)
 * 
 * @package OnlineService\BranchCompany
 */

define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use OnlineService\Site\Company;

header('Content-Type: application/json; charset=utf-8');

global $USER;

// Проверяем авторизацию
if (!$USER->IsAuthorized()) {
    echo json_encode([
        'success' => false,
        'message' => 'Необходимо авторизоваться'
    ]);
    die();
}

// Проверяем метод запроса
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный метод запроса'
    ]);
    die();
}

try {
    // Получаем ID головной компании
    $headCompanyElementId = intval($_POST['head_company_element_id'] ?? 0);
    
    if (empty($headCompanyElementId)) {
        echo json_encode([
            'success' => false,
            'message' => 'Не указана головная компания'
        ]);
        die();
    }

    // Создаем экземпляр класса Company
    $company = new Company();

    // Проверяем права доступа
    $permissionCheck = $company->checkBranchCreatePermission($headCompanyElementId, $USER->GetID());
    
    if (!$permissionCheck['has_access']) {
        echo json_encode([
            'success' => false,
            'message' => $permissionCheck['message'] ?? 'У вас нет прав для создания дочерней компании'
        ]);
        die();
    }

    // Получаем данные файла реквизитов
    $uploadedFile = null;
    if (isset($_FILES['UF_REQ']) && $_FILES['UF_REQ']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadedFile = $_FILES['UF_REQ'];
    }

    // Выполняем создание дочерней компании через метод класса Company
    $result = $company->createBranchCompany($_POST, $uploadedFile);

    // Формируем ответ
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'redirect' => '/personal/profile/',
            'company_id' => $result['data']['company_id'] ?? null,
            'company_b24_id' => $result['data']['company_b24_id'] ?? null
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }

} catch (Exception $e) {
    // Логируем ошибку для отладки
    error_log('Branch company create error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Произошла ошибка при создании дочерней компании'
    ]);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");