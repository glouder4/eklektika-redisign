# ST-04: Follow-up по снятию временной зависимости usersync->company

## Связь с задачей
- Родительская задача: [TASK-2026-04-22-refactor-continue-st11-stabilization](../README.md)
- Внешние ссылки:
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md`

## Цель подзадачи
Подготовить безопасный implement-ready follow-up для поэтапного снятия временного исключения `eklektika.b24.usersync -> eklektika.company` через узкий контракт, без реализации в этом инкременте.

## Описание работ
1. Зафиксировать текущее исключение, owner и дедлайн снятия в едином формате.
2. Описать целевой контракт (`CompanyGateway`) и минимальный путь миграции без изменения бизнес-поведения.
3. Определить входные критерии для code-touch шага (какие smoke-сценарии обязательны до и после).
4. Подготовить короткий блок требований для implement-цикла teamlead/developer.

## Технические детали
- Компоненты/модули:
  - `eklektika.b24.usersync`
  - `eklektika.company`
  - архитектурная матрица зависимостей
- Изменяемые файлы/области:
  - `docs/features/local_classes_segments_and_modules.md` (если требуется уточнение)
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md` (если требуется уточнение follow-up)
  - текущий `README.md` задачи ST-11

## Зависимости
- Блокируется:
  - ST-03
- Блокирует:
  - следующий implement-цикл code-touch (отдельная задача)

## Критерии приёмки
- [ ] Описан конкретный контракт и рамка миграции `usersync -> CompanyGateway`
- [ ] Зафиксированы owner, дедлайн и условия снятия исключения
- [ ] Подготовлен implement-ready блок требований без расширения текущего scope

## Проверка
- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - ревью плана teamlead/developer: требования однозначны, порядок шагов воспроизводим

## Документация
- Изученные документы:
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md`
- Что обновить:
  - архитектурные пометки по исключению и follow-up
- Что создать (если нужно):
  - нет

## Статус
- planned
