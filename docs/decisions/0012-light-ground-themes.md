# ADR 0012: Guest themes are an accent on a light ground

Date: 2026-10-13 · Status: accepted · Amends ADR 0004 and ADR 0008

## Context

ADR 0004 let a wedding theme re-declare every semantic token, and the
coloured themes (Rosé, Alpine, Riviera, Lavanda) painted the whole guest
site in their hue on a dark ground. The landing studio, which is what
couples choose from, shows the designs on porcelain with only the accent
changing. The two no longer matched, and dark coloured grounds fought with
the couple's photos (spec 2026-10-13-couple-photos).

## Decision

Every guest theme keeps Ivory's ground, ink, rules and muted tones. A theme
sets only its accent: `--brand`, `--primary`, `--ring` and `--brand-muted`,
each tuned to keep WCAG AA on porcelain. Templates decide the layout,
photos carry the colour, the theme signs it.

## Consequences

- Guest sites look like the landing studio, and photos sit on a calm
  ground.
- Theme slugs stay, so no data changes. Designer themes (roadmap 1.7)
  follow the same rule: an accent, not a ground.
