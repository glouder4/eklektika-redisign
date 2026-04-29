<?php

/**
 * ST-09/ST-10: единая и безопасная цепочка bootstrap для yomerch.* модулей.
 * Порядок загрузки зафиксирован по зависимостям:
 * b24.rest -> company -> catalog.pricing -> site -> catalog.import -> orders.applications -> b24.usersync
 */ 
function requireProjectModuleInclude(string $moduleId): bool
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

requireProjectModuleInclude('yomerch.b24.rest');
// User (yomerch.b24.usersync) extends OnlineService\B24\Request — при сбое include rest класс может не объявиться (runtime: «Request not found»).
$projectB24RestRequestPaths = [
    __DIR__ . '/../modules/yomerch.b24.rest/lib/Request.php',
];
if (!empty($_SERVER['DOCUMENT_ROOT'])) {
    $projectB24RestRequestPaths[] = rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/\\') . '/local/modules/yomerch.b24.rest/lib/Request.php';
}
if (!\class_exists(\OnlineService\B24\Request::class, false)) {
    foreach ($projectB24RestRequestPaths as $projectB24RestRequestPath) {
        if (\is_file($projectB24RestRequestPath)) {
            require_once $projectB24RestRequestPath;
            break;
        }
    }
}
requireProjectModuleInclude('yomerch.company');
requireProjectModuleInclude('yomerch.catalog.pricing');
requireProjectModuleInclude('yomerch.site');
requireProjectModuleInclude('yomerch.catalog.import');
requireProjectModuleInclude('yomerch.orders.applications');
requireProjectModuleInclude('yomerch.b24.inbound');
requireProjectModuleInclude('yomerch.b24.usersync');

if (class_exists(\OnlineService\Site\CatalogPriceFloor::class)) {
    \OnlineService\Site\CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog();
}

$GLOBALS['OS_BREADCRUMBS_ADD_CONTAINER'] = 'Y';

// Ограничение цены по закупке: CatalogPriceFloor::bootstrap() в local/php_interface/init.php
// Модификация индексирования: SearchIndexingBootstrap в yomerch.site/include.php