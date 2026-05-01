<?php

declare(strict_types=1);

use OnlineService\Sync\FromCrm\InboundPayloadValidator;
use OnlineService\Sync\FromCrm\InboundGateway;
use OnlineService\Sync\FromCrm\CrmInboundUfMap;
use OnlineService\Sync\InboundIdempotencyGate;
use OnlineService\Sync\InboundSecurity;
use OnlineService\Sync\SyncTrace;

require_once __DIR__ . '/../../lib/SyncInboundLog.php';
require_once __DIR__ . '/../../lib/SyncTrace.php';
require_once __DIR__ . '/../../lib/InboundSecurity.php';
require_once __DIR__ . '/../../lib/InboundIdempotencyGate.php';
require_once __DIR__ . '/../../lib/from-crm/InboundPayloadValidator.php';
require_once __DIR__ . '/../../lib/from-crm/InboundGateway.php';
require_once __DIR__ . '/../../lib/from-crm/CrmInboundUfMap.php';

/**
 * Lightweight contract harness for implementation-first checks.
 * Run: php local/modules/yomerch.b24.inbound/tests/contract/contract_harness.php
 * Windows (PHP via WSL or no global php): tools/run_contract_harness.ps1 from repo root.
 */
final class ContractHarness
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): int
    {
        $this->testValidatorContract();
        $this->testSecurityContract();
        $this->testDedupContract();
        $this->testUsersyncEmailConflictReasonCodesContract();
        $this->testUpdateCompanyPropagationContract();
        $this->testUpdateCompanyResponseRuntimeContract();
        $this->testUpdateContactPayloadSanitizerKeepsIdAndManagerFallbacks();

        echo 'passed=' . $this->passed . ' failed=' . $this->failed . PHP_EOL;

        return $this->failed === 0 ? 0 : 1;
    }

    private function testUsersyncEmailConflictReasonCodesContract(): void
    {
        $usersyncFile = __DIR__ . '/../../../yomerch.b24.usersync/lib/RegisterUserCompany.php';
        $contents = @\file_get_contents($usersyncFile);
        $this->assert(\is_string($contents) && $contents !== '', 'usersync RegisterUserCompany readable');
        if (!\is_string($contents) || $contents === '') {
            return;
        }

        $this->assert(\strpos($contents, "'email_conflict_site'") !== false, 'usersync reason_code email_conflict_site present');
        $this->assert(\strpos($contents, "'email_conflict_crm'") !== false, 'usersync reason_code email_conflict_crm present');
        $this->assert(\strpos($contents, "'email_conflict_both'") !== false, 'usersync reason_code email_conflict_both present');
        $this->assert(\strpos($contents, "'email_check_unavailable'") !== false, 'usersync reason_code email_check_unavailable present');

        $guardPattern = '/if\s*\(!\$this->ensureEmailUniquenessPrecheck\(\$arFields\)\)\s*\{\s*return\s+false\s*;\s*\}/m';
        $guardEntryPos = \strpos($contents, '!$this->ensureEmailUniquenessPrecheck($arFields)');
        $responsePos = \strpos($contents, '$response = $this->isUserRegistered($arFields);');
        $createPos = \strpos($contents, '$this->createB24Company($arFields)');

        $this->assert(\preg_match($guardPattern, $contents) === 1, 'usersync fail-closed email precheck guard present');
        $this->assert($responsePos !== false, 'usersync duplicate lookup marker present');
        $this->assert($guardEntryPos !== false && $responsePos !== false && $guardEntryPos < $responsePos, 'usersync fail-closed guard precedes duplicate lookup');
        $this->assert($guardEntryPos !== false && ($createPos === false || $guardEntryPos < $createPos), 'usersync guard precedes CRM side effects');
    }

    private function testUpdateCompanyPropagationContract(): void
    {
        $gatewayFile = __DIR__ . '/../../lib/from-crm/InboundGateway.php';
        $gatewayContents = @\file_get_contents($gatewayFile);
        $this->assert(\is_string($gatewayContents) && $gatewayContents !== '', 'inbound gateway readable');
        if (!\is_string($gatewayContents) || $gatewayContents === '') {
            return;
        }

        $this->assert(\strpos($gatewayContents, "'update_company_propagated'") !== false, 'UPDATE_COMPANY propagated reason_code present');
        $this->assert(\strpos($gatewayContents, "'update_company_partial_propagation'") !== false, 'UPDATE_COMPANY partial reason_code present');
        $this->assert(\strpos($gatewayContents, "'update_company_failed'") !== false, 'UPDATE_COMPANY failed reason_code present');
        $this->assert(\strpos($gatewayContents, "'evidence'") !== false, 'UPDATE_COMPANY evidence field present');
        $this->assert(\strpos($gatewayContents, "'resolved'") !== false, 'UPDATE_COMPANY evidence resolved present');
        $this->assert(\strpos($gatewayContents, "'unresolved'") !== false, 'UPDATE_COMPANY evidence unresolved present');
        $this->assert(\strpos($gatewayContents, "'effective'") !== false, 'UPDATE_COMPANY evidence effective present');

        $companyFile = __DIR__ . '/../../../yomerch.company/lib/Company.php';
        $companyContents = @\file_get_contents($companyFile);
        $this->assert(\is_string($companyContents) && $companyContents !== '', 'company module readable');
        if (!\is_string($companyContents) || $companyContents === '') {
            return;
        }
        $this->assert(\strpos($companyContents, "'UF_ADVERSTERING_AGENT' =>") !== false, 'Company propagation updates UF_ADVERSTERING_AGENT');
        $this->assert(\strpos($companyContents, "'ACTIVE' =>") !== false, 'Company propagation updates ACTIVE');
    }

    private function testUpdateCompanyResponseRuntimeContract(): void
    {
        $contractMap = InboundGateway::actionContractMap();
        $companyContract = $contractMap['UPDATE_COMPANY'] ?? [];
        $this->assert(($companyContract['success_reason'] ?? '') === 'update_company_propagated', 'UPDATE_COMPANY contract success_reason canonical');
        $this->assert(($companyContract['failure_reason'] ?? '') === 'update_company_failed', 'UPDATE_COMPANY contract failure_reason canonical');
        $allowed = $companyContract['allowed_reason_codes'] ?? [];
        $this->assert(\is_array($allowed) && \in_array('update_company_partial_propagation', $allowed, true), 'UPDATE_COMPANY contract exposes partial reason_code');

        $reflection = new \ReflectionClass(InboundGateway::class);
        $builder = $reflection->getMethod('buildUpdateCompanyResponsePayload');
        $builder->setAccessible(true);
        $payload = $builder->invoke(
            null,
            1,
            'UPDATE_COMPANY',
            'update_company_partial_propagation',
            ['resolved' => [['ref' => 'crm-7']], 'unresolved' => [], 'effective' => [['site_user_id' => 77]]],
            123
        );

        $this->assert(\is_array($payload), 'UPDATE_COMPANY runtime payload is array');
        if (!\is_array($payload)) {
            return;
        }
        $this->assert(($payload['reason_code'] ?? '') === 'update_company_partial_propagation', 'UPDATE_COMPANY runtime payload keeps reason_code');
        $evidence = $payload['evidence'] ?? null;
        $this->assert(\is_array($evidence), 'UPDATE_COMPANY runtime payload has evidence');
        if (!\is_array($evidence)) {
            return;
        }
        $this->assert(isset($evidence['resolved']) && \is_array($evidence['resolved']), 'UPDATE_COMPANY evidence resolved list');
        $this->assert(isset($evidence['unresolved']) && \is_array($evidence['unresolved']), 'UPDATE_COMPANY evidence unresolved list');
        $this->assert(isset($evidence['effective']) && \is_array($evidence['effective']), 'UPDATE_COMPANY evidence effective list');
    }

    private function testUpdateContactPayloadSanitizerKeepsIdAndManagerFallbacks(): void
    {
        $payload = [
            'UF_CRM_3804624445748' => '456',
            'UF_CRM_1757682312' => '789',
            'UF_CRM_1775034008956' => '1',
            'UF_CRM_RANDOM_NOISE' => 'drop-me',
            '_SYNC_TRACE' => 'drop-me-too',
        ];
        CrmInboundUfMap::prepareUserUpdatePayload($payload);

        $this->assert(isset($payload['UF_CRM_3804624445748']), 'payload sanitizer keeps site user fallback UF');
        $this->assert(isset($payload['UF_CRM_1757682312']), 'payload sanitizer keeps second manager UF');
        $this->assert(isset($payload['UF_ADVERSTERING_AGENT']) && (int)$payload['UF_ADVERSTERING_AGENT'] === 1, 'payload sanitizer maps advertising UF');
        $this->assert(!isset($payload['UF_CRM_RANDOM_NOISE']), 'payload sanitizer drops unknown UF_CRM noise');
        $this->assert(!isset($payload['_SYNC_TRACE']), 'payload sanitizer drops sync debug keys');
    }

    private function testValidatorContract(): void
    {
        $missingAction = InboundPayloadValidator::validate([]);
        $this->assert($missingAction['valid'] === false, 'missing ACTION rejected');
        $this->assert(($missingAction['reason_code'] ?? '') === 'missing_action', 'missing ACTION reason_code');

        $unknownAction = InboundPayloadValidator::validate(['ACTION' => 'PING']);
        $this->assert($unknownAction['valid'] === true, 'unknown ACTION is delegated to gateway');

        $invalidUpdateCompany = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_COMPANY', 'ACTIVE' => 'Y']);
        $this->assert($invalidUpdateCompany['valid'] === false, 'UPDATE_COMPANY requires mandatory fields');

        $deleteMissing = InboundPayloadValidator::validate(['ACTION' => 'DELETE_CONTACT']);
        $this->assert($deleteMissing['valid'] === false, 'DELETE_CONTACT rejects empty identifiers');
        $this->assert(($deleteMissing['reason_code'] ?? '') === 'delete_contact_missing_id', 'DELETE_CONTACT missing reason_code');

        $deleteB24Only = InboundPayloadValidator::validate(['ACTION' => 'DELETE_CONTACT', 'B24_ID' => '99']);
        $this->assert($deleteB24Only['valid'] === true, 'DELETE_CONTACT accepts B24_ID without legacy ID');

        $updateMissingIds = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT']);
        $this->assert($updateMissingIds['valid'] === false, 'UPDATE_CONTACT rejects when all identifiers are missing');
        $this->assert(($updateMissingIds['reason_code'] ?? '') === 'update_contact_missing_identifier', 'UPDATE_CONTACT missing identifier reason_code');

        $updateLegacyOnly = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT', 'ID' => '123']);
        $this->assert($updateLegacyOnly['valid'] === true, 'UPDATE_CONTACT accepts legacy ID fallback');

        $updateSiteUserOnly = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT', 'UF_CRM_3804624445748' => '456']);
        $this->assert($updateSiteUserOnly['valid'] === true, 'UPDATE_CONTACT accepts UF_CRM site user fallback');

        $updateB24ZeroLegacyFallback = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT', 'B24_ID' => '0', 'ID' => '789']);
        $this->assert($updateB24ZeroLegacyFallback['valid'] === true, 'UPDATE_CONTACT accepts fallback when B24_ID is zero');

        $updateInvalidLegacyWithoutB24 = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT', 'ID' => 'abc']);
        $this->assert($updateInvalidLegacyWithoutB24['valid'] === false, 'UPDATE_CONTACT rejects non-numeric legacy fallback without B24_ID');
        $this->assert(($updateInvalidLegacyWithoutB24['reason_code'] ?? '') === 'update_contact_missing_identifier', 'UPDATE_CONTACT invalid legacy fallback reason_code');

        $updateInvalidSiteUserWithoutB24 = InboundPayloadValidator::validate(['ACTION' => 'UPDATE_CONTACT', 'UF_CRM_3804624445748' => 'abc']);
        $this->assert($updateInvalidSiteUserWithoutB24['valid'] === false, 'UPDATE_CONTACT rejects non-numeric UF_CRM fallback without B24_ID');
        $this->assert(($updateInvalidSiteUserWithoutB24['reason_code'] ?? '') === 'update_contact_missing_identifier', 'UPDATE_CONTACT invalid UF_CRM fallback reason_code');

        $updateValidB24WithGarbageFallbacks = InboundPayloadValidator::validate([
            'ACTION' => 'UPDATE_CONTACT',
            'B24_ID' => '77',
            'ID' => 'abc',
            'UF_CRM_3804624445748' => 'abc',
        ]);
        $this->assert($updateValidB24WithGarbageFallbacks['valid'] === true, 'UPDATE_CONTACT accepts valid B24_ID even with invalid fallback IDs');
    }

    private function testSecurityContract(): void
    {
        $cfg = ['inbound_secret' => 'top-secret'];
        $result = InboundSecurity::verifyRequest(
            ['REQUEST_METHOD' => 'POST', 'HTTP_X_SYNC_TOKEN' => 'top-secret'],
            ['ACTION' => 'UPDATE_CONTACT'],
            $cfg
        );
        $this->assert($result['allowed'] === true, 'security accepts valid token');

        $forbidden = InboundSecurity::verifyRequest(
            ['REQUEST_METHOD' => 'POST'],
            ['ACTION' => 'UPDATE_CONTACT'],
            ['inbound_secret' => 'top-secret']
        );
        $this->assert($forbidden['allowed'] === false, 'security rejects missing token');
        $this->assert(($forbidden['error_code'] ?? '') === 'sync_forbidden', 'security reason_code sync_forbidden');

        $methodBlocked = InboundSecurity::verifyRequest(
            ['REQUEST_METHOD' => 'GET', 'HTTP_X_SYNC_TOKEN' => 'top-secret'],
            ['ACTION' => 'UPDATE_CONTACT'],
            ['inbound_secret' => 'top-secret', 'require_post_method' => true]
        );
        $this->assert($methodBlocked['allowed'] === false, 'security rejects non-post method');
        $this->assert(($methodBlocked['error_code'] ?? '') === 'sync_method_not_allowed', 'security method reason_code');

        $signaturePayload = '{"ACTION":"UPDATE_CONTACT","B24_ID":"7"}';
        $signature = hash_hmac('sha256', $signaturePayload, 'sig-secret');
        $signedOk = InboundSecurity::verifyRequest(
            ['REQUEST_METHOD' => 'POST', 'HTTP_X_SYNC_TOKEN' => 'top-secret', 'HTTP_X_SYNC_SIGNATURE' => $signature],
            ['ACTION' => 'UPDATE_CONTACT', 'B24_ID' => '7'],
            ['inbound_secret' => 'top-secret', 'inbound_hmac_secret' => 'sig-secret'],
            $signaturePayload
        );
        $this->assert($signedOk['allowed'] === true, 'security accepts valid hmac signature');

        $signedFail = InboundSecurity::verifyRequest(
            ['REQUEST_METHOD' => 'POST', 'HTTP_X_SYNC_TOKEN' => 'top-secret', 'HTTP_X_SYNC_SIGNATURE' => 'bad-signature'],
            ['ACTION' => 'UPDATE_CONTACT', 'B24_ID' => '7'],
            ['inbound_secret' => 'top-secret', 'inbound_hmac_secret' => 'sig-secret'],
            $signaturePayload
        );
        $this->assert($signedFail['allowed'] === false, 'security rejects invalid hmac signature');
        $this->assert(($signedFail['error_code'] ?? '') === 'sync_signature_invalid', 'security hmac reason_code');
    }

    private function testDedupContract(): void
    {
        $storePath = \sys_get_temp_dir() . '/yomerch-contract-harness-dedup.json';
        @\unlink($storePath);

        $cfg = ['inbound_dedup_ttl_seconds' => 120, 'inbound_dedup_store_path' => $storePath];
        $request = ['ACTION' => 'UPDATE_CONTACT', '_SYNC_REQUEST_ID' => 'req-001'];
        SyncTrace::setRequestId('req-001');

        $first = InboundIdempotencyGate::assertNotDuplicate($request, $cfg);
        $this->assert($first['allowed'] === true, 'dedup first request allowed');

        $second = InboundIdempotencyGate::assertNotDuplicate($request, $cfg);
        $this->assert($second['allowed'] === false, 'dedup duplicate blocked');
        $this->assert(($second['reason_code'] ?? '') === 'dedup_duplicate', 'dedup reason_code set');

        @\unlink($storePath);
    }

    private function assert(bool $condition, string $label): void
    {
        if ($condition) {
            $this->passed++;
            return;
        }

        $this->failed++;
        \fwrite(STDERR, '[FAIL] ' . $label . PHP_EOL);
    }
}

$harness = new ContractHarness();
exit($harness->run());
