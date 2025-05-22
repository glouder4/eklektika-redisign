<?php

use Bitrix\Main\Loader;
use intec\Core;

if (Loader::includeModule('intec.core')) {
    Core::setAlias('@intec/eklectika', __DIR__);
    Core::setAlias('@intec/eklectika/module', dirname(__DIR__));
}