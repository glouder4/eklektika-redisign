# TARGET-STRUCTURE (legacy): было запланировано как `local/classes/core` + `segment/`

> **Статус:** заменено целевой схемой размещения в модулях. Актуальный документ: [**MODULE-LAYOUT.md**](./MODULE-LAYOUT.md) (`local/modules/<module_id>/lib/`).

Заказчик уточнил (2026-04-21): **не использовать `local/classes` как постоянную организационную структуру**; механику размещать в **`local/modules/eklektika.*/lib/`**. Установщики (`install/index.php` и т.п.) пока не делаем.

Ниже сохранено **историческое** дерево для тех, кто сверяется со старыми коммитами или обсуждением ST-01–ST-02; при планировании новых переносов ориентироваться только на [MODULE-LAYOUT.md](./MODULE-LAYOUT.md).

---

## Историческое дерево (не целевое)

```text
local/classes/
├── core/
│   └── b24/
│       ├── RestClient.php
│       └── Request.php
├── segment/
│   ├── usersync/
│   ├── company/
│   ├── catalog_pricing/
│   ├── catalog_import_1c/
│   ├── orders_applications/
│   └── content_pagesettings/
├── requires.php
├── events.php
└── ajax.php
```

**Сопоставление с модулями:** `core/b24` → модуль `eklektika.b24.rest`; каждая папка `segment/<name>/` → отдельный модуль из таблицы в [MODULE-LAYOUT.md §2](./MODULE-LAYOUT.md#2-таблица-соответствия-сегмент--текущие-файлы--module_id--lib).

---

## Переходный период

Пока классы физически ещё в [`local/classes/`](../../../local/classes/) (в т.ч. после ST-02), допустимо временное сосуществование; **новый код** добавлять в **`local/modules/.../lib/`** согласно [MODULE-LAYOUT.md](./MODULE-LAYOUT.md).

Связанные артефакты: [README.md](./README.md), [ST-10](./subtasks/10-architecture-segment-independence-and-core-boundary.md).
