# Couple photos and the landing's designs on the guest site

Date: 2026-10-13 · Status: approved by the founder in chat ("Fotos + heller Grund")

## Problem

The landing studio shows Atelier, Garten and Panorama as photo-led sites
on porcelain, where a theme only changes the accent. The real guest sites
had no photo at all, and the coloured themes painted the whole page (Alpine
became a dark blue page). A couple who chose a design on the landing got
something else.

## Decision

1. **Couple photos.** In «Inhalte», a couple uploads up to four photos of
   themselves. The first is the cover; any photo can be made the cover;
   any can be removed.
   - Stored on the `memories` disk (server volume in Switzerland) under the
     wedding's directory, so they leave with the wedding (ADR 0007).
   - Only a web copy is kept: upright, longest edge 1600px, JPEG 82. The
     original is not stored. Guest pages stay within rule 2.
   - Served through `/paarfoto/{token}`: an unguessable token per photo,
     `noindex`, cached for a day. Works in the couple's preview, on
     personal links and on a couple's own domain.
   - Not guest data: kept memories (ADR 0011) do not strip them.
2. **Heroes as on the landing.** The guest templates take the landing's
   heroes: Atelier sets the names over the cover photo; Garten puts it under
   an arch between two branches; Panorama runs the names as a band over a
   strip of up to three photos. Without photos each hero still stands:
   Atelier on the night ground, Garten's arch in the accent's light tint,
   Panorama without the strip.
3. **Light ground, accent themes** (ADR 0012). Every theme is porcelain
   and night ink; a theme sets only the accent (`--brand`, `--primary`,
   `--ring`, `--brand-muted`), as the landing's accent chips do.

4. **Focal point.** A tap on a photo in «Inhalte» marks the spot that must
   stay in view (usually their faces); every template crops around it
   (`object-position`). Centre by default.

## Out of scope

Free cropping, captions, more than four photos, video.

## Tests

Upload (owner only, image only, at most four, resized, stored under the
wedding), cover, focal point (0–100, owner only), delete (file gone), serving by token (unknown token 404,
noindex), photos in the invitation payload in order, removal with the
wedding.
