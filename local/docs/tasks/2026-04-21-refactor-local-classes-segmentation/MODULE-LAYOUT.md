# Целевая раскладка: `local/modules/<module_id>/lib/`

Документ — **основной** артефакт целевого размещения кода после уточнения заказчика (2026-04-21): доменная механика и транспорт REST живут в **Bitrix-модулях** с префиксом `eklektika.*`; внутри модуля код размещается в **`lib/`** (штатная практика ядра Битрикс).

Связанные артефакты: [README.md](./README.md), [TARGET-STRUCTURE.md](./TARGET-STRUCTURE.md) (legacy: прежний план `core/` + `segment/` под `local/classes/`), [ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md).

---

## 1. Принципы

| Зона | Что допускается | Что запрещено |
|------|-----------------|---------------|
| **`local/modules/eklektika.<имя>/lib/`** | Классы домена и сервисов модуля, bootstrap сегмента (`ServiceProvider`, `…::register()`), узкие DTO под этот модуль. | Домен другого модуля без **документированного** контракта ([ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md)). |
| **Транспорт CRM (REST)** | Только в **`eklektika.b24.rest`**: клиент запросов, обёртка совместимости с конфигом [`local/php_interface/b24_integration_config.php`](../../../local/php_interface/b24_integration_config.php). | Бизнес-правила контактов, компаний, заказов внутри REST-клиента. |
| **`local/php_interface/`** | Константы окружения, тонкий `init.php`, подключение модулей (`CModule::IncludeModule` / `Loader::includeModule`), **без** разрастания бизнес-логики. | Длинные функции домена (перенос в `lib/` модулей). |
| **`local/classes/`** | **Не целевая** организационная корневая зона — см. [§4](#4-отношение-к-localclasses-переходный-период). | Новый постоянный «дом» для механики после завершения миграции. |

### Установщики модулей

Каталоги **`install/`**, **`install/index.php`**, полноценная установка через админку — **вне scope** текущей задачи, пока не утвержден отдельный этап. Допускается только **минимальный** `include.php` у модуля (автозагрузка, регистрация namespace) — см. [ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md).

### Однозначное правило для исполнителя

**Новый код** собственной разработки размещать в **`local/modules/eklektika.<domain>/lib/`** в модуле из таблицы соответствия ([§2](#2-таблица-соответствия-сегмент--текущие-файлы--module_id--lib)), а не под произвольными путями в `local/classes/`.

---

## 2. Таблица соответствия: сегмент / текущие файлы → `module_id` → пути под `lib/`

Имена классов и точные подпапки под `lib/` (например `lib/User/`, плоско в `lib/`) определяются исполнителем с сохранением PSR-подобной структуры и автозагрузки Битрикс.

| Сегмент / домен | Текущие файлы (ориентиры) | `module_id` | Пути под `lib/` (пример) |
|-----------------|---------------------------|-------------|---------------------------|
| HTTP-слой и конфиг вызовов B24 REST | [`local/modules/eklektika.b24.rest/lib/RestClient.php`](../../../local/modules/eklektika.b24.rest/lib/RestClient.php), [`lib/Request.php`](../../../local/modules/eklektika.b24.rest/lib/Request.php); конфиг [`local/php_interface/b24_integration_config.php`](../../../local/php_interface/b24_integration_config.php); подключение [`include.php`](../../../local/modules/eklektika.b24.rest/include.php) | `eklektika.b24.rest` | `lib/RestClient.php`, `lib/Request.php` (или `lib/Http/` при росте) |
| Пользователь ↔ CRM (синхронизация контакта) | [`local/modules/eklektika.b24.usersync/lib/`](../../../local/modules/eklektika.b24.usersync/lib/) (`RegisterUserCompany.php`, `User.php`, `UserSyncBootstrap.php`, `ContactAjaxFacade.php`); AJAX-экшены контакта → `ContactAjaxFacade`; события регистрируются из [`include.php`](../../../local/modules/eklektika.b24.usersync/include.php) модуля | `eklektika.b24.usersync` | как в таблице |
| Компании, холдинги, менеджеры | [`local/modules/eklektika.company/lib/`](../../../local/modules/eklektika.company/lib/) (`Company.php`, `Manager.php`, `UserGroups.php`); часть [`local/classes/ajax.php`](../../../local/classes/ajax.php) | `eklektika.company` | `lib/Company.php`, `lib/Manager.php`, `lib/UserGroups.php`; минимальный **`include.php`** с **`Loader::registerAutoLoadClasses`** для классов в **`OnlineService\Site\`** **без смены namespace до [ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md)**. Контракт **ST-05 → ST-04:** **`Company::getMaxCompanyDiscountPercentForUserGroups(array): float`** (уже реализован в `Company`) — узкая зависимость pricing от company, без импорта всего домена. |
| Ценообразование, пол цены, скидка компании | [`local/modules/eklektika.catalog.pricing/lib/CatalogPriceFloor.php`](../../../local/modules/eklektika.catalog.pricing/lib/CatalogPriceFloor.php); узкий контракт **`Company::getMaxCompanyDiscountPercentForUserGroups`** из **`eklektika.company`** | `eklektika.catalog.pricing` | `lib/CatalogPriceFloor.php`; минимальный **`include.php`** с **`Loader::registerAutoLoadClasses`** для **`OnlineService\Site\CatalogPriceFloor`** (**namespace до ST-09** без смены) |
| Постобработка каталога после импорта 1С | [`local/modules/eklektika.catalog.import/lib/`](../../../local/modules/eklektika.catalog.import/lib/) (`PostImportHandler`, `Import1cBootstrap`); событие **`catalog` / `OnSuccessCatalogImport1C`** регистрируется из [`include.php`](../../../local/modules/eklektika.catalog.import/include.php); логика импорта удалена из [`local/php_interface/init.php`](../../../local/php_interface/init.php) | `eklektika.catalog.import` | как в дереве §3; подключение из [`requires.php`](../../../local/classes/requires.php): **`catalog.pricing` → `catalog.import` → `usersync`** (**§5 п. 3b**) |
| Заявки из сделки B24 → корзина | тонкие **`getApplication`**, **`addApplication`** в [`local/php_interface/init.php`](../../../local/php_interface/init.php) → **[`DealApplicationsService`](../../../local/modules/eklektika.orders.applications/lib/DealApplicationsService.php)**; GET kit-методов — **`RestClient::callKitRestGet`** | `eklektika.orders.applications` | `lib/DealApplicationsService.php` |
| Настройки страницы (Page editor), поиск BeforeIndex | [`eklektika.site/lib/`](../../../local/modules/eklektika.site/lib/) (**`PageSettings`**, **`PageEditorGlobalFunctions`**, **`SearchIndexingBootstrap`**) | `eklektika.site` | `lib/PageSettings.php`, `lib/SearchIndexingBootstrap.php` |
| Поиск (stemming) | [`local/classes/requires.php`](../../../local/classes/requires.php) → обработчик | **`TBD`** в рамках ST-08 | либо модуль `eklektika.search` с `lib/...`, либо временно оставить путь в `php_interface/classes/...` до решения |

**Зависимости между модулями** — по правилам [ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md): прямых связей между доменными модулями нет; транспорт — через `eklektika.b24.rest`; узкий контракт pricing ← company — как исключение.

---

## 3. Пример целевого дерева (фрагмент)

Без установщиков; только то, что нужно для автозагрузки и включения из `init.php`:

```text
local/modules/
├── eklektika.b24.rest/
│   ├── include.php              # минимум: Loader::registerAutoLoadClasses / зависимости
│   └── lib/
│       ├── RestClient.php
│       └── Request.php
├── eklektika.b24.usersync/
│   ├── include.php
│   └── lib/
│       ├── RegisterUserCompany.php
│       └── ...
├── eklektika.company/
│   ├── include.php              # Loader::registerAutoLoadClasses: Company, Manager, UserGroups (namespace OnlineService\Site — до ST-09)
│   └── lib/
│       ├── Company.php
│       ├── Manager.php
│       ├── UserGroups.php
│       └── ...
├── eklektika.catalog.pricing/
│   ├── include.php
│   └── lib/
│       └── CatalogPriceFloor.php
├── eklektika.catalog.import/
│   ├── include.php              # registerAutoLoadClasses + Import1cBootstrap события catalog / OnSuccessCatalogImport1C
│   └── lib/
│       ├── PostImportHandler.php
│       └── Import1cBootstrap.php
└── ...
```

Точные имена классов и namespaces (`OnlineService\*` vs `Eklektika\*`) — политика в [ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md).

---

## 4. Отношение к `local/classes` (переходный период)

- **Как постоянная организационная корневая зона (`local/classes/core`, `local/classes/segment`, «сегментация через папки под classes») — отвергается** в пользу модулей и `lib/`.
- **Допустимо временно:** файлы в `local/classes/` (в т.ч. [`requires.php`](../../../local/classes/requires.php), [`b24/*.php`](../../../local/classes/b24/) после ST-02)) — пока не перенесены в `local/modules/**/lib/` и не подключены через `include.php`; тонкий bootstrap в `init.php` подключает модули и старые require по мере миграции.

Исторический документ с деревом `core/` + `segment/` сохранён как [**TARGET-STRUCTURE.md**](./TARGET-STRUCTURE.md) для сопоставления с прежним планом и ревью; актуальная целевая схема — **этот файл**.

---

## 5. Миграция и загрузка (namespace, `include.php`, `Loader::registerAutoLoadClasses`)

1. Для каждого `eklektika.*` завести **минимальный** `include.php`: регистрация классов через `\Bitrix\Main\Loader::registerAutoLoadClasses` (или эквивалент по версии ядра) в соответствии с файлами в `lib/`.
2. В [`local/php_interface/init.php`](../../../local/php_interface/init.php) последовательно заменить ручные `require` классов на `Loader::includeModule('eklektika....')` там, где классы уже лежат в модуле.
3. **Bootstrap ST-04:** в [`local/classes/requires.php`](../../../local/classes/requires.php) (и при необходимости согласованно в `init.php`) порядок **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.b24.usersync`**; после подключения **`eklektika.company`** убрать три **`require_once`** на **`UserGroups.php`**, **`Company.php`**, **`Manager.php`**; **`CatalogPriceFloor.php`** и связанный вызов **`markCompositeNonCacheableForAuthorizedCatalog`** — **до ST-05** оставить ручной **`require`**; **после ST-05** см. пункт 3a.

3a. **Bootstrap ST-05:** в **`requires.php`** порядок **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.b24.usersync`**; убрать **`require_once`** старого **`CatalogPriceFloor.php`**; сохранить вызов **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`** сразу после успешной загрузки **`eklektika.catalog.pricing`**. В [`local/php_interface/init.php`](../../../local/php_interface/init.php) сохранить **`CatalogPriceFloor::bootstrap()`** после подключения **`requires.php`** (относительный порядок ранних эффектов и **`bootstrap()`** не должен регрессировать). Зависимость **`catalog.pricing`** от **`company`**: только **`Company::getMaxCompanyDiscountPercentForUserGroups`** ([ST-05](./subtasks/05-segment-catalog-pricing-discount.md)).

3b. **Bootstrap ST-06:** в **`requires.php`** порядок **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.catalog.import` → `eklektika.b24.usersync`**; затем без изменений смысла — **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`**. Модуль **`eklektika.catalog.import`** сам регистрирует обработчик **`catalog` / `OnSuccessCatalogImport1C`** в своём **`include.php`**. Из [`local/php_interface/init.php`](../../../local/php_interface/init.php) удалить логику импорта 1С (**`IBLOCK_ID_1C`**, **`updateProperties`**, **`actionSection`**, **`AddEventHandler`**), не добавляя туда **`includeModule`** для этого модуля ([ST-06](./subtasks/06-segment-catalog-1c-import-hooks.md)).

3c. **Bootstrap ST-07:** в **`requires.php`** после внедрения модуля **`eklektika.orders.applications`**: порядок **`Loader::includeModule`**: **`eklektika.b24.rest` → … → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`**; затем как раньше — **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`**. Модуль **`orders.applications`** зависит от транспорта **`b24.rest`** (**`RestClient`**, **`getKitWebhookPrefix`**); модулей **`catalog.import`** / **`usersync`** для загрузки классов не требует — порядок между **`catalog.import`** и **`usersync`** выбран для единообразия цепочки. Подключения **`sale`** / **`iblock`** остаются в рантайме внутри методов сервиса. Детали — [ST-07](./subtasks/07-segment-orders-deal-applications.md).

3d. **Bootstrap ST-08:** в **`requires.php`** включить **`eklektika.site`** (**после `catalog.pricing`**, **до `catalog.import`**): **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.site` → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`**. Глобальные **`sendRequestB24`** / **`sendRequest`** подключаются из **`eklektika.b24.rest`** (**`LegacyGlobalB24.php`**). Регистрация **`search` / BeforeIndex`** перенесена из **`requires.php`** в **`eklektika.site/include.php`** (**`SearchIndexingBootstrap`**). Детали — [ST-08](./subtasks/08-refactor-init-php-bootstrap.md).

4. При смене namespace — переходный `class_alias` / параллельный класс-обёртка ([ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md)).
5. Конфигурация URL/вебхуков B24 не дублируется в `lib/` — только чтение из существующего конфига (ST-02).

---

## 6. Обновление `docs/features/`

После утверждения раскладки исполнитель обновляет [`docs/features/local_classes_segments_and_modules.md`](../../features/local_classes_segments_and_modules.md): таблица модулей, ссылка на **этот файл** как на каноническую целевую структуру (без дублирования полного дерева при необходимости).

По завершении **ST-04** дополнительно синхронизировать [`docs/features/company_system.md`](../../features/company_system.md): модуль **`eklektika.company`**, файлы под `lib/`, цепочка загрузки **`rest` → `company` → `usersync`**, контракт **`getMaxCompanyDiscountPercentForUserGroups`** для связки с каталогом/pricing до выноса **`CatalogPriceFloor`** в ST-05.

По завершении **ST-05** синхронизировать раздел pricing в [`docs/features/local_classes_segments_and_modules.md`](../../features/local_classes_segments_and_modules.md) и при необходимости [`docs/features/company_system.md`](../../features/company_system.md): модуль **`eklektika.catalog.pricing`**, цепочка **`rest` → `company` → `catalog.pricing` → `usersync`**, **`CatalogPriceFloor::bootstrap()`** в **`init.php`** после **`requires.php`**.

По завершении **ST-06** обновить раздел каталога / bootstrap в [`docs/features/local_classes_segments_and_modules.md`](../../features/local_classes_segments_and_modules.md): модуль **`eklektika.catalog.import`**, цепочка **`rest` → `company` → `catalog.pricing` → `catalog.import` → `usersync`**; постобработка импорта 1С **не** в **`init.php`**.
