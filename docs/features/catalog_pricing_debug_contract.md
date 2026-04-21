# Debug-контракт `eklektika.catalog.pricing`

## Описание

Документ фиксирует безопасный runtime-контракт debug-флагов для `OnlineService\Site\CatalogPriceFloor` без использования `pre()`/`die()`.

## Флаги

- `os_price_debug=1` — включает диагностический лог по синхронизации витринной цены в `syncCatalogSkuOfferDisplayFromOptimal`.
- `os_price_debug_breakdown=1` — включает лог breakdown-структур из `computeAdvertisingWholesaleMarketingBreakdown`.
- `os_price_debug_product=<ID>` — фильтр продукта для обоих debug-режимов; при заданном ID логируется только этот товар/ТП.

## Канал логирования

- Запись идёт через `CatalogPriceFloor::debugLog(...)`.
- Файл: `DOCUMENT_ROOT/log/catalog_price_floor.log` (если `DOCUMENT_ROOT` недоступен — fallback к `<project_root>/log/catalog_price_floor.log`).
- Формат: timestamp + request uri + message + json context.

## Что логируется

### `os_price_debug=1`
- снимок цены до синхронизации (`MIN_PRICE`, `ITEM_PRICES` type 3, snapshot `PRICES`);
- снимок после синхронизации;
- диагностические данные по каталожным скидкам и результатам пробных вычислений;
- справочные значения базы/пола цены;
- технические пробы GOP и ключевые Bitrix discount options.

### `os_price_debug_breakdown=1`
- label breakdown-ветки (`catalog_advertising_list` или `full_chain`);
- `productId`;
- полный массив breakdown;
- дополнительный контекст ветки (`extra`).

## Ограничения и инварианты

- Debug-флаги не должны менять бизнес-логику и итоговые цены.
- Отладка не должна прерывать runtime (никаких `die()`/`pre()`).
