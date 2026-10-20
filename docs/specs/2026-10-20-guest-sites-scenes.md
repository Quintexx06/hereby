# Guest sites with the landing's moments

**Why.** After the desktop spread the sites were tidy but read as an empty
canvas next to the landing page: one photo, black on porcelain, no motion of
their own. They should feel like the evening the landing promises.

**What.**

- `GuestScene`: a decorative three.js layer for guest pages. Fetched only
  after the first scroll, wheel, touch or key and only near the screen
  (rule 2); none on data saver or without WebGL2; a still frame under
  reduced motion; pauses off screen.
- Atelier hero: the photo edge to edge, the sparkler heart over it, names
  up to 11rem with an italic ampersand, the days to go as a figure with an
  italic unit (`invitation.countdown_unit`, four languages).
- Atelier programme as acts (`InvitationActs`) on a night band: a photo of
  the couple's per act, roman numeral, time large, place in italic. From
  `lg` the row pins and travels sideways (`atelierMotion`, `pinSpacing`
  forced on because the page is a flex column; the same fix for Panorama).
- `InvitationTicker`: the day's parts as a running band, solid and outlined
  italic in turn, leaning with scroll speed.
- The greeting's names in the accent's italic; the footer's names large.
- `--tint` (8% of the accent in porcelain) grounds the practical chapters
  (full-bleed via border-image), Garten's programme arch and the footer.
- The couple's photos open like a curtain (`sharedMotion`).
- Garten: 3D petals in the hero (CSS petals until they load). All
  templates: two rings turning above «Kommt ihr?».

**Budget.** The invitation chunk stays ~12 kB gzip and the guest CSS
~22 kB; three.js (~140 kB gzip) and each scene load only after interaction.
The italic Archivo face is declared in `guest.css` and fetched only where
italic is set.
