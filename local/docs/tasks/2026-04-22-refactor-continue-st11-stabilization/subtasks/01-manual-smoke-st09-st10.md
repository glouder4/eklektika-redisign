# ST-01: Ручной smoke ST-09/ST-10 и фиксация статусов

## Связь с задачей
- Родительская задача: [TASK-2026-04-22-refactor-continue-st11-stabilization](../README.md)
- Внешние ссылки:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/SMOKE-REPORT-ST09-ST10.md`

## Цель подзадачи
Подтвердить, что принятые изменения ST-09/ST-10 не привели к регрессиям в ключевых пользовательских и интеграционных сценариях.

## Описание работ
1. Взять текущий smoke-чеклист из `SMOKE-REPORT-ST09-ST10.md` и выполнить все сценарии вручную.
2. Для каждого сценария проставить `pass` или `fail` и добавить краткую заметку (окружение/артефакт).
3. При `fail` зафиксировать минимальные шаги воспроизведения и зону влияния.
4. Свести общий статус smoke и передать вход для ST-03 (закрытие ST-09/ST-10).

## Технические детали
- Компоненты/модули:
  - `eklektika.b24.usersync`
  - `eklektika.company`
  - `eklektika.catalog.pricing`
  - `eklektika.catalog.import`
  - `eklektika.orders.applications`
  - `eklektika.site`
- Изменяемые файлы/области:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/SMOKE-REPORT-ST09-ST10.md`

## Зависимости
- Блокируется:
  - нет
- Блокирует:
  - ST-03

## Критерии приёмки
- [ ] Все сценарии smoke имеют статус `pass` или `fail` (без `Not run`)
- [ ] Для каждого `fail` указан краткий repro и затронутая зона
- [ ] Общий статус smoke отражён в файле отчёта

## Проверка
- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - usersync: регистрация/обновление пользователя и синхронизация с CRM
  - company/manager/holding: операции компаний и менеджеров
  - pricing: применение контрактной скидки компании
  - import: постобработка после импорта 1С
  - deal applications: заявки из сделки в заказ
  - site: page settings и search bootstrap

## Документация
- Изученные документы:
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/SMOKE-REPORT-ST09-ST10.md`
- Что обновить:
  - `docs/tasks/2026-04-21-refactor-local-classes-segmentation/SMOKE-REPORT-ST09-ST10.md`
- Что создать (если нужно):
  - нет

## Статус
- planned
