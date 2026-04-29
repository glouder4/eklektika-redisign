<?php

namespace OnlineService\Sync;

final class InboundIdempotencyGate
{
    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $cfg
     * @return array{allowed:bool,reason_code?:string,idempotency_key?:string}
     */
    public static function assertNotDuplicate(array $request, array $cfg): array
    {
        $ttl = (int)($cfg['inbound_dedup_ttl_seconds'] ?? 0);
        if ($ttl <= 0) {
            return ['allowed' => true];
        }

        $key = self::buildKey($request);
        if ($key === '') {
            return ['allowed' => true];
        }

        $storePath = self::resolveStorePath($cfg);
        $store = self::readStore($storePath);
        $now = \time();

        foreach ($store as $storedKey => $expiresAt) {
            if (!\is_int($expiresAt) || $expiresAt < $now) {
                unset($store[$storedKey]);
            }
        }

        if (isset($store[$key]) && (int)$store[$key] >= $now) {
            self::writeStore($storePath, $store);

            return ['allowed' => false, 'reason_code' => 'dedup_duplicate', 'idempotency_key' => $key];
        }

        $store[$key] = $now + $ttl;
        self::writeStore($storePath, $store);

        return ['allowed' => true, 'idempotency_key' => $key];
    }

    /**
     * @param array<string, mixed> $request
     */
    private static function buildKey(array $request): string
    {
        $requestId = $request['_SYNC_REQUEST_ID'] ?? $request['REQUEST_ID'] ?? $request['EVENT_ID'] ?? '';
        $requestId = \is_scalar($requestId) ? \trim((string)$requestId) : '';
        if ($requestId === '') {
            return '';
        }

        $action = \is_scalar($request['ACTION'] ?? null) ? \trim((string)$request['ACTION']) : '';
        if ($action === '') {
            return '';
        }

        return \sha1($action . ':' . $requestId);
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function resolveStorePath(array $cfg): string
    {
        $path = $cfg['inbound_dedup_store_path'] ?? '';
        if (\is_scalar($path) && \trim((string)$path) !== '') {
            return (string)$path;
        }

        return \sys_get_temp_dir() . '/yomerch-inbound-dedup.json';
    }

    /**
     * @return array<string, int>
     */
    private static function readStore(string $path): array
    {
        if (!\is_file($path)) {
            return [];
        }

        $raw = @\file_get_contents($path);
        if (!\is_string($raw) || $raw === '') {
            return [];
        }

        $decoded = \json_decode($raw, true);

        return \is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<string, int> $store
     */
    private static function writeStore(string $path, array $store): void
    {
        $dir = \dirname($path);
        if (!@\is_dir($dir)) {
            @\mkdir($dir, 0755, true);
        }

        @\file_put_contents($path, \json_encode($store, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
}
