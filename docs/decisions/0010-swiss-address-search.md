# 0010: Venue addresses from the federal address register

**Status:** Accepted (2026-10-09)

## Context
Guests must arrive at the right door, and venues share a place across many
couples (Phase 2). A free-text address field gives typos, missing postcodes
and no coordinates for maps or public transport later.

## Decision
- The venue address comes from the **official register of building
  addresses**, through the swisstopo geo.admin.ch search API
  (`SearchServer`, `type=locations`, `origins=address`, `sr=4326`). It covers
  every Swiss address, has coordinates, is free and needs no key.
- We call it **server-side** (`GET /adressen?q=`, `SwissAddressSearch`), so
  visitors' IPs never reach a third party. Results are cached for 7 days,
  throttled at 30 per minute per user, and the search degrades to an empty list.
- We store street, postcode, town, lat/lng and the register's feature id
  (`venue_reference`), which is stable across renames.
- Manual entry stays possible (venues abroad, brand-new buildings).
- The URL is configurable (`GEOADMIN_SEARCH_URL`) for tests and outages.

## Consequences
- Production needs outbound HTTPS to `api3.geo.admin.ch`.
- Phase 3's door-to-door travel can use the stored coordinates directly.
