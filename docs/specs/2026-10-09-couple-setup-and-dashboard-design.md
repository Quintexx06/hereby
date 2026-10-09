# Couple setup, guest import and dashboard

**Status:** Implemented 2026-10-09. Roadmap: 1.1 (couple side), 1.9, 1.11
**Product source:** [vision](../product/vision.md), [features](../product/features.md)

## Problem

A couple who signs up today lands on an empty starter-kit dashboard. The
vision promises "set up for you within 48 hours of a 20-minute intake". This
spec turns that intake into a guided, self-paced setup the couple can finish
on a phone in one evening, followed by a dashboard that answers one question:
**what needs us today?**

## What couples complain about (research, 2026-10-09)

The evidence is thin and mostly from reviews, so we treat it as signals, not proof.

| Signal | Source | Our answer |
| --- | --- | --- |
| Plus-ones and children must be typed in by hand before anyone can reply | Zola help centre | Households are first-class. "+1 allowed" is a household switch, and the guest names the partner when they reply (Phase 1.4) |
| Editing people on the guest list is buggy | Joy, Trustpilot | Every import goes through a **preview** that shows duplicates before anything is saved |
| "Difficult to set up", "can't reorder sections", lots of bugs | GettingMarried, Trustpilot | Seven short steps, one decision each. Progress saves after every step |
| Glitchy and slow | Joy, Trustpilot | Server-rendered steps, no heavy client state, no 3D in the app |
| Reminder texts "look like phishing" | Joy, Trustpilot | Reminders always carry the couple's names and their own link (Phase 1.10) |
| The caterer wants a CSV; retyping is failure | Mixily blog | Venue and event data are captured structurally from day one (export: 1.12) |

## Scope

In:
- the setup wizard
- Swiss address search for the venue
- guest import in four ways, with a preview
- the dashboard (today view, counts, households)

Out:
- the RSVP form (1.4)
- reminders (1.10)
- exports (1.12)
- payment

## The setup: seven steps, one decision each

`/hochzeit/neu` creates a **draft** wedding. Every step saves on "Weiter", so a
couple can stop anywhere and resume from the dashboard ("Weiter einrichten,
Schritt 3 von 7"). Steps can be revisited in any order once reached.

| # | Step | Question | Fields |
| - | ---- | -------- | ------ |
| 1 | Ihr zwei | What are your names? | `partner_one`, `partner_two` → `couple_names` |
| 2 | Datum | When is the big day? | `wedding_date` (required), `rsvp_deadline` (suggested: 6 weeks before) |
| 3 | Ort | Where are you celebrating? | `venue_name`, plus an exact address from the Swiss address register (`venue_address`, `venue_lat`, `venue_lng`, `venue_reference`), or "noch offen" |
| 4 | Ablauf | Which parts does your day have? | `celebration` (day / evening / both), plus event presets with times → `events` |
| 5 | Gäste | How big is the wedding, in which languages? | `guest_estimate` (band), `languages` (≥1 locale), `default_locale` |
| 6 | Look | Which design fits? | `theme`, with a suggestion from the answers so far |
| 7 | Übersicht | Is everything right? | Review with "Ändern" links → **Website erstellen** |

Decisions:

- **The date is required.** Every event needs a date, and couples buying a CHF
  390+ site have booked their venue. The RSVP deadline defaults to 6 weeks before the date.
- **Day or evening** sets the preset times (e.g. day: Trauung 14:00 · Apéro
  15:30 · Dinner 18:30; evening: Trauung 17:00 · Dinner 19:30 · Fest 22:00). A
  separate civil ceremony can be placed on another day (offset −1 day).
- **Guest size is a band** (bis 50 / 50–100 / 100–150 / 150+), not an exact
  number. Couples don't know yet; the guest list makes it exact later.
- **The theme suggestion** is honest and simple: Alpine for lake and mountain
  postcodes (by canton), Riviera for Ticino, Rosé for daytime, Ivory otherwise.

## Venue address search (ADR 0010)

- Source: the federal register of building addresses through the swisstopo
  geo.admin.ch search (`SearchServer`, `type=locations`, `origins=address`).
  Every official Swiss address is in it, with coordinates, and it needs no key.
- We call it **server-side** (`GET /adressen?q=`), so guests' and couples' IPs
  never go to a third party. Results are cached for 7 days and the endpoint is
  throttled (30 per minute per user).
- Results have their `<b>` tags stripped and are split into street, postcode
  and town. On failure the endpoint returns an empty list and the UI offers
  "Adresse von Hand eingeben".
- A combobox with keyboard support: 250 ms debounce, minimum 3 characters,
  arrow keys and Enter.

## Guest import: four ways in, one preview

Couples' lists live in different places, so we accept all of them:

1. **Paste** from Excel, Numbers or Google Sheets. The tab-separated text is
   read with header detection (Name / Vorname / Nachname / Haushalt / Familie /
   E-Mail / Sprache / Kind).
2. **Paste a plain list**, one household per line: `Familie Meier: Heidi, Peter, Lina`
   or `Heidi und Peter Meier`.
3. **Upload a file**: `.csv`, `.tsv`, `.txt`, `.xlsx` (read with ZipArchive, no
   dependency) or `.vcf`, a contact export from the iPhone or Android
   address book.
4. **Add by hand**: one household at a time.

Every way ends in the **same preview**: households with their guests, each
marked new or **possible duplicate** (same normalised name as an existing
guest). The couple can untick rows before "X Haushalte importieren".
Imported households get every event by default; they can be narrowed later.
Limits: 2 MB, 1,000 rows.

## Dashboard

- **No wedding yet:** one invitation to start the setup.
- **Draft:** "Weiter einrichten" with the step.
- **Active:**
  - a header with the couple's names, the date and "noch N Tage"
  - **"Heute für euch"**: up to three next actions, computed:
    1. import guests, if there are no households
    2. share the links, if households have never opened them
    3. remind those who opened but didn't answer, once the deadline is
       less than 14 days away
    4. set the RSVP deadline, if it is missing
  - Counts: households, guests, then never opened / opened, not answered / answered (1.11).
  - The Gäste page: search, segment filters, the personal link per household
    (copy button), and adding or importing households.
  - **Editing a household** (click its name): name, people (add, rename,
    remove; a removed person's answers go with them), email, language, +1,
    and which parts of the day it is invited to (at least one). "Neuen Link
    erstellen" replaces the token, so a link sent to the wrong person stops
    working at once. Removing a household deletes its people and answers.

## Acceptance criteria (tests)

- [x] A couple can only see and change their own weddings (policy)
- [x] Each step validates and saves; the draft resumes at the furthest step
- [x] The programme step creates events from presets on the wedding date
- [x] The address search maps geo.admin results, caches them and degrades to `[]` on failure
- [x] All four import formats parse to the same structure; duplicates are flagged; a 120-row Excel list imports
- [x] Import creates households and guests and attaches all events
- [x] The dashboard shows the right state for none / draft / active
- [x] Editing syncs people and events, rejects another wedding's events and
      households (404), renewing a link kills the old one, removing deletes guests
