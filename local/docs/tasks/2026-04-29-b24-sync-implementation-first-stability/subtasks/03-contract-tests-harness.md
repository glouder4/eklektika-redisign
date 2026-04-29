# SBT-03: Contract tests harness

## Статус
- status: done
- owner-role: qa-automation + backend-integrations

## Зависимости
- SBT-01
- SBT-02
- SBT-04
- SBT-05

## Acceptance checklist
- [x] Добавлен harness: `local/modules/yomerch.b24.inbound/tests/contract/contract_harness.php`
- [x] Добавлены кейсы validator/security/dedup
- [x] Выполнен фактический запуск harness (в т.ч. через `wsl`/PHP 8.x)
- [x] Приложен артефакт результата прогона: `tests/contract/contract_harness_last_run.txt`
- [x] Добавлен запускатор Windows: `tools/run_contract_harness.ps1`

## Известное ограничение окружения
- Интеграционные сквозные тесты синхронизации с порталом Bitrix24 — **Фаза C**, после подтверждения их переработки (Фаза B).

## Next action
- Повторять прогон harness при изменениях контракта или security/dedup.
- После Фазы B: обязательный прогон harness + sync smoke против обновлённого портала.
