# Graphify Runbook: Local Scope Only

## Purpose
Подготовка графа знаний по интеграционному контуру проекта без выхода за допустимый scope.

## Hard scope policy
- Источник данных: только `local/`.
- Обязательные исключения: `local/templates/`, `local/components/`.
- Запрещено добавлять в graphify любые пути вне `local/`.

## Integration invariants for analysis
- Inbound CRM -> site: только через `local/modules/yomerch.b24.inbound/endpoint.php`.
- Outbound site -> B24: целевой транспорт через `local/modules/yomerch.b24.rest/lib/RestClient.php`.

## Recommended safe launch (PowerShell)
```powershell
New-Item -ItemType Directory -Force -Path .graphify-scope-local | Out-Null
robocopy local .graphify-scope-local /E /XD local\templates local\components > $null
/graphify .graphify-scope-local
```

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
