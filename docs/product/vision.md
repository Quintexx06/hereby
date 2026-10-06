# Product vision: premium wedding websites

> Source: "Premium Wedding Website Platform: Feature Vision and Roadmap", @Martin, 2026-10-06.
> Feature catalogue: [features.md](features.md) · Business: [business.md](business.md) · Roadmap: [../roadmap/ROADMAP.md](../roadmap/ROADMAP.md)

## The bet

A couple spending about CHF 40,000 on a wedding will pay CHF 400–1,900 for a
site that looks made for them and runs their guest logistics without errors.
Existing Swiss tools cost CHF 50–200 and compete on price. **Hereby competes on finish.**

Three things make it different:

1. **Design is the product.** Every site is art-directed, and nothing ships with a visible bug.
2. **Every guest gets a personal site.** One private link per household shows only their events, names and options.
3. **The venue is built in.** Floor plans, menus, rooms and transport are preloaded, and the venue gets final numbers without a single email.

## Who it's for

- **Couples** with 60+ guests, a budget above CHF 30,000, and guests in more than one language or country.
- **Venues** hosting 15+ weddings a year. They are the second customer and the distribution channel.

| Area              | Standard tools                | Hereby                                                       |
| ----------------- | ----------------------------- | ------------------------------------------------------------ |
| Design            | A template with a colour swap | Art-directed per couple: typography, motion, photos graded to one look |
| Guest access      | One public link, maybe a password | A personal link per household                            |
| Setup             | Do it yourself                | Done for the couple within 48h of a 20-minute intake         |
| Support           | Email                         | A named person, reachable by WhatsApp                        |
| Venue             | An address text field         | The venue's real floor plans, menus, rooms and transport     |
| After the wedding | The site expires              | The site becomes a keepsake                                  |

## Five rules the product never breaks

These are product requirements. Every spec must say how it respects them.

1. **A guest answers in under 60 seconds on a phone**, with no account and no app.
2. **Every page loads in under 2 seconds on mobile data.**
3. **Guests never see ads or vendor offers.** A venue gets one credit line in the footer.
4. **Guest data stays in Switzerland** and is deleted on a fixed schedule.
5. **Fewer features, all finished.** A feature with a known bug is switched off, not shipped.

## What we don't build

| Not building                        | Why                                                                |
| ----------------------------------- | ------------------------------------------------------------------ |
| A free tier                         | Competing on free loses. Offer a live demo instead.                |
| A full wedding planner              | Checklists and budgets add screens without adding beauty.          |
| A vendor marketplace with ads       | Breaks the "no offers" rule and turns the product into a directory. |
| A native guest app                  | Guests won't install an app for one day. Web plus a wallet pass covers it. |
| A fully open design editor          | Total freedom produces ugly sites. Curated choices keep the standard. |
| Generated couples or invented venues | Anything fake about real people or places damages trust.          |
| Holding gift money                  | May bring financial regulation. Money goes straight to the couple. |
| Our own livestream technology       | Embed an existing service.                                         |

## AI principles

AI is used where it saves the couple hours or answers a guest at midnight. It
never replaces something real, and **a person checks every output before guests see it.**

| Feature            | Guardrail                                                              |
| ------------------ | ---------------------------------------------------------------------- |
| Guest concierge    | Answers only from the couple's content. Unknown questions are passed on, never guessed. |
| Reply parsing      | The guest confirms the parsed RSVP before it counts.                   |
| Story writer       | The couple edits and approves.                                         |
| Translation        | A native speaker reviews invitation and RSVP wording once per language. |
| Seating draft      | A starting point only; the couple decides.                             |
| Design from a mood | A designer approves every site.                                        |
| Photo curation     | No face recognition without each guest's consent.                      |
| Thank-you drafts   | The couple sends nothing unread.                                       |
