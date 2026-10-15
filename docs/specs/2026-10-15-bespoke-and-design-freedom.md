# Massanfertigung, design freedom and venue photos

Status: approved 2026-10-15 by the founder ("lass die 3 … kannst die umbau
fähigkeit auch etwas erhöhen aber grosses ist auch ein produkt offerieren …
für diese person dann eine seite bauen"; "Fotos der Location: ja mach das").

## 1. More freedom inside the three templates

The three templates stay. Under Look › **Feinschliff** (setup and Eckdaten):

- **Own colour.** Any colour instead of the five Farbwelten. Its hue and
  chroma go on the themes' lightness ladder (lib/accent.ts, wedding.css), so
  it keeps WCAG AA on porcelain whatever is picked (ADR 0013). It follows
  the couple to every guest page (site, reply, photos, newspaper,
  programme, reminders) and the setup and dashboard previews.
- **Sections.** The couple may leave out the opening veil, «Haltet euch
  den Tag frei», the ornaments and the closing call. Everything a guest
  needs to answer always stays (rule 1).

## 2. «Massanfertigung» (bespoke)

A product, not a builder: the couple tells us what they want, the team
builds the site on the couple's own pages.

- Page «Massanfertigung» for couples: what it is (three steps), the price
  (one-time, `HEREBY_PRICE_BESPOKE`, CHF 690 until the founder sets it),
  booking through Stripe like every extra, included in Couture.
- The brief: wishes, up to three colours, mood in a few words, links, a
  date it should be ready by, and up to twelve inspiration images (private
  web copies on the `memories` disk; only couple and team open them; they
  leave with the wedding). Saved as a draft; sent once booked, which mails
  the team inbox.
- The team's «Massanfertigung» list: every sent brief, open work first,
  with a status (eingegangen, in Arbeit, fertig) and a note the couple sees
  on their page. A ready brief points the couple to the preview.

## 3. Venue photos (3.3)

Venues upload up to six photos on «Profil». The first leads their public
page and every tour preview, in place of the couple's photos.

## Out of scope

Free layouts or own fonts (Archivo only, CLAUDE.md rule 6); a builder with
drag and drop (3.1); payment by invoice for bespoke work.
