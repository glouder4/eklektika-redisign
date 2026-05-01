# Subtask 02: Restore manager data-source contract for rendering

- Parent task: [../task.md](../task.md)
- ADR: [../adr.md](../adr.md)
- Status: `todo`
- Priority: `P0`

## Scope

Внести минимальный фикс, который восстанавливает корректный резолв и рендер персональных менеджеров в `sale.personal.section`, не затрагивая несвязанные ветки.

## Inputs

- Root cause из Subtask 01.
- Текущая логика `result_modifier.php`, `template.php`, `parts/manager.php`.
- Ограничение: запрет на откат полезных изменений в `Company.php`.

## Outputs

- Кодовое исправление для восстановления отображения блока.
- Подтверждение корректного наполнения карточки менеджера.

## Dependencies

- Subtask 01.

## Risks

- Over-fix: слишком широкие изменения могут затронуть другие виджеты ЛК.
- Частичный фикс: блок появится, но данные менеджера останутся неполными.

## Definition of Done

- [ ] Блок менеджера отображается при прежних условиях.
- [ ] Отрисованы ключевые данные менеджера (имя + доступные контакты/fallback).
- [ ] Diff ограничен целевыми файлами manager block flow.

## Verification checklist

- [ ] Smoke для сценария с основным менеджером (`PROPERTY_MANAGER`).
- [ ] Smoke для сценария со вторым менеджером (`PROPERTY_MANAGER2`), если используется.
- [ ] Проверка, что при реально пустом manager source блок не показывается (старое ожидаемое поведение).
