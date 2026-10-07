# Roadmap

> The single source of truth for what gets built. Product context:
> [vision](../product/vision.md) · [features](../product/features.md) ·
> [business](../product/business.md). Design: [DESIGN.md](../../DESIGN.md).

## How to read this

- **ID** (`1.4`): phase, then item. Refer to items by ID in branches, specs,
  plans, commits and `/feature 1.4`.
- **Status:** `[x]` done · `[~]` in progress · `[ ]` planned · `[?]` blocked on a product decision.
- **Owner:** `code` (built in this repo, goes through the AI workflow) or
  `human` (outside the repo: sales, research, legal, design hires).
- **Done when:** the acceptance check. An item is ticked only when every part
  of it is true *and* the [Definition of Done](#definition-of-done) holds.
- Items with a spec or plan link them inline: `spec · plan`.
- **Gates:** no phase opens on a date alone. A phase opens when the previous
  gate is met and couples or venues have paid.

## Now and next

| Now  | Next | Blocked |
| ---- | ---- | ------- |
| 0.2 Demo site for one real venue (`code`) · 0.3 demo-request form (`code`) | 0.3 Impressum & Datenschutz (`human` + `code`) | 0.6 designer, 0.8 hosting (`human`) |

`/roadmap` recomputes this table from the statuses below.

## Definition of Done

Every `code` item, before it is ticked:

1. Spec in `docs/specs/` and plan in `docs/plans/` (skip only for single-file fixes).
2. Tests cover the behaviour; `composer ci:check` passes.
3. UI follows `DESIGN.md`; a `/design-review` on the diff is clean; screenshots at 375px and 1440px.
4. Respects the five product rules in `CLAUDE.md` (the spec says how).
5. Guest-facing strings go through `t()` in every active locale.
6. This file is updated in the same PR: status, links, changelog row.

---

## F · Engineering foundation ✅ (October 2026)

| ID   | Item | Owner | Status |
| ---- | ---- | ----- | ------ |
| F.1  | Laravel 13 + Inertia v3 + Vue 3 starter kit (Fortify auth, 2FA, passkeys, Wayfinder) | code | [x] |
| F.2  | ~~Brand identity: ink / paper / seal~~ ([ADR 0002](../decisions/0002-design-identity.md), superseded by F.10) | code | [x] |
| F.3  | Modular Tailwind v4 theme with `@apply` component classes; GSAP motion foundation ([ADR 0003](../decisions/0003-motion.md)) | code | [x] |
| F.4  | AI workflow: CLAUDE.md, skills, MCP, slash commands, docs | code | [x] |
| F.5  | Domain model: wedding, event, household, guest, response ([spec](../specs/2026-10-06-domain-foundation-design.md)) | code | [x] |
| F.6  | Personal household link (`/i/{token}`): event-aware, noindex, throttled, "opened" tracked ([ADR 0005](../decisions/0005-personal-links.md)) | code | [x] |
| F.7  | Locale per household (de-CH, fr, it, en) with shared translations ([ADR 0006](../decisions/0006-i18n.md)) | code | [x] |
| F.8  | Per-wedding themes scoped to the guest site, with 3 starter themes ([ADR 0004](../decisions/0004-wedding-themes.md)) | code | [x] |
| F.9  | Data protection basics: encrypted dietary notes, deletion 12 months after the wedding ([ADR 0007](../decisions/0007-data-protection.md)) | code | [x] |
| F.10 | ~~Design identity "Something blue"~~ ([ADR 0008](../decisions/0008-something-blue.md), superseded by F.12) | code | [x] |
| F.11 | AI setup v2: design skills (Impeccable, Taste, UI/UX Pro Max, Web Interface Guidelines), `PRODUCT.md`, hooks, roadmap with IDs ([skills](../ai/skills.md)) | code | [x] |
| F.12 | Design identity "Vorhang auf": Archivo, night & champagne, photography, three.js veil and rings, scroll-driven sections (acts, theme studio, strike-through, signed footer) ([ADR 0009](../decisions/0009-vorhang-auf.md), [DESIGN.md](../../DESIGN.md)) | code | [x] |

## Phase 0 · Validate (Oct–Dec 2026)

**Goal:** prove that couples and venues pay before any platform exists.

| ID  | Item | Owner | Status | Done when |
| --- | ---- | ----- | ------ | --------- |
| 0.1 | Visit TrauDich! Zurich (17–18 Oct): venues, planners, couples | human | [ ] | 15+ conversations logged; 5 venue contacts with a follow-up date |
| 0.2 | **One demo site for one real venue**, by hand, at full visual quality, on this codebase | code | [ ] | A real venue's events, rooms and transport on the guest page; opens < 2s on 4G; venue has seen it |
| 0.3 | Landing page in German for the demo and the prepay ask ([analysis](../marketing/landing-page.md)) | code | [~] | `/` in de-CH with a working demo request form (stored, emailed to us); no English left on the page. **Done:** German page, SEO, FAQ data. **Open:** demo-request form, company details in Impressum/Datenschutz (drafts at /impressum and /datenschutz) and legal review, production domain |
| 0.4 | Interview 20 engaged couples; ask 5 to prepay | human | [ ] | 20 interview notes in a shared doc; prepay link sent to every fit |
| 0.5 | Pitch 10 venues with the demo and the preview-on-the-tour idea | human | [ ] | 10 pitches done; objections listed; 2 pilot agreements signed |
| 0.6 | `[?]` Find the designer who sets the look; their themes replace the starter themes | human | [?] | Designer contracted; scope of the first 3 themes agreed |
| 0.7 | Test the five competing tools hands-on and list their bugs | human | [ ] | One page per tool: price, RSVP time on a phone, bugs with screenshots |
| 0.8 | `[?]` Swiss hosting provider chosen ([ADR 0007](../decisions/0007-data-protection.md)) | human | [?] | Provider chosen, data-processing agreement signed, ADR updated |

**Gate →** 5 couples prepaid at CHF 390+, and 2 venues signed for a pilot.

## Phase 1 · Concierge MVP (Jan–Mar 2027)

**Goal:** 10–15 sites live for summer 2027, built by hand on one shared codebase.

| ID   | Item | Owner | Status | Done when |
| ---- | ---- | ----- | ------ | --------- |
| 1.1  | Couple account owns weddings; internal admin to set up sites | code | [ ] | An admin creates a wedding, events and households for a couple; the couple signs in and sees only theirs |
| 1.2  | Invitation content blocks (story, venue, dress code, FAQ) on the personal link | code | [ ] | Blocks render per household and per event access, in the household's language |
| 1.3  | Opening sequence (skippable, reduced-motion safe) | code | [ ] | Skippable in one tap; never blocks the RSVP; static under reduced motion; still < 2s |
| 1.4  | **60-second RSVP**: per person and event, menu, allergies, children's meal, shuttle, stay, song; asks only what applies | code | [ ] | Median completion < 60s in a timed test with 5 people on phones; no account; dietary notes encrypted |
| 1.5  | Edit until the deadline, with an email summary and a calendar (.ics) entry | code | [ ] | Answers editable until the deadline, locked after; summary email + .ics sent on every save |
| 1.6  | Language per guest: German and English content | code | [ ] | Every guest string in de-CH and en, native-reviewed |
| 1.7  | Collection of **three designs** (designer-made themes) | code | [ ] | 3 themes from the designer replace Alpine/Riviera; each passes WCAG AA |
| 1.8  | Travel and stay pages, filled in by hand | code | [ ] | Transport, parking, shuttle and room blocks per wedding, shown per event access |
| 1.9  | Couple dashboard: today view, live counts, household list (+ Excel import) | code | [ ] | Import of a 120-row Excel works; counts match the database; "what needs me today" on top |
| 1.10 | Smart reminders 14 / 7 / 2 days before the deadline (email first) | code | [ ] | Queued per household that hasn't answered; contain the personal link; opt-out respected |
| 1.11 | "Opened but not answered" segment | code | [ ] | Dashboard separates never opened / opened, not answered / answered |
| 1.12 | Kitchen and service sheets as an export | code | [ ] | PDF/Excel export: counts per menu, allergies per table, children |
| 1.13 | Performance budget enforced: guest pages < 2s on mobile data | code | [ ] | CI check (Lighthouse, throttled 4G) fails the build above the budget |
| 1.14 | Legal review of guest data before the first paid wedding | human | [ ] | Written sign-off from a Swiss data-protection lawyer |

**Gate →** 10 sites live, and 85% of guests answer without a manual chase.

## Phase 2 · Season pilot (Apr–Sep 2027)

**Goal:** deliver 15 weddings without a single failure on the day. Each item
needs an approved spec with its own acceptance criteria before work starts.

| ID  | Item | Owner | Status |
| --- | ---- | ----- | ------ |
| 2.1 | Day-of mode: live programme, find my table, announcements (works offline) | code | [ ] |
| 2.2 | Photo wall with live slideshow, message capsule, one gallery | code | [ ] |
| 2.3 | Seating on the real floor plan | code | [ ] |
| 2.4 | Venue dashboard: profile, wedding calendar, final-numbers and seating handoff | code | [ ] |
| 2.5 | Broadcast, roles and witness mode | code | [ ] |
| 2.6 | Change log of guest answers | code | [ ] |
| 2.7 | French and Italian (native review per language) | code | [ ] |
| 2.8 | Monitoring and on-call runbook for every wedding Saturday | code | [ ] |

**Gate →** 8 of 10 couples would recommend it, and 3 venues pay to continue.

## Phase 3 · Productise (Oct 2027–Mar 2028)

**Goal:** cut setup to under 2 hours per site and sell the 2028 season.

| ID  | Item | Owner | Status |
| --- | ---- | ----- | ------ |
| 3.1 | Self-serve builder with the design studio and design from a mood | code | [ ] |
| 3.2 | AI: guest concierge, reply by message, story writer, translation, seating draft ([guardrails](../product/vision.md#ai-principles)) | code | [ ] |
| 3.3 | Preview on the tour, venue film and enquiries (Showcase venues) | code | [ ] |
| 3.4 | Gifts by TWINT and QR code (no money held) | code | [ ] |
| 3.5 | Matching print exports and wallet pass | code | [ ] |
| 3.6 | Door-to-door travel from public transport data | code | [ ] |
| 3.7 | Photographer and planner accounts | code | [ ] |
| 3.8 | Billing for the couple tiers and venue plans | code | [ ] |

**Gate →** 100 couples booked for 2028, and 10 paying venues.

## Phase 4 · Scale (from Apr 2028)

| ID  | Item | Owner | Status |
| --- | ---- | ----- | ------ |
| 4.1 | Romandie and Ticino, with native reviewers for every text | human | [ ] |
| 4.2 | Thank-you assistant, keepsake book and anniversary | code | [ ] |
| 4.3 | Photo curation, cost forecast, plan B and carpool board | code | [ ] |
| 4.4 | Referral views for other partners (only if the Phase 0 two-shop test worked) | code | [ ] |
| 4.5 | Neighbouring regions, and other events at partner venues | human | [ ] |

**Decision point →** at 300 couples a season and 30 venues: hire, or stay a small studio.

---

## Changelog

| Date       | Change |
| ---------- | ------ |
| 2026-10-06 | Engineering foundation; roadmap replaced with the wedding-platform plan |
| 2026-10-07 | Landing round 4: floating pill nav, theme deck with arrows and Lavanda, hand-drawn strikes and "Ja." swoosh, left-aligned GSAP footer, German sign-in/up (incl. validation), draft Datenschutz and Impressum |
| 2026-10-07 | Landing round 3: realistic veil shader, theme deck (+ Rosé theme), pop-out icons, "Fragt uns" question form (stored + mailed), GSAP footer wordmark, animated sign-in/sign-up |
| 2026-10-07 | F.12 revised after founder review: Archivo only (Bodoni dropped); acts, marquee, theme studio, strike-through, closing veil and signed footer replace the generic second half |
| 2026-10-07 | F.12 "Vorhang auf" (ADR 0009) replaces "Something blue" after founder review; 0.3 German landing page with SEO/UX analysis, three.js veil and rings |
| 2026-10-07 | F.10 "Something blue" design identity (ADR 0008) replaces ink/paper/seal; F.11 AI setup v2; roadmap gets IDs, owners, "done when" checks and a Definition of Done |
