# Initiative: RestClient — inbound JSON vs `b24_api_error`

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `isInboundEndpointResponseUrl` + пропуск `normalizeB24ApiError` для `endpoint.php` | done |
| T2 | `outbound_detail` в логе для настоящего `b24_api_error` | done |
| T3 | Док §5.3 + ADR | done |

## Team Lead — data follow-up (не код)

- **`update_contact_user_not_found`** для `B24_ID`/`ID` = 777: на сайте нет пользователя с привязкой к этому CRM contact id — проверить регистрацию/маппинг UF, либо не слать `UPDATE_CONTACT` до появления пользователя.

## Audit

- [ ] Реальный вызов B24 REST с телом `{"error":"..."}` (без inbound URL) по-прежнему даёт `b24_api_error` и `outbound_detail` в логе.

## Risks

- Если когда-нибудь URL inbound переименуют без `endpoint.php` в пути — условие нужно обновить.
