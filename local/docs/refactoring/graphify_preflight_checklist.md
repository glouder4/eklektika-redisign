# Graphify Preflight Checklist

Перед каждым запуском graphify:

- [ ] Target scope задан как `local/`.
- [ ] `templates/` и `components/` исключены (из корня `local/`); при наличии — исключён `modules/intec.eklectika/`.
- [ ] В команде запуска нет путей вне `local/`.
- [ ] Подтвержден инвариант inbound: CRM входящие разбираются только через `local/modules/yomerch.b24.inbound/endpoint.php`.
- [ ] Подтвержден целевой outbound транспорт: `yomerch.b24.rest/RestClient`.
- [ ] В анализ не добавлены секретные файлы и временные дампы.
- [ ] Планируемые критичные узлы wave-1 перечислены в запуске/заметке.
- [ ] После запуска подготовлен короткий отчет: что попало в граф, что исключено и почему.
