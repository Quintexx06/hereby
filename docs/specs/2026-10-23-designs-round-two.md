# The five designs, second round

Date: 2026-10-23 · Status: approved by the founder in chat (review of the
live samples, see [ADR 0016](../decisions/0016-designs-with-their-own-moments.md))
· Follows [2026-10-22-five-designs](2026-10-22-five-designs.md)

## Problem

After the first round the samples read as one design in five colours:
four serif faces on cream, the same header (photo, then names), the same
sections and a dark closing band. Salon was empty on a wide screen, Arve
and Lido looked alike, Garten had lost its arch, branches and petals, and
Raster lacked icons.

## Scope

- **Garten**: the version from before ADR 0015, restored from git
  (`6320cea`) and checked against it on desktop and phone; its photos
  extend from three to seven arches in rows of three.
- **Salon**: a grand hotel's salon at night. A claret wall with a faint
  regency stripe, gilt frames, Ballet for the names and the parts of the
  day, Tenor Sans for everything else. The couple's photo hangs from the
  ampersand between their names; the letter lies on an ivory card between
  two frames; the other photos hang salon-style (one to five, the largest
  in the middle); a hand-lamp light follows a fine pointer; flutes toast
  above the last question.
- **Arve**: the Alps on a Swiss map. Sofia Sans Condensed; snow, granite,
  glacier water, the trail sign's yellow and the trail marker's red. The
  landscape full height under contour lines that draw themselves, the
  names like a ski poster with a trail sign counting the days, the day as
  a signpost, the photos as a mosaic rising into view, the story as an
  ascent to the summit (the wedding), the alpine night with stars.
- **Lido**: a summer at the lake bath, same colours. Shrikhand and
  Figtree. The awning with a scalloped edge, the photo cut out as a sun
  going down into the lake in stripes, drifting waves, the letter as an
  entry ticket with its stub, the day in pool lanes with bead ropes, the
  photos cut into a sun, a pebble, a scalloped print, a capsule and a
  tile that bob, the last question on the deep lake.
- **Raster**: pictograms at every section and over each practical
  chapter, a sign for each part of the day, signs on calendar and route
  links, a numbered photo section.
- **Photos**: couples upload up to eight; samples use six stock photos,
  each design opening on its own (Salon the sparklers, Arve the lake,
  Lido the sunset, the others the veil).
- **Weight**: each design is its own chunk and its own inlined
  stylesheet; effects are CSS, or GSAP and three.js after the first
  interaction; photos below the first screen load at low priority.

### Non-goals

- Any choice of colour, font or section by the couple (ADR 0015 stands).
- New content types: every design still shows the same content.

## Edge cases

- No photo: Salon's frame holds the initials on velvet, Garten's arch the
  initials, Arve the alpenglow, Lido a sunset sun, Raster type alone.
- One to seven extra photos: Salon hangs up to two beside the card and up
  to five on the wall; the others lay out all seven.
- Long names and latin-ext names: every face ships latin-ext; names wrap.
- Reduced motion: the veil, petals, contour drawing, waves, bobbing,
  hang-in and scroll reveals are off; 3D shows a still frame.

## Acceptance criteria

- The five samples are clearly different at first sight on a 1440 px
  screen and on a phone, with no empty bands.
- Garten matches the pre-ADR-0015 Garten.
- Text AA on every ground.
- The demo invitation and its reply page stay inside the performance
  budget (LCP < 2 s, ≤ 200 KB on slow 4G).
- `composer ci:check` passes.
