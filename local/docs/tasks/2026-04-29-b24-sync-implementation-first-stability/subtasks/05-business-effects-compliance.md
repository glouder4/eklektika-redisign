# SBT-05: Business-effects compliance

## Статус
- status: partially_done
- owner-role: backend-integrations + product-owner

## Зависимости
- SBT-01
- SBT-04

## Acceptance checklist
- [x] В gateway есть блокировка через `inbound_disabled_actions`
- [x] Единый ответ `business_effects_blocked` (HTTP 409)
- [x] Согласован список временно блокируемых ACTION (**по умолчанию: пустой список — блокировок нет**)
- [ ] Для каждого blocked ACTION назначен owner и exit-условие (актуально только при включении блокировок)
- [ ] Policy при непустом списке зафиксирована в `config.local.php` стенда

Регистр: `docs/refactoring/inbound_blocked_actions_register.md`.

## Next action
При необходимости блокировок ACTION — заполнить таблицу в регистре и обновить `inbound_disabled_actions` на стенде до перехода к P2.
