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
            return [
                'success' => 0,
                'error' => 'B24 REST configuration is not loaded (URL_B24 / B24_REST_WEBHOOK_MAIN)',
            ];
        }

        $queryUrl = RestTransportConfig::buildMainWebhookMethodUrl($method);
        $decodedResult = self::executePostFull($queryUrl, $params, $debug);

        if (isset($decodedResult['success']) && (int) $decodedResult['success'] === 0) {
            return $decodedResult;
        }

        return $decodedResult['result'];
    }

    /**
     * POST на прокси /local/classes/ajax.php — полный декодированный JSON (как legacy sendRequest()).
     */
    public static function postAjaxProxy(array $params, bool $debug = false): array
    {
        self::ensureB24ConfigLoaded();

        if (!defined('URL_B24')) {
            return [
                'success' => 0,
                'error' => 'URL_B24 is not defined',
            ];
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
            return [
                'success' => 0,
                'error' => 'URL_B24 is not defined',
            ];
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
            return [
                'success' => 0,
                'error' => 'B24 kit webhook is not configured (URL_B24 / B24_REST_WEBHOOK_KIT)',
            ];
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

        \curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => false,
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
            return [
                'success' => 0,
                'error' => 'CURL Error: ' . $curlError,
                'errno' => $curlErrno,
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => 0,
                'error' => 'HTTP Error: ' . $httpCode,
                'response' => $result,
            ];
        }

        $decodedResult = \json_decode($result, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => 0,
                'error' => 'JSON Parse Error: ' . \json_last_error_msg(),
                'raw_response' => $result,
            ];
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

        \curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => false,
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
            return [
                'success' => 0,
                'error' => 'CURL Error: ' . $curlError,
                'errno' => $curlErrno,
            ];
        }

        if ($httpCode !== 200) {
            return [
                'success' => 0,
                'error' => 'HTTP Error: ' . $httpCode,
                'response' => $result,
            ];
        }

        $decodedResult = \json_decode($result, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => 0,
                'error' => 'JSON Parse Error: ' . \json_last_error_msg(),
                'raw_response' => $result,
            ];
        }

        return $decodedResult;
    }
}
