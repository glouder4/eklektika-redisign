# Рефакторинг сегментации `local/classes` и подготовка модулей `eklektika.*`

## Метаданные

- ID: TASK-2026-04-21-refactor-local-classes-segmentation
- Статус: **in_progress** (ST-11 stabilization docs sync done, manual smoke pending)
- Приоритет: high
- Дата создания: 2026-04-21
- Дата последнего обновления карточки: 2026-04-22 (ST-11 stabilization: docs closeout sync + manual smoke handoff)
- Ответственный: нет

## Текущий фокус выполнения

### Следующий спринт (приоритет исполнения)

1. **ST-09** — каркас модулей, автозагрузка, политика namespace ([подзадача 09](./subtasks/09-modules-eklektika-scaffold-and-migration.md)).
2. **ST-10** — архитектурные границы модулей и независимость доменов ([подзадача 10](./subtasks/10-architecture-segment-independence-and-core-boundary.md)).
3. **ST-11** — stabilization: smoke/documentation closeout и handoff на ручной прогон ([подзадача 11](./subtasks/11-stabilization-smoke-documentation-closeout.md)).

### Статус текущей точки (2026-04-22, ST-11)

- Миграция доменов в `local/modules/eklektika.*/lib/` выполнена по основным сегментам; bootstrap-цепочка модулей уже собрана в [`local/classes/requires.php`](../../../local/classes/requires.php).
- Основной незавершённый объём: **унификация `include.php`/autoload (ST-09)** и **формализация/проверка межмодульных границ (ST-10)**.
- Критичный фокус на этапе continue: не менять бизнес-логику, а довести инфраструктурную консистентность загрузки и документацию до финального состояния.
- Документация ST-09/ST-10 и feature-артефакты синхронизированы для ручного smoke и финального closeout.
- ST-11 выделен как stabilization-слой: без новых миграций, только консистентность формулировок/артефактов и прозрачный статус ожидания ручной проверки.

- **Завершённые:** ST-01 … **ST-08**; в т.ч. **ST-08**: модуль [`eklektika.site`](../../../local/modules/eklektika.site/) (**`PageSettings`**, **`SearchIndexingBootstrap`**), глобальные REST-обёртки в [`eklektika.b24.rest/lib/LegacyGlobalB24.php`](../../../local/modules/eklektika.b24.rest/lib/LegacyGlobalB24.php). **ST-07**: модуль [`eklektika.orders.applications`](../../../local/modules/eklektika.orders.applications/) (**`DealApplicationsService`**), расширение **`RestClient::callKitRestGet`**, фасады в [`init.php`](../../../local/php_interface/init.php), подключение в [`requires.php`](../../../local/classes/requires.php) между **`catalog.import`** и **`usersync`**. **ST-06**: модуль [`eklektika.catalog.import`](../../../local/modules/eklektika.catalog.import/) (**`OnlineService\Catalog\Import1c\*`**), регистрация **`OnSuccessCatalogImport1C`** в **`include.php`**; из [`init.php`](../../../local/php_interface/init.php) удалены **`IBLOCK_ID_1C`**, **`updateProperties`**, **`actionSection`**, связанный **`AddEventHandler`**.
- **Ранее (ST-01–ST-02):** карта, REST в [`eklektika.b24.rest/lib/`](../../../local/modules/eklektika.b24.rest/lib/); [MODULE-LAYOUT.md](./MODULE-LAYOUT.md) каноничен.
- **Порядок подключения в bootstrap (`init.php` / `requires.php`):** в [`requires.php`](../../../local/classes/requires.php): **`Loader::includeModule`**: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.site` → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`**, затем **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`**. Namespace без изменений до [ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md).
- **Карта целевого размещения кода (обязательно к прочтению):** [MODULE-LAYOUT.md](./MODULE-LAYOUT.md) — таблица **сегмент → `module_id` → пути под `lib/`**, переход от `local/classes`, порядок миграции (`include.php`, `Loader::registerAutoLoadClasses`). Исторический вариант с `core/segment` — [TARGET-STRUCTURE.md](./TARGET-STRUCTURE.md) (legacy).

### Вход в implement-цикл (актуализация от 2026-04-21, `/orchestrate continue`)

1. Зафиксировать в каждом `local/modules/eklektika.*/include.php` единый шаблон `Loader::registerAutoLoadClasses`, убрать рассинхрон namespace/путей.
2. Проверить и устранить остаточные прямые `require` из `local/classes` для классов, уже переехавших в `local/modules/eklektika.*/lib/`.
3. Проверить граф зависимостей: доменные модули не тянут друг друга напрямую, кроме документированного контракта pricing -> company.
4. Обновить `docs/features/local_classes_segments_and_modules.md` разделом "Правила независимости" с явными allowed/forbidden зависимостями.
5. Обновить `docs/features/README.md` и профильные документы (`b24_integration.md`, `company_system.md`) по фактическим точкам bootstrap (`init.php`, `requires.php`) и списку модулей.
6. Провести smoke-проверку: CRM sync пользователя, компании/менеджеры, ценообразование, заявки сделки, поиск, page settings.

### Операционный план завершения (continue, без перепостановки задачи)

1. **Закрыть ST-09 (техническая унификация модулей):**
   - Привести все `local/modules/eklektika.*/include.php` к единому шаблону `Loader::registerAutoLoadClasses`.
   - Проверить, что пути и классы в автозагрузке совпадают с фактическим `lib/`.
   - Удалить остаточные `require` legacy-классов из `local/classes` там, где класс уже в модуле.
2. **Закрыть ST-10 (архитектурные границы):**
   - Зафиксировать в документации final-граф допустимых зависимостей модулей.
   - Проверить, что кросс-доменные зависимости сведены к documented exceptions.
3. **Стабилизационный проход перед финалом:**
   - Проверить bootstrap-порядок `Loader::includeModule` в `requires.php`.
   - Подтвердить отсутствие регрессий по smoke-сценариям доменов.
4. **Финализация документации и карточки задачи:**
   - Синхронизировать `docs/features/*` с фактическим кодом и bootstrap.
   - Обновить статусы ST-09/ST-10 и критерии готовности в этой карточке.

### Приоритетный порядок ближайшей реализации (implementation queue)

1. **Q1 (обязательный):** пройти все `local/modules/eklektika.*/include.php`, выровнять `Loader::registerAutoLoadClasses` по фактическим классам в `lib/`, убрать битые/лишние регистрации.
2. **Q2 (обязательный):** в `local/php_interface/init.php` и `local/classes/requires.php` убрать legacy `require` для уже мигрированных классов; сохранить текущий порядок `Loader::includeModule`.
3. **Q3 (обязательный):** проверить фактические зависимости между модулями `eklektika.*`; оформить allowed/forbidden matrix и временные исключения.
4. **Q4 (обязательный):** синхронизировать `docs/features/local_classes_segments_and_modules.md`, `docs/features/README.md`, `docs/features/b24_integration.md`, `docs/features/company_system.md`.
5. **Q5 (финал):** выполнить smoke по доменам и закрыть ST-09/ST-10 с фиксацией факта в карточке.

### Continue-порядок закрытия (операционный runbook)

1. **RUN-1 (ST-09 / include.php):** пройти все подключаемые `eklektika.*` модули, проверить и исправить `class -> file` в `Loader::registerAutoLoadClasses`.
2. **RUN-2 (ST-09 / bootstrap cleanup):** убрать legacy `require` migrated-классов из `init.php`/`requires.php`, сохранить текущий порядок `Loader::includeModule`.
3. **RUN-3 (ST-10 / dependency check):** сверить фактические зависимости в `local/modules/eklektika.*/lib/` и оформить исключения с owner + сроком снятия.
4. **RUN-4 (docs sync):** синхронизировать `docs/features/*` с фактическим bootstrap, автозагрузкой и матрицей зависимостей.
5. **RUN-5 (final smoke):** прогнать ключевые сценарии и только после этого перевести ST-09/ST-10 в done.

### Единые критерии завершения ST-09 + ST-10 (DoD для финального цикла)

- [ ] Во всех используемых модулях `eklektika.*` автозагрузка настроена через рабочий `include.php` без рассинхрона class->path.
- [ ] Для migrated-классов отсутствуют дублирующие подключения из `local/classes/*` (кроме явно задокументированного переходного остатка).
- [ ] Зафиксирован и соблюдается граф зависимостей: домены не импортируют друг друга произвольно; исключения описаны как контракты.
- [ ] `requires.php` содержит целевой порядок модулей и проходит smoke без фаталов.
- [ ] Обновлены `docs/features/local_classes_segments_and_modules.md`, `docs/features/README.md`, профильные документы (`b24_integration.md`, `company_system.md`) под фактическое состояние.
- [ ] В документации зафиксирована матрица зависимостей `allowed/forbidden` для всех используемых модулей `eklektika.*` и отмечены временные исключения (если есть).
- [ ] Заполнен отдельный smoke-артефакт с датой прогона и статусами `pass/fail/not run` по ключевым сценариям (финализация в ST-11).

## Требования заказчика

### Уточнение 2026-04-21 (актуально)

1. **Целевое размещение механики:** отдельные Bitrix-модули `local/modules/eklektika.*`, внутри модуля код в **`lib/`** (см. [MODULE-LAYOUT.md](./MODULE-LAYOUT.md)). Папка **`local/classes` как постоянная организационная корневая зона не используется**; допустим только **переходный** перенос и bootstrap (`requires.php`, старые пути), пока соответствующие классы не переехали в модули.
2. **Установщики модулей** (`install/index.php` и полный цикл установки из админки) — **вне scope** до отдельного решения; на этом этапе достаточно **минимального** `include.php` и автозагрузки ([ST-09](./subtasks/09-modules-eklektika-scaffold-and-migration.md)).
3. Домены (**холдинги / компании**, **синхронизация пользователя с CRM**, каталог, заказы и т.д.) — **изолированные модули**; сквозные зависимости **запрещены**, кроме документированных исключений ([ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md)).
4. **Транспорт CRM** (`RestClient`, `Request`) — модуль **`eklektika.b24.rest`**; конфиг окружения (тестовый/боевой B24) — по-прежнему [`local/php_interface/b24_integration_config.php`](../../../local/php_interface/b24_integration_config.php) и фрагменты `init.php`, **без дублирования секретов** в доменных `lib/`.

### Первоначальное уточнение (частично заменено схемой модулей)

Ранее планировались папки `local/classes/core` и `local/classes/segment/*` — **эта схема снята с цели**; осталась в [TARGET-STRUCTURE.md](./TARGET-STRUCTURE.md) только как legacy-описание.

## Ссылки на источники

- Issue: нет
- PRD/Док: нет
- Доп. контекст: оркестрация `/orchestrate`, правила [.cursor/rules/arch-rules.mdc](../../../.cursor/rules/arch-rules.mdc), индекс [docs/features/README.md](../../features/README.md)

## Цель

Разобрать «плоский» состав `local/classes/` и смешанную загрузку через [`local/php_interface/init.php`](../../../local/php_interface/init.php) и [`local/classes/requires.php`](../../../local/classes/requires.php): перенести механику в **собственные модули** `local/modules/eklektika.*` с кодом в **`lib/`**, зафиксировать **границы модулей** и таблицу соответствия ([MODULE-LAYOUT.md](./MODULE-LAYOUT.md)), не затрагивая сторонние модули вне префикса `eklektika.*`, и выполнить **пошаговую миграцию** с минимизацией регрессий.

Ожидаемый эффект: проще сопровождать код, ясные зависимости между модулями, единственное очевидное место для нового кода — `local/modules/eklektika.*/lib/`, без дальнейшего разрастания `init.php` доменной логикой.

## Карта доменов / сегментов

| Сегмент | Назначение | Ключевые точки входа / файлы | Связь с Bitrix24 |
|--------|-------------|------------------------------|------------------|
| **Пользователь ↔ CRM** | Регистрация, обновление профиля, удаление; синхронизация с контактом CRM | [`eklektika.b24.usersync`](../../../local/modules/eklektika.b24.usersync/) (`lib/`, регистрация событий в **`UserSyncBootstrap`**) | REST `crm.contact.*`, связанные операции |
| **Компании, холдинги, менеджеры** | ИБ компаний, холдинговая структура, менеджеры, пользователи компании | Модуль [`eklektika.company`](../../../local/modules/eklektika.company/) (`lib/Company.php`, `Manager.php`, `UserGroups.php`), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php), [`local/classes/ajax.php`](../../../local/classes/ajax.php) | REST `crm.company.*`, `crm.contact.company.*`, `crm.requisite.*`, файлы CRM |
| **Ценообразование и «скидка компании»** | Пол цены, рекламная/оптовая база, нижняя граница; групповая скидка через **`Company::getMaxCompanyDiscountPercentForUserGroups`** | Модуль [`eklektika.catalog.pricing`](../../../local/modules/eklektika.catalog.pricing/) (**`lib/CatalogPriceFloor.php`**); шаблоны каталога/корзины | Обычно без прямого REST на витрине; бизнес-правила сайта |
| **HTTP-слой и конфигурация B24** | Единый способ вызова REST, базовый URL, вебхуки | Модуль [`eklektika.b24.rest`](../../../local/modules/eklektika.b24.rest/) (`lib/RestClient`, `lib/Request`); глобальные `sendRequestB24`, `sendRequest`, константы в [`init.php`](../../../local/php_interface/init.php) | Все REST-вызовы |
| **Каталог: постобработка после импорта 1С** | Сбор свойства «типы нанесения» в одно поле после обмена | Модуль [`eklektika.catalog.import`](../../../local/modules/eklektika.catalog.import/) (`lib/PostImportHandler.php`, **`Import1cBootstrap`**); загрузка в [`requires.php`](../../../local/classes/requires.php) | нет |
| **Заказы и заявки из сделок B24** | Подтягивание строк заявок в заказ Sale | [`eklektika.orders.applications`](../../../local/modules/eklektika.orders.applications/) (**`DealApplicationsService`**), фасады в [`init.php`](../../../local/php_interface/init.php), **`RestClient::callKitRestGet`** | Специализированный REST через вебхук KIT |
| **Поиск** | Модификация индексирования (stemming) | [`local/classes/requires.php`](../../../local/classes/requires.php) → `OnlineService\Classes\Handlers\Search\Stemming` | нет |
| **Настройки контента (Page editor)** | Чтение свойств элемента ИБ как настроек страницы | Модуль [`eklektika.site`](../../../local/modules/eklektika.site/) (**`PageSettings`**, глобальные **`getPageEditorSettings`** / **`getPageSettingValue`**) | нет |

**Дополнительно выделенные области** (поверх трёх известных блоков): постобработка каталога после 1С; интеграция заявок сделки с корзиной; глобальный HTTP-слой REST в `init.php`; поисковый обработчик в `requires.php`; класс `PageSettings` в `init.php`; широкое использование `CatalogPriceFloor` в шаблонах как отдельный домен ценообразования.

### Соответствие модулей и кода

Каноническая таблица **сегмент → файлы → `module_id` → пути под `lib/`** — в [MODULE-LAYOUT.md §2](./MODULE-LAYOUT.md#2-таблица-соответствия-сегмент--текущие-файлы--module_id--lib). Кратко:

| `module_id` | Ответственность |
|-------------|-----------------|
| `eklektika.b24.rest` | Клиент REST, `Request`, без доменной логики CRM |
| `eklektika.b24.usersync` | Пользователь ↔ контакт CRM |
| `eklektika.company` | Компании, холдинги, менеджеры, группы |
| `eklektika.catalog.pricing` | `CatalogPriceFloor`, события цен |
| `eklektika.catalog.import` | Постобработка после импорта 1С |
| `eklektika.orders.applications` | Заявки сделки → корзина |
| `eklektika.site` | `PageSettings` и прочий вынос из `init.php` (по [MODULE-LAYOUT.md](./MODULE-LAYOUT.md)) |

Уточнение имён и зависимостей — в **ST-09**; физическая раскладка классов — в **`…/lib/`**, а не под `local/classes/segment`.

### Зависимости между модулями (кратко)

Правила зафиксированы в [ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md). Кратко:

- Любой доменный модуль `eklektika.*` (кроме транспорта) → только **`eklektika.b24.rest`** для REST, API Битрикс и документированные узкие контракты.
- **catalog.pricing** → не импортирует `Company` целиком; только контракт скидки из **eklektika.company** (ST-04 + ST-05).
- **b24.usersync** → **b24.rest** + собственный `lib/`; прямых зависимостей модулей на **catalog.pricing** нет (класс **`Company`** подгружается через **`eklektika.company`** раньше по **`requires.php`** для **`RegisterUserCompany`**).
- **company** → **b24.rest** для реквизитов/компаний CRM.
- **orders.applications** → **b24.rest** + Sale; изолировать от usersync.

## Границы (Scope)

### In scope

- Создание и наполнение **`local/modules/eklektika.*/lib/`** (механика доменов и REST), минимальные **`include.php`**, подключение из [`local/php_interface/init.php`](../../../local/php_interface/init.php) / [`local/classes/requires.php`](../../../local/classes/requires.php) с поэтапным отказом от ручных require.
- Переходное использование [`local/classes/`](../../../local/classes/) только пока классы не перенесены (см. [MODULE-LAYOUT §4](./MODULE-LAYOUT.md#4-отношение-к-localclasses-переходный-период)).
- Уточнение точек входа в [`director/`](../../../director/), [`personal/`](../../../personal/), собственные компоненты [`local/components/`](../../../local/components/), шаблоны [`local/templates/`](../../../local/templates/) — как потребители классов из модулей (обновление `use`/namespace при необходимости).
- Документация в [`docs/features/`](../../features/): обзор модулей и таблица соответствия, обновление индекса.
- **Не** добавлять полноценные установщики модулей в рамках текущей задачи (только минимальный каркас в ST-09).

### Out of scope

- Любые изменения в зонах, перечисленных в [`.cursor/rules/arch-rules.mdc`](../../../.cursor/rules/arch-rules.mdc) как сторонние или запрещённые для правок в рамках этой задачи (конкретные пути — только в arch-rules).
- Переписывание бизнес-логики сторонних модулей Битрикс.

## План внедрения

1. Зафиксировать карту доменов и обновить `docs/features/` (ST-01).
2. Выделить слой B24 HTTP/конфигурации без изменения поведения REST (ST-02).
3. Перенести синхронизацию пользователя с CRM в **`eklektika.b24.usersync/lib/`** (обработчики `events.php`, `RegisterUserCompany`, часть `User`) (ST-03).
4. Перенести компании/холдинги/менеджеры и связанный AJAX в **`eklektika.company/lib/`** (ST-04).
5. Изолировать ценообразование `CatalogPriceFloor` и контракт скидки в **`eklektika.catalog.pricing/lib/`** (ST-05).
6. Изолировать постобработку импорта 1С в **`eklektika.catalog.import/lib/`** (ST-06).
7. Изолировать заказ↔заявки сделки в **`eklektika.orders.applications/lib/`** (ST-07).
8. Упростить `init.php`/`requires.php`: тонкий bootstrap, перенос `PageSettings` и прочего в **`eklektika.site`** / согласованные модули (ST-08).
9. Унифицировать минимальные **`include.php`**, автозагрузку и политику namespace для всех **`eklektika.*`** без установщиков `install/` в текущем scope (ST-09).
10. Зафиксировать правила независимости **модулей** и исключения в `docs/features/` (ST-10).

### Стратегия `init.php`

- **Текущее состояние (после ST-08):** cookie/домен сессии, замеры Server-Timing, константы B24 из конфига, `requires.php` (цепочка **`eklektika.*`** включая **`site`**, **`orders.applications`**, legacy REST при загрузке **`b24.rest`**), **`CatalogPriceFloor::bootstrap()`**; без **`PageSettings`** и без объявлений **`sendRequestB24`** в **`init.php`**.
- **Целевое:** короткий пролог: настройки окружения сайта + `Loader::includeModule('eklektika....')` / вызовы минимальных bootstrap-классов из **`local/modules/eklektika.*/lib/`** (регистрация обработчиков внутри модулей).
- **Порядок модулей `eklektika.*`:** **`rest` → `company` → `catalog.pricing` → `site` → `catalog.import` → `orders.applications` → `usersync`**; в [`requires.php`](../../../local/classes/requires.php) ([ST-02](./subtasks/02-b24-http-layer-and-config.md) … [ST-08](./subtasks/08-refactor-init-php-bootstrap.md)).
- **Принцип:** не переносить новую бизнес-логику в `init.php`; только подключение модулей и инфраструктура.

### Порядок миграции (минимизация риска)

Детально: [MODULE-LAYOUT.md §5](./MODULE-LAYOUT.md#5-миграция-и-загрузка-namespace-includephp-loaderregisterautoloadclasses). Логический порядок:

1. Документация и инвентаризация (ST-01).
2. REST-слой + конфиг (ST-02) — основа; транспорт размещён в [`local/modules/eklektika.b24.rest/lib/`](../../../local/modules/eklektika.b24.rest/lib/).
3. User CRM sync (ST-03): домен в **`eklektika.b24.usersync/lib/`**; транспорт — **`eklektika.b24.rest`**.
4. Company (ST-04) — **выполнено:** **`eklektika.company/lib/`**, порядок в [`requires.php`](../../../local/classes/requires.php): **`rest` → `company` → `usersync`** — см. [ST-04](./subtasks/04-segment-company-holding-manager.md).
5. Catalog pricing (ST-05) — **выполнено:** **`eklektika.catalog.pricing/lib/CatalogPriceFloor.php`**, см. [ST-05](./subtasks/05-segment-catalog-pricing-discount.md).
6. Import hooks (ST-06) — **выполнено:** **`eklektika.catalog.import/lib/`** (`OnlineService\Catalog\Import1c\*`, событие в **`include.php`**, только **`requires.php`**).
7. Orders applications (ST-07) → **`eklektika.orders.applications/lib/`**.
8. Разгрузка `init.php` и **`requires.php`** (ST-08); вынос поиска/PageSettings — по [MODULE-LAYOUT.md](./MODULE-LAYOUT.md).
9. Унификация каркаса модулей, namespace, `Loader::registerAutoLoadClasses` (ST-09); **без** установщиков `install/` в текущем scope.
10. Документирование границ модулей (ST-10) — параллельно ревью ST-03–ST-06.

## Подзадачи

- [x] [ST-01: Документирование карты доменов и границ модулей](./subtasks/01-documentation-domain-map.md)
- [x] [ST-02: Слой B24 REST и конфигурация вызовов](./subtasks/02-b24-http-layer-and-config.md)
- [x] [ST-03: Сегмент синхронизации пользователя с CRM](./subtasks/03-segment-user-registration-crm-sync.md)
- [x] [ST-04: Сегмент компаний, холдингов и менеджеров](./subtasks/04-segment-company-holding-manager.md)
- [x] [ST-05: Сегмент ценообразования и скидки компании](./subtasks/05-segment-catalog-pricing-discount.md)
- [x] [ST-06: Постобработка каталога после импорта 1С](./subtasks/06-segment-catalog-1c-import-hooks.md)
- [x] [ST-07: Заявки из сделки B24 и корзина](./subtasks/07-segment-orders-deal-applications.md)
- [x] [ST-08: Рефакторинг bootstrap init.php и requires.php](./subtasks/08-refactor-init-php-bootstrap.md)
- [ ] [ST-09: Каркас модулей eklektika.* и перенос пространств имён](./subtasks/09-modules-eklektika-scaffold-and-migration.md) - ready_for_review (docs), финальный smoke pending
- [ ] [ST-10: Границы core/segment и независимость сегментов](./subtasks/10-architecture-segment-independence-and-core-boundary.md) - ready_for_review (docs), follow-up exception pending
- [ ] [ST-11: Stabilization, smoke/documentation closeout and sync](./subtasks/11-stabilization-smoke-documentation-closeout.md) - in_progress (waiting manual smoke evidence)

## Зависимости и риски

### Зависимости

- Работа ST-04–ST-07 опирается на транспорт REST (**`eklektika.b24.rest/lib/`**).
- ST-05 зависит от контракта со ST-04: зафиксирован **`OnlineService\Site\Company::getMaxCompanyDiscountPercentForUserGroups(array): float`** как точка входа для «скидки компании» (расширение/интерфейс — только если понадобится при выносе pricing в ST-05).
- ST-09 **пересекается** с ST-03–ST-08: минимальный `include.php` и модульные каталоги можно вводить по мере переноса; финальная политика namespace — после стабилизации кода в `lib/`.
- ST-10 задаёт ревью-рамку для всех переносов; закрывается после фиксации текста в `docs/features/` и может идти параллельно ST-03–ST-05.

### Риски

- **ST-04 (закрыто, ретроспектива):** после переноса критичен порядок **rest → company → usersync** в [`requires.php`](../../../local/classes/requires.php); следить за регрессиями ЛК/AJAX компании на стенде.
- **ST-03 (закрыто, ретроспектива):** неверный порядок (**`usersync`** раньше **`rest`**) давал бы отказ REST; после ST-04 добавляется риск, если **`usersync`** окажется раньше **`company`** при удалении ручных `require` классов компании.
- Регрессии цен и корзины при переносе `CatalogPriceFloor`.
- Дублирование или конфликт глобальных функций (`sendRequestB24`) при частичном рефакторинге.
- Неверный порядок загрузки обработчиков событий `main`/`catalog`.
- Неочевидные зависимости шаблонов от полного namespace `OnlineService\Site\CatalogPriceFloor`.
- **Namespace и автозагрузка:** рассинхрон путей в `Loader::registerAutoLoadClasses` и фактических файлов в `lib/`; дубли классов при переходном периоде в `local/classes/` и в модуле.
- **Минимальный `include.php`:** ошибки в регистрации классов без установщика — проявляются сразу на загрузке страницы; нужны smoke-проверки после каждого переноса.

### Блокеры закрытия ST-09/ST-10 (check-before-done)

- [ ] Во всех подключаемых модулях `eklektika.*` нет битых регистраций в `include.php`.
- [ ] В `init.php`/`requires.php` отсутствуют `require` для migrated-классов из `local/classes/{b24,site}`.
- [ ] По каждому временному исключению в зависимостях есть owner, причина, дедлайн снятия.
- [ ] Документация `docs/features/*` описывает только фактическую схему bootstrap/зависимостей.
- [ ] Smoke по usersync/company/pricing/import/orders/site выполняется без фаталов.

## Smoke-артефакт

- Текущий smoke-файл: [SMOKE-REPORT-ST09-ST10.md](./SMOKE-REPORT-ST09-ST10.md)
- Статус на 2026-04-22: **Not run** (ожидается ручной прогон пользователем в рамках ST-11 closeout)

### Митигации

- Поэтапный перенос с сохранением публичного API классов (алиасы namespace/class_alias на переходный период).
- Чек-листы ручного тестирования по сегментам в подзадачах.
- Вынос REST в один класс до переноса доменной логики.

## План обновления документации `docs/features/`

1. Поддерживать **`docs/features/local_classes_segments_and_modules.md`**: таблица модулей `eklektika.*`, файлы, запрещённые зоны; раздел **«Целевая структура»** со ссылкой на [MODULE-LAYOUT.md](./MODULE-LAYOUT.md) и правилами зависимостей между модулями (ST-10). Legacy-ссылка на [TARGET-STRUCTURE.md](./TARGET-STRUCTURE.md) — опционально в сноске.
2. Обновить **`docs/features/README.md`**: актуализировать описание (модули `local/modules/eklektika.*`, `lib/`).
3. При необходимости уточнить **`b24_integration.md`** ссылками на MODULE-LAYOUT и REST-модуль (без дублирования кода).
4. После стабилизации на стенде — согласовать обновление [`.cursor/rules/arch-rules.mdc`](../../../.cursor/rules/arch-rules.mdc) (сейчас там акцент на `local/classes/`; целевой источник истины — модули + `docs/features/`, без противоречия явному решению заказчика).

### Обязательный факт-чекинг перед обновлением `docs/features/*`

1. Сверить фактическую цепочку подключения модулей в [`local/classes/requires.php`](../../../local/classes/requires.php) с описанием в docs.
2. Сверить наличие и состав `include.php`/`lib/` для каждого модуля из [MODULE-LAYOUT.md](./MODULE-LAYOUT.md).
3. Убедиться, что docs не описывают legacy-пути как целевые (`local/classes/*` только как переходный слой).
4. Для каждого обновлённого feature-документа добавить явную ссылку на `MODULE-LAYOUT.md` или текущую подзадачу (ST-09/ST-10).

## Вход в implement-цикл (готовый блок требований)

**Режим:** continue существующего рефакторинга, без перепроектирования.

**Обязательные требования:**
- Работать только в зонах собственной разработки: `local/modules/eklektika.*/`, `local/classes/requires.php`, `local/php_interface/init.php`, `docs/features/*`, `docs/tasks/2026-04-21-refactor-local-classes-segmentation/*`.
- Не трогать: `local/modules/intec.eklectika/`, `script/crm/rest/`, код с namespace `intec\eklectika\`.
- Не менять бизнес-логику доменных классов без отдельного запроса; текущая цель — консистентная загрузка, зависимости и документация.

**Deliverables цикла:**
1. Унифицированные `include.php` для всех используемых `eklektika.*` модулей.
2. Очищенный bootstrap (`init.php`/`requires.php`) без legacy `require` migrated-классов.
3. Зафиксированная матрица зависимостей модулей + перечень временных исключений (если остались).
4. Синхронизированные `docs/features/*` по фактическому состоянию кода.
5. Smoke-отчёт по сценариям: usersync, company/manager, pricing, import hooks, deal applications, page settings/search.

**Критерий завершения цикла:**
- ST-09 и ST-10 переводятся в `done`, а чек-лист DoD этой карточки закрыт без открытых критичных рисков.

**Порядок выполнения для implement-team (без перепостановки):**
1. Сначала техническая консистентность загрузки (ST-09), затем архитектурная консистентность зависимостей (ST-10).
2. Документация обновляется после каждого закрытого RUN-шага, а не в самом конце одним блоком.
3. Любое отклонение от матрицы зависимостей оформляется как явное временное исключение в task/docs.

**Явные действия по завершении ST-04 (исполнитель):**

| Документ | Что сделать |
|----------|-------------|
| **`docs/features/company_system.md`** | Указать **`module_id` `eklektika.company`**, пути **`local/modules/eklektika.company/lib/`** для **`Company`**, **`Manager`**, **`UserGroups`**; порядок загрузки **`rest` → `company` → `usersync`** в bootstrap; что **`CatalogPriceFloor`** пока в `local/classes/site/` и связывается с компанией через **`getMaxCompanyDiscountPercentForUserGroups`** до ST-05. |
| **`docs/features/local_classes_segments_and_modules.md`** | Строка/строки в таблице модулей: **`eklektika.company`** — перечисление трёх классов и контракт скидки для pricing (**`getMaxCompanyDiscountPercentForUserGroups`**); ссылка на [MODULE-LAYOUT.md](./MODULE-LAYOUT.md). |

**Явные действия по завершении ST-05 (исполнитель):**

| Документ | Что сделать |
|----------|-------------|
| **`docs/features/local_classes_segments_and_modules.md`** | Сегмент ценообразования: модуль **`eklektika.catalog.pricing`**, путь **`lib/CatalogPriceFloor.php`**, таблица bootstrap **`requires`** — четыре модуля (**`rest` → `company` → `catalog.pricing` → `usersync`**), без ручного **`require`** **`CatalogPriceFloor`**. |
| **`docs/features/company_system.md`** | Убрать формулировку про переходный **`require`** **`CatalogPriceFloor`** из **`requires.php`**; зафиксировать связь каталога с компанией только через **`getMaxCompanyDiscountPercentForUserGroups`** и модуль **`eklektika.catalog.pricing`**. |
| **[MODULE-LAYOUT.md](./MODULE-LAYOUT.md)** | Синхронизировать §2 и §5 с фактическим переносом (**п. 3a**). |
| **[README задачи](./README.md)** | Отметить ST-05 закрытым; **текущий фокус** — [ST-06](./subtasks/06-segment-catalog-1c-import-hooks.md). |

**Явные действия по завершении ST-06 (исполнитель):**

| Документ | Что сделать |
|----------|-------------|
| **`docs/features/local_classes_segments_and_modules.md`** | Сегмент постобработки 1С: модуль **`eklektika.catalog.import`**, пути **`lib/`**, namespace **`OnlineService\Catalog\Import1c\*`**; цепочка **`requires.php`** с **`catalog.import`** между **`catalog.pricing`** и **`usersync`**; убрать упоминание логики импорта из **`init.php`**. |
| **[MODULE-LAYOUT.md](./MODULE-LAYOUT.md)** | §2 строка **`eklektika.catalog.import`**, §5 **п. 3b** — актуально после мержа кода. |
| **[README задачи](./README.md)** | Отметить ST-06 закрытым; **текущий фокус** — [ST-07](./subtasks/07-segment-orders-deal-applications.md). |
| **`docs/features/README.md`** | Опционально: одна строка про модуль постобработки импорта 1С. |

**Явные действия по завершении ST-07 (исполнитель):**

| Документ | Что сделать |
|----------|-------------|
| **`docs/features/local_classes_segments_and_modules.md`** | Сегмент заказов: модуль **`eklektika.orders.applications`**, путь **`lib/DealApplicationsService.php`** (или фактическое имя), фасады **`getApplication`** / **`addApplication`** в **`init.php`**; цепочка **`requires.php`** с **`orders.applications`** после **`catalog.import`**, перед **`usersync`** (см. [MODULE-LAYOUT §5 п. 3c](./MODULE-LAYOUT.md#5-миграция-и-загрузка-namespace-includephp-loaderregisterautoloadclasses)); убрать из таблицы «точки входа» доменную логику заявок как единственный дом **`init.php`**. |
| **[MODULE-LAYOUT.md](./MODULE-LAYOUT.md)** | §2 строка заявки → **`eklektika.orders.applications`**; §5 **п. 3c** — синхронизировать с фактическим **`requires.php`**. |
| **[README задачи](./README.md)** | Отметить ST-07 закрытым; **текущий фокус** — [ST-08](./subtasks/08-refactor-init-php-bootstrap.md). |
| **[ST-07](./subtasks/07-segment-orders-deal-applications.md)** | Статус **done** после мержа. |

## Критерии готовности задачи

- [ ] Все подзадачи закрыты (включая ST-10)
- [ ] Выполнены критерии приёмки (по сегментам — отсутствие регрессий в сценариях из подзадач)
- [ ] Обновлена документация в `docs/features/` согласно плану выше
- [ ] Фактическое размещение механики соответствует [MODULE-LAYOUT.md](./MODULE-LAYOUT.md) (модули `eklektika.*`, код в `lib/`) или задокументированы отклонения; **`local/classes/` не используется как постоянная организационная зона**
