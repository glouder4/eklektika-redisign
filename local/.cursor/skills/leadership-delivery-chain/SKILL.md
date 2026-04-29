---
name: leadership-delivery-chain
description: Сквозной регламент цепочки техлид -> тимлид -> разработчики -> аудит -> доработки -> техлид.
trigger: /delivery-chain
---

# /delivery-chain

Сквозной регламент исполнения задач через управленческую цепочку.

## Команда вызова

`/delivery-chain <initiative>`

## Команды запуска циклов

- Полный цикл:
  - `/delivery-chain full <initiative>`
- Только постановка от Tech Lead к Team Lead:
  - `/delivery-chain handoff <initiative>`
- Только волна разработки (Team Lead -> Developer Squad):
  - `/delivery-chain dev-wave <initiative>`
- Только аудит и доработки (Team Lead <-> Developer Squad):
  - `/delivery-chain audit-rework <initiative>`
- Только закрытие инициативы Tech Lead (ADR/docs/checklists):
  - `/delivery-chain close <initiative>`

Если подкоманда не указана, используется `full`.

## Последовательность

1. Tech Lead:
   - формирует/обновляет ADR,
   - обновляет `tasks/subtasks`,
   - передает `Next steps for Team Lead`.
2. Team Lead:
   - декомпозирует на параллельные задачи,
   - назначает Developer Squad.
3. Developer Squad:
   - реализует задачи и возвращает отчеты.
4. Team Lead (audit):
   - проверяет качество и полноту,
   - формирует доработки при необходимости.
5. Developer Squad (rework):
   - вносит доработки.
6. Tech Lead:
   - обновляет документацию проекта,
   - финализирует ADR и чеклисты.

## Требования качества

- Нельзя пропускать этапы аудита.
- Нельзя закрывать инициативу без обновления `tasks/subtasks`.
- Все handoff-блоки должны быть явно оформлены в тексте.
- Для инициатив, затрагивающих `local/`, обязательно обновлять graphify-контекст:
  - запуск `/graphify-local-wave1 <initiative>`;
  - для Graphify MCP-запросов использовать сервер `graphify-eklektika-site`;
  - обязательное исключение `local/templates/`;
  - без этого этап закрытия считается неполным.
