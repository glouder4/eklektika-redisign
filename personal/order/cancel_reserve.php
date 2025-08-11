<?php
use Bitrix\Main\Loader;
use Bitrix\Sale;
use intec\eklectika\advertising_agent\Client;

define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;
$userId = $USER->GetID();

Loader::includeModule('sale');

$orderId = $_REQUEST['RESERVE_ID'];
$NEW_STATUS_ID = 'RO';

// Функция для возврата JSON-ответа
function returnJsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Проверяем, что пользователь авторизован
if (!$userId) {
    returnJsonResponse(false, 'Пользователь не авторизован');
}

// Проверяем, что передан ID заказа
if (!$orderId) {
    returnJsonResponse(false, 'Не указан ID заказа');
}

try {
    // Получаем заказ
    $order = Sale\Order::load($orderId);

    if (!$order) {
        returnJsonResponse(false, 'Заказ не найден');
    }

    // Проверяем, является ли текущий пользователь собственником заказа
    $orderUserId = $order->getUserId();

    if ($orderUserId != $userId) {
        returnJsonResponse(false, 'У вас нет прав для отмены этого заказа');
    }

    // Проверяем текущий статус заказа
    $currentStatus = $order->getField('STATUS_ID');

    // Изменяем статус заказа
    $result = $order->setField('STATUS_ID', $NEW_STATUS_ID);

    if ($result->isSuccess()) {
        // Сохраняем изменения
        $saveResult = $order->save();

        if ($saveResult->isSuccess()) {
            returnJsonResponse(true, 'Статус заказа успешно изменен на: ' . $NEW_STATUS_ID);
        } else {
            returnJsonResponse(false, 'Ошибка при сохранении заказа: ' . implode(', ', $saveResult->getErrorMessages()));
        }
    } else {
        returnJsonResponse(false, 'Ошибка при изменении статуса: ' . implode(', ', $result->getErrorMessages()));
    }

} catch (Exception $e) {
    returnJsonResponse(false, 'Произошла ошибка: ' . $e->getMessage());
}
?>