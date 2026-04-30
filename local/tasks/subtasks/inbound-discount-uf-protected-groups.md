# Initiative: UF скидки CRM → группы сайта и защита групп

## Policy

- **`UF_CRM_1771490556028`** — каноническое поле списка скидки в CRM; легаси **`UF_CRM_1777030197`** поддерживается с меньшим приоритетом.
- Маппинг CRM id → группа сайта: **`CompanyModuleConfig::PROD_COMPANY_STATUS_GROUP_ID_MAP`** (prod) / **`TEST_COMPANY_STATUS_GROUP_ID_MAP`** (тест).
- Группы из **`CompanyModuleConfig::getCompanyDiscountProtectedSiteGroupIds()`** не снимаются через **`User::removeUserFromGroupsByIds()`**, кроме вызова из **`Company::applyB24CompanyGroupsToUser(..., true)`** (синхронизация после входящего `UPDATE_COMPANY` с ключом `OS_COMPANY_DISCOUNT_VALUE`).
- Если обновляемая компания — **головная холдинга**, при переданной скидке она применяется также ко всем **`OS_COMPANY_USERS`** дочерних компаний (`OS_HOLDING_OF` → головная); иначе — только к сотрудникам этой карточки (`Company::collectSiteUserIdsFromChildCompaniesForDiscount` / `applyInboundDiscountGroupsOnlyToSiteUser`).

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `CrmInboundUfMap::applyCompanyInboundDiscountUfToSiteProperties` + вызов из `InboundGateway` | done |
| T2 | Флаг `$allowCompanyDiscountGroupRemoval` в `User::removeUserFromGroupsByIds`; `Company` передаёт `true` | done |
| T3 | `CompanyModuleConfig::getCompanyDiscountProtectedSiteGroupIds()` | done |
| T4 | Документация `UPDATE_COMPANY.md` | done |
| T5 | Головная компания: скидка на сотрудников дочерних элементов | done |

## Risks

- Другие участки кода с `CUser::SetUserGroup` без фильтра могут перезаписать весь список групп и потерять скидку — обходится только аудитом таких мест.

## Next steps for Team Lead

- На портале убедиться, что в теле `UPDATE_COMPANY` приходит **`UF_CRM_1771490556028`** с id варианта **1014–1021** (или явное «пусто» для снятия).
