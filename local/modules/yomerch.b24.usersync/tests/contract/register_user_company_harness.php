<?php

declare(strict_types=1);

namespace OnlineService\B24 {
    if (!class_exists(Request::class, false)) {
        class Request
        {
        }
    }
}

namespace {
require_once __DIR__ . '/../../lib/RegisterUserCompany.php';

use OnlineService\B24\RegisterUserCompany;

final class RegisterUserCompanyHarness
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): int
    {
        $this->testEmailConflictReasonCodes();
        $this->testEmailCheckUnavailableReasonCodeContract();
        $this->testFailClosedNoSideEffectConflictContract();
        echo 'passed=' . $this->passed . ' failed=' . $this->failed . PHP_EOL;

        return $this->failed === 0 ? 0 : 1;
    }

    private function testEmailConflictReasonCodes(): void
    {
        $instance = new RegisterUserCompany();
        $method = new ReflectionMethod(RegisterUserCompany::class, 'resolveEmailConflictReasonCode');
        $method->setAccessible(true);

        $site = $method->invoke($instance, true, false);
        $crm = $method->invoke($instance, false, true);
        $both = $method->invoke($instance, true, true);
        $none = $method->invoke($instance, false, false);

        $this->assert($site === 'email_conflict_site', 'site conflict reason_code');
        $this->assert($crm === 'email_conflict_crm', 'crm conflict reason_code');
        $this->assert($both === 'email_conflict_both', 'both conflict reason_code');
        $this->assert($none === null, 'no conflict has no reason_code');
    }

    private function testEmailCheckUnavailableReasonCodeContract(): void
    {
        $reflection = new ReflectionClass(RegisterUserCompany::class);
        $constant = $reflection->getConstant('REASON_EMAIL_CHECK_UNAVAILABLE');

        $this->assert($constant === 'email_check_unavailable', 'reason_code email_check_unavailable constant');
    }

    private function testFailClosedNoSideEffectConflictContract(): void
    {
        $sourcePath = __DIR__ . '/../../lib/RegisterUserCompany.php';
        $source = @file_get_contents($sourcePath);
        $this->assert(is_string($source) && $source !== '', 'RegisterUserCompany source readable');
        if (!is_string($source) || $source === '') {
            return;
        }

        $guardPattern = '/if\s*\(!\$this->ensureEmailUniquenessPrecheck\(\$arFields\)\)\s*\{\s*return\s+false\s*;\s*\}/m';
        $guardEntrySnippet = '!$this->ensureEmailUniquenessPrecheck($arFields)';
        $responseSnippet = '$response = $this->isUserRegistered($arFields);';
        $createSnippet = '$this->createB24Company($arFields)';

        $guardPresent = preg_match($guardPattern, $source) === 1;
        $guardEntryPos = strpos($source, $guardEntrySnippet);
        $responsePos = strpos($source, $responseSnippet);
        $createPos = strpos($source, $createSnippet);

        $this->assert($guardPresent, 'email precheck fail-closed guard present');
        $this->assert($responsePos !== false, 'duplicate check call present');
        $this->assert($guardPresent && $guardEntryPos !== false && $responsePos !== false && $guardEntryPos < $responsePos, 'fail-closed guard executes before duplicate lookup');
        $this->assert($guardPresent && $guardEntryPos !== false && ($createPos === false || $guardEntryPos < $createPos), 'precheck guard protects CRM side effects');
    }

    private function assert(bool $condition, string $label): void
    {
        if ($condition) {
            $this->passed++;

            return;
        }
        $this->failed++;
        fwrite(STDERR, '[FAIL] ' . $label . PHP_EOL);
    }
}

$harness = new RegisterUserCompanyHarness();
exit($harness->run());
}
