# ST-02: Синхронизация bootstrap-цепочки в docs/features

## Связь с задачей
- Родительская задача: [TASK-2026-04-22-refactor-continue-st11-stabilization](../README.md)
- Внешние ссылки:
  - `docs/features/company_system.md`
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/features/b24_integration.md`
  - `local/classes/requires.php`

## Цель подзадачи
Устранить рассинхрон в описании bootstrap-порядка модулей между документацией и фактическим `requires.php`.

## Описание работ
1. Считать фактический порядок `Loader::includeModule` из `local/classes/requires.php` как единственный источник истины.
2. Синхронизировать формулировки в `docs/features/company_system.md` (замечание по текущему инкременту).
3. Проверить и выровнять related-описания в `docs/features/local_classes_segments_and_modules.md` и `docs/features/b24_integration.md`.
4. Проверить, что `docs/features/README.md` не противоречит обновлённому описанию модульной загрузки.

## Технические детали
- Компоненты/модули:
  - bootstrap `local/classes/requires.php`
  - docs features по company/b24/segment map
- Изменяемые файлы/области:
  - `docs/features/company_system.md`
  - `docs/features/local_classes_segments_and_modules.md` (если нужно)
  - `docs/features/b24_integration.md` (если нужно)
  - `docs/features/README.md` (если нужно)

## Зависимости
- Блокируется:
  - нет
- Блокирует:
  - ST-03

## Критерии приёмки
- [ ] В `company_system.md` bootstrap-цепочка совпадает с `requires.php`
- [ ] Нет противоречий между `company_system.md`, `local_classes_segments_and_modules.md`, `b24_integration.md`
- [ ] Не добавлены изменения вне документации и bootstrap-контекста

## Проверка
- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - открыть `requires.php`, сверить порядок модулей с каждым обновлённым документом

## Документация
- Изученные документы:
  - `docs/features/README.md`
  - `docs/features/company_system.md`
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/features/b24_integration.md`
- Что обновить:
  - перечисленные docs/features файлы по факту рассинхрона
- Что создать (если нужно):
  - нет

## Статус
- planned
