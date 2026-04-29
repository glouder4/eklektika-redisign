# Оркестрация субагентов: Tech Lead -> Team Lead -> Developers

Этот проект использует цепочку:

1. `tech-lead`
2. `team-lead`
3. `developer-squad`
4. `team-lead` (аудит)
5. `developer-squad` (доработки)
6. `tech-lead` (обновление ADR/документации/чеклистов)

## Команды вызова навыков

- `/techlead <контекст_или_цель>`
- `/teamlead <next_steps_from_techlead>`
- `/dev-squad <task_bundle_from_teamlead>`
- `/delivery-chain <initiative>`
- `/local-graphify`

## Команды запуска циклов

- Полный сквозной цикл:
  - `/delivery-chain full <initiative>`
- Подготовка/хэнд-офф от техлида:
  - `/delivery-chain handoff <initiative>`
- Волна параллельной разработки:
  - `/delivery-chain dev-wave <initiative>`
- Аудит тимлида + доработки разработчиков:
  - `/delivery-chain audit-rework <initiative>`
- Финализация техлидом (ADR + docs + checklists):
  - `/delivery-chain close <initiative>`

## Файлы команд (папка `.cursor/commands`)

- `/delivery-chain-full <initiative>`
- `/delivery-chain-handoff <initiative>`
- `/delivery-chain-dev-wave <initiative>`
- `/delivery-chain-audit-rework <initiative>`
- `/delivery-chain-close <initiative>`

## Гарантии процесса

- Tech Lead обязательно ведет ADR и `tasks/subtasks`.
- Team Lead формирует четкие задачи и управляет параллельной работой.
- Developer Squad выполняет задачи пакетами и возвращает отчеты.
- После аудита Team Lead получает итог и обновляет проектную документацию.

## Политика Graphify

- Graphify разрешено использовать только на содержимом `local`.
- Нельзя индексировать весь репозиторий.
- Обязательно исключать `local/templates`.
- Для MCP-запросов к графу использовать сервер `graphify-eklektika-site`.
