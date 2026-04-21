<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Контент сайта: настройки страницы (Page editor), поисковый bootstrap.
 * Зависимости: ядро Bitrix, iblock (в рантайме внутри PageSettings).
 */
Loader::registerAutoLoadClasses('eklektika.site', [
    \OnlineService\Site\PageSettings::class => 'lib/PageSettings.php',
    \OnlineService\Site\SearchIndexingBootstrap::class => 'lib/SearchIndexingBootstrap.php',
    \OnlineService\Site\Config\SiteModuleConfig::class => 'lib/Config/SiteModuleConfig.php',
]);

require_once __DIR__ . '/lib/PageEditorGlobalFunctions.php';

\OnlineService\Site\SearchIndexingBootstrap::register();
