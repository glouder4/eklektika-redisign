<?php
/**
 * Copy to config.local.php (do not commit secrets).
 */
return [
    // Profiles: "default" (backward compatible) or "strict" (recommended before B24 handoff).
    'inbound_profile' => 'default',
    'inbound_secret' => '',
    // Explicit local-only override for development. Keep false by default.
    'allow_inbound_without_secret' => false,
    // Optional hardening flags (all disabled by default for backward compatibility).
    'require_post_method' => false,
    // If true, sync token must be in X-SYNC-TOKEN header (request param fallback disabled).
    'inbound_require_header_token' => false,
    // If > 0, validate X-Sync-Timestamp/sync_ts absolute drift in seconds.
    'inbound_max_skew_seconds' => 0,
    // Optional HMAC SHA-256 signature secret (header X-Sync-Signature).
    'inbound_hmac_secret' => '',
    // If > 0, deduplicate by ACTION + REQUEST_ID (or X-Sync-Request-Id) for TTL window.
    'inbound_dedup_ttl_seconds' => 0,
    // Optional custom store path for dedup state.
    'inbound_dedup_store_path' => '',
    // Optional list of blocked ACTION names.
    'inbound_disabled_actions' => [],
    // Keep true for backward compatibility where some actions return plain scalar.
    // Set false to enforce JSON responses for all actions in InboundGateway.
    'inbound_legacy_plain_responses' => true,
    'sync_debug' => false,
    'sync_inbound_log' => false,
    'sync_primitive_breakpoint_step' => '',
];
