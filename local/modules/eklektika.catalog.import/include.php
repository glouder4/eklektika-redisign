<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('eklektika.catalog.import', [
    \OnlineService\Catalog\Import1c\PostImportHandler::class => 'lib/PostImportHandler.php',
    \OnlineService\Catalog\Import1c\Import1cBootstrap::class => 'lib/Import1cBootstrap.php',
    \OnlineService\Catalog\Import1c\Config\PostImportConfig::class => 'lib/Config/PostImportConfig.php',
]);

\OnlineService\Catalog\Import1c\Import1cBootstrap::register();
