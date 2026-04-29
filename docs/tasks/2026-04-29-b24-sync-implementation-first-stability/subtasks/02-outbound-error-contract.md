# SBT-02: Outbound error contract normalization

## Статус
- status: done
- owner-role: backend-integrations

## Зависимости
- нет

## Acceptance checklist
- [x] Канонический outbound через `local/modules/yomerch.b24.rest/lib/RestClient.php`
- [x] Нормализованы transport errors (curl/http/json)
- [x] Нормализованы B24 API errors
- [x] Обработан `b24_missing_result`
- [x] Legacy-обертки оставлены как compatibility shim

## Next action
Зафиксировать как baseline и запретить новые обходные outbound-вызовы.
