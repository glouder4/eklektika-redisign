# Сегменты собственной разработки и модули `local/modules/eklektika.*`

## Назначение документа

Единая ссылочная карта доменных сегментов и связей с Bitrix24. **Целевое размещение механики** — каталог **`lib/`** модулей с префиксом **`eklektika.*`** (`local/modules/<module_id>/lib/`), не «общая» иерархия под `local/classes`. Загрузка через **bootstrap** в `local/php_interface/init.php`, **`Loader::includeModule`** и точечные `requires` на переходном этапе.

**Каноническая раскладка модулей и таблица «сегмент → module_id → lib»:** [`docs/tasks/2026-04-21-refactor-local-classes-segmentation/MODULE-LAYOUT.md`](../tasks/2026-04-21-refactor-local-classes-segmentation/MODULE-LAYOUT.md).

Полный план работ, риски и порядок миграции: [`docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`](../tasks/2026-04-21-refactor-local-classes-segmentation/README.md) (здесь не дублируются длинные блоки).

## Таблица сегментов

| Сегмент | Назначение | Ключевые файлы / точки входа | Связь с Bitrix24 |
|--------|-------------|------------------------------|-------------------|
| **Пользователь ↔ CRM** | Регистрация, обновление профиля, удаление; синхронизация с контактом CRM | Модуль **`eklektika.b24.usersync`**: [`lib/RegisterUserCompany.php`](../../local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php), [`lib/Config/RegisterUserCompanyConfig.php`](../../local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php), [`lib/Config/UserSyncConfig.php`](../../local/modules/eklektika.b24.usersync/lib/Config/UserSyncConfig.php), [`lib/User.php`](../../local/modules/eklektika.b24.usersync/lib/User.php), [`lib/UserSyncBootstrap.php`](../../local/modules/eklektika.b24.usersync/lib/UserSyncBootstrap.php), [`lib/ContactAjaxFacade.php`](../../local/modules/eklektika.b24.usersync/lib/ContactAjaxFacade.php) (AJAX `UPDATE_CONTACT` / `UPDATE_BATCH_USERS` / `DELETE_CONTACT` → только фасад); подключение [`include.php`](../../local/modules/eklektika.b24.usersync/include.php) через **`Loader::includeModule`** в [`requires.php`](../../local/classes/requires.php) | REST `crm.contact.*` и смежные операции (`RestClient::callRestMethod` для usersync-CRM сценариев) |
| **Компании, холдинги, менеджеры** | ИБ компаний, холдинги, менеджеры, пользователи компании | Модуль **`eklektika.company`**: [`lib/Company.php`](../../local/modules/eklektika.company/lib/Company.php), [`lib/Config/CompanyB24Config.php`](../../local/modules/eklektika.company/lib/Config/CompanyB24Config.php), [`lib/Manager.php`](../../local/modules/eklektika.company/lib/Manager.php), [`lib/UserGroups.php`](../../local/modules/eklektika.company/lib/UserGroups.php); [`director/person/add-new-person-action.php`](../../director/person/add-new-person-action.php), [`local/classes/ajax.php`](../../local/classes/ajax.php). Контракт скидки для pricing: **`Company::getMaxCompanyDiscountPercentForUserGroups`** | REST `crm.company.*`, `crm.contact.company.*`, `crm.requisite.*`, файлы CRM через `RestClient` |
| **Ценообразование и «скидка компании»** | Пол цены, рекламная/оптовая база, нижняя граница; групповая скидка из контекста компании | Модуль **`eklektika.catalog.pricing`**: [`lib/CatalogPriceFloor.php`](../../local/modules/eklektika.catalog.pricing/lib/CatalogPriceFloor.php), [`lib/Config/CatalogPricingConfig.php`](../../local/modules/eklektika.catalog.pricing/lib/Config/CatalogPricingConfig.php); связь с компанией только через **`Company::getMaxCompanyDiscountPercentForUserGroups`**; вызовы из шаблонов каталога/корзины; ранний вызов из [`local/classes/requires.php`](../../local/classes/requires.php); **`CatalogPriceFloor::bootstrap()`** в [`local/php_interface/init.php`](../../local/php_interface/init.php) после `requires` | Обычно без прямого REST на витрине |
| **HTTP-слой и конфигурация B24** | Единый транспорт REST и конфиг URL/вебхуков | Модуль **`eklektika.b24.rest`**: [`RestClient.php`](../../local/modules/eklektika.b24.rest/lib/RestClient.php), [`lib/Config/RestTransportConfig.php`](../../local/modules/eklektika.b24.rest/lib/Config/RestTransportConfig.php), [`Request.php`](../../local/modules/eklektika.b24.rest/lib/Request.php), legacy-глобали **[`LegacyGlobalB24.php`](../../local/modules/eklektika.b24.rest/lib/LegacyGlobalB24.php)** (`sendRequestB24`, `sendRequest`, …); конфиг [`b24_integration_config.php`](../../local/php_interface/b24_integration_config.php); первым в [`requires.php`](../../local/classes/requires.php) | Все REST-вызовы сайта |
| **Каталог: постобработка после импорта 1С** | Сбор свойства «типы нанесения» (`APPLICATION_TYPES`) после обмена с 1С | Модуль **`eklektika.catalog.import`**: [`local/modules/eklektika.catalog.import/lib/`](../../local/modules/eklektika.catalog.import/lib/) (namespace **`OnlineService\Catalog\Import1c\*`**), конфиг маппинга [`lib/Config/PostImportConfig.php`](../../local/modules/eklektika.catalog.import/lib/Config/PostImportConfig.php); событие **`catalog` / `OnSuccessCatalogImport1C`** регистрируется при загрузке модуля ([`include.php`](../../local/modules/eklektika.catalog.import/include.php)); подключение через **`Loader::includeModule`** в [`requires.php`](../../local/classes/requires.php). Логики в [`init.php`](../../local/php_interface/init.php) нет. | Нет |
| **Заказы и заявки из сделок B24** | Строки заявок в заказ Sale | Модуль **`eklektika.orders.applications`**: [`lib/DealApplicationsService.php`](../../local/modules/eklektika.orders.applications/lib/DealApplicationsService.php), конфиг [`lib/Config/DealApplicationsConfig.php`](../../local/modules/eklektika.orders.applications/lib/Config/DealApplicationsConfig.php); kit-методы — **`RestClient::callKitRestGet`**; тонкие **`getApplication`** / **`addApplication`** в [`local/php_interface/init.php`](../../local/php_interface/init.php). Отладочные логи в **`DOCUMENT_ROOT`** — только при **`define('EKLEKTIKA_DEAL_APPLICATIONS_DEBUG_LOG', true)`**. | Специализированный REST через вебхук KIT |
| **Поиск** | Модификация индексирования (stemming) | Регистрация из **`eklektika.site`**: [`SearchIndexingBootstrap.php`](../../local/modules/eklektika.site/lib/SearchIndexingBootstrap.php), конфиг [`lib/Config/SiteModuleConfig.php`](../../local/modules/eklektika.site/lib/Config/SiteModuleConfig.php) → класс [`Stemming`](../../local/php_interface/classes/handlers/search/stemming.php) | Нет |
| **Настройки контента (Page editor)** | Свойства элемента ИБ как настройки страницы | Модуль **`eklektika.site`**: [`PageSettings.php`](../../local/modules/eklektika.site/lib/PageSettings.php), глобальные **`getPageEditorSettings`** / **`getPageSettingValue`** ([`PageEditorGlobalFunctions.php`](../../local/modules/eklektika.site/lib/PageEditorGlobalFunctions.php)) | Нет |

## Точки входа bootstrap

| Файл | Роль |
|------|------|
| [`local/php_interface/init.php`](../../local/php_interface/init.php) | Подключение [`b24_integration_config.php`](../../local/php_interface/b24_integration_config.php) и константы `URL_B24`, `B24_REST_WEBHOOK_*`; cookie/окружение; Server-Timing; заявки — фасады **`getApplication`** / **`addApplication`** → **`eklektika.orders.applications`**; **`CatalogPriceFloor::bootstrap()`** после `requires`; без **`PageSettings`** и без legacy REST-функций (ST-08). |
| [`local/classes/requires.php`](../../local/classes/requires.php) | **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.site` → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`**; затем **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`**. Поиск **`BeforeIndex`** регистрируется из **`eklektika.site/include.php`**. |
| `eklektika.catalog.import` | Обработчик **`catalog` / `OnSuccessCatalogImport1C`** регистрируется при загрузке модуля (в **`include.php`**), классы в **`OnlineService\Catalog\Import1c\*`**. |
| `eklektika.b24.usersync` | Обработчики событий `main` регистрируются в **`UserSyncBootstrap::register()`** при загрузке модуля. |

### Глобальные REST-хелперы

Базовый транспорт — класс **`OnlineService\B24\RestClient`** в модуле **`eklektika.b24.rest`**; глобальные **`sendRequestB24`** и **`sendRequest`** подключаются вместе с модулем **`eklektika.b24.rest`** ([`LegacyGlobalB24.php`](../../local/modules/eklektika.b24.rest/lib/LegacyGlobalB24.php), @deprecated). Домен компании/менеджера/групп — модуль **`eklektika.company`**; ценообразование витрины — **`eklektika.catalog.pricing`** (`CatalogPriceFloor`). Остаток под `local/classes/site/` на переходном этапе — например **`StatusDiscounter`** и др. по MODULE-LAYOUT.

### Инвентаризация legacy include/глобалей (ST-09 continue)

| Legacy точка | Где используется сейчас | Модульный эквивалент | Статус |
|---|---|---|---|
| `sendRequestB24()` | legacy-вызовы в доменных классах и внешних скриптах | `\OnlineService\B24\RestClient::callRestMethod()` | сохранено как `@deprecated`-shim в `eklektika.b24.rest/lib/LegacyGlobalB24.php` |
| `sendRequest()` | legacy-вызовы прокси в `local/classes/ajax.php` и наследуемых классах | `\OnlineService\B24\RestClient::postAjaxProxy()` | сохранено как `@deprecated`-shim в `eklektika.b24.rest/lib/LegacyGlobalB24.php` |
| `getApplication()` / `addApplication()` в `init.php` | интеграция с внешними триггерами заказа | `\OnlineService\Orders\Applications\DealApplicationsService` | сохранены как thin-фасады (BC), логика в модуле |
| прямой вызов `CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()` из `requires.php` | bootstrap каталога | `eklektika.catalog.pricing` (`CatalogPriceFloor`) | стабилизирован проверкой `class_exists` перед вызовом |


## Предлагаемые модули `local/modules/eklektika.*`

Имена и границы — **рабочие**; финальное решение — **ST-09**.

| Предполагаемый модуль | Ответственность | Примечание |
|----------------------|-----------------|------------|
| `eklektika.core` или `eklektika.site` | Общий bootstrap, автозагрузка, конфиг домена | Держать отдельно от модулей вне префикса `eklektika.*` (сторонние поставщики) |
| `eklektika.b24.rest` | Клиент REST, конфиг URL/ключей | Вынос размазанных `sendRequestB24` |
| `eklektika.b24.usersync` | Регистрация/обновление пользователя ↔ контакт | `RegisterUserCompany`, часть `User` |
| `eklektika.company` | Компании, холдинги, менеджеры, группы | `Company`, `Manager`, `UserGroups` |
| `eklektika.catalog.pricing` | `CatalogPriceFloor` | Зависимость от контракта «скидка из компании» |
| `eklektika.catalog.import` | Постобработка после импорта 1С | `updateProperties` и аналоги |
| `eklektika.orders.applications` | Заявки из сделки в заказ | `DealApplicationsService`, фасады в `init.php` |
| `eklektika.site` | Page editor, регистрация поиска | `PageSettings`, `SearchIndexingBootstrap` |

## Правила независимости модулей (ST-10)

### Allowed dependencies (`module -> allowed deps`)

| module_id | Разрешённые зависимости | Запрещено |
|---|---|---|
| `eklektika.b24.rest` | Bitrix core, curl/json и собственные классы модуля | доменная логика компаний, pricing, usersync, заказов |
| `eklektika.company` | Bitrix core, `eklektika.b24.rest` | зависимость от `eklektika.catalog.pricing`, `eklektika.orders.applications`, `eklektika.catalog.import` |
| `eklektika.catalog.pricing` | Bitrix core, **узкий контракт** скидки из `eklektika.company` (`Company::getMaxCompanyDiscountPercentForUserGroups`) | любые остальные API `eklektika.company`; зависимость от usersync/orders/import |
| `eklektika.site` | Bitrix core, собственные классы модуля | прямые зависимости на usersync/company/pricing/orders/import |
| `eklektika.catalog.import` | Bitrix core, собственные классы модуля | зависимости на company/pricing/usersync/orders |
| `eklektika.orders.applications` | Bitrix core (`sale`, `iblock`), `eklektika.b24.rest` | зависимости на usersync/company/pricing/import |
| `eklektika.b24.usersync` | Bitrix core, `eklektika.b24.rest` | прямые зависимости на pricing/orders/import |

### Временные исключения (зафиксированы для BC)

1. `eklektika.catalog.pricing -> eklektika.company`: допустимо только через контракт скидки `Company::getMaxCompanyDiscountPercentForUserGroups`.
2. `eklektika.b24.usersync -> eklektika.company`: историческая связь в `RegisterUserCompany` (создание/обновление элемента компании после CRM-операций). Сохраняется до отдельного шага выделения фасада `CompanyGateway` в рамках follow-up без изменения бизнес-поведения.

   - Follow-up ID: `FU-ST11-USERSYNC-COMPANY-GATEWAY`.
   - Owner: Eklektika architecture and refactoring team (module maintainers: usersync + company).
   - Deadline/condition for removal: до `2026-05-29` или в первый следующий code-touch цикл usersync/company (если цикл не наступил до даты — перенос только с явным обновлением task docs).
   - Acceptance criteria: usersync использует только узкий gateway/контракт к company, прямая зависимость удалена, BC сохранён и подтверждён ручным smoke usersync/company.
   - Follow-up docs: [ST-10: Границы модулей, транспорт REST и независимость доменов](../tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md), [ST-11: Stabilization, smoke/documentation closeout and sync](../tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/11-stabilization-smoke-documentation-closeout.md).

## Ссылки на документацию фич

- [Интеграция с Bitrix24](./b24_integration.md) — REST, вебхуки, типовые сценарии CRM.
- [Компании и холдинги](./company_system.md) — ИБ, холдинги, поля компании.
- [Рекламный агент](./uf_advertising_agent.md) — пользовательское поле и ЛК (контекст для пересечений с `init.php`).
- [Система групп пользователей](./user_groups_system.md) — группы и права (`UserGroups`).
- [Методы работы с компаниями пользователей](./user_company_methods.md) — API компаний пользователя.

## Out of scope (по arch-rules)

Зоны, перечисленные в архитектурных правилах как **сторонние или вне зоны изменений** рефакторинга сегментов `local/classes`, **не описываются в этой карте** как целевые сегменты и **не меняются** без отдельного решения. Конкретные пути и namespace — в [`.cursor/rules/arch-rules.mdc`](../../.cursor/rules/arch-rules.mdc).

Сторонние Bitrix-модули в `local/modules/`, не относящиеся к собственному префиксу `eklektika.*`, в карте сегментов **не именуются**; их границы задаёт тот же arch-rules.

## Дополнительные зоны поверх «трёх базовых блоков»

Базовые блоки в плане задачи: пользователь↔CRM, компании/холдинги/менеджеры, ценообразование (`CatalogPriceFloor`). **Дополнительно** явно выделены:

1. Глобальный HTTP-слой REST: конфиг `b24_integration_config.php`, `RestClient`, legacy-функции в **`eklektika.b24.rest`**.
2. Постобработка каталога после импорта 1С — модуль **`eklektika.catalog.import`** (`OnlineService\Catalog\Import1c\*`).
3. Заявки из сделки B24 ↔ заказ Sale (`getApplication`, `addApplication`, вебхук).
4. Поиск: обработчик `Stemming`, регистрация через **`eklektika.site`**.
5. **`PageSettings`** и хелперы страницы — модуль **`eklektika.site`**.

## Целевая структура: модули и `lib/`

**Каноничный артефакт:** [`MODULE-LAYOUT.md`](../tasks/2026-04-21-refactor-local-classes-segmentation/MODULE-LAYOUT.md) — таблица сегмент → **`module_id`** → пути под **`lib/`**; установщики модулей пока вне scope.

Исторический черновик с деревом `local/classes/core/` + `segment/` сохранён как [`TARGET-STRUCTURE.md`](../tasks/2026-04-21-refactor-local-classes-segmentation/TARGET-STRUCTURE.md) (legacy); **не** использовать как целевую модель размещения.

**Правила независимости** доменных модулей и общий транспорт **`eklektika.b24.rest`:** [ST-10](../tasks/2026-04-21-refactor-local-classes-segmentation/subtasks/10-architecture-segment-independence-and-core-boundary.md).

**Пример для исполнителя (ST-03):** синхронизация пользователя с CRM → **`local/modules/eklektika.b24.usersync/lib/`** (не смешивать с REST-транспортом).

## Практический статус по `local/php_interface/classes`

- Удаление `local/php_interface/classes` в текущем инкременте **не выполняется** (safe staged-result).
- Подтверждённая runtime-зависимость: `eklektika.site/lib/SearchIndexingBootstrap.php` регистрирует `\OnlineService\Classes\Handlers\Search\Stemming` из `local/php_interface/classes/handlers/search/stemming.php`.
- Gate-условия перед удалением/переносом:
  1. Вынести `Stemming` в модульный namespace `eklektika.site` (или отдельный `eklektika.search`) с сохранением сигнатуры `BeforeIndexHandler`.
  2. Переключить автозагрузку `SearchIndexingBootstrap` на новый путь, оставить BC-шим на 1 релиз.
  3. Выполнить ручной smoke поиска по артикулам/опечаткам (сценарии из `Stemming`) и сравнить выдачу до/после.
  4. Только после успешного smoke и doc-sync — удалять legacy-путь.

---

*Документ: ST-01 + MODULE-LAYOUT · последнее обновление: 2026-04-22 (ST-11 stabilization: docs closeout sync, bootstrap consistency, implement-ready follow-up)*
