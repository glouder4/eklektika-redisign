# SBT-05: Business-effects compliance

## Статус
- status: planned
- owner-role: backend-integrations + product-owner

## Зависимости
- SBT-01
- SBT-04

## Acceptance checklist
- [x] В gateway есть блокировка через `inbound_disabled_actions`
- [x] Единый ответ `business_effects_blocked` (HTTP 409)
- [ ] Согласован список временно блокируемых ACTION
- [ ] Для каждого blocked ACTION назначен owner и exit-условие
- [ ] Policy зафиксирована в конфиге стенда

## Next action
Согласовать risk-policy по ACTION и включить ее в стенде до перехода к P2.
