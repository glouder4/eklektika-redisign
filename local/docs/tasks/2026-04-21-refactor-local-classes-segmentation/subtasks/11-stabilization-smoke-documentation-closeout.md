# ST-11: Stabilization, smoke/documentation closeout and sync

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Связанные артефакты:
  - [SMOKE-REPORT-ST09-ST10.md](../SMOKE-REPORT-ST09-ST10.md)
  - [ST09-ST10-AUDIT-TRACEABILITY.md](../ST09-ST10-AUDIT-TRACEABILITY.md)
  - [ST-09](./09-modules-eklektika-scaffold-and-migration.md)
  - [ST-10](./10-architecture-segment-independence-and-core-boundary.md)

## Цель подзадачи

Закрыть стабилизационный контур после ST-09/ST-10 без новой миграции кода: подготовить документацию и smoke-артефакты к ручному прогону и финальному closeout.

## Scope ST-11

1. Синхронизировать `docs/features/*` с фактическим bootstrap в `local/classes/requires.php`.
2. Подготовить smoke-артефакт с прозрачным статусом ожидания ручного прогона (`Not run`, если прогон не выполнялся).
3. Обновить traceability между ST-09/ST-10 и текущей стабилизацией.
4. Зафиксировать implement-ready follow-up для снятия временного исключения `eklektika.b24.usersync -> eklektika.company`.
5. Закрыть deployment/secrets-блокер по `b24_integration_config.php`: убрать безусловную загрузку конфига в `init.php` и сохранить BC-контракт констант.

## Out of scope ST-11

- Перенос классов, изменение бизнес-логики доменных модулей.
- Новые архитектурные миграции.
- Изменения в запрещённых зонах из `arch-rules`.

## Implement-ready follow-up: usersync -> company

### Follow-up item

- Идентификатор: `FU-ST11-USERSYNC-COMPANY-GATEWAY`
- Цель: убрать прямую зависимость `eklektika.b24.usersync -> eklektika.company` через выделение узкого фасада `CompanyGateway` (или эквивалентного контракта) без регрессии BC.
- Owner: Eklektika architecture and refactoring team (module maintainers: usersync + company).
- Deadline/condition: выполнить в ближайшем code-touch цикле usersync/company, но не позднее `2026-05-29`; если до даты нет code-touch, переносится на первый следующий change-set по usersync/company и фиксируется в task update.

### Definition of Ready (DoR)

- [ ] Подтверждён текущий call-chain в `RegisterUserCompany` с точками обращения к `Company`.
- [ ] Согласован минимальный публичный контракт фасада (без утечки внутренней логики `Company`).
- [ ] Подготовлен BC-план: что остаётся совместимым для текущих сценариев регистрации/синхронизации.

### Acceptance criteria (DoD)

- [ ] В `eklektika.b24.usersync` отсутствует прямой вызов доменных методов `Company`, вместо этого используется фасад/контракт.
- [ ] Порядок bootstrap в `requires.php` остаётся валидным и не меняет бизнес-поведение.
- [ ] Обновлены `docs/features/local_classes_segments_and_modules.md`, `docs/features/company_system.md`, `docs/features/b24_integration.md` в части зависимости.
- [ ] Ручной smoke (usersync/company) заполнен в `SMOKE-REPORT-ST09-ST10.md` с финальным `pass/fail`.

## Статус

- in_progress (manual smoke pending, docs sync in ST-11 completed)

## Deployment/Secrets hardening note

- Секретный runtime-файл `local/php_interface/b24_integration_config.php` не должен быть обязательным артефактом коммита.
- Эталон для настройки стендов: `local/php_interface/b24_integration_config.example.php`.
- `local/php_interface/init.php` обязан обрабатывать отсутствие секрета без фатала и с сохранением определения констант `URL_B24`, `B24_REST_WEBHOOK_MAIN`, `B24_REST_WEBHOOK_KIT`.
