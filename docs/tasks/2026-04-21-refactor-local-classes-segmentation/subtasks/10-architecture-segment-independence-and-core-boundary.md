# ST-10: Границы модулей, транспорт REST и независимость доменов

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Связанные артефакты: [MODULE-LAYOUT.md](../MODULE-LAYOUT.md) (каноническая целевая схема), [TARGET-STRUCTURE.md](../TARGET-STRUCTURE.md) (legacy: core/segment под `local/classes`)

- Внешние ссылки:
  - нет

## Цель подзадачи

Зафиксировать для всей команды и исполнителей **правила зависимостей**: доменные модули **`eklektika.*`** (кроме транспорта) **не зависят друг от друга** напрямую; допустимо пересечение только в модуле **`eklektika.b24.rest`** (транспорт CRM + чтение конфигурации окружения B24 через уже принятый конфиг); исключения документировать (узкие контракты).

Термины «core» и «segment» из старого плана соответствуют: **core** → **`eklektika.b24.rest`**; **segment/** → остальные доменные модули с **`lib/`**.

## Описание работ

1. Убедиться, что [MODULE-LAYOUT.md](../MODULE-LAYOUT.md) отражает разделение транспорта (**`eklektika.b24.rest`**) и доменных модулей и запрет смешения домена с REST-клиентом.
2. Зафиксировать **граф зависимостей**:
   - Любой доменный модуль может зависеть от **`eklektika.b24.rest`** и от **публичного API Битрикс** (`CUser`, инфоблоки и т.д.).
   - Между доменными модулями **`eklektika.*`** прямых `use`/require **нет**, кроме перечисленных ниже исключений.
3. **Исключение 1 (узкий контракт):** модуль **`eklektika.catalog.pricing`** (ST-05) потребляет только интерфейс/фасад скидки компании из **`eklektika.company`** (ST-04), например `CompanyDiscountResolverInterface` + фабрика в `eklektika.company/lib/` — без импорта внутренних классов pricing из company и без обратной зависимости company → pricing.
4. **Исключение 2:** общие типы/DTO — только в **`eklektika.b24.rest`** при необходимости минимальных DTO запросов или в отдельном согласованном модуле; до появия — минимизировать общие сущности.
5. Внести краткий раздел в [`docs/features/local_classes_segments_and_modules.md`](../../../features/local_classes_segments_and_modules.md): «Правила независимости» + ссылка на MODULE-LAYOUT и этот файл.

### Ближайшие шаги реализации (continue)

1. Зафиксировать в документации список разрешённых зависимостей в формате `module -> allowed deps`.
2. Явно описать запреты: прямые `use`/`require` между доменными модулями без утверждённого контракта.
3. Проверить текущие модули `eklektika.*` на фактические нарушения и завести follow-up пункты (если найдутся).
4. Сверить порядок подключения в `requires.php` с зависимостями и обновить README задачи при расхождении.

### Continue-фокус (2026-04-22)

- Подзадача выполняется сразу после стабилизации ST-09: сначала фиксируется факт по загрузке модулей, затем формализуются итоговые архитектурные границы.
- Ключевой результат — проверяемые правила dependency governance для implement/review цикла, а не декларативное описание.

### Ближайшие шаги реализации (continue)

1. Зафиксировать финальный список `allowed dependencies` по каждому модулю `eklektika.*`.
2. Проверить фактические зависимости в `local/modules/eklektika.*/lib/` и отметить нарушения (если есть).
3. Для обнаруженных нарушений: либо убрать зависимость в коде в рамках implement-цикла, либо документировать как временное исключение с дедлайном снятия.
4. Обновить `docs/features/local_classes_segments_and_modules.md` секцией «Правила независимости модулей» с примерами allowed/forbidden.
5. Синхронизировать `docs/features/b24_integration.md` и `docs/features/company_system.md` с этими правилами.

### Матрица проверки (обязательная для исполнителя, факт на 2026-04-22)

Traceability artifact: [`ST09-ST10-AUDIT-TRACEABILITY.md`](../ST09-ST10-AUDIT-TRACEABILITY.md).

- [x] По каждому модулю `eklektika.*` зафиксирован список **allowed dependencies** в `docs/features/local_classes_segments_and_modules.md`.
- [x] Для каждого найденного отклонения определено: исправление в коде или временное исключение в docs/tasks (актуальное исключение: `usersync -> company`).
- [x] Исключение pricing -> company описано как узкий контракт и отражено одинаково в task/docs/features.
- [x] Подтверждено, что `eklektika.b24.rest` позиционируется как транспорт без доменной бизнес-логики.

### Остаток до полного закрытия ST-10 (handoff через ST-11)

- [ ] Снять временное исключение `usersync -> company` по follow-up `FU-ST11-USERSYNC-COMPANY-GATEWAY` (после выделения фасада/контракта и обновления кодовой зависимости).
- [ ] Провести ручной smoke и зафиксировать итоговый статус `pass/fail` в отдельном артефакте (текущий статус: Not run).

### Реестр временных исключений (owner/deadline/follow-up)

| Exception | Owner | Deadline/condition | Follow-up |
|---|---|---|---|
| `eklektika.b24.usersync -> eklektika.company` | Eklektika architecture and refactoring team (module maintainers: usersync + company) | Remove by 2026-05-29 or in the first next usersync/company code-touch cycle; no carry-over without task update | `FU-ST11-USERSYNC-COMPANY-GATEWAY` in ST-11 + `docs/features/local_classes_segments_and_modules.md` + this ST-10 subtask |

### Implement-ready criteria for follow-up `FU-ST11-USERSYNC-COMPANY-GATEWAY`

- [ ] Definition of Ready: call-chain points in `RegisterUserCompany` identified; target contract agreed.
- [ ] Implementation: direct dependency `usersync -> company` replaced by narrow gateway/contract.
- [ ] Verification: manual smoke for usersync/company paths recorded in smoke report with explicit `pass/fail`.
- [ ] Documentation: ST-10, ST-11 and feature docs updated consistently in the same change-set.

### Выходные артефакты ST-10 (обязательные)

1. Финальная таблица `module -> allowed deps -> forbidden deps`.
2. Реестр отклонений (если есть): `нарушение -> решение -> owner -> срок снятия`.
3. Синхронные обновления `docs/features/local_classes_segments_and_modules.md`, `b24_integration.md`, `company_system.md`.
4. Краткая проверка соответствия порядка `Loader::includeModule` в `requires.php` зафиксированным зависимостям.

## Технические детали

- Компоненты/модули:
  - документация в `docs/features/`
  - при ревью кода — проверка импортов между `local/modules/eklektika.*/lib/`
- Изменяемые файлы/области:
  - `docs/features/local_classes_segments_and_modules.md`
  - опционально комментарий в корне задачи [README.md](../README.md)

## Зависимости

- Блокируется:
  - понимание ST-03–ST-05 (чтобы исключение pricing→company были точными)
- Блокирует:
  - нет (скорее «рамка» для ревью всех подзадач)

## Критерии приёмки

Master DoD: см. [README.md](../README.md) -> "Единые критерии завершения ST-09 + ST-10 (DoD для финального цикла)".

- [ ] В `docs/features/local_classes_segments_and_modules.md` есть явный список: транспорт в **`eklektika.b24.rest`**, домены в отдельных модулях **`lib/`**, запрет сквозных зависимостей между доменами
- [ ] Исключение pricing→company описано как **контракт**, не как произвольный импорт `Company.php`
- [ ] Исполнитель может по импортам в PR ответить на вопрос: «Это нарушает независимость доменных модулей?»
- [ ] Для каждого текущего модуля `eklektika.*` в документе указаны allowed dependencies и пример недопустимой зависимости
- [ ] Для каждого временного исключения есть owner, причина и условие снятия в документации задачи/фичи
- [ ] В `docs/features/b24_integration.md` и `docs/features/company_system.md` нет противоречий с правилами независимости из `local_classes_segments_and_modules.md`

## Проверка

- Unit/интеграционные проверки:
  - нет (документ + при необходимости статический grep по `lib/` в PR)
- Ручной сценарий:
  - Ревью diff ST-03–ST-05 на отсутствие лишних `use` между несвязанными модулями
  - Ревью diff ST-06–ST-09 на отсутствие новых прямых зависимостей между доменными модулями

## Документация

- Изученные документы:
  - [MODULE-LAYOUT.md](../MODULE-LAYOUT.md)
  - [.cursor/rules/arch-rules.mdc](../../../../.cursor/rules/arch-rules.mdc)
- Что обновить:
  - `docs/features/local_classes_segments_and_modules.md`
  - `docs/features/b24_integration.md`
  - `docs/features/company_system.md`
- Что создать (если нужно):
  - нет (артефакт задачи уже создан)

## Статус

- ready_for_review (docs synced in ST-11, temporary exception implement-ready, smoke Not run)
