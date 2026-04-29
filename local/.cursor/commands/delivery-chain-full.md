---
description: Полный сквозной цикл Tech Lead -> Team Lead -> Developers -> Audit -> Rework -> Tech Lead docs update
---

Запусти полный цикл для инициативы: `$ARGUMENTS`.

Обязательная последовательность:
1. Вызови subagent `tech-lead` и сформируй/обнови ADR + `tasks/subtasks`.
2. Передай `Next steps for Team Lead`.
3. Вызови subagent `team-lead` для декомпозиции на параллельные задачи.
4. Вызови subagent `developer-squad` для реализации волны задач.
5. Снова вызови `team-lead` для аудита результатов.
6. Если есть замечания — отправь доработки в `developer-squad`.
7. Заверши через `tech-lead`: обнови ADR, прогресс, чеклисты и `tasks/subtasks`.

В финале выдай краткий отчет:
- что сделано;
- что осталось;
- какие риски.
