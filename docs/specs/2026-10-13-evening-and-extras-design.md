# «Der Abend» and Extras: design

Status: approved 2026-10-13 by the founder ("implement them all"), from the
premium-feature analysis of the same day. Adds to the tiers in
[business.md](../product/business.md) and the paywall spec
([2026-10-12](2026-10-12-paywall-and-memories-design.md)).

## The idea in one line

**Atelier is "the evening runs itself".** Signature takes the stress out of
the replies; Atelier takes it out of the day: who sits where, who speaks
when, who is in which group photo and how everyone gets home. A few extras
can be bought on any plan.

## What is in which plan

| Feature | Signature | Atelier | Couture | Add-on |
| --- | --- | --- | --- | --- |
| Table mates on the personal link | | ✓ | ✓ | |
| Way-home reminder | | ✓ | ✓ | |
| Group photo call-up | | ✓ | ✓ | |
| Programme sign-ups (Einlagen) + witness link | | ✓ | ✓ | |
| Wedding newspaper (Hochzeitszeitung) | | | ✓ | CHF 149 |
| «Wir fassen nach» (the team chases late replies) | | | ✓ | CHF 190 |
| Own domain | | | ✓ | CHF 49 |
| Keep the memories one more year | | | | CHF 39 / year (Atelier and up) |

Add-ons are one-time Stripe Checkout payments through the existing flow
(metadata `addon` instead of `plan`), recorded in `wedding_addons`.
«Keep the memories» is bought once per year, not a subscription: no
stored card, no renewal surprises; each purchase adds a year.

## Features

### 1. Table mates («Wer sitzt mit euch am Tisch»)
- Each guest gets an optional `table_name` and a `table_note`: one line from
  the couple ("Lena, Annas Studienfreundin. Fragt sie nach Island.").
- The couple fills them in on «Tischplan» and publishes the plan when ready
  (`seating_published_at`). Until then guests see nothing.
- On the personal link: the household's table(s), and for each table the
  first names and notes of everyone else seated there. Never last names,
  never dietary data, never households that aren't at that table.

### 2. Way home («Heimweg»)
- The couple lists the last connections of the night (label, place, time;
  times before noon belong to the night after the wedding day).
- On the wedding day the personal link shows them with a live countdown.
- 30 minutes before each one, every household that is attending and left an
  email gets one short mail in its language. Sent once (`way_home_sent`).

### 3. Group photos («Gruppenfotos»)
- The couple lists the groups and picks the households in each.
- On the day the witnesses call a group from their link: its households get
  a mail, and their personal link shows a banner for 20 minutes.

### 4. Programme sign-ups («Einlagen») and the witness link
- Guests offer a speech, game, song, video or something else from their
  personal link: title, minutes, what they need. After the reply, never
  before it (rule 1). They can withdraw it while it is still open.
- Swiss custom: the evening's surprises are the witnesses' business. So the
  couple shares one private **witness link** (`witness_token`, like the photo
  link); there the witnesses accept or decline, order, and see the total
  against the couple's time budget (`programme_minutes`). They also print
  the running order.
- The couple sees only counts and minutes, not what is planned.

### 5. Wedding newspaper («Hochzeitszeitung»)
- One contribution link (`newspaper_token`): name, headline, text, an
  optional photo. No account.
- The witnesses see the articles on their link, remove what doesn't belong
  and print the newspaper: A4, in the couple's names and date, from the
  browser (no PDF library, as the kitchen sheet).
- Photos sit on the private `memories` disk and leave with the wedding.

### 6. Keep the memories
- After the retention window (12 months), guest data still goes as ADR 0007
  says: households, guests, answers, sign-ups, groups.
- With «Erinnerungen behalten» paid, the wedding itself, its content, the
  guest photos and the newspaper stay until `memories_kept_until`; then
  everything goes. Each purchase adds 12 months. ADR 0011.

### 7. «Wir fassen nach» (chase service)
- Couples add an optional phone number per household.
- The team's admin lists, for weddings with the service whose deadline is
  within 14 days, every household that hasn't answered, with its contacts,
  and marks each one as contacted (`chased_at`).

### 8. Own domain
- The couple enters a domain; the team connects DNS and TLS by hand and
  marks it connected. From then on every personal link (guest list, mails,
  calendar) uses that domain. On that domain only the wedding's own guest
  pages answer; everything else is 404.

## Product rules

1. Nothing new stands between a guest and the 60-second reply; offers to
   sign up or contribute come after it.
2. The guest page gains no heavy assets: text and a countdown only.
3. No ads, no vendor offers.
4. Guest data is deleted on schedule, also with memories kept (ADR 0011).
   Table mates expose first names and the couple's note only, and only once
   the couple publishes the plan.
5. Each feature is gated, tested, and switched off for plans that don't
   include it.

## Out of scope (later)

SMS or push (mail and the personal link only), drag-and-drop floor plans
(2.3), roles beyond the witness link (2.5), automatic DNS/TLS.
