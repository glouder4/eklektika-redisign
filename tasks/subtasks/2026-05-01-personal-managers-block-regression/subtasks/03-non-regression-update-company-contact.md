# Subtask 03: Prove non-regression for UPDATE_COMPANY and UPDATE_CONTACT

- Parent task: [../task.md](../task.md)
- ADR: [../adr.md](../adr.md)
- Status: `todo`
- Priority: `P0`

## Scope

Подтвердить, что восстановление UI-блока не ломает полезный hotfix-контур propagation для `UPDATE_COMPANY`/`UPDATE_CONTACT`.

## Inputs

- Фикс из Subtask 02.
- Контрольные сценарии inbound по компании и контакту.
- Логи/trace с `reason_code` и meta/evidence по propagation.

## Outputs

- Non-regression протокол по двум потокам.
- Заключение "go/no-go" для выката P0 восстановления блока.

## Dependencies

- Subtask 02.

## Risks

- Скрытая зависимость между пользовательскими полями профиля и inbound-обновлениями.
- Недостаточное покрытие edge-кейсов (частичные payload, fallback ids).

## Definition of Done

- [ ] Пройден `UPDATE_COMPANY` сценарий с подтвержденной корректной propagation семантикой.
- [ ] Пройден `UPDATE_CONTACT` сценарий без деградации ожидаемого поведения.
- [ ] Для обоих сценариев приложены trace/evidence артефакты.

## Verification checklist

- [ ] Проверка employee propagation: `ACTIVE`, `UF_ADVERSTERING_AGENT`, скидочные группы по правилам hotfix.
- [ ] Проверка `reason_code` и evidence в ответах/trace после фикса UI.
- [ ] Подготовлен audit-блок Team Lead с рисками релиза и рекомендацией по выкату.
