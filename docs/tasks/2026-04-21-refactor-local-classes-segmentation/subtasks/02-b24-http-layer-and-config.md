# ST-02: Слой B24 REST и конфигурация вызовов

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Убрать размазанность по проекту глобальных функций REST (`sendRequestB24`, `sendRequest`, дубли вебхуков в `init.php`) и базового класса **Request** (фактическое размещение: [`local/modules/eklektika.b24.rest/lib/Request.php`](../../../local/modules/eklektika.b24.rest/lib/Request.php)), заменив их на **один транспортный слой** с конфигурируемым базовым URL и идентификаторами вебхуков (без изменения контрактов методов CRM на первом шаге).

**После стабилизации поведения:** перенести файлы транспорта из [`local/classes/b24/`](../../../local/classes/b24/) в модуль **`eklektika.b24.rest`** → **`local/modules/eklektika.b24.rest/lib/`** ([MODULE-LAYOUT.md](../MODULE-LAYOUT.md), ST-03/ST-09).

## Описание работ

1. Инвентаризация всех вызовов `sendRequestB24`, прямых `curl` к `URL_B24`, использования базового **Request** / **RestClient** в `local/classes`, `director/`, `personal/` (в границах scope).
2. Ввести класс (или набор классов) уровня «HTTP-клиент B24»: методы `call(string $method, array $params)`, обработка ошибок CURL/HTTP/JSON единообразно с текущим поведением.
3. Вынести константу `URL_B24` и строки вебхуков из [`local/php_interface/init.php`](../../../local/php_interface/init.php) в конфигурируемое место (например, `.settings.php` модуля или отдельный конфиг под `local/php_interface/` — решение за реализацией, зафиксировать в документации).
4. Обеспечить обратную совместимость: временные обёртки с прежними именами функций, помеченные `@deprecated`, до полной миграции вызовов.

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — функции `sendRequestB24`, `sendRequest`, `findContact`, `newRest`
  - [`local/modules/eklektika.b24.rest/lib/Request.php`](../../../local/modules/eklektika.b24.rest/lib/Request.php) — наследники вызывают защищённый транспорт на `URL_B24 . 'local/classes/site_requests_handler.php'` (не путать с глобальной `sendRequest`, которая бьёт в `URL_B24 . '/local/classes/ajax.php'`).
  - потребители (примеры; полный список — по инвентаризации в рамках scope): [`local/modules/eklektika.company/lib/Company.php`](../../../local/modules/eklektika.company/lib/Company.php), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php), [`local/components/online-service/user.profile.edit/class.php`](../../../local/components/online-service/user.profile.edit/class.php)
- Контракты возврата (не смешивать при выносе в клиент):
  - `sendRequestB24` после успешного JSON-декода возвращает **`$decoded['result']`** (одно значение из ответа REST).
  - глобальная `sendRequest` возвращает **весь декодированный массив** ответа (`$decodedResult`).
- Изменяемые файлы/области:
  - `local/classes/b24/` (новые классы клиента)
  - `local/php_interface/init.php` (сокращение после переноса)
  - точечные правки вызовов в разрешённых директориях

## Зависимости

- Блокируется:
  - желательно наличие ST-01 для единой терминологии
- Блокирует:
  - ST-03, ST-04, ST-07 (части с REST)

## Критерии приёмки

- [x] Один основной класс/сервис выполняет REST POST к основному вебхуку B24 (`…/rest/1/<token>/…`) с тем же форматом успешного ответа, что и текущий `sendRequestB24`: после парсинга JSON возвращается значение ключа **`result`** (как сейчас в строке `return $decodedResult['result'];`).
- [x] Путь «прокси» через сайт (`ajax.php` / `site_requests_handler.php`) при рефакторинге сохраняет прежний тип возврата: **`sendRequest`** — полный декодированный массив; методы классов на базе **Request** (`eklektika.b24.rest/lib`) — без изменения набора ключей успешного ответа относительно текущего поведения.
- [x] Конфигурация базового URL и идентификаторов вебхуков не захардкожена размазанно по десятку файлов в зонах задачи; новые дубликаты строк не добавляются.
- [x] Документировано в `docs/features/local_classes_segments_and_modules.md` или `b24_integration.md`: где лежит конфиг и как переключить стенд (test/prod)

## Проверка

- Unit/интеграционные проверки:
  - при наличии стенда — один интеграционный вызов `crm.contact.list` с тестовым фильтром (или мок)
- Ручной сценарий:
  - Регистрация/обновление пользователя и операция из админского сценария компании не падают с ошибкой транспорта

## Документация

- Изученные документы:
  - `docs/features/b24_integration.md`
- Что обновить:
  - `docs/features/b24_integration.md` — раздел про конфигурацию REST и вебхуков
  - `docs/features/local_classes_segments_and_modules.md` — слой транспорта
- Что создать (если нужно):
  - нет

## Статус

- done
