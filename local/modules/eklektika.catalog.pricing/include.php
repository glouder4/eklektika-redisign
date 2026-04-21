<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

/**
 * Пол цены, оптовая база, нижняя граница закупки; узкая связь с компанией через
 * OnlineService\Site\Company::getMaxCompanyDiscountPercentForUserGroups (модуль eklektika.company).
 */
Loader::registerAutoLoadClasses('eklektika.catalog.pricing', [
    \OnlineService\Site\CatalogPriceFloor::class => 'lib/CatalogPriceFloor.php',
    \OnlineService\Site\Config\CatalogPricingConfig::class => 'lib/Config/CatalogPricingConfig.php',
]);
