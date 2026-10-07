# 0009 — Design identity: "Vorhang auf"

**Status:** Accepted (2026-10-07). Supersedes [0008](0008-something-blue.md).

## Context
"Something blue" (0008) fixed the template look, but the founder's verdict was
clear: *professional, but far too corporate and too small*, and blue does not
fit a wedding. A premium wedding product has to feel like the evening itself:
emotional, cinematic, big. It must still avoid the machine defaults that 0002
fell into (cream paper, a red seal, tracked mono labels).

## Decision
- **World:** the wedding evening as a stage. A sheer veil parts and reveals the
  couple ("Vorhang auf für euer Ja."). Night and porcelain, lit by candlelight;
  **colour comes from photography**, the UI stays black, white and champagne.
- **Palette** (OKLCH): porcelain (light ground), night (warm black stage), sand
  (warm neutrals), champagne (the one accent, thin and rare), moss/ember (state
  only). `.stage` turns any section to night; marketing is otherwise light.
- **Type:** Archivo Variable only, self-hosted: semibold and widened for
  display, plain for body; Archivo italic in champagne is the accent voice
  (`.accent`). *Amended 2026-10-07:* Bodoni Moda was tried first and dropped
  after founder review ("I want the modern one").
- **Photography:** real, self-hosted Unsplash images (credited), art-directed
  per breakpoint. No stock icons, no illustrations standing in for photos.
- **3D:** three.js, lazy-loaded after first paint: the veil curtain (opening
  the hero, drifting back in over the closing scene) and the interlinked rings. Both have
  reduced-motion and no-WebGL fallbacks; the photo is always a plain `<img>`.
- **Landing language:** Swiss German first (roadmap 0.3), SEO meta and FAQ
  structured data rendered on the server.

*Amended 2026-10-07:* the accent moved from champagne to **blush** (rosé,
`--color-blush-*`) and the neutrals tilted slightly pink, after founder review.

## Consequences
- Details live in [DESIGN.md](../../DESIGN.md); values in `resources/css/theme/`.
- The second half of the page uses scroll-linked motion (pinned acts track,
  drawn lines, strike-through, rising wordmark) via `useScrollProgress`,
  without scroll-jacking; every pattern has a reduced-motion state.
- three.js (~133 kB gzipped) is a new dependency, loaded only on the landing
  page and never before the hero photo is decoded.
- The Ivory guest theme follows the new house style; theme slugs are unchanged.
- The previous identities (0002, 0008) stay as history. New colours or fonts
  still need an ADR.
