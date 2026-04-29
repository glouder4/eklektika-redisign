# SBT-03: Contract tests harness

## Статус
- status: blocked
- owner-role: qa-automation + backend-integrations

## Зависимости
- SBT-01
- SBT-02
- SBT-04
- SBT-05

## Acceptance checklist
- [x] Добавлен harness: `local/modules/yomerch.b24.inbound/tests/contract/contract_harness.php`
- [x] Добавлены кейсы validator/security/dedup
- [ ] Выполнен фактический запуск harness
- [ ] Приложен артефакт результата прогона

## Блокер
- В окружении отсутствует `php` CLI в PATH.
- Интеграционные тесты синхронизации зависят от внешней переработки команды Bitrix24.

## Next action
Завершить internal implementation pull и передать внешний пакет Bitrix24; после подтверждения их переработки выполнить обязательный прогон harness и sync smoke.
