# Initiative: inbound trace — значения в логе

## Done

| ID | Title | Status |
|----|--------|--------|
| T0 | Док: `ACTIVE` в UPDATE_COMPANY приходит от CRM; UF может перезаписать | done |
| T0b | Док `UPDATE_CONTACT.md` + строка в контракте про `ID` vs `B24_ID` | done |
| T0c | `UPDATE_CONTACT failed` trace с `B24_ID` / `legacy_ID_param` | done |
| T1 | `params_preview` в `SyncTrace::summarizeRequest` | done |
| T2 | Рефактор `InboundRequestLogger` (preview + redact tree) | done |
| T3 | Флаг `sync_inbound_trace_full_payload` + bootstrap/example | done |
| T4 | Enum 31519 для `OS_IS_MARKETING_AGENT` + `Company::isMarketingAgentParamOn` | done |
| T5 | ADR | done |

## Next steps for Team Lead

- [ ] На проде при отладке: `sync_debug=true` **или** `sync_inbound_log=true`; при необходимости полного дерева — `sync_inbound_trace_full_payload=true` (кратковременно).

## Risks

- `params_preview` увеличивает размер строки лога; длинные поля усекаются (см. лимиты в `InboundRequestLogger`).
