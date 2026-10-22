# Five finished designs instead of the page builder

Date: 2026-10-22 · Status: approved by the founder in chat («deaktiviere
pagebuilder … auswahl von guten 5 … wo der inhalt angepasst werden kann
und alles andere ist custom von uns») · Decision: [ADR 0015](../decisions/0015-five-finished-designs.md)

## Problem

The guest sites did not look professional. They reused the landing's
effects (3D, tickers, pinned scenes), set Bodoni Moda, Cormorant Garamond
and Fraunces over Archivo, and coloured everything in soft pastels. The
builder (three templates × five themes × an own accent × hidden sections ×
style directions) made combinations nobody had designed together.

## Research (summary)

Search-based, because the proxy blocks most competitor sites; sources are
listed in the research notes of this session and in ADR 0015.

- **Premium means curation.** Riley & Grey's designs are template-based and
  not fully customisable (fixed colours for all but two); Minted's websites
  continue a printed suite; Zola's collections are each one typographic
  idea. Joy's hundreds of themes read as mass-market.
- **What to avoid.** Cormorant with Lora, sage and cream and gold is the
  look of AI-made wedding sites (Lovable templates); Inter, Tailwind
  palettes, Fraunces and Bodoni Moda are listed as AI tells. The editorial
  magazine look with scroll animations, countdowns and envelope openings
  is now stock on Framer and Wix.
- **What designers set.** On printed invitations: grotesks (Univers, Neue
  Haas, News Gothic) or one engraved face at one size; letterpress is a
  trend in Zürich and Luzern; Swiss Style (grid, flush left, one accent).
- **Colour 2025–26.** Deep and earthy tones (oxblood, chocolate, fig,
  olive, midnight) with one saturated note (cobalt; primary yellow after
  butter); Pantone's 2026 colour is a white (Cloud Dancer, #F0EEE9).
- **Fonts.** About 25 OFL faces checked for licence, npm availability,
  size and OpenType features. Fontsource's latin files drop small caps and
  old-style or tabular figures, so the designs never depend on them.

## Scope

- Five designs, fixed in layout, type and colour: **Salon** (black tie),
  **Raster** (Swiss grid, modern), **Garten** (private-press book,
  countryside), **Arve** (mountain lodge), **Lido** (lake bath, summer).
  Faces, palettes and signature details are in ADR 0015.
- Every design shows the same content: the household's greeting and the
  letter with the reply, the day's programme with routes, the evening
  (Atelier plan), photos, story, place, travel, stay, dress code,
  questions, the closing with the calendar, the footer.
- Every guest page (reply, programme sign-ups, memories, newspaper,
  reminders stopped) takes the design's paper, ink, accent, faces and
  button shape.
- Setup and «Eckdaten» › Look: pick one of five, shown as real
  screenshots, each with its sample site; a suggestion from the venue
  (Ticino → Lido, Alps → Arve, cities → Raster, a day feast → Garten,
  else Salon); a pointer to the Massanfertigung for anything else.
- Landing studio: the five as real first screens (desktop and phone) with
  «Live ansehen»; samples at `/beispiel/{design}`.

### Non-goals

- Any choice of colour, font, section or style by the couple.
- Motion beyond a short fade and press feedback; 3D on guest pages.
- Dropping the builder's columns (kept, unread; a later migration).

## Data model

- `weddings.template`: `salon | raster | garten | arve | lido`, default
  `salon`; atelier → salon, panorama → arve, garten stays.
- `weddings.theme` nullable; `theme`, `accent_color`, `hidden_sections`,
  `look_styles`, `look_wishes` are no longer read or written.

## Edge cases

- No photo: Salon and Raster open on type alone; Garten, Arve and Lido
  open without their plate, panorama or postcard.
- One to three extra photos: every design lays out 1, 2 or 3.
- Long names, latin-ext names: names may break; every face ships
  latin-ext.
- Old links: venue demos with `?vorlage=atelier|panorama` open the closest
  design; old sample URLs redirect.

## Acceptance criteria

- The five samples look finished on a 1440 px screen and on a phone, with
  no empty bands, overlaps or pastel grounds; the reply is one tap away.
- Text colours pass WCAG AA on their grounds.
- Guest pages preload only their design's faces and load no GSAP or
  three.js.
- Setup saves only the design; unknown designs are refused.
- `composer ci:check` passes.
