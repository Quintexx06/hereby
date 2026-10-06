# Domain foundation: weddings, households and personal links

**Status:** Implemented 2026-10-06 · Roadmap: engineering foundation
**Product source:** [vision](../product/vision.md), [features](../product/features.md)

## Problem

Every Phase 1 feature (RSVP, reminders, live counts, kitchen sheets) depends on
one data model and on the personal household link. Getting these right first
keeps Phase 1 small.

## Scope

In: data model, personal link page (read-only), locale per household,
per-wedding theme, retention, encrypted dietary notes, a demo seeder.
Out (Phase 1 items): RSVP form, couple dashboard, reminders, imports, admin.

## Data model

```
users ─1:n─ weddings ─1:n─ events
                 │            │ n:m (event_household): who is invited to what
                 └─1:n─ households ─1:n─ guests ─1:n─ event_responses ─n:1─ events
```

| Table | Key fields | Notes |
| --- | --- | --- |
| `weddings` | owner_id, slug, couple_names, wedding_date, rsvp_deadline, default_locale, theme | `MassPrunable` after the retention window |
| `events` | wedding_id, type (`EventType`), name?, starts_at, ends_at?, location_name, address | Stored in UTC; displayed in Europe/Zurich |
| `households` | wedding_id, name, locale (`Locale`), token (40 chars, unique), plus_one_allowed, opened_at | `opened_at` powers "opened but not answered" |
| `event_household` | event_id, household_id | Event-aware invitations |
| `guests` | household_id, first/last name, is_child, is_plus_one, dietary_notes (encrypted) | Dietary notes never reach the frontend |
| `event_responses` | guest_id, event_id, status (`ResponseStatus`), menu_choice, responded_at | Unique per guest and event; the RSVP UI comes in Phase 1 |

## Personal link

`GET /i/{token}` (named `invitation.show`) works with no account. It sets the
household's locale, records the first open, and renders `invitation/Show` with
an `InvitationResource` holding only that household's data. See [ADR 0005](../decisions/0005-personal-links.md).

## Acceptance criteria (covered by tests)

- [x] A household sees only its own events (`ShowInvitationTest`)
- [x] Unknown and malformed tokens return 404
- [x] The first open is recorded once
- [x] The page renders in the household's language
- [x] Responses carry `X-Robots-Tag: noindex, nofollow`
- [x] Tokens are 40 random characters, unique, and generated even with model events muted
- [x] Weddings older than 12 months are pruned, with households and guests cascading (`PruneWeddingsTest`)
- [x] Dietary notes are encrypted at rest

## How it respects the five rules

1. **< 60s on a phone:** no login, names pre-filled, only the household's events.
2. **< 2s load:** the invitation chunk carries no GSAP; fonts are self-hosted.
3. **No ads:** only the Hereby credit line.
4. **Data in CH + deletion:** pruning is scheduled daily; hosting is open ([ADR 0007](../decisions/0007-data-protection.md)).
5. **Finished features only:** RSVP shows "replies open soon" until the form ships.
