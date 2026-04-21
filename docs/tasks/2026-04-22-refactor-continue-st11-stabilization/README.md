# Продолжение рефакторинга: ST-11 стабилизация после ST-09/ST-10

## Метаданные
- ID: TASK-2026-04-22-refactor-continue-st11-stabilization
- Статус: planned
- Приоритет: high
- Дата создания: 2026-04-22
- Ответственный: нет

## Ссылки на источники
- Issue: нет
- PRD/Док: нет
- Доп. контекст: `/orchestrate Продолжи рефакторинг`, `docs/tasks/2026-04-21-refactor-local-classes-segmentation/README.md`, `docs/tasks/2026-04-21-refactor-local-classes-segmentation/SMOKE-REPORT-ST09-ST10.md`

## Цель
Минимально-рискованно продолжить принятый рефакторинг без отмены результатов ST-09/ST-10: закрыть верификационный хвост (ручной smoke), синхронизировать документацию bootstrap-цепочки с фактическим `requires.php`, и формализовать follow-up по временному исключению зависимостей `usersync -> company`.

Ожидаемый эффект: завершение итерации ST-09/ST-10 до операционно готового состояния (документация + проверка сценариев), снижение риска скрытых регрессий и фиксация следующего узкого технического шага без расширения scope на новые миграции.

## Границы (Scope)
- In scope:
  - Ручной smoke ключевых сценариев из ST-09/ST-10 с фиксацией `pass/fail`.
  - Синхронизация `docs/features/company_system.md` с фактическим порядком bootstrap в `local/classes/requires.php`.
  - Проверка согласованности `docs/features/local_classes_segments_and_modules.md` и `docs/features/b24_integration.md` по bootstrap/legacy-shim.
  - Обновление артефактов `docs/tasks/2026-04-21-refactor-local-classes-segmentation/*` по факту прогона smoke и закрытия хвостов.
  - Подготовка ограниченного follow-up-плана на снятие временной зависимости `eklektika.b24.usersync -> eklektika.company` без изменения бизнес-логики в этом инкременте.
- Out of scope:
  - Новая миграция доменных классов между модулями.
  - Изменения бизнес-логики usersync/company/pricing/orders/import.
  - Любые правки в `local/modules/intec.eklectika/`, `script/crm/rest/`, и namespace `intec\eklectika\`.

## План внедрения
1. Прогнать ручной smoke по фиксированному чек-листу ST-09/ST-10 и обновить smoke-артефакт.
2. Синхронизировать описание bootstrap-цепочки в feature-доках с фактическим `requires.php`.
3. Сверить и зафиксировать итоговый статус ST-09/ST-10 в task-артефактах прошлого инкремента.
4. Оформить требования и границы для следующего implement-цикла (узкий follow-up по исключениям зависимостей).

## Staged-result (безопасный code-touch в рамках ST-11)
- Внедрён единый подход `canonical transport + модульный config/mapping` без изменения бизнес-логики:
  - `eklektika.b24.rest`: `lib/Config/RestTransportConfig.php`
  - `eklektika.b24.usersync`: `lib/Config/UserSyncConfig.php` (групповые ID)
  - `eklektika.company`: `lib/Config/CompanyB24Config.php` + переход внутренних B24-вызовов на `RestClient::callRestMethod()`
  - `eklektika.catalog.import`: `lib/Config/PostImportConfig.php` (маппинг свойств нанесения)
  - `eklektika.catalog.pricing`: `lib/Config/CatalogPricingConfig.php`
  - `eklektika.orders.applications`: `lib/Config/DealApplicationsConfig.php`
  - `eklektika.site`: `lib/Config/SiteModuleConfig.php`
- В `docs/features/local_classes_segments_and_modules.md` зафиксированы gate-условия для `local/php_interface/classes` вместо рискованного удаления.
- Вне текущего шага: полноценный runtime smoke и финальный cleanup legacy-дубликатов путей в рабочем дереве.

## Подзадачи
- [ ] [ST-01: Ручной smoke ST-09/ST-10 и фиксация статусов](./subtasks/01-manual-smoke-st09-st10.md)
- [ ] [ST-02: Синхронизация bootstrap-цепочки в docs/features](./subtasks/02-bootstrap-chain-doc-sync.md)
- [ ] [ST-03: Закрытие ST-09/ST-10 в docs/tasks и traceability](./subtasks/03-closeout-st09-st10-traceability.md)
- [ ] [ST-04: Follow-up по снятию временной зависимости usersync->company](./subtasks/04-followup-usersync-company-boundary.md)

## Зависимости и риски
- Зависимости:
  - Наличие доступа к стенду для ручного smoke (CRM, ЛК, каталог, заявки, page settings).
  - Актуальное состояние `local/classes/requires.php` как источника истины bootstrap-цепочки.
  - Базовые документы `docs/features/README.md`, `company_system.md`, `b24_integration.md`, `local_classes_segments_and_modules.md`.
- Риски:
  - Smoke выявит скрытую регрессию и потребует внепланового bugfix.
  - Частичная синхронизация документации может оставить противоречивые формулировки между feature-доками.
  - Попытка сразу убрать зависимость `usersync -> company` в этом инкременте увеличит риск регресса.
- Митигации:
  - Разделить verify и code-touch: в ST-11 только проверка, документация и рамка follow-up.
  - Любой найденный дефект оформлять как отдельный bugfix-инкремент с узким scope.
  - Для исключения `usersync -> company` фиксировать отдельный план с owner и дедлайном, без немедленного рефакторинга.

## Критерии готовности задачи
- [ ] Все подзадачи закрыты
- [ ] Выполнены критерии приёмки
- [ ] Обновлена документация в `docs/features/`
