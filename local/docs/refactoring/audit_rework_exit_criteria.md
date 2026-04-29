# Audit-Rework Exit Criteria

Итерация `audit-rework` считается завершенной, если:

- [ ] Подтвержден inbound-инвариант: CRM входящие обрабатываются через `local/modules/yomerch.b24.inbound/endpoint.php`.
- [ ] Подтвержден целевой outbound-транспорт через `yomerch.b24.rest`.
- [ ] Актуализирован `legacy_integration_exceptions_register.md`.
- [ ] Актуализирован `b24_outbound_policy.md`.
- [ ] Пройден `graphify_preflight_checklist.md`.
- [ ] Выполнен discovery-проход командами из `discovery_commands_b24_sync.md`.
- [ ] Подготовлен handoff-пакет для Tech Lead на финальное обновление документации.
