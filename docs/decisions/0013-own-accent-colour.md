# ADR 0013: Couples may pick their own accent colour

Date: 2026-10-15 · Status: accepted · Extends ADR 0012 · **Superseded by [ADR 0015](0015-five-finished-designs.md)**

## Context

Guest themes are an accent on a light ground (ADR 0012), with five fixed
Farbwelten. Couples and the new «Massanfertigung» ask for their own
colours, which the fixed list cannot give.

## Decision

A wedding may carry `accent_color` (#rrggbb). The guest pages take its OKLCH
hue and chroma (chroma capped at 0.15) and put them on the same lightness
ladder as the coloured themes: brand 0.47, ring 0.6, brand-muted 0.9. The
ground, ink and type never change.

## Consequences

- Any picked colour keeps WCAG AA on porcelain, as the themes do; very
  light or neon picks come out darker and calmer than picked, and the
  picker shows the result before saving.
- No new palette in the brand system: the Hereby surfaces are untouched.
