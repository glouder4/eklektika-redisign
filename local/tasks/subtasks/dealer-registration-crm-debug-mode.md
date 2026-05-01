# Initiative: debug-режим CRM ошибок при регистрации дилера

## Goal

Добавить диагностический режим для страницы регистрации дилера: при `debug=1` и CRM-ошибке показать подробный CRM ответ, сохранив текущий UX в обычном режиме.

## Tasks

| ID | Title | Status |
|----|--------|--------|
| T1 | ADR: `local/docs/adr/ADR-2026-04-30-dealer-registration-crm-debug-mode.md` | done |
| T2 | Task tree: `local/docs/tasks/2026-04-30-dealer-registration-crm-debug-mode/*` | done |
| T3 | Декомпозиция с учетом sequential-thinking рисков/валидации | done |
| T4 | Реализация + rework по security masking (value-based + fallback raw/trace/request) | done |
| T5 | Closure report: `local/docs/tasks/2026-04-30-dealer-registration-crm-debug-mode/closure-report.md` | done |

## Final status

- Инициатива закрыта после реализации и post-audit rework.
- Подзадачи ST-01..ST-03 отмечены как `done`; общие DoD критерии закрыты.
- Baseline-поведение без `debug` сохранено; debug-блок доступен только для `debug=1` + `CRM:*`.
