# SBT-04: Idempotency + dedup gate

## Статус
- status: in_progress
- owner-role: backend-integrations

## Зависимости
- SBT-01
- SBT-03

## Acceptance checklist
- [x] Реализован `InboundIdempotencyGate`
- [x] Детерминированный dedup key
- [x] Ответ duplicate: `duplicate_request` (HTTP 409)
- [x] TTL/store path вынесены в конфиг
- [ ] Утвержден production-safe storage backend
- [ ] Включен TTL > 0 на стенде и проверен retry-storm сценарий

## Next action
Утвердить storage policy (файл/redis/другое), включить dedup в стенде и перепроверить дубли.
