# Partner accounts: a first version of 2.4, 3.3 and 3.7

Status: approved 2026-10-15 by the founder ("kannst du schon eine grobe
version machen"). A rough cut ahead of the venue pilots (0.5): enough to
show a venue, a photographer or a planner what they would get, and to run
the first pilots by hand. Each part is finished and tested; what is left
out is listed at the end.

## One account type for three partners

A **partner** is a venue, a photographer or a planner (`PartnerType`). The
team creates it on «Partner» in the admin area with a name, the type and
the email of the person who signs in; that person gets a link to choose a
password, as couples set up by hand do. A user belongs to at most one
partner (`users.partner_id`, set by the team only).

Every partner has a **connect code** (eight letters, e.g. `KAST-7Q2M`). The
partner gives it to their couples; **the couple connects** on their own
page «Partner» and can disconnect at any time. Nothing of a wedding is
visible to a partner before the couple connects (consent, ADR 0007).

**Two more ways in** (added 2026-10-15): the partner's **link**
`/verbinden/{code}` (login keeps it as the intended page, then «Partner»
offers one-click connect), and the couple's **invitation by mail** for a
partner not on Hereby yet: the link `/partner/beitreten/{token}` opens a
partner account already connected to the wedding. A partner already on
Hereby is connected at once instead. Unused invitations are deleted after
30 days. The team can correct every partner's name, kind, sign-in, contact
and Showcase on «Partner» in the admin area, and resend the sign-in link.

## What each partner sees (2.4, 3.7)

On `/partner`, instead of the couple's pages:

- **Kalender**: every connected wedding by date: couple, date, deadline
  and whether answers are still open, invited / attending / open, and the
  menu counts. Never a guest's name on this page.
- **Venue and planner**: the final-numbers sheet per wedding, the same
  printable sheet the couple has on «Küche & Service» (counts, menus,
  allergies per table). Allergy notes are rendered on the server only,
  as for the couple (CLAUDE.md rule 9).
- **Photographer**: an upload page per wedding. Photos land in the couple's
  «Erinnerungen», marked as the photographer's, and leave with the wedding.
  Works for weddings whose plan includes the memories.
- **Profil**: name, contact, website; for venues the address, capacity,
  how to get there and rooms. **Entered once**: when a couple connects a
  venue, an empty venue on the wedding is filled from the profile, and the
  venue's travel and rooms texts become the «Anreise» and «Übernachten»
  blocks if the couple has none yet.

## Showcase venues (3.3)

The team marks a venue as **Showcase** (plan Venue Showcase, business.md).
It then gets:

- **Vorschau für die Besichtigung**: two names and a date on the venue's
  dashboard show a finished-looking site at that venue in the venue's
  profile, with a code to hand over. Nothing is stored.
- **A public venue page** `/orte/{slug}` with the profile and a form to ask
  for a tour. Requests are stored for the venue (name, email, preferred
  date, message), mailed to it, and deleted after 12 months.
- **One venue credit line** on the guest pages of connected weddings, in
  the footer: «Gefeiert im Seehotel Kastanienbaum», linking to that page.
  This is the one credit line product rule 3 allows; no offers, no ads.

## Out of scope for this cut

Venue film; floor plans and seating handoff on the plan (2.3); room blocks;
a signed-off final-numbers lock; planners editing a couple's site; venue
billing (3.8). Partner pages are German only, like the couple's.
