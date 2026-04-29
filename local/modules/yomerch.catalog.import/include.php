<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
    \OnlineService\Catalog\Import1c\PostImportHandler::class => '/local/modules/yomerch.catalog.import/lib/PostImportHandler.php',
    \OnlineService\Catalog\Import1c\Import1cBootstrap::class => '/local/modules/yomerch.catalog.import/lib/Import1cBootstrap.php',
    \OnlineService\Catalog\Import1c\Config\PostImportConfig::class => '/local/modules/yomerch.catalog.import/lib/Config/PostImportConfig.php',
]);

\OnlineService\Catalog\Import1c\Import1cBootstrap::register();
