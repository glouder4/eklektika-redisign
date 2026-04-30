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
                    'request_payload' => self::redactPayloadAssociative($request),
                ],
                JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
            )
        );
    }

    /**
     * Полное дерево запроса с маскированием секретов (для sync_inbound_log / sync_inbound_trace_full_payload).
     *
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public static function redactPayloadAssociative(array $request): array
    {
        $sanitized = [];
        foreach ($request as $key => $item) {
            $k = (string)$key;
            if (self::isSensitiveFieldName($k)) {
                $sanitized[$key] = '***';
                continue;
            }
            $sanitized[$key] = self::redactValueRecursive($item);
        }

        return $sanitized;
    }

    /**
     * Компактные значения верхнего уровня для `[trace] request` (без полного дерева файлов/длинных строк).
     *
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public static function shallowParamsPreviewForTrace(array $request, int $maxScalarLen = 400, int $maxListItems = 10, int $maxTopKeys = 96): array
    {
        $keys = \array_keys($request);
        \sort($keys, \SORT_STRING);
        $out = [];
        $n = 0;
        foreach ($keys as $k) {
            if (++$n > $maxTopKeys) {
                $out['_preview_truncated'] = 'remaining_keys=' . (\count($keys) - $maxTopKeys);

                break;
            }
            if (!\is_string($k)) {
                continue;
            }
            if (self::isSensitiveFieldName($k)) {
                $out[$k] = '***';
                continue;
            }
            if (!\array_key_exists($k, $request)) {
                continue;
            }
            $out[$k] = self::shallowValueForTrace($request[$k], $maxScalarLen, $maxListItems);
        }

        return $out;
    }

    private static function isSensitiveFieldName(string $k): bool
    {
        return \preg_match('/token|secret|password|passwd|auth|key/i', $k) === 1;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function redactValueRecursive($value)
    {
        if (\is_array($value)) {
            $sanitized = [];
            foreach ($value as $key => $item) {
                $k = (string)$key;
                if (self::isSensitiveFieldName($k)) {
                    $sanitized[$key] = '***';
                    continue;
                }
                $sanitized[$key] = self::redactValueRecursive($item);
            }

            return $sanitized;
        }

        if (\is_string($value) && mb_strlen($value) > 1200) {
            return mb_substr($value, 0, 1200) . '...<truncated>';
        }

        return $value;
    }

    /**
     * @param mixed $v
     * @return mixed
     */
    private static function shallowValueForTrace($v, int $maxScalarLen, int $maxListItems)
    {
        if ($v === null || \is_bool($v) || \is_int($v) || \is_float($v)) {
            return $v;
        }
        if (\is_string($v)) {
            return mb_strlen($v) > $maxScalarLen ? mb_substr($v, 0, $maxScalarLen) . '…' : $v;
        }
        if (!\is_array($v)) {
            return '(' . \get_debug_type($v) . ')';
        }
        if ($v === []) {
            return [];
        }
        $keys = \array_keys($v);
        $isList = $keys !== [] && $keys === \range(0, \count($v) - 1);
        if ($isList) {
            $out = [];
            $lim = \min($maxListItems, \count($v));
            for ($i = 0; $i < $lim; $i++) {
                $out[] = self::shallowValueForTrace($v[$i], \min(160, $maxScalarLen), 4);
            }
            if (\count($v) > $lim) {
                $out[] = '…+' . (string)(\count($v) - $lim) . ' items';
            }

            return $out;
        }
        $out = [];
        $c = 0;
        foreach ($v as $sk => $sv) {
            if (++$c > 14) {
                $out['…'] = 'truncated_nested_assoc';

                break;
            }
            $ks = (string)$sk;
            if (self::isSensitiveFieldName($ks)) {
                $out[$ks] = '***';
            } elseif (\is_array($sv)) {
                $out[$ks] = '[array n=' . \count($sv) . ']';
            } else {
                $out[$ks] = self::shallowValueForTrace($sv, $maxScalarLen, $maxListItems);
            }
        }

        return $out;
    }
}
