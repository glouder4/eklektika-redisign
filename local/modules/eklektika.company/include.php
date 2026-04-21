<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Домен компаний, менеджеров и пользовательских групп (минимальный модуль без установщика).
 * Namespace OnlineService\Site сохранён до ST-09.
 */
Loader::registerAutoLoadClasses('eklektika.company', [
    \OnlineService\Site\Company::class => 'lib/Company.php',
    \OnlineService\Site\Manager::class => 'lib/Manager.php',
    \OnlineService\Site\UserGroups::class => 'lib/UserGroups.php',
    \OnlineService\Site\Config\CompanyB24Config::class => 'lib/Config/CompanyB24Config.php',
]);
