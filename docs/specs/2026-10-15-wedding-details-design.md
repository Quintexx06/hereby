# «Eckdaten»: edit the wedding after setup

Status: approved 2026-10-15 by the founder ("make sure … available to edit
infos in the dashboard somewhere").

## Problem

The seven setup steps are the only place where the couple sets their names,
date, deadline, venue, programme, languages and look. Once the site is live,
setup redirects to the dashboard, so none of it can be changed: a moved
date, a new venue or a later dinner needs the team.

## Decision

One page, **Eckdaten** (`hochzeit/{wedding}/eckdaten/{step}`), in the
sidebar of an active wedding and on the dashboard's shortcuts. It shows the
same six setup questions as tabs (Paar, Datum, Ort, Ablauf, Gäste, Look;
not the review) with the same components, and saves one tab at a time.

Live weddings have guests, invitations and answers, so saving must never
lose them:

- **Programme** updates events in place: each row takes the next existing
  event of its type (keeping its id, invitations and answers), new rows
  create events, rows the couple removed delete theirs. On a live wedding a
  new part is offered to every household; the couple narrows it per
  household on «Gäste» as before.
- **Date** moves every event by the same number of days, keeping its Swiss
  local time.
- **Venue** repoints events at the venue, as in setup.

Guests see the change on their next visit, and calendars subscribed via
webcal refresh within 12 hours (the .ics already carries the new times).

## Out of scope

Telling guests about a change by mail (the couple can share the link
again); editing the URL or the plan.
