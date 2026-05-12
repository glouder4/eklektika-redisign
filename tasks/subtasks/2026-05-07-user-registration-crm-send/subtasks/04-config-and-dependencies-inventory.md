# Subtask 04: config and dependencies inventory

- Parent: [../task.md](../task.md)
- Status: `done`

## Goal

Составить инвентарь конфигов/зависимостей для registration → CRM send, не фиксируя секреты.

## Inventory (confirmed)

### Runtime config

- `local/php_interface/b24_integration_config.php`
  - возвращает массив:
    - `use_test_portal` (bool)
    - `base_url` (portal URL)
    - `rest_webhook_main` (webhook segment, secret)
    - `rest_webhook_kit` (webhook segment, secret)

### Constants used by transport

Определяются в `local/php_interface/init.php` (и подстрахованы fallback-инициализацией в `RestClient::ensureB24ConfigLoaded()`):

- `B24_USE_TEST_PORTAL`
- `URL_B24`
- `B24_REST_WEBHOOK_MAIN`
- `B24_REST_WEBHOOK_KIT`

### Module boundaries

- `yomerch.b24.usersync`
  - entrypoints, orchestration: `SyncEventHandlers`, `UserSyncBootstrap`
  - business logic: `RegisterUserCompany`
  - CRM custom field mapping: `RegisterUserCompanyConfig`, `UserSyncConfig`
- `yomerch.b24.rest`
  - outbound transport: `RestClient`, `RestTransportConfig`

## Definition of Done

- [x] Конфиг-источник и константы перечислены без секретных значений.
- [x] Зафиксированы границы модулей (usersync vs rest) и их ответственности.

## Verification checklist

- [x] Поиск по `b24_integration_config.php` подтверждает единый источник базового URL и webhook segments.

