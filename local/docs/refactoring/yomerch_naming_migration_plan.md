# Yomerch Naming Migration Plan

## Goal
Снизить путаницу в проекте: перейти на нейминг `yomerch` без поломки runtime-контрактов Bitrix.

## Current state
- Runtime-контур модулей завязан на `eklektika.*`:
  - `local/modules/bootstrap.php`
  - `local/php_interface/init.php`
  - `local/modules/eklektika.*/include.php`
- Sync-конфиг исторически использует `$GLOBALS['EKLEKTIKA_SYNC_CONFIG']`.

## Migration policy
1. Без big-bang rename module id на первом этапе.
2. Сначала dual naming/alias layer.
3. Полный rename module id только отдельной волной с rollback-планом.

## Wave 1 (safe)
- Ввести alias для sync-конфига:
  - canonical: `$GLOBALS['YOMERCH_SYNC_CONFIG']`
  - compatibility fallback: `$GLOBALS['EKLEKTIKA_SYNC_CONFIG']`
- Обновить код чтения sync-конфига на dual-read.
- Обновить документацию: "project yomerch, legacy runtime ids eklektika.*".

## Wave 2 (safe-medium)
- Добавить bootstrap alias map для module ids `yomerch.* -> eklektika.*` в точках includeModule/requires.
- Не переименовывать физические папки модулей.

## Wave 3 (medium-high)
- Частичный рефактор имен в коде и docs (без смены module id).
- Проверка регрессий по inbound/outbound CRM и user flows.

## Wave 4 (high risk, optional)
- Полный rename module id и директорий (`eklektika.* -> yomerch.*`).
- Отдельный preprod прогон, миграция БД-ключей (если есть), rollback скрипт.

## Non-goals (for this migration stream)
- Не смешивать с отдельным контуром legacy `intec.eklectika` в ту же волну.
