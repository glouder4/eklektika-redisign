<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Домен компаний, менеджеров и пользовательских групп (минимальный модуль без установщика).
 * Namespace OnlineService\Site сохранён до ST-09.
 */
Loader::registerAutoLoadClasses(null, [
    \OnlineService\Site\Company::class => '/local/modules/yomerch.company/lib/Company.php',
    \OnlineService\Site\Manager::class => '/local/modules/yomerch.company/lib/Manager.php',
    \OnlineService\Site\UserGroups::class => '/local/modules/yomerch.company/lib/UserGroups.php',
    \OnlineService\Site\Config\CompanyB24Config::class => '/local/modules/yomerch.company/lib/Config/CompanyB24Config.php',
    \OnlineService\Site\Config\CompanyModuleConfig::class => '/local/modules/yomerch.company/lib/Config/CompanyModuleConfig.php',
]);
