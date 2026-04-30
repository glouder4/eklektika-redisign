<?php
/**
 * Module-owned bootstrap for inbound CRM -> site channel.
 */
$syncConfig = [
    'inbound_profile' => 'default',
    'inbound_secret' => '',
    'require_post_method' => false,
    'inbound_require_header_token' => false,
    'inbound_max_skew_seconds' => 0,
    'inbound_hmac_secret' => '',
    'inbound_dedup_ttl_seconds' => 0,
    'inbound_dedup_store_path' => '',
    'inbound_disabled_actions' => [],
    'inbound_legacy_plain_responses' => true,
    'sync_debug' => false,
    'sync_inbound_trace_full_payload' => false,
    'sync_inbound_log' => false,
    'sync_primitive_breakpoint_step' => '',
];

$configLocal = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/yomerch.b24.inbound/config.local.php';
if (is_file($configLocal)) {
    $local = include $configLocal;
    if (is_array($local)) {
        $syncConfig = array_replace_recursive($syncConfig, $local);
    }
}

$profile = is_scalar($syncConfig['inbound_profile'] ?? null)
    ? strtolower(trim((string)$syncConfig['inbound_profile']))
    : 'default';
if ($profile === 'strict') {
    // Strict profile: deterministic and fail-closed defaults for stable handoff.
    if ((string)($syncConfig['inbound_secret'] ?? '') !== '') {
        $syncConfig['require_post_method'] = true;
        $syncConfig['inbound_require_header_token'] = true;
    }
    $syncConfig['inbound_legacy_plain_responses'] = false;
    if ((int)($syncConfig['inbound_dedup_ttl_seconds'] ?? 0) <= 0) {
        $syncConfig['inbound_dedup_ttl_seconds'] = 3600;
    }
}

$GLOBALS['EKLEKTIKA_SYNC_CONFIG'] = $syncConfig;
$GLOBALS['YOMERCH_SYNC_CONFIG'] = $syncConfig;

require_once __DIR__ . '/SyncInboundLog.php';
require_once __DIR__ . '/SyncTrace.php';
require_once __DIR__ . '/SyncPrimitiveBreakpoint.php';
require_once __DIR__ . '/InboundSecurity.php';
require_once __DIR__ . '/InboundIdempotencyGate.php';
require_once __DIR__ . '/from-crm/CrmInboundUfMap.php';
require_once __DIR__ . '/from-crm/InboundGateway.php';
require_once __DIR__ . '/to-crm/OutboundUpdateContactPayload.php';
