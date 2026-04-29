# ST-08: Рефакторинг bootstrap init.php и requires.php

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Свести [`local/php_interface/init.php`](../../../local/php_interface/init.php) и [`local/classes/requires.php`](../../../local/classes/requires.php) к **тонким загрузчикам**: инфраструктура сайта (заголовки, cookie, тайминги) и последовательное подключение модулей **`eklektika.*`** через `Loader::includeModule` без доменной логики в одном файле. Ориентир порядка: [MODULE-LAYOUT.md §5](../MODULE-LAYOUT.md#5-миграция-и-загрузка-namespace-includephp-loaderregisterautoloadclasses).

## Описание работ

1. Вынести класс `PageSettings` и функции-хелперы `getPageEditorSettings` / `getPageSettingValue` из `init.php` в модуль **`eklektika.site`** → **`local/modules/eklektika.site/lib/`** с сохранением API (уточнение имени модуля — в [MODULE-LAYOUT §2](../MODULE-LAYOUT.md#2-таблица-соответствия-сегмент--текущие-файлы--module_id--lib)).
2. Перенести регистрацию поискового обработчика из [`requires.php`](../../../local/classes/requires.php) в явный bootstrap или оставить отдельным техническим подключением до решения по модулю `eklektika.search` ([MODULE-LAYOUT](../../MODULE-LAYOUT.md)).
3. После выполнения ST-03–ST-07 удалить из `init.php` перенесённые функции; оставить только: подключение модулей, вызовы bootstrap из `lib/`, инфраструктурные обработчики (`OnEpilog`, Server-Timing).
4. Документировать порядок подключения модулей (чтобы события регистрировались в нужном порядке).

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php)
  - [`local/classes/requires.php`](../../../local/classes/requires.php)
  - класс `PageSettings` сейчас inline в `init.php`
  - [`local/php_interface/classes/handlers/search/stemming.php`](../../../local/php_interface/classes/handlers/search/stemming.php) (путь из requires)
- Изменяемые файлы/области:
  - `local/php_interface/init.php`
  - `local/classes/requires.php`
  - `local/modules/eklektika.site/lib/` (или согласованный модуль для контента)
  - при необходимости новый модуль поиска

## Зависимости

- Блокируется:
  - ST-03–ST-07 (чтобы не переносить дважды)
- Блокирует:
  - финальную вычистку автозагрузки в ST-09

## Критерии приёмки

- [x] `init.php` не содержит REST-функций и тяжёлой доменной логики перенесённых модулей (**`sendRequestB24`**, **`sendRequest`**, **`findContact`**, **`newRest`** → **`eklektika.b24.rest/lib/LegacyGlobalB24.php`**)
- [x] **`PageSettings`**, **`getPageEditorSettings`**, **`getPageSettingValue`** → модуль **`eklektika.site`**, API глобальных функций без изменений
- [x] Поисковый **`BeforeIndex`** — регистрация через **`SearchIndexingBootstrap`** в **`eklektika.site/include.php`** (цепочка **`requires.php`** сохраняет порядок загрузки **`site`** после **`catalog.pricing`**)

## Проверка

- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - Открытие страницы с настройками из ИБ; полнотекстовый поиск smoke; регистрация пользователя (интеграция с модулями)

## Документация

- Изученные документы:
  - `docs/features/local_classes_segments_and_modules.md`
- Что обновить:
  - раздел «Стратегия init.php» с фактическим порядком `Loader::includeModule`
- Что создать (если нужно):
  - нет

## Статус

- **done** (2026-04-21): модуль **`eklektika.site`**, **`LegacyGlobalB24`** в **`b24.rest`**, упрощены **`init.php`** и **`requires.php`**, обновлена документация сегментов.
