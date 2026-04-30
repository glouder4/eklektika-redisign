<?php

namespace OnlineService\B24;

/**
 * Контрактные запросы с телом ACTION / METHOD / PARAMS на сторону портала Bitrix24.
 *
 * Транспорт: POST на `URL_B24` + `/local/modules/yomerch.b24.inbound/endpoint.php`
 * (\OnlineService\B24\Config\RestTransportConfig::SITE_AJAX_PROXY_PATH), не штатный REST `/rest/…/{method}.json` по вебхуку.
 * Токен inbound: если в `$params` нет непустого `sync_token`, {@see RestClient::postAjaxProxy()} подставляет
 * `inbound_secret` из `$GLOBALS['YOMERCH_SYNC_CONFIG']` (или заголовок `X-Sync-Token`, если включён `inbound_require_header_token`).
 */
class Request
{
    protected function sendRequest($params, $debug = false)
    {
        return RestClient::postAjaxProxy($params, $debug);
    }
}
