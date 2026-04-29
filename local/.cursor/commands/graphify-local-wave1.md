---
description: Подготовка и запуск graphify по local wave-1 со строгим исключением local/templates
---

Подготовь и запусти graphify для контекста `$ARGUMENTS` по правилам:

1. Использовать только `local/`.
2. Исключить `local/templates/`.
3. Не включать ничего вне `local/`.
4. До запуска пройти `docs/refactoring/graphify_preflight_checklist.md`.
5. Использовать runbook `docs/refactoring/graphify_runbook_local_scope.md`.
6. Перед запуском выполнить discovery из `docs/refactoring/discovery_commands_b24_sync.md`.
7. После запуска выдать краткий отчет:
   - включенные директории,
   - исключенные директории,
   - подтверждение инварианта inbound через `/local/modules/yomerch.b24.inbound/endpoint.php`,
   - найденные legacy-исключения.

Важно: в этом проекте использовать только MCP Graphify-сервер `graphify-eklektika-site`.
