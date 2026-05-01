# Initiative: `UPDATE_CONTACT` возвращает ложноположительный success

## Goal

Устранить расхождение "`success=1` и `meta.updated=true`" при фактическом отсутствии обновления в целевой системе.

## Tasks

| ID | Title | Status |
|----|-------|--------|
| T1 | ADR: `local/docs/adr/ADR-2026-04-30-update-contact-false-positive-success.md` | done |
| T2 | Task tree: `local/docs/tasks/2026-04-30-update-contact-false-positive-success/*` | done |
| T3 | Outcome contract (`updated/no-effect/failed`) + reason-коды | todo |
| T4 | Post-condition verification (`changed_fields_count`, `meta.updated`) | todo |
| T5 | Incident replay (770/35488) + regression + observability checks | todo |

## Next steps for Team Lead

- [ ] Согласовать с backend формат финальных `reason_code` и политику `success/meta.updated` для `UPDATE_CONTACT`.
- [ ] Назначить владельца на реализацию ST-02 (post-condition diff) и задать SLA на hotfix.
- [ ] Подтвердить с внешней B24-командой трактовку no-effect исхода как "принято без изменений", а не "обновлено".
- [ ] Провести audit по incident replay (`ID=770`, `B24_ID=35488`, `OS_COMPANY_B24_ID=770`) и приложить evidence из логов.

## Audit

- [ ] В каждом `UPDATE_CONTACT` логе присутствует `effect_summary` (`changed_fields_count`, changed fields, resolved entity ids).
- [ ] Нет сценариев с `meta.updated=true` и одновременно `changed_fields_count=0`.
- [ ] Контракт в action-доке и runtime поведение совпадают.

## Risks

- Сохранение старой семантики у интегратора B24 приведет к неверной автоматизации на их стороне.
- Без нормализации полей сравнения возможны ложные no-effect/updated.
