# Initiative: outbound inbound-proxy `sync_token` auto-inject

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `RestClient::prepareInboundProxyAuth` + header/body ветка | done |
| T2 | `executePostFull` опциональные заголовки | done |
| T3 | Док `b24_site_contracts_yomerch.md` §1.1 | done |
| T4 | ADR `docs/adr/ADR-2026-04-30-outbound-inbound-proxy-sync-token.md` | done |

## Next steps for Team Lead

- [ ] Прогон сценариев: `GET_CONTACT_ID`, `DELETE_CONTACT` с портала/сайта — ожидать не `403 sync_forbidden`, а бизнес-ответ (200 + success или валидация).
- [ ] Убедиться, что на **обоих** концах один и тот же `inbound_secret` (сайт и хост `URL_B24` с `endpoint.php`).

## Audit

- [ ] При `inbound_require_header_token=true` проверить, что заголовок доходит до PHP как `HTTP_X_SYNC_TOKEN`.

## Risks

- Два разных секрета на сайте и на хосте `URL_B24` — по-прежнему 403; это конфигурация, не баг транспорта.
