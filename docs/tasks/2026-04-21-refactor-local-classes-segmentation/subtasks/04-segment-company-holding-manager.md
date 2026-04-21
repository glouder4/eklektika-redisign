# ST-04: Сегмент компаний, холдингов и менеджеров

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Сгруппировать логику компаний (включая холдинговую модель и параметры вроде скидки через группы), менеджеров и пользовательских групп в модуле **`eklektika.company`** с кодом в **`local/modules/eklektika.company/lib/`** и чётким публичным API для шаблонов, `director/`, компонентов заказа и AJAX. Карта модулей: [MODULE-LAYOUT.md](../MODULE-LAYOUT.md).

**Контекст после ST-03:** контактный AJAX (`UPDATE_CONTACT`, `UPDATE_BATCH_USERS`, `DELETE_CONTACT`) вынесен в модуль **`eklektika.b24.usersync`** (**`ContactAjaxFacade`**); в **`local/classes/ajax.php`** для этой подзадачи остаются экшены **`UPDATE_GROUP`**, **`DELETE_COMPANY`**, **`UPDATE_COMPANY`**, **`UPDATE_MANAGER`**, **`SYNC_COMPANY_CONTACTS`** (файл в репозитории).

## Контракт для ST-05 (зафиксировать в коде и в `docs/features`)

В классе **`OnlineService\Site\Company`** уже есть публичный метод:

```php
public static function getMaxCompanyDiscountPercentForUserGroups(array $userGroupIds): float
```

Исполнитель ST-04 **не меняет сигнатуру и семантику** без согласования со ST-05; **`CatalogPriceFloor`** и шаблоны продолжают вызывать этот метод до выноса pricing. В ST-05 допускается только обёртка/интерфейс поверх того же контракта, если модуль **`eklektika.catalog.pricing`** не должен ссылаться на класс напрямую.

## Вне scope ST-04

- ~~**`local/classes/site/CatalogPriceFloor.php`** — не переносить в ST-04~~ — после **ST-05** файл в модуле **`eklektika.catalog.pricing`**, см. [ST-05](./05-segment-catalog-pricing-discount.md).

## Описание работ

1. Создать **`local/modules/eklektika.company/`** с **`lib/`** и минимальным **`include.php`**: **`Loader::registerAutoLoadClasses`** для **`OnlineService\Site\Company`**, **`OnlineService\Site\Manager`**, **`OnlineService\Site\UserGroups`** с путями к файлам под **`lib/`** (namespace **сохранять** до [ST-09](./09-modules-eklektika-scaffold-and-migration.md)).
2. Перенести **`Company.php`**, **`Manager.php`**, **`UserGroups.php`** из [`local/classes/site/`](../../../local/classes/site/) в **`lib/`** (подпапки по обязанности — по усмотрению, без изменения внешнего поведения).
3. Обновить [`local/classes/requires.php`](../../../local/classes/requires.php): **`Loader::includeModule('eklektika.company')`** **между** **`eklektika.b24.rest`** и **`eklektika.b24.usersync`** (целевая цепочка **`rest` → `company` → `usersync`**); удалить три **`require_once`** на перенесённые файлы; **`CatalogPriceFloor`** не трогать.
4. Проверить [`local/php_interface/init.php`](../../../local/php_interface/init.php): при дублировании порядка подключения модулей — согласовать с `requires.php` (без нарушения цепочки).
5. Структурировать код внутри модуля по обязанностям (ИБ, B24, реквизиты), не меняя внешнее поведение.
6. Централизовать REST-вызовы компании/реквизитов через **`eklektika.b24.rest`** (клиент из ST-02).
7. Обновить точки входа: [`local/classes/ajax.php`](../../../local/classes/ajax.php) (перечисленные экшены), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php), [`local/components/bitrix/sale.order.ajax/class.php`](../../../local/components/bitrix/sale.order.ajax/class.php) — только при необходимости смены импортов/`use` (namespace классов домена тот же до ST-09).

## Технические детали

- Компоненты/модули:
  - [`local/modules/eklektika.company/lib/Company.php`](../../../local/modules/eklektika.company/lib/Company.php)
  - [`local/modules/eklektika.company/lib/Manager.php`](../../../local/modules/eklektika.company/lib/Manager.php)
  - [`local/modules/eklektika.company/lib/UserGroups.php`](../../../local/modules/eklektika.company/lib/UserGroups.php)
  - [`local/classes/ajax.php`](../../../local/classes/ajax.php) (часть экшенов)
  - [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php)
- Изменяемые файлы/области:
  - **`local/modules/eklektika.company/lib/`**, **`include.php`**
  - [`local/classes/requires.php`](../../../local/classes/requires.php)
  - при необходимости [`local/php_interface/init.php`](../../../local/php_interface/init.php)
  - потребители в `local/components/`, `local/templates/` при смене путей подключения

## Зависимости

- Блокируется:
  - ST-02
  - ST-03 — **выполнена** (usersync и контактный AJAX отделены; домен компании не смешивается с переносом контакта)
- Блокирует:
  - ST-05 (контракт скидки и перенос **`CatalogPriceFloor`**)
  - финальную унификацию в ST-09

## Риски и митигации

| Риск | Митигация |
|------|-----------|
| **`usersync` раньше `company`** после удаления `require` — **`RegisterUserCompany`** не находит **`Company`** | Строго соблюдать порядок **`Loader::includeModule`** в **`requires.php`**; smoke после деплоя: регистрация / сценарий usersync |
| Дубли определения класса (старый путь + модуль) | Удалить исходники из **`local/classes/site/`** после переноса **одним коммитом** с подключением модуля |
| Регрессии AJAX компании/менеджера | Ручной чек по экшенам **`UPDATE_*`**, **`DELETE_COMPANY`**, **`SYNC_COMPANY_CONTACTS`**, **`UPDATE_GROUP`** |
| Неочевидные `require` вне `requires.php` | Поиск по проекту по имени файла/класса перед удалением |

## Критерии приёмки

- [x] Создан модуль **`local/modules/eklektika.company/`** с **`lib/`** и минимальным **`include.php`**
- [x] В **`include.php`** зарегистрированы классы **`OnlineService\Site\Company`**, **`Manager`**, **`UserGroups`** через **`Loader::registerAutoLoadClasses`**
- [x] Файлы **`Company.php`**, **`Manager.php`**, **`UserGroups.php`** физически в **`lib/`**; копии под **`local/classes/site/`** удалены
- [x] В **`requires.php`** порядок: **`eklektika.b24.rest` → `eklektika.company` → `eklektika.b24.usersync`**; три **`require_once`** на домен компании удалены; **`CatalogPriceFloor`** подключается по-прежнему
- [x] Публичный контракт **`getMaxCompanyDiscountPercentForUserGroups(array): float`** сохранён (имя, аргументы, возврат); упомянут в отчёте/ST-05 как опора для pricing
- [ ] Операции компании/менеджера/группы и синхронизация контактов компании работают как до рефакторинга (AJAX + `director/` + заказ при наличии сценариев) — **ручная проверка на стенде**
- [ ] Холдинговые связи и поля **`B24_ID`** не ломаются (сверка с [company_system.md](../../../features/company_system.md)) — **ручная проверка**
- [x] Обновлены **`docs/features/company_system.md`** и **`docs/features/local_classes_segments_and_modules.md`** по плану в [README § «План обновления документации»](../README.md#план-обновления-документации-docsfeatures)

## Проверка

- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - Редактирование компании в ЛК; сценарии из **`director/`**; оформление заказа с профилем компании; действия AJAX по списку экшенов ST-04

## Документация

- Изученные документы:
  - `docs/features/company_system.md`
  - `docs/features/user_company_methods.md`
- Что обновить:
  - **`docs/features/company_system.md`** — модуль, пути `lib/`, bootstrap, связь с **`CatalogPriceFloor`** через **`getMaxCompanyDiscountPercentForUserGroups`**
  - **`docs/features/local_classes_segments_and_modules.md`** — строка **`eklektika.company`**, контракт скидки
- Что создать (если нужно):
  - нет

## Статус

- done (ожидает стендовой приёмки по пунктам «операции компании» и «B24_ID»)
