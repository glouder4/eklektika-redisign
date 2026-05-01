# ADR: restore personal managers block without reverting propagation hotfix

- Initiative: [task.md](task.md)
- Status: accepted
- Date: 2026-05-01

## Decision

Восстанавливаем отображение блока персональных менеджеров через точечную коррекцию UI visibility/data-source цепочки в `sale.personal.section` и связанных user fields, без отката изменений в `local/modules/yomerch.company/lib/Company.php`, обеспечивших корректную propagation семантику `UPDATE_COMPANY`/`UPDATE_CONTACT`.

## Why

- Текущий инцидент — UI regression (пропал блок), а не доказанный дефект в самой employee-propagation логике.
- Откат hotfix может вернуть ранее закрытые проблемы в обновлении пользователей по inbound-сценариям.
- Наиболее вероятный root cause сосредоточен в условии показа (`MANAGER_BLOCK_SHOW`) и/или резолве источника менеджера.

## Probable technical focus

- `template.1/result_modifier.php`: логика `MANAGER_BLOCK_SHOW`, резолв manager id, проверка активности элемента.
- `template.1/template.php` + `parts/manager.php`: фактический рендер и зависимость от заполненности manager payload.
- Данные: `PROPERTY_MANAGER`, `PROPERTY_MANAGER2`, `MANAGER_IBLOCK_ID`, дефолтные значения user field.

## Consequences

- Team Lead реализует минимальный и проверяемый фикс для восстановления отображения блока.
- Отдельно подтверждается, что `UPDATE_COMPANY`/`UPDATE_CONTACT` не деградировали.
- В post-incident пакете обязательно остаются evidence: до/после по visibility и по propagation.

## Acceptance criteria

- Блок персональных менеджеров отображается при прежних условиях на пользовательском сценарии инцидента.
- `UPDATE_COMPANY` и `UPDATE_CONTACT` сохраняют ожидаемую propagation семантику без регресса.
- Изменения ограничены целевой областью и не затрагивают несвязанные UI/интеграционные ветки.

## Links

- Main task: [task.md](task.md)
- Subtask 1: [subtasks/01-reproduce-and-pinpoint-visibility-gate.md](subtasks/01-reproduce-and-pinpoint-visibility-gate.md)
- Subtask 2: [subtasks/02-restore-manager-data-source-contract.md](subtasks/02-restore-manager-data-source-contract.md)
- Subtask 3: [subtasks/03-non-regression-update-company-contact.md](subtasks/03-non-regression-update-company-contact.md)
