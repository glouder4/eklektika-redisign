<?php

declare(strict_types=1);

use OnlineService\Sync\FromCrm\InboundGateway;

require_once dirname(__DIR__) . '/lib/from-crm/InboundGateway.php';

$map = InboundGateway::actionContractMap();
\ksort($map, \SORT_STRING);

$lines = [];
$lines[] = '# Inbound ACTION Contract Map';
$lines[] = '';
$lines[] = '| ACTION | event | success_reason | failure_reason |';
$lines[] = '| --- | --- | --- | --- |';

foreach ($map as $action => $contract) {
    $lines[] = \sprintf(
        '| %s | %s | %s | %s |',
        (string)$action,
        (string)($contract['event'] ?? ''),
        (string)($contract['success_reason'] ?? ''),
        (string)($contract['failure_reason'] ?? '')
    );
}

$markdown = \implode(PHP_EOL, $lines) . PHP_EOL;
$targetPath = isset($argv[1]) && \is_string($argv[1]) ? \trim($argv[1]) : '';

if ($targetPath === '') {
    echo $markdown;
    exit(0);
}

if (\strpos($targetPath, DIRECTORY_SEPARATOR) !== 0 && !\preg_match('/^[A-Za-z]:\\\\/', $targetPath)) {
    $targetPath = \getcwd() . DIRECTORY_SEPARATOR . $targetPath;
}

$targetDir = \dirname($targetPath);
if (!\is_dir($targetDir) && !@\mkdir($targetDir, 0755, true) && !\is_dir($targetDir)) {
    \fwrite(STDERR, 'Failed to create directory: ' . $targetDir . PHP_EOL);
    exit(1);
}

$ok = @\file_put_contents($targetPath, $markdown);
if ($ok === false) {
    \fwrite(STDERR, 'Failed to write file: ' . $targetPath . PHP_EOL);
    exit(1);
}

echo 'Contract map exported to: ' . $targetPath . PHP_EOL;
