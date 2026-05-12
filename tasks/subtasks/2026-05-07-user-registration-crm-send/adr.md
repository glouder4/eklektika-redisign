# ADR: integration point for user registration → CRM (Bitrix24) send

- Initiative: [task.md](task.md)
- Status: accepted
- Date: 2026-05-07

## Decision

Считать канонической точкой интеграции “регистрация пользователя на сайте → отправка данных в CRM” обработчики событий Bitrix `main:OnBeforeUserAdd` и `main:OnAfterUserAdd`, зарегистрированные модулем `yomerch.b24.usersync` и делегирующие в `\OnlineService\B24\RegisterUserCompany`:

- pre-hook (валидации/гейты до создания пользователя): `OnBeforeUserAdd` → `OnBeforeUserRegisterHandler()`
- post-hook (создание/привязка CRM сущностей): `OnAfterUserAdd` → `OnAfterUserRegisterHandler()`

## Why

- Фактический поток регистрации на проекте использует `CUser::Add()`; для него гарантированно срабатывают `OnBeforeUserAdd/OnAfterUserAdd`, тогда как `OnBeforeUserRegister/OnAfterUserRegister` могут не вызываться.
- Синхронизация с CRM должна происходить **после** успешного создания пользователя (есть `USER_ID`), чтобы:
  - записать связку `UF_B24_USER_ID` (CRM contact id) в `b_user`;
  - отправить корректное подтверждающее письмо `NEW_USER_CONFIRM`.

## Integration points (code-level)

- Event binding:
  - `local/modules/yomerch.b24.usersync/lib/UserSyncBootstrap.php`
  - `local/modules/yomerch.b24.usersync/lib/SyncEventHandlers.php`
- CRM sync implementation:
  - `local/modules/yomerch.b24.usersync/lib/RegisterUserCompany.php`
- Transport / endpoint construction:
  - `local/modules/yomerch.b24.rest/lib/RestClient.php`
  - `local/modules/yomerch.b24.rest/lib/Config/RestTransportConfig.php`
- Config entry:
  - `local/php_interface/b24_integration_config.php` (base URL + webhook segments; значения секретов не фиксируются в ADR)

## Consequences

- Team Lead при расследованиях “не уходит в CRM после регистрации” начинает с проверки `OnBeforeUserAdd/OnAfterUserAdd` и `OS_SKIP_USERSYNC_EVENTS`, а не с шаблонов/форм.
- Любые изменения в регистрации/активации пользователя должны учитывать, что `OnBeforeUserRegisterHandler()` принудительно выставляет `ACTIVE='N'` и делает CRM prechecks.
- Для стабильности UX post-hook сбрасывает возможное `ThrowException` в `$APPLICATION` после попытки синка (чтобы не смешивать успех регистрации и ошибки синка).

## Links

- Main task: [task.md](task.md)
- Subtask 1: [subtasks/01-locate-handler-and-event-binding.md](subtasks/01-locate-handler-and-event-binding.md)
- Subtask 2: [subtasks/02-map-call-chain-and-crm-methods.md](subtasks/02-map-call-chain-and-crm-methods.md)
- Subtask 3: [subtasks/03-verify-crm-transport-and-endpoints.md](subtasks/03-verify-crm-transport-and-endpoints.md)
- Subtask 4: [subtasks/04-config-and-dependencies-inventory.md](subtasks/04-config-and-dependencies-inventory.md)
- Subtask 5: [subtasks/05-test-steps-and-evidence.md](subtasks/05-test-steps-and-evidence.md)

