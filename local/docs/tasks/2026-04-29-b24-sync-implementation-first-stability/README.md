# Стабилизация B24 sync: implementation-first

## Метаданные
- ID: TASK-2026-04-29-b24-sync-implementation-first-stability
- Статус: in_progress (Фаза A: **готовность к сборке внешнего handoff-пакета** — P0+P1 по коду закрыты; передача в Bitrix24 — Фаза B)
- Приоритет: high
- Дата создания: 2026-04-29
- Ответственный: team-lead (orchestration)

## Цель
Обеспечить стабильность обмена и синхронизации с Bitrix24 через поэтапную реализацию контрактов в коде, затем передать внешний handoff-пакет команде Bitrix24 и только после их переработки запускать интеграционные тесты синхронизации.

## Фазы выполнения
### Фаза A — Internal implementation pull (сейчас)
- Закрыть кодовый пулл работ по SBT-01..SBT-06.
- Зафиксировать внутренний handoff-пакет: контракт, политика ошибок, idempotency, observability.
- Подготовить внешний пакет требований/изменений для Bitrix24 команды.

### Фаза B — External Bitrix24 rework
- Передать пакет внешней команде B24.
- Получить подтверждение и фактическую переработку с их стороны.

### Фаза C — Joint sync verification
- Выполнить contract/harness/integration тесты синхронизации после завершения Фазы B.
- Зафиксировать итоговый smoke и release-readiness.

## Roadmap (P0/P1/P2)

### P0 — Stabilize Runtime Path
- Канонический inbound entrypoint: `local/modules/yomerch.b24.inbound/endpoint.php`.
- Канонический outbound transport: `local/modules/yomerch.b24.rest/lib/RestClient.php`.
- Базовая трассировка и логирование по request correlation.

### P1 — Deterministic Processing
- Idempotency/dedup для inbound событий.
- Валидатор payload как обязательный pre-check.
- Детерминированный error contract.

### P2 — Operational Hardening
- Финализация policy/config и диагностик.
- Повторяемые smoke/contract прогоны.
- Release-readiness без критических исключений.

## Hard Gate на внешнюю документацию и тесты
- Внешняя документация формируется после стабилизации внутреннего implementation pull (минимум P0+P1 code-complete).
- Интеграционные тесты синхронизации выполняются только после внешней переработки Bitrix24 (Фаза B).

## Режимы включения (handoff-ready)
- `inbound_profile=default`: backward-compatible режим, мягкое включение hardening.
- `inbound_profile=strict`: целевой режим перед handoff в Bitrix24:
  - JSON-only ответы inbound (legacy plain выключен),
  - dedup TTL включен по умолчанию (минимум 3600),
  - при заданном секрете включаются `POST-only` и `header-token-only`.

## Пакет для передачи команде Bitrix24 (готово к отправке)

- Входной документ для внешней команды: `docs/features/BITRIX24_EXTERNAL_TEAM_HANDOFF.md` (список вложений, URL, заголовки, чеклист). Собранная папка для передачи целиком: `docs/bitrix24-external-developers/` (см. `README.md` там).
- Полный контракт полей: `docs/features/b24_site_contracts_yomerch.md`.
- Сгенерированная карта ACTION: `docs/refactoring/generated_inbound_action_contract_map.md` (перегенерировать перед релизом: см. раздел «Кодогенерация» ниже).

## Кодогенерация contract map
- Источник истины: `InboundGateway::actionContractMap()`.
- Утилита экспорта: `local/modules/yomerch.b24.inbound/tools/export_contract_map.php`.
- Пример запуска:
  - `php local/modules/yomerch.b24.inbound/tools/export_contract_map.php docs/refactoring/generated_inbound_action_contract_map.md`
  - На Windows без `php` в PATH: `pwsh local/modules/yomerch.b24.inbound/tools/run_contract_harness.ps1` (или `wsl` + `php`).

## Подзадачи и статусы
- [ ] [SBT-01: Inbound contract + security hardening](./subtasks/01-inbound-contract-security.md) — verification_pending (код готов; полный стендовый smoke по ACTION — после переработки Bitrix24)
- [x] [SBT-02: Outbound error contract normalization](./subtasks/02-outbound-error-contract.md) — done
- [x] [SBT-03: Contract tests harness](./subtasks/03-contract-tests-harness.md) — done (прогон + артефакт; интеграционный sync-smoke — Фаза C)
- [ ] [SBT-04: Idempotency + dedup gate](./subtasks/04-idempotency-dedup.md) — verification_pending (политика storage зафиксирована; проверка TTL на стенде — ops)
- [ ] [SBT-05: Business-effects compliance](./subtasks/05-business-effects-compliance.md) — partially_done (регистр и конфиг готовы; список блокировок при пустом по умолчанию согласован как «нет блокировок»)
- [ ] [SBT-06: Observability and traceability](./subtasks/06-observability-and-traceability.md) — partially_done (SLI/SLO задокументированы; дашборды/алерты — по инфраструктуре)

## Текущий статус-срез
- Общий статус: in_progress → **внутренний кодовый контур P0+P1 готов к handoff-документированию**.
- Текущий фокус: финализовать внешний пакет для Bitrix24 (Фаза B) и настройки стенда (TTL dedup, путь store, профиль strict).
- P0+P1 по реализации: закрыты; остаются стендовые/ops-проверки и продуктовое согласование блокируемых ACTION (при необходимости).
- Тесты синхронизации перенесены в Фазу C и зависят от внешней переработки Bitrix24.

## Стоп-факторы
- Обнаружен обход канонического inbound/outbound пути.
- Невозможность восстановить цепочку обработки по логам.
- Непредсказуемое поведение при повторах/дублях событий.
