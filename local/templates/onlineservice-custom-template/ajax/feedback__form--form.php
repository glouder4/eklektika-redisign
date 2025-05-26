<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header('Content-Type: application/json; charset=UTF-8');

if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("main")) {
    echo json_encode(['success' => false, 'error' => 'Не удалось подключить модули']);
    exit;
}

if (check_bitrix_sessid() && !empty($_REQUEST["submit"])) {
    // Получаем поля
    $fields = [
        'name' => trim($_REQUEST['fio']),
        'phone' => trim($_REQUEST['phone']),
        'email' => trim($_REQUEST['email']),
        'message' => trim($_REQUEST['message']),
        'webform_id' => trim($_REQUEST['webform_id']),
    ];

    // Валидация
    foreach (['name', 'phone'] as $f) {
        if (empty($fields[$f])) {
            echo json_encode(['success' => false, 'error' => 'Заполните все обязательные поля']);
            exit;
        }
    }

    if (CModule::IncludeModule("form")) {
        $formId = (int)$fields['webform_id']; // ID вашей веб-формы
        $arValues = [
            "form_text_3" => $fields['name'],                   // ФИО
            "form_text_5" => $fields['phone'],                 // Телефон
            "form_email_6" => $fields['email'],                 // Email
            "form_textarea_4" => $fields['message'],          // Сообщение
        ];
        $RESULT_ID = 0;
        if (CFormResult::Add($formId, $arValues, "N", $RESULT_ID)) {
            // Успех, результат добавлен
            echo json_encode(['success' => true, 'fields' => $fields]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Ошибка добавления результата веб-формы']);
            exit;
        }
    }
}
echo json_encode(['success' => false, 'error' => 'Ошибка сессии или submit']);