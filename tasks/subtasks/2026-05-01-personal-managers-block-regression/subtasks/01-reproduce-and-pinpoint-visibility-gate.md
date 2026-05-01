# Subtask 01: Reproduce and pinpoint manager visibility gate

- Parent task: [../task.md](../task.md)
- ADR: [../adr.md](../adr.md)
- Status: `in_progress`
- Priority: `P0`

## Scope

Воспроизвести инцидент исчезновения блока и доказательно локализовать, где именно в цепочке вычисления происходит скрытие (`MANAGER_BLOCK_SHOW`, manager id resolve, `loadManager`).

## Inputs

- Пользователь/аккаунт с воспроизводимым инцидентом.
- Конфиг параметров компонента `sale.personal.section` (свойства менеджера и iblock).
- Текущее состояние user fields `PROPERTY_MANAGER` / `PROPERTY_MANAGER2`.

## Outputs

- Подтвержденный root-cause visibility gate.
- Снимок значений: `MANAGER_BLOCK_SHOW`, `iManagerId`, `iSecondManagerId`, результат `loadManager`.

## Dependencies

- Нет (стартовая подзадача).

## Risks

- Ложная локализация, если проверка выполнена не на данных инцидента.
- Пропуск дефолтного manager fallback (`MANAGER_DEFAULT_USE`).

## Definition of Done

- [ ] Инцидент воспроизведен на контролируемом окружении.
- [ ] Установлена точка, где блок отключается (условие/данные).
- [ ] Подготовлен краткий evidence-пакет "до фикса".

## Verification checklist

- [ ] Проверены оба источника manager id: пользовательское поле и default значение.
- [ ] Проверена активность менеджера в инфоблоке (`ACTIVE=Y`).
- [ ] Подтверждено, что `template.php` скрывает блок только из-за `MANAGER_BLOCK_SHOW=false`.
