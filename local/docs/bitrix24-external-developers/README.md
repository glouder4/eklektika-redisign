# Документация для разработчиков / интеграторов Bitrix24

Здесь лежит **собранный снэпшот** пакета передачи: все файлы из одной папки можно **заархивировать и отправить** внешней команде без ссылок на остальной репозиторий.

## С чего начать

1. Откройте **[BITRIX24_EXTERNAL_TEAM_HANDOFF.md](./BITRIX24_EXTERNAL_TEAM_HANDOFF.md)** — точка входа (URL inbound, заголовки, ACTION, ошибки, чеклист).
2. Детали полей и сценариев — **[b24_site_contracts_yomerch.md](./b24_site_contracts_yomerch.md)**.
3. **Маппинг сайт ↔ UF CRM** — **[b24_site_to_crm_uf_field_map.md](./b24_site_to_crm_uf_field_map.md)** (поля `b_user` / компании и соответствующие `UF_CRM_*` на портале).
4. Карта **ACTION → event / reason_code** — **[generated_inbound_action_contract_map.md](./generated_inbound_action_contract_map.md)** (перед финальной передачей сверьте с владельцем сайта, что версия собрана из актуального кода).
5. По желанию: **[inbound_dedup_storage_policy.md](./inbound_dedup_storage_policy.md)**, **[inbound_sli_slo.md](./inbound_sli_slo.md)**.

Секреты (токен inbound, пути к `config.local.php`) в этот архив **не включают** — только отдельным защищённым каналом.

## Канонические копии в репозитории

Исходные документы по-прежнему ведутся в `docs/features/` и `docs/refactoring/`. При изменении контракта или кода обновите канонические файлы и **скопируйте заново** содержимое в эту папку перед передачей (или выполните те же шаги копирования, что использует ваша команда в CI).
