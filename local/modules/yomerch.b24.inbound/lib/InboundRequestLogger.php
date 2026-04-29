<?php

namespace OnlineService\Sync;

final class InboundRequestLogger
{
    public static function logRequest(array $request, string $rawBody): void
    {
        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $enabled = $cfg['sync_inbound_log'] ?? false;
        $isEnabled = $enabled === true
            || $enabled === 1
            || $enabled === '1'
            || $enabled === 'on'
            || $enabled === 'ON'
            || $enabled === 'yes'
            || $enabled === 'YES';

        if (!$isEnabled) {
            return;
        }

        $rawBodyForLog = mb_substr($rawBody, 0, 8000);
        if (mb_strlen($rawBody) > 8000) {
            $rawBodyForLog .= '...<truncated>';
        }

        SyncInboundLog::lineAlways(
            '[inbound.request] '
            . json_encode(
                [
                    'request_id' => SyncTrace::getRequestId(),
                    'method' => (string)($_SERVER['REQUEST_METHOD'] ?? ''),
                    'content_type' => (string)($_SERVER['CONTENT_TYPE'] ?? ''),
                    'raw_body' => $rawBodyForLog,
                    'request_summary' => SyncTrace::summarizeRequest($request),
                    'request_payload' => self::sanitize($request),
                ],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            )
        );
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function sanitize($value)
    {
        if (is_array($value)) {
            $sanitized = [];
            foreach ($value as $key => $item) {
                $k = (string)$key;
                if (preg_match('/token|secret|password|passwd|auth|key/i', $k)) {
                    $sanitized[$key] = '***';
                    continue;
                }
                $sanitized[$key] = self::sanitize($item);
            }

            return $sanitized;
        }

        if (is_string($value) && mb_strlen($value) > 1200) {
            return mb_substr($value, 0, 1200) . '...<truncated>';
        }

        return $value;
    }
}
