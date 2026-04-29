# Политика хранения dedup (inbound)

## Утверждённый backend для production (одиночный веб-узел Bitrix)

- **Тип:** файловое JSON-хранилище с блокировкой записи (`LOCK_EX` в `InboundIdempotencyGate`).
- **Путь:** задаётся `inbound_dedup_store_path` в `config.local.php`. Рекомендуется каталог вне webroot, с правами только для PHP-пользователя, например относительно загрузок Bitrix или отдельный data-каталог на сервере.
- **По умолчанию (если путь пуст):** `sys_get_temp_dir()/yomerch-inbound-dedup.json` — только для dev; на production **обязательно** задать явный путь.

## Ограничения

- Кластер из нескольких PHP-воркеров на **разных** хостах без общего storage: дедуп **не согласован** между узлами. Варианты:
  - sticky sessions по входящим от Bitrix24;
  - общая NFS/SMB точка монтирования для файла dedup;
  - будущее расширение: Redis/БД с атомарным `SET NX` и TTL.

## Связка с задачами

- SBT-04: файл + явный production path считаются **production-safe** для типичной single-node установки маркетплейса.
