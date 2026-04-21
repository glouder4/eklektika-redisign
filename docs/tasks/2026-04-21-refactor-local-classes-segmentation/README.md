# Рефакторинг сегментации `local/classes` и подготовка модулей `eklektika.*`

## Метаданные

- ID: TASK-2026-04-21-refactor-local-classes-segmentation
- Статус: planned
- Приоритет: high
- Дата создания: 2026-04-21
- Ответственный: нет

## Ссылки на источники

- Issue: нет
- PRD/Док: нет
- Доп. контекст: оркестрация `/orchestrate`, правила [.cursor/rules/arch-rules.mdc](../../../.cursor/rules/arch-rules.mdc), индекс [docs/features/README.md](../../features/README.md)

## Цель

Разобрать «плоский» состав `local/classes/` и смешанную загрузку через [`local/php_interface/init.php`](../../../local/php_interface/init.php) и [`local/classes/requires.php`](../../../local/classes/requires.php): выделить **независимые доменные сегменты** (внутри сайта и точки интеграции с Bitrix24), зафиксировать **границы будущих модулей** `local/modules/eklektika.*` без затрагивания сторонних модулей вне собственного префикса, подготовить **пошаговую миграцию** с минимизацией регрессий в каталоге и CRM-синхронизации.

Ожидаемый эффект: проще сопровождать код, ясные зависимости между блоками, возможность включать логику как отдельные Bitrix-модули без дальнейшего разрастания `init.php`.

## Карта доменов / сегментов

| Сегмент | Назначение | Ключевые точки входа / файлы | Связь с Bitrix24 |
|--------|-------------|------------------------------|------------------|
| **Пользователь ↔ CRM** | Регистрация, обновление профиля, удаление; синхронизация с контактом CRM | [`local/classes/events.php`](../../../local/classes/events.php), [`local/classes/b24/RegisterUserCompany.php`](../../../local/classes/b24/RegisterUserCompany.php), [`local/classes/b24/User.php`](../../../local/classes/b24/User.php) | REST `crm.contact.*`, связанные операции |
| **Компании, холдинги, менеджеры** | ИБ компаний, холдинговая структура, менеджеры, пользователи компании | [`local/classes/site/Company.php`](../../../local/classes/site/Company.php), [`local/classes/site/Manager.php`](../../../local/classes/site/Manager.php), [`local/classes/site/UserGroups.php`](../../../local/classes/site/UserGroups.php), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php), [`local/classes/ajax.php`](../../../local/classes/ajax.php) | REST `crm.company.*`, `crm.contact.company.*`, `crm.requisite.*`, файлы CRM |
| **Ценообразование и «скидка компании»** | Пол цены, рекламная/оптовая база, нижняя граница; использование групповой скидки из контекста компании | [`local/classes/site/CatalogPriceFloor.php`](../../../local/classes/site/CatalogPriceFloor.php), шаблоны каталога/корзины (вызовы класса), связь с `Company` по группам | Обычно без прямого REST на витрине; бизнес-правила сайта |
| **HTTP-слой и конфигурация B24** | Единый способ вызова REST, базовый URL, вебхуки | Глобальные `sendRequestB24`, `sendRequest`, [`local/classes/b24/Request.php`](../../../local/classes/b24/Request.php), константы в [`init.php`](../../../local/php_interface/init.php) | Все REST-вызовы |
| **Каталог: постобработка после импорта 1С** | Сбор свойства «типы нанесения» в одно поле после обмена | `updateProperties`, `OnSuccessCatalogImport1C` в [`init.php`](../../../local/php_interface/init.php), `IBLOCK_ID_1C` | нет |
| **Заказы и заявки из сделок B24** | Подтягивание строк заявок в заказ Sale | `getApplication`, `addApplication`, вебхук `kit.productapplications.*` в [`init.php`](../../../local/php_interface/init.php) | Специализированный REST через вебхук |
| **Поиск** | Модификация индексирования (stemming) | [`local/classes/requires.php`](../../../local/classes/requires.php) → `OnlineService\Classes\Handlers\Search\Stemming` | нет |
| **Настройки контента (Page editor)** | Чтение свойств элемента ИБ как настроек страницы | класс `PageSettings` и хелперы в [`init.php`](../../../local/php_interface/init.php) | нет |

**Дополнительно выделенные области** (поверх трёх известных блоков): постобработка каталога после 1С; интеграция заявок сделки с корзиной; глобальный HTTP-слой REST в `init.php`; поисковый обработчик в `requires.php`; класс `PageSettings` в `init.php`; широкое использование `CatalogPriceFloor` в шаблонах как отдельный домен ценообразования.

### Предлагаемые границы модулей `local/modules/eklektika.*` (псевдонимы имён)

| Предполагаемый модуль | Ответственность | Примечание |
|----------------------|-----------------|------------|
| `eklektika.core` или `eklektika.site` | Общий bootstrap, автозагрузка, конфиг домена | Не смешивать с модулями вне префикса `eklektika.*` (сторонние поставщики) |
| `eklektika.b24.rest` | Клиент REST, конфиг URL/ключей (без бизнес-правил CRM) | Замена размазанных `sendRequestB24` |
| `eklektika.b24.usersync` | Регистрация/обновление пользователя ↔ контакт | Текущие `RegisterUserCompany`, часть `User` |
| `eklektika.company` | Компании, холдинги, менеджеры, группы | `Company`, `Manager`, `UserGroups` |
| `eklektika.catalog.pricing` | `CatalogPriceFloor` | Зависимость от контракта «скидка из компании» |
| `eklektika.catalog.import` | Обработчики после импорта 1С | `updateProperties` и аналоги |
| `eklektika.orders.applications` | Заявки из сделки в заказ | Функции из `init.php` |

Точные имена модулей утверждаются в подзадаче ST-09; допускается промежуточный этап только с папками под `local/classes/Segment/` до создания Install-модулей.

### Зависимости между сегментами (кратко)

- **Catalog pricing** → **Company** (получение максимальной скидки по группам пользователя компании): при выносе модулей ввести узкий интерфейс (например, `CompanyDiscountResolver`), чтобы не тянуть весь `Company` в каталог.
- **User CRM sync** → **B24 REST layer** (обязательно первым консолидировать транспорт).
- **Company** → **B24 REST** для обратной синхронизации полей и реквизитов.
- **Orders applications** → **Sale** + **B24 webhook** (изолировать от пользовательской синхронизации).

## Границы (Scope)

### In scope

- Рефакторинг и разнесение по папкам/namespace в [`local/classes/`](../../../local/classes/), [`local/php_interface/init.php`](../../../local/php_interface/init.php), [`local/classes/requires.php`](../../../local/classes/requires.php).
- Уточнение точек входа в [`director/`](../../../director/), [`personal/`](../../../personal/), собственные компоненты [`local/components/`](../../../local/components/), шаблоны [`local/templates/`](../../../local/templates/) — только как потребители перенесённых классов (обновление `use`/путей при необходимости).
- Документация в [`docs/features/`](../../features/): новый обзорный документ по сегментам + обновление индекса.
- Проектирование и каркас модулей `local/modules/eklektika.*` без изменений сторонних модулей вне этого префикса (см. arch-rules).

### Out of scope

- Любые изменения в зонах, перечисленных в [`.cursor/rules/arch-rules.mdc`](../../../.cursor/rules/arch-rules.mdc) как сторонние или запрещённые для правок в рамках этой задачи (конкретные пути — только в arch-rules).
- Переписывание бизнес-логики сторонних модулей Битрикс.

## План внедрения

1. Зафиксировать карту доменов и обновить `docs/features/` (ST-01).
2. Выделить слой B24 HTTP/конфигурации без изменения поведения REST (ST-02).
3. Вынести сегмент синхронизации пользователя с CRM (обработчики `events.php`, `RegisterUserCompany`, часть `User`) (ST-03).
4. Вынести сегмент компаний/холдингов/менеджеров и AJAX-API (ST-04).
5. Изолировать сегмент ценообразования `CatalogPriceFloor` и контракт скидки (ST-05).
6. Изолировать постобработку импорта 1С (ST-06).
7. Изолировать сценарии заказ↔заявки сделки (ST-07).
8. Упростить `init.php`/`requires.php`: тонкие bootstrap-стабы, перенос `PageSettings` и инфраструктурных кусков (ST-08).
9. Создать каркас модулей `eklektika.*` и план переноса классов в `include.php` (ST-09).

### Стратегия `init.php`

- **Текущее состояние:** смешаны доменные cookie, замеры Server-Timing, константы B24, глобальные функции REST, класс `PageSettings`, импорт 1С, подключение сторонних модулей (без перечисления имён в задаче), `requires.php`, точечный bootstrap `CatalogPriceFloor`.
- **Целевое:** короткий пролог: настройки окружения сайта + вызов фасада `Eklektika\Site\Bootstrap::register()` (или нескольких модульных `include.php`), где каждый сегмент регистрирует только свои обработчики.
- **Принцип:** не переносить новую бизнес-логику в `init.php`; только подключение модулей и инфраструктура.

### Порядок миграции (минимизация риска)

1. Документация и инвентаризация (ST-01).
2. REST-слой + конфиг (ST-02) — основа для остальных.
3. User CRM sync (ST-03).
4. Company / holding / manager (ST-04).
5. Catalog pricing (ST-05) — регрессии на витрине; тестировать после стабилизации Company-контракта.
6. Import hooks (ST-06).
7. Orders applications (ST-07).
8. Разгрузка `init.php` (ST-08).
9. Модули `eklektika.*` (ST-09).

## Подзадачи

- [x] [ST-01: Документирование карты доменов и границ модулей](./subtasks/01-documentation-domain-map.md)
- [ ] [ST-02: Слой B24 REST и конфигурация вызовов](./subtasks/02-b24-http-layer-and-config.md)
- [ ] [ST-03: Сегмент синхронизации пользователя с CRM](./subtasks/03-segment-user-registration-crm-sync.md)
- [ ] [ST-04: Сегмент компаний, холдингов и менеджеров](./subtasks/04-segment-company-holding-manager.md)
- [ ] [ST-05: Сегмент ценообразования и скидки компании](./subtasks/05-segment-catalog-pricing-discount.md)
- [ ] [ST-06: Постобработка каталога после импорта 1С](./subtasks/06-segment-catalog-1c-import-hooks.md)
- [ ] [ST-07: Заявки из сделки B24 и корзина](./subtasks/07-segment-orders-deal-applications.md)
- [ ] [ST-08: Рефакторинг bootstrap init.php и requires.php](./subtasks/08-refactor-init-php-bootstrap.md)
- [ ] [ST-09: Каркас модулей eklektika.* и перенос пространств имён](./subtasks/09-modules-eklektika-scaffold-and-migration.md)

## Зависимости и риски

### Зависимости

- Работа ST-03–ST-07 опирается на выделенный транспорт REST из ST-02.
- ST-05 зависит от контракта со ST-04 по API скидки компании (или выделенного интерфейса).
- ST-09 логически после стабилизации структуры папок в ST-03–ST-08.

### Риски

- Регрессии цен и корзины при переносе `CatalogPriceFloor`.
- Дублирование или конфликт глобальных функций (`sendRequestB24`) при частичном рефакторинге.
- Неверный порядок загрузки обработчиков событий `main`/`catalog`.
- Неочевидные зависимости шаблонов от полного namespace `OnlineService\Site\CatalogPriceFloor`.

### Митигации

- Поэтапный перенос с сохранением публичного API классов (алиасы namespace/class_alias на переходный период).
- Чек-листы ручного тестирования по сегментам в подзадачах.
- Вынос REST в один класс до переноса доменной логики.

## План обновления документации `docs/features/`

1. Создать **`docs/features/local_classes_segments_and_modules.md`**: таблица сегментов, файлы, границы модулей `eklektika.*`, запрещённые зоны, диаграмма зависимостей (текстом).
2. Обновить **`docs/features/README.md`**: ссылка на новый документ в разделе архитектуры / сопровождения.
3. При необходимости уточнить **`b24_integration.md`** и **`company_system.md`** ссылками на новую структуру (без дублирования больших блоков кода).

## Критерии готовности задачи

- [ ] Все подзадачи закрыты
- [ ] Выполнены критерии приёмки (по сегментам — отсутствие регрессий в сценариях из подзадач)
- [ ] Обновлена документация в `docs/features/` согласно плану выше
