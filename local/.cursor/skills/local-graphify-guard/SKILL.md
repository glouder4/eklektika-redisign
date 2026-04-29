---
name: local-graphify-guard
description: Жесткие правила использования graphify только для папки local с исключением local/templates.
trigger: /local-graphify
---

# /local-graphify

Навык-ограничитель для безопасного использования Graphify в этом проекте.

## Команда вызова

`/local-graphify`

## Обязательные правила

1. Источник данных для graphify: только `@local`.
2. Нельзя скармливать graphify весь репозиторий или внешние папки.
3. Обязательно исключать `@local/templates` и `@local/components`.
4. Не добавлять в граф ничего, что находится вне `@local`.
5. Если есть сомнения по пути, остановиться и запросить уточнение до запуска.
6. Для Graphify MCP в этом проекте использовать сервер `graphify-eklektika-site`.

## Практическая политика скоупа

- Разрешено: `local/classes`, `local/modules/eklektika.*`, `local/php_interface`, `local/sync`, и другие подпапки внутри `local`, кроме исключений.
- Запрещено: любые пути вне `local`.
- Явно исключено: `local/templates`, `local/components`.

## Чеклист перед запуском graphify

- [ ] Путь запуска = `local`
- [ ] Исключения `local/templates` и `local/components` зафиксированы
- [ ] Нет дополнительных директорий вне `local`

## Рекомендуемый безопасный запуск (Windows PowerShell)

```powershell
New-Item -ItemType Directory -Force -Path .graphify-scope-local | Out-Null
robocopy local .graphify-scope-local /E /XD local\templates local\components > $null
/graphify .graphify-scope-local
```

Пояснение:
- В graphify попадает только копия содержимого `local`.
- `local/templates` и `local/components` принудительно исключаются.
- Ничего вне `local` в граф не попадает.
