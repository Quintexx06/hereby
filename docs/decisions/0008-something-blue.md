# 0008 — Design identity: "Something blue"

**Status:** Superseded by [0009](0009-vorhang-auf.md) (2026-10-07). Superseded [0002](0002-design-identity.md).

## Context
The first identity ("ink, paper & seal": cream paper, Fraunces with italic
accents, Martian Mono eyebrows, a vermilion wax seal, noise grain, § markers)
read as generic AI output. Impeccable's calibration lists that exact look
(cream ground + high-contrast serif + red accent; italic display serif + small
tracked mono labels) as the most common machine default, and Fraunces tops its
list of overused faces. A premium product whose promise is "design is the
product" can't look like a template.

## Decision
- **World:** Swiss poster typography meets the wedding's "something blue".
  One grotesk, a strict grid, white space, and gentian blue as a committed
  colour field.
- **Palette** (OKLCH): snow, ink, stone (cold neutrals), gentian (brand),
  moss (success), ember (danger). Semantic `seal`/`highlight` are replaced by
  `brand`/`brand-muted`.
- **Type:** Archivo Variable only (`wght` + `wdth` axes), self-hosted via
  `@fontsource-variable/archivo`. Fraunces, Schibsted Grotesk and Martian Mono
  are removed.
- **Marketing is always light** (`.surface-light`); the app keeps a night theme.
- **Signature moment:** the hero shows a real guest invitation, re-addressed
  across households and languages. GSAP is no longer loaded on the landing page.

## Consequences
- Details live in [DESIGN.md](../../DESIGN.md); values in `resources/css/theme/`.
- Wedding themes now set `--brand` instead of `--seal`. Theme slugs are unchanged.
- New colours or fonts still need an ADR.
