# ST-03: Сегмент синхронизации пользователя с CRM

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Изолировать логику «пользователь сайта ↔ контакт Bitrix24» в модуле **`eklektika.b24.usersync`** с кодом в **`local/modules/eklektika.b24.usersync/lib/`**, отделив её от компаний и ценообразования.

**Целевое размещение:** см. [MODULE-LAYOUT.md](../MODULE-LAYOUT.md). **`RegisterUserCompany.php`** и **`User.php`** переносятся из [`local/classes/b24/`](../../../local/classes/b24/) в **`local/modules/eklektika.b24.usersync/lib/`**. Регистрация событий из [`local/classes/events.php`](../../../local/classes/events.php) переносится в **bootstrap модуля** (класс в `lib/`, например `ServiceProvider` / аналог). Транспорт REST (`RestClient`, `Request`) — только модуль **`eklektika.b24.rest`**; физически транспорт уже размещён в **`local/modules/eklektika.b24.rest/lib/`** после **ST-02** (этот модуль в рамках ST-03 не дублировать и не переносить повторно).

### Порядок bootstrap (`init.php` / `requires.php`)

Обязательная последовательность при подключении модулей:

1. **`Loader::includeModule('eklektika.b24.rest')`** — клиент REST и конфигурация вызовов доступны первыми.
2. **`Loader::includeModule('eklektika.b24.usersync')`** — доменная логика синхронизации пользователя и регистрация обработчиков событий.

Иной порядок недопустим: usersync опирается на транспорт из **`eklektika.b24.rest`**.

### Риски и ограничения

- **`RegisterUserCompany`** использует типы из **`intec\eklectika\...`**: по архитектурным правилам проекта этот код **не правится** в рамках задачи; допускается только вызов как есть после переноса обвязки в модуль.
- **`RegisterUserCompany`** также завязан на **`OnlineService\Site\Company`**: на этапе ST-03 класс ещё лежал под `local/classes/site/`; после **ST-04** физическое место — модуль **`eklektika.company`** (`lib/Company.php`). Не смешивать перенос компании с usersync без явного решения по порядку модулей (**`company` до `usersync`** в [`requires.php`](../../../local/classes/requires.php)).
- Зависимость от порядка **`Loader::includeModule`**: см. раздел выше.

## Описание работ

1. Создать модуль **`local/modules/eklektika.b24.usersync/`** с **`lib/`** и минимальным **`include.php`** (`Loader::registerAutoLoadClasses` — см. [ST-09](./09-modules-eklektika-scaffold-and-migration.md)); перенести **`RegisterUserCompany.php`**, **`User.php`** из текущего `local/classes/b24/` (обновить namespace или оставить прежний с алиасом по согласованию).
2. Перенести регистрацию обработчиков из [`local/classes/events.php`](../../../local/classes/events.php) в bootstrap модуля (класс `ServiceProvider` или аналог в `lib/`), сохранив те же события: `OnBeforeUserDelete`, `OnBeforeUserRegister`, `OnAfterUserRegister`, `OnAfterUserUpdate`.
3. Уточнить связи только с **`eklektika.b24.rest`** (`RestClient`/`Request`): не вводить зависимости на `eklektika.company` или pricing ([ST-10](./10-architecture-segment-independence-and-core-boundary.md)).
4. Обновить [`local/classes/ajax.php`](../../../local/classes/ajax.php) действиями `UPDATE_CONTACT`, `UPDATE_BATCH_USERS`, `DELETE_CONTACT` — только через публичный фасад модуля (без изменения контракта ответа для фронта без необходимости).
5. Обновить [`local/php_interface/init.php`](../../../local/php_interface/init.php) и/или [`local/classes/requires.php`](../../../local/classes/requires.php): подключить модули в порядке **REST → usersync** (см. «Порядок bootstrap» выше); убрать прямой `require` доменных файлов из `local/classes/b24/` после переноса.
6. Убедиться, что вызовы CRM идут через **`eklektika.b24.rest`** (`RestClient`/`Request` в `lib/`) и поведение согласовано с уже внедрённым слоем ST-02.

## Технические детали

- Компоненты/модули:
  - регистрация событий: **`lib/UserSyncBootstrap.php`** (ранее [`local/classes/events.php`](../../../local/classes/events.php), удалён)
  - **`local/modules/eklektika.b24.usersync/lib/*.php`**
  - [`local/classes/ajax.php`](../../../local/classes/ajax.php) (часть экшенов)
  - **`eklektika.b24.rest`**: транспорт в [`local/modules/eklektika.b24.rest/lib/`](../../../local/modules/eklektika.b24.rest/lib/) — см. [MODULE-LAYOUT](../MODULE-LAYOUT.md)
- Изменяемые файлы/области:
  - `local/modules/eklektika.b24.usersync/lib/` (в т.ч. bootstrap регистрации событий), `include.php`
  - `local/classes/requires.php`, `local/php_interface/init.php` — порядок **`eklektika.b24.rest`** → **`eklektika.b24.usersync`**
  - ~~`local/classes/events.php`~~ — удалён после переноса в модуль
  - **`eklektika.b24.rest`** — только как зависимость (ST-02 закрыт); изменения в `local/modules/eklektika.b24.rest/lib/` не входят в ST-03, если не нужен узкий фикс для совместимости

## Зависимости

- Блокируется:
  - ST-02 (транспорт REST) — **выполнена:** `eklektika.b24.rest` с `lib/` доступен до старта ST-03
- Блокирует:
  - финальную унификацию в ST-09 для домена пользователя

## Критерии приёмки

- [x] Файлы домена пользователя лежат в **`local/modules/eklektika.b24.usersync/lib/`**, не смешаны с транспортом REST (`eklektika.b24.rest` отдельно)
- [x] В bootstrap: сначала **`Loader::includeModule('eklektika.b24.rest')`**, затем **`Loader::includeModule('eklektika.b24.usersync')`** ([`requires.php`](../../../local/classes/requires.php))
- [x] Обработчики событий `main` регистрируются из **`UserSyncBootstrap::register()`** при загрузке модуля; файл **`local/classes/events.php`** удалён
- [x] Экшены [`ajax.php`](../../../local/classes/ajax.php) (`UPDATE_CONTACT`, `UPDATE_BATCH_USERS`, `DELETE_CONTACT`) вызывают только публичный фасад модуля — **`OnlineService\B24\UserSync\ContactAjaxFacade`**
- [x] Документация: **`b24_integration.md`**, **`local_classes_segments_and_modules.md`**, **MODULE-LAYOUT** обновлены под пути модуля

## Проверка

- Unit/интеграционные проверки:
  - нет / по возможности smoke-тест на тестовом B24
- Ручной сценарий:
  - Регистрация тестового пользователя; изменение полей профиля; проверка контакта в CRM

## Документация

- Изученные документы:
  - `docs/features/b24_integration.md`
  - `docs/features/user_profile_edit.md` (если есть расхождения — синхронизировать)
- Что обновить:
  - `docs/features/b24_integration.md` — блок «пользователь и контакт»
  - `docs/features/local_classes_segments_and_modules.md` — границы модуля `eklektika.b24.usersync`
- Что создать (если нужно):
  - нет

## Статус

- done
