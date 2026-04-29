# SLI / SLO для inbound Bitrix24 → сайт (черновик под мониторинг)

Логи ориентируются на строки `inbound.outcome` и отклонения security/dedup/payload (см. `SyncInboundLog`, `InboundRequestLogger`).

## SLI (что измеряем)

| SLI | Описание | Источник / разметка |
|-----|----------|---------------------|
| `error_rate` | Доля ответов с `http_code` >= 500 или `success=0` с `event=dispatch_failed` | Парсинг JSON-lогов или reverse-proxy access log + тело ответа |
| `auth_reject_rate` | Доля запросов с `sync_forbidden`, `sync_method_not_allowed`, `sync_signature_invalid` | `reason_code` в ответе / логах |
| `dedup_hit_rate` | Доля `409` / `dedup_blocked` / `reason_code=dedup_duplicate` | Inbound outcome + gateway |
| `payload_reject_rate` | `invalid_payload`, `unknown_action` (если считаете отказом) | Validator + gateway |

## SLO (целевые ориентиры — уточняются по продукту)

| Метрика | Target (черновик) | Окно |
|---------|-------------------|------|
| Доступность endpoint (5xx) | < 0,5% успешных по объёму | 7 дней |
| Неожиданные 5xx (`dispatch_failed`) | < 0,1% от всех inbound-вызовов | 7 дней |

## Алерты / дашборды

- **Рекомендация:** панель по `reason_code` и HTTP-коду; алерт при росте `dispatch_failed` и `auth_reject_rate` выше базовой линии.
- **Внедрение:** на стороне хостинга (ELK, Grafana Loki, Datadog и т.д.) — отдельная задача DevOps; acceptance SBT-06 закрывается формализацией SLI/SLO в этом документе, подключение дашбордов — по инфраструктуре.
