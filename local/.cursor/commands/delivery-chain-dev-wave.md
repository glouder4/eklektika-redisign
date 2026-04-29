---
description: Этап параллельной разработки Team Lead -> Developer Squad
---

Запусти только dev-wave для инициативы: `$ARGUMENTS`.

Сделай:
1. Вызови subagent `team-lead` для декомпозиции на параллельные задачи.
2. Вызови subagent `developer-squad` для исполнения задач.
3. Убедись, что по каждой задаче есть:
   - Implemented;
   - Validation;
   - Known limitations;
   - Ready for Team Lead audit.
4. После завершения wave обнови контекст graphify по `local/`:
   - вызови `/graphify-local-wave1 <initiative>`;
   - используй MCP-сервер `graphify-eklektika-site`;
   - зафиксируй, что в graphify не попадает `local/templates/`;
   - если graphify не обновлен, wave считается незавершенным.

Верни сводку реализации по потокам для запуска аудита.
