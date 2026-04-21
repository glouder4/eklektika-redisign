<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Заявки по строкам сделки B24 (kit.productapplications) и перенос в заказ Sale.
 * Зависимость: eklektika.b24.rest (RestClient) должен быть загружен раньше.
 */
Loader::registerAutoLoadClasses('eklektika.orders.applications', [
    \OnlineService\Orders\Applications\DealApplicationsService::class => 'lib/DealApplicationsService.php',
    \OnlineService\Orders\Applications\Config\DealApplicationsConfig::class => 'lib/Config/DealApplicationsConfig.php',
]);
