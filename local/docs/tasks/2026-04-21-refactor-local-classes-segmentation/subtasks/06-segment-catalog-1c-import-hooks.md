# ST-06: Постобработка каталога после импорта 1С

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Изолировать логику обработчика `OnSuccessCatalogImport1C` (`updateProperties`), константу идентификатора ИБ **`IBLOCK_ID_1C`** и вспомогательную **`actionSection`** (используется только во **временно закомментированном** блоке `updateSections`) из [`local/php_interface/init.php`](../../../local/php_interface/init.php) в модуле **`eklektika.catalog.import`** под **`local/modules/eklektika.catalog.import/lib/`**, без смешения с CRM и ценообразованием ([MODULE-LAYOUT.md](../MODULE-LAYOUT.md)).

## Описание работ

1. Создать **`local/modules/eklektika.catalog.import/`** с **`lib/`** и минимальным **`include.php`**: **`Loader::registerAutoLoadClasses`** для классов в namespace **`OnlineService\Catalog\Import1c\*`** (или эквивалентная раскладка файлов под `lib/`, согласованная с ST-09).
2. Вынести в класс(ы):
   - постобработку после импорта (бывшая **`updateProperties`**) — публичный метод-обработчик события;
   - **`IBLOCK_ID_1C`** — как **`const`/конфиг модуля** внутри класса или отдельного малого класса констант (глобальный **`define`** в **`init.php`** не оставлять);
   - **`actionSection`** — статический метод или сервис секций для возможного включения закомментированного `updateSections` без дублирования.
3. Зарегистрировать **`AddEventHandler('catalog', 'OnSuccessCatalogImport1C', …)`** при загрузке модуля (**в `include.php`** или вызываемом bootstrap-классе **`…::register()`**), чтобы обработчик не зависел от порядка строк в **`init.php`**.
4. Подключить модуль через **`Loader::includeModule('eklektika.catalog.import')`** в [`local/classes/requires.php`](../../../local/classes/requires.php) **в конце цепочки после остальных **`eklektika.*`** до **`usersync`**: **`rest` → `company` → `catalog.pricing` → `catalog.import` → `usersync`** (import не зависит от пользовательского синка; порядок относительно **`catalog.pricing`** зафиксирован для предсказуемости bootstrap).
5. Из [`local/php_interface/init.php`](../../../local/php_interface/init.php) **полностью удалить**: **`define('IBLOCK_ID_1C', …)`**, **`AddEventHandler`**, функции **`updateProperties`** и **`actionSection`** (закомментированный блок `updateSections` при необходимости перенести в модуль как комментарий или TODO со ссылкой на класс с **`actionSection`**).

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — удаляемые блоки: `IBLOCK_ID_1C`, `updateProperties`, `AddEventHandler("catalog", "OnSuccessCatalogImport1C", ...)`, `actionSection`.
  - [`local/classes/requires.php`](../../../local/classes/requires.php) — точка включения **`eklektika.catalog.import`**.
- Изменяемые файлы/области:
  - **`local/modules/eklektika.catalog.import/`** — `include.php`, `lib/` (классы **`OnlineService\Catalog\Import1c\*`**).
  - **`local/php_interface/init.php`** — только удаление перечисленного (без замены на **`includeModule`** для этого модуля).
- Примечание: в перенесённом **`PostImportHandler::actionSection`** для **`CIBlockSection::Add`** используется **`IBLOCK_ID` = `self::IBLOCK_ID_1C`** (в старом **`init.php`** было **`IBLOCK_ID`** без определения — исправление для согласованности с фильтром выше по тому же методу).

## Зависимости

- Блокируется:
  - нет жёстко; финальная зачистка **`init.php`** по другим блокам — в ST-08 ([MODULE-LAYOUT §5](../MODULE-LAYOUT.md#5-миграция-и-загрузка-namespace-includephp-loaderregisterautoloadclasses))
- Блокирует:
  - финальную зачистку блока импорта в ST-08

## Критерии приёмки

- [x] Логика постобработки и **`actionSection`** размещены в **`local/modules/eklektika.catalog.import/lib/`** с namespace **`OnlineService\Catalog\Import1c\*`** (`PostImportHandler`, `Import1cBootstrap`).
- [x] В **`include.php`** модуля: **`Loader::registerAutoLoadClasses`** и регистрация **`AddEventHandler('catalog', 'OnSuccessCatalogImport1C', …)`** при загрузке модуля (callable на **`PostImportHandler::onSuccessCatalogImport`**).
- [x] В [`requires.php`](../../../local/classes/requires.php) добавлен **`Loader::includeModule('eklektika.catalog.import')`** после **`eklektika.catalog.pricing`** и до **`eklektika.b24.usersync`**; в [`init.php`](../../../local/php_interface/init.php) **нет** **`IBLOCK_ID_1C`**, **`updateProperties`**, **`actionSection`**, **`AddEventHandler`** для этого события.
- [ ] После обмена с 1С свойство **`APPLICATION_TYPES`** для элементов целевого ИБ заполняется **идентично** прежнему маппингу (выборочная проверка по элементам с ненулевыми свойствами нанесения) — **ручная проверка**.
- [x] В проекте нет «висящих» ссылок на удалённый глобальный **`define('IBLOCK_ID_1C')`** и функцию **`updateProperties`** в PHP-коде (константа — **`PostImportHandler::IBLOCK_ID_1C`**).

## Проверка

- Unit/интеграционные проверки:
  - нет / скрипт выборочной проверки ИБ при наличии
- Ручной сценарий:
  - Запуск обмена на тестовой копии или имитация вызова обработчика на тестовых данных

## Документация

- Изученные документы:
  - [`docs/features/local_classes_segments_and_modules.md`](../../../features/local_classes_segments_and_modules.md)
  - [`MODULE-LAYOUT.md`](../MODULE-LAYOUT.md)
- Что обновить (обязательно при закрытии ST-06):
  - [`docs/features/local_classes_segments_and_modules.md`](../../../features/local_classes_segments_and_modules.md) — сегмент «постобработка 1С», модуль **`eklektika.catalog.import`**, цепочка **`requires.php`**, отсутствие логики в **`init.php`**
  - [`MODULE-LAYOUT.md`](../MODULE-LAYOUT.md) — строка таблицы §2, пункт §5 **3b** (bootstrap ST-06)
  - [`README.md`](../README.md) задачи — завершение ST-06, таблица явных действий исполнителя
  - [`docs/features/README.md`](../../../features/README.md) — краткая строка в блоке архитектуры (опционально)
- Что создать (если нужно):
  - `docs/features/catalog_1c_import_hooks.md` — только если решат вынести описание свойств **`APPLICATION_TYPES`** и списка полей печати из подзадачи

## Статус

- done (ожидает ручной проверки после обмена с 1С)
