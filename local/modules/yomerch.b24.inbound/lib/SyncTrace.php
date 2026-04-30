<?php

declare(strict_types=1);

namespace OnlineService\Sync;

/**
 * Общий отладочный режим обмена с B24: флаг `sync_debug` в
 * $GLOBALS['YOMERCH_SYNC_CONFIG'] (fallback: `EKLEKTIKA_SYNC_CONFIG`)
 * (см. local/modules/yomerch.b24.inbound/config.local.php).
 *
 * При включении: пишет inbound-лог, `debug_trace` в JSON ответа inbound, и **должен использоваться
 * в других сценариях** (исходящий crm.* из ЛК, точечные pre+stop и т.д.) вместо отдельных флагов.
 *
 * `summarizeRequest()` всегда добавляет `params_preview` (значения верхнего уровня с маскированием секретов).
 * Полное дерево `payload_redacted` — при `sync_inbound_trace_full_payload` в sync config.
 *
 * @see self::enabled()
 */
final class SyncTrace
{
    private const MAX_LINES = 80;

    /** @var list<string> */
    private static array $buffer = [];
    private static string $requestId = '';

    public static function reset(): void
    {
        self::$buffer = [];
        self::$requestId = '';
    }

    public static function enabled(): bool
    {
        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $v = $cfg['sync_debug'] ?? false;

        return $v === true || $v === 1 || $v === '1' || $v === 'on' || $v === 'ON' || $v === 'yes' || $v === 'YES';
    }

    /**
     * Синоним {@see self::enabled()} — удобно в коде, где важен смысл «включён общий sync-дебаг».
     */
    public static function isDebugModeEnabled(): bool
    {
        return self::enabled();
    }

    public static function setRequestId(string $requestId): void
    {
        self::$requestId = \trim($requestId);
    }

    public static function getRequestId(): string
    {
        return self::$requestId;
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function add(string $step, array $context = []): void
    {
        if (!self::enabled()) {
            return;
        }
        $suffix = $context === [] ? '' : ' ' . \json_encode($context, \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE);
        $line = \date('c') . ' ' . $step . $suffix;
        if (\count(self::$buffer) >= self::MAX_LINES) {
            \array_shift(self::$buffer);
        }
        self::$buffer[] = $line;
        SyncInboundLog::line('[trace] ' . $step . $suffix);
    }

    /**
     * @return list<string>|null
     */
    public static function flushLines(): ?array
    {
        if (!self::enabled() || self::$buffer === []) {
            return null;
        }

        return self::$buffer;
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public static function summarizeRequest(array $request): array
    {
        $keys = \array_keys($request);
        \sort($keys, \SORT_STRING);

        $summary = [
            'request_id' => self::getRequestId(),
            'ACTION' => (string)($request['ACTION'] ?? ''),
            'param_keys' => $keys,
            'OS_COMPANY_B24_ID' => $request['OS_COMPANY_B24_ID'] ?? null,
            'ID' => $request['ID'] ?? null,
        ];

        if (\class_exists(InboundRequestLogger::class)) {
            $summary['params_preview'] = InboundRequestLogger::shallowParamsPreviewForTrace($request);
        }

        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $full = $cfg['sync_inbound_trace_full_payload'] ?? false;
        if ($full === true || $full === 1 || $full === '1' || $full === 'on' || $full === 'ON' || $full === 'yes' || $full === 'YES') {
            if (\class_exists(InboundRequestLogger::class)) {
                $summary['payload_redacted'] = InboundRequestLogger::redactPayloadAssociative($request);
            }
        }

        return $summary;
    }
}
