     <?php
     // Включаем отладку (временно: раскомментируйте для ошибок, потом закомментируйте)
     // error_reporting(E_ALL); ini_set('display_errors', 1);

     // Подключаем Bitrix
     require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

     if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

     // Подключаем модули (если нужно)
     CModule::IncludeModule('main');

     // Проверка доступа: ЗАКОММЕНТИРОВАНА для публичных файлов (раскомментируйте, если файлы приватные)
     // if (!check_bitrix_sessid()) {
     //     http_response_code(403);
     //     die('Access denied');
     // }

     $fileId = (int)($_GET['file_id'] ?? 0);
     if (!$fileId) {
         http_response_code(400);
         die('File ID not specified');
     }

     // Получаем файл: сначала пробуем CFile (универсально), fallback на FileTable
     $arFile = CFile::GetFileArray($fileId);
     if (!$arFile || !is_array($arFile)) {
         // Fallback на D7 API, если CFile не сработал
         if (class_exists('\Bitrix\Main\FileTable')) {
             $arFile = \Bitrix\Main\FileTable::getById($fileId)->fetch();
         }
     }
     if (!$arFile || !is_array($arFile)) {
         http_response_code(404);
         die('File not found');
     }

     // Оригинальное имя
     $originalName = $arFile['ORIGINAL_NAME'] ?: basename($arFile['FILE_NAME'] ?: $arFile['FILE_NAME']);
     if (empty($originalName)) {
         $originalName = 'file_' . $fileId; // Fallback, если ничего нет
     }

     // MIME-тип
     $mimeType = $arFile['CONTENT_TYPE'] ?: 'application/octet-stream';

     // Путь к файлу: используем SRC из CFile (он относительный, добавляем DOCUMENT_ROOT)
     $filePath = $_SERVER['DOCUMENT_ROOT'] . ($arFile['SRC'] ?? '/upload/' . $arFile['FILE_NAME']);

     // Проверяем существование
     if (!file_exists($filePath)) {
         http_response_code(404);
         die('File not readable. Path: ' . $filePath); // Для отладки
     }

     // Заголовки для скачивания с оригинальным именем (UTF-8 для кириллицы)
     header('Content-Type: ' . $mimeType);
     header('Content-Disposition: attachment; filename="' . $originalName . '"');
     header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode($originalName));
     header('Content-Length: ' . ($arFile['FILE_SIZE'] ?? filesize($filePath)));
     header('Cache-Control: private, max-age=0, must-revalidate');
     header('Pragma: public');
     header('Expires: 0');

     // Очищаем буфер и выводим файл
     if (ob_get_level()) ob_end_clean();
     flush();
     readfile($filePath);

     // Завершаем Bitrix
     require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
     ?>
     