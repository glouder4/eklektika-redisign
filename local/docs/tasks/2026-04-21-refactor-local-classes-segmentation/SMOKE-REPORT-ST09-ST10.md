# Smoke report: ST-09/ST-10

## Metadata

- Date: 2026-04-22
- Scope: TASK-2026-04-21-refactor-local-classes-segmentation (ST-09/ST-10)
- Run type: ST-11 stabilization handoff for manual smoke
- Overall status: Not run
- Note: smoke was not executed in this step by explicit constraint (manual run is performed by user).

## Scenario statuses

| Scenario | Status (`pass`/`fail`/`not run`) | Notes |
|---|---|---|
| usersync: user <-> CRM sync | Not run | Manual check pending |
| company/manager/holding flows | Not run | Manual check pending |
| catalog pricing + company discount contract | Not run | Manual check pending |
| 1C post-import hooks | Not run | Manual check pending |
| deal applications -> sale order | Not run | Manual check pending |
| site module: search bootstrap + page settings | Not run | Manual check pending |

## How to update after manual run

1. Set run date and executor name in metadata (`Date`, add `Executed by`).
2. For each scenario set status to `pass` or `fail` (leave `not run` only for intentionally skipped scenarios).
3. For each `fail` add short reproduction, impact, and where logs were checked.
4. Update `Overall status`: `pass` only if all mandatory scenarios are `pass`, otherwise `fail` (or `partial` if explicitly agreed).
5. Sync final result with parent task `README.md`, ST-09/ST-10 subtasks, and ST-11 subtask.

## Manual smoke checklist template

- [ ] Open homepage and verify early bootstrap has no fatals/notices blocking rendering.
- [ ] Validate usersync scenario (registration/profile sync with CRM contact).
- [ ] Validate company/manager/holding scenario in personal/director flows.
- [ ] Validate pricing floor and company-discount contract on catalog/cart paths.
- [ ] Validate 1C post-import hook path (or explicitly mark as not run with reason).
- [ ] Validate deal applications path from B24 deal to sale order.
- [ ] Validate site module bootstrap: search indexing hook and page settings helpers.
