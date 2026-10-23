# ADR 0016: Designs with their own moments

Date: 2026-10-23 · Status: accepted · Amends [ADR 0015](0015-five-finished-designs.md)

## Context

The founder reviewed the five designs of ADR 0015 on the live samples
(2026-10-23) and rejected most of them:

- **Garten**: «die alte version hol sie zurück». The private-press book
  lost to the earlier Garten with its arch, branches, petals and rings.
- **Raster**: almost right; it needed more icons.
- **Salon**: «sieht einfach AI-Slop aus … unglaublich leer» on desktop. Use
  the space for effects, more photos, other visuals.
- **Arve**: the same header, content and font as the others.
- **Lido**: the colours could stay; it needed another font and a photo
  that is not framed but cut out, and more of everything.

Four of the five had become serif type on cream with a dark closing band,
so they read as one design in different colours. ADR 0015's «restraint
over effects» took away what the founder liked: the effects of the
earlier Garten.

## Decision

- **Garten is the earlier Garten again**, restored from before ADR 0015:
  the opening veil, the couple under an arch with branches drawing
  themselves and petals falling (three.js once the guest interacts), the
  day on a vine, a running band of the day's parts, the story in garden
  gates, the photos under arches, the rings above the last question,
  GSAP choreography. Cormorant Garamond with Archivo, ivory paper, night
  ink and blush. Its CSS is scoped to `[data-template='garten']`.
- **Each design gets one idea of its own and the effects that serve it**,
  so no two share a structure, a face or a ground:

  | Design | Idea | Faces | Signature moments |
  | ------ | ---- | ----- | ----------------- |
  | Salon  | a grand hotel's salon at night | Ballet, Tenor Sans | the couple's photo in a gilt frame hung from the ampersand, the names either side; a hand-lamp light under the pointer; the photos hung salon-style, each settling on its nail; the letter on an ivory card between two frames; champagne flutes (three.js) above the last question |
  | Raster | the Swiss grid | Schibsted Grotesk | pictograms at every section, a sign for each part of the day, signs on the calendar and route links |
  | Garten | romantic and botanical (restored) | Cormorant Garamond, Archivo | see above |
  | Arve   | the Alps on a Swiss map | Sofia Sans Condensed | contour lines drawing over the landscape, names like a ski poster, the day as a yellow trail signpost, the story as an ascent to the summit, white-red-white trail marks, the alpine night with stars |
  | Lido   | a summer at the lake bath | Shrikhand, Figtree | the awning's scalloped edge, the photo cut out as a sun going down into the lake in stripes, drifting waves, the letter as an entry ticket, the day in pool lanes, the photos cut into shapes that bob |

- **Effects stay inside the load budget** (rule 2): CSS and scroll-driven
  animations only, GSAP and three.js fetched after the guest's first
  scroll, tap or key; reduced motion turns them off. Each design is its
  own JavaScript chunk and its own stylesheet (`resources/css/designs/
  <design>.css`, a Vite entry that `app.blade.php` inlines next to the
  shared guest styles), so a guest downloads only the design they see.
- **Couples may upload eight photos** (was four): the cover and up to
  seven more, which every design lays out. The samples show six.

ADR 0015 stands for everything else: five fixed designs, no colour, font
or section choice, content filled in by the couple, custom work as
Massanfertigung.

## Consequences

- The demo invitation (Salon without photos) weighs 177 KB with an LCP of
  1.8 s on Lighthouse's slow 4G; the reply page 166 KB.
- Each design's faces live in `resources/css/designs/<design>/fonts.css`;
  the app imports all five for the previews (`base/wedding-fonts.css`).
- A design change regenerates `public/images/designs/` from the samples.
