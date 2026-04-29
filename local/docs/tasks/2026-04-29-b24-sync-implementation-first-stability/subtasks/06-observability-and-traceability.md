# SBT-06: Observability and traceability

## Статус
- status: partially_done
- owner-role: backend-integrations + devops

## Зависимости
- SBT-01
- SBT-02

## Acceptance checklist
- [x] `request_id` прокинут через endpoint/ответы
- [x] `SyncTrace` поддерживает request correlation
- [x] Inbound logging санитизирует payload и фиксирует raw body с ограничениями
- [x] Reject и dispatch-failed логи унифицированы
- [x] Outcome-логирование унифицировано (`inbound.outcome`) с `reason_code` по основным ACTION
- [x] Формализованы SLI/SLO (error-rate, dedup-hit, auth-reject): `docs/refactoring/inbound_sli_slo.md`
- [ ] Настроены алерты/дашборды по reason_code (зависит от стека логирования на хостинге)

## Next action
Подключить дашборды/алерты по полям SLI-документа; использовать как gate release readiness после Фазы B.
