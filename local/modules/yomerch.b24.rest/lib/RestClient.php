<?php

namespace OnlineService\B24;

use OnlineService\B24\Config\RestTransportConfig;

/**
 * Единый транспорт HTTP для Bitrix24: прямой REST по входящему вебхуку и POST на прокси-сценарии сайта.
 */
final class RestClient
{
    /**
     * Ensure B24 constants are defined even when init.php bootstrap was skipped.
     */
    private static function ensureB24ConfigLoaded(): void
    {
        if (\defined('URL_B24') && \defined('B24_REST_WEBHOOK_MAIN') && \defined('B24_REST_WEBHOOK_KIT')) {
            return;
        }

        $b24IntegrationConfig = [
            'use_test_portal' => false,
            'base_url' => '',
            'rest_webhook_main' => '',
            'rest_webhook_kit' => '',
        ];

        $configPath = \dirname(__DIR__, 3) . '/php_interface/b24_integration_config.php';
        if (\file_exists($configPath)) {
            $loadedB24Config = require $configPath;
            if (\is_array($loadedB24Config)) {
                $b24IntegrationConfig = \array_merge($b24IntegrationConfig, $loadedB24Config);
            }
        }

        if (!\defined('B24_USE_TEST_PORTAL')) {
            \define('B24_USE_TEST_PORTAL', (bool) $b24IntegrationConfig['use_test_portal']);
        }
        if (!\defined('URL_B24')) {
            \define('URL_B24', (string) $b24IntegrationConfig['base_url']);
        }
        if (!\defined('B24_REST_WEBHOOK_MAIN')) {
            \define('B24_REST_WEBHOOK_MAIN', (string) $b24IntegrationConfig['rest_webhook_main']);
        }
        if (!\defined('B24_REST_WEBHOOK_KIT')) {
            \define('B24_REST_WEBHOOK_KIT', (string) $b24IntegrationConfig['rest_webhook_kit']);
        }
    }

    /**
     * POST на URL вида .../rest/1/{token}/{method}.json; при успехе возвращает $decoded['result'] (как legacy sendRequestB24).
     *
     * @return mixed значение ключа result, либо массив ошибки с ключом success === 0
     */
    public static function callRestMethod(string $method, array $params, bool $debug = false)
    {
        self::ensureB24ConfigLoaded();

        if (!defined('URL_B24') || !defined('B24_REST_WEBHOOK_MAIN')) {
            return self::contractError(
                'b24_config_missing',
                'B24 REST configuration is not loaded (URL_B24 / B24_REST_WEBHOOK_MAIN)'
            );
        }

        $queryUrl = RestTransportConfig::buildMainWebhookMethodUrl($method);
        $decodedResult = self::executePostFull($queryUrl, $params, $debug);

        if (isset($decodedResult['success']) && (int) $decodedResult['success'] === 0) {
            return $decodedResult;
        }
        if (!\array_key_exists('result', $decodedResult)) {
            return [
                'success' => 0,
                'error_code' => 'b24_missing_result',
                'error' => 'B24 response contract violation: result is missing',
                'method' => $method,
                'response' => $decodedResult,
                'transport' => 'b24_rest',
            ];
        }

        return $decodedResult['result'];
    }

    /**
     * POST на прокси /local/modules/yomerch.b24.inbound/endpoint.php — полный декодированный JSON (как legacy sendRequest()).
     */
    public static function postAjaxProxy(array $params, bool $debug = false): array
    {
        self::ensureB24ConfigLoaded();

        if (!defined('URL_B24')) {
            return self::contractError('b24_config_missing', 'URL_B24 is not defined');
        }

        $queryUrl = URL_B24 . \ltrim(RestTransportConfig::SITE_AJAX_PROXY_PATH, '/');

        return self::executePostFull($queryUrl, $params, $debug);
    }

    /**
     * POST на прокси site_requests_handler.php — полный декодированный JSON (базовый класс Request).
     */
    public static function postSiteRequestsHandler(array $params, bool $debug = false): array
    {
        self::ensureB24ConfigLoaded();

        if (!defined('URL_B24')) {
            return self::contractError('b24_config_missing', 'URL_B24 is not defined');
        }

        $queryUrl = URL_B24 . \ltrim(RestTransportConfig::SITE_REQUESTS_HANDLER_PATH, '/');

        return self::executePostFull($queryUrl, $params, $debug);
    }

    /**
     * Префикс URL вебхука для kit.productapplications.* (со слешем на конце).
     */
    public static function getKitWebhookPrefix(): string
    {
        self::ensureB24ConfigLoaded();

        if (!defined('URL_B24') || !defined('B24_REST_WEBHOOK_KIT')) {
            return '';
        }

        return RestTransportConfig::buildKitWebhookPrefix();
    }

    /**
     * GET по полному URL после префикса kit-вебхука (контракт вида kit.productapplications....?ID=...).
     *
     * @return array полный декодированный JSON или структура ошибки с success => 0
     */
    public static function callKitRestGet(string $pathAfterKitPrefix, bool $debug = false): array
    {
        $prefix = self::getKitWebhookPrefix();
        if ($prefix === '') {
            return self::contractError(
                'b24_kit_config_missing',
                'B24 kit webhook is not configured (URL_B24 / B24_REST_WEBHOOK_KIT)'
            );
        }

        $queryUrl = $prefix . $pathAfterKitPrefix;

        return self::executeGetFull($queryUrl, $debug);
    }

    /**
     * @return array полный декодированный ответ или структура ошибки с success => 0
     */
    private static function executeGetFull(string $queryUrl, bool $debug): array
    {
        $curl = \curl_init();
        $insecureTls = self::isInsecureTlsOverrideEnabled();

        \curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => $insecureTls ? 0 : 1,
            CURLOPT_SSL_VERIFYHOST => $insecureTls ? 0 : 2,
            CURLOPT_HTTPGET => true,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_TIMEOUT => RestTransportConfig::REQUEST_TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => RestTransportConfig::CONNECT_TIMEOUT_SECONDS,
        ]);

        $result = \curl_exec($curl);
        $httpCode = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = \curl_error($curl);
        $curlErrno = \curl_errno($curl);

        \curl_close($curl);

        if ($curlErrno) {
            return self::contractError('transport_curl_error', 'CURL Error: ' . $curlError, [
                'errno' => $curlErrno,
            ]);
        }

        if ($httpCode !== 200) {
            return self::contractError('transport_http_error', 'HTTP Error: ' . $httpCode, [
                'http_code' => $httpCode,
                'response' => $result,
            ]);
        }

        $decodedResult = \json_decode($result, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            return self::contractError('transport_json_error', 'JSON Parse Error: ' . \json_last_error_msg(), [
                'raw_response' => $result,
            ]);
        }
        if (isset($decodedResult['error'])) {
            return self::normalizeB24ApiError($decodedResult, $httpCode, (string)$result);
        }

        return $decodedResult;
    }

    /**
     * @return array полный декодированный ответ или структура ошибки с success => 0
     */
    private static function executePostFull(string $queryUrl, array $params, bool $debug): array
    {
        $curl = \curl_init();
        $queryData = \http_build_query($params);
        $insecureTls = self::isInsecureTlsOverrideEnabled();

        \curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => $insecureTls ? 0 : 1,
            CURLOPT_SSL_VERIFYHOST => $insecureTls ? 0 : 2,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
            CURLOPT_TIMEOUT => RestTransportConfig::REQUEST_TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => RestTransportConfig::CONNECT_TIMEOUT_SECONDS,
        ]);

        $result = \curl_exec($curl);
        $httpCode = \curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = \curl_error($curl);
        $curlErrno = \curl_errno($curl);

        \curl_close($curl);

        if ($curlErrno) {
            return self::contractError('transport_curl_error', 'CURL Error: ' . $curlError, [
                'errno' => $curlErrno,
            ]);
        }

        if ($httpCode !== 200) {
            return self::contractError('transport_http_error', 'HTTP Error: ' . $httpCode, [
                'http_code' => $httpCode,
                'response' => $result,
            ]);
        }

        $decodedResult = \json_decode($result, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            return self::contractError('transport_json_error', 'JSON Parse Error: ' . \json_last_error_msg(), [
                'raw_response' => $result,
            ]);
        }
        if (isset($decodedResult['error'])) {
            return self::normalizeB24ApiError($decodedResult, $httpCode, (string)$result);
        }

        return $decodedResult;
    }

    /**
     * @return array{success:int,error:string,error_code:string,error_description?:string,http_code:int,response:array<string,mixed>,raw_response:string}
     */
    private static function normalizeB24ApiError(array $decodedResult, int $httpCode, string $rawResponse): array
    {
        return self::contractError('b24_api_error', 'B24 API error', [
            'error_code' => (string)($decodedResult['error'] ?? 'b24_error'),
            'error_description' => (string)($decodedResult['error_description'] ?? ''),
            'http_code' => $httpCode,
            'response' => $decodedResult,
            'raw_response' => $rawResponse,
        ]);
    }

    /**
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    private static function contractError(string $errorCode, string $error, array $extra = []): array
    {
        $payload = \array_merge([
            'success' => 0,
            'error' => $error,
            'error_code' => $errorCode,
            'retryable' => self::isRetryableErrorCode($errorCode),
            'transport' => 'b24_rest',
            'request_id' => \class_exists(\OnlineService\Sync\SyncTrace::class)
                ? \OnlineService\Sync\SyncTrace::getRequestId()
                : '',
        ], $extra);

        if (\class_exists(\OnlineService\Sync\SyncInboundLog::class)) {
            \OnlineService\Sync\SyncInboundLog::lineAlways('[outbound.error] ' . \json_encode([
                'request_id' => (string)($payload['request_id'] ?? ''),
                'error_code' => $errorCode,
                'http_code' => (int)($payload['http_code'] ?? 0),
                'retryable' => self::isRetryableErrorCode($errorCode),
            ], \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE));
        }

        return $payload;
    }

    private static function isRetryableErrorCode(string $errorCode): bool
    {
        return \in_array($errorCode, ['transport_curl_error', 'transport_http_error', 'transport_json_error'], true);
    }

    private static function isInsecureTlsOverrideEnabled(): bool
    {
        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $raw = $cfg['allow_insecure_tls'] ?? false;
        if (\is_bool($raw)) {
            return $raw;
        }

        $value = \is_scalar($raw) ? \strtolower(\trim((string)$raw)) : '';

        return \in_array($value, ['1', 'true', 'yes', 'on'], true);
    }
}
