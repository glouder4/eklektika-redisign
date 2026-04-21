<?php

use Bitrix\Main\Loader;

/**
 * ST-09/ST-10: единая и безопасная цепочка bootstrap для eklektika.* модулей.
 * Порядок загрузки зафиксирован по зависимостям:
 * b24.rest -> company -> catalog.pricing -> site -> catalog.import -> orders.applications -> b24.usersync
 */
function includeEklektikaModule(string $moduleId): bool
{
    $included = Loader::includeModule($moduleId);
    if (!$included) {
        // Не прерываем bootstrap, чтобы сохранить поведение легаси.
        trigger_error('Failed to include module: ' . $moduleId, E_USER_WARNING);
    }

    return $included;
}

includeEklektikaModule('eklektika.b24.rest');
includeEklektikaModule('eklektika.company');
includeEklektikaModule('eklektika.catalog.pricing');
includeEklektikaModule('eklektika.site');
includeEklektikaModule('eklektika.catalog.import');
includeEklektikaModule('eklektika.orders.applications');
includeEklektikaModule('eklektika.b24.usersync');

if (class_exists(\OnlineService\Site\CatalogPriceFloor::class)) {
    \OnlineService\Site\CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog();
}

$GLOBALS['OS_BREADCRUMBS_ADD_CONTAINER'] = 'Y';

// Ограничение цены по закупке: CatalogPriceFloor::bootstrap() в local/php_interface/init.php
// Модификация индексирования: SearchIndexingBootstrap в eklektika.site/include.php