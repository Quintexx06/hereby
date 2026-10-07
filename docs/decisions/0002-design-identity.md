# 0002 — Design identity: ink, paper & seal

**Status:** Superseded by [0008](0008-something-blue.md), then [0009](0009-vorhang-auf.md) (2026-10-07)

## Context
"Hereby" is the language of declarations: *I hereby…* The UI should feel like
a well-made document, not a generic SaaS template (no purple gradients, no
Inter-on-white).

## Decision
- **Palette** (OKLCH): *ink* (cold blue-black), *paper* (warm archival ivory),
  *seal* (vermilion wax, the only loud accent), *brass* (sparing highlight),
  *sage* (success). The dark theme is "midnight ink".
- **Type:** *Fraunces* (display; variable `opsz`/`SOFT`/`WONK` axes give it a
  hand-set, slightly irregular voice), *Schibsted Grotesk* (body; it comes from
  newspaper typesetting), *Martian Mono* (labels, fine print).
- Fonts are **self-hosted** via `@fontsource-variable`, with no third-party
  font CDN (better privacy, performance and offline builds).

## Consequences
- The seal is used **once per view** at most.
- New colours or fonts need a new ADR.
