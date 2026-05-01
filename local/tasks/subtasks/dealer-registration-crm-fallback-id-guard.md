# Initiative: убрать ложный duplicate при fallback-ID из CRM

## Goal

Не блокировать регистрацию, если `CRM:getContactID` вернул только `ID`, но фактическое совпадение введенных email/phone с контактом CRM не подтверждено.

## Tasks

| ID | Title | Status |
|----|--------|--------|
| T1 | ADR: `local/docs/adr/ADR-2026-04-30-dealer-registration-crm-fallback-id-guard.md` | done |
| T2 | Task tree: `local/docs/tasks/2026-04-30-dealer-registration-crm-fallback-id-guard/*` | done |
| T3 | Матрица исходов duplicate-check + guard верификации fallback-ID (plan) | done |
| T4 | Team Lead implementation + audit checklist | done |

## Final status

- Инициатива закрыта после реализации и rework.
- Guard fallback-ID реализован: при `CRM:getContactID` с ID-only выполняется `crm.contact.get` и сравнение нормализованных email/phone; без подтверждения совпадения регистрация не блокируется.
- Baseline без `debug=1` сохранен; в debug-режиме добавлен fallback context для диагностики.
- Технический маркер `*CRM:getContactID` убран из baseline-сообщения вне debug.

## Closure checks

1. `crm_response {"ID":"..."}` + mismatch email/phone -> регистрация разрешена (`unconfirmed_fallback_id`).
2. Подтвержденное совпадение email/phone -> блокировка `already_registered` (`confirmed_duplicate`).
3. Без `debug=1` baseline UX не меняется и не содержит техмаркеров.
4. При `debug=1` присутствует диагностический контекст с masking.
