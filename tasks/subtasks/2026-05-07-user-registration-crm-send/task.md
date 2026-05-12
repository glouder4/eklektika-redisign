# Initiative: user registration → CRM (Bitrix24) sync integration points

## Business goal

Зафиксировать и верифицировать точки интеграции, по которым регистрация пользователя на сайте приводит к созданию/обновлению сущностей в CRM (Bitrix24): контакт (и при необходимости компания) + связка `site user id ↔ crm contact id`.

## Context and constraints

- Scope: `local/modules/**` + `local/php_interface/**` (не трогать `local/templates`).
- Graphify hint (scoped): `\OnlineService\B24\RegisterUserCompany::OnAfterUserRegisterHandler()` в `local/modules/yomerch.b24.usersync/lib/RegisterUserCompany.php`.
- Фактическая регистрация на сайте может идти через `CUser::Add()` (а не `CUser::Register()`), поэтому события `OnBeforeUserRegister/OnAfterUserRegister` могут **не** сработать; вместо этого используются `OnBeforeUserAdd/OnAfterUserAdd`.

## What we found (entrypoints + call chain)

### 1) Event integration point (Bitrix main events)

- Регистрация обработчиков: `local/modules/yomerch.b24.usersync/lib/UserSyncBootstrap.php`
- Хуки событий:
  - `main:OnBeforeUserAdd` → `\OnlineService\Events\SyncEventHandlers::onBeforeUserAdd()` → `\OnlineService\B24\RegisterUserCompany::OnBeforeUserRegisterHandler()`
  - `main:OnAfterUserAdd` → `\OnlineService\Events\SyncEventHandlers::onAfterUserAdd()` → `\OnlineService\B24\RegisterUserCompany::OnAfterUserRegisterHandler()`

### 2) CRM outbound calls executed after successful add()

Файл: `local/modules/yomerch.b24.usersync/lib/RegisterUserCompany.php`

- `OnBeforeUserRegisterHandler()`:
  - отключает пользователя (`ACTIVE='N'`) на pre-hook этапе
  - делает prechecks уникальности email и ИНН, в т.ч. через CRM:
    - `crm.duplicate.findbycomm` (email)
    - `crm.requisite.list` (ИНН)
- `OnAfterUserRegisterHandler()`:
  - при `USER_ID>0` создаёт/привязывает CRM сущности через `createB24Company()`:
    - `crm.company.add`, `crm.company.get`, `crm.company.update`
    - `crm.requisite.add`, `crm.requisite.update`, `crm.requisite.list`
    - `crm.contact.add`, `crm.contact.get`, `crm.contact.company.add`
  - пишет `UF_B24_USER_ID` в `b_user` (связь site user → crm contact id) и оставляет `ACTIVE='N'`
  - отправляет email-событие `NEW_USER_CONFIRM` через `\Bitrix\Main\Mail\Event::send()`

## Config / env dependencies (no secrets)

- Bitrix24 REST URL + webhook tokens:
  - `local/php_interface/b24_integration_config.php` (return array `base_url`, `rest_webhook_main`, `rest_webhook_kit`)
  - константы: `URL_B24`, `B24_REST_WEBHOOK_MAIN`, `B24_REST_WEBHOOK_KIT` (инициализация в `local/php_interface/init.php`, а также fallback в `local/modules/yomerch.b24.rest/lib/RestClient.php`)
- REST URL construction:
  - `local/modules/yomerch.b24.rest/lib/Config/RestTransportConfig.php` → `buildMainWebhookMethodUrl($method)`
- CRM поля (custom UF) и mapping:
  - `local/modules/yomerch.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`
  - `local/modules/yomerch.b24.usersync/lib/Config/UserSyncConfig.php`

## Subtasks (investigation / documentation)

- [subtasks/01-locate-handler-and-event-binding.md](subtasks/01-locate-handler-and-event-binding.md)
- [subtasks/02-map-call-chain-and-crm-methods.md](subtasks/02-map-call-chain-and-crm-methods.md)
- [subtasks/03-verify-crm-transport-and-endpoints.md](subtasks/03-verify-crm-transport-and-endpoints.md)
- [subtasks/04-config-and-dependencies-inventory.md](subtasks/04-config-and-dependencies-inventory.md)
- [subtasks/05-test-steps-and-evidence.md](subtasks/05-test-steps-and-evidence.md)

## Definition of Done (initiative acceptance)

- [ ] Указаны конкретные файлы и события Bitrix, обеспечивающие запуск CRM-синка при регистрации.
- [ ] Описана цепочка вызовов до REST транспорта и перечень `crm.*` методов.
- [ ] Зафиксированы конфиги/зависимости (пути, константы, глобальные конфиги) без утечки секретов.
- [ ] Добавлен проверяемый test plan (ручной/логический) + expected evidence.
- [ ] ADR и task/subtasks двусторонне связаны и статусно согласованы.

## Status and progress

- Priority: `P1`
- Status: `in_progress`
- Progress: `60%` (точки входа и цепочка подтверждены в коде; требуется финализация тест-пакета и TL execution checklist)
- Blockers:
  - нет hard blocker в документации; runtime верификация зависит от доступа к тест-стенду/логам.

## ADR link

- [adr.md](adr.md)

## Next steps for Team Lead

- [ ] Проверить фактический сценарий регистрации на сайте: где вызывается `CUser::Add()` (ожидаемо — ajax action), и убедиться, что `OnBeforeUserAdd/OnAfterUserAdd` реально срабатывают.
  - Search directives:
    - искать `CUser::Add(` и `ajax-register-action` по `local/` и `bitrix/` (если нужно)
    - искать `OS_SKIP_USERSYNC_EVENTS` (флаги отключения синка) по `local/`
- [ ] Подтвердить на стенде, что после регистрации выполняются outbound вызовы `crm.contact.add` и (для юрлиц/агентов) `crm.company.add`/`crm.requisite.*`.
  - Primary files:
    - `local/modules/yomerch.b24.usersync/lib/RegisterUserCompany.php`
    - `local/modules/yomerch.b24.rest/lib/RestClient.php`
- [ ] Зафиксировать минимальный evidence-пакет:
  - созданный site user id
  - записанный `UF_B24_USER_ID`
  - полученный CRM contact id и (опционально) company id
  - логи ошибок транспорта (если есть) из `SyncInboundLog` / системного лога Bitrix (`CEventLog`, тип `YOMERCH_POST_REGISTRATION_SYNC_ISSUE`)

