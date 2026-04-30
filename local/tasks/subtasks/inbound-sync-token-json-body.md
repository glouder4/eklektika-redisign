# Initiative: inbound `sync_token` visible when body is JSON

## Goal

Eliminate false `sync_forbidden` / missing token when CRM sends JSON in POST body with `Content-Type: application/x-www-form-urlencoded`.

## Tasks

| ID | Title | Status |
|----|--------|--------|
| T1 | Merge JSON object from raw body into `$_REQUEST` in `endpoint.php` | done |
| T2 | Document behaviour in `b24_site_contracts_yomerch.md` §2.1 | done |
| T3 | ADR `docs/adr/ADR-2026-04-30-inbound-json-body-into-request.md` | done |
| T4 | Extend `inbound-test.php` security probe (`sync_token` in request fields) | done |

## Team Lead — next steps for CRM

- Prefer correct `Content-Type: application/json` for JSON bodies (optional after server fix).
- Always send `sync_token` (header `X-SYNC-TOKEN` if `inbound_require_header_token` is on) and full action payloads; `DELETE_CONTACT` with only `ACTION` remains invalid.

## Risks

- Very large JSON bodies increase memory use (unchanged from already reading full `php://input`).
- Conflicting keys: JSON merge overwrites existing `$_REQUEST` entries for the same key (acceptable for server-to-server inbound).
