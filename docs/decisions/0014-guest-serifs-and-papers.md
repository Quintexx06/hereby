# ADR 0014: Guest sites get their own serifs and papers

Date: 2026-10-21 · Status: accepted · Amends ADR 0009 and ADR 0012 · **Superseded by [ADR 0015](0015-five-finished-designs.md)**

## Context

Couples compare Hereby with art-directed wedding sites (Riley & Grey,
Minted, Squarespace and Wix editorial templates). Those share a few marks:
a high-contrast serif for names and headings, small tracked capitals for
dates and places, a monogram, a site bar with the chapters and the reply,
and a warm paper instead of plain white. Hereby's guest sites set
everything in Archivo on porcelain, which reads as the platform rather
than the couple's wedding. The founder chose, on 2026-10-21, to give each
template a serif of its own and each theme a paper of its own, for guest
sites only.

## Decision

- **Platform unchanged.** Marketing, auth and the app stay Archivo on
  porcelain (ADR 0009). The serifs never load there.
- **One display serif per template**, self-hosted from `@fontsource-variable`,
  latin only, declared in `guest.css`: Atelier Bodoni Moda (names in
  capitals), Garten Cormorant Garamond (italic headings and ampersand),
  Panorama Fraunces. Archivo stays the reading face. The template's roman
  file is preloaded (`WeddingTemplate::serifFile()`); the italic loads only
  where the accent is set.
- **Small tracked capitals** for dates, places and the bar's links are
  allowed on guest sites (the platform's "no labels" rule stays).
- **A paper per theme** (`--paper-l/c/h`, one ladder for card, muted and
  rules): Ivory cream, Rosé blush, Alpine stone, Riviera champagne,
  Lavanda mist. Ink stays night; accents keep WCAG AA on their paper.
  Supersedes ADR 0012's "Ivory's ground for every theme".

## Consequences

- A guest page loads one serif family (about 26–67 kB) on top of Archivo;
  rule 2 holds because the files are preloaded and `font-display: swap`.
- New templates must name their serif and tune it in
  `invitation-type.css`; new themes must name their paper.
- Designer themes (roadmap 1.7) may set a paper, not a dark ground.
