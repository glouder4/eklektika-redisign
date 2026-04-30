# Initiative: UPDATE_COMPANY → ACTIVE пользователей сайта

## Goal

При входящем `UPDATE_COMPANY` значение `ACTIVE` компании (после UF-мэппинга) должно совпадать с `b_user.ACTIVE` у связанных пользователей (`OS_COMPANY_USERS`, дополнительно `CONTACT_IDS`).

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `Company::applyInboundCompanyActiveToSiteUser` + вызовы из `updateCompanyElement` / `createCompanyFromUpdate` | done |
| T2 | Документация `docs/bitrix24-inbound-from-site-contracts/actions/UPDATE_COMPANY.md` | done |

## Risks

- Пользователь в нескольких компаниях: последний успешный `UPDATE_COMPANY` перезапишет `ACTIVE`.
- UF маркетинга может изменить `ACTIVE` до сохранения — пользователи получают **то же** итоговое `ACTIVE`, что и элемент ИБ.

## Next steps for Team Lead

- Прогнать входящий запрос с `ACTIVE":"N"` и проверить `b_user` для `CONTACT_IDS`.
