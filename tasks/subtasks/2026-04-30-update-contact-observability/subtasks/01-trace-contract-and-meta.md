# Subtask 01: Trace contract and meta completeness for UPDATE_CONTACT

- Parent task: [../task.md](../task.md)
- ADR: [../adr.md](../adr.md)
- Status: `in_progress`

## Scope

Зафиксировать обязательный набор полей observability для `UPDATE_CONTACT` и правила чтения `ok/failed` trace при расследовании.

## Inputs

- Логи с `inbound.outcome` (`success=1`, `reason_code=update_contact_ok`).
- Текущая реализация meta-контекста (`lookup_b24_id`, `legacy_id`, `resolved_user_id`, `updated`).

## Outputs

- Согласованный контракт полей в логе/ответе.
- Проверочный чеклист для Team Lead на этапе triage.

## Risks

- Неполный набор полей в реальном логе (например, из-за edge-кейса payload) даст ложный вывод "обновление не дошло".

## Definition of Done

- [ ] Поля `reason_code` + `meta.*` перечислены как обязательные.
- [ ] Для `ok` и `failed` зафиксированы разные ожидания по `updated` и `resolved_user_id`.
- [ ] Чеклист применим без чтения исходного кода.

## Verification checklist

- [ ] На одном `ok`-кейсе подтверждено наличие всех полей.
- [ ] На одном `failed`-кейсе подтверждено наличие всех полей.
- [ ] Для обоих кейсов сохранен `trace_id` для связи с audit.
