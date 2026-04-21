<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Транспорт REST к Bitrix24 без установщика (минимальный модуль ST-02 / MODULE-LAYOUT.md).
 */
Loader::registerAutoLoadClasses('eklektika.b24.rest', [
    \OnlineService\B24\RestClient::class => 'lib/RestClient.php',
    \OnlineService\B24\Request::class => 'lib/Request.php',
    \OnlineService\B24\Config\RestTransportConfig::class => 'lib/Config/RestTransportConfig.php',
]);

require_once __DIR__ . '/lib/LegacyGlobalB24.php';
