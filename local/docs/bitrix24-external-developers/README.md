# Документация для разработчиков / интеграторов Bitrix24

Здесь лежит **собранный снэпшот** пакета передачи: все файлы из одной папки можно **заархивировать и отправить** внешней команде без ссылок на остальной репозиторий.

**Важно:** это пакет относится к реализации **`yomerch.b24.*`** (канал inbound/outbound этого проекта). Он не заменяет продуктовую документацию **`Bitrix24SiteConnectorCore`**. См. **[SCOPE_AND_LAYERING.md](./SCOPE_AND_LAYERING.md)**.

## С чего начать

1. Откройте **[BITRIX24_EXTERNAL_TEAM_HANDOFF.md](./BITRIX24_EXTERNAL_TEAM_HANDOFF.md)** — точка входа (URL inbound, заголовки, ACTION, ошибки, чеклист).
2. Детали полей и сценариев — **[b24_site_contracts_yomerch.md](./b24_site_contracts_yomerch.md)**.
3. Карта **ACTION → event / reason_code** — **[generated_inbound_action_contract_map.md](./generated_inbound_action_contract_map.md)** (перед финальной передачей сверьте с владельцем сайта, что версия собрана из актуального кода).
4. По желанию: **[inbound_dedup_storage_policy.md](./inbound_dedup_storage_policy.md)**, **[inbound_sli_slo.md](./inbound_sli_slo.md)**.
5. Предметные контракты по полям каждого `ACTION` и разделение **CRM→сайт** / **сайт→CRM**: **[bitrix24-inbound-from-site-contracts/README.md](../bitrix24-inbound-from-site-contracts/README.md)**.

Секреты (токен inbound, пути к `config.local.php`) в этот архив **не включают** — только отдельным защищённым каналом.


