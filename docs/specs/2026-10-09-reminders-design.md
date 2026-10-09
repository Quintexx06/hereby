# Reminders before the deadline

**Status:** Implemented 2026-10-09 (decisions taken by the build). Roadmap: 1.10

## Problem

Couples chase replies by hand in the last two weeks. Reminders from other
tools "look like phishing". Ours come from the couple by name, carry the
guest's own link, and stop the moment the household answers or says stop.

## Decisions

1. **When:** 14, 7 and 2 days before the RSVP deadline (Swiss time), from a
   daily run at 10:07. A stage is also sent the day after, in case a run was
   missed. A wedding that starts late (deadline 5 days away) gets only the
   stages still ahead (2), never a burst of all three.
2. **Who:** households of an active wedding that
   - have an email address
   - have not answered
   - have not opted out
   - have not had this stage yet (`households.reminder_stage` stores the last one sent)
3. **What:** in the household's language, from "{Paar} via Hereby", subject "{Paar}: bitte antwortet bis {Datum}". The body has one button, their personal link, and a one-click opt-out link (signed URL, also as a `List-Unsubscribe` header).
4. **Couple control:** "Erinnerungen automatisch senden" on the Antwortformular
   (on by default), with the dates the next reminders go out.

## Data

- `weddings.sends_reminders` (boolean, default true)
- `households.reminder_stage` (unsigned tinyint, nullable)
- `households.reminders_opted_out_at` (timestamp, nullable)

## Acceptance criteria (tests)

- [x] At 14 days, unanswered households with an address get one reminder, in their language, with their link
- [x] Nobody gets the same stage twice; answered, opted-out or address-less households get none
- [x] Weddings that turned reminders off send none
- [x] The opt-out link only works when signed and stops further reminders
