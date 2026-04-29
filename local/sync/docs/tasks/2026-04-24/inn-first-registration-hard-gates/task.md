# Task: INN-first registration rewrite + hard gates

## Бизнес-цель

Переписать регистрацию пользователя под контракт "INN-first локально, B24-by-ID", чтобы исключить дубли компаний/контактов и убрать неуправляемые fallback-сценарии в цепочке сайт -> B24.

## Scope

- В контуре задачи: регистрация пользователя, поиск/создание компании, usersync в B24, входящий/исходящий транспорт и связанная документация `local/sync/docs`.
- Вне scope: полная миграция всего legacy-кода в `local/sync/*` и масштабный рефакторинг нерелевантных модулей.

## Проверенные изменения (coverage snapshot)

- Проверены изменения в ключевых файлах регистрации и интеграции:
  - `personal/ajax/ajax-register-action.php`
  - `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`
  - `local/modules/eklektika.b24.rest/lib/RestClient.php`
  - `local/events/SyncEventHandlers.php`
  - `personal/registraciya.php`
  - `personal/ajax/get-company-by-inn-public.php`
  - `eklektika-ru-b24/local/sync/from-site/site_requests_handler.php`
- Проверены/синхронизированы профильные документы:
  - `local/sync/README.md`
  - `local/sync/docs/functional-contract.md`
  - `local/sync/docs/channels.md`
  - `local/sync/docs/runbook.md`
  - `local/sync/docs/registration-checklist.md`

## Подзадачи

- [01. Контракт и документация регистрации](subtasks/01-contract-and-doc-sync.md)
- [02. Hard-gates, roadmap и блокеры](subtasks/02-hard-gates-roadmap.md)

## Roadmap (techlead-sync, приоритет)

1. Завершить документацию контракта INN-first + B24-by-ID и чеклисты верификации.  
   Milestone: все правила single-writer/fallback/ошибки sync явно описаны.
2. Закрыть повторные замечания по hard-gates (QA, Release, SRE, final go-teamlead).  
   Milestone: каждый gate переведен минимум в `pass with risks`.
3. Снять остаточные риски и перевести итоговый статус в `ready`.  
   Milestone: финальный `go-teamlead = ready`, без незакрытых major/critical замечаний.

## Definition of Done (общий)

- Контракт регистрации INN-first/B24-by-ID документирован без противоречий к текущему коду.
- Для каждого gate есть актуальный статус, блокеры и владелец следующего действия.
- Roadmap и прогресс задачи обновлены в одном каноническом месте.
- Остаточные риски сформулированы измеримо и имеют следующий шаг закрытия.

## Статус и прогресс

- Текущий статус: `at risk`.
- Прогресс: `~70%` (контракт и харднинг зафиксированы, но hard-gates не закрыты).
- Gate recap:
  - QA: `changes requested`
  - Security: `pass with risks` (после hardening endpoint)
  - Release: `changes requested` / `blocked`
  - SRE: `changes requested`
  - Final go-teamlead: `changes requested`

## Блокеры

- Не закрыты замечания QA по полному прогону сценариев регистрации (включая ошибки и edge-cases по связке INN/B24 ID).
- Release/SRE не подтверждают готовность из-за незакрытых эксплуатационных и релизных рисков.
- Финальный `go-teamlead` оставил доработки по рискам регрессии и полноте проверок.

## Следующий приоритетный шаг

Подготовить и выполнить единый remediation-пакет для QA+Release+SRE замечаний с обязательным прогоном `registration-checklist.md` и фиксацией результатов в артефактах gate-ревью.

