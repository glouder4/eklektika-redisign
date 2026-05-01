# Initiative: UPDATE_CONTACT observability — success=1 but no visual update

## Business goal

Снизить время диагностики кейса, когда inbound `UPDATE_CONTACT` возвращает `success=1` (`reason_code=update_contact_ok`), но пользователь визуально не видит обновления в интерфейсе.

## Context and constraints

- Логи подтверждают успешный исход inbound (`inbound.outcome`) и наличие `B24_ID` + legacy `ID`.
- В коде уже добавлен meta-контекст для `update-contact`: `lookup_b24_id`, `legacy_id`, `resolved_user_id`, `updated`.
- `InboundGateway` уже пишет `meta` в `UPDATE_CONTACT` (`ok`/`failed`) trace и в `data` ответа.
- Graphify preflight (scope `local/`) подтверждает ключевые узлы потока: `InboundGateway` -> `SyncTrace` -> `User::updateContact` и downstream-зависимости в `Company`/группах.

## Subtasks

- [01-trace-contract-and-meta.md](subtasks/01-trace-contract-and-meta.md)
- [02-visual-staleness-diagnostic-path.md](subtasks/02-visual-staleness-diagnostic-path.md)

## Definition of Done (initiative)

- [ ] Для `UPDATE_CONTACT` зафиксирован единый диагностический контракт: какие поля в `trace/data` обязательны в `ok` и `failed`.
- [ ] Для Team Lead определен воспроизводимый path-поиск причины "success=1, визуально stale".
- [ ] Есть ADR-запись с решением и границами интерпретации `update_contact_ok`.
- [ ] Все ссылки между `task`, `subtasks`, `adr` двусторонние.

## Status and progress

- Status: `in_progress`
- Progress: `65%` (кодовый observability-контекст уже внедрен, нужна фиксация в task-пакете и запуск follow-up проверки)
- Blockers: нет hard-blocker, есть риск несинхронности чтения данных на UI-слое.

## ADR link

- [adr.md](adr.md)

## Next steps for Team Lead

- [ ] Прогнать 3 сценария на стенде: `updated=true`, `updated=false`, `failed` и сверить `meta`/`reason_code` с фактическим состоянием профиля.
- [ ] Для каждого кейса зафиксировать `trace_id`, `B24_ID`, `legacy_id`, `resolved_user_id`, `updated` и источник UI-данных (какой компонент/endpoint читает профиль).
- [ ] Проверить отложенные/конкурентные перезаписи после `UPDATE_CONTACT`: события профиля, group mapping, company sync.
- [ ] Отдельно подтвердить отсутствие кэша/реплики, из-за которых UI читает состояние до коммита обновления.
- [ ] Передать tech lead короткий audit: где именно расходятся "inbound success" и "визуальное состояние", с классификацией причины (данные, кэш, read-model, race).
