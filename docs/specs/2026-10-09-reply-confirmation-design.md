# Reply confirmation and calendar entry

**Status:** Implemented 2026-10-09 (decisions taken by the build). Roadmap: 1.5
**Builds on:** [the 60-second RSVP](2026-10-09-rsvp-design.md), which already lets guests change their answer until the deadline and locks it afterwards.

## Problem

After answering, guests want proof and a reminder in their own calendar.
Competitors' reminder emails "look like phishing" (setup spec research), so
ours must be unmistakably from the couple.

## Decisions

1. **Email only if the household gave an address.** The reply form gets an
   optional field, "E-Mail für eine Bestätigung", filled with the address the
   couple entered. The field updates `households.email`, which reminders (1.10) use too.
2. **Sent after every save,** in the household's language, queued.
   - **Subject and sender name** carry the couple's names: "Anna & Luca: eure Antwort ist da".
   - **Body:** who comes to which part of the day, with menus and the plus-one; the personal link to change the answer; the deadline.
   - **Never sent:** allergies (only "angegeben") and anything about other households.
3. **Calendar file** (`/i/{token}/kalender.ics`):
   - **Events included:** the parts of the day the household attends, or every part it is invited to before it answers.
   - **Format:** times in UTC, each with the venue's address and the personal link.
   - **Delivery:** attached to the email, and linked on the invitation ("In den Kalender").

## Acceptance criteria (tests)

- [x] Saving a reply with an email queues the confirmation to that address, in the household's language, with the calendar attached
- [x] No email address means no email; the address can be added or changed in the form
- [x] The email never contains allergy text
- [x] The calendar holds the attended parts (all invited parts before replying), with times in UTC
