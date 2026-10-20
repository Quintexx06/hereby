# Guest sites on wide screens

**Why.** On a laptop the three templates read as a phone column stretched
across empty porcelain: one narrow column, a 60px heading over one line of
text, headings centred over left-aligned text, no photo after the hero, and
Panorama's programme and story frames cut off at the right edge. The landing
studio promised a magazine, a garden and a film; the real sites now deliver
them from `lg` up. Phones keep their single column.

**What.**

- `invitation-layout.css` (guest bundle only, rule 2): everything below.
- Splits (Atelier, Panorama): programme, calendar, place, travel, stay,
  dress code and FAQ as `5fr / 7fr`, heading left and sticky, a hairline
  above each, consecutive chapters pulled together rule to rule.
- Letter: Atelier sets «Liebe …» as a statement beside the text and the
  reply; Panorama as a giant left-aligned welcome; Garten stays centred.
- Programme: Atelier a ledger (time column, hairlines, no nodes); Garten
  the vine down the middle with sides taking turns; Panorama a night band
  whose frames sit side by side when there are four or fewer (no pinned
  sideways scroll then; more than four keep it).
- Story: Garten's arches three across; Panorama's film frames side by side
  when there are three or fewer.
- `InvitationGallery`: the couple's photos after the cover (up to three;
  Panorama one), as a spread, arches or a 21:9 frame. Absent with only the
  cover. Lazy-loaded; settles from a slight zoom where scroll timelines exist.
- `InvitationBlocks` groups consecutive practical blocks (`.inv-practical`);
  Garten sets them as arched cards two by two.

**Rules kept.** Theme tokens only (the night band redeclares them, the
accent as a light tint); strings unchanged (no new copy); reduced motion
drops the photo zoom; no new script on guest pages.
