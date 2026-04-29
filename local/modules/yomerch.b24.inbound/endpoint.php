<?php

use OnlineService\Sync\FromCrm\InboundGateway;
use OnlineService\Sync\InboundRequestLogger;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/bootstrap.php';

$rawBody = file_get_contents('php://input');
\OnlineService\Sync\SyncTrace::reset();
$requestId = (string)($_SERVER['HTTP_X_SYNC_REQUEST_ID'] ?? '');
if ($requestId === '') {
    $requestId = (string)($_REQUEST['REQUEST_ID'] ?? '');
}
if ($requestId === '') {
    try {
        $requestId = \bin2hex(\random_bytes(8));
    } catch (\Throwable $e) {
        $requestId = \uniqid('sync_', true);
    }
}
\OnlineService\Sync\SyncTrace::setRequestId($requestId);
$_REQUEST['_SYNC_REQUEST_ID'] = $requestId;
if (!headers_sent()) {
    header('X-Sync-Request-Id: ' . $requestId);
}
\OnlineService\Sync\InboundSecurity::assertInboundAllowed(is_string($rawBody) ? $rawBody : '');
InboundRequestLogger::logRequest($_REQUEST, is_string($rawBody) ? $rawBody : '');
InboundGateway::dispatch($_REQUEST);
