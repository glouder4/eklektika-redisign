# Subtask 02: Diagnostic path for visual staleness

- Parent task: [../task.md](../task.md)
- ADR: [../adr.md](../adr.md)
- Status: `todo`

## Scope

Построить воспроизводимый путь проверки, почему при `update_contact_ok` пользователь не видит изменения в UI.

## Inputs

- `trace_id` и meta-поля из inbound trace.
- Наблюдаемый "stale UI" кейс на стенде/проде.
- Понимание, какой endpoint/компонент читает профиль для визуализации.

## Outputs

- Матрица причин расхождения (write/read split, cache, async overwrite, race).
- Протокол проверки с четкой последовательностью шагов.

## Dependencies

- Subtask 01 (контракт полей и единый triage-чеклист).

## Risks

- Если UI читает агрегированный read-model с лагом, без проверки времени коммита можно ошибочно обвинить inbound.
- Конкурентные sync-потоки могут перезаписать поле после успешного `UPDATE_CONTACT`.

## Definition of Done

- [ ] Есть минимум 3 воспроизводимых сценария (`updated=true`, `updated=false`, `failed`) с одинаковым шаблоном артефактов.
- [ ] Для каждого сценария зафиксирован источник UI-данных и время чтения относительно inbound.
- [ ] Причины расхождений классифицированы и отданы Team Lead в audit-формате.

## Verification checklist

- [ ] Проверен кеш приложения/прокси (TTL, invalidation, bypass).
- [ ] Проверены post-update обработчики, которые могут изменять те же поля.
- [ ] Подготовлена рекомендация: что мониторить постоянно, а что включать только в debug-режиме.
