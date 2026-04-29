# B24 Outbound Policy

## Decision
Канонический путь исходящих запросов site -> Bitrix24: через модуль `yomerch.b24.rest`.

## Allowed
- `\OnlineService\B24\RestClient::callRestMethod()`
- `\OnlineService\B24\RestClient::callKitRestGet()`
- `\OnlineService\B24\RestClient::postAjaxProxy()`
- `\OnlineService\B24\RestClient::postSiteRequestsHandler()`
- Legacy-обертки из `LegacyGlobalB24` только как совместимость при миграции.

## Not allowed (without explicit exception record)
- Прямой `curl` на webhook URL B24 в доменном коде.
- Прямые `file_get_contents("https://.../rest/...")` запросы к B24.
- Непрозрачные helper-функции вне `yomerch.b24.rest`, дублирующие транспорт.

## Exception process
Если обход необходим временно:
1. Завести запись в `docs/refactoring/legacy_integration_exceptions_register.md`.
2. Указать owner, срок и план миграции.
3. Не закрывать задачу без статуса по исключению.
