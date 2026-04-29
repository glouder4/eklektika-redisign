<?php

declare(strict_types=1);

use OnlineService\Sync\FromCrm\InboundPayloadValidator;
use OnlineService\Sync\InboundIdempotencyGate;
use OnlineService\Sync\InboundSecurity;
use OnlineService\Sync\SyncTrace;

require_once __DIR__ . '/../../lib/SyncInboundLog.php';
require_once __DIR__ . '/../../lib/SyncTrace.php';
require_once __DIR__ . '/../../lib/InboundSecurity.php';
require_once __DIR__ . '/../../lib/InboundIdempotencyGate.php';
require_once __DIR__ . '/../../lib/from-crm/InboundPayloadValidator.php';

/**
 * Lightweight contract harness for implementation-first checks.
 * Run: php local/modules/yomerch.b24.inbound/tests/contract/contract_harness.php
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

        echo 'passed=' . $this->passed . ' failed=' . $this->failed . PHP_EOL;

        return $this->failed === 0 ? 0 : 1;
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
