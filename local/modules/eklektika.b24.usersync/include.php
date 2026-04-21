<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Домен синхронизации пользователя ↔ контакт Bitrix24 (без установщика).
 * Зависимость: модуль eklektika.b24.rest (RestClient / Request) должен быть загружен раньше.
 */
Loader::registerAutoLoadClasses('eklektika.b24.usersync', [
    \OnlineService\B24\UserSync\UserSyncBootstrap::class => 'lib/UserSyncBootstrap.php',
    \OnlineService\B24\UserSync\ContactAjaxFacade::class => 'lib/ContactAjaxFacade.php',
    \OnlineService\B24\UserSync\Config\RegisterUserCompanyConfig::class => 'lib/Config/RegisterUserCompanyConfig.php',
    \OnlineService\B24\UserSync\Config\UserSyncConfig::class => 'lib/Config/UserSyncConfig.php',
    \OnlineService\B24\RegisterUserCompany::class => 'lib/RegisterUserCompany.php',
    \OnlineService\B24\User::class => 'lib/User.php',
]);

\OnlineService\B24\UserSync\UserSyncBootstrap::register();
