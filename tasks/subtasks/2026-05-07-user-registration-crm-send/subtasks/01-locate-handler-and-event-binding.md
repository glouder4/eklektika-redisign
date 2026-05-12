# Subtask 01: locate handler and event binding

- Parent: [../task.md](../task.md)
- Status: `done`

## Goal

Найти точные обработчики/обёртки, которые запускают CRM-синк при регистрации пользователя на сайте, и зафиксировать, какие события Bitrix являются фактическим entrypoint.

## Scope

- Только `local/` (без `local/templates` и `local/components`).

## Findings (confirmed)

- `local/modules/yomerch.b24.usersync/lib/UserSyncBootstrap.php` регистрирует события:
  - `main:OnBeforeUserAdd`
  - `main:OnAfterUserAdd`
- `local/modules/yomerch.b24.usersync/lib/SyncEventHandlers.php` делегирует:
  - `onBeforeUserAdd()` → `\OnlineService\B24\RegisterUserCompany::OnBeforeUserRegisterHandler()`
  - `onAfterUserAdd()` → `\OnlineService\B24\RegisterUserCompany::OnAfterUserRegisterHandler()`

## Definition of Done

- [x] Указаны точные файлы регистрации событий и обработчиков.
- [x] Указано соответствие `On*UserAdd` → `RegisterUserCompany::On*UserRegisterHandler`.

## Verification checklist

- [x] Поиск по репозиторию подтверждает единственный путь entrypoint для этих методов в `local/modules`.

