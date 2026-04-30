# Initiative: Graphify MCP stale index (intec noise)

## Done

| ID | Title | Status |
|----|--------|--------|
| T1 | Explain root cause: MCP `graph.json` not refreshed / wrong path | done |
| T2 | Record policy in `.cursor/rules/graphify-preflight.mdc` (health check) | done |
| T3 | ADR `docs/adr/ADR-2026-04-30-graphify-mcp-stale-index.md` | done |

## Next steps (human / infra)

1. From repo root `local/`, run **`docs/refactoring/graphify_runbook_local_scope.md`** (robocopy + `graphify update`).
2. Confirm `Test-Path .graphify-scope-local\modules\intec.eklectika` → **False**.
3. **Rebind or re-index** Cursor MCP `graphify-eklektika-site` to the new `graphify-out/graph.json`.
4. Re-run **`graph_stats`**: node count should drop to scoped size (hundreds, not 10k+).

## Team Lead — audit

- [ ] After rebind, `query_graph` for inbound seeds returns yomerch / endpoint neighborhood without intec vendor spam.
