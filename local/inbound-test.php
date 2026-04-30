<?php

declare(strict_types=1);

/**
 * Самодиагностика канала CRM → сайт (yomerch.b24.inbound).
 *
 * Доступ (HTTP):
 * - по умолчанию — разрешён без ключа (секреты в ответе только маскируются). На проде задайте в config.local.php:
 *   'inbound_diag_allow_open' => false — тогда только localhost, CLI, администратор Bitrix (сессия) или ?sync_token=
 * - CLI: php local/inbound-test.php
 *
 * Ответ — JSON (параметр pretty=1 для форматирования).
 */

if (PHP_SAPI !== 'cli') {
    header('Content-Type: application/json; charset=UTF-8');
}

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$prologAfter = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_after.php';
if (\is_file($prologAfter)) {
    require_once $prologAfter;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/bootstrap.php';

/**
 * @param array<string, mixed> $cfg
 */
function inbound_diag_bool(mixed $raw): bool
{
    if (\is_bool($raw)) {
        return $raw;
    }
    $v = \is_scalar($raw) ? \strtolower(\trim((string)$raw)) : '';

    return \in_array($v, ['1', 'true', 'yes', 'on'], true);
}

/**
 * @param array<string, mixed> $cfg
 */
function inbound_diag_gate(array $cfg): string
{
    if (PHP_SAPI === 'cli') {
        return 'cli';
    }

    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    if (\in_array($ip, ['127.0.0.1', '::1'], true)) {
        return 'localhost';
    }

    $secret = (string)($cfg['inbound_secret'] ?? '');
    $token = isset($_GET['sync_token']) ? (string)$_GET['sync_token'] : '';

    if ($secret !== '' && \hash_equals($secret, $token)) {
        return 'sync_token';
    }

    // По умолчанию диагностика открыта без токена (данные чувствительные не выводятся). На проде — false в config.local.php.
    $allowOpen = \array_key_exists('inbound_diag_allow_open', $cfg)
        ? inbound_diag_bool($cfg['inbound_diag_allow_open'])
        : true;
    if ($allowOpen) {
        return 'allow_open_default';
    }

    global $USER;
    if (\is_object($USER) && \method_exists($USER, 'IsAdmin') && $USER->IsAdmin()) {
        return 'bitrix_admin';
    }

    http_response_code(403);

    echo json_encode([
        'ok' => false,
        'error' => 'forbidden',
        'hint' => 'Включите inbound_diag_allow_open в config.local.php, откройте с localhost, войдите как администратор Bitrix или передайте ?sync_token= (inbound_secret).',
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

/**
 * @param mixed $value
 */
function inbound_diag_mask_scalar($value): string
{
    if ($value === null) {
        return '(null)';
    }
    if (\is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    $s = \trim((string)$value);

    return $s === '' ? '(empty)' : $s;
}

/**
 * @param mixed $secretish
 */
function inbound_diag_mask_secret($secretish): array
{
    $raw = \is_scalar($secretish) ? (string)$secretish : '';
    $len = \strlen($raw);
    if ($len === 0) {
        return ['present' => false, 'length' => 0, 'suffix4' => null];
    }

    return [
        'present' => true,
        'length' => $len,
        'suffix4' => \substr($raw, -4),
    ];
}

/** @param array<string, mixed> $cfg */
function inbound_diag_security_probe(array $cfg): array
{
    $rows = [];

    $secret = (string)($cfg['inbound_secret'] ?? '');
    $postServer = ['REQUEST_METHOD' => 'POST'];

    $baselineSecretCfg = ['inbound_secret' => 'diag-probe-token'];

    $missingToken = \OnlineService\Sync\InboundSecurity::verifyRequest($postServer, ['ACTION' => 'UPDATE_CONTACT'], $baselineSecretCfg);
    $rows[] = [
        'name' => 'missing_token_when_secret_configured',
        'ok' => $missingToken['allowed'] === false && ($missingToken['error_code'] ?? '') === 'sync_forbidden',
        'detail' => ['allowed' => $missingToken['allowed'] ?? null, 'error_code' => $missingToken['error_code'] ?? ''],
    ];

    $goodToken = \OnlineService\Sync\InboundSecurity::verifyRequest(
        \array_merge($postServer, ['HTTP_X_SYNC_TOKEN' => 'diag-probe-token']),
        ['ACTION' => 'UPDATE_CONTACT'],
        $baselineSecretCfg
    );
    $rows[] = [
        'name' => 'post_with_matching_header_token',
        'ok' => $goodToken['allowed'] === true,
        'detail' => ['allowed' => $goodToken['allowed'] ?? null],
    ];

    $goodTokenInRequest = \OnlineService\Sync\InboundSecurity::verifyRequest(
        $postServer,
        ['ACTION' => 'UPDATE_CONTACT', 'sync_token' => 'diag-probe-token'],
        $baselineSecretCfg
    );
    $rows[] = [
        'name' => 'post_with_matching_sync_token_in_request_fields',
        'ok' => $goodTokenInRequest['allowed'] === true,
        'detail' => ['allowed' => $goodTokenInRequest['allowed'] ?? null],
    ];

    $needPost = \OnlineService\Sync\InboundSecurity::verifyRequest(
        ['REQUEST_METHOD' => 'GET', 'HTTP_X_SYNC_TOKEN' => 'diag-probe-token'],
        ['ACTION' => 'UPDATE_CONTACT'],
        ['inbound_secret' => 'diag-probe-token', 'require_post_method' => true]
    );
    $rows[] = [
        'name' => 'method_blocked_when_require_post',
        'ok' => $needPost['allowed'] === false && ($needPost['error_code'] ?? '') === 'sync_method_not_allowed',
        'detail' => ['allowed' => $needPost['allowed'] ?? null, 'error_code' => $needPost['error_code'] ?? ''],
    ];

    $hmacSecret = (string)($cfg['inbound_hmac_secret'] ?? '');
    if ($hmacSecret !== '') {
        $payload = '{"ACTION":"UPDATE_CONTACT","B24_ID":"7"}';
        $sig = \hash_hmac('sha256', $payload, $hmacSecret);
        $signedOk = \OnlineService\Sync\InboundSecurity::verifyRequest(
            [
                'REQUEST_METHOD' => 'POST',
                'HTTP_X_SYNC_TOKEN' => $secret !== '' ? $secret : 'diag-probe-token',
                'HTTP_X_SYNC_SIGNATURE' => $sig,
            ],
            ['ACTION' => 'UPDATE_CONTACT', 'B24_ID' => '7'],
            \array_merge($cfg, ['inbound_secret' => $secret !== '' ? $secret : 'diag-probe-token']),
            $payload
        );
        $rows[] = [
            'name' => 'hmac_signature_roundtrip',
            'ok' => $signedOk['allowed'] === true,
            'detail' => ['allowed' => $signedOk['allowed'] ?? null],
        ];
    } else {
        $rows[] = [
            'name' => 'hmac_signature_roundtrip',
            'ok' => true,
            'detail' => ['skipped' => true, 'reason' => 'inbound_hmac_secret not set'],
        ];
    }

    return $rows;
}

/** @param array<string, mixed> $cfg */
function inbound_diag_validator_probe(array $cfg): array
{
    unset($cfg);

    $missingAction = \OnlineService\Sync\FromCrm\InboundPayloadValidator::validate([]);
    $companyIncomplete = \OnlineService\Sync\FromCrm\InboundPayloadValidator::validate([
        'ACTION' => 'UPDATE_COMPANY',
        'ACTIVE' => 'Y',
    ]);

    return [
        [
            'name' => 'missing_ACTION_rejected',
            'ok' => $missingAction['valid'] === false && ($missingAction['reason_code'] ?? '') === 'missing_action',
            'detail' => $missingAction,
        ],
        [
            'name' => 'UPDATE_COMPANY_requires_mandatory_fields',
            'ok' => $companyIncomplete['valid'] === false,
            'detail' => ['reason_code' => $companyIncomplete['reason_code'] ?? ''],
        ],
    ];
}

$cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
if (!\is_array($cfg)) {
    $cfg = [];
}

$gateReason = inbound_diag_gate($cfg);

$configLocalPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/yomerch.b24.inbound/config.local.php';
$endpointPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/yomerch.b24.inbound/endpoint.php';

$payload = [
    'ok' => true,
    'gate' => $gateReason,
    'generated_at' => \gmdate('c'),
    'php_sapi' => PHP_SAPI,
    'paths' => [
        'document_root' => $_SERVER['DOCUMENT_ROOT'],
        'config_local_exists' => \is_file($configLocalPath),
        'config_local_path' => $configLocalPath,
        'endpoint_exists' => \is_file($endpointPath),
        'endpoint_public_hint' => '/local/modules/yomerch.b24.inbound/endpoint.php',
    ],
    'config_snapshot' => [
        'inbound_profile' => inbound_diag_mask_scalar($cfg['inbound_profile'] ?? null),
        'require_post_method' => inbound_diag_mask_scalar($cfg['require_post_method'] ?? null),
        'allow_inbound_without_secret' => inbound_diag_mask_scalar($cfg['allow_inbound_without_secret'] ?? null),
        'inbound_require_header_token' => inbound_diag_mask_scalar($cfg['inbound_require_header_token'] ?? null),
        'inbound_max_skew_seconds' => inbound_diag_mask_scalar($cfg['inbound_max_skew_seconds'] ?? null),
        'inbound_dedup_ttl_seconds' => inbound_diag_mask_scalar($cfg['inbound_dedup_ttl_seconds'] ?? null),
        'inbound_legacy_plain_responses' => inbound_diag_mask_scalar($cfg['inbound_legacy_plain_responses'] ?? null),
        'sync_debug' => inbound_diag_mask_scalar($cfg['sync_debug'] ?? null),
        'inbound_secret' => inbound_diag_mask_secret($cfg['inbound_secret'] ?? ''),
        'inbound_hmac_secret' => inbound_diag_mask_secret($cfg['inbound_hmac_secret'] ?? ''),
        'inbound_diag_allow_open' => \array_key_exists('inbound_diag_allow_open', $cfg)
            ? inbound_diag_mask_scalar($cfg['inbound_diag_allow_open'])
            : 'not_set_defaults_true',
    ],
    'checks' => [
        'security' => inbound_diag_security_probe($cfg),
        'validator' => inbound_diag_validator_probe($cfg),
    ],
];

$allOk = true;
foreach ($payload['checks']['security'] as $row) {
    if (!($row['ok'] ?? false)) {
        $allOk = false;
    }
}
foreach ($payload['checks']['validator'] as $row) {
    if (!($row['ok'] ?? false)) {
        $allOk = false;
    }
}
$payload['checks_passed'] = $allOk;

$pretty = isset($_GET['pretty'])
    && (\is_scalar($_GET['pretty']) ? \trim((string)$_GET['pretty']) : '') !== ''
    && \in_array(\strtolower((string)$_GET['pretty']), ['1', 'true', 'yes', 'on'], true);

$flags = JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;
if ($pretty) {
    $flags |= JSON_PRETTY_PRINT;
}

echo json_encode($payload, $flags);
