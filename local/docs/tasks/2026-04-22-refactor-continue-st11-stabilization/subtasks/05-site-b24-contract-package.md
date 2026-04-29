# ST-05: Контрактный пакет Site <=> B24 для yomerch

## Статус
- status: done
- date: 2026-04-29
- owner: tech-lead

## Цель
Зафиксировать полный и передаваемый на сторону B24 контракт обмена для текущей архитектуры `yomerch`.

## Входы
- Код модулей:
  - `local/modules/yomerch.b24.inbound`
  - `local/modules/yomerch.b24.usersync`
  - `local/modules/yomerch.company`
  - `local/modules/yomerch.b24.rest`
- Актуальные feature-документы по B24.

## Шаги
1. Провести фактическую сверку ACTION/payload/response inbound endpoint.
2. Зафиксировать outbound-сценарии, B24 REST-методы и структуру payload.
3. Свести explicit mapping Site <=> B24 (user/contact, company, managers).
4. Добавить секции compatibility/legacy aliases и ошибки/валидация.
5. Обновить индекс feature-документации.

## Артефакты
- `docs/features/b24_site_contracts_yomerch.md`
- `docs/features/README.md`
- `docs/features/b24_integration.md`

## DoD
- [x] Канонический inbound endpoint и базовые требования задекларированы
- [x] Все фактические ACTION описаны с payload/обязательностью
- [x] OUTBOUND методы и payload-поля задокументированы
- [x] Manager mapping (`ASSIGNED_BY_ID`, `UF_CRM_1757682312`, `UF_MANAGER`, `UF_MANAGER2`) оформлен явно
- [x] Legacy aliases и ошибки/коды включены
- [x] Добавлены open questions/assumptions

## Риски
- Неунифицированный формат response между ACTION (`UPDATE_GROUP`, `SYNC_COMPANY_CONTACTS`).
- Расхождение по полям города компании в разных outbound-потоках.
