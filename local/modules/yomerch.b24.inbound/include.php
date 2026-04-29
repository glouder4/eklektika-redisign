<?php

defined('B_PROLOG_INCLUDED') || die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
    \OnlineService\Sync\InboundRequestLogger::class => '/local/modules/yomerch.b24.inbound/lib/InboundRequestLogger.php',
    \OnlineService\Sync\InboundSecurity::class => '/local/modules/yomerch.b24.inbound/lib/InboundSecurity.php',
    \OnlineService\Sync\InboundIdempotencyGate::class => '/local/modules/yomerch.b24.inbound/lib/InboundIdempotencyGate.php',
    \OnlineService\Sync\SyncInboundLog::class => '/local/modules/yomerch.b24.inbound/lib/SyncInboundLog.php',
    \OnlineService\Sync\SyncTrace::class => '/local/modules/yomerch.b24.inbound/lib/SyncTrace.php',
    \OnlineService\Sync\SyncPrimitiveBreakpoint::class => '/local/modules/yomerch.b24.inbound/lib/SyncPrimitiveBreakpoint.php',
    \OnlineService\Sync\FromCrm\InboundGateway::class => '/local/modules/yomerch.b24.inbound/lib/from-crm/InboundGateway.php',
    \OnlineService\Sync\FromCrm\InboundPayloadValidator::class => '/local/modules/yomerch.b24.inbound/lib/from-crm/InboundPayloadValidator.php',
    \OnlineService\Sync\FromCrm\CrmInboundUfMap::class => '/local/modules/yomerch.b24.inbound/lib/from-crm/CrmInboundUfMap.php',
    \OnlineService\Sync\ToCrm\OutboundUpdateContactPayload::class => '/local/modules/yomerch.b24.inbound/lib/to-crm/OutboundUpdateContactPayload.php',
]);

require_once __DIR__ . '/lib/bootstrap.php';
