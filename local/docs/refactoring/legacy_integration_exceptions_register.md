# Legacy Integration Exceptions Register

Реестр временных исключений интеграционного контура B24.

## Правило
- Любой прямой `curl`/webhook обход `yomerch.b24.rest` должен быть внесен в реестр.
- Для каждого исключения обязателен владелец и план вывода.

## Records

| Path | Exception type | Owner | Status | Exit plan |
|---|---|---|---|---|
| `local/modules/intec.eklectika/classes/advertising_agent/DismissEmployees.php` | Direct webhook/curl call to B24, bypass transport | TBD | accepted-temporary | Перевести на `RestClient` или изолировать вне production потока |

## Status values
- `accepted-temporary`
- `deprecate-planned`
- `removed`
