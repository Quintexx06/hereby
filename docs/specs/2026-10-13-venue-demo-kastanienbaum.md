# Venue demo: Seehotel Kastanienbaum (roadmap 0.2)

Date: 2026-10-13 · Status: built as a seeder, to be shown to the venue

## Why this venue

- **Seehotel Kastanienbaum**, St. Niklausenstrasse 105, 6047 Kastanienbaum
  (Horw peninsula, Lake Lucerne). Lakeside restaurant with pavilion and
  terrace, banquet rooms for up to 120, about 42 rooms (most with balcony
  and lake view), its own boat landing stage.
- **It is exactly the wedding Hereby is built for**: guests arrive by train
  to Lucerne and then by bus 21 (about 20 minutes, every 30 minutes, stop
  opposite the hotel) or, in summer, by scheduled boat to the hotel's own
  landing stage. Many stay the night. That is travel, rooms and a way home
  per household, which a PDF invitation cannot do and Hereby can.
- **Reachable**: an independent mid-size house with a lakeside wedding
  offer, 15 minutes from Lucerne, close to the TrauDich! fair audience
  (0.1). A venue that hosts weddings every summer weekend gains from
  guests who know where to go and when the last bus leaves.

Sources (checked 2026-10-13): Bridebook venue listing, MySwitzerland,
the hotel's «Kontakt & Anreise» page, Eawag's directions to Kastanienbaum.

## The demo

`php artisan db:seed --class=KastanienbaumDemoSeeder`

- A fictional couple, **Lea & Jonas**, Saturday 12 June 2027, Atelier
  template, Alpine accent, Atelier plan (Der Abend unlocked), published.
- The day at the hotel: free ceremony on the lakeshore, apéro on the
  terrace, dinner in the pavilion, party until late. Real address.
- Content: how to get there (train, bus 21, summer boat, free parking),
  rooms at the hotel, dress code for a lakeshore ceremony, FAQ.
- Five households in German, French and English with different parts of
  the day, tables with a line from the couple.
- Three couple photos from the licensed landing set (CREDITS.md).
- The venue appears as the place of the wedding, never as a sponsor or
  partner (rule 3), and nothing claims an agreement with the hotel.

## Before showing it

The way home is left empty on purpose: the last connections (bus 21 to
Luzern Bahnhof, the last boat in summer) must come from the SBB timetable
for the actual date, not from memory. Enter them under «Der Abend ›
Heimweg» (two minutes), then open a household link on a phone over 4G.
