# ADR 0015: Five finished designs instead of a page builder

Date: 2026-10-22 · Status: accepted · Supersedes ADR 0004 (themes), 0012,
0013 and 0014 for guest sites; amends ADR 0009

## Context

The founder judged the guest sites unprofessional (2026-10-22): they read
like the landing page with effects, the fonts felt like AI defaults
(Bodoni Moda, Cormorant, Fraunces over Archivo) and the colours like
Tailwind pastels. The builder behind them multiplied choices nobody
designed together: three templates × five colour themes × any accent
colour × sections to hide × twelve style directions. Research for this
change (spec [2026-10-22-five-designs](../specs/2026-10-22-five-designs.md))
found the premium makers do the opposite: Riley & Grey's designs are
"template-based and not fully customizable", with fixed colours for all
but two; breadth (Joy's 600 themes) reads as mass-market; the editorial
"magazine" look with scroll effects is now stock on Framer and Wix; and
Cormorant with sage and cream is the documented look of AI-made wedding
sites.

The founder decided: switch the builder off, offer **five good designs
whose content the couple fills in**, and make everything beyond them
custom work (Massanfertigung).

## Decision

- **Five designs**, each finished by us in layout, type and colour
  (`App\Enums\WeddingTemplate`):

  | Design | For | Faces | Palette (paper / ink / accent / dark) |
  | ------ | --- | ----- | ------------------------------------- |
  | Salon  | black tie, grand hotel, guildhall, castle | Castoro Titling, Libre Caslon Text | cotton #F5F2EC, velvet ink #16120F, oxblood #6A1420, champagne on the dark |
  | Raster | modern venues, museums, the city | Schibsted Grotesk | concrete #ECEBE7, graphite #111214, vermilion #B8321C, graphite #1B1C1E |
  | Garten | gardens, vineyards, barns, estates | Linden Hill, Ysabeau Office | candle cream #F3E9D8, #2A2619, fig #6B2E4A, hedge #252D1F with butter |
  | Arve   | mountain hotels, huts, alpine lakes | Vollkorn | wool #F2EEE6, #1B2420, geranium #A8232A, night forest #1F2C26 with stone pine |
  | Lido   | lakes, Ticino, feasts by day | Gloock, Familjen Grotesk | lido paper #F7F1E3, lake #13234A, cobalt #1F45C4, butter field #F2D36B |

  Raw values live in `resources/css/theme/designs.css`; each design maps
  them onto the semantic tokens in `themes/wedding.css`, so every guest
  page (invitation, reply, programme, memories, newspaper) takes the
  design's paper, ink, accent, faces and button shape. Every text colour
  keeps WCAG AA on its ground; decoration-only colours are marked.
- **No colour, accent, section or style choice.** The theme, accent colour,
  hidden sections and style directions leave the setup, the resources and
  the guest pages. Their columns keep their data for now and are no longer
  read (`theme` becomes nullable); a later migration may drop them.
- **Content is the couple's**: names, date, programme, photos (cover plus
  three), story, place, travel, stay, dress code, questions, contact. The
  designs differ in layout, type and colour, never in what guests learn
  (`useInvitationContent`, shared parts in `components/invitation/`).
- **Faces** are self-hosted from Fontsource (SIL OFL), two families at most
  per design, latin and latin-ext, declared in `base/wedding-fonts.css`;
  `WeddingTemplate::fonts()` names the files each design preloads. The
  previous serifs and Archivo leave guest pages.
- **Restraint over effects.** No 3D, tickers, parallax, scroll reveals,
  veil opening or pinned scenes on guest pages. Motion is limited to
  Salon's 220 ms card fade, press feedback and colour transitions.
- **Spaced small capitals** for dates, places and headings are part of
  Salon, Garten and Arve (stationery practice). This is an intended
  exception to the platform's "no labels" rule, for guest pages only.
- **Old keys keep working**: weddings move atelier → Salon and panorama →
  Arve (Garten stays Garten, redesigned); `WeddingTemplate::fromKey()`
  reads the old keys in venue-demo links; `/beispiel/atelier` and
  `/beispiel/panorama` redirect.

## Consequences

- A new design is a design project: a template component, a stylesheet in
  `resources/css/designs/`, a palette in `theme/designs.css`, its faces,
  its preview images (`public/images/designs/`) and an update to this ADR.
- The landing studio and the setup's look step show the designs as real
  screenshots of `/beispiel/{design}`; regenerate them when a design
  changes.
- Couples who want other colours or fonts book the Massanfertigung; the
  look step points there.
- Guest pages no longer load GSAP or three.js; first paint carries one or
  two preloaded font files per design.
