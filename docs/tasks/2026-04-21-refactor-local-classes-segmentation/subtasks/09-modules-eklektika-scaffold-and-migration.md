# ST-09: Каркас модулей eklektika.* и перенос пространств имён

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Унифицировать структуру Bitrix-модулей в **`local/modules/`** с префиксом **`eklektika.`**: код домена только в **`lib/`**, минимальный **`include.php`** с `\Bitrix\Main\Loader::registerAutoLoadClasses` (или эквивалент для версии ядра), постепенная смена namespace с `OnlineService\...` на `Eklektika\...` без ломки шаблонов (этапы с `class_alias` или параллельные обёртки). Модули вне префикса `eklektika.*` не затрагиваются (см. arch-rules).

**Вне scope текущей задачи:** полноценные установщики — каталог **`install/`**, **`install/index.php`**, установка через «Настройки → Модули»; на этом этапе достаточно наличия каталогов модуля и рабочей автозагрузки при подключении из [`init.php`](../../../local/php_interface/init.php).

## Описание работ

1. Свести к единому шаблону все модули из [MODULE-LAYOUT.md §2](../MODULE-LAYOUT.md#2-таблица-соответствия-сегмент--текущие-файлы--module_id--lib): каждый с **`include.php`**, файлами в **`lib/`**; без **`install/`** до отдельного согласования.
2. В **`include.php`** каждого модуля зарегистрировать автозагрузку через **`Loader::registerAutoLoadClasses`** в соответствии с фактическими путями под `lib/`.
3. Определить политику имён: либо сохранить `OnlineService\*` внутри модулей до второй волны, либо ввести `Eklektika\*` и алиасы — зафиксировать в `docs/features/`.
4. В [`local/php_interface/init.php`](../../../local/php_interface/init.php) заменить оставшиеся ручные `require` классов из `local/classes` на **`Loader::includeModule('eklektika....')`** там, где классы уже в модулях.

### Ближайшие шаги реализации (continue)

1. Составить check-list по каждому модулю `eklektika.*`: `include.php` есть, автозагрузка покрывает все классы из `lib/`, нет неиспользуемых регистраций.
2. Прогнать быстрый аудит потребителей (`director/`, `personal/`, `local/components/`, `local/templates/`) на предмет `require`/`use` старых путей из `local/classes/{b24,site}`.
3. Для несовместимых namespace зафиксировать временные `class_alias` только как переходное решение с пометкой на удаление в следующем цикле.
4. Синхронизировать итог с `MODULE-LAYOUT.md` (таблица + порядок миграции) и `docs/features/local_classes_segments_and_modules.md`.

### Continue-фокус (2026-04-22)

- Подзадача рассматривается как **инфраструктурная стабилизация** перед финалом рефакторинга: выровнять автозагрузку и убрать технический долг bootstrap.
- Приоритет — консистентность `include.php` и отсутствие дублирующего подключения migrated-классов, без расширения бизнес-объёма.

### Ближайшие шаги реализации (continue)

1. Составить чек-лист `module_id -> include.php -> классы lib/` по факту текущего дерева и отметить расхождения.
2. Исправить `include.php` в модулях с расхождениями (не менять бизнес-логику классов).
3. Пройти по `init.php` и `requires.php`, убрать legacy `require` migrated-классов.
4. Проверить потребителей (шаблоны/компоненты) на работоспособность без прямого `require` из `local/classes`.
5. Передать результат в ST-10 для фиксации final dependency-rules в документации.

### Конкретный чек-лист исполнения (факт на 2026-04-22, docs-only sync)

Traceability artifact: [`ST09-ST10-AUDIT-TRACEABILITY.md`](../ST09-ST10-AUDIT-TRACEABILITY.md).

- [x] Собран список модулей `eklektika.*`, реально подключаемых в `requires.php` (см. `README.md` задачи и `docs/features/local_classes_segments_and_modules.md`).
- [x] Для каждого модуля проверено соответствие `class -> file` в `include.php` (зафиксировано по результатам предыдущих шагов ST-03..ST-08).
- [x] Исправлены битые или устаревшие записи автозагрузки (остаточные несовпадения не зафиксированы в task-артефактах).
- [x] Удалены legacy `require` в `init.php`/`requires.php` для уже мигрированных классов (по текущему состоянию документации).
- [x] Зафиксированы переходные исключения в документации задач/фич (для dependency-исключений см. ST-10 и feature docs).

### Остаток до полного закрытия ST-09 (handoff через ST-11)

- [ ] Повторно подтвердить checklist после следующего code-touch цикла (если изменится bootstrap/autoload).
- [ ] Зафиксировать результат smoke-прогона в отдельном smoke-артефакте (текущий статус: Not run, шаблон и handoff в ST-11).

### Выходные артефакты ST-09 (обязательные)

1. Таблица `module_id -> include.php -> classes(lib)` с пометкой `OK/needs-fix`.
2. Список удалённых legacy `require` (с указанием файла, из которого удалено).
3. Список временно сохранённых переходных подключений (если есть) с owner и датой пересмотра.
4. Обновление `docs/features/local_classes_segments_and_modules.md` по факту автозагрузки.

## Технические детали

- Компоненты/модули:
  - существующие и новые: `local/modules/eklektika.*/`
  - не трогать: сторонние модули вне префикса `eklektika.*` (перечень запретов — в arch-rules)
- Изменяемые файлы/области:
  - `local/modules/eklektika.*/include.php`, `local/modules/eklektika.*/lib/**`
  - `local/php_interface/init.php`
  - постепенно — namespace у потребителей в шаблонах/компонентах

## Зависимости

- Блокируется:
  - ST-02–ST-08 (стабилизированные переносы в `lib/`)
- Блокирует:
  - нет

## Критерии приёмки

Master DoD: см. [README.md](../README.md) -> "Единые критерии завершения ST-09 + ST-10 (DoD для финального цикла)".

- [ ] Для каждого используемого домена есть модуль **`eklektika.*`** с кодом в **`lib/`** и рабочим **`include.php`** (автозагрузка без ошибок при типовых сценариях)
- [ ] **Не** добавлены установщики `install/` в рамках этой подзадачи без отдельного решения (или зафиксировано явное исключение заказчиком)
- [ ] В `docs/features/local_classes_segments_and_modules.md` перечислены имена модулей и соответствие доменам (см. [MODULE-LAYOUT](../MODULE-LAYOUT.md))
- [ ] В `init.php` и `requires.php` отсутствуют ручные подключения классов, уже перенесённых в `local/modules/eklektika.*/lib/`
- [ ] Выбранная политика namespace (`OnlineService` как baseline или миграция к `Eklektika`) явно зафиксирована в документации
- [ ] В `init.php`/`requires.php` нет подключений migrated-классов из `local/classes/*` (кроме явно сохранённого переходного остатка с пометкой в docs)

## Проверка

- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - После правок загрузки открыть сайт и выполнить smoke: usersync, company/manager, pricing, import, deal applications, page settings/search
  - Проверить отсутствие fatals на раннем bootstrap (главная, личный кабинет, каталог, карточка товара)

## Документация

- Изученные документы:
  - `docs/features/local_classes_segments_and_modules.md`
- Что обновить:
  - `docs/features/local_classes_segments_and_modules.md` — список модулей, автозагрузка, отсутствие установщиков на текущем этапе
  - `docs/features/README.md` — краткая ссылка
  - `docs/features/b24_integration.md` — подтвердить единый REST-транспорт и отсутствие legacy-подключений как целевого пути
- Что создать (если нужно):
  - нет

## Статус

- ready_for_review (docs synced in ST-11, waiting manual smoke evidence)
