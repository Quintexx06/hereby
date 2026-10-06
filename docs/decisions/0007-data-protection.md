# 0007: Guest data protection and retention

**Status:** Accepted (2026-10-06), with hosting still open

## Context
Rule 4: guest data stays in Switzerland and is deleted on a fixed schedule.
Allergy notes are health data, which counts as sensitive data under the Swiss FADP (nDSG).

## Decision
- `guests.dietary_notes` uses the `encrypted` cast and is `#[Hidden]`. It never
  reaches the frontend through the invitation resources.
- Weddings are `MassPrunable`: `model:prune` runs daily and deletes weddings
  older than `hereby.retention_months` (default 12). Households, guests,
  events and responses cascade at the database level.
- Production must run on Swiss hosting, database and backups included. **The
  provider is not chosen yet** (Phase 0 item); don't market "hosted in
  Switzerland" until it is.
- Guest pages carry no third-party scripts or font CDNs.

## Consequences
- Backups need the same retention, or they keep deleted data alive.
- Legal review of data handling happens before the first paid wedding (Phase 1).
- Rotating `APP_KEY` needs `APP_PREVIOUS_KEYS`, or encrypted notes become unreadable.
