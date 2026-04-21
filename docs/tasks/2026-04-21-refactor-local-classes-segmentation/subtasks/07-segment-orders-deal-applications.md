# ST-07: Заявки из сделки B24 и корзина

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Вынести функции `getApplication`, `addApplication` и связанный с ними вызов REST `kit.productapplications.deal.productrows.get` из [`local/php_interface/init.php`](../../../local/php_interface/init.php) в модуле **`eklektika.orders.applications`** с кодом в **`local/modules/eklektika.orders.applications/lib/`**, используя клиент REST из **`eklektika.b24.rest`** и не смешивая с пользовательской синхронизацией CRM ([MODULE-LAYOUT.md](../MODULE-LAYOUT.md)).

## Описание работ

1. Создать **`local/modules/eklektika.orders.applications/`** с **`lib/`** и минимальным **`include.php`**; класс **`DealApplicationsService`** (или аналог): вызов **`kit.productapplications.deal.productrows.get`** через **`OnlineService\B24\RestClient::getKitWebhookPrefix()`** и транспорт без дублирования «сырого» curl в доменном коде — при отсутствии GET-пути в **`RestClient`** добавить узкий метод в **`eklektika.b24.rest`** (например, GET по полному URL kit-вебхука с декодированием JSON), затем использовать его из сервиса.
2. Вынести из **`addApplication`** вложенные **`findItem`**, **`setPrice`**, **`updateOrder`** в методы класса (или маленькие классы под **`lib/`**), без вложенных **`function`** внутри функции.
3. В **[`local/php_interface/init.php`](../../../local/php_interface/init.php)** оставить только обратную совместимость: глобальные **`getApplication($dl, $ord)`** / **`addApplication($dl, $ord)`** как тонкие обёртки, делегирующие в сервис модуля (или эквивалентные глобальные функции-обёртки без дублирования тела).
4. **[`local/classes/requires.php`](../../../local/classes/requires.php):** **`Loader::includeModule('eklektika.orders.applications')`** — **после** **`eklektika.b24.rest`** (обязательная зависимость для **`RestClient`**). Логичная позиция в цепочке: **после `catalog.import`, до `usersync`** — модуль не зависит от **`usersync`** / **`catalog.import`**; **`sale`** / **`iblock`** подключаются в рантайме внутри методов (**`CModule::IncludeModule`** / **`Loader::includeModule`**), не в **`requires.php`**.
5. Найти все вызовы **`addApplication`** / **`getApplication`** в репозитории; при необходимости оставить глобальные имена без изменений вызовов. Запись логов в файлы под **`DOCUMENT_ROOT`** — убрать или сделать конфигурируемой/отключаемой (по согласованию с заказчиком).

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — только фасады **`getApplication`**, **`addApplication`**
  - [`local/modules/eklektika.b24.rest/lib/RestClient.php`](../../../local/modules/eklektika.b24.rest/lib/RestClient.php) — префикс kit, при необходимости расширение для GET kit-методов
  - [`local/php_interface/b24_integration_config.php`](../../../local/php_interface/b24_integration_config.php) — **`B24_REST_WEBHOOK_KIT`** (ST-02), без литералов секретов в **`orders.applications`**
- Изменяемые файлы/области:
  - `local/modules/eklektika.orders.applications/lib/` (например **`DealApplicationsService.php`**), **`include.php`**
  - при необходимости — **`eklektika.b24.rest`** (один метод транспорта для GET kit URL)
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php), [`local/classes/requires.php`](../../../local/classes/requires.php)

## Зависимости

- Блокируется:
  - ST-02 (конфиг вебхуков и HTTP)
- Блокирует:
  - финальную зачистку `init.php` в ST-08

## Критерии приёмки

- [x] Логика заявок сделки и корзины находится в **`local/modules/eklektika.orders.applications/lib/`** (класс сервиса + вспомогательные классы при необходимости).
- [x] HTTP к B24 для kit: **`getKitWebhookPrefix()`** + вызов через **`RestClient::callKitRestGet`** в **`eklektika.b24.rest`**, без **`curl_*`** в **`orders.applications`**.
- [x] **`findItem`**, **`setPrice`**, **`updateOrder`** — методы класса (**`ensureCatalogItems`**, **`setPriceForProduct`**, **`updateOrderBasket`**), не вложенные функции.
- [x] В **`init.php`** остаются только совместимые **`getApplication($dl, $ord)`** / **`addApplication($dl, $ord)`**, делегирующие в сервис.
- [x] В **`requires.php`** порядок: **`… catalog.import → eklektika.orders.applications → eklektika.b24.usersync`**.
- [x] Поведение сценария добавления строк заявок в заказ: **явно изменено:** логи в корень сайта **по умолчанию отключены**; для включения — **`define('EKLEKTIKA_DEAL_APPLICATIONS_DEBUG_LOG', true);`** до вызова сценария (например в **`init.php`** после подключения **`requires.php`**). **`getApplication`** без этого флага — **no-op** (нет запроса к B24 и нет записи лога; раньше всегда писался **`get-items-log.txt`**). Исправлен фильтр цены (**`productId`** вместо опечатки **`$PRODUCT_ID`**). Добавлена защита при **`array_search`** по родительской строке сделки и **пропуск позиции**, если элемент каталога по фильтру не найден (**`updateOrderBasket`**).
- [x] Нет строковых литералов вебхуков/секретов в **`orders.applications`** вне централизованного конфига.

## Риски

- **Нестандартный вызов API:** текущий код делает **GET** на URL вида **`{kitPrefix}kit.productapplications.deal.productrows.get/?ID={dealId}`** — не тот же контракт, что **`callRestMethod($method, $params)`** (POST **`…/method.json`**). Нужно явно зафиксировать транспорт в **`b24.rest`**, иначе «замена на RestClient» останется формальной.
- **Регрессии корзины и скидок:** **`updateOrder`** меняет корзину **`Sale\Order`**, пересчёт скидок; требуется прогон на стенде с реальной сделкой/заказом.
- **Наследованные дефекты:** в текущем **`setPrice`** в фильтре **`CPrice::GetList`** используется **`$PRODUCT_ID`** вместо **`$productID`** (возможная ошибка области видимости) — решить: исправить в рамках ST-07 или зафиксировать «как было» для строгого parity.
- **Логи в корне сайта:** запись в **`get-items-log.txt`** / **`log-update-order-check-script.txt`** — риск на проде; согласовать отключение или уровень логирования.
- **Порядок загрузки:** **`orders.applications`** должен видеть **`RestClient`** → **`b24.rest` раньше**; при неверном порядке **`includeModule`** — фатал при первом вызове.

## Проверка

- Unit/интеграционные проверки:
  - мок ответа REST для сборки массива позиций заявок (**`$itemForOrder`**); при наличии — тест метода транспорта GET kit URL
- Ручной сценарий:
  - Создание/обновление заказа с привязкой к сделке на тестовом стенде

## Документация

- Изученные документы:
  - `docs/features/README.md` (раздел заказы — при отсутствии зафиксировать статус «в разработке»)
- Что обновить:
  - `docs/features/local_classes_segments_and_modules.md` — модуль **`eklektika.orders.applications`**, цепочка **`requires.php`**
  - [README задачи](../README.md), [MODULE-LAYOUT.md](../MODULE-LAYOUT.md) — п. bootstrap ST-07
- Что создать (если нужно):
  - `docs/features/orders_b24_applications.md` — при выделении как отдельной фичи

## Статус

- **done** (2026-04-21): реализация в **`eklektika.orders.applications`**, расширение **`RestClient`**, обновлены **`init.php`**, **`requires.php`**, документация сегментов.
