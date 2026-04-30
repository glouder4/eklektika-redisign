<?php

use OnlineService\Sync\FromCrm\InboundGateway;
use OnlineService\Sync\InboundRequestLogger;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/bootstrap.php';

$rawBody = file_get_contents('php://input');
$rawBodyStr = \is_string($rawBody) ? $rawBody : '';
// CRM часто шлёт JSON в raw body при заголовке `application/x-www-form-urlencoded`;
// PHP тогда не заполняет $_POST → `sync_token` и поля ACTION не попадают в $_REQUEST.
if ($rawBodyStr !== '') {
    $lead = \ltrim($rawBodyStr);
    if ($lead !== '' && $lead[0] === '{') {
        $decoded = \json_decode($rawBodyStr, true);
        if (\is_array($decoded)) {
            $keys = \array_keys($decoded);
            $isIndexedList = $keys !== [] && $keys === \range(0, \count($decoded) - 1);
            if (!$isIndexedList) {
                foreach ($decoded as $key => $value) {
                    if (!\is_string($key) || $key === '') {
                        continue;
                    }
                    $_REQUEST[$key] = $value;
                }
            }
        }
    }
}
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
if (isset($_REQUEST['ACTION']) && (string)$_REQUEST['ACTION'] === 'UPDATE_STATUS_GROUP') {
    $_REQUEST['ACTION'] = 'UPDATE_GROUP';
}
if (!headers_sent()) {
    header('X-Sync-Request-Id: ' . $requestId);
}
\OnlineService\Sync\InboundSecurity::assertInboundAllowed(is_string($rawBody) ? $rawBody : '');
InboundRequestLogger::logRequest($_REQUEST, is_string($rawBody) ? $rawBody : '');
InboundGateway::dispatch($_REQUEST);
