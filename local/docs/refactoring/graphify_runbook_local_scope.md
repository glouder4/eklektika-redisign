# Graphify Runbook: Local Scope Only

## Purpose
Подготовка графа знаний по интеграционному контуру проекта без выхода за допустимый scope.

## Hard scope policy
- Источник данных: только `local/`.
- Обязательные исключения: `templates/`, `components/` (и при наличии — `modules/intec.eklectika/` как сторонний модуль с тяжёлыми вендорами).
- Запрещено добавлять в graphify любые пути вне `local/`.

## Integration invariants for analysis
- Inbound CRM -> site: только через `local/modules/yomerch.b24.inbound/endpoint.php`.
- Outbound site -> B24: целевой транспорт через `local/modules/yomerch.b24.rest/lib/RestClient.php`.

## Recommended safe launch (PowerShell)

Из **корня репозитория `local/`** (где лежат `modules/`, `php_interface/`, `templates/`):

```powershell
if (Test-Path .graphify-scope-local) { Remove-Item -Recurse -Force .graphify-scope-local }
New-Item -ItemType Directory -Force -Path .graphify-scope-local | Out-Null
robocopy . .graphify-scope-local /E /XD templates components modules\intec.eklectika /NFL /NDL /NJH /NJS
graphify update .graphify-scope-local
New-Item -ItemType Directory -Force -Path graphify-out | Out-Null
Copy-Item -Force .graphify-scope-local\graphify-out\* graphify-out\
```

Артефакты: сначала в `.graphify-scope-local\graphify-out\`, затем копия в корневой **`graphify-out/`** для IDE и скриптов. CLI: **`graphify update <path>`** (не голый путь — см. `graphify --help`).

Если корневой `graphify-out\graph.json` уже был от старой полной сборки и CLI отказывается перезаписать, удалите старый `graphify-out` или обновляйте только через копию из `.graphify-scope-local\graphify-out\` как выше.

Если в логе `Refusing to overwrite` / число узлов не сходится со scope: удалите `.graphify-scope-local\graphify-out\graph.json` (и при необходимости корневой `graphify-out\graph.json`) и снова выполните `graphify update .graphify-scope-local`.

**MCP `user-graphify-eklektika-site`:** подхватывает граф на стороне сервера MCP; после локального `graphify update` нужно **заново загрузить/переиндексировать** `graph.json` в конфигурации MCP, иначе `graph_stats` в чате останется от прежней базы.

## Wave 1 include set
- `local/modules/yomerch.b24.inbound`
- `local/modules/yomerch.b24.rest`
- `local/modules/yomerch.b24.usersync`
- `local/modules/yomerch.company`
- `local/php_interface`
- `local/components/online-service`

## Expected outputs
- `graphify-out/graph.html`
- `graphify-out/GRAPH_REPORT.md`
- `graphify-out/graph.json`
