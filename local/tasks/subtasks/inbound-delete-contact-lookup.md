# Initiative: DELETE_CONTACT — поиск пользователя по B24 contact id

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | `collectInboundDeleteContactLookupCandidates`: порядок `B24_ID` → `ID`, поиск `UF_B24_USER_ID` | done |
| T2 | `getUserIDByB24ID`: варианты string/int для фильтра Bitrix | done |
| T3 | Контракт `b24_site_contracts_yomerch.md` (+ features mirror) | done |

## Risks

- Если в UF записан третий идентификатор, не совпадающий ни с `B24_ID`, ни с `ID`, пользователь по-прежнему не находится.
