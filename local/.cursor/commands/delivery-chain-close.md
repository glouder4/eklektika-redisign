---
description: Финализация инициативы через Tech Lead (ADR, docs, checklists, tasks/subtasks)
---

Запусти этап close для инициативы: `$ARGUMENTS`.

Сделай:
1. Вызови subagent `tech-lead`.
2. Обнови ADR по финальному состоянию решения.
3. Обнови документацию прогресса и чеклисты.
4. Обязательно актуализируй `tasks/subtasks`.
5. Зафиксируй остаточные риски и follow-up задачи (если есть).
6. Обязательно проверь и зафиксируй последний graphify snapshot по `local/`:
   - для graph query использовать MCP-сервер `graphify-eklektika-site`;
   - подтверждено исключение `local/templates/`;
   - в отчете есть ссылка на последний запуск `/graphify-local-wave1`.

Верни финальный статус инициативы: `closed` или `follow-up-required` с причинами.
