# ADR: semantics of UPDATE_CONTACT success vs visual freshness

- Initiative: [task.md](task.md)
- Status: accepted
- Date: 2026-04-30

## Decision

Считать `inbound.outcome success=1 reason_code=update_contact_ok` индикатором успешного прохождения update-ветки на write-side, но не финальным доказательством визуальной актуальности read-side.

## Why

- `success=1` подтверждает отсутствие критической ошибки в inbound pipeline.
- Для пользовательской "визуальной актуальности" нужны дополнительные факты: целевой `resolved_user_id`, флаг `updated`, источник чтения UI и отсутствие post-update перезаписей/кэша.

## Observability contract

Обязательные поля диагностики для `UPDATE_CONTACT`:

- `reason_code`
- `meta.lookup_b24_id`
- `meta.legacy_id`
- `meta.resolved_user_id`
- `meta.updated`
- `trace_id`

## Consequences

- Team Lead в инцидентах класса "success=1, но stale UI" должен проверять write/read split и асинхронные перезаписи, а не только inbound `ok`.
- Для postmortem достаточно одного набора артефактов (`trace_id` + meta-поля + UI source), чтобы локализовать причину.

## Links

- Main task: [task.md](task.md)
- Subtask 1: [subtasks/01-trace-contract-and-meta.md](subtasks/01-trace-contract-and-meta.md)
- Subtask 2: [subtasks/02-visual-staleness-diagnostic-path.md](subtasks/02-visual-staleness-diagnostic-path.md)
