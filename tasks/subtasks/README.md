# tasks/subtasks

Обязательная рабочая папка для сопровождения прогресса задач.

## Минимальные требования

- Для каждой инициативы создается отдельная папка с датой и short-name.
- Внутри обязательно фиксируются подзадачи, статус, DoD и риски.
- После аудита Team Lead техлид обновляет итоговый статус и ADR-связи.

## Рекомендуемая структура

`tasks/subtasks/<YYYY-MM-DD>-<initiative>/`

- `task.md` - общий контекст и цель.
- `subtasks/01-*.md`, `subtasks/02-*.md` - декомпозиция.
- `audit.md` - результаты аудита Team Lead.
- `handoff-techlead.md` - финальный хэнд-офф на обновление ADR/документации.

## Active initiatives

- `2026-04-30-update-contact-observability` - расследование кейса `UPDATE_CONTACT success=1`, но визуально stale профиль.
- `2026-05-01-personal-managers-block-regression` - P0 восстановление блока персональных менеджеров после hotfix ParseError без отката propagation-фиксов.
