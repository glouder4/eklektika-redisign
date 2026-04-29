# SBT-01: Inbound contract + security hardening

## Статус
- status: verification_pending (стендовый smoke по всем ACTION — после изменений со стороны Bitrix24)
- owner-role: backend-integrations

## Зависимости
- SBT-02
- SBT-06

## Acceptance checklist
- [x] Канонический endpoint: `local/modules/yomerch.b24.inbound/endpoint.php`
- [x] Fail-closed по секрету (`sync_forbidden`)
- [x] Token policy (`X-SYNC-TOKEN` / `sync_token`)
- [x] Поддержан strict token policy (`inbound_require_header_token`) и optional HMAC (`X-Sync-Signature`)
- [x] Payload validation по ACTION
- [x] `unknown_action` для невалидного ACTION
- [x] В `InboundGateway` введен канонический словарь contract-кодов (`reason_code`, `event`, `error`)
- [ ] Стендовый smoke по всем ACTION

## Next action
Собрать внешний handoff-пакет по inbound контракту для команды Bitrix24; полный стендовый smoke по всем ACTION — после их переработки.
