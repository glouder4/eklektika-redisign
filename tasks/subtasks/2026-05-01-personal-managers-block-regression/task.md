# Initiative: personal managers block regression after ParseError hotfix

## Business goal

Восстановить отображение блока персональных менеджеров в личном кабинете после hotfix в `local/modules/yomerch.company/lib/Company.php`, сохранив полезные изменения по propagation в `UPDATE_COMPANY` и `UPDATE_CONTACT`.

## Context and constraints

- Инцидент: после фикса ParseError пользователь перестал видеть блок персональных менеджеров в `sale.personal.section`.
- Откат hotfix запрещен: необходимо сохранить корректную employee-propagation логику (`ACTIVE`, `UF_ADVERSTERING_AGENT`, скидочные группы).
- Scope sync после Team Lead audit: в этой же волне менялся не только `UPDATE_CONTACT`, но и `UPDATE_COMPANY` (contract `reason_code` + `evidence` payload); эти изменения считаются параллельным hardening и отделены от UI root cause блока менеджеров.
- Фикс sanitizer whitelist (`UF_CRM_3804624445748`, `UF_CRM_1757682312`) сохраняется как обязательный guardrail и не подлежит откату в рамках восстановления UI.
- Graphify preflight пройден (scoped graph): 441 nodes / 565 edges / 41 communities.
- Вероятная зона регрессии находится на стыке UI-условий показа и источников данных менеджеров.

## Most probable regression points (P0 investigation scope)

- `template.1/result_modifier.php`:
  - вычисление `$arVisual['MANAGER_BLOCK_SHOW']`;
  - резолв `$iManagerId` через `PROPERTY_MANAGER`/`MANAGER_DEFAULT_USE`;
  - `loadManager()` с фильтром `ACTIVE='Y'` и зависимостью от `MANAGER_IBLOCK_ID`.
- `template.1/template.php`:
  - фактический gating рендера `parts/manager.php` только при `MANAGER_BLOCK_SHOW=true`.
- `template.1/parts/manager.php`:
  - рендер полей зависит от наполненности `$arManager['MANAGER_PROPERTY']` и вторичного `$arManager2`.
- Источник данных менеджеров:
  - пользовательские поля `PROPERTY_MANAGER` / `PROPERTY_MANAGER2` (user fields);
  - дефолт из настроек user field (`DEFAULT_VALUE`);
  - карточки менеджеров в инфоблоке (`MANAGER_IBLOCK_ID`) с обязательной активностью.
- Косвенная зона влияния:
  - `local/modules/yomerch.company/lib/Company.php` и интеграционные потоки `UPDATE_COMPANY`/`UPDATE_CONTACT` как потенциальный источник side-effect на профиль пользователя/связанные поля.

## Subtasks

- [01-reproduce-and-pinpoint-visibility-gate.md](subtasks/01-reproduce-and-pinpoint-visibility-gate.md)
- [02-restore-manager-data-source-contract.md](subtasks/02-restore-manager-data-source-contract.md)
- [03-non-regression-update-company-contact.md](subtasks/03-non-regression-update-company-contact.md)

## Milestones and phase gates

1. Reproduce + isolate (`P0`)
   - Результат: воспроизводимый кейс + точная причина скрытия блока (visibility gate/data source).
   - Gate: причина доказана на коде и данных, без гипотез.
2. Restore + verify (`P0`)
   - Результат: блок снова отображается при прежних условиях.
   - Gate: ручная проверка на целевом сценарии + подтверждение корректных данных менеджера.
3. Protect from regressions (`P0`)
   - Результат: подтверждено отсутствие регресса в `UPDATE_COMPANY`/`UPDATE_CONTACT`.
   - Gate: пройдены regression checks, оформлен audit для Team Lead.

## Definition of Done (initiative acceptance)

- [ ] Блок персональных менеджеров снова отображается в ЛК при прежних условиях показа.
- [ ] Условия показа (`MANAGER_BLOCK_SHOW`, user field mapping, manager IBLOCK active state) зафиксированы и проверены.
- [ ] Источник данных менеджеров (user fields + manager iblock) валидирован на данных инцидента.
- [ ] Нет регресса propagation по `UPDATE_COMPANY` / `UPDATE_CONTACT` (включая `ACTIVE` и связанные поля).
- [ ] ADR, task и subtasks двусторонне связаны и синхронизированы по статусам.

## Status and progress

- Priority: `P0`
- Status: `in_progress`
- Progress: `20%` (контур регрессии и вероятные точки определены, требуется исполнение Team Lead + dev-аудит)
- Blockers:
  - нет hard-blocker в документации;
  - риск скрытого data drift в user fields/manager iblock.

## ADR link

- [adr.md](adr.md)

## Next steps for Team Lead (P0)

- [ ] Зафиксировать baseline инцидента: user id, значения `PROPERTY_MANAGER/PROPERTY_MANAGER2`, `MANAGER_IBLOCK_ID`, активность элемента менеджера.
- [ ] Пройти `result_modifier.php` по шагам и снять фактические значения `MANAGER_BLOCK_SHOW`, `$iManagerId`, `$iSecondManagerId`, результат `loadManager()`.
- [ ] Восстановить рендер блока через минимальное исправление (без отката полезных изменений hotfix в `Company.php`).
- [ ] Провести smoke по ЛК: блок виден, карточка менеджера корректна (имя/телефон/email/аватар fallback).
- [ ] Обязательно прогнать non-regression пакет по `UPDATE_COMPANY` и `UPDATE_CONTACT` с фиксацией evidence и reason codes.
- [ ] Передать tech lead audit с root cause, diff-подходом и подтверждением acceptance.
