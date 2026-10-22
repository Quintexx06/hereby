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
| 0.2 Demo site for one real venue: last connections from the SBB timetable (`code`) | 0.3 street address and providers in Impressum & Datenschutz, then legal review (`human`) | 0.8 confirm hosting location and sign the data-processing agreements (`human`) |

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
| 0.3 | Landing page in German for the demo and the prepay ask ([analysis](../marketing/landing-page.md)) | code | [~] | `/` in de-CH whose main action works; no English left on the page. **Done:** German page, SEO, FAQ data; the main action is «Kostenlos starten» to sign-up (build free, pay to send), so no demo-request form. Operator (Martin Brunner, Zürich, info@hereby.ch) filled in and the privacy policy covers every feature built so far; live on Coolify. **Open:** street address and PLZ, hosting and mail provider with location, log retention, then the legal review |
| 0.4 | Interview 20 engaged couples; ask 5 to prepay | human | [ ] | 20 interview notes in a shared doc; prepay link sent to every fit |
| 0.5 | Pitch 10 venues with the demo and the preview-on-the-tour idea | human | [ ] | 10 pitches done; objections listed; 2 pilot agreements signed |
| 0.6 | ~~Find the designer who sets the look~~: the founder accepted the current templates and themes for launch (2026-10-14); a designer stays optional | human | [x] | Designer contracted; scope of the first 3 themes agreed |
| 0.7 | Test the five competing tools hands-on and list their bugs | human | [ ] | One page per tool: price, RSVP time on a phone, bugs with screenshots |
| 0.8 | Swiss hosting provider chosen ([ADR 0007](../decisions/0007-data-protection.md)); the app runs live on Coolify, provider and location to confirm | human | [~] | Provider chosen, data-processing agreement signed, ADR updated |

**Gate →** 5 couples prepaid at CHF 390+, and 2 venues signed for a pilot.

## Phase 1 · Concierge MVP (Jan–Mar 2027)

**Goal:** 10–15 sites live for summer 2027, built by hand on one shared codebase.

| ID   | Item | Owner | Status | Done when |
| ---- | ---- | ----- | ------ | --------- |
| 1.1  | Couple account owns weddings; internal admin to set up sites | code | [x] | An admin creates a wedding, events and households for a couple; the couple signs in and sees only theirs |
| 1.2  | Invitation content blocks (story, venue, dress code, FAQ) on the personal link | code | [x] | Blocks render per household and per event access, in the household's language |
| 1.3  | Opening sequence (skippable, reduced-motion safe) | code | [x] | Skippable in one tap; never blocks the RSVP; static under reduced motion; still < 2s |
| 1.4  | **60-second RSVP**: per person and event, menu, allergies, children's meal, shuttle, stay, song; asks only what applies | code | [~] | Median completion < 60s in a timed test with 5 people on phones; no account; dietary notes encrypted |
| 1.5  | Edit until the deadline, with an email summary and a calendar (.ics) entry | code | [x] | Answers editable until the deadline, locked after; summary email + .ics sent on every save |
| 1.6  | Language per guest: German and English content | code | [ ] | Every guest string in de-CH and en, native-reviewed |
| 1.7  | Collection of **three designs**: the three templates (Atelier, Garten, Panorama) with five themes, accepted by the founder for launch (2026-10-14) | code | [x] | Each passes WCAG AA; a designer collection can replace them later |
| 1.8  | Travel and stay pages, filled in by hand | code | [x] | Transport, parking, shuttle and room blocks per wedding, shown per event access |
| 1.9  | Couple dashboard: today view, live counts, household list (+ Excel import) | code | [x] | Import of a 120-row Excel works; counts match the database; "what needs me today" on top |
| 1.10 | Smart reminders 14 / 7 / 2 days before the deadline (email first) | code | [x] | Queued per household that hasn't answered; contain the personal link; opt-out respected |
| 1.11 | "Opened but not answered" segment | code | [x] | Dashboard separates never opened / opened, not answered / answered |
| 1.12 | Kitchen and service sheets as an export | code | [x] | PDF/Excel export: counts per menu, allergies per table, children |
| 1.13 | Performance budget enforced: guest pages < 2s on mobile data | code | [x] | CI check (Lighthouse, throttled 4G) fails the build above the budget |
| 1.14 | Legal review of guest data before the first paid wedding (incl. table mates and kept memories, ADR 0011) | human | [ ] | Written sign-off from a Swiss data-protection lawyer |
| 1.15 | «Der Abend» for Atelier: table mates, way home, group photo call-up, programme sign-ups with the witness link ([spec](../specs/2026-10-13-evening-and-extras-design.md)) | code | [x] | Gated on Atelier, tested; guest strings in all four languages; nothing before the reply |
| 1.16 | Extras: wedding newspaper, «Wir fassen nach», own domain, keep the memories ([spec](../specs/2026-10-13-evening-and-extras-design.md), [ADR 0011](../decisions/0011-kept-memories.md)) | code | [x] | Bought through Stripe or included in Couture; guest data still deleted on schedule |
| 1.18 | «Massanfertigung» and more freedom in the three templates: own accent colour, sections to leave out, a brief the team builds from ([spec](../specs/2026-10-15-bespoke-and-design-freedom.md), [ADR 0013](../decisions/0013-own-accent-colour.md)) | code | [x] | Brief sent and tracked by the team; any colour keeps AA; nothing a guest needs to answer can be hidden |
| 1.17 | «Eckdaten»: names, date, deadline, venue, programme, languages and look editable after setup ([spec](../specs/2026-10-15-wedding-details-design.md)) | code | [x] | Each saves on its own; a moved date moves the programme; editing the programme keeps answers |

**Gate →** 10 sites live, and 85% of guests answer without a manual chase.

## Phase 2 · Season pilot (Apr–Sep 2027)

**Goal:** deliver 15 weddings without a single failure on the day. Each item
needs an approved spec with its own acceptance criteria before work starts.

| ID  | Item | Owner | Status |
| --- | ---- | ----- | ------ |
| 2.1 | Day-of mode: live programme, find my table, announcements (works offline) | code | [ ] |
| 2.2 | Photo wall with live slideshow, message capsule, one gallery ([proposal](../specs/2026-10-12-paywall-and-memories-design.md)) | code | [ ] |
| 2.3 | Seating on the real floor plan | code | [ ] |
| 2.4 | Venue dashboard: profile, wedding calendar, final-numbers and seating handoff ([first cut](../specs/2026-10-15-partner-accounts-design.md): profile entered once, calendar, final-numbers sheet; seating on the plan and sign-off open) | code | [~] |
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
| 3.3 | Preview on the tour, venue film and enquiries (Showcase venues) ([first cut](../specs/2026-10-15-partner-accounts-design.md): tour preview with the venue's photos, public page, enquiries, one credit line; venue film open) | code | [~] |
| 3.4 | Gifts by TWINT and QR code (no money held) | code | [ ] |
| 3.5 | Matching print exports and wallet pass | code | [ ] |
| 3.6 | Door-to-door travel from public transport data | code | [ ] |
| 3.7 | Photographer and planner accounts ([first cut](../specs/2026-10-15-partner-accounts-design.md): photographers upload into the couple's memories, planners see calendar and final numbers; planners editing a site open) | code | [~] |
| 3.8 | Billing for the couple tiers and venue plans ([spec](../specs/2026-10-12-paywall-and-memories-design.md): build free, pay to send; couple tiers done, venue plans open) | code | [~] |

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
| 2026-10-22 | Production image: PHP's gd and exif are installed (the base image has neither, so no photo could be processed), `memory_limit` is 512M, and `WebImage` opens a photo only when it fits in the memory left (at most 50 MP), scaling before it turns it. Too large photos are refused instead of crashing the request or the queue worker |
| 2026-10-15 | 1.18 done: couples pick an own accent colour (any hue, kept AA on the theme ladder, ADR 0013) and leave out the opening, the calendar section, ornaments or the closing call (Look › Feinschliff). New product «Massanfertigung» (CHF 690 placeholder, included in Couture): a brief with wishes, colours, mood, links and up to twelve inspiration images; the team's list with status and a note to the couple. Venues upload photos that lead their public page and the tour preview |
| 2026-10-15 | First cut of partner accounts (2.4, 3.3, 3.7): the team opens venue, photographer and planner accounts on «Partner»; couples connect them with the partner's code on their own «Partner» page and can let them go. Partners get a calendar of connected weddings (counts and menus, no names), venues and planners print the final numbers, photographers upload into the couple's memories, venue profiles fill an empty venue and the travel and rooms blocks. Showcase venues add a tour preview, a public page with tour requests (deleted after 12 months) and the one credit line in the guest footer |
| 2026-10-15 | Every couple's site ends in a footer: the couple's names, who guests ask (name, mail, phone, set under «Fusszeile» on «Inhalte»), the credit line and a link to the privacy policy on the main domain. Landing studio: the windows stand still, a «Ganze Seite ansehen» pill rises on hover, and a click opens the whole site in a popup that grows from the click |
| 2026-10-15 | 1.17 done: «Eckdaten» in the sidebar and on the dashboard reopens the setup questions for a live wedding, one tab at a time. Editing the programme updates events in place (answers stay, new parts go to every household), a new date moves the whole programme. Landing studio: each template gets its own body under the hero (magazine, garden with hand-drawn frames, cinema) |
| 2026-10-14 | The founder accepted the current templates and themes for launch (0.6, 1.7 closed). Impressum and Datenschutz filled in for Martin Brunner, Zürich, and extended to payments, photos, newspaper, phone numbers, table notes, the chase service and kept memories. Plan cards promise only what is built: the photo wall, voice wishes and film came off until they exist (rule 5) |
| 2026-10-13 | 0.2 built (not yet shown): demo site at the real Seehotel Kastanienbaum on Lake Lucerne for a fictional couple, Lea & Jonas (`KastanienbaumDemoSeeder`, spec 2026-10-13-venue-demo-kastanienbaum). Verified arrival (train, bus 21, summer boat to the hotel's landing stage, free parking), rooms, four parts of the day at the hotel, households in German, French and English with tables. LCP 1.2–1.3 s on throttled 4G. Open before showing it: the night's last connections from the SBB timetable, and the venue seeing it |
| 2026-10-13 | Couple photos get a focal point (tap the faces; every template crops around it). The studio carousel steps reliably on fast clicks and slow devices (`scrollend`, aim-based steps); Panorama's rule lines up with its photos; shadcn's sidebar components removed. Checked end to end in the running app: sign-in, upload, focus, cover, preview, a personal link, the landing carousel |
| 2026-10-13 | Guest sites look like the landing studio: couples upload up to four photos in «Inhalte» (web copies on the Swiss `memories` disk, served by unguessable token, gone with the wedding), the templates take the landing's heroes (Atelier over the cover, Garten under an arch, Panorama as a night band over a strip), and every theme is an accent on porcelain (ADR 0012). App: own nav instead of shadcn's sidebar (no layout jump on zoom), a scroll rail instead of the native page scrollbar, tidier Küche & Service, Extras with a scene per extra |
| 2026-10-13 | 1.15 and 1.16 done: Atelier becomes «the evening runs itself» (Tischplan with a line per guest for their table mates, Heimweg with a countdown and a mail 30 min before the last connections, Gruppenfotos called by the witnesses, Einlagen offered after the reply and run by the witnesses on their private link, the couple sees only counts). Extras on any plan through Stripe: Hochzeitszeitung (contribution link, printed by the witnesses), «Wir fassen nach» (team list with phone numbers), own domain (personal links on it, only its guests answer there), «Erinnerungen behalten» per year with guest data still deleted on schedule (ADR 0011) |
| 2026-10-13 | Landing: visitors type their names and date into the studio's example site (hero, monogram, URL follow; nothing stored). The main action is «Kostenlos starten» everywhere instead of «Demo anfragen», which led to sign-up anyway; 0.3 no longer needs a demo-request form. Header wordmark replays the footer's entrance on hover |
| 2026-10-13 | Landing studio becomes a full example wedding site with photos; accent chips recolour only accents (no section re-theme, no auto-switching tabs) |
| 2026-10-12 | 3.8 (couples): building is free, a plan opens the personal links. «Freischalten» page with Signature CHF 290, Atelier CHF 590 (memories), Couture from CHF 1,490 on request; Stripe Checkout in CHF (cards, TWINT) without an SDK, confirmed by a signed webhook and on return; upgrades pay the difference; the team unlocks invoices in the admin. Closed links answer 404 to guests (the couple can still try them) and send no reminders; weddings already live became Signature. Guest photos get a private `memories` disk on the server's volume in Switzerland |
| 2026-10-12 | Landing studio as a wide browser window with big template tabs and three colour dots. App: page transitions (View Transitions on Inertia visits), sidebar wordmark letters drop on hover, masthead scenes fade out above the rule, the flutes no longer overlap. Team «Hochzeiten» in one column: «Paar anlegen» in the masthead (side sheet), first steps as a strip, weddings as a table. Proposal for the paywall and «Erinnerungen» (photos, voice wishes, film) awaiting approval |
| 2026-10-12 | Micro-interactions across the app and guest pages (press, switch, arrow, swap-to-tick, count-up, list settle, page fade, a petal burst after a guest's yes; see DESIGN.md). Guest translations ship only to guest pages; unused header layout removed; Panorama's sideways rows reachable by keyboard. Budget measured on the production image with Lighthouse (mobile, slow 4G): Atelier LCP 1.79 s, Garten 1.80 s, Panorama 1.63 s, all ~161 KB, TBT < 100 ms, CLS 0 |
| 2026-10-12 | Guest-site templates (layout and motion, independent of the colour theme): Atelier (editorial, story as a drawn path), Garten (arched hero with hand-drawn SVG branches, story in garden gates), Panorama (running title over three.js bokeh, the day as a pinned horizontal scroll, story as a film strip). GSAP and three.js load on the guest's first interaction, so the first paint and the load budget carry neither (rule 2) |
| 2026-10-09 | The sidebar appears only once a wedding is active (or for the team); before that a slim top bar. Dashboard: a hero band with names, date, venue and countdown, four numbers, cards for today's tasks and shortcuts to every area. Guest site: stacked names with a turning save-the-date seal and countdown, the reply button right under it, the day as an illustrated timeline with route links, botanical dividers, and «Haltet euch den Tag frei» for every guest (.ics, Google Calendar, webcal subscription that refreshes twice a day) |
| 2026-10-09 | Setup polish: the app is light only (dark mode and «Darstellung» removed); the programme step lays parts of the day out as timeline cards across the full width; the look step adds style directions (up to three of twelve) and a free-text idea that brief the team's hand-tuning and show on the admin's wedding cards |
| 2026-10-09 | 1.1 follow-up: the team area gets its own sidebar section (Hochzeiten, Anfragen with an open-count badge, Website ansehen); landing-page questions are read and marked answered under «Anfragen»; the Hochzeiten page gains a candlelit hero with the roses, live counts, a first-visit tour and a starter checklist derived from real data |
| 2026-10-09 | 1.1 done: the team's admin ("Hochzeiten"): every wedding at a glance, and setting one up for a couple, who gets a link to choose a password; admins edit on the couple's own pages (Gate::before). Admin only via `php artisan hereby:make-admin` |
| 2026-10-09 | 1.13 done: guest pages measured at LCP ~1.2 s on Lighthouse slow 4G (was 5.9 s uncompressed, 3.0 s gzipped): SSR, hydration after first frame, a lean inlined guest stylesheet, weight-only font, lazy layouts; a CI job fails above the budget ([frontend](../architecture/frontend.md#guest-pages-and-the-two-second-budget-roadmap-113)). Production must run the SSR server |
| 2026-10-09 | 1.8 done: "Anreise" and "Übernachten" blocks (hotels with details and a link), per event access and language like every content block; shuttle seats are asked in the reply (1.4) |
| 2026-10-09 | 1.3 done: the invitation opens behind a sheer veil in the couple's theme that parts after a beat (about 1.4s, CSS only); once per link, any tap or key skips, nothing under reduced motion |
| 2026-10-09 | 1.12 done: "Küche & Service" with counts per part, menu and children; a printable A4 sheet with allergies (server-rendered, owner only) and a CSV for Excel. No PDF library: the browser prints to PDF; per-table counts follow with seating (2.3) ([spec](../specs/2026-10-09-kitchen-sheets-design.md)) |
| 2026-10-09 | 1.10 done: reminders 14, 7 and 2 days before the deadline to unanswered households with an address, from the couple by name, once per stage, with one-click opt-out; couples switch them off on the Antwortformular ([spec](../specs/2026-10-09-reminders-design.md)) |
| 2026-10-09 | 1.5 done: replies stay editable until the deadline; every save mails a summary from the couple by name (when the guest leaves an address) with the calendar attached, and the invitation offers "In den Kalender" ([spec](../specs/2026-10-09-reply-confirmation-design.md)) |
| 2026-10-09 | 1.2 done: story, venue (with route link), dress code and FAQ on the personal link, per event access and in the household's language with fallback; the couple's "Inhalte" editor and a preview in every language ([spec](../specs/2026-10-09-invitation-content-design.md)). Konto gets the account at a glance |
| 2026-10-09 | 1.4 in progress: the reply form on the personal link (per person and part of the day, menus, children's menu, write-only allergies, named plus-one, shuttle/stay/song) and the couple's Antwortformular; code and tests done, the timed test with 5 people on phones is open ([spec](../specs/2026-10-09-rsvp-design.md)). Design pass over dashboard, guests and account (Swiss German everywhere, 44px targets, "Eure Einladung" panel) |
| 2026-10-09 | Sidebar as a night stage with the couple's names and countdown; setup scenes that change per step with a story line; household editor (people, language, +1, invited parts, renew link, remove) |
| 2026-10-09 | 1.9, 1.11 done; 1.1 in progress (couples own weddings and set them up in a 7-step guided setup with a live invitation preview; internal admin still open). Swiss address search (ADR 0010), guest import by paste, Excel, CSV and contacts with a preview ([spec](../specs/2026-10-09-couple-setup-and-dashboard-design.md)) |
| 2026-10-06 | Engineering foundation; roadmap replaced with the wedding-platform plan |
| 2026-10-07 | Landing round 4: floating pill nav, theme deck with arrows and Lavanda, hand-drawn strikes and "Ja." swoosh, left-aligned GSAP footer, German sign-in/up (incl. validation), draft Datenschutz and Impressum |
| 2026-10-07 | Landing round 3: realistic veil shader, theme deck (+ Rosé theme), pop-out icons, "Fragt uns" question form (stored + mailed), GSAP footer wordmark, animated sign-in/sign-up |
| 2026-10-07 | F.12 revised after founder review: Archivo only (Bodoni dropped); acts, marquee, theme studio, strike-through, closing veil and signed footer replace the generic second half |
| 2026-10-07 | F.12 "Vorhang auf" (ADR 0009) replaces "Something blue" after founder review; 0.3 German landing page with SEO/UX analysis, three.js veil and rings |
| 2026-10-07 | F.10 "Something blue" design identity (ADR 0008) replaces ink/paper/seal; F.11 AI setup v2; roadmap gets IDs, owners, "done when" checks and a Definition of Done |
