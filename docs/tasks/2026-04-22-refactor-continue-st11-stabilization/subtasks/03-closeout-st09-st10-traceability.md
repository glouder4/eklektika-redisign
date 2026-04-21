# ST-03: Закрытие ST-09/ST-10 в docs/tasks и traceability

## Связь с задачей
- Родительская задача: [TASK-2026-04-22-refactor-continue-st11-stabilization](../README.md)
- Внешние ссылки:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/09-modules-eklektika-scaffold-and-migration.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/ST09-ST10-AUDIT-TRACEABILITY.md`

## Цель подзадачи
Перевести ST-09/ST-10 из статуса документационного ожидания в операционно закрытую стадию после smoke и doc-sync.

## Описание работ
1. Обновить статус ST-09/ST-10 в родительской задаче `2026-04-21-.../README.md` по факту smoke и doc-sync.
2. Синхронизировать подзадачи `09` и `10` (статусы, критерии приёмки, ссылки на артефакты).
3. Обновить `ST09-ST10-AUDIT-TRACEABILITY.md`, чтобы он отражал финальное состояние.
4. Проверить, что все ссылки между task-артефактами целостны и не ведут на устаревшие статусы.

## Технические детали
- Компоненты/модули:
  - docs/tasks track ST-09/ST-10
- Изменяемые файлы/области:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/09-modules-eklektika-scaffold-and-migration.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/ST09-ST10-AUDIT-TRACEABILITY.md`

## Зависимости
- Блокируется:
  - ST-01
  - ST-02
- Блокирует:
  - ST-04

## Критерии приёмки
- [ ] ST-09 и ST-10 имеют согласованный финальный статус в `README.md` и подзадачах
- [ ] `ST09-ST10-AUDIT-TRACEABILITY.md` синхронизирован с фактическим состоянием
- [ ] Все артефакты ST-09/ST-10 содержат согласованные ссылки на smoke/doc evidence

## Проверка
- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - пройти все ссылки из README ST-09/ST-10 и убедиться, что статусы/чек-листы совпадают

## Документация
- Изученные документы:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/ST09-ST10-AUDIT-TRACEABILITY.md`
- Что обновить:
  - task-артефакты ST-09/ST-10
- Что создать (если нужно):
  - нет

## Статус
- planned
