# Сегменты `local/classes` и границы модулей `eklektika.*`

## Назначение документа

Единая ссылочная карта доменных сегментов в зонах собственной разработки (`local/classes`, bootstrap в `local/php_interface`, подключение через `requires.php` / `events.php`) и связей с Bitrix24. Документ закрывает **ST-01** задачи по рефакторинг-сегментации; точные имена модулей `eklektika.*` утверждаются отдельно (**ST-09**).

Полный план работ, риски и порядок миграции: [`docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`](../tasks/2026-04-21-refactor-local-classes-segmentation/README.md) (здесь не дублируются длинные блоки).

## Таблица сегментов

| Сегмент | Назначение | Ключевые файлы / точки входа | Связь с Bitrix24 |
|--------|-------------|------------------------------|-------------------|
| **Пользователь ↔ CRM** | Регистрация, обновление профиля, удаление; синхронизация с контактом CRM | [`local/classes/events.php`](../../local/classes/events.php), [`local/classes/b24/RegisterUserCompany.php`](../../local/classes/b24/RegisterUserCompany.php), [`local/classes/b24/User.php`](../../local/classes/b24/User.php) | REST `crm.contact.*` и смежные операции |
| **Компании, холдинги, менеджеры** | ИБ компаний, холдинги, менеджеры, пользователи компании | [`local/classes/site/Company.php`](../../local/classes/site/Company.php), [`local/classes/site/Manager.php`](../../local/classes/site/Manager.php), [`local/classes/site/UserGroups.php`](../../local/classes/site/UserGroups.php), [`director/person/add-new-person-action.php`](../../director/person/add-new-person-action.php), [`local/classes/ajax.php`](../../local/classes/ajax.php) | REST `crm.company.*`, `crm.contact.company.*`, `crm.requisite.*`, файлы CRM |
| **Ценообразование и «скидка компании»** | Пол цены, рекламная/оптовая база, нижняя граница; групповая скидка из контекста компании | [`local/classes/site/CatalogPriceFloor.php`](../../local/classes/site/CatalogPriceFloor.php); вызовы из шаблонов каталога/корзины; [`local/classes/requires.php`](../../local/classes/requires.php) (bootstrap класса) | Обычно без прямого REST на витрине |
| **HTTP-слой и конфигурация B24** | Единый способ вызова REST, базовый URL | Глобальные `sendRequestB24`, `sendRequest` в [`local/php_interface/init.php`](../../local/php_interface/init.php); [`local/classes/b24/Request.php`](../../local/classes/b24/Request.php) | Все REST-вызовы сайта |
| **Каталог: постобработка после импорта 1С** | Сбор свойства «типы нанесения» после обмена | `updateProperties`, обработчик `OnSuccessCatalogImport1C`, константа `IBLOCK_ID_1C` в [`local/php_interface/init.php`](../../local/php_interface/init.php) | Нет |
| **Заказы и заявки из сделок B24** | Строки заявок в заказ Sale | `getApplication`, `addApplication`, вебхук `kit.productapplications.*` в [`local/php_interface/init.php`](../../local/php_interface/init.php) | Специализированный REST через вебхук |
| **Поиск** | Модификация индексирования (stemming) | [`local/classes/requires.php`](../../local/classes/requires.php) → [`local/php_interface/classes/handlers/search/stemming.php`](../../local/php_interface/classes/handlers/search/stemming.php) (`OnlineService\Classes\Handlers\Search\Stemming`) | Нет |
| **Настройки контента (Page editor)** | Свойства элемента ИБ как настройки страницы | Класс `PageSettings` в [`local/php_interface/init.php`](../../local/php_interface/init.php) | Нет |

## Точки входа bootstrap

| Файл | Роль |
|------|------|
| [`local/php_interface/init.php`](../../local/php_interface/init.php) | Константы B24 (`URL_B24` и др.), глобальные **`sendRequestB24`**, **`sendRequest`**, cookie/настройки окружения, обработчики каталога (`updateProperties`), заявки (`getApplication` / `addApplication`), класс **`PageSettings`**, подключение сторонних модулей по правилам проекта (имена не фиксируются в этой карте), вызов `require` для [`local/classes/requires.php`](../../local/classes/requires.php). |
| [`local/classes/requires.php`](../../local/classes/requires.php) | Жёсткие `require` ключевых классов (`b24`, `site`), ранний вызов `CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`, регистрация поискового обработчика и автозагрузки `Stemming`. |
| [`local/classes/events.php`](../../local/classes/events.php) | Обработчики событий `main` для пользователя и связки с CRM. |

### Глобальные REST-хелперы

Определены в [`local/php_interface/init.php`](../../local/php_interface/init.php): **`sendRequestB24($method, $params, …)`**, **`sendRequest($params, …)`** — базовый транспорт для интеграций; доменная логика остаётся в классах `local/classes/b24/` и `local/classes/site/`.

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
| `eklektika.orders.applications` | Заявки из сделки в заказ | Функции из `init.php` |

## Зависимости между сегментами (кратко)

- **Catalog pricing** → **Company** (скидка по группам компании): при выносе модулей — узкий контракт (например, резолвер скидки), без притягивания всего `Company` в каталог.
- **User CRM sync** → **B24 REST layer** (сначала консолидировать транспорт).
- **Company** → **B24 REST** для обратной синхронизации полей и реквизитов.
- **Orders applications** → модуль Sale + вебхук B24; изолировать от пользовательской синхронизации.

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

1. Глобальный HTTP-слой REST и константы в `init.php` (`sendRequestB24`, `sendRequest`).
2. Постобработка каталога после импорта 1С (`updateProperties`, `OnSuccessCatalogImport1C`).
3. Заявки из сделки B24 ↔ заказ Sale (`getApplication`, `addApplication`, вебхук).
4. Поиск: обработчик `Stemming` и регистрация в `requires.php`.
5. Класс `PageSettings` и сценарии контента в `init.php`.

---

*Документ: ST-01 · последнее обновление: 2026-04-21*
