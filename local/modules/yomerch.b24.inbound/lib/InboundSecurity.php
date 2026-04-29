<?php
namespace OnlineService\Sync;

/**
 * Проверка входящих запросов на ajax.php (канал CRM → сайт).
 * Fail-closed: без inbound_secret запрос запрещается, если явно не включен dev-override.
 */
class InboundSecurity
{
    public static function assertInboundAllowed(string $rawBody = ''): void
    {
        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $result = self::verifyRequest($_SERVER, $_REQUEST, $cfg, $rawBody);
        if (!$result['allowed']) {
            self::deny($result['error_code']);
        }
    }

    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $request
     * @param array<string, mixed> $cfg
     * @return array{allowed:bool,error_code?:string}
     */
    public static function verifyRequest(array $server, array $request, array $cfg, string $rawBody = ''): array
    {
        $secret = (string)($cfg['inbound_secret'] ?? '');

        if (self::isPostMethodRequired($cfg)) {
            $method = \strtoupper((string)($server['REQUEST_METHOD'] ?? ''));
            if ($method !== 'POST') {
                return ['allowed' => false, 'error_code' => 'sync_method_not_allowed'];
            }
        }

        $timestampCheck = self::verifyTimestamp($server, $request, $cfg);
        if (!$timestampCheck['allowed']) {
            return $timestampCheck;
        }

        $signatureCheck = self::verifySignature($server, $cfg, $rawBody);
        if (!$signatureCheck['allowed']) {
            return $signatureCheck;
        }

        if ($secret === '' && !self::isDevOverrideEnabled($cfg)) {
            return ['allowed' => false, 'error_code' => 'sync_forbidden'];
        }
        if ($secret === '') {
            return ['allowed' => true]; // explicit dev-override only
        }

        $tokenFromHeader = $server['HTTP_X_SYNC_TOKEN'] ?? '';
        $tokenFromRequest = $request['sync_token'] ?? '';
        $token = self::isHeaderTokenRequired($cfg) ? $tokenFromHeader : ($tokenFromHeader !== '' ? $tokenFromHeader : $tokenFromRequest);
        $token = \is_scalar($token) ? (string)$token : '';

        if ($token === '' || !\hash_equals($secret, $token)) {
            return ['allowed' => false, 'error_code' => 'sync_forbidden'];
        }

        return ['allowed' => true];
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function isDevOverrideEnabled(array $cfg): bool
    {
        return self::boolConfigValue(
            $cfg['allow_inbound_without_secret'] ?? \getenv('YOMERCH_ALLOW_INBOUND_WITHOUT_SECRET') ?? ''
        );
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function isPostMethodRequired(array $cfg): bool
    {
        return self::boolConfigValue($cfg['require_post_method'] ?? false);
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function isHeaderTokenRequired(array $cfg): bool
    {
        return self::boolConfigValue($cfg['inbound_require_header_token'] ?? false);
    }

    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $request
     * @param array<string, mixed> $cfg
     * @return array{allowed:bool,error_code?:string}
     */
    private static function verifyTimestamp(array $server, array $request, array $cfg): array
    {
        $maxSkew = (int)($cfg['inbound_max_skew_seconds'] ?? 0);
        if ($maxSkew <= 0) {
            return ['allowed' => true];
        }

        $raw = $server['HTTP_X_SYNC_TIMESTAMP'] ?? ($request['sync_ts'] ?? '');
        $value = \is_scalar($raw) ? \trim((string)$raw) : '';
        if ($value === '' || !\preg_match('/^\d+$/', $value)) {
            return ['allowed' => false, 'error_code' => 'sync_timestamp_invalid'];
        }

        $timestamp = (int)$value;
        if ($timestamp <= 0) {
            return ['allowed' => false, 'error_code' => 'sync_timestamp_invalid'];
        }

        $drift = \abs(\time() - $timestamp);
        if ($drift > $maxSkew) {
            return ['allowed' => false, 'error_code' => 'sync_timestamp_expired'];
        }

        return ['allowed' => true];
    }

    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $cfg
     * @return array{allowed:bool,error_code?:string}
     */
    private static function verifySignature(array $server, array $cfg, string $rawBody): array
    {
        $hmacSecret = (string)($cfg['inbound_hmac_secret'] ?? '');
        if ($hmacSecret === '') {
            return ['allowed' => true];
        }

        $header = $server['HTTP_X_SYNC_SIGNATURE'] ?? '';
        $signature = \is_scalar($header) ? \trim((string)$header) : '';
        if ($signature === '') {
            return ['allowed' => false, 'error_code' => 'sync_signature_missing'];
        }
        if (\strpos($signature, 'sha256=') === 0) {
            $signature = \substr($signature, 7);
        }
        if ($signature === '' || !\preg_match('/^[a-f0-9]{64}$/i', $signature)) {
            return ['allowed' => false, 'error_code' => 'sync_signature_invalid'];
        }

        $expected = \hash_hmac('sha256', $rawBody, $hmacSecret);
        if (!\hash_equals($expected, \strtolower($signature))) {
            return ['allowed' => false, 'error_code' => 'sync_signature_invalid'];
        }

        return ['allowed' => true];
    }

    /**
     * @param mixed $raw
     */
    private static function boolConfigValue($raw): bool
    {
        if (\is_bool($raw)) {
            return $raw;
        }

        $value = \is_scalar($raw) ? \strtolower(\trim((string)$raw)) : '';

        return \in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    private static function deny(string $error): void
    {
        $requestId = SyncTrace::getRequestId();
        SyncInboundLog::line(
            '[inbound] reject '
            . $error
            . ' request_id='
            . $requestId
            . ' ip='
            . (string)($_SERVER['REMOTE_ADDR'] ?? 'n/a')
        );
        if (!headers_sent()) {
            http_response_code(403);
            header('Content-Type: application/json; charset=UTF-8');
            if ($requestId !== '') {
                header('X-Sync-Request-Id: ' . $requestId);
            }
        }
        echo json_encode([
            'success' => 0,
            'error' => $error,
            'error_code' => $error,
            'request_id' => $requestId,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
