# ST-02: Слой B24 REST и конфигурация вызовов

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Убрать размазанность по проекту глобальных функций REST (`sendRequestB24`, `sendRequest`, дубли вебхуков в `init.php`) и базового класса [`local/classes/b24/Request.php`](../../../local/classes/b24/Request.php), заменив их на **один транспортный слой** с конфигурируемым базовым URL и идентификаторами вебхуков (без изменения контрактов методов CRM на первом шаге).

## Описание работ

1. Инвентаризация всех вызовов `sendRequestB24`, прямых `curl` к `URL_B24`, использования [`Request.php`](../../../local/classes/b24/Request.php) в `local/classes`, `director/`, `personal/` (в границах scope).
2. Ввести класс (или набор классов) уровня «HTTP-клиент B24»: методы `call(string $method, array $params)`, обработка ошибок CURL/HTTP/JSON единообразно с текущим поведением.
3. Вынести константу `URL_B24` и строки вебхуков из [`local/php_interface/init.php`](../../../local/php_interface/init.php) в конфигурируемое место (например, `.settings.php` модуля или отдельный конфиг под `local/php_interface/` — решение за реализацией, зафиксировать в документации).
4. Обеспечить обратную совместимость: временные обёртки с прежними именами функций, помеченные `@deprecated`, до полной миграции вызовов.

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — функции `sendRequestB24`, `sendRequest`, `findContact`, `newRest`
  - [`local/classes/b24/Request.php`](../../../local/classes/b24/Request.php)
  - потребители в [`local/classes/site/Company.php`](../../../local/classes/site/Company.php), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php)
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

- [ ] Один основной класс/сервис выполняет REST POST к B24 с тем же форматом ответа, что и текущий `sendRequestB24` (возврат `result` как сейчас)
- [ ] Конфигурация базового URL и ключей вебхуков не захардкожена размазанно по десятку файлов (исключены новые дубликаты)
- [ ] Документировано в `docs/features/local_classes_segments_and_modules.md` или `b24_integration.md`: где лежит конфиг и как добавить второй стенд (test/prod)

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

- planned
