# ST-05: Сегмент ценообразования и скидки компании

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Выделить домен ценообразования (класс в [`local/modules/eklektika.catalog.pricing/lib/CatalogPriceFloor.php`](../../../local/modules/eklektika.catalog.pricing/lib/CatalogPriceFloor.php)) в модуле **`eklektika.catalog.pricing`** с кодом в **`local/modules/eklektika.catalog.pricing/lib/`** с минимальными зависимостями от CRM-транспорта и чёткой зависимостью от контракта «скидка компании», описанного в ST-04 ([MODULE-LAYOUT.md](../MODULE-LAYOUT.md), [ST-10](./10-architecture-segment-independence-and-core-boundary.md)).

## Описание работ (согласованный план исполнителя)

1. **Модуль `eklektika.catalog.pricing`:** **`local/modules/eklektika.catalog.pricing/`** с минимальным **`include.php`**: **`Loader::registerAutoLoadClasses`** для класса **`OnlineService\Site\CatalogPriceFloor`** → **`lib/CatalogPriceFloor.php`** (перенесено из **`local/classes/site/`**; без смены namespace до [ST-09](./09-modules-eklektika-scaffold-and-migration.md)).
2. **`requires.php` (после ST-04):** дополнить порядок подключения — **`Loader::includeModule('eklektika.catalog.pricing')`** сразу после **`eklektika.company`** и **до** **`eklektika.b24.usersync`** (итого: **`rest` → `company` → `catalog.pricing` → `usersync`**); **убрать** **`require_once`** на старый **`CatalogPriceFloor.php`**; **сохранить** вызов **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`** после загрузки модуля pricing (класс доступен через автозагрузку модуля).
3. **`init.php`:** оставить **`CatalogPriceFloor::bootstrap()`** после подключения [`requires.php`](../../../local/classes/requires.php); порядок **сохранить**: сначала эффекты из **`requires`** (**`markCompositeNonCacheable…`** при загрузке страницы через цепочку requires), затем **`bootstrap()`** там, где он уже вызывается в **`init.php`** — без перестановки относительно текущего поведения.
4. **Зависимость от домена компании:** только узкий контракт ST-04 — **`Company::getMaxCompanyDiscountPercentForUserGroups`**; не подтягивать прочие методы **`Company`** и не вводить зависимости **`catalog.pricing` → `b24.usersync`**.
5. Шаблоны: при **`namespace`** без изменений правки вызовов не требуются; инвентаризация **`class_exists` / use** в [`local/templates/`](../../../local/templates/) на предмет регрессий — по чек-листу проверки.

## Технические детали

- Компоненты/модули:
  - **Новые:** `local/modules/eklektika.catalog.pricing/include.php`, `lib/CatalogPriceFloor.php`
  - **Источник переноса:** ~~`local/classes/site/CatalogPriceFloor.php`~~ — удалён после переноса
  - [`local/classes/requires.php`](../../../local/classes/requires.php) — порядок модулей + отказ от ручного `require` класса пола цен
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — только подтверждение порядка **`…::bootstrap()`** после requires
  - [`local/modules/eklektika.company/lib/Company.php`](../../../local/modules/eklektika.company/lib/Company.php) — контракт **`getMaxCompanyDiscountPercentForUserGroups`** для **`CatalogPriceFloor`**
- Изменяемые файлы/области:
  - `local/modules/eklektika.catalog.pricing/` (новый модуль)
  - `local/classes/requires.php`
  - при необходимости — шаблоны только если меняется имя класса или namespace (в ST-05 не планируется)

## Зависимости

- Блокируется:
  - ST-04 (контракт **`getMaxCompanyDiscountPercentForUserGroups`**)
- Блокирует:
  - нет прямого; следующий спринт **[ST-06](./06-segment-catalog-1c-import-hooks.md)** (постобработка 1С) логически следует после стабилизации bootstrap и таблицы модулей — в [README](../README.md) после закрытия ST-05 переносится фокус на ST-06

## Критерии приёмки

**Код и загрузка**

- [x] Создан модуль **`eklektika.catalog.pricing`** с **`include.php`** и **`Loader::registerAutoLoadClasses`** для **`OnlineService\Site\CatalogPriceFloor`** → **`lib/CatalogPriceFloor.php`**
- [x] В [`requires.php`](../../../local/classes/requires.php) порядок: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.b24.usersync`**; **нет** `require_once` старого пути **`site/CatalogPriceFloor.php`**
- [x] После подключения **`eklektika.catalog.pricing`** выполняется **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`** (как сейчас по смыслу раннего побочного эффекта при загрузке requires)
- [x] В **`init.php`** сохранён вызов **`CatalogPriceFloor::bootstrap()`** после requires; относительный порядок от **`markComposite…`** к **`bootstrap()`** не ломает прежнее поведение

**Домен и зависимости**

- [x] Получение «скидки компании» для расчёта пола цен идёт только через **`Company::getMaxCompanyDiscountPercentForUserGroups`** (или вызов этого метода без обходных прямых зависимостей на остальной **`Company`**)
- [x] Нет циклических и лишних зависимостей: **`catalog.pricing`** не тянет **`eklektika.b24.usersync`**; связь с компанией — только через контракт ST-04

**Поведение и регрессии**

- [ ] Цены на карточке товара, в списке и в корзине на выборке товаров/пользователей совпадают с эталоном до изменений (ручная сверка)
- [ ] Скидка по группам компании применяется как до рефакторинга

**Документация (закрытие ST-05)**

- [x] Обновлён [`docs/features/local_classes_segments_and_modules.md`](../../../features/local_classes_segments_and_modules.md): модуль **`eklektika.catalog.pricing`**, путь к **`CatalogPriceFloor`**, цепочка **`requires`** с четырьмя модулями
- [x] При необходимости уточнён [`docs/features/company_system.md`](../../../features/company_system.md): связь **`CatalogPriceFloor`** с компанией только через **`getMaxCompanyDiscountPercentForUserGroups`**; убрано/обновлено упоминание переходного **`require`** **`CatalogPriceFloor`** из **`requires.php`**
- [x] Обновлены [MODULE-LAYOUT.md](../MODULE-LAYOUT.md) (§ миграция/bootstrap после ST-05) и карточка [README задачи](../README.md): текущий фокус — **следующий спринт ST-06** ([постобработка 1С](./06-segment-catalog-1c-import-hooks.md))

## Проверка

- Unit/интеграционные проверки:
  - при наличии — расчёт цены для фикстуры товара/пользователя
- Ручной сценарий:
  - Каталог (несколько типов цен), корзина, мини-корзина, оформление заказа с пользователем с корпоративной скидкой

## Документация

- Изученные документы:
  - [`docs/features/company_system.md`](../../../features/company_system.md)
  - [`docs/features/local_classes_segments_and_modules.md`](../../../features/local_classes_segments_and_modules.md)
  - [MODULE-LAYOUT.md](../MODULE-LAYOUT.md)
- Что обновить:
  - **`local_classes_segments_and_modules.md`** — строка сегмента ценообразования, таблица bootstrap **`requires.php`**, при необходимости блок про глобальные REST-хелперы (убрать «остаток site/CatalogPriceFloor до ST-05» после выноса)
  - **`company_system.md`** — секция про переходный `require` **`CatalogPriceFloor`** заменена на указание модуля **`eklektika.catalog.pricing`** и контракта скидки
  - **MODULE-LAYOUT.md** — пункт про bootstrap post-ST-05 (четыре модуля, без ручного require pricing)
  - **README задачи** — статус ST-05, фокус на ST-06
- Что создать (если нужно):
  - отдельный `docs/features/catalog_pricing_floor.md` — не требуется, пока объём не вырос (по усмотрению после ST-06+)

## Статус

- done (ожидает ручной сверки цен и корпоративной скидки на стенде)
