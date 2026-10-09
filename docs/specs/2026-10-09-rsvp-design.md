# The 60-second RSVP

**Status:** Implemented 2026-10-09 (decisions taken by the build, listed below; a timed test with 5 people is still open). Roadmap: 1.4
**Product source:** [vision](../product/vision.md) rule 1, [features](../product/features.md)

## Problem

A guest opens their personal link today and reads "Die Anmeldung öffnet bald."
They cannot answer. Rule 1 says a guest answers **in under 60 seconds on a
phone, with no account and no app**. Competitors lose guests at two points:
long forms that ask everyone everything, and plus-ones or children the couple
had to type in by hand first (see the [setup spec](2026-10-09-couple-setup-and-dashboard-design.md#what-couples-complain-about-research-2026-10-09)).

## Users

- **Guests:** one household per link, one person usually answers for all. On a
  phone, one-handed, in their own language.
- **Couples:** decide which questions are asked, then see the answers in the
  dashboard and guest list.

## Scope

In:
- the reply form on the personal link (`/i/{token}/antwort`)
- attendance per person and per event the household is invited to
- menu choice at the dinner, with a children's menu for children
- allergies and intolerances per attending person (encrypted, write-only)
- naming a plus-one when the household may bring one
- household questions: shuttle seats, overnight stay, a song wish
- the couple's "Antwortformular" page: which of these questions are asked
- a summary of the answer on the invitation, changeable until the deadline

Out (later items):
- confirmation email and calendar file (1.5)
- reminders (1.10)
- exports for kitchen and service (1.12)
- a free message to the couple, table wishes, transport details beyond seats

## Decisions

1. **One screen, not a wizard.** Steps cost taps. The form shows every event
   as a block with one row per person; everything else unfolds only when it
   applies. A household where everyone comes answers with **one tap** on
   "Wir sind alle dabei" and one on "Antwort senden".
2. **Asks only what applies.**
   - The menu appears only for people marked "dabei" at the **dinner**, and only if
     the couple listed menus.
   - Children get the children's menu option first, if the couple offers one.
   - The allergy field appears per attending person behind "Allergien oder
     Unverträglichkeiten?".
   - Shuttle, stay and song appear only if the couple turned them on, and only
     once someone is attending.
3. **Allergies are write-only.** `guests.dietary_notes` is encrypted and never
   sent to the browser (CLAUDE.md rule 9). When it is filled, the guest sees
   "Angegeben. Nur ausfüllen, um es zu ändern." An empty field on save keeps the
   old value, and "Entfernen" clears it.
4. **The plus-one is a person, not a number.** If `plus_one_allowed`, the guest
   can add "Begleitung" with a first name. It is stored as a guest with
   `is_plus_one` and is invited to the same events as the household. Removing
   the name removes that guest.
5. **Changeable until the deadline.** After saving, the invitation shows the
   answer with "Antwort ändern". After `rsvp_deadline`, the form is read-only
   and says who to contact (the couple's names).
6. **Everything in the guest's language** through `t()` (ADR 0006), in
   de-CH, en, fr and it. fr and it are drafts until native review (2.7).
7. **Defaults for the couple:** no menus, children's menu off, shuttle off,
   stay off, song wish on. Nothing is asked that the couple didn't choose.

## UX flow (guest)

```
Invitation ──"Jetzt antworten"──▶ Reply form ──"Antwort senden"──▶ Invitation
                                                                   with "Eure Antwort"
                                                                   + "Antwort ändern"
```

Reply form, top to bottom:

1. The couple's names and the deadline, then **"Wir sind alle dabei"** (sets
   every person to "dabei" at every event).
2. One block per event (name, day, time). In it, one row per person with two
   segmented buttons: **Dabei** / **Leider nicht**.
3. Under each attending person at the dinner: menu chips (one choice).
4. "Allergien oder Unverträglichkeiten?" opens a text field per attending
   person (up to 500 characters).
5. Plus-one (if allowed): "Begleitung mitbringen", a first-name field.
6. Household questions (if on): shuttle seats (stepper, 0 to the number of
   people attending), overnight stay (yes/no), song wish (one line).
7. Sticky footer: **"Antwort senden"**, disabled until every person has an
   answer for every event. The hint says how many answers are missing.

Validation errors appear inline in the guest's language. Touch targets are at
least 44px.

## Data model

`weddings` (the couple's choices):

| Column           | Type          | Default |
| ---------------- | ------------- | ------- |
| `menu_options`   | json, nullable | null (no menu question) |
| `children_menu`  | boolean       | false   |
| `offers_shuttle` | boolean       | false   |
| `offers_stay`    | boolean       | false   |
| `asks_song`      | boolean       | true    |

`households` (one answer per household):

| Column          | Type              |
| --------------- | ----------------- |
| `shuttle_seats` | unsigned tinyint, nullable |
| `needs_stay`    | boolean, nullable |
| `song_wish`     | string(160), nullable |
| `responded_at`  | timestamp, nullable |

`event_responses` (exists): status `attending` / `declined`, `menu_choice` is
a menu key, or `children`.

`menu_options` holds `[{ "key": "fleisch", "label": "Kalbsfilet, Kartoffelgratin" }]`.
Labels are written by the couple in one language. They are not translated.

## Couple side

- **Antwortformular** (`/hochzeit/{id}/antwortformular`, sidebar item):
  - menus: up to 4, each a short label; "Kein Menü zur Auswahl" when empty
  - toggles for the children's menu, shuttle, stay and song wish
  - a note that the deadline is set in the setup
- The dashboard and guest list already count answered households. Their "Geantwortet"
  now fills from real replies.

## Edge cases

- **A household invited to no events:** the form says so. This can't happen through the editor,
  which requires at least one event.
- **The couple removes a menu after replies:** the stored choice stays. The summary shows
  the raw label, if known, or nothing.
- **The deadline passes while the form is open:** the server rejects with a message
  in the guest's language, and the page reloads read-only.
- **A guest submits a person or event that isn't theirs:** 422 (the ids are scoped
  to the household and its events).
- **The link is renewed by the couple:** the old link 404s (already built).

## Acceptance criteria (tests)

- [x] The reply page renders only the household's guests and events, in its locale
- [x] Saving stores one response per guest and event, and stamps `responded_at`
- [x] The menu is required only for attending guests at the dinner when menus exist
- [x] Children may choose `children` only if the couple offers it
- [x] Allergies are stored encrypted, never appear in any Inertia prop, and
      an empty field keeps the stored value
- [x] Plus-one: adding creates an `is_plus_one` guest invited to the household's
      events. Removing it deletes that guest. Not allowed means 422
- [x] Shuttle, stay and song are ignored unless the couple offers them
- [x] Foreign guest or event ids are rejected
- [x] After the deadline, saving is refused and the page is read-only
- [x] The couple can save the Antwortformular. Only the owner can (policy)
