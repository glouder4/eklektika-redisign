<?php
namespace OnlineService\Sync\FromCrm;

use OnlineService\B24\User;
use OnlineService\Site\UserGroups;
use OnlineService\Site\Company;
use OnlineService\Site\Manager;
use OnlineService\Sync\SyncInboundLog;
use OnlineService\Sync\SyncPrimitiveBreakpoint;
use OnlineService\Sync\SyncTrace;

/**
 * Тонкий фасад: маршрутизация ACTION → классы канала from-crm.
 */
class InboundGateway
{
    private const ERR_DISPATCH_FAILED = 'dispatch_failed';
    private const ERR_UNKNOWN_ACTION = 'unknown_action';
    private const ERR_INVALID_PAYLOAD = 'invalid_payload';
    private const ERR_COMPLIANCE_BLOCKED = 'business_effects_blocked';
    private const ERR_DUPLICATE_REQUEST = 'duplicate_request';

    private const EVT_DISPATCH_FAILED = 'dispatch_failed';
    private const EVT_UPDATE_GROUP_OK = 'update_group_ok';
    private const EVT_UPDATE_GROUP_OK_LEGACY = 'update_group_ok_legacy_plain';
    private const EVT_UPDATE_BATCH_USERS_RESULT = 'update_batch_users_result';
    private const EVT_UPDATE_CONTACT_RESULT = 'update_contact_result';
    private const EVT_DELETE_CONTACT_RESULT = 'delete_contact_result';
    private const EVT_DELETE_COMPANY_RESULT = 'delete_company_result';
    private const EVT_UPDATE_COMPANY_RESULT = 'update_company_result';
    private const EVT_SYNC_COMPANY_CONTACTS_RESULT = 'sync_company_contacts_result';
    private const EVT_SYNC_COMPANY_CONTACTS_RESULT_LEGACY = 'sync_company_contacts_result_legacy_plain';
    private const EVT_UPDATE_MANAGER_RESULT = 'update_manager_result';
    private const EVT_UNKNOWN_ACTION = 'unknown_action';
    private const EVT_INVALID_PAYLOAD = 'invalid_payload';
    private const EVT_COMPLIANCE_BLOCKED = 'compliance_blocked';
    private const EVT_DEDUP_BLOCKED = 'dedup_blocked';

    private const RC_DISPATCH_FAILED = 'dispatch_failed';
    private const RC_ACTION_BLOCKED_BY_POLICY = 'action_blocked_by_policy';
    private const RC_DEDUP_DUPLICATE = 'dedup_duplicate';
    private const RC_UPDATE_GROUP_OK = 'update_group_ok';
    private const RC_UPDATE_BATCH_USERS_OK = 'update_batch_users_ok';
    private const RC_UPDATE_BATCH_USERS_FAILED = 'update_batch_users_failed';
    private const RC_UPDATE_CONTACT_OK = 'update_contact_ok';
    private const RC_UPDATE_CONTACT_FAILED = 'update_contact_failed';
    private const RC_DELETE_CONTACT_OK = 'delete_contact_ok';
    private const RC_DELETE_CONTACT_FAILED = 'delete_contact_failed';
    private const RC_DELETE_COMPANY_OK = 'delete_company_ok';
    private const RC_DELETE_COMPANY_FAILED = 'delete_company_failed';
    private const RC_UPDATE_COMPANY_OK = 'update_company_ok';
    private const RC_UPDATE_COMPANY_FAILED = 'update_company_failed';
    private const RC_SYNC_COMPANY_CONTACTS_OK = 'sync_company_contacts_ok';
    private const RC_SYNC_COMPANY_CONTACTS_FAILED = 'sync_company_contacts_failed';
    private const RC_UPDATE_MANAGER_OK = 'update_manager_ok';
    private const RC_UPDATE_MANAGER_FAILED = 'update_manager_failed';

    public static function dispatch(array $request): void
    {
        SyncTrace::reset();
        SyncTrace::add('request', SyncTrace::summarizeRequest($request));

        try {
            self::dispatchInternal($request);
        } catch (\Throwable $e) {
            SyncTrace::add('dispatch_exception', [
                'class' => \get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . (string)$e->getLine(),
            ]);
            SyncInboundLog::line(
                '[inbound] dispatch_failed '
                . \get_class($e) . ': '
                . $e->getMessage()
                . ' @' . $e->getFile() . ':' . (string)$e->getLine()
            );
            self::respondJson(500, self::EVT_DISPATCH_FAILED, [
                'success' => 0,
                'error' => self::ERR_DISPATCH_FAILED,
                'reason_code' => self::RC_DISPATCH_FAILED,
                'message' => 'Internal error',
            ]);
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private static function withDebugTrace(array $payload): array
    {
        $payload['request_id'] = SyncTrace::getRequestId();
        $trace = SyncTrace::flushLines();
        if ($trace !== null) {
            $payload['debug_trace'] = $trace;
        }

        return $payload;
    }

    private static function dispatchInternal(array $request): void
    {
        $action = $request['ACTION'] ?? '';
        $cfg = $GLOBALS['YOMERCH_SYNC_CONFIG'] ?? $GLOBALS['EKLEKTIKA_SYNC_CONFIG'] ?? [];
        $validation = InboundPayloadValidator::validate($request);
        if (!$validation['valid']) {
            self::respondInvalidPayload($action, (string)($validation['reason_code'] ?? 'invalid_payload'));
            return;
        }
        if (self::isActionBlocked($action, $cfg)) {
            self::respondComplianceBlocked($action, self::RC_ACTION_BLOCKED_BY_POLICY);
            return;
        }
        $idempotency = \OnlineService\Sync\InboundIdempotencyGate::assertNotDuplicate($request, $cfg);
        if (!$idempotency['allowed']) {
            self::respondDuplicate($action, (string)($idempotency['reason_code'] ?? self::RC_DEDUP_DUPLICATE), (string)($idempotency['idempotency_key'] ?? ''));
            return;
        }

        if ($action === 'UPDATE_GROUP') {
            $group = new UserGroups($request);
            $groupId = (int)$group->getGroupId();
            if (self::useLegacyPlainResponses($cfg)) {
                SyncInboundLog::lineAlways('[inbound.outcome] ' . \json_encode([
                    'request_id' => SyncTrace::getRequestId(),
                    'http_code' => 200,
                    'event' => self::EVT_UPDATE_GROUP_OK_LEGACY,
                    'success' => 1,
                    'reason_code' => self::RC_UPDATE_GROUP_OK,
                    'action' => $action,
                ], \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE));
                echo $groupId;
                return;
            }
            self::respondJson(200, self::EVT_UPDATE_GROUP_OK, [
                'success' => 1,
                'reason_code' => self::RC_UPDATE_GROUP_OK,
                'action' => $action,
                'data' => ['group_id' => $groupId],
            ]);
            return;
        }

        if ($action === 'UPDATE_CONTACT' || $action === 'UPDATE_BATCH_USERS') {
            if (class_exists(User::class)) {
                $user = new User();
                if ($action === 'UPDATE_BATCH_USERS') {
                    $ok = (bool)$user->updateBatch($request);
                    self::respondJson(200, self::EVT_UPDATE_BATCH_USERS_RESULT, [
                        'success' => $ok ? 1 : 0,
                        'reason_code' => $ok ? self::RC_UPDATE_BATCH_USERS_OK : self::RC_UPDATE_BATCH_USERS_FAILED,
                        'action' => $action,
                        'data' => ['batch' => $ok],
                    ]);
                    return;
                }
                $ok = (bool)$user->update($request);
                $reasonCode = $ok ? self::RC_UPDATE_CONTACT_OK : self::RC_UPDATE_CONTACT_FAILED;
                if (!$ok) {
                    $lastFailReason = \method_exists($user, 'getLastUpdateFailReason')
                        ? $user->getLastUpdateFailReason()
                        : null;
                    if ($lastFailReason !== null && $lastFailReason !== '') {
                        $reasonCode = (string)$lastFailReason;
                    }
                    SyncTrace::add('UPDATE_CONTACT failed', [
                        'reason_code' => $reasonCode,
                        'B24_ID' => (string)($request['B24_ID'] ?? ''),
                        'legacy_ID_param' => (string)($request['ID'] ?? ''),
                    ]);
                }
                self::respondJson(200, self::EVT_UPDATE_CONTACT_RESULT, [
                    'success' => $ok ? 1 : 0,
                    'reason_code' => $reasonCode,
                    'action' => $action,
                    'data' => ['updated' => $ok],
                ]);
                return;
            }

            throw new \RuntimeException('No contact sync handler class found');
        }

        if ($action === 'DELETE_CONTACT') {
            if (class_exists(User::class)) {
                $user = new User();
                SyncTrace::add('DELETE_CONTACT start', [
                    'B24_ID' => $request['B24_ID'] ?? null,
                    'ID' => $request['ID'] ?? null,
                ]);
                $ok = (bool)$user->delete($request);
                $reasonCode = $ok ? self::RC_DELETE_CONTACT_OK : self::RC_DELETE_CONTACT_FAILED;
                if (!$ok) {
                    $lastFail = \method_exists($user, 'getLastDeleteFailReason')
                        ? $user->getLastDeleteFailReason()
                        : null;
                    if ($lastFail !== null && $lastFail !== '') {
                        $reasonCode = (string)$lastFail;
                    }
                }
                SyncTrace::add('DELETE_CONTACT end', [
                    'ok' => $ok,
                    'reason_code' => $reasonCode,
                ]);
                self::respondJson(200, self::EVT_DELETE_CONTACT_RESULT, [
                    'success' => $ok ? 1 : 0,
                    'reason_code' => $reasonCode,
                    'action' => $action,
                    'data' => ['deleted' => $ok],
                ]);
                return;
            }
            throw new \RuntimeException('No contact delete handler class found');
        }

        if ($action === 'DELETE_COMPANY' || $action === 'UPDATE_COMPANY' || $action === 'SYNC_COMPANY_CONTACTS') {
            if (!class_exists(Company::class)) {
                throw new \RuntimeException('No company handler class found');
            }
            $company = new Company();
            if ($action === 'DELETE_COMPANY') {
                SyncTrace::add('DELETE_COMPANY start', []);
                $result = $company->deleteCompanyElement($request);
                SyncTrace::add('DELETE_COMPANY end', ['ok' => (bool)$result]);
                self::respondJson(200, self::EVT_DELETE_COMPANY_RESULT, [
                    'success' => $result ? 1 : 0,
                    'reason_code' => $result ? self::RC_DELETE_COMPANY_OK : self::RC_DELETE_COMPANY_FAILED,
                    'action' => $action,
                    'data' => ['deleted' => (bool)$result],
                ]);
                return;
            }
            if ($action === 'UPDATE_COMPANY') {
                SyncTrace::add('UPDATE_COMPANY start', []);
                SyncPrimitiveBreakpoint::hit('sync_bp_inbound_before_update_company', [
                    'ACTION' => $action,
                    'summary' => SyncTrace::summarizeRequest($request),
                ]);
                CrmInboundUfMap::applyCompanyInboundCrmUfToSiteProperties($request);
                CrmInboundUfMap::applyCompanyInboundHeadOfHoldingUfToSiteProperties($request);
                CrmInboundUfMap::applyCompanyInboundDiscountUfToSiteProperties($request);
                try {
                    $result = $company->updateCompanyElement($request);
                } catch (\InvalidArgumentException $e) {
                    self::respondInvalidPayload($action, (string)$e->getMessage());
                    return;
                }
                SyncPrimitiveBreakpoint::hit('sync_bp_inbound_after_update_company', [
                    'result' => $result === false ? 'false' : (string)(int)$result,
                ]);
                SyncTrace::add('UPDATE_COMPANY end', [
                    'result' => $result === false ? 'false' : (string)(int)$result,
                ]);
                self::respondJson(200, self::EVT_UPDATE_COMPANY_RESULT, [
                    'success' => $result ? 1 : 0,
                    'reason_code' => $result ? self::RC_UPDATE_COMPANY_OK : self::RC_UPDATE_COMPANY_FAILED,
                    'action' => $action,
                    'data' => ['company_id' => (int)$result],
                ]);
                return;
            }
            $syncResult = (bool)$company->syncCompanyContacts($request);
            if (self::useLegacyPlainResponses($cfg)) {
                SyncInboundLog::lineAlways('[inbound.outcome] ' . \json_encode([
                    'request_id' => SyncTrace::getRequestId(),
                    'http_code' => 200,
                    'event' => self::EVT_SYNC_COMPANY_CONTACTS_RESULT_LEGACY,
                    'success' => $syncResult ? 1 : 0,
                    'reason_code' => $syncResult ? self::RC_SYNC_COMPANY_CONTACTS_OK : self::RC_SYNC_COMPANY_CONTACTS_FAILED,
                    'action' => $action,
                ], \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE));
                echo $syncResult ? '1' : '0';
                return;
            }
            self::respondJson(200, self::EVT_SYNC_COMPANY_CONTACTS_RESULT, [
                'success' => $syncResult ? 1 : 0,
                'reason_code' => $syncResult ? self::RC_SYNC_COMPANY_CONTACTS_OK : self::RC_SYNC_COMPANY_CONTACTS_FAILED,
                'action' => $action,
                'data' => ['synced' => $syncResult],
            ]);
            return;
        }

        if ($action === 'UPDATE_MANAGER') {
            if (!class_exists(Manager::class)) {
                throw new \RuntimeException('No manager handler class found');
            }
            $idScalar = isset($request['ID']) && \is_scalar($request['ID']) ? \trim((string)$request['ID']) : '';
            if ($idScalar === '' && isset($request['BITRIX24_ID']) && \is_scalar($request['BITRIX24_ID'])
                && \trim((string)$request['BITRIX24_ID']) !== '') {
                $request['ID'] = $request['BITRIX24_ID'];
            }
            $manager = new Manager();
            $ok = (bool)$manager->update($request);
            self::respondJson(200, self::EVT_UPDATE_MANAGER_RESULT, [
                'success' => $ok ? 1 : 0,
                'reason_code' => $ok ? self::RC_UPDATE_MANAGER_OK : self::RC_UPDATE_MANAGER_FAILED,
                'action' => $action,
                'data' => ['updated' => $ok],
            ]);
            return;
        }

        if ($action !== '') {
            SyncTrace::add('unknown_action', ['action' => $action]);
            self::respondJson(400, self::EVT_UNKNOWN_ACTION, [
                'success' => 0,
                'error' => self::ERR_UNKNOWN_ACTION,
                'action' => $action,
            ]);
            return;
        }
    }

    private static function respondInvalidPayload(string $action, string $reasonCode): void
    {
        SyncTrace::add('invalid_payload', ['action' => $action, 'reason_code' => $reasonCode]);
        self::respondJson(400, self::EVT_INVALID_PAYLOAD, [
            'success' => 0,
            'error' => self::ERR_INVALID_PAYLOAD,
            'reason_code' => $reasonCode,
            'action' => $action,
        ]);
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function isActionBlocked(string $action, array $cfg): bool
    {
        if (!isset($cfg['inbound_disabled_actions']) || !\is_array($cfg['inbound_disabled_actions'])) {
            return false;
        }

        return \in_array($action, $cfg['inbound_disabled_actions'], true);
    }

    private static function respondComplianceBlocked(string $action, string $reasonCode): void
    {
        SyncTrace::add('compliance_blocked', ['action' => $action, 'reason_code' => $reasonCode]);
        self::respondJson(409, self::EVT_COMPLIANCE_BLOCKED, [
            'success' => 0,
            'error' => self::ERR_COMPLIANCE_BLOCKED,
            'reason_code' => $reasonCode,
            'action' => $action,
        ]);
    }

    private static function respondDuplicate(string $action, string $reasonCode, string $idempotencyKey): void
    {
        SyncTrace::add('dedup_blocked', ['action' => $action, 'reason_code' => $reasonCode, 'key' => $idempotencyKey]);
        self::respondJson(409, self::EVT_DEDUP_BLOCKED, [
            'success' => 0,
            'error' => self::ERR_DUPLICATE_REQUEST,
            'reason_code' => $reasonCode,
            'idempotency_key' => $idempotencyKey,
            'action' => $action,
        ]);
    }

    /**
     * Canonical action-to-contract map for internal/external handoff sync docs.
     *
     * @return array<string, array{success_reason:string,failure_reason:string,event:string}>
     */
    public static function actionContractMap(): array
    {
        return [
            'UPDATE_GROUP' => [
                'success_reason' => self::RC_UPDATE_GROUP_OK,
                'failure_reason' => self::ERR_INVALID_PAYLOAD,
                'event' => self::EVT_UPDATE_GROUP_OK,
            ],
            'UPDATE_BATCH_USERS' => [
                'success_reason' => self::RC_UPDATE_BATCH_USERS_OK,
                'failure_reason' => self::RC_UPDATE_BATCH_USERS_FAILED,
                'event' => self::EVT_UPDATE_BATCH_USERS_RESULT,
            ],
            'UPDATE_CONTACT' => [
                'success_reason' => self::RC_UPDATE_CONTACT_OK,
                'failure_reason' => self::RC_UPDATE_CONTACT_FAILED,
                'event' => self::EVT_UPDATE_CONTACT_RESULT,
            ],
            'DELETE_CONTACT' => [
                'success_reason' => self::RC_DELETE_CONTACT_OK,
                'failure_reason' => self::RC_DELETE_CONTACT_FAILED,
                'event' => self::EVT_DELETE_CONTACT_RESULT,
            ],
            'DELETE_COMPANY' => [
                'success_reason' => self::RC_DELETE_COMPANY_OK,
                'failure_reason' => self::RC_DELETE_COMPANY_FAILED,
                'event' => self::EVT_DELETE_COMPANY_RESULT,
            ],
            'UPDATE_COMPANY' => [
                'success_reason' => self::RC_UPDATE_COMPANY_OK,
                'failure_reason' => self::RC_UPDATE_COMPANY_FAILED,
                'event' => self::EVT_UPDATE_COMPANY_RESULT,
            ],
            'SYNC_COMPANY_CONTACTS' => [
                'success_reason' => self::RC_SYNC_COMPANY_CONTACTS_OK,
                'failure_reason' => self::RC_SYNC_COMPANY_CONTACTS_FAILED,
                'event' => self::EVT_SYNC_COMPANY_CONTACTS_RESULT,
            ],
            'UPDATE_MANAGER' => [
                'success_reason' => self::RC_UPDATE_MANAGER_OK,
                'failure_reason' => self::RC_UPDATE_MANAGER_FAILED,
                'event' => self::EVT_UPDATE_MANAGER_RESULT,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $cfg
     */
    private static function useLegacyPlainResponses(array $cfg): bool
    {
        $raw = $cfg['inbound_legacy_plain_responses'] ?? true;
        if (\is_bool($raw)) {
            return $raw;
        }
        $value = \strtolower(\trim((string)$raw));

        return \in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function respondJson(int $statusCode, string $event, array $payload): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        $body = self::withDebugTrace($payload);
        SyncInboundLog::lineAlways('[inbound.outcome] ' . \json_encode([
            'request_id' => SyncTrace::getRequestId(),
            'http_code' => $statusCode,
            'event' => $event,
            'success' => (int)($body['success'] ?? 0),
            'error' => (string)($body['error'] ?? ''),
            'reason_code' => (string)($body['reason_code'] ?? ''),
            'action' => (string)($body['action'] ?? ''),
        ], \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE));
        echo \json_encode($body, JSON_UNESCAPED_UNICODE);
    }

}
