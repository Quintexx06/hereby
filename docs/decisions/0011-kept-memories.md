# 0011: Keeping memories past the retention window

**Status:** Accepted (2026-10-13). Extends [0007](0007-data-protection.md); needs the
legal review (roadmap 1.14) to cover it before the first paid wedding.

## Context
ADR 0007 deletes a wedding, and everything in it, 12 months after the date.
Couples asked for a way to keep the photos, voice wishes and the wedding
newspaper longer («Erinnerungen behalten», spec 2026-10-13). Guests did not
agree to their names, answers and allergy notes living on for that.

## Decision
- «Erinnerungen behalten» is an extra bought per year (Atelier and up). Each
  purchase moves `weddings.memories_kept_until` one year on, counted from the
  later of the retention end, the current date and the previous end.
- At the retention end, `hereby:strip-guest-data` (daily, before
  `model:prune`) deletes the guest data of weddings with kept memories:
  households (and with them guests, answers, invitations to events and group
  photo memberships), programme sign-ups and group photos. It records
  `guest_data_deleted_at`. Personal links stop working with their household.
- What stays until `memories_kept_until`: the wedding row, the couple's own
  content and programme, guest photos and the newspaper, including the photos
  and stories guests sent and the names they signed them with.
- `model:prune` skips weddings whose `memories_kept_until` is today or later,
  and deletes them, files included, the day after.

## Consequences
- The upload pages tell guests their photos and stories belong to the
  couple's memories; the privacy notice must say they may be kept longer
  than 12 months when the couple chooses to.
- No automatic renewal and no stored card: a couple who doesn't renew loses
  the memories at the end of the year. A reminder mail before that is a
  follow-up, not part of this decision.
- Backups follow the same schedule as in 0007.
