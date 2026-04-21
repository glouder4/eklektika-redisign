<?php

/**
 * ST-09/ST-10: единая и безопасная цепочка bootstrap для eklektika.* модулей.
 * Порядок загрузки зафиксирован по зависимостям:
 * b24.rest -> company -> catalog.pricing -> site -> catalog.import -> orders.applications -> b24.usersync
 */
function requireEklektikaModuleInclude(string $moduleId): bool
{
    $moduleIncludePath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $moduleId . '/include.php';

    if (is_file($moduleIncludePath)) {
        require_once $moduleIncludePath;
        return true;
    }

    // Не прерываем bootstrap, чтобы сохранить поведение легаси.
    trigger_error('Failed to include module include.php: ' . $moduleId, E_USER_WARNING);

    return false;
}

requireEklektikaModuleInclude('eklektika.b24.rest');
requireEklektikaModuleInclude('eklektika.company');
requireEklektikaModuleInclude('eklektika.catalog.pricing');
requireEklektikaModuleInclude('eklektika.site');
requireEklektikaModuleInclude('eklektika.catalog.import');
requireEklektikaModuleInclude('eklektika.orders.applications');
requireEklektikaModuleInclude('eklektika.b24.usersync');

if (class_exists(\OnlineService\Site\CatalogPriceFloor::class)) {
    \OnlineService\Site\CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog();
}

$GLOBALS['OS_BREADCRUMBS_ADD_CONTAINER'] = 'Y';

// Ограничение цены по закупке: CatalogPriceFloor::bootstrap() в local/php_interface/init.php
// Модификация индексирования: SearchIndexingBootstrap в eklektika.site/include.php