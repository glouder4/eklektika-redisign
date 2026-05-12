# Subtask 02: map call chain and CRM methods

- Parent: [../task.md](../task.md)
- Status: `done`

## Goal

Описать цепочку вызовов от события Bitrix до реальных `crm.*` вызовов Bitrix24 и точки, где пишется связка `site user ↔ crm contact`.

## Findings (confirmed)

### Call chain

1) `main:OnBeforeUserAdd` → `SyncEventHandlers::onBeforeUserAdd()` → `RegisterUserCompany::OnBeforeUserRegisterHandler(&$arFields)`

- эффекты:
  - `ACTIVE='N'`
  - CRM precheck по email: `crm.duplicate.findbycomm`
  - CRM precheck по ИНН: `crm.requisite.list` (для company registration типов)

2) `main:OnAfterUserAdd` → `SyncEventHandlers::onAfterUserAdd()` → `RegisterUserCompany::OnAfterUserRegisterHandler(&$arFields)`

- эффекты:
  - (если нужно) `createB24Company()` создаёт/привязывает CRM сущности:
    - `crm.company.add|get|update`
    - `crm.requisite.add|update|list`
    - `crm.contact.add|get`
    - `crm.contact.company.add`
  - записывает `UF_B24_USER_ID` в `b_user` через `CUser::Update()`
  - отправляет `NEW_USER_CONFIRM` через `\Bitrix\Main\Mail\Event::send()`

### CRM transport

`RegisterUserCompany::callB24Method()` → `\OnlineService\B24\RestClient::callRestMethod($method, $params)`

## Definition of Done

- [x] Зафиксирован перечень `crm.*` методов, которые вызываются в ветке регистрации.
- [x] Зафиксирована точка записи `UF_B24_USER_ID`.

## Verification checklist

- [x] Локальный поиск по `crm.` в `RegisterUserCompany.php` соответствует списку выше.

