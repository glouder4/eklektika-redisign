# SBT-04: Idempotency + dedup gate

## Статус
- status: verification_pending (проверка TTL и retry-storm на стенде)
- owner-role: backend-integrations

## Зависимости
- SBT-01
- SBT-03

## Acceptance checklist
- [x] Реализован `InboundIdempotencyGate`
- [x] Детерминированный dedup key
- [x] Ответ duplicate: `duplicate_request` (HTTP 409)
- [x] TTL/store path вынесены в конфиг
- [x] Утвержден production-safe storage backend (файл + явный путь; см. `docs/refactoring/inbound_dedup_storage_policy.md`)
- [ ] Включен TTL > 0 на стенде и проверен retry-storm сценарий

## Next action
На стенде: выставить `inbound_dedup_ttl_seconds` (для strict — не ниже профиля), задать `inbound_dedup_store_path` вне temp, выполнить сценарий повторной доставки.
