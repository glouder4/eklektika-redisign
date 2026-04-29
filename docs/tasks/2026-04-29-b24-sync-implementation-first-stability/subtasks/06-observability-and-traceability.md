# SBT-06: Observability and traceability

## Статус
- status: in_progress
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
- [ ] Формализованы SLI/SLO (error-rate, dedup-rate, auth-reject-rate)
- [ ] Настроены алерты/дашборды по reason_code

## Next action
Зафиксировать SLI/SLO и подключить мониторинг как gate для release readiness.
