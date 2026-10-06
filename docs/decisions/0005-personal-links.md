# 0005: Guest access through personal household links

**Status:** Accepted (2026-10-06)

## Context
Rule 1: a guest answers in under 60 seconds on a phone, with no account and no
app. Passwords and logins fail that test. One public link leaks to uninvited
guests and gives "mystery" answers.

## Decision
- Each household gets a 40-character random alphanumeric token (~238 bits),
  generated through `HasUniqueStringIds`, so it works even when model events
  are muted. URL: `/i/{token}`.
- The token is the credential. It is hidden from model serialization; malformed
  tokens 404 before any query runs.
- Routes are throttled (`invitations`: 60/min per IP) and send
  `X-Robots-Tag: noindex, nofollow`.
- The first open is stored (`opened_at`) for the "opened but not answered" nudge.
- Only `InvitationResource` decides what a household can see.

## Consequences
- A forwarded link gives access to that household's page. That's acceptable
  (it's how paper invitations work); the couple can regenerate a token (Phase 1 admin).
- Tokens are stored in plain text so the couple can re-share links. If the
  threat model changes, store a hash plus an encrypted copy.
