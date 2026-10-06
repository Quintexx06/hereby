# 0003 — Motion: CSS first, GSAP for choreography

**Status:** Accepted (2026-10-06)

## Context
We want memorable motion on marketing pages and calm, fast motion in the app.

## Decision
- **CSS transitions** (with tokens from `theme/motion.css`) for hover, press,
  reveal-on-scroll (`v-reveal`) and component states.
- **GSAP** (all plugins are free since 2025) for timelines: hero entrances,
  SplitText, DrawSVG and ScrollTrigger. Import it only from `@/lib/gsap`,
  and run it only through `useGsap()`, which scopes, cleans up on unmount and
  handles reduced motion.
- Principles follow Emil Kowalski (the `emil-design-eng` skill): purposeful,
  ease-out, under 300ms for UI, never from `scale(0)`.

## Consequences
- GSAP is loaded only by the pages that import it (code-split).
- Every animation needs a static fallback for `prefers-reduced-motion`.
