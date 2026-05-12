# Subtask 03: verify CRM transport and endpoints

- Parent: [../task.md](../task.md)
- Status: `done`

## Goal

Подтвердить, какой именно транспорт/endpoint используется для отправки данных в CRM при регистрации, и какие компоненты за это отвечают.

## Findings (confirmed)

- Outbound вызовы регистрации используют **штатный REST webhook** (`.../rest/1/{token}/{method}.json`):
  - `\OnlineService\B24\RestClient::callRestMethod()`
  - URL строится в `\OnlineService\B24\Config\RestTransportConfig::buildMainWebhookMethodUrl($method)`
- Альтернативный транспорт `postAjaxProxy()` (через `URL_B24 + /local/modules/yomerch.b24.inbound/endpoint.php`) в регистрации **не используется**.

## Dependencies

- `local/modules/yomerch.b24.rest/lib/RestClient.php`
- `local/modules/yomerch.b24.rest/lib/Config/RestTransportConfig.php`

## Definition of Done

- [x] Подтверждено, что `callRestMethod()` — основной транспорт в registration flow.
- [x] Зафиксирован способ построения endpoint URL.

## Verification checklist

- [x] В `RegisterUserCompany.php` все CRM вызовы идут через `callB24Method()` → `RestClient::callRestMethod()`.

