# Kitchen and service sheets

**Status:** Implemented 2026-10-09 (decisions taken by the build). Roadmap: 1.12

## Problem

"The caterer wants a CSV; retyping is failure" (setup spec research). The
couple has to hand the venue final numbers: how many come to each part,
which menus, how many children, and who has allergies.

## Decisions

1. **"Küche & Service" page** in the app: per part of the day
   - people coming, children among them, and anyone still unanswered
   - menu counts, children's menu included
   - household totals: shuttle seats, overnight stays, song wishes

   It also counts people with allergies, but shows no names and no text.
2. **Printable sheet** (`…/kueche/blatt`), rendered on the server as a plain
   HTML document designed for A4. It has the numbers, then the allergy list:
   name, household, which parts they attend, the note. "Drucken oder als PDF
   sichern" uses the browser. No PDF library, so no new dependency.
3. **Excel export** (`…/kueche/gaeste.csv`): one row per person, with each part's
   answer and menu, the allergy note and the household answers. UTF-8 with
   BOM and `;` separators, so Swiss Excel opens it directly.
4. **Privacy:**
   - `dietary_notes` never travels in Inertia props (CLAUDE.md rule 9).
   - It appears only in the two server-rendered documents, and only for the owner.
   - Both responses are `no-store` and never logged.

## Acceptance criteria (tests)

- [x] Counts per part and menu match the answers; children and unanswered are counted
- [x] The page's props carry no allergy text
- [x] The printable sheet and the CSV include allergy notes, for the owner only
- [x] The CSV opens in Excel: BOM, `;` separators, one row per person
