<?php
/**
 * Пример структуры b24_integration_config.php.
 * Скопируйте в b24_integration_config.php и подставьте URL портала и токены вебхуков.
 */
$useTestPortal = false;

return [
    'base_url' => $useTestPortal
        ? 'https://test-portal.example.com/'
        : 'https://prod-portal.example.com/',
    'rest_webhook_main' => 'your_incoming_webhook_token',
    'rest_webhook_kit' => 'your_kit_applications_webhook_token',
];
