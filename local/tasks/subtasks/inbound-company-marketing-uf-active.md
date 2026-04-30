# Initiative: UPDATE_COMPANY — UF маркетинг-агента → ACTIVE + OS_IS_MARKETING_AGENT

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `CrmInboundUfMap::COMPANY_OS_IS_MARKETING_AGENT_UF` + `applyCompanyInboundCrmUfToSiteProperties` | done |
| T2 | Вызов из `InboundGateway` перед `updateCompanyElement` | done |
| T3 | Контракт в `b24_site_contracts_yomerch.md` | done |
| T4 | ADR | done |
| T5 | Маппинг CRM list id → enum «Да» на сайте (`OS_IS_MARKETING_AGENT_INBOUND_VALUE_IDS_AS_YES`, напр. 2076 → 31519) | done |

## Next steps for Team Lead

- [ ] На стороне CRM убедиться, что в `UPDATE_COMPANY` приходит `UF_CRM_1675675211485` с ожидаемыми значениями (Y/N/1/0/Да/Нет и т.д. по текущим правилам).
- [ ] Проверить в админке ИБ, что у списка `OS_IS_MARKETING_AGENT` варианты согласованы с `VALUE` `Y` / пусто.

## Audit

- [ ] Сценарий: только `ACTIVE`+UF без канонического `OS_IS_MARKETING_AGENT` — элемент и группы пользователей ведут себя ожидаемо.

## Risks

- Нестандартные значения списка CRM (числовой enum id без попадания в true/false правила) — UF удаляется из запроса, `ACTIVE` с этой ветки не меняется.
- Новый числовой ID «да» со стороны CRM нужно добавить в `CompanyModuleConfig::OS_IS_MARKETING_AGENT_INBOUND_VALUE_IDS_AS_YES`, иначе значение снова нормализуется в `false`.
