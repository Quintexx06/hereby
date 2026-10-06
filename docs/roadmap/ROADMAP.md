# Roadmap

> The single source of truth for what gets built. Tick items off **in the PR
> that ships them**. Larger items get a spec in `docs/specs/` and a plan in
> `docs/plans/`; link both from the item. Product context: [docs/product/](../product/vision.md).

**Status:** `[x]` done · `[~]` in progress · `[ ]` planned · `[?]` needs a product decision

**Rule:** no phase opens on a calendar date alone. Each phase opens only when the
previous gate is met, and couples or venues have paid.

---

## Engineering foundation ✅ (October 2026)

- [x] Laravel 13 + Inertia v3 + Vue 3 starter kit (Fortify auth, 2FA, passkeys, Wayfinder)
- [x] Brand identity: ink / paper / seal; Fraunces, Schibsted Grotesk and Martian Mono ([ADR 0002](../decisions/0002-design-identity.md))
- [x] Modular Tailwind v4 theme with `@apply` component classes; GSAP motion foundation ([ADR 0003](../decisions/0003-motion.md))
- [x] AI workflow: CLAUDE.md, skills, MCP, slash commands, docs
- [x] Domain model: wedding, event, household, guest, response ([spec](../specs/2026-10-06-domain-foundation-design.md))
- [x] Personal household link (`/i/{token}`): event-aware, noindex, throttled, "opened" tracked ([ADR 0005](../decisions/0005-personal-links.md))
- [x] Locale per household (de-CH, fr, it, en) with shared translations ([ADR 0006](../decisions/0006-i18n.md))
- [x] Per-wedding themes scoped to the guest site, with 3 starter themes ([ADR 0004](../decisions/0004-wedding-themes.md))
- [x] Data protection basics: encrypted dietary notes, automatic deletion 12 months after the wedding ([ADR 0007](../decisions/0007-data-protection.md))

## Phase 0 · Validate (Oct–Dec 2026)

Goal: prove that couples and venues pay before any platform exists.

- [ ] Visit TrauDich! Zurich (17–18 Oct): talk to venues, planners and couples
- [ ] Build **one demo site for one real venue**, by hand, at full visual quality (on this codebase)
- [ ] Landing page in German for the demo and the prepay ask
- [ ] Interview 20 engaged couples; ask 5 to prepay
- [ ] Pitch 10 venues with the demo and the preview-on-the-tour idea
- [ ] `[?]` Find the designer who sets the look; their themes replace the starter themes
- [ ] Test the five competing tools hands-on and list their bugs
- [ ] `[?]` Swiss hosting provider chosen ([ADR 0007](../decisions/0007-data-protection.md))

**Gate →** 5 couples prepaid at CHF 390+, and 2 venues signed for a pilot.

## Phase 1 · Concierge MVP (Jan–Mar 2027)

Goal: 10–15 sites live for summer 2027, built by hand on one shared codebase.

- [ ] Couple account owns weddings (roles come in Phase 2); internal admin to set up sites
- [ ] Personal links and event-aware invitation page (foundation done; needs content blocks)
- [ ] Opening sequence (skippable, reduced-motion safe)
- [ ] **60-second RSVP**: per person and event, menu, allergies, children's meal, shuttle, stay, song; only asks what applies
- [ ] Edit until the deadline, with an email summary and a calendar (.ics) entry
- [ ] Language per guest: German and English content
- [ ] Collection of **three designs** (designer-made themes)
- [ ] Travel and stay pages, filled in by hand
- [ ] Couple dashboard: today view, live counts, household list (+ Excel import)
- [ ] Smart reminders 14 / 7 / 2 days before the deadline (email first)
- [ ] "Opened but not answered" segment
- [ ] Kitchen and service sheets as an export
- [ ] Performance budget enforced: guest pages < 2s on mobile data
- [ ] Legal review of guest data before the first paid wedding

**Gate →** 10 sites live, and 85% of guests answer without a manual chase.

## Phase 2 · Season pilot (Apr–Sep 2027)

Goal: deliver 15 weddings without a single failure on the day.

- [ ] Day-of mode: live programme, find my table, announcements (works offline)
- [ ] Photo wall with live slideshow, message capsule, one gallery
- [ ] Seating on the real floor plan
- [ ] Venue dashboard: profile, wedding calendar, final-numbers and seating handoff
- [ ] Broadcast, roles and witness mode
- [ ] Change log of guest answers
- [ ] French and Italian (native review per language)
- [ ] Monitoring and on-call runbook for every wedding Saturday

**Gate →** 8 of 10 couples would recommend it, and 3 venues pay to continue.

## Phase 3 · Productise (Oct 2027–Mar 2028)

Goal: cut setup to under 2 hours per site and sell the 2028 season.

- [ ] Self-serve builder with the design studio and design from a mood
- [ ] AI: guest concierge, reply by message, story writer, translation, seating draft (see the guardrails in [vision](../product/vision.md#ai-principles))
- [ ] Preview on the tour, venue film and enquiries (Showcase venues)
- [ ] Gifts by TWINT and QR code (no money held)
- [ ] Matching print exports and wallet pass
- [ ] Door-to-door travel from public transport data
- [ ] Photographer and planner accounts
- [ ] Billing for the couple tiers and venue plans

**Gate →** 100 couples booked for 2028, and 10 paying venues.

## Phase 4 · Scale (from Apr 2028)

- [ ] Romandie and Ticino, with native reviewers for every text
- [ ] Thank-you assistant, keepsake book and anniversary
- [ ] Photo curation, cost forecast, plan B and carpool board
- [ ] Referral views for other partners (only if the Phase 0 two-shop test worked)
- [ ] Neighbouring regions, and other events at partner venues

**Decision point →** at 300 couples a season and 30 venues: hire, or stay a small studio.

---

## Changelog

| Date       | Change                                                       |
| ---------- | ------------------------------------------------------------ |
| 2026-10-06 | Engineering foundation; roadmap replaced with the wedding-platform plan |
